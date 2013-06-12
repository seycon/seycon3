<?php
ob_start();
include_once("../conexion.php");
include("../MPDF53/mpdf.php");
$mysql = new MySQL();

function set_separador() {
    echo "<tr><td colspan='7' class='td_separador'></td></tr>";
}
function set_margen_principal() {
    echo "<div class='margenPrincipal'></div>";
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
    echo "<table class='reporte' align='center'>";
}
function fin_tabla() {
    echo "</table>";
}
function get_tc($mysql) {
    $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
    $tc = $mysql->getCampo('dolarcompra', $sql);
    return $tc;
}
function set_sucursal($sucursal_origen, $almacen_origen) {
    echo " <tr>
                  <td colspan='7' class='td_nombre_sucursal'>SUCURSAL ORIGEN : $sucursal_origen</td>
              </tr>
              <tr>
                  <td colspan='7' class='td_nombre_sucursal'>ALMACEN : $almacen_origen</td>
            </tr>";
}
function set_titulos() {
    echo "<tr>
            <td class='td_titulo'>Nota</td>
            <td class='td_titulo'>Fecha</td>
            <td class='td_titulo'>Destino</td>
            <td class='td_titulo'>Producto</td>
            <td class='td_titulo'>P/U</td>
            <td class='td_titulo'>Cantidad</td>
            <td class='td_titulo'>Importe</td>            
        </tr>";
}
function set_datos($nota, $fecha, $destino, $producto, $precio, $cantidad, $total,$cebra) {
    
    $precio = number_format($precio, 2);
    $total = number_format($total, 2);
    $stylo="style='background: #E6E6E6;'";
    if($cebra%2==0){
        $stylo="";
    }
    echo "<tr $stylo>
                <td class='td_nota'>$nota</td>
                <td class='td_fecha'>$fecha</td>
                <td class='td_destino'>$destino</td>
                <td class='td_producto'>$producto</td>
                <td class='td_pu'>$precio</td>
                <td class='td_cantidad'>$cantidad</td>
                <td class='td_importe'>$total</td>            
          </tr>";
}
function set_subtotales($subtotal, $subtotal_cantidad) {
    $subtotal = number_format($subtotal, 2);
    $subtotal_cantidad = number_format($subtotal_cantidad, 2);
    echo "<tr>
                <td colspan='5' class='nombre_subtotal'>Sub Total</td>                
                <td class='td_subtotalcantidad'>$subtotal_cantidad</td>
                <td class='td_subtotal'>$subtotal</td>
          </tr>";
}

function set_totales_gral($total_gral, $total_cantidad_gral) {
    $total_gral = number_format($total_gral, 2);
    $total_cantidad_gral = number_format($total_cantidad_gral, 2);
    echo "<tr>
                <td colspan='5' class='nombre_subtotal'>TOTAL</td>
                <td class='td_subtotalcantidad'>$total_cantidad_gral</td>
                <td class='td_subtotal'>$total_gral</td>
          </tr>";
}

function get_trasapso($mysql, $filtro) {
    $dsd = $mysql->GetFormatofecha($_GET['desde'], '/');
    $hst = $mysql->GetFormatofecha($_GET['hasta'], '/');

    $consulta = "select t1.*,dt.precio,dt.total,dt.cantidad,Date_format(t1.fecha,'%d/%m/%Y') as 'fecha',p.idproducto,p.nombre as 'producto'
    from 
    (select t.idtraspaso,t.moneda,t.fecha, s1.idsucursal as 'idsucursal_origen',s2.idsucursal as 'idsucursal_destino', s1.nombrecomercial as 'sucursal_origen',
    s2.nombrecomercial as 'sucursal_destino', a1.idalmacen as 'idalmacen_origen',a2.idalmacen as 'idalmacen_destino',a1.nombre as 'almacen_origen',
    a2.nombre as 'almacen_destino'
    from almacen a1 inner join traspaso t on t.idalmacenorigen=a1.idalmacen
                    inner join almacen a2 on t.idalmacendestino=a2.idalmacen
                    inner join sucursal s1 on s1.idsucursal=a1.sucursal
                    inner join sucursal s2 on s2.idsucursal=a2.sucursal
                    where t.estado=1 and a1.estado=1 and a2.estado=1 and s1.estado=1 and s2.estado=1) t1 
                    inner join detalletraspaso dt on t1.idtraspaso=dt.idtraspaso
    inner join producto p on p.idproducto=dt.idproducto
    where t1.fecha between '" . $dsd . "' and '" . $hst . "' and p.estado=1";
    if ($filtro == true) {
        $consulta = $consulta . " and t1.idsucursal_origen=" . $_GET['idsucursal'];
    }
    $consulta = $consulta . " order by t1.idsucursal_origen,t1.idtraspaso,dt.fecha,t1.idalmacen_origen";
    return $mysql->consulta($consulta);
}

