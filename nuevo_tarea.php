<?php include_once('bdlocal.php');  

tieneacceso('tarea');

if($_POST['titulo']!=''){


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



mysql_query("INSERT INTO tarea
(idtarea,titulo,fechaincio,fechafinalizacion,privado,destinartareaa,asignadopor,trabajarcon,tareapendiente,desarrollotarea,activartarea,tareaterminada,estado) VALUES (NULL,'".$_POST['titulo']."','".$_POST['fechaincio']."','".$_POST['fechafinalizacion']."','".$_POST['privado']."','".$_POST['destinartareaa']."','".$_POST['asignadopor']."','".$_POST['trabajarcon']."','".$_POST['tareapendiente']."','".$_POST['desarrollotarea']."','".$_POST['activartarea']."','".$_POST['tareaterminada']."','".$_POST['estado']."');");
header("Location: listar_tarea.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Gestion On-Line - Operaciones de la tabla tarea</title>

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
$("#fechaincio").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'yy/mm/dd'
});
$("#fechafinalizacion").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'yy/mm/dd'
});

$("#formValidado").validate({});
});
</script>

<script>
  function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
</script>


</head>
<body>


<form id='formValidado' name='formValidado' method='post' action='nuevo_tarea.php' enctype='multipart/form-data'>
<table width='70%' border='0' align='center' cellpadding='4' cellspacing='3' style='minwidth:895px;'>
<tr style='background-image: url(fondotit.jpg);'>
<td colspan='5' align='center' ><strong class='letrastabla' style='font-size:20px;color:#000' >Tarea</strong></td>
</tr>
<tr>
<td colspan='2' ><strong class='titulostablas'>Nuevo Tarea:</strong></td>
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
<input name='cancelar' type='button' class='botongeneral' id='cancelar' value='Cancelar' onclick="location.href='listar_tarea.php'"/>
<input name='imprimir' type='button' class='botongeneral' id='imprimir' value='Imprimir' onclick="location.href='imprimir_tarea.php'"/>
</td>
<td width='118' rowspan='3' align='center'></td>
</tr>
<tr>
<td width='88' align='right' valign='top'>Titulo<span class='rojo'></span>:</td>
<td width='181' valign='top'> 	 <input type='text' id="titulo" name="titulo"  class="" size="32" />
</td>
<td width='125'  align='right' valign='top'>Fechafinalizacion<span class='rojo'></span>:</td>
<td width='150' valign='top'><input type='text' id="fechafinalizacion" name="fechafinalizacion"  class="date" size="32" />
</td>
 </tr>
<tr>
<td width='88' align='right' valign='top'>Fechaincio<span class='rojo'></span>:</td>
<td width='181' valign='top'><input type='text' id="fechaincio" name="fechaincio"  class="date" size="32" />
</td>
<td width='125'  align='right' valign='top'>Privado<span class='rojo'></span>:</td>
<td width='150' valign='top'><input type='checkbox' onclick='checkclick(this.id)' value='1' checked='checked' name="privado" id="privado"  class="" size="32" />
</td>
</tr>
<tr>
<td colspan='5' ><div align='left' class='masagua'> Los campos con <span class='rojo'>(*) </span>son requeridos:</div></td>
</tr>




<tr>
<td colspan='5' >
<div id='tabs'>
<ul  style='height:40px;'>
<li><a href='#tabs-1'>Tarea</a></li>
<li><a href='#tabs-2'>Otros datos</a></li>
</ul>
<div id='tabs-1'>
<table width='400' border='0' align='center'>
<tr>
<td width='99' align="right">Destinartareaa:</td>
<td width='160'>
<input type='text' id="destinartareaa" name="destinartareaa" class="" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Asignadopor:</td>
<td width='160'>
<input type='text' id="asignadopor" name="asignadopor" class="" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Trabajarcon:</td>
<td width='160'>
<input type='text' id="trabajarcon" name="trabajarcon" class="" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Tareapendiente<span class='rojo'>*</span>:</td>
<td width='160'>
<textarea name="tareapendiente" id="tareapendiente" cols="20" rows="5"></textarea><tr>
<td width='99' align="right">Desarrollotarea<span class='rojo'>*</span>:</td>
<td width='160'>
<textarea name="desarrollotarea" id="desarrollotarea" cols="20" rows="5"></textarea><tr>
<td width='99' align="right">Activartarea:</td>
<td width='160'>
<input type='checkbox' onclick='checkclick(this.id)'  value='1'  checked='checked' id="activartarea" name="activartarea" class="" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Tareaterminada:</td>
<td width='160'>
<input type='checkbox' onclick='checkclick(this.id)'  value='1'  checked='checked' id="tareaterminada" name="tareaterminada" class="" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Estado:</td>
<td width='160'>
<input type='checkbox' onclick='checkclick(this.id)'  value='1'  checked='checked' id="estado" name="estado" class="" size="32" /></td>
</tr>
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