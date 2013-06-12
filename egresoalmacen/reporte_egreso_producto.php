<?php
ob_start();
include_once("../conexion.php");
include("../MPDF53/mpdf.php");
$mysql = new MySQL();

function set_separador() {
    echo "<tr><td colspan='7' class='td_separador'>&nbsp;&nbsp;</td></tr>";
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
    echo "
        <div style='position : absolute;left: 10%;  '></div>
        <div class='div_contenedor'>
        <table class='reporte' cellspacing=0 cellpadding=2>";
}

function fin_tabla() {
    echo "</table></div>";
}

function set_almacen($almacen) {
    echo "
          <tr>
             <td colspan='9' class='td_almacen' >ALMACEN :  $almacen</td>
          </tr>";
}

function set_producto($producto) {
    echo "<tr><td colspan='9' style=' font-weight: bold;'>PRODUCTO :  $producto</td></tr>";
}

function set_total($total, $unidadalternativa) {
    $total = number_format($total, 2);
    if ($unidadalternativa == '') {
        echo " <tr>
               <td colspan='4'></td>            
               <td class='td_total'>$total</td>
               <td ></td>
            </tr>";
    } else {
        echo " <tr>
                <td colspan='5'></td>            
                <td class='td_total'>$total</td>
                <td ></td>
            </tr>";
    }
}

function set_titulos($unidadmedida, $unidadalternativa) {
    if ($unidadalternativa == '') {
        echo "  
        <tr class='tr_titulos'>
            <td rowspan='2' class='td_nota'>Nota</td>
            <td rowspan='2' class='td_fecha'>Fecha</td>            
            <td >Salida</td>
            <td rowspan='2'>Precio Unit.</td>                    
            <td rowspan='2'> Precio Total</td>                    
            <td rowspan='2' colspan ='2'>Glosa</td>  
        </tr>
        <tr>
            <td class='td_unidad'>$unidadmedida</td>
        </tr>";
    } else {
        echo "
        <tr class='tr_titulos'>                    
                <td rowspan='2' class='td_nota'>Nota</td>
                <td rowspan='2' class='td_fecha'>Fecha</td>
                <td colspan='2'>Salida</td>
                <td rowspan='2'>Precio Unit.</td>                    
                <td rowspan='2'>Precio Total</td>                    
                <td rowspan='2'>Glosa</td>                   
            </tr>
                <tr>
                <td class='td_unidad'>$unidadmedida</td>
                <td class='td_un'>$unidadalternativa</td>
            </tr>";
    }
}

function get_datos_egreso($mysql, $idalmacen, $idproducto, $desde, $hasta, $num_rows) {
    $consulta = "select a.idalmacen,a.nombre as'almacen',p.nombre as 'producto',p.*,Date_format(ep.fecha,'%d/%m/%Y') as 'fecha',
                ep.idegresoprod,LEFT(ep.glosa,23) as 'glosa',dep.cantidad,dep.total,dep.unidadmedida,dep.precio
                from almacen a inner join egresoproducto ep on ep.idalmacen=a.idalmacen
                inner join detalleegresoproducto dep on dep.idegresoprod=ep.idegresoprod
                inner join producto p on p.idproducto=dep.idproducto  
where a.estado=1 and ep.estado=1 and p.estado=1 and ep.fecha between '$desde' and '$hasta' and a.idalmacen=$idalmacen";
    if ($idproducto != "*") {
        $consulta = $consulta . " and p.idproducto=$idproducto";
    }
    $consulta = $consulta . " order by p.idproducto,ep.idegresoprod";
    if ($num_rows == true) {
        return $mysql->getnumRow($consulta);
    } else {
        return $mysql->consulta($consulta);
    }
}

function get_entero_decimal($numero) {
    $valores = explode('.', $numero);
    $valores[0] = $valores[0];
    $valores[1] = '0.' . $valores[1];
    return $valores;
}

