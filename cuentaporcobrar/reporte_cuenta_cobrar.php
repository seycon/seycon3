<?php
ob_start();
include_once("../conexion.php");
include("../MPDF53/mpdf.php");
$mysql = new MySQL();
function set_margen_principal() {
    echo "<div class='margenPrincipal'></div>";
}
function get_tc($mysql) {
    $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
    $tc = $mysql->getCampo('dolarcompra', $sql);
    return $tc;
}
function setPie() {
    echo "<div class='session_pie'>
	<table width='93%' border='0' align='center'>
        <tr>
        <td width='120' align='right'></td>
        <td width='324'></td>
        <td width='93'>&nbsp;</td>
        <td width='189'>&nbsp;</td>
        <td width='201'>&nbsp;</td>
        <td width='170' >Impreso: " . date("d/m/Y") . "</td>
        <td width='130'>Hora:" . date("H:i:s") . "</td>
        </tr>
        </table>
        </div>";
}
function inicio_tabla() {
    echo "<table class='tabla_reporte' align='center'>";
}
function fin_tabla() {
    echo "</table>";
}
function set_deudor($deudor) {
    echo "<tr>
            <td class='td_deudor' colspan='5'>Deudor : $deudor</td>
           </tr>";
}
function set_titulos() {
    echo " <tr>
                <td class='td_titulo'>Fecha</td>
                <td class='td_titulo'>Nº Nota</td>
                <td class='td_titulo'>Importe Original</td>
                <td class='td_titulo'>Aportes</td>
                <td class='td_titulo'>Saldo Actual</td>
            </tr>";
}
function set_datos($fecha, $nota, $importe, $aporte, $saldo, $cebra) {
    $importe = number_format($importe, 2);
    $aporte = number_format($aporte, 2);
    $saldo = number_format($saldo, 2);

    if ($cebra % 2 == 0) {
        echo "<tr>
                <td class='td_fecha'>$fecha</td>
                <td class='td_nota'>$nota</td>
                <td class='td_importe'>$importe</td>
                <td class='td_aporte'>$aporte</td>
                <td class='td_saldo_actual'>$saldo</td>
            </tr>";
    } else {
        echo "<tr class='tr_relleno'>
                <td class='td_fecha'>$fecha</td>
                <td class='td_nota'>$nota</td>
                <td class='td_importe'>$importe</td>
                <td class='td_aporte'>$aporte</td>
                <td class='td_saldo_actual'>$saldo</td>
            </tr>  ";
    }
}
function set_totales($total) {
    $total = number_format($total);
    echo "         
        <table class='tabla_totales'>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class='td_titulo_total'>Total Saldo:</td>
                <td class='td_total'>$total</td>
            </tr>
        </table>
        ";
}
function get_datos_notaventa($mysql, $idcliente, $obj) {
    $consulta = "select Date_format( nv.fecha,'%d/%m/%Y') as 'fecha_nv' , nv.*,di.*,Date_format( i.fecha,'%d/%m/%Y')  as 'fecha_ingreso' 
                 from notaventa nv inner join detalleingreso di on di.idtransaccion=nv.idnotaventa 
                 inner join ingreso i on i.idingreso=di.idingreso 
                 where nv.estado=1 and i.estado=1 and di.transaccion='Nota Venta Producto' and nv.idcliente=$idcliente and i.idpersona=$idcliente and i.tipopersona='$obj' 
                 order by nv.idnotaventa,di.idtransaccion";    
    return $mysql->consulta($consulta);
}
function get_datos_ingresoproducto($mysql, $idnotaventa) {
    if ($idnotaventa != "") {
        $consulta = "select *,Date_format(fecha,'%d/%m/%Y') as 'fecha_ingreso' from ingresoproducto where facproveedor=$idnotaventa and tipoingreso='NotaVentaProducto' ";
    } else {
        $consulta = "select * from ingresoproducto where facproveedor=-1 and tipoingreso='??' ";
    }
    return $mysql->consulta($consulta);
}
function get_datos_cuentaporcobrar($mysql, $iddeudor, $obj) {
    $consulta = "select Date_format( cc.fecha,'%d/%m/%Y')  as 'fecha_cc',cc.*,di.*,Date_format( i.fecha,'%d/%m/%Y')  as 'fecha_ingreso' 
                 from cuentaporcobrar cc inner join detalleingreso di on di.idtransaccion=cc.idporcobrar 
                 inner join ingreso i on i.idingreso=di.idingreso 
                 where cc.estado=1 and i.estado=1 and di.transaccion='Cuenta Por Cobrar' and cc.iddeudor=$iddeudor and i.idpersona=$iddeudor and i.tipopersona='$obj'
                 order by cc.idporcobrar,di.idtransaccion";
    return $mysql->consulta($consulta);
}
function set_reporte($mysql, $iddeudor, $nombre_deudor, $obj, $tc) {
    $res_notaventa = get_datos_notaventa($mysql, $iddeudor, $obj);
    $res_cuentacobrar = get_datos_cuentaporcobrar($mysql, $iddeudor, $obj);
    $cont = 0;
    $saldo = 0;
    $cebra = 0;
    $monto = 0;
    $total = 0;
    $vesprim = 1;
    $idporcobrar = 0;
    set_margen_principal();
    inicio_tabla();

    while ($row = mysql_fetch_array($res_notaventa)) {

        if ($row['montoactualcredito'] < $row['credito']) {
            if ($vesprim == 1) {
                set_deudor($nombre_deudor);
                set_titulos();
                $saldo = $row['monto'] - $row['credito'];
                set_datos($row['fecha_nv'], 'V-' . $row['idnotaventa'], $row['monto'] / $tc, $row['credito'] / $tc, $saldo / $tc, $cebra++);
                $monto = $row['montobolivianos'] + ( $row['montodolares'] * $tc);
                $saldo-=$monto;
                set_datos($row['fecha_ingreso'], 'ID-' . $row['idingreso'], '0', $monto / $tc, $saldo / $tc, $cebra++);
                $idnotaventa = $row['idnotaventa'];
                $vesprim++;
            } else {
                if ($idnotaventa != $row['idnotaventa']) {
                    $res_ingresoprod = get_datos_ingresoproducto($mysql, $row['idnotaventa']);
                    while ($row1 = mysql_fetch_array($res_ingresoprod)) {
                        if ($vesprim == 1) {
                            set_titulos();
                            $saldo-=($row1['monto'] / $tc);
                            set_datos($row1['fecha_ingreso'], 'IP-' . $row1['idingresoprod'], $row1['monto'], '0.00', $saldo, $cebra++);
                            $vesprim++;
                        }
                        $saldo-=($row1['monto'] / $tc);
                        set_datos($row1['fecha_ingreso'], 'IP-' . $row1['idingresoprod'], $row1['monto'], '0.00', $saldo, $cebra++);

                        $cont++;
                        if ($cont > 25) {
                            fin_tabla();
                            inicio_tabla();
                            set_margen_principal();
                            setPie();
                            $cont = 0;
                        }
                    }
                    $total+=$saldo;
                    $cebra = 0;
                    echo "<tr><td colspan='5' style='border-top:1px solid;' >&nbsp;&nbsp;&nbsp;</td></tr>";
                    set_titulos();
                    $saldo = $row['monto'] - $row['credito'];
                    set_datos($row['fecha_nv'], 'V-' . $row['idnotaventa'], $row['monto'] / $tc, $row['credito'] / $tc, $saldo / $tc, $cebra++);
                    $monto = $row['montobolivianos'] + ( $row['montodolares'] * $tc);
                    $saldo-=$monto;
                    set_datos($row['fecha_ingreso'], 'ID-' . $row['idingreso'], '0', $monto / $tc, $saldo / $tc, $cebra++);
                    $idnotaventa = $row['idnotaventa'];
                } else {
                    $monto = $row['montobolivianos'] + ( $row['montodolares'] * $tc);
                    $saldo-=$monto;
                    set_datos($row['fecha_ingreso'], 'ID-' . $row['idingreso'], '0', $monto / $tc, $saldo / $tc, $cebra++);
                    $idnotaventa = $row['idnotaventa'];
                }
            }
            $cont++;
            if ($cont > 25) {
                fin_tabla();
                inicio_tabla();
                set_margen_principal();
                setPie();
                $cont = 0;
            }
        }
    }
    $total+=$saldo;
    $saldo = 0;


    while ($row2 = mysql_fetch_array($res_cuentacobrar)) {
        if ($row2['montoactualcobrado'] < $row2['monto']) {
            if ($vesprim == 1) {
                set_deudor($nombre_deudor);
                set_titulos();
                set_datos($row2['fecha_cc'], 'CC-' . $row2['idporcobrar'], $row2['monto'] / $tc, '0.00', $row2['monto'] / $tc, $cebra++);
                $monto = $row2['montobolivianos'] + ( $row2['montodolares'] * $tc);/*aqui ta fallando */
                $saldo = $row2['monto'];
                $saldo-=$monto;
                set_datos($row2['fecha_ingreso'], 'ID-' . $row2['idingreso'], '0', $monto / $tc, $saldo / $tc, $cebra++);
                $idporcobrar = $row2['idporcobrar'];
                $vesprim++;
            } else {
                if ($idporcobrar != $row2['idporcobrar']) {
                    echo "<tr><td colspan='5' style='border-top:1px solid;' >&nbsp;&nbsp;&nbsp;</td></tr>";
                    $total+=$saldo;
                    $cebra = 0;
                    set_titulos();
                    set_datos($row2['fecha_cc'], 'CC-' . $row2['idporcobrar'], $row2['monto'] / $tc, '0.00', $row2['monto'] / $tc, $cebra++);
                    $monto = $row2['montobolivianos'] + ( $row2['montodolares'] * $tc);
                    $saldo = $row2['monto'];
                    $saldo-=$monto;
                    set_datos($row2['fecha_ingreso'], 'ID-' . $row2['idingreso'], '0', $monto / $tc, $saldo / $tc, $cebra++);
                    $idporcobrar = $row2['idporcobrar'];
                } else {

                    $monto = $row2['montobolivianos'] + ( $row2['montodolares'] * $tc);
                    $saldo-=$monto;
                    set_datos($row2['fecha_ingreso'], 'ID-' . $row2['idingreso'], '0', $monto / $tc, $saldo / $tc, $cebra++);
                    $idporcobrar = $row2['idporcobrar'];
                }
            }
            $cont++;
            if ($cont > 25) {
                fin_tabla();
                inicio_tabla();
                set_margen_principal();
                setPie();
                $cont = 0;
            }
        }
    }
    $total+=$saldo;
    fin_tabla();
    set_totales($total);
    setPie();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link type="text/css" rel="stylesheet" href="estilo_estado_cuenta_cobrar.css"/>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ventas</title>
    </head>
    <body> 
        <?php
        if ($_GET['moneda'] != "Bolivianos") {
            $tc = get_tc($mysql);
        } else {
            $tc = 1;
        }
        set_reporte($mysql, $_GET['iddeudor'], $_GET['nombre_deudor'], $_GET['obj'], $tc);
        ?>    
    </body>
</html>
<?php
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$fechaI = explode('/', $desde);
$fechaF = explode('/', $hasta);
$desde = "$fechaI[2]/$fechaI[1]/$fechaI[0]";
$hasta = "$fechaF[2]/$fechaF[1]/$fechaF[0]";

$sql = "select * from empresa ";
$empresa = $mysql->consulta($sql);
$empresa = mysql_fetch_array($empresa);

$header = "            
        <table width='100%'>
          <tr>
            <td rowspan='3'><img src='../$empresa[imagen]' width='200' height='70'/></td>
            <td style='text-align: center; width:60%;font-family: cursive ,sans-serif; font-size: 18px;font-family: Geneva,Arial,Helvetica,sans-serif;   
                font-weight: bold;'>
                    ESTADO DE CUENTA POR COBRAR
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style='width:10%;'>&nbsp;</td>
          </tr>
          <tr>
              <td style='text-align: center; width:60%;'>(Expresado en " . $_GET['moneda'] . ")</td>
            <td>&nbsp;</td>
            <td style='text-align: center; width:8%;background: #E6E6E6;border:1px solid;'>{PAGENO}/{nb}</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>";
//mPDF($mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=25,$mgb=16,$mgh=9,$mgf=9, $orientation='P')
$mpdf = new mPDF('utf-8', 'Letter', 0, '', 15, 15, 30, 15, 9, 9, 'P');
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>