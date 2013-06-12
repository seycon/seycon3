<?php
session_start();
if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
}
ob_start();
include("../MPDF53/mpdf.php");
include("../conexion.php");
include('../reportes/literal.php');
$db = new MySQL();

$sql = "select imagen,left(nombrecomercial,18)as 'nombrecomercial',nit,left(reprepropietario,20)as 'reprepropietario',cipropietario,numafiliado from empresa;";
$datoGeneral = $db->arrayConsulta($sql);


function insertarCabecera($titulo){
 echo " <tr>
    <td colspan='2' class='session2_tituloPrincipal'>$titulo</td>
    <td width='21%' >&nbsp;</td>
    <td width='14%' >&nbsp;</td>
    <td width='15%' >&nbsp;</td>
  </tr>
  <tr>
    <td width='35%' class='session2_titulosCabecera2'>Detalle</td>
    <td width='15%' class='session2_titulosCabecera'>Fecha de Compra</td>
    <td class='session2_titulosCabecera'>Responsable</td>
    <td class='session2_titulosCabecera'>Cantidad</td>
    <td class='session2_titulosCabecera'>Valor Actual</td>
  </tr>";	
}

function insertarFilaFinal($dato,$total){
  	
 echo " <tr>
    <td class='session2_datos2'>$dato[detalle]</td>
    <td class='session2_datos2_1'>$dato[fecha]</td>
    <td class='session2_datos2_1'>$dato[responsable]</td>
    <td class='session2_datos2_1'>$dato[cantidad]</td>
    <td class='session2_datos2_2'>$dato[precio]</td>
  </tr> "; 	
  return $total;
}

function insertarFila($dato,$total){ 
   $total[0] = $total[0] + $dato['cantidad']; 	
   $total[1] = $total[1] + $dato['precio']; 	
   echo "  <tr>
    <td class='session2_datos1'>$dato[detalle]</td>
    <td class='session2_datos1_1'>$dato[fecha]</td>
    <td class='session2_datos1_1'>$dato[responsable]</td>
    <td class='session2_datos1_1'>$dato[cantidad]</td>
    <td class='session2_datos1_2'>".number_format($dato['precio'])."</td>
  </tr>"; 	
  return $total;
}

function insertarTotal($total){
  echo " 
   <tr>   
    <td class='session3_bordeSuperior'>&nbsp;</td>
    <td class='session3_bordeSuperior'>&nbsp;</td>
    <td class='session2_TextoTotal'>TOTAL</td>
    <td class='session2_totalDato'>".number_format($total[0])."</td>
    <td class='session2_totalDato2'>".number_format($total[1])."</td>
   </tr>"; 
}

function limpiarTotal(){
 return array(0,0);	
}

$sqlActivos = "select left(t.nombre,50)as 'tipo',left(a.nombre,40)as 'detalle',date_format(a.fechacompra,'%d/%m/%Y')as 'fecha',left(concat(r.nombre,' ',r.apellido),25)as 'responsable',a.cantidad,a.precio   
from activo a,tipoactivo t,trabajador r 
where a.idtipoactivo=t.idtipoactivo
and r.idtrabajador=a.idtrabajador
and a.estado=1 group by t.nombre;";
$totalGeneral = array(0,0);
$numFila = 0;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla de Seguro</title>
<link rel="stylesheet" href="activos.css" type="text/css" />

</head>

<body>
<?php


 $header = '';



$Consactivos = $db->consulta($sqlActivos);
//$totalItem = $db->getnumRow($sqlSeguro);

//while ($numFila < $totalItem ){
?>
<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="session1_datoempresa"><?php echo strtoupper($datoGeneral['nombrecomercial']);  ?></div>
<div class="session1_datoempresa_nit"><?php echo "NIT: $datoGeneral[nit]"; ?></div>
<div class="session1_logotipo"><?php echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>


