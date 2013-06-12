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
    echo "<tr><td colspan='9'>PRODUCTO :  $producto</td></tr>";
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
            <td colspan='2'>Entradas</td>
            <td rowspan='2'>Precio Unit.</td>                    
            <td rowspan='2'> Precio Total</td>                    
            <td rowspan='2' colspan ='2'>Glosa</td>  
        </tr>
        <tr>
            <td class='td_unidad'>$unidadmedida</td>
			<td class='td_un'>$unidadalternativa</td>
        </tr>";
    } else {
        echo "
        <tr class='tr_titulos'>                    
                <td rowspan='2' class='td_nota'>Nota</td>
                <td rowspan='2' class='td_fecha'>Fecha</td>
                <td colspan='2'>Entradas</td>
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

function get_entero_decimal($numero) {
    $valores = explode('.', $numero);
    $valores[0] = $valores[0];
    $valores[1] = '0.' . $valores[1];
    return $valores;
}

function get_datos_ingreso($mysql, $filtro, $numrows) {
    $desde = $mysql->GetFormatofecha($_GET['desde'], '/');
    $hasta = $mysql->GetFormatofecha($_GET['hasta'], '/');

    $consulta = "select a.idalmacen,a.nombre as 'almacen',ip.nroingresoprod as 'nota_i',ip.idtransaccion as 'nota_t',Date_format(ip.fecha,'%d/%m/%Y')
as 'fecha',LEFT(ip.glosa,40) as 'glosa', p.*,dip.unidadmedida,dip.cantidadingresada,dip.precio,dip.total
from almacen a inner join ingresoproducto ip on ip.idalmacen=a.idalmacen 
               inner join detalleingresoproducto dip on dip.idingresoprod=ip.idingresoprod 
               inner join producto p on p.idproducto=dip.idproducto
                where a.estado=1 and ip.estado=1 and p.estado=1 and dip.estado=1 and ip.fecha between '" . $desde . "' and '" . $hasta . "' and a.idalmacen=" . $_GET['idalmacen'];

    if ($filtro == true) {
        $consulta = $consulta . "  and dip.idproducto=" . $_GET['idproducto'];
    }
    $consulta = $consulta . " order by p.idproducto,ip.idingresoprod";

    if ($numrows == true) {
        return $mysql->getnumRow($consulta);
    } else {
        return $mysql->consulta($consulta);
    }
}

function set_datos_ingreso($nueva_h,$nota, $fecha, $cantidad, $preciounit, $total, $glosa, $conv, $cebra) {
    $cantidad[1] = $cantidad[1] * $conv;
    $cantidad[1] = number_format($cantidad[1], 2);
    $cantidad[0] = number_format($cantidad[0]);
    $total = number_format($total, 2);
    $preciounit = number_format($preciounit, 2);
    $stylo = "style='background: #E6E6E6;'";
    $border= "style='border-top:1px solid;'";
    
    if ($cebra % 2 == 0) {
        $stylo = "";
    }
    if($nueva_h!=0){
        $border="";
    }
/*if($cantidad[0] != '' or $cantidad[1] != '')
{*/
    echo "<tr $stylo>
            <td class='td_datos_nota' $border>$nota</td>
            <td class='td_datos_fecha'  $border>$fecha</td>            
            <td class='td_datos_um' $border>$cantidad[0]</td>            
            <td class='td_datos_ua'  $border>$cantidad[1]</td>
            <td class='td_datos_pu' $border>$preciounit</td>
            <td class='td_datos_total' $border>$total</td>
            <td class='td_datos_glosa' $border>$glosa</td>
        </tr>";
		/* }
		 else
		 {
			  echo "<tr $stylo>
            <td class='td_datos_nota' $border>$nota</td>
            <td class='td_datos_fecha'  $border>$fecha</td>            
            <td class='td_datos_um' $border>$cantidad[0]</td>     
			<td class='td_datos_ua'  $border>0.0000</td>       
            <td class='td_datos_pu' $border>$preciounit</td>
            <td class='td_datos_total' $border>$total</td>
            <td class='td_datos_glosa' $border>$glosa</td>
        </tr>";
		  }*/
}

function set_reporte($mysql, $filtro) {
    $idalmacen = 0;
    $idproducto = 0;    
    $cont_filas = 0;    
    $res = get_datos_ingreso($mysql, $filtro, false);
    $cebra = 0;
    $nota = 0;
    inicio_tabla();
    while ($row = mysql_fetch_array($res)) {
        $nota = 'I-' . $row['nota_i'];
        if ($row['nota_i'] == 0) {
            $nota = 'T-' . $row['nota_t'];
        }
        if ($idalmacen != $row['idalmacen']) {
            set_almacen($row['almacen']);
            set_producto($row['nombre']);
            set_titulos($row['unidaddemedida'], $row['unidadalternativa']);
            $valores = get_entero_decimal($row['cantidadingresada']);
            set_datos_ingreso(1,$nota, $row['fecha'], $valores, $row['precio'], $row['total'], $row['glosa'], $row['conversiones'], $cebra++);
            $idalmacen = $row['idalmacen'];
            $idproducto = $row['idproducto'];
            $cont_filas +=5;
        } else {
            if ($idproducto != $row['idproducto']) {
                if ($cont_filas != 0) {
                    set_separador();
                    $cont_filas++;
                }
                $cebra = 0;
                set_producto($row['nombre']);
                set_titulos($row['unidaddemedida'], $row['unidadalternativa']);
                $valores = get_entero_decimal($row['cantidadingresada']);
                set_datos_ingreso($cont_filas,$nota, $row['fecha'], $valores, $row['precio'], $row['total'], $row['glosa'], $row['conversiones'], $cebra++);
                $idalmacen = $row['idalmacen'];
                $idproducto = $row['idproducto'];
                $cont_filas +=4;
            } else {
                $valores = get_entero_decimal($row['cantidadingresada']);
                set_datos_ingreso($cont_filas,$nota, $row['fecha'], $valores, $row['precio'], $row['total'], $row['glosa'], $row['conversiones'], $cebra++);
                $idalmacen = $row['idalmacen'];
                $idproducto = $row['idproducto'];
                $cont_filas++;
            }
        }       
        if ($cont_filas >= 41) {
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
        <link type="text/css" rel="stylesheet" href="estilo_ingreso_producto.css"/>
        <title>Ingreso por Producto</title>
    </head>
    <body>
        <?php
        if ($_GET['idproducto'] == '*') {
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

$header = "<table width='100%'>
          <tr>
            <td rowspan='3'><img src='../$empresa[imagen]' width='200' height='70'/></td>
            <td style='text-align: center; width:60%;font-family: cursive ,sans-serif; font-size: 18px;font-family: Geneva,Arial,Helvetica,sans-serif;   
                font-weight: bold;'>
                    INGRESO POR PRODUCTOS
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
            <td style='text-align: center;font-size: 12px'></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>";
$mpdf = new mPDF('utf-8', 'Letter');
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>