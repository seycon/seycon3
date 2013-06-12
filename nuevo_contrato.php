<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');  
	$db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Administracion'],'Registro del Sistema','nuevo_contrato.php')) {
	    header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	if (isset($_POST['transaccion'])) {
		
		$fechainicio =  $db->GetFormatofecha($_POST['fechainicio'],'/');
		$fechafinal =  $db->GetFormatofecha($_POST['fechafinal'],'/');
		$fechanacimiento =  $db->GetFormatofecha($_POST['fechanacimiento'],'/');
	
		if ($_POST['transaccion'] == "insertar") {
		    $sql =  "INSERT INTO contrato
		   (idcontrato,contrato,representante,fechainicio,fechafinal
		   ,fechanacimiento,nacionalidad,ci,ciudad,direcciondomicilio
		   ,telefonocontacto,observaciones,estado,idusuario)
			VALUES (NULL,'".filtro($_POST['contrato'])."','".filtro($_POST['representante'])
		   ."','$fechainicio','$fechafinal','$fechanacimiento','".filtro($_POST['nacionalidad'])
		   ."','".filtro($_POST['ci'])."','".filtro($_POST['ciudad'])."','"
		   .filtro($_POST['direcciondomicilio'])."','".filtro($_POST['telefonocontacto'])
		   ."','".filtro($_POST['observaciones'])."',1,$_SESSION[id_usuario]);";    
		}
		
		if ($_POST['transaccion'] == "modificar") {
			$sql = "update contrato set contrato='".filtro($_POST['contrato'])
			."',representante='".filtro($_POST['representante'])
			."',fechainicio='".filtro($fechainicio)."'
			,fechafinal='$fechafinal',fechanacimiento='$fechanacimiento',nacionalidad='"
			.filtro($_POST['nacionalidad'])."',ci='".$_POST['ci']."'
			,ciudad='".$_POST['ciudad']."',direcciondomicilio='".$_POST['direcciondomicilio']
			."',telefonocontacto='".$_POST['telefonocontacto']."'
			,observaciones='".filtro($_POST['observaciones'])
			."',idusuario=$_SESSION[id_usuario] where idcontrato=$_POST[idcontrato]"; 	
		}
	
	    mysql_query($sql);
	    header("Location: nuevo_contrato.php?msj#t6");
	}
	
	$transaccion = "insertar";
	$sql = "SELECT * FROM contrato";
	$numContrato = $db->getnumRow($sql);
	if ($numContrato > 0) {
	    $datoContrato = $db->arrayConsulta($sql);  
	    $transaccion = "modificar";  
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateadministracion.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<script language='javascript' type='text/javascript' src='jscripts/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript' src='conftiny.js'></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script>
$(document).ready(function()
{
$("#fechainicio").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechafinal").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechanacimiento").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});

 var leerURL = function(){
	 var param =  location.search;
	 if (param.length > 0){
	 	 document.getElementById("mensajeRespuesta").innerHTML = "Sus Datos Fueron Guardados Correctamente"; 
	 }
 }
 
  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
	
   if(tecla == 113){ //F2
	 $$("enviar").click();
	  
	}
 }
 
 var $$ = function(id){
	return document.getElementById(id);  
  }
