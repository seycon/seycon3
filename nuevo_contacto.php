<?php 
//include_once('bdlocal.php');  
include ('conexion.php');
$db = new MYSQL();
$idcontac = $db->getMaxCampo('idcontacto', 'contacto');


tieneacceso('contacto');

if($_POST['fecha']!=''){


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



mysql_query("INSERT INTO contacto
(idcontacto,fecha,privado,compartira,tipo,nombre,apellido,sexo,email,telefono,direccion,pais,ciudad,skype,facebook,empresa,cargo,telefonooficina,website,emailcorporativo,comentario,estado) VALUES (NULL,'".$_POST['fecha']."','".$_POST['privado']."','".$_POST['compartira']."','".$_POST['tipo']."','".$_POST['nombre']."','".$_POST['apellido']."','".$_POST['sexo']."','".$_POST['email']."','".$_POST['telefono']."','".$_POST['direccion']."','".$_POST['pais']."','".$_POST['ciudad']."','".$_POST['skype']."','".$_POST['facebook']."','".$_POST['empresa']."','".$_POST['cargo']."','".$_POST['telefonooficina']."','".$_POST['website']."','".$_POST['emailcorporativo']."','".$_POST['comentario']."','".$_POST['estado']."');");
header("Location: listar_contacto.php");}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Gestion On-Line - Operaciones de la tabla contacto</title>

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
$("#fecha").datepicker({
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


<style type="text/css">
#formValidado table tr td #tabs #tabs-1 table {
	font-size: 1pc;
}
</style>
</head>
<body>


<form id='formValidado' name='formValidado' method='post' action='nuevo_contacto.php' enctype='multipart/form-data'>
<table width='70%' border='0' align='center' cellpadding='4' cellspacing='3' style='minwidth:895px; font-size: 1pc;'>
<tr style='background-image: url(fondotit.jpg);'>
<td colspan='6' align='center' ><strong class='letrastabla' style='font-size:20px;color:#000' >Contacto</strong></td>
</tr>
<tr>
<td colspan='2' ><strong class='titulostablas'>Nuevo Contacto:</strong></td>
<td></td>
<td align='right'><strong>N&deg;:</strong></td>   
<td colspan="2"><?php echo $db->getMaxCampo('idcontacto', 'contacto')+1;?></td>
</tr>
<tr>
<td colspan='6'> <hr /></td>
</tr>
<tr>
<td colspan='4'>
<input name='enviar' type='submit' class='botongeneral' id='enviar' value='Guardar' />
<input name='cancelar' type='button' class='botongeneral' id='cancelar' value='Cancelar' onclick="location.href='listar__contacto.php'"/>
<input name='imprimir' type='button' class='botongeneral' id='imprimir' value='Imprimir' onclick="location.href='imprimir_contacto.php'"/>
</td>
<td colspan="2" align='center'></td>
</tr>
<tr>
<td width='55' align='right' valign='top'>Fecha<span class='rojo'></span>:</td>
<td width='90' valign='top'><input type='text' id="fecha" name="fecha"  class="date" size="15" />
</td>
<td width='77'  align='right' valign='top'>Compartir a<span class='rojo'></span>:</td>
<td width='120' valign='top'><input type='text' id="compartira" name="compartira"  class="" size="20" />
</td>
<td width='64' align='center'>Estado:</td>
<td width='25' align='center'><input type='checkbox' onclick='checkclick(this.id)'  value='1'  checked='checked' id="estado" name="estado" class="" size="20" /></td>
 </tr>
<tr>
<td width='55' align='right' valign='top'>Privado<span class='rojo'></span>:</td>
<td width='90' valign='top'><input type='checkbox' onclick='checkclick(this.id)' value='1' checked='checked' name="privado" id="privado"  class="" size="32" />
</td>
<td width='77'  align='right' valign='top'>Tipo<span class='rojo'></span>:</td>
<td width='120' valign='top'><input type='text' id="tipo" name="tipo"  class="" size="20" />
</td>
<td colspan="2" align='center'></td>
</tr>

<tr>
<td colspan='6' >
<div id='tabs'>
<ul  style='height:40px;'>
<li><a href='#tabs-1'>Contacto</a></li>
<li><a href='#tabs-2'>Mas datos</a></li>
</ul>
<div id='tabs-1'>
<table width='400' border='0' align='center'>
<tr>
<td width='99' align="right">Nombre<span class='rojo'>*</span>:</td>
<td width='160'>
<input type='text' id="nombre" name="nombre" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Apellido<span class='rojo'>*</span>:</td>
<td width='160'>
<input type='text' id="apellido" name="apellido" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Sexo:</td>
<td width='160'>
<input type='text' id="sexo" name="sexo" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Email:</td>
<td width='160'>
<input type='text' id="email" name="email" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Teléfono<span class='rojo'>*</span>:</td>
<td width='160'>
<input type='text' id="telefono" name="telefono" class="" size="15" /></td>
</tr>
<tr>
<td width='99' align="right">Dirección<span class='rojo'>*</span>:</td>
<td width='160'>
<input type='text' id="direccion" name="direccion" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Pais:</td>
<td width='160'>
<input type='text' id="pais" name="pais" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Ciudad:</td>
<td width='160'>
<input type='text' id="ciudad" name="ciudad" class="" size="20" /></td>
</tr><tr>
<td width='99' align="right">&nbsp;</td>
<td width='160'>&nbsp;</td>
</tr>
</table>
</div>
<div id='tabs-2'>
 <!--Otros Datos-->
<table width='400' border='0' align='center'>
<tr>
<td width='99' align="right">Skype:</td>
<td width='160'>
<input type='text' id="skype" name="skype" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Facebook:</td>
<td width='160'>
<input type='text' id="facebook" name="facebook" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Empresa:</td>
<td width='160'>
<input type='text' id="empresa" name="empresa" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Cargo:</td>
<td width='160'>
<input type='text' id="cargo" name="cargo" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Teléfono Of.:</td>
<td width='160'>
<input type='text' id="telefonooficina" name="telefonooficina" class="" size="15" /></td>
</tr>
<tr>
<td width='99' align="right">Website:</td>
<td width='160'>
<input type='text' id="website" name="website" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Mail Corporativo:</td>
<td width='160'>
<input type='text' id="emailcorporativo" name="emailcorporativo" class="" size="20" /></td>
</tr>
<tr>
<td width='99' align="right">Comentario:</td>
<td width='160'>
<textarea name="comentario" id="comentario" cols="20" rows="5"></textarea><tr>
 
 </table>
</div>
</div> 
</td>
</tr>
</table>
</form>






</body></html>