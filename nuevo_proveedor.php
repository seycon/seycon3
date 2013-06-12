<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');  
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Proveedores','nuevo_proveedor.php','listar_proveedor.php');
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}
		
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
    if (isset($_POST['transaccion'])) {
	   $fechainicio = $db->GetFormatofecha($_POST['fechainicio'],"/");
		
		if ($_POST['transaccion'] == "insertar") {
			$sql = "INSERT INTO proveedor
			(idproveedor,nombre,nombrenit,contacto,nit,direccion,telefono
			,email,skype,sitioweb,facebook,tiempodecredito,nombrebanco
			,cuentabancaria,fechainicio,rubro,pais,ciudad,estado,idusuario) 
			VALUES (NULL,'".filtro($_POST['nombre'])."','".filtro($_POST['nombrenit'])
			."','".filtro($_POST['contacto'])."','".filtro($_POST['nit'])
			."','".filtro($_POST['direccion'])."','".filtro($_POST['telefono'])
			."','".filtro($_POST['email'])."','".filtro($_POST['skype'])
			."','".filtro($_POST['sitioweb'])."','".filtro($_POST['facebook'])
			."','".filtro($_POST['tiempodecredito'])."','".filtro($_POST['nombrebanco'])
			."','".filtro($_POST['cuentabancaria'])."','".$fechainicio."','".filtro($_POST['rubro'])."','".
			filtro($_POST['pais'])."','".filtro($_POST['ciudad'])."','1',$_SESSION[id_usuario]);";
			mysql_query($sql);
		}
		
		if ($_POST['transaccion'] == "modificar") {
			$sql = "UPDATE proveedor SET nombre='".filtro($_POST['nombre'])
			."', nombrenit='".filtro($_POST['nombrenit'])."', contacto='".filtro($_POST['contacto'])
			."', nit='".filtro($_POST['nit'])."', direccion='".filtro($_POST['direccion'])
			."', telefono='".filtro($_POST['telefono'])."', email='".filtro($_POST['email'])
			."', skype='".filtro($_POST['skype'])."', sitioweb='".filtro($_POST['sitioweb'])
			."', facebook='".filtro($_POST['facebook'])."', tiempodecredito='".
			filtro($_POST['tiempodecredito'])."', nombrebanco='".filtro($_POST['nombrebanco'])
			."', cuentabancaria='".filtro($_POST['cuentabancaria'])."', fechainicio='"
			.filtro($fechainicio)."', rubro='".filtro($_POST['rubro'])
			."', pais='".filtro($_POST['pais'])."', ciudad='".filtro($_POST['ciudad'])
			."',idusuario=$_SESSION[id_usuario] WHERE idproveedor= '".$_POST['idproveedor']."';";	
			mysql_query($sql);
		}		
		header("Location: nuevo_proveedor.php#t8");
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
		$transaccion = "modificar";	
		$sql = "SELECT * FROM proveedor WHERE idproveedor= ".$_GET['idproveedor'];
		$datoProveedor = $db->arrayConsulta($sql);  
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateinventario.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
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
  $("#fechanacimiento").datepicker({
  showOn: "button",
  buttonImage: "css/images/calendar.gif",
  buttonImageOnly: true,
  dateFormat: 'dd/mm/yy'
  });
  
  $("#formValidado").validate({});
  });


  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115){ //F4
    if ($$("cancelar") != null)
	 location.href='listar_proveedor.php#t8';
   }
	
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