<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">ACTIVOS FIJOS</td></tr>
    </table>
 </div>
 
 <div class="session2_datosPersonales">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
   <!--  <tr>
    <td colspan="2" class="session2_tituloPrincipal">MUEBLES Y ENSERES</td>
    <td width="21%" >&nbsp;</td>
    <td width="14%" >&nbsp;</td>
    <td width="15%" >&nbsp;</td>
  </tr>
  <tr>
    <td width="35%" class="session2_titulosCabecera2">Detalle</td>
    <td width="15%" class="session2_titulosCabecera">Fecha de Compra</td>
    <td class="session2_titulosCabecera">Responsable</td>
    <td class="session2_titulosCabecera">Cantidad</td>
    <td class="session2_titulosCabecera">Valor Actual</td>
  </tr>
  
    <tr>
    <td class='session2_datos1'>Detalle</td>
    <td class='session2_datos1_1'>Fecha de Compra</td>
    <td class='session2_datos1_1'>Responsable</td>
    <td class='session2_datos1_1'>Cantidad</td>
    <td class='session2_datos1_2'>Valor Actual</td>
  </tr>
    <tr>
    <td class='session2_datos1'>Detalle</td>
    <td class='session2_datos1_1'>Fecha de Compra</td>
    <td class='session2_datos1_1'>Responsable</td>
    <td class='session2_datos1_1'>Cantidad</td>
    <td class='session2_datos1_2'>Valor Actual</td>
  </tr>
    <tr>
    <td class='session2_datos2'>Detalle</td>
    <td class='session2_datos2_1'>Fecha de Compra</td>
    <td class='session2_datos2_1'>Responsable</td>
    <td class='session2_datos2_1'>Cantidad</td>
    <td class='session2_datos2_2'>Valor Actual</td>
  </tr>
  <tr>   
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class='session2_Texto'>TOTAL</td>
    <td class='session2_totalDato'>22</td>
    <td class='session2_totalDato2'>22</td>
   </tr>-->
  <?php
  $i =0;
  $tipo = "";
     while ($dato = mysql_fetch_array($Consactivos)){
	   $i++;
	   $tipoActual = $dato['tipo'];
		 if ($tipo != $tipoActual) {
			 if ($tipo != ""){
				insertarTotal($totalGeneral);
				$totalGeneral = limpiarTotal();
				echo "<br>";
			 }
			
			 insertarCabecera($tipoActual);
		     $totalGeneral = insertarFila($dato,$totalGeneral);
			 $tipo = $tipoActual;
		 }
		 if ($i == 40)
		 break;
	 }
  
	insertarTotal($totalGeneral);
  ?>
  

</table>

 </div>
 
 
<div class="session4_datos"> 
 <table width="100%" border="0">
  <tr>
    <td colspan="2" class="session4_textos2">CODIGO AFILIADO</td>
    <td width="10%">&nbsp;</td>
    <td width="26%" class="session4_textos_1"><?php echo $datoGeneral['reprepropietario'];?></td>
    <td width="6%">&nbsp;</td>
    <td width="13%" class="session4_textos_1"><?php echo $datoGeneral['cipropietario'];?></td>
    <td width="6%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="7%">&nbsp;</td>
  </tr>
  <tr>
    <td width="5%" class="session4_textos2" align="right">Nº</td>
    <td width="12%" class="session4_textos2" align="left"><?php echo $datoGeneral['numafiliado'];?></td>
    <td>&nbsp;</td>
    <td class="session4_textos">Nombre Empleador o Representante</td>
    <td>&nbsp;</td>
    <td class="session4_textos">Nº de C.I.</td>
    <td>&nbsp;</td>
    <td class="session4_textos">Firma</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

 </div>
 
 
 
 
<div class="session5_pie"> 
    <table width="93%" border="0" align="center">
  <tr>
    <td width="130" align="right">Realizado por.</td>
    <td width="224" ><?php echo $_SESSION['nombre_usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="183" ></td>
    <td width="191">&nbsp;</td>
    <td width="200" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="206">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
</div>


   <?php
/*	  
	       
	   if ($numFila < $totalItem ){
	
	  echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>"; 
	   }
}*/
    ?>
	 



</body>
</html>
<?php
$mpdf=new mPDF('utf-8','Letter'); 
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>