function set_datos($nueva_h, $nota, $fecha, $cantidad, $preciounit, $total, $glosa, $conv, $cebra) {
    $total = number_format($total, 2);
    $preciounit = number_format($preciounit, 2);
    $cantidad[1] = $cantidad[1] * $conv;
    $cantidad[0] = number_format($cantidad[0]);
    $cantidad[1] = number_format($cantidad[1], 2);

    $borde = "style='border-top: 1px solid;'";
    $stylo = "style='background: #E6E6E6;'";
    if ($nueva_h != 0) {
        $borde = "";
    }
    if ($cebra % 2 == 0) {
        $stylo = "";
    }
    echo "<tr $stylo>
            <td class='td_datos_nota' $borde >$nota</td>
            <td class='td_datos_fecha' $borde>$fecha</td>                        
            <td class='td_datos_um' $borde>$cantidad[0]</td>
            <td class='td_datos_ua' $borde>$cantidad[1]</td>
            <td class='td_datos_pu' $borde>$preciounit</td>
            <td class='td_datos_total' $borde>$total</td>
            <td class='td_datos_glosa' $borde>$glosa</td>
        </tr>";
}

function set_reporte($mysql, $idalmacen, $idproducto, $desde, $hasta) {
    $vesprim = 1;
    $cebra = 0;
    $cont_filas = 0;
    $res_e = get_datos_egreso($mysql, $idalmacen, $idproducto, $desde, $hasta, false);
    $id_producto = 0;

    inicio_tabla();
    while ($row = mysql_fetch_array($res_e)) {
        if ($vesprim == 1) {
            set_almacen($row['almacen']);
            set_producto($row['producto']);
            set_titulos($row['unidaddemedida'], $row['unidadalternativa']);
            $valores = get_entero_decimal($row['cantidad']);
            set_datos(1, $row['idegresoprod'], $row['fecha'], $valores, $row['precio'], $row['total'], $row['glosa'], $row['conversiones'], $cebra++);
            $id_producto = $row['idproducto'];
            $vesprim++;
            $cont_filas +=5;
        } else {
            if ($id_producto != $row['idproducto']) {
                $cebra = 0;
                if ($cont_filas != 0) {
                    set_separador();
                    $cont_filas++;
                }
                set_producto($row['producto']);
                set_titulos($row['unidaddemedida'], $row['unidadalternativa']);
                $valores = get_entero_decimal($row['cantidad']);
                set_datos($cont_filas, $row['idegresoprod'], $row['fecha'], $valores, $row['precio'], $row['total'], $row['glosa'], $row['conversiones'], $cebra++);
                $id_producto = $row['idproducto'];
                $cont_filas += 4;
            } else {
                $valores = get_entero_decimal($row['cantidad']);
                set_datos($cont_filas, $row['idegresoprod'], $row['fecha'], $valores, $row['precio'], $row['total'], $row['glosa'], $row['conversiones'], $cebra++);
                $id_producto = $row['idproducto'];
                $cont_filas++;
            }
        }
        if ($cont_filas >= 42) {
            $cont_filas = 0;
            fin_tabla();
            set_margen_principal();
            setPie();
            for ($i = 0; $i < 55; $i++) {
                echo "<br/>";
            }
            inicio_tabla();
        }
    }
    fin_tabla();
    set_margen_principal();
    setPie();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="text/css" rel="stylesheet" href="stylo_egreso_producto.css"/>
        <title>Egreso por Producto</title>
    </head>
    <body>
        <?php
        $desde = $mysql->GetFormatofecha($_GET['desde'], '/');
        $hasta = $mysql->GetFormatofecha($_GET['hasta'], '/');
        set_reporte($mysql, $_GET['idalmacen'], $_GET['idproducto'], $desde, $hasta);
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



$header = "<table width='100%'>
          <tr>
            <td rowspan='3'><img src='../$empresa[imagen]' width='200' height='70'/></td>
            <td style='text-align: center; width:60%;font-family: cursive ,sans-serif; font-size: 18px;font-family: Geneva,Arial,Helvetica,sans-serif;   
                font-weight: bold;'>
                    EGRESO POR PRODUCTO
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style='width:10%;'>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: center; font-size: 13px;'>
                 Del " . $fechaI[0] . " de " . $mysql->mes($fechaI[1]) . " de " . $fechaI[2] . " al " . $fechaF[0] . " de " . $mysql->mes($fechaF[1]) . " de " . $fechaF[2] . "
            </td>
            <td>&nbsp;</td>
            <td style='text-align: center; width:8%;background: #E6E6E6;border:1px solid;'>{PAGENO}/{nb}</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: center;font-size: 12px'>(Expresado en Bolivianos)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>";
//$mpdf = new mPDF('utf-8', 'Letter', 0, '', 15, 15, 30, 18, 9, 9, 'P');
$mpdf = new mPDF('utf-8', 'Letter');
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>