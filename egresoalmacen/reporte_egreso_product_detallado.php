<?php
ob_start();
include_once("../conexion.php");
include("../MPDF53/mpdf.php");
$mysql = new MySQL();

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
    echo "<table class='reporte' colspan='0'>";
}

function fin_tabla() {
    echo "</table>";
}
function set_separador(){
    echo "<div class='seprardor'></div>";
}
//function get_almacenes($mysql, $idalmacen,$desde,$hasta) {
//  $consulta= "select a.idalmacen, a.nombre as 'almacen'
//                from almacen a inner join egresoproducto ep on ep.idalmacen=a.idalmacen
//                inner join detalleegresoproducto dep on dep.idegresoprod=ep.idegresoprod
//                inner join producto p on p.idproducto=dep.idproducto  
//where a.estado=1 and ep.estado=1 and p.estado=1 and ep.fecha between '$desde' and '$hasta' and a.idalmacen=$idalmacen";
//    return $mysql->consulta($consulta);
//}

function set_almacen($almacen) {
    echo "<tr>
             <td colspan='6'>ALMACEN :  $almacen</td>
          </tr>";
}

function set_titulos() {

    echo "<tr class='tr_titulos'>                    
                <td rowspan='2'>Nota</td>
                <td rowspan='2'>Fecha</td>
                <td rowspan='2'>Producto</td>
                <td colspan='2'>Salida</td>               
                <td rowspan='2'>Total</td>                                                      
            </tr>
                <tr>
                <td class='td_t_um' >U.M.</td>
                <td class='td_t_cant'>Cantidad</td>
            </tr>";
}

function get_datos_egreso($mysql, $idalmacen, $desde, $hasta) {
    $consulta = "select a.idalmacen,a.nombre as'almacen',p.nombre as 'producto',p.*,Date_format(ep.fecha,'%d/%m/%Y') as 'fecha',
                ep.idegresoprod,LEFT(ep.glosa,23) as 'glosa',dep.cantidad,dep.total
                from almacen a inner join egresoproducto ep on ep.idalmacen=a.idalmacen
                inner join detalleegresoproducto dep on dep.idegresoprod=ep.idegresoprod
                inner join producto p on p.idproducto=dep.idproducto  
where a.estado=1 and ep.estado=1 and p.estado=1 and ep.fecha between '$desde' and '$hasta' and a.idalmacen=$idalmacen";
    /*if ($idproducto != "*") {
        $consulta = $consulta . " and p.idproducto=$idproducto";
    }*/
    $consulta = $consulta . " order by ep.idegresoprod";    
    return $mysql->consulta($consulta);
}

function set_datos($idegreso, $fecha, $producto, $um, $cantidad, $total,  $cebra) {
    $total= number_format($total,2);
    $stylo = "style='background: #E6E6E6;'";
    if ($cebra % 2 == 0) {
        $stylo = "";
    }
    echo "<tr $stylo>
            <td class='td_nota'>$idegreso</td>
            <td class='td_fecha'>$fecha</td>            
            <td class='td_producto'>$producto</td>
            <td class='td_um'>$um</td>
            <td class='td_cantidad'>$cantidad</td>
            <td class='td_total'>$total</td>            
        </tr>";
}

function set_subtotal($sub_total) {
    $sub_total=  number_format($sub_total,2);
    echo "<tr class='tr_subtotal'>
        <td colspan='5' style='text-align:right;font-weight: bold;'>Sub Total :</td>
        <td class='td_subtotal'>$sub_total</td>        
        </tr>";    
}
function set_total($total) {
    $total=  number_format($total,2);
    echo "<tr class='tr_total'>
        <td colspan='5' style='text-align:right;font-weight: bold;'>TOTAL : </td>
        <td class='td_subtotal'>$total</td>        
        </tr>";    
}
function set_reporte($mysql, $idalmacen, $desde, $hasta) {
    $vesprim = 1;
    $cont = 0;
    $cont2=0;
    $cebra = 0;
    $idproducto = 0;
    $subtotal=0;
    $total=0;
    $res_egreso = get_datos_egreso($mysql, $idalmacen, $desde, $hasta);
    inicio_tabla();
    while ($row = mysql_fetch_array($res_egreso)) {
        if ($vesprim == 1) {
            set_almacen($row['almacen']);$cont++;$cont2++;
            set_titulos();$cont++;$cont2++;
            set_datos($row['idegresoprod'], $row['fecha'], $row['producto'], $row['unidaddemedida'], $row['cantidad'], $row['total'], $cebra++);$cont++;$cont2++;
            $subtotal+=$row['total'];
            $total+=$row['total'];
            $idproducto = $row['idegresoprod'];
            $vesprim++;
        } else {
            if ($idproducto != $row['idegresoprod']) {
                set_subtotal($subtotal);
                $subtotal=0;   
                $cebra=0;
                set_datos($row['idegresoprod'], $row['fecha'], $row['producto'], $row['unidaddemedida'], $row['cantidad'], $row['total'],$cebra++);$cont++;$cont2++;
                $subtotal+=$row['total'];
            $total+=$row['total'];
                $idproducto = $row['idegresoprod'];
            } else {
                set_datos('', $row['fecha'], $row['producto'], $row['unidaddemedida'], $row['cantidad'], $row['total'],$cebra++);$cont++;$cont2++;
                $subtotal+=$row['total'];
                $total+=$row['total'];
                $idproducto = $row['idegresoprod'];
            }
        }
        if ($cont > 20) {
            fin_tabla();  
            setPie();
            set_margen_principal();
            inicio_tabla();
            $cont = 0;
        }
            if ($cont2 > 40) {            
            fin_tabla();
            set_separador();
            setPie();
            set_margen_principal();
            $cebra=0;
            echo "<br/><br/>";
            inicio_tabla();
            set_titulos();
            $cont2 = 0;
        }
    }
    set_subtotal($subtotal);
    set_total($total);    
    fin_tabla();
    setPie();
    set_margen_principal();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="text/css" rel="stylesheet" href="estilo_egreso_producto_detallado.css"/>
        <title>Egreso Producto Detallado</title>

    </head>
    <body>
        <?php
        $desde = $mysql->GetFormatofecha($_GET['desde'], '/');
        $hasta = $mysql->GetFormatofecha($_GET['hasta'], '/');
        set_reporte($mysql, $_GET['idalmacen'], $desde, $hasta);
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
            <td style='text-align: center;font-size: 12px'></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style='width:10%;'>&nbsp;</td>
          </tr>
          <tr>
           
             <td style='text-align: center; width:60%;font-family: cursive ,sans-serif; font-size: 18px;font-family: Geneva,Arial,Helvetica,sans-serif;   
                font-weight: bold;'>
                    EGRESO DE PRODUCTO DETALLADO
            </td>
            
            <td>&nbsp;</td>
            <td style='text-align: center; width:8%;background: #E6E6E6;border:1px solid;'>{PAGENO}/{nb}</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: center; font-size: 13px;'>
                 Del " . $fechaI[0] . " de " . $mysql->mes($fechaI[1]) . " de " . $fechaI[2] . " al " . $fechaF[0] . " de " . $mysql->mes($fechaF[1]) . " de " . $fechaF[2] . "
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>";

//mPDF($mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=25,$mgb=16,$mgh=9,$mgf=9, $orientation='P')
$mpdf = new mPDF('utf-8', 'Letter', 0, '', 10, 10, 30, 10, 9, 9, 'P');
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>