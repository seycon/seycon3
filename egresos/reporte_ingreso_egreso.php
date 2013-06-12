<?php
	ob_start();
	include_once("../conexion.php");
	include("../MPDF53/mpdf.php");
	$mysql=new MySQL(); 

	function set_margen_principal() 
	{
		echo "<div class='margenPrincipal'></div>
			  <div class='color1'></div>
			  <div class='color2'></div>
		";
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

function get_datos_ingresos($mysql, $mes, $anio, $idsucursal)
{
    if ($idsucursal == "" ) {
        $consulta="
		select (sum(dl.haber)-sum(dl.debe))as 'ingresos' 
		 from detallelibrodiario dl,librodiario l,plandecuenta pc
		 where 
		 dl.idlibro=l.idlibrodiario 
		and l.estado=1 
		and year(l.fecha)=$anio  
		and pc.codigo=dl.idcuenta 
		and month(l.fecha)=$mes  
		and pc.estado=1 
		and left(dl.idcuenta,1)=4 
		group by left(dl.idcuenta,1)=4;
		";
    } else {
        $consulta = "
		select (sum(dl.haber)-sum(dl.debe))as 'ingresos' 
		 from detallelibrodiario dl,librodiario l,plandecuenta pc
		 where 
		 dl.idlibro=l.idlibrodiario 
		and l.estado=1 
		and year(l.fecha)=$anio  
		and pc.codigo=dl.idcuenta 
		and month(l.fecha)=$mes  
		and pc.estado=1 
		and l.idsucursal=$idsucursal 
		and left(dl.idcuenta,1)=4 
		group by left(dl.idcuenta,1)=4;
		";
    }
    
    $data = $mysql->arrayConsulta($consulta);
	return $data['ingresos'];	 
}


function get_datos_egresos($mysql, $mes, $anio, $idsucursal)
{
    if ($idsucursal == "" ) {
        $consulta="
		select (sum(dl.debe) - sum(dl.haber))as 'egresos' 
		from detallelibrodiario dl,librodiario l,plandecuenta pc 
		where 
		dl.idlibro=l.idlibrodiario 
		and l.estado=1 
		and year(l.fecha)=$anio  
		and pc.codigo=dl.idcuenta 
		and pc.estado=1 
		and month(l.fecha)=$mes  
		and left(dl.idcuenta,1)=6 
		group by left(dl.idcuenta,1)=6;
		";
    } else {
        $consulta = "
		select (sum(dl.debe) - sum(dl.haber))as 'egresos' 
		 from detallelibrodiario dl,librodiario l,plandecuenta pc
		 where 
		 dl.idlibro=l.idlibrodiario 
		and l.estado=1 
		and year(l.fecha)=$anio  
		and pc.estado=1  
		and pc.codigo=dl.idcuenta 
		and month(l.fecha)=$mes  
		and l.idsucursal=$idsucursal 
		and left(dl.idcuenta,1)=6 
		group by left(dl.idcuenta,1)=6;
		";
    }
    
    $data = $mysql->arrayConsulta($consulta);
	return $data['egresos'];	 
}



function set_datos($total_mes, $total_egreso) { 
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
          </tr>
		  <tr>
              <td class='td_totales'>".number_format($total_egreso[0],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[1],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[2],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[3],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[4],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[5],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[6],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[7],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[8],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[9],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[10],2)."</td>
              <td class='td_totales'>".number_format($total_egreso[11],2)."</td>
          </tr>
		  
		  ";
}

function set_grafico($total_ingreso, $total_egreso) {  
    $todo_ingreso = 0;
	$todo_egreso = 0;
    for ($i = 0; $i <= 11; $i++) {
		if ($total_ingreso[$i] < 0) {
			$total_ingreso[$i] = 0;
		}
		if ($total_egreso[$i] < 0) {
			$total_egreso[$i] = 0;
		}
		
        $todo_ingreso += $total_ingreso[$i];
		$todo_egreso += $total_egreso[$i];
    } 
    if ($todo_ingreso <= 0) {
        $todo_ingreso = 1;
    }   
	if ($todo_egreso <= 0) {
        $todo_egreso = 1;
    }   
    echo "  <div class='div_contenedor_gral' align='center'>
            <div class='div_titulo'>GRÁFICO COMPARATIVO</div>
            <div class='div_contenedor_barras'>";    
 
      for ($x = 0; $x <= 11; $x++) {          
          $px = ($total_ingreso[$x] * 200) / $todo_ingreso;                    
          $sup = (200 - $px);             
          $porc= ($px * 100) / 200; 
		  
		  $pxe = ($total_egreso[$x] * 200) / $todo_egreso;                    
          $supe = (200 - $pxe);             
          $porce= ($pxe * 100) / 200;
		  
		  
		                 
		  if ($x + 1 == 1) {
			  echo "
				  <div class='espacio_inicial'></div>                
				  <div class='div_barras'>
					  <div class='div_superior' style='height:".round($sup,1)."px;'></div>
					  <div class='div_base'>".round($porc,1)."%"."</div>
					  <div class='div_inferior' style='height:".round($px,1)."px;'></div>					  
				  </div>
				  <div class='div_espacio1'></div>
				  <div class='div_barras'>
					  <div class='div_superior' style='height:".round($supe,1)."px;'></div>
					  <div class='div_base'>".round($porce,1)."%"."</div>
					  <div class='div_inferior2' style='height:".round($pxe,1)."px;'></div>					  
				  </div>
				  ";
				  
		  } else {
			  echo "				                
				  <div class='div_espacio'></div>                
				  <div class='div_barras'>
					  <div class='div_superior' style='height: ".round($sup,1)."px;'></div>
					  <div class='div_base'>".round($porc,1)."%"."</div>
					  <div class='div_inferior' style='height: ".round($px,1)."px;'></div>					  
				  </div>
				  <div class='div_espacio1'></div>
				  <div class='div_barras'>
					  <div class='div_superior' style='height:".round($supe,1)."px;'></div>
					  <div class='div_base'>".round($porce,1)."%"."</div>
					  <div class='div_inferior2' style='height:".round($pxe,1)."px;'></div>					  
				  </div>				  
				  ";                         
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
    <link type="text/css" rel="stylesheet" href="estilo_ingreso_egreso.css"/>    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ventas</title>
</head>
<body>  

    <?php
    set_margen_principal();
    echo "<br/>";	
    inicio_tabla();
    set_meses();
    $total_ingresos = array(); 
	$total_egresos = array();    
	
    for ($i = 1; $i <= 12; $i++) {
       $total_ingresos[$i - 1] = get_datos_ingresos($mysql, $i, $_GET['anio'], $_GET['sucursal']);    
       $total_egresos[$i - 1] = get_datos_egresos($mysql, $i, $_GET['anio'], $_GET['sucursal']);
    }
    set_datos($total_ingresos, $total_egresos);
    fin_tabla();    
    echo "<br/><br/>";
    set_grafico($total_ingresos, $total_egresos);
    echo "<br/><br/>
	<div class='tabla_referencia'> 
	    <table width='100%' border='0' >
  <tr>
    <td width='20%'>&nbsp;</td>
    <td colspan='2' class='textoreferencia'>REFERENCIA</td>
    <td width='20%'>&nbsp;</td>
  </tr>  
    <tr>
    <td width='20%' height='7'></td>
    <td width='30%' ></td>
    <td width='30%'></td>
    <td width='20%'></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td  class='referencia1'></td>
    <td  class='textoreferencia'>&nbsp;&nbsp;Ingresos</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height='5'></td>
    <td ></td>
    <td ></td>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class='referencia2'>&nbsp;</td>
    <td class='textoreferencia'>&nbsp;&nbsp;Egresos</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td height='5'></td>
    <td ></td>
    <td ></td>
    <td></td>
  </tr>
</table>
</div>
	<br/>
	<br/><br/>";
    echo "<div style='font-size: 10px;'>Si no puede ver los gráficos click 
	<a href='http://get.adobe.com/es/reader/' target='_blank'>Aquí</a> y descargue Adobe Reader recomendado.</div>";
    setPie();
    ?>  
    


    
</body>
</html>
<?php



	$sql = "select * from empresa ";
	$empresa = $mysql->consulta($sql);
	$empresa = mysql_fetch_array($empresa);

    $header="<table align='center' width='100%' cellpadding='0' cellspacing='0'>
              <tr>
                <td rowspan='3' class='td_logo'><img src='../$empresa[imagen]' width='200' height='70'/></td>
                <td class='td_titulo1'>INGRESOS Y EGRESOS DE LA GESTIÓN ".$_GET['anio']."</td>
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


