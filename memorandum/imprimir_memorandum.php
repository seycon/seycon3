<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	ob_start();
	 include("../MPDF53/mpdf.php");
	 include('../conexion.php');
	 $db = new MySQL();
	 $idmemorandum = $_GET['idmemorandum'];   
	 $sql = "select left(reprepropietario,25)as 'propietario',imagen from empresa;";
	 $empresa = $db->arrayConsulta($sql);     
	 $sql = "select left(para,100)as 'para',left(referencia,'100')as 'referencia',day(fecha)as 'dia',month(fecha)as 'mes',
	 year(fecha)as 'anio',contenido,left(cc,100)as 'cc' from memorandum where idmemorandum=$idmemorandum;";
	 $reporte = $db->arrayConsulta($sql);
   
   
	 function insertarSession2($para, $ref)
	 {
		 echo "<table width='100%' border='0'>
		  <tr>
		   <td width='15%' align='right' class='session2_textos'>PARA:</td>
		   <td width='85%' class='session2_datos'>$para</td>
		  </tr>
		  <tr>
		   <td align='right' class='session2_textos'>REF.:</td>
		   <td class='session2_datos'>$ref</td>
		  </tr>
		 </table>";
	 }
	 
	 function insertarDatos($datos)
	 {
		echo "<table width='100%' border='0'>
		 <tr>
		  <td height='500' valign='top' class='session2_datosContenido'>$datos</td>
		 </tr>
		</table>"; 
	 }
  
	 function insertarFechaMemorandum($ciudad, $dia, $mes, $anio, $db)
	 {
	   $meses = strtolower($db->mes($mes));	  
	   $meses = ucfirst($meses); 
		echo "<table width='100%' border='0'>
		 <tr>
		   <td width='65%'>&nbsp;</td>
		   <td width='35%' class='session3_datosFecha'>$ciudad, $dia de $meses de $anio</td>
		</tr>
	   </table>";   
	 }
   
   
   function insertarSession4($representante, $cargo)
   {
	  $representante = ucfirst($representante);
	  echo "<table width='100%' border='0'>
	  <tr>
		<td width='16%' align='right' class='session2_datosContenido'>Atte.:</td>
		<td width='18%'>&nbsp;</td>
		<td width='31%'>&nbsp;</td>
		<td width='35%'>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class='session4_datosFirma'>$representante <br> $cargo</td>
		<td>&nbsp;</td>
	  </tr>
	 </table>";   
   }
   
   function session_5($cc){
	echo "<table width='100%' border='0'>
     <tr>
       <td width='10%' align='right' class='session2_datosContenido'>C/c:</td>
       <td width='90%' class='session2_datosContenido'>$cc</td>
     </tr>
    </table>";   
   }
   
?>   


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="memorandum.css" rel="stylesheet" type="text/css" />
<title>Memorandum</title>
</head>

<body>

  <div class="margenPrincipal"></div>
  <div class="session1_logotipo"><?php echo "<img src='../$empresa[imagen]' width='200' height='70'/>"; ?></div>
  <div class="session1_tituloPrincipal">
   <table width="100%" border="0">
     <tr><td class="session1_textoTitulo">MEMORANDUM Nº <?php echo $idmemorandum;?></td></tr>
   </table>
  </div>
  <div class="session2_titulos"><?php insertarSession2($reporte['para'],$reporte['referencia']);?></div>
  <div class="session2_division"></div>
  <div class="session2_contenido"><?php insertarDatos($reporte['contenido']);?></div>
  <div class="session2_division2"></div>
  <div class="session3_fecha"><?php echo insertarFechaMemorandum('Santa Cruz',$reporte['dia'],$reporte['mes'],$reporte['anio'],$db);?></div>
  <div class="session4_firmas"><?php echo insertarSession4($empresa['propietario'],'Gerente General');?></div>  
  <div class="session5"><?php echo session_5($reporte['cc']);?></div>
  
</body>
</html>

<?php
$mpdf = new mPDF('utf-8','Letter'); 
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>