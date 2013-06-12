<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include('conexion.php');
	$db = new MYSQL();
	$idcontac = $db->getMaxCampo('idcargo', 'cargo');
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Recursos'],'Cargo','nuevo_cargo.php','listar_cargo.php');
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}	
	
	if (isset($_POST['transaccion'])) {	
	  if ($_POST['transaccion'] == "insertar") {
	     $sql = "INSERT INTO cargo
		 (idcargo,cargo,descripcion,estado,idusuario) VALUES (NULL,'"
		 .filtro($_POST['cargo'])."','".filtro($_POST['descripcion'])."','1',$_SESSION[id_usuario]);";
		 mysql_query($sql);
	  }
	  
	  if ($_POST['transaccion'] == "modificar") {
		 $sql = "UPDATE cargo SET cargo='".filtro($_POST['cargo'])
		 ."', descripcion='".filtro($_POST['descripcion'])."',
		 idusuario=$_SESSION[id_usuario] WHERE idcargo= '".$_POST['idcargo']."';";  
		 mysql_query($sql);
	  }
	   header("Location: nuevo_cargo.php#t5");
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
		$transaccion = "modificar";	
		$sql = "SELECT * FROM cargo WHERE idcargo = ".$_GET['idcargo'];
		$datoCargo = $db->arrayConsulta($sql);  
	}
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
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script>  
  $(document).ready(function()
  {  
  $("#formValidado").validate({});
  });
  
  document.onkeydown = function(e){
	 tecla = (window.event) ? event.keyCode : e.which;	 
	 if (tecla == 115) { //F4
	     if ($$("cancelar") != null)
	         location.href = 'listar_cargo.php#t5';
	 }	  
	 if (tecla == 113) { //F2
	   document.getElementById("enviar").click();	  
	 }
  }
  
  var $$ = function(id){
	 return document.getElementById(id); 
  }  
</script>
<style>
.bordeContenido{
  border: 1px solid #CCC;	
}
</style>


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

<div class="menuTituloFormulario"> Recursos > Cargo </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Cargo"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_cargo.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
   <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]'/>
	<?php 
      if ($fileAcceso['File'] == "Si") {
          echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" 
           id="cancelar" value="Cancelar [F4]" onClick="location.href=&#039listar_cargo.php#t5&#039"/>';	
      }
    ?> 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idcargo" name="idcargo" value="<?php echo $datoCargo['idcargo'];?>" /> </td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php 
		if (isset($_GET['idcargo'])) {
			echo $_GET['idcargo'];
		} else {
			echo $db->getNextID("idcargo","cargo");	
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
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3'>
<tr>
<td colspan='4' align='center'></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td width='11' align='center'></td>
</tr>
<tr>
  <td colspan="4" >
    <table width="90%" border="0" align="center" class="bordeContenido">
      <tr>
        <td width="21%">&nbsp;</td>
        <td width="60%">&nbsp;</td>
        <td width="9%">&nbsp;</td>
        <td width="10%">&nbsp;</td>
        </tr>
      <tr>
        <td align="right">Nombre del Cargo:<span class='rojo'>*</span></td>
        <td><input type='text' id="cargo" name="cargo"  class="required" 
        size="20" value="<?php echo $datoCargo['cargo'];?>"/></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td align="right">Descripción:</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td colspan="4" align="center">
        <textarea name="descripcion" cols="80" rows="3" id="descripcion"><?php echo $datoCargo['descripcion'];?></textarea>
        </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      </table></td>
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