function set_reporte($mysql, $filtro) {
    $cebra=0;
    $cont2=0;
    $cont = 0;
    $vesprim = 1;
    $tc=1;
    $idsucursal = 0;
    $idalmacen = 0;
    $idnota = 0;
    $subtotal = 0;
    $subtotalcantidad = 0;
    $total = 0;
    $totalcantidad = 0;
    $res = get_trasapso($mysql, $filtro);
    inicio_tabla();
    set_margen_principal();
    if ($_GET['moneda'] != "Bolivianos") {
        $tc=  get_tc($mysql);
    }
        while ($row = mysql_fetch_array($res)) {
            if ($idsucursal != $row['idsucursal_origen']) {
                if ($vesprim == 1) {
                    set_sucursal($row['sucursal_origen'], $row['almacen_origen']);$cont++;$cont2++;
                    set_titulos();$cont++;$cont2++;
                    set_datos($row['idtraspaso'], $row['fecha'], $row['almacen_destino'], $row['producto'], $row['precio']/$tc, $row['cantidad'], $row['total']/$tc,$cebra++);$cont++;$cont2++;
                    $idsucursal = $row['idsucursal_origen'];
                    $idnota = $row['idtraspaso'];
                    $subtotal+=$row['total'];
                    $subtotalcantidad+=$row['cantidad'];
                    $total+=$row['total'];
                    $totalcantidad+=$row['cantidad'];
                    $idalmacen = $row['idalmacen_origen'];
                    $vesprim++;
                } else {
                    set_subtotales($subtotal/$tc, $subtotalcantidad);$cont++;$cont2++;
                    set_totales_gral($total/$tc, $totalcantidad);$cont++;$cont2++;
                    $total = 0;
                    $totalcantidad = 0;
                    $subtotal = 0;
                    $subtotalcantidad = 0;
                    set_sucursal($row['sucursal_origen'], $row['almacen_origen']);$cont++;$cont2++;
                    set_titulos();$cont++;$cont2++;
                    set_datos($row['idtraspaso'], $row['fecha'], $row['almacen_destino'], $row['producto'], $row['precio']/$tc, $row['cantidad'], $row['total']/$tc,$cebra++);$cont++;$cont2++;
                    $idsucursal = $row['idsucursal_origen'];
                    $idnota = $row['idtraspaso'];
                    $subtotal+=$row['total'];
                    $subtotalcantidad+=$row['cantidad'];
                    $total+=$row['total'];
                    $totalcantidad+=$row['cantidad'];
                    $idalmacen = $row['idalmacen_origen'];
                }
            } else {
                if ($idalmacen != $row['idalmacen_origen']) {
                    set_subtotales($subtotal/$tc, $subtotalcantidad);$cont++;$cont2++;
                    set_totales_gral($total/$tc, $totalcantidad);$cont++;$cont2++;
                    $total = 0;
                    $totalcantidad = 0;
                    $subtotal = 0;
                    $subtotalcantidad = 0;
                    set_sucursal($row['sucursal_origen'], $row['almacen_origen']);$cont++;$cont2++;
                    set_titulos();$cont++;$cont2++;
                    set_datos($row['idtraspaso'], $row['fecha'], $row['almacen_destino'], $row['producto'], $row['precio']/$tc, $row['cantidad'], $row['total']/$tc,$cebra++);$cont++;$cont2++;
                    $idsucursal = $row['idsucursal_origen'];
                    $idnota = $row['idtraspaso'];
                    $subtotal+=$row['total'];
                    $subtotalcantidad+=$row['cantidad'];
                    $total+=$row['total'];
                    $totalcantidad+=$row['cantidad'];
                    $idalmacen = $row['idalmacen_origen'];
                } else {
                    if ($idnota != $row['idtraspaso']) {
                        set_subtotales($subtotal/$tc, $subtotalcantidad);$cont++;$cont2++;
                        $subtotal = 0;
                        $subtotalcantidad = 0;
                        set_datos($row['idtraspaso'], $row['fecha'], $row['almacen_destino'], $row['producto'], $row['precio']/$tc, $row['cantidad'], $row['total']/$tc,$cebra++);$cont++;$cont2++;
                        $idsucursal = $row['idsucursal_origen'];
                        $idnota = $row['idtraspaso'];
                        $subtotal+=$row['total'];
                        $subtotalcantidad+=$row['cantidad'];
                        $total+=$row['total'];
                        $totalcantidad+=$row['cantidad'];
                        $idalmacen = $row['idalmacen_origen'];
                    } else {
                        set_datos('', $row['fecha'], $row['almacen_destino'], $row['producto'], $row['precio']/$tc, $row['cantidad'], $row['total']/$tc,$cebra++);$cont++;$cont2++;
                        $idsucursal = $row['idsucursal_origen'];
                        $idnota = $row['idtraspaso'];
                        $subtotal+=$row['total'];
                        $subtotalcantidad+=$row['cantidad'];
                        $total+=$row['total'];
                        $totalcantidad+=$row['cantidad'];                        
                        $idalmacen = $row['idalmacen_origen'];
                    }
                }
            }            
            if ($cont > 20) {
                fin_tabla();                                                
                set_margen_principal();
                setPie();
                inicio_tabla();               
                $cont = 0;
            }
            if ($cont2 > 48) {
                set_separador();
                fin_tabla();
                $cebra=0;
                echo "<br/>";
                set_margen_principal();
                setPie();
                inicio_tabla();  
                set_titulos();
                $cont2 = 0;
            }
        }

    set_subtotales($subtotal/$tc, $subtotalcantidad);
    set_totales_gral($total/$tc, $totalcantidad);
    fin_tabla();
    set_margen_principal();
    setPie();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="text/css" rel="stylesheet" href="estilo_traspaso_detallado.css"/>
        <title>Traspaso Detallado</title>

    </head>
    <body> 
<?php
if ($_GET['idsucursal'] == "*") {
    set_reporte($mysql, false);
} else {
    set_reporte($mysql, true);
}
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
                    TRASPASO DE PRODUCTO DETALLADO
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style='width:10%;'>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: center; font-size: 12px;'>
                 Del " . $fechaI[0] . " de " . $mysql->mes($fechaI[1]) . " de " . $fechaI[2] . " al " . $fechaF[0] . " de " . $mysql->mes($fechaF[1]) . " de " . $fechaF[2] . "
            </td>
            <td>&nbsp;</td>
            <td style='text-align: center; width:8%;background: #E6E6E6;border:1px solid;'>{PAGENO}/{nb}</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: center;font-size: 12px'>(Expresados en " . $_GET['moneda'] . ")</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>";
//mPDF($mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=25,$mgb=16,$mgh=9,$mgf=9, $orientation='P')
$mpdf = new mPDF('utf-8', 'Letter', 0, '', 10, 10, 30, 15, 9, 9, 'P');
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>