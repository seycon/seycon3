<?php include_once('bdlocal.php');  

tieneacceso('depreciacion');

if($_POST['idcuenta']!=''){


$destino='';
$ahora=time();
$archivo = $_FILES['foto']['name'];
$archivo1 = $_FILES['imagen']['name'];
if ($archivo != '') {
$destino =  "files/$ahora".$archivo;
copy($_FILES['foto']['tmp_name'],$destino);
}
if ($archivo1 != '') {
$destino =  "files/$ahora".$archivo1;
copy($_FILES['imagen']['tmp_name'],$destino);
}



mysql_query("INSERT INTO depreciacion
(iddepreciacion,idcuenta,porcentdepreciacion,estado) VALUES (NULL,'".$_POST['idcuenta']."','".$_POST['porcentdepreciacion']."','".$_POST['estado']."');");
header("Location: listar_depreciacion.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Gestion On-Line - Operaciones de la tabla depreciacion</title>

<!-- TinyMCE -- aumentar un simbolo de mayor para activar el editor de texto avanzado TinyMCE
<script language='javascript' type='text/javascript' src='jscripts/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript' src='conftiny.js'></script>
<!-- /TinyMCE -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="js/jquery.validate.js"></script>

<script>	$(function() {	$( '#tabs' ).tabs();	});  </script>

<script>
$(document).ready(function()
{

$("#formValidado").validate({});
});
</script>

<script>
  function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
</script>


</head>
<body>


<form id='formValidado' name='formValidado' method='post' action='nuevo_depreciacion.php' enctype='multipart/form-data'>
<table width='70%' border='0' align='center' cellpadding='4' cellspacing='3' style='minwidth:895px;'>
<tr style='background-image: url(fondotit.jpg);'>
<td colspan='5' align='center' ><strong class='letrastabla' style='font-size:20px;color:#000' >Depreciacion</strong></td>
</tr>
<tr>
<td colspan='2' ><strong class='titulostablas'>Nuevo Depreciacion:</strong></td>
<td></td>
<td align='right'><strong>N&deg;:</strong></td>   
<td>&nbsp;</td>
</tr>
<tr>
<td colspan='5'> <hr /></td>
</tr>
<tr>
<td colspan='4'>
<input name='enviar' type='submit' class='botongeneral' id='enviar' value='Guardar' />
<input name='cancelar' type='button' class='botongeneral' id='cancelar' value='Cancelar' onclick="location.href='listar_depreciacion.php'"/>
<input name='imprimir' type='button' class='botongeneral' id='imprimir' value='Imprimir' onclick="location.href='imprimir_depreciacion.php'"/>
</td>
<td width='118' rowspan='3' align='center'></td>
</tr>
<tr>
<td width='88' align='right' valign='top'>Idcuenta<span class='rojo'></span>:</td>
<td width='181' valign='top'><input type='text' id="idcuenta" name="idcuenta"  class="number" size="32" />
</td>
<td width='125'  align='right' valign='top'>Estado<span class='rojo'></span>:</td>
<td width='150' valign='top'><input type='checkbox' onclick='checkclick(this.id)' value='1' checked='checked' name="estado" id="estado"  class="" size="32" />
</td>
 </tr>
<tr>
<td width='88' align='right' valign='top'>Porcentdepreciacion<span class='rojo'></span>:</td>
<td width='181' valign='top'><input type='text' id="porcentdepreciacion" name="porcentdepreciacion"  class="number" size="32" />
</td>
<td width='125'  align='right' valign='top'><span class='rojo'></span>:</td>
<td width='150' valign='top'> class="" size="32" />
</td>
</tr>
<tr>
<td colspan='5' ><div align='left' class='masagua'> Los campos con <span class='rojo'>(*) </span>son requeridos:</div></td>
</tr>




<tr>
<td colspan='5' >
<div id='tabs'>
<ul  style='height:40px;'>
<li><a href='#tabs-1'>Depreciacion</a></li>
<li><a href='#tabs-2'>Otros datos</a></li>
</ul>
<div id='tabs-1'>
<table width='400' border='0' align='center'>
</table>
</div>
<div id='tabs-2'>
 Otros Datos
</div>
</div> 
</td>
</tr>
</table>
</form>






</body></html>