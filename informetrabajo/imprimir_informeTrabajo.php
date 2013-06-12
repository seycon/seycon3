<?php
 session_start();
 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
 }
 ob_start();
 include('../conexion.php');
 include('../reportes/literal.php');
 include("../MPDF53/mpdf.php");
 include('../aumentaComa.php');
 $db = new MySQL();
 $logo = $_GET['logo'];
 
//$idinforme = 2;
$idinforme = $_GET['idinformetrabajo'];
$sql = "select idinformetrabajo,nrofactura,estadocobranza,fecha,privado,firmadigital,comentario,
idcliente,idusuario from informetrabajo where idinformetrabajo=$idinforme;";
$maestro = mysql_query($sql);
$maestro = mysql_fetch_array($maestro);


function aumentarDigito($valor){
 $res = $valor;	
  for ($i=strlen($valor);$i<3;$i++)
   $res = "0".$res;
 return $res;	
}



$sql="select * from empresa";
$datoEmpresa = mysql_query($sql);
$datoEmpresa = mysql_fetch_array($datoEmpresa);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Informe de Trabajo</title>
<link rel="stylesheet"  type="text/css" href="informe.css" />
</head>

<body>
<div style="position:absolute; border:1px solid #000; width:95%; height:95%; top:20px; left:20px;"></div>

<table width="722" border="0" align="center">
  <tr>
    <td height="52" class="titulo">INFORME DE TRABAJO</td>
  </tr>
</table>
<br />
<table width="722" border="0" align="center">
  <tr>
    <td width="231" rowspan="2"><?php if ($logo == 'true'){ echo "<img src='../$datoEmpresa[imagen]' width='200' height='70' />";} ?></td>
    <td width="238" height="23">&nbsp;</td>
    <td width="124">&nbsp;</td>
    <td width="111" class="contorno">N° <?php echo aumentarDigito($maestro['idinformetrabajo'])?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" class="contornofecha">Fecha de Emision:<?php echo $db->GetFormatofecha($maestro['fecha'],'-'); ?> </td>
  </tr>
</table>
<br />
<table width="722" height="58" border="0" align="center" cellspacing="0">
  <tr>
    <td class="session1">&nbsp;</td>
    <td class="session1Datos" >&nbsp;</td>
    <td class="session1">&nbsp;</td>
    <td class="session1Datos">&nbsp;</td>
  </tr>
    <tr>
    <td class="session1_filas">&nbsp;</td>
    <td class="session1Datos_filas" >&nbsp;</td>
    <td class="session1_filas">&nbsp;</td>
    <td class="session1Datos_filas">&nbsp;</td>
  </tr>
  <tr>
    <td width="82" class="session1_filas">PARA:</td>
    <td width="222" class="session1Datos_filas" ><?php 
	$id = $maestro['idcliente']; 
	if ($id!=""){
	 $sql = "select left(nombre,20)as nombre,left(nombrecontacto,25) as nombrecontacto from cliente where idcliente=$id";
	 $consulta = mysql_query($sql);
	 $consulta = mysql_fetch_array($consulta);	
	 echo $consulta['nombre'];
	}
	?></td>
    <td width="47" class="session1_filas">DE:</td>
    <td width="343" class="session1Datos_filas"><?php 	
	$idtrabajador = $maestro['idusuario']; 
	$sql = "select concat(t.nombre,' ',t.apellido)as 'nombre',c.cargo,e.nombrecomercial from trabajador t,usuario u,
     cargo c,empresa e  where u.idtrabajador=t.idtrabajador and t.idcargo=c.idcargo and u.idusuario=$idtrabajador;";
	$remitente = mysql_query($sql);
	$remitente = mysql_fetch_array($remitente);
	echo $remitente['nombre']; 
	 
	?></td>
  </tr>
  <tr>
    <td width="82" class="session1_filas"></td>
    <td width="222" class="session1Datos_filas" ><?php 
	if ($id!=""){
		echo $consulta['nombrecontacto'];
	}
	?></td>
    <td width="47" class="session1_filas"></td>
    <td width="343" class="session1Datos_filas"><?php echo $remitente['cargo'];  ?></td>
  </tr>
   <tr>
    <td width="82" class="session1_filas"></td>
    <td width="222" class="session1Datos_filas" ><?php 
	if ($id!=""){
	 
	}
	?></td>
    <td width="47" class="session1_filas"></td>
    <td width="343" class="session1Datos_filas"><?php  echo $remitente['nombrecomercial'];?></td>
  </tr>
  
