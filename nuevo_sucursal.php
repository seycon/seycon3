<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include_once('conexion.php');  
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Administracion'],'Sucursal','nuevo_sucursal.php','listar_sucursal.php');
	if ($fileAcceso['Acceso'] == "No") {
	  header("Location: cerrar.php");	
	}
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	
	if (isset($_POST['transaccion'])) {  
		if ($_POST['transaccion'] == "insertar") { 
			$fechaLimEmision = $db->GetFormatofecha($_POST['fechalimitemision'],'/');
			$sql = "INSERT INTO sucursal(idsucursal,nombrecomercial,telefono,numsucursal
			,direccion,ciudad,numautorizacion,llavedosificacion,numfactuinicial
			,numfactfinal,fechalimitemision,numfacturaactual,estado,idusuario) VALUES (NULL,'".
			filtro($_POST['nombrecomercial'])."','".filtro($_POST['telefono'])."','".
			filtro($_POST['numsucursal'])."','".filtro($_POST['direccion'])."','".
			filtro($_POST['ciudad'])."','".
			filtro($_POST['numautorizacion'])."','".filtro($_POST['llavedosificacion'])."','".
			filtro($_POST['numfactuinicial'])."','".filtro($_POST['numfactfinal'])."','".
			filtro($fechaLimEmision)."','".filtro($_POST['numfactuinicial'])."','1',$_SESSION[id_usuario]);";  
		}
	  
		if ($_POST['transaccion'] == "modificar") {
			$fechaLimEmision = $db->GetFormatofecha($_POST['fechalimitemision'],'/');
			$sql = "UPDATE sucursal SET nombrecomercial='".filtro($_POST['nombrecomercial'])
			."', telefono='".filtro($_POST['telefono'])."', numsucursal='"
			.filtro($_POST['numsucursal'])."',direccion='".filtro($_POST['direccion'])
			."', ciudad='".filtro($_POST['ciudad'])."', numautorizacion='"
			.filtro($_POST['numautorizacion'])."', llavedosificacion='"
			.filtro($_POST['llavedosificacion'])."', numfactuinicial='".
			filtro($_POST['numfactuinicial'])."', numfactfinal='"
			.filtro($_POST['numfactfinal'])."', fechalimitemision='$fechaLimEmision'
			,numfacturaactual='".filtro($_POST['numfactuinicial'])
			."',idusuario=$_SESSION[id_usuario] WHERE idsucursal= '".$_POST['idsucursal']."';";   
		}
		mysql_query($sql);  
		header("Location: nuevo_sucursal.php#t10");
	}
	
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	   $transaccion = "modificar";	
	   $sql = "SELECT * FROM sucursal WHERE idsucursal= ".$_GET['idsucursal'];
	   $datoSucursal = $db->arrayConsulta($sql);  
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
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script>
$(document).ready(function()
{
$("#fechalimitemision").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});

 
 function soloNumeros(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
  return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0);
 }

 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115){ //F4
     if ($$("cancelar") != null)
      location.href = 'listar_sucursal.php#t10';	 
   }
	
   if(tecla == 113){ //F2     
	 document.formValidado.submit();	  
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

<div class="menuTituloFormulario"> Admininstración > Gestionar Sucursal </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Administracion'];
   $privilegios = $db->getOpciones($menus, "Sucursal"); 
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
<br />
<table style="width:75%;top:15px;margin: 0 auto;position:relative;" border="0">
 <tr>
 <td>
<div class="contenedorPrincipal">
<form id='formValidado' name='formValidado' method='post' action='nuevo_sucursal.php' enctype='multipart/form-data'>

<div class="contemHeaderTop">
<table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
    &nbsp;&nbsp; 
    <?php 
	  if ($fileAcceso['File'] == "Si") {
		 echo '<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
		 value="Cancelar [F4]" onClick="location.href=&#039listar_sucursal.php#t10&#039"/>';	
	  }
	?>
 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idsucursal" name="idsucursal" value="<?php echo $datoSucursal['idsucursal'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php 
	if (isset($_GET['idsucursal'])) {
	 echo $_GET['idsucursal'];	
    } else {
  	 echo $db->getNextID("idsucursal","sucursal");
	}
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
<td colspan='6' align='center' ></tr>
<tr>
<td colspan='2' ></td>
<td></td>
<td colspan="3" align='right'></td>   
</tr>
<tr>
  <td colspan='4'></td>      
  <td colspan="2" rowspan='6' align='center'>&nbsp;</td>
</tr>
<tr>
<td colspan='4'> </td>
</tr>
<tr>
  <td width='231' align='right' valign='top'>Nombre Comercial<span class='rojo'>*</span>:</td>
  <td width='179' valign='top'> 	 <input type='text' id="nombrecomercial" name="nombrecomercial"  
  class="required" size="20" value="<?php echo $datoSucursal['nombrecomercial'];?>" />
  </td>
  <td width='183'  align='right' valign='top'>Nº de Sucursal:</td>
  <td width='257' valign='top'><input type='text' id="numsucursal" name="numsucursal" class="number" size="10" value="<?php echo $datoSucursal['numsucursal'];?>" /></td>
</tr>
<tr>
  <td align='right' valign='top'>Dirección:</td>
  <td valign='top'>
  <input type='text' id="direccion" name="direccion" class="" size="20" value="<?php echo $datoSucursal['direccion'];?>"/>
  </td>
  <td  align='right' valign='top'>Teléfono<span class='rojo'></span>:</td>
  <td valign='top'>
  <input type='text' id="telefono" name="telefono"  class="" size="10" value="<?php echo $datoSucursal['telefono'];?>"/>
  </td>
</tr>
<tr>
  <td align='right' valign='top'>Ciudad:</td>
  <td valign='top'>
   <select name="ciudad" id="ciudad">
      <?php echo $db->getDepartamentos($datoSucursal['ciudad']);?>
    </select>
        </td>
  <td  align='right' valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
</tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td  align='right' valign='top'>&nbsp;</td>
  <td valign='top'><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />  
  </td>
</tr>
<tr>
  <td colspan='6' >  
  <div align='left' style="font-size:10px;"> 
   <hr />
  </div></td>
</tr>
<tr>
<td colspan='6' >
<div id='tabs'>
  <table width='100%' border="0" align="center" >
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="188"><div align="right">Nº de Autorización:</div></td>
      <td width="170"><input type='text' id="numautorizacion" name="numautorizacion" class="number" size="15"
       value="<?php echo $datoSucursal['numautorizacion'];?>"/></td>
      <td width="137"><div align="right">Nº de Factura Inicial:</div></td>
      <td width="209"><input type='text' id="numfactuinicial" name="numfactuinicial" class="number" size="10" 
       value="<?php echo $datoSucursal['numfactuinicial'];?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Llave de Dosificación:</div></td>
      <td><input type='text' id="llavedosificacion" name="llavedosificacion" class="" size="15" value="<?php echo $datoSucursal['llavedosificacion'];?>"/></td>
      <td><div align="right">Nº de Factura Final:</div></td>
      <td><input type='text' id="numfactfinal" name="numfactfinal" class="number" size="10" 
       value="<?php echo $datoSucursal['numfactfinal'];?>"/></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><div align="right">Fecha Limite de Emisión:</div></td>
      <td><input type='text' id="fechalimitemision" name="fechalimitemision" class="date" size="10"
       value="<?php $date = $datoSucursal['fechalimitemision'];
	  if ($date != "")
	   echo $db->GetFormatofecha($date,'-');
	  ?>"/></td>
    </tr>
  </table> 

</div> 
</td>
</tr>
</table>
</form>
</div>
</td></tr></table>


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