</script>
<!-- InstanceEndEditable -->
<script>
 $(document).ready(function(){ 
	$("ul.submenu").parent().append("<span></span>"); 	
	$("ul.menu li span").click(function() { 
		$(this).parent().find("ul.submenu").slideDown('fast').show(); 
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('slow'); 
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); 
		}, function(){	
			$(this).removeClass("subhover"); 
	});
	
	$("ul.menuH li span").click(function() { 		
		$(this).parent().find("ul.submenu").slideDown('fast').show();  
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('slow'); 
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); 
		}, function(){
			$(this).removeClass("subhover"); 
	});
 
});
</script>
<link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
</head> 
<body >
<div class="franjaCabecera">
<div class="franjaInicial"></div>
<div class="alineadorFrontalSeycon">
<?php
	  function setCabeceraTemplate($titulo) {
		  $cadenaNit = $_SESSION['nit'];
		  if (strlen($cadenaNit) > 15)	{			  
			  $cadenaNit = substr($cadenaNit,0,15);
		  }
		  $cadena = $_SESSION['nombreEmpresa'];
		  if (strlen($cadena) > 35) {				  
			  $cadena = substr($cadena,0,35);
		  }		
		  echo "
			  <div class='headerPrincipal'>
			   <div class='logoEmpresa'></div>			  
				  <div class='tituloEmpresa'>$titulo</div>
				  <div class='nitEmpresa'> 
				   $cadena-$cadenaNit
				  </div>
			  </div>
		  ";
	  }
	  
	  function setMenuTemplate($tituloP, $modulo) {
		 if ($modulo != "Administracion") 
		 echo "<a href='#'>$tituloP</a>"; 
		 $estructura = $_SESSION['estructura'];
		 $menus = $estructura[$modulo];
  	     echo  "<ul class='submenu'>"; 
		 if ($menus != "") {
		   for ($i = 0; $i < count($menus); $i++) {
			   $titulo = $menus[$i]['Menu']; 
			   echo "<li><a href='redireccion.php?mod=$modulo&opt=$titulo'>".$titulo."</a></li>";
		   }		   
		 } 
		 if ($modulo == "Administracion")
		     echo "<li><a href='cerrar.php'>Salir</a></li>";
		 echo "</ul>";
	  }
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td > 
    <?php setCabeceraTemplate("Sistema Empresarial y Contable");?>
    </td>
  </tr>
  <tr>
    <td >
     <div class="menu2"></div>
    </td>
  </tr>
</table>
  <div class="contenedorMenuFrontal">
   <ul class="menu"> 
      <li><?php setMenuTemplate("Inventario", "Inventario");?></li> 
      <li><?php setMenuTemplate("Recursos", "Recursos");?></li>
      <li><?php setMenuTemplate("Activos", "Activo");?></li> 
      <li><?php setMenuTemplate("Ventas", "Ventas");?></li> 
      <li><?php setMenuTemplate("Contabilidad", "Contabilidad");?></li> 
      <li><?php setMenuTemplate("Agenda", "Agenda");?></li>  
    </ul> 
    <div class="usuarioSistema">
      <div class="borde1Usuario"></div>
      <div class="borde2Usuario">
         <div class="sessionHerramienta">
         <ul class="menuH"> 
           <li>
		   <div class="imgHerramienta"></div>
		   <?php setMenuTemplate("Administracion", "Administracion");?></li>               
         </ul>
         </div>
         <div class="nombreUsuario">
		  <?php
          $cadena = $_SESSION['nombre_usuario'];
          $cadena = (strlen($cadena) > 15) ? $cadena = substr($cadena,0,15) : $cadena;
          echo ucfirst($cadena);				
          ?></div>
      </div>
    </div> 
         
    </div>       
   </div>  
</div>
<div class="container">
  <!-- InstanceBeginEditable name="Regioneditable" -->
<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Administración > Registro del Sistema </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Administracion'];
   $privilegios = $db->getOpciones($menus, "Registro del Sistema"); 
   $option = "";
   for ($i = 0; $i < count($privilegios); $i++) {	
	   $link = "location.href='".$privilegios[$i]["Enlace"]."'";
	   $option = "<div class='privilegioMenu' onclick=$link>".$privilegios[$i]['Texto']."</div>". $option;
   } 
   echo $option;
 ?>
</div>
</div>
<br /> 

<table style="width:75%;top:38px;margin: 0 auto;position:relative;" border="0">
 <tr>
 <td>
 <div class="contenedorPrincipal">
<form id='formValidado' name='formValidado' method='post' action='nuevo_contrato.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden"  id="idcontrato" name="idcontrato" value="<?php echo $datoContrato['idcontrato'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php
    if ($numContrato > 0)
     echo $datoContrato['idcontrato'];
    else
     echo "1";
    ?>

</td>
  </tr>
  <tr>
    <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>  
  </tr>
</table>
</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table>
</div>

<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr >
<td colspan='6' align='center' ></td>
</tr>
<tr>
<td colspan='4' >  
  <div id="mensajeRespuesta" class="mensajeRespuesta"></div>
</td>
<td width="38" align="right">&nbsp;</td>
<td width="135"></td>
</tr>
<tr>
<td colspan='4'></td>
<td colspan="2" rowspan='3' align='center'><table width="95%" border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td height="34">&nbsp;</td>
  </tr>