<div class="menuTituloFormulario"> Inventario > Proveedores </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Proveedores"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_proveedor.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
        <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
        <?php 
            if ($fileAcceso['File'] == "Si") {
			   echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
			   value="Cancelar [F4]" onClick="location.href=&#039listar_proveedor.php#t8&#039"/>';	
            }
        ?>
     
     </td>
    <td>
    <input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden" id="idproveedor" name="idproveedor" value="<?php echo $datoProveedor['idproveedor'];?>" />
   </td>
    <td colspan="3" align='right'><table width="356" border="0">
      <tr>
        <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
        <td width="142">
        <?php
              if (isset($_GET['idproveedor'])) {
                echo $_GET['idproveedor'];  
              } else {
                echo $db->getNextID("idproveedor", "proveedor");  
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
<td colspan='5' align='center' ></td>
</tr>

<tr>
<td colspan='4'>

</td>
<td width='3' rowspan='3' align='center'>&nbsp;</td>
</tr>
<tr>
<td width='193' align='right' valign='top'>Nombre Comercial<span class='rojo'>*</span>:</td>
<td width='120' valign='top'> 	 
<input type='text' id="nombre" name="nombre"  class="required" size="20" value="<?php echo $datoProveedor['nombre'];?>"/>
</td>
<td width='81'  align='right' valign='top'>Contacto<span class='rojo'></span>:</td>
<td width='224' valign='top'>
<input type='text' id="contacto" name="contacto"  class="" size="20" value="<?php echo $datoProveedor['contacto'];?>"/>
</td>
 </tr>
<tr>
<td width='193' align='right' valign='top'>Nombre Nit<span class='rojo'>*</span>:</td>
<td width='120' valign='top'>
<input type='text' id="nombrenit" name="nombrenit"  class="required" size="20" value="<?php echo $datoProveedor['nombrenit'];?>"/>
</td>
<td width='81'  align='right' valign='top'>Nit<span class='rojo'></span>:</td>
<td width='224' valign='top'>
<input type='text' id="nit" name="nit"  class="number" size="20" value="<?php echo $datoProveedor['nit'];?>"/>
</td>
</tr>
<tr>
<td colspan='5' ></td>
</tr>
<tr>
  <td colspan='5' ><hr /></td>
</tr>
<tr>
<td colspan='5' >
<div id='tabs-1' style="height:300px;">
<table width='664' border='0' align='center'>
<tr>
<td width='135' align="right"><div align="right">Dirección:</div></td>
<td width='166'>
<input type='text' id="direccion" name="direccion" size="20" value="<?php echo $datoProveedor['direccion'];?>"/>
</td>
<td width='125'><div align="right">Nombre del Banco:</div></td>
<td width='220'>
<input type='text' id="nombrebanco" name="nombrebanco" size="20" value="<?php echo $datoProveedor['nombrebanco'];?>"/>
</td>
</tr>
<tr>
<td width='135' align="right"><div align="right">Teléfono:</div></td>
<td width='166'><input type='text' id="telefono" name="telefono" size="20" value="<?php echo $datoProveedor['telefono'];?>"/>
</td>
<td width='125'><div align="right">Cuenta Bancaria:</div></td>
<td width='220'>
<input type='text' id="cuentabancaria" name="cuentabancaria" size="20" value="<?php echo $datoProveedor['cuentabancaria'];?>"/>
</td>
</tr>
<tr>
<td width='135' align="right"><div align="right">Email:</div></td>
<td width='166'>
<input type='text' id="email" name="email" class="" size="20" value="<?php echo $datoProveedor['email'];?>"/>
</td>
<td width='125'><div align="right">Fecha de Inicio:</div></td>
<td width='220'>
<input type='text' id="fechainicio" name="fechainicio" class="date" size="12"
 value="<?php echo $db->GetFormatofecha($datoProveedor['fechainicio'],"-");?>"/></td>
</tr>
<tr>
<td width='135' align="right"><div align="right">Skype:</div></td>
<td width='166'>
<input type='text' id="skype" name="skype" size="20" value="<?php echo $datoProveedor['skype'];?>"/>
</td>
<td width='125' align="right">Rubro:</td>
<td width='220'>
<select id="rubro" name="rubro">
  <?php
	 $selec = $datoProveedor['rubro']; 
	 $tipo = array("Servicio", "Comercial", "Industrial");
	 for ($i = 0; $i < count($tipo); $i++) {
		$atributo = ""; 
		if ($selec == $tipo[$i]) {
		    $atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
	 }	
  ?>  
</select>
</td>
</tr>
<tr>
<td width='135' align="right"><div align="right">Sitio Web:</div></td>
<td width='166'>
<input type='text' id="sitioweb" name="sitioweb" class="" size="20" value="<?php echo $datoProveedor['sitioweb'];?>"/></td>
<td width='125'><div align="right">País:</div></td>
<td width='220'><input type='text' id="pais" name="pais" class="" size="20" value="<?php echo $datoProveedor['pais'];?>"/></td>
</tr>
<tr>
<td width='135' align="right"><div align="right">Facebook:</div></td>
<td width='166'>
<input type='text' id="facebook" name="facebook" size="20" value="<?php echo $datoProveedor['facebook'];?>"/></td>
<td width='125'><div align="right">Ciudad:</div></td>
<td width='220'>
<select id="ciudad" name="ciudad">
<?php
  $db->getDepartamentos($datoProveedor['ciudad']);
?>
</select></td>
</tr>
<tr>
<td width='135' align="right"><div align="right">Tiempo de Crédito:</div></td>
<td width='166'>
<input type='text' id="tiempodecredito" name="tiempodecredito" size="20" value="<?php echo $datoProveedor['tiempodecredito'];?>"/>
</td>
<td width='125'><div align="right"></div></td>
<td width='220'>&nbsp;</td>
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