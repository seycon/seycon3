<?php
ob_start();
include_once("../../conexion.php");
include("../../MPDF53/mpdf.php");
$mysql=new MySQL(); 

function set_margen_principal() 
{
    echo "<div class='margenPrincipal'></div>";
}

function setPie() 
{
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

function inicio_tabla() 
{
    echo "<table class='tabla_reporte'>";
}

function fin_tabla() 
{
    echo "</table>";
}

function set_sucursal($sucursal) 
{
    echo "<tr>
              <td>$sucursal</td>
          </tr>";
}

function set_meses()
{
    echo "<tr>
              <td class='td_mes' width='10%'>ENERO</td>
              <td class='td_mes' width='10%'>FEBRERO</td>
              <td class='td_mes' width='10%'>MARZO</td>
              <td class='td_mes' width='10%'>ABRIL</td>
              <td class='td_mes' width='10%'>MAYO</td>
              <td class='td_mes' width='10%'>JUNIO</td>
              <td class='td_mes' width='10%'>JULIO</td>
              <td class='td_mes' width='10%'>AGOSTO</td>
              <td class='td_mes' width='10%'>SEPTIEMBRE</td>
              <td class='td_mes' width='10%'>OCTUBRE</td>              
              <td class='td_mes' width='10%'>NOVIEMBRE</td>
              <td class='td_mes' width='10%'>DICIEMBRE</td>              
          </tr>";
}

function get_datos($mysql, $mes, $anio)
{
    $consulta="select *,sum(monto) as 'total_mes' from notaventa
                where MONTH(fecha)=$mes and YEAR(fecha)=$anio and tiponota='servicios' and estado=1
                group by MONTH(fecha)";
    
    return $mysql->consulta($consulta);
}

function set_datos($total_mes) { 
    echo "<tr>
              <td class='td_totales'>".number_format($total_mes[0],2)."</td>
              <td class='td_totales'>".number_format($total_mes[1],2)."</td>
              <td class='td_totales'>".number_format($total_mes[2],2)."</td>
              <td class='td_totales'>".number_format($total_mes[3],2)."</td>
              <td class='td_totales'>".number_format($total_mes[4],2)."</td>
              <td class='td_totales'>".number_format($total_mes[5],2)."</td>
              <td class='td_totales'>".number_format($total_mes[6],2)."</td>
              <td class='td_totales'>".number_format($total_mes[7],2)."</td>
              <td class='td_totales'>".number_format($total_mes[8],2)."</td>
              <td class='td_totales'>".number_format($total_mes[9],2)."</td>
              <td class='td_totales'>".number_format($total_mes[10],2)."</td>
              <td class='td_totales'>".number_format($total_mes[11],2)."</td>
          </tr>";
}

function set_grafico($totales) {  
    $todo=0;
    for ($i = 0; $i <= 11; $i++) {
        $todo += $totales[$i];
    } 
    if ($todo <= 0) {
        $todo = 1;
    }    
    echo "  <div class='div_contenedor_gral' align='center'>
            <div class='div_titulo'>GRÁFICO COMPARATIVO</div>
            <div class='div_contenedor_barras'>";    
 
      for ($x = 0; $x <= 11; $x++) {          
          $px =($totales[$x] * 200) / $todo;                    
          $sup = (200 - $px);             
          $porc= ($px * 100) / 200;                
		  if ($x + 1 == 1) {
			  echo "
				  <div class='div_espacio'></div>                
				  <div class='div_barras'>
					  <div class='div_superior' style='height:".round($sup,1)."px;'></div>
					  <div class='div_base'>".round($porc,1)."%"."</div>
					  <div class='div_inferior' style='height:".round($px,1)."px;'></div>
					  
				  </div>";
		  } else {
			  echo "
				  <div class='div_espacio'></div>                
				  <div class='div_espacio'></div>                
				  <div class='div_barras'>
					  <div class='div_superior' style='height: ".round($sup,1)."px;'></div>
					  <div class='div_base'>".round($porc,1)."%"."</div>
					  <div class='div_inferior' style='height: ".round($px,1)."px;'></div>
					  
				  </div>";                         
		  }                     
      }
 
     echo "                            
         </div>
                <div class='div_contenedor_meses'>
                <table class='tabla_nombre_meses'>
                    <tr>                    
                        <td class='td_nombre_mes'>Enero</td>
                        <td class='td_nombre_mes'>Febrero</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; Marzo</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;Abril</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;Mayo</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;  Junio</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp; Julio</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;   Agosto</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Septiembre</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Octubre</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Noviembre</td>
                        <td class='td_nombre_mes'>&nbsp;&nbsp;&nbsp;&nbsp; Diciembre</td>                        
                    </tr>
                </table>
            </div>
     
        </div>";       
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link type="text/css" rel="stylesheet" href="estilo_reporte_ventas_gestion.css"/>    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ventas</title>
</head>
<body>  

    <?php
    set_margen_principal();
    echo "<br/>";	
    inicio_tabla();
    set_meses();
    $total_mes= array();    
    for ($i = 1; $i <= 12; $i++) {
        $res = get_datos($mysql, $i, $_GET['anio']);        
        while ($row = mysql_fetch_array($res)) {
            $total_mes[$i-1]=$row['total_mes'];            
        }
    }
    set_datos($total_mes);
    fin_tabla();    
    echo "<br/><br/>";
    set_grafico($total_mes);
    echo "<br/><br/><br/><br/><br/>";
    echo "<div style='font-size: 10px;'>Si no puede ver los gráficos click 
	<a href='http://get.adobe.com/es/reader/' target='_blank'>Aquí</a> y descargue Adobe Reader recomendado.</div>";
    setPie();
    ?>  
    
    
</body>
</html>
<?php
    $desde=$_GET['desde'];
    $hasta=$_GET['hasta'];
    $fechaI = explode('/',$desde);
    $fechaF = explode('/',$hasta);
    $desde = "$fechaI[2]/$fechaI[1]/$fechaI[0]";
    $hasta = "$fechaF[2]/$fechaF[1]/$fechaF[0]";    
	$sql = "select * from empresa ";
	$empresa = $mysql->consulta($sql);
	$empresa = mysql_fetch_array($empresa);

    $header="<table align='center' width='100%' cellpadding='0' cellspacing='0'>
              <tr>
                <td rowspan='3' class='td_logo'><img src='../../$empresa[imagen]' width='200' height='70'/></td>
                <td class='td_titulo1'>VENTAS DE LA GESTIÓN ".$_GET['anio']."</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
                <tr>
                <td class='td_titulo2'>(Expresados en Bolivianos)</td>
                <td></td>                
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class='td_titulo2'>                    
                    <!--Del " . $fechaI[0] . " de " . $mysql->mes($fechaI[1])
					 . " de " . $fechaI[2] . " al " . $fechaF[0] . " de " 
					 . $mysql->mes($fechaF[1]) . " de " . $fechaF[2] ."-->
                </td>
                <td>&nbsp;</td>
                <td class=''></td>
                <td>&nbsp;</td>    
              </tr>
            </table>";

	$mpdf = new mPDF('utf-8','Letter-L',0,'',15,15,35,15,9,9);
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>