</table></td>
</tr>
<tr>
<td width='212' align='right' valign='top'>Representante<span class='rojo'>*</span>:</td>
<td width='201' valign='top'><input type='text' id="representante" name="representante"  class="required" size="20" value="<?php echo $datoContrato['representante'];?>"/></td>
<td width='192'  align='right' valign='top'>Fecha de Inicio<span class='rojo'></span>:</td>
<td width='230' valign='top'><input type='text' id="fechainicio" name="fechainicio"  class="date" size="10" value="<?php echo $db->GetFormatofecha($datoContrato['fechainicio'],'-');?>"/>
</td>
 </tr>
<tr>
<td colspan="2" align='left' ></td>
<td width='192'  align='right' valign='top'>Fecha Final<span class='rojo'></span>:</td>
<td width='230' valign='top'><input type='text' id="fechafinal" name="fechafinal"  class="date" size="10" value="<?php echo $db->GetFormatofecha($datoContrato['fechafinal']
,'-');?>"/>
</td>
</tr>
<tr>
<td colspan='6' ><hr /></td>
</tr>
<tr>
<td colspan='6' >
<div id='tabs'>
<div id='tabs-1'>
  <table width='663' border='0' align='center'>
    <tr>
      <td align="right"><div align="right">Fecha de Nacimiento:</div></td>
      <td width="146" >
      <input type='text' id="fechanacimiento" name="fechanacimiento" class="date"
       size="10" value="<?php echo $db->GetFormatofecha($datoContrato['fechanacimiento'],'-');?>"/></td>
      <td width="200" align="right"><div align="right">C.I.:</div></td>
      <td colspan="2"  align="left"><input type='text' id="ci" name="ci"
       size="20" value="<?php echo $datoContrato['ci'];?>"/></td>
    </tr>
    <tr>
      <td  align="right"><div align="right">Nacionalidad:</div></td>
      <td ><input type='text' id="nacionalidad" name="nacionalidad" 
      size="20" value="<?php echo $datoContrato['nacionalidad'];?>"/></td>
      <td  align="right"><div align="right">Ciudad:</div></td>
      <td colspan="2"  align="left">
      <select name="ciudad" id="ciudad">
      <?php $db->getDepartamentos($datoContrato['ciudad']);?>
      </select>     
      </td>
    </tr>
    <tr>
      <td  align="right"><div align="right">Dirección: </div></td>
      <td  align="left">
      <input type='text' id="direcciondomicilio" name="direcciondomicilio" 
      size="20" value="<?php echo $datoContrato['direcciondomicilio'];?>"/></td>
      <td ><div align="right">Teléfono:</div></td>
      <td colspan="2" align="left"><input type='text' id="telefonocontacto" name="telefonocontacto"
       size="20" value="<?php echo $datoContrato['telefonocontacto'];?>"/></td>
    </tr>
    <tr>
      <td colspan="5" align="right">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" align="right">&nbsp;</td>
      </tr>
    <tr>
      <td width='137' align="left">Contrato<span class='rojo'>*</span>:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td width="147">Observaciones:</td>
      <td width="11">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="2" align="right">
      <textarea name="contrato" id="contrato" cols="30" rows="8"><?php echo $datoContrato['contrato'];?></textarea>
      </td>
      <td>&nbsp;</td>
      <td colspan="2">
      <textarea name="observaciones" id="observaciones" cols="30" rows="8"><?php echo $datoContrato['observaciones'];?></textarea>
      </td>
      </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
    </tr>
</table>
  <p>&nbsp;</p>
</div>
<div id='tabs-2'>

</div>
</div> 
</td>
</tr>
</table>
</form></div>
</td></tr></table>
<script>
  leerURL();
</script>
<!-- InstanceEndEditable -->  
  <!-- end .footer -->
</div>
 <div class="footerAdm">
  <div class="logo1"><div class="img_logo1"></div></div>
  <div class="logo2"><div class="img_logo2"></div></div>
  <div class="logo3"><div class="img_logo3"></div></div>
  <div class="textoPie1">Seycon 3.0 - Diseñado y Desarrollado por:  Jorge G. Eguez Soliz </div>
  <div class="textoPie2">Copyright &copy; Consultora Guez S.R.L</div>
 </div>
</body>
<!-- InstanceEnd --></html>