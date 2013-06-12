<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: index.php");	
	}
	
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Recursos'],'Parámetros de RRHH','nuevo_datosvacaciones.php')) {
	  header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena));
	}
	
	
	if (isset($_POST['transaccion'])) {	
	  $sql = "select iddatosvacaciones from datosvacaciones";
	  $datos = $db->arrayConsulta($sql);
	  
		if ($datos['iddatosvacaciones'] == '') {
		  $sql =  "INSERT INTO datosvacaciones(rango1,rango2,rango3) 
		  VALUES ('".filtro($_POST['rango1'])."','".filtro($_POST['rango2'])
		  ."','".filtro($_POST['rango3'])."');"; 
		} else {
		   $sql = "update datosvacaciones set rango1='".filtro($_POST['rango1'])
		   ."',rango2='".filtro($_POST['rango2'])."',rango3='".filtro($_POST['rango3'])."'"; 
		}
	  
		$db->consulta($sql);
		header("Location: nuevo_datosvacaciones.php?msj#t3");
	}
	
	$sql = "select * from datosvacaciones";
	$datosPlanilla = $db->arrayConsulta($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templaterecursos.dwt.php" codeOutsideHTMLIsLocked="false" -->
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
<link rel="stylesheet" href="planillas/datosplanilla.css" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script>
 $(document).ready(function()
 {
   $("#formValidado").validate({});
 });

  var leerURL = function() {
	 var param =  location.search;
	 if (param.length > 0) {
	 	 document.getElementById("mensajeRespuesta").innerHTML = "Sus datos fueron guardados correctamente."; 
	 }
  }
 
  document.onkeydown = function(e) {
   tecla = (window.event) ? event.keyCode : e.which;  
    if (tecla == 113) { //F2
	    document.formValidado.submit();	  
	}
  }
  
  function $$(id) {
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
<div class="menuTituloFormulario"> Recursos > Parámetros de RRHH </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Parámetros de RRHH"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_datosvacaciones.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
<table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' /> 
 </td>
<td><input type="hidden" id="transaccion" name="transaccion" value="insertar"  />
  <div id="mensajeRespuesta" class="mensajeRespuesta"></div></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="142" colspan="2" align="center"><strong>Parametros de Vacaciones</strong></td>
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
<tr>
<td colspan='4' align='center' ></td>
</tr>
<tr>
<td colspan='4' >
<br />
<table width="90%" align="center" border="0" class="session1_bordes">
  <tr>
    <td colspan="12">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="12">
      <table width="320" border="0" align="center">
        <tr>
          <td class="cabeceras">Vacaciones</td>
          </tr>
        </table>
        </td>
  </tr>
  <tr>
    <td colspan="2" >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" >&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td width="13%" class="cabeceras">Años</td>
    <td width="5%">&nbsp;</td>
    <td width="12%" class="cabeceras">Días</td>
    <td width="18%" >&nbsp;</td>
    <td colspan="2" ></td>
    <td width="1%">&nbsp;</td>
    <td width="1%" ></td>
    <td width="13%">&nbsp;</td>
  </tr>
  <tr>
    <td width="7%">&nbsp;</td>
    <td width="16%" ></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="1%" ></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ></td>
    <td>&nbsp;</td>
    <td class="session1_bordes">1 - 5 Años</td>
    <td>&nbsp;</td>
    <td colspan="2"><input type='text' id="rango1" name="rango1" class="required number" size="10" 
    value="<?php echo $datosPlanilla['rango1'];?>" /></td>
    <td>&nbsp;</td>
    <td ></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ></td>
    <td>&nbsp;</td>
    <td class="session1_bordes">5 -10 Años</td>
    <td>&nbsp;</td>
    <td colspan="2"><input type='text' id="rango2" name="rango2" class="required number" size="10"
     value="<?php echo $datosPlanilla['rango2'];?>" /></td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ></td>
    <td>&nbsp;</td>
    <td class="session1_bordes">&gt; 10 Años</td>
    <td>&nbsp;</td>
    <td colspan="2"><input type='text' id="rango3" name="rango3" class="required number" size="10"
     value="<?php echo $datosPlanilla['rango3'];?>" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>	
</td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5">&nbsp;</td>
    </tr>
</table>
</td>
</tr>
</table>
</form>
</div>
</td></tr></table>
<script> leerURL();</script>
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