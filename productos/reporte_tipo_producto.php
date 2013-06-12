<?php
include_once("../conexion.php");
include("../MPDF53/mpdf.php");
ob_start();
$mysql = new MySQL();

function set_separador() {
    echo "<tr><td colspan='6' class='td_separador'></td></tr>";
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

function set_grupo($grupo,$nuevo_gru) {
    $border="style='border-top:1px solid;'";
    if($nuevo_gru!=0){
        $border="";
    }
    echo "
          <tr>            
            <td class='td_grupo' colspan='6' $border>GRUPO : $grupo</td>
          </tr>";
}

function set_titulos() {
    echo "  <tr class='tr_titulos'>
                <td class='td_titulo'>SUB GRUPO</td>                
                <td class='td_titulo'>Producto</td>
                <td class='td_titulo'>U.M.</td>
                <td class='td_titulo'>U.A.</td>
                <td class='td_titulo'>CONV.</td>
                <td class='td_titulo'>COSTO</td>
              </tr>";
}

function set_datos($nueva_h,$subgurpo, $producto, $um, $ua, $conv, $costo, $cebra) {
    $stylo = "style='background: #E6E6E6;'";
    if ($cebra % 2 == 0) {
        $stylo = "";
    }
    $costo = number_format($costo, 2);
    $borde = "style='border-top:1px solid;'";
    if($nueva_h!=0){
        $borde="";
    }
    echo "<tr $stylo>            
            <td class='td_subgrupo' $borde>$subgurpo</td>
            <td class='td_producto' $borde>$producto</td>
            <td class='td_datos' $borde>$um</td>
            <td class='td_datos'  $borde>$ua</td>
            <td class='td_datos'  $borde>$conv</td>
            <td class='td_costo' $borde>$costo</td>
          </tr>";
}

function get_datos($mysql, $idgrupo) {
    $consulta = "select g.idgrupo,g.nombre as 'grupo',sg.idsubgrupo, sg.nombre as 'subgrupo',p.*            
                from producto p inner join subgrupo sg on p.idsubgrupo=sg.idsubgrupo
                inner join grupo g on sg.idgrupo=g.idgrupo where p.estado=1 and g.estado=1 ";
    if ($idgrupo != "*") {
        $consulta = $consulta . " and g.idgrupo=$idgrupo";
    }
    $consulta = $consulta . "  order  by g.idgrupo,sg.idsubgrupo ";
    return $mysql->consulta($consulta);
}

function set_reporte($mysql, $idgrupo) {
    $id_grupo = 0;
    $idsubgrupo = 0;
    $cebra = 0;
    $cont_filas = 0;
    $vesprim=1;
    $res = get_datos($mysql, $idgrupo);    
    inicio_tabla();
    while ($row = mysql_fetch_array($res)) {
        if ($id_grupo != $row['idgrupo']) {
            if($vesprim==1){
                set_grupo($row['grupo'],1);
                $vesprim++;
            }else{
                set_grupo($row['grupo'],0);
            }            
            set_titulos();            
            set_datos(1,$row['subgrupo'], $row['nombre'], $row['unidaddemedida'], $row['unidadalternativa']
			, $row['conversiones'], $row['costo'], $cebra++);
            $id_grupo = $row['idgrupo'];
            $idsubgrupo = $row['idsubgrupo'];
            $cont_filas +=3;
        } else {
            if ($idsubgrupo != $row['idsubgrupo']) {
                set_datos(0,$row['subgrupo'], $row['nombre'], $row['unidaddemedida'], $row['unidadalternativa']
				, $row['conversiones'], $row['costo'], $cebra++);
                $id_grupo = $row['idgrupo'];
                $idsubgrupo = $row['idsubgrupo'];
                $cont_filas++;
            } else {
                set_datos($cont_filas,'', $row['nombre'], $row['unidaddemedida'], $row['unidadalternativa']
				, $row['conversiones'], $row['costo'], $cebra++);
                $id_grupo = $row['idgrupo'];
                $idsubgrupo = $row['idsubgrupo'];
                $cont_filas++;
            }
        }
        if ($cont_filas >= 46) {
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
        <title>Tipo de Productos</title>
        <link type="text/css" href="estilo_resporte_tipo_producto.css" rel="stylesheet"/>
    </head>
    <body>

        <?php
        set_reporte($mysql, $_GET['idgrupo']);
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
                    
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style='width:10%;'>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: center; width:60%;font-family: cursive ,sans-serif; font-size: 18px;font-family: Geneva,Arial,Helvetica,sans-serif;   
                font-weight: bold;'>TIPO DE PRODUCTOS
                 <!--Del " . $fechaI[0] . " de " . $mysql->mes($fechaI[1]) . " de " . $fechaI[2] . " al " . $fechaF[0] . " de " . $mysql->mes($fechaF[1]) . " de " . $fechaF[2] . "-->
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