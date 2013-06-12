<?php include_once('bdlocal.php');  

tieneacceso('planilla');

if($_POST['idtrabajador']!=''){


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



mysql_query("INSERT INTO planilla
(idplanilla,idtrabajador,fecha,diastrabajados,horasextras,bonodesempeno,otrosbonos,anticipo,otrosdescuentos,responsable,iddatosplanilla,estado) VALUES (NULL,'".$_POST['idtrabajador']."','".$_POST['fecha']."','".$_POST['diastrabajados']."','".$_POST['horasextras']."','".$_POST['bonodesempeno']."','".$_POST['otrosbonos']."','".$_POST['anticipo']."','".$_POST['otrosdescuentos']."','".$_POST['responsable']."','".$_POST['iddatosplanilla']."','".$_POST['estado']."');");
header("Location: listar_planilla.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Gestion On-Line - Operaciones de la tabla planilla</title>

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


</head>
<body>


<form id='formValidado' name='formValidado' method='post' action='nuevo_planilla.php' enctype='multipart/form-data'>
<table width='70%' border='0' align='center' cellpadding='4' cellspacing='3' style='minwidth:895px;'>
<tr style='background-image: url(fondotit.jpg);'>
<td colspan='5' align='center' ><strong class='letrastabla' style='font-size:20px;color:#000' >Planilla</strong></td>
</tr>
<tr>
<td colspan='2' ><strong class='titulostablas'>Nuevo Planilla:</strong></td>
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
<input name='cancelar' type='button' class='botongeneral' id='cancelar' value='Cancelar' onclick="location.href='listar_planilla.php'"/>
<input name='imprimir' type='button' class='botongeneral' id='imprimir' value='Imprimir' onclick="location.href='imprimir_planilla.php'"/>
</td>
<td width='118' rowspan='3' align='center'></td>
</tr>
<tr>
<td width='88' align='right' valign='top'>Idtrabajador<span class='rojo'>*</span>:</td>
<td width='181' valign='top'><input type='text' id="idtrabajador" name="idtrabajador"  class="required digits" size="32" />
</td>
<td width='125'  align='right' valign='top'>Diastrabajados<span class='rojo'></span>:</td>
<td width='150' valign='top'><input type='text' id="diastrabajados" name="diastrabajados"  class="number" size="32" />
</td>
 </tr>
<tr>
<td width='88' align='right' valign='top'>Fecha<span class='rojo'></span>:</td>
<td width='181' valign='top'><input type='text' id="fecha" name="fecha"  class="date" size="32" />
</td>
<td width='125'  align='right' valign='top'>Horasextras<span class='rojo'></span>:</td>
<td width='150' valign='top'><input type='text' id="horasextras" name="horasextras"  class="number" size="32" />
</td>
</tr>
<tr>
<td colspan='5' ><div align='left' class='masagua'> Los campos con <span class='rojo'>(*) </span>son requeridos:</div></td>
</tr>




<tr>
<td colspan='5' >
<div id='tabs'>
<ul  style='height:40px;'>
<li><a href='#tabs-1'>Planilla</a></li>
<li><a href='#tabs-2'>Otros datos</a></li>
</ul>
<div id='tabs-1'>
<table width='400' border='0' align='center'>
<tr>
<td width='99' align="right">Bonodesempeno:</td>
<td width='160'>
<input type='text' id="bonodesempeno" name="bonodesempeno" class="number" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Otrosbonos:</td>
<td width='160'>
<input type='text' id="otrosbonos" name="otrosbonos" class="number" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Anticipo:</td>
<td width='160'>
<input type='text' id="anticipo" name="anticipo" class="number" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Otrosdescuentos:</td>
<td width='160'>
<input type='text' id="otrosdescuentos" name="otrosdescuentos" class="number" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Responsable:</td>
<td width='160'>
<input type='text' id="responsable" name="responsable" class="" size="32" /></td>
</tr>
<tr>
<td width='99' align="right">Iddatosplanilla:</td>
<td width='160'>
<input type='text' id="iddatosplanilla" name="iddatosplanilla" class="number" size="32" /></td>
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