</table>
<br />
<table width="722" align="center" cellspacing="0" class="tablasession2">
  <tr >
    <td width="92" rowspan="2" class="tituloComentario">COMENTARIO: </td>
    <td width="351" rowspan="2" ><?php echo $maestro['comentario']; ?></td>
    <td width="139" height="39" class="cabecerasession2">N° DE FACTURA EMITIDA</td>
    <td width="130" class="cabecerasession2">ESTADO DEL TRABAJO</td>
  </tr>
  <tr>
    <td class="cabeceradatossession2"><?php echo $maestro['nrofactura']; ?></td>
    <td class="cabeceradatossession2"><?php echo $maestro['estadocobranza']; ?></td>
  </tr>
</table>
<br />
<table width="722" cellspacing="0" cellpadding="0" align="center" class="tablasession2">
  <tr>
    <td width="62" class="cabecerasession3_1">N°</td>
    <td width="505" class="cabecerasession3">DESCRIPCION DEL TRABAJO </td>
    <td width="153" class="cabecerasession3">IMPORTE</td>
  </tr>
  
  
  <?php
  
  $sql ="select descripcion,round(importe,2)as importe from detalleinformetrabajo where idinformetrabajo=$idinforme";
  $detalle = mysql_query($sql);
  $i = 0;
  $totalT = 0;
  while($dato = mysql_fetch_array($detalle)){
  $i++;
  $totalT = $totalT + $dato['importe'];
  echo "
  <tr>
    <td align='center' class='contenidoInicial'>$i</td>
    <td class='contenido'>&nbsp;$dato[descripcion]</td>
    <td class='contenidoFinal'>&nbsp;".number_format($dato['importe'],2)."</td>
  </tr> ";  
  }
  
  for ($j=$i;$j<=18;$j++){
   	if ($j == 18){
		echo "
         <tr>
           <td align='center' class='contenidoLineaFinal'>&nbsp;</td>
           <td class='contenidoLineaFinal'>&nbsp;</td>
           <td class='contenidoLineaFinal'>&nbsp;</td>
         </tr> ";
	}else{
		echo "
          <tr>
            <td ></td>
            <td >".'&nbsp;'."</td>
            <td >".'&nbsp;'."</td>
          </tr> ";
	}
  }
  
  ?>
</table>
<table width="722" border="0" align="center" cellspacing="0">
  <tr>
    <td width="502" height="50" class="raya"><strong>Son:<?php echo strtoupper(NumerosALetras($totalT)); ?></strong></td>
    <td width="216" class="total">Total : <?php echo convertir($totalT);?></td>
  </tr>
</table>
<br />
<table width="722" border="0" align="center" cellspacing="0">
  <tr>
    <td width="503">&nbsp;</td>
    <td width="126" class="contornofechaimpresion">FECHA DE IMPRESION</td>
    <td width="87" class="semicontorno"><?php echo date("d/m/Y");?></td>
  </tr>
</table>



<div class="sessionFinal_cobranza">
<table width="100%" border="0" align="center">
  <tr>
    <td width="6%">&nbsp;</td>
    <td width="46%" class="letra_sessionFinal">Dir.:<?php echo $datoEmpresa['direccion']?></td>
    <td width="23%" class="letra_sessionFinal">Telf.:<?php echo $datoEmpresa['telefono']?></td>
    <td width="25%" class="letra_sessionFinal"><?php echo $datoEmpresa['website']?></td>
  </tr>
</table>
</div>
<div class="sessionFinal_cobranza_2">
<table width="100%" border="0">
  <tr><td class="letra_sessionFinal" align="center"><?php echo $datoEmpresa['ciudad'];?></td></tr>
</table>
</div>

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

