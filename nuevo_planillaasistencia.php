<?php
include('conexion.php');
$db = new MySQL();
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['softLogeoadmin'])){
    header("Location: index.php");	
}
$estructura = $_SESSION['estructura'];
if (!$db->tieneAccesoFile($estructura['Recursos'],'Reporte de Planillas','nuevo_boletapago.php')){
  header("Location: cerrar.php");	
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<link href='estiloslistado.css' rel='stylesheet' type='text/css' />
<script src="autocompletar/FuncionesUtiles.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/jquery.validate.js"></script>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">


.botongeneral{    
    border-radius: 8px;
    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
    -khtml-border-radius: 8px;
	cursor:pointer;
	font-size:13px;
	color:#FFF;
	height: 25px;
	background:url(fondotabla.jpg);
	border: 1px solid #666;
}

.mensajeRespuesta{
  position : relative;
  float : left;
  left : 5px;	
  font-weight : bold;
  color : #333;
  font-size:12px;
}

.rojo{
	color:#F33;
}

.bordeContenido {
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
<script>

$(document).ready(function()
{
$("#form2").validate({});
});

 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
    if(tecla == 113){ //F2
	 document.form2.submit();
	  
	}
  }
  
  var leerURL = function(){
	var param =  location.search;
	 if (param.length > 0){
	 	 document.getElementById("mensajeRespuesta").innerHTML = 
		 "Señor Usuario la Planilla de Bonos debe ser generada inicialmente"; 
	 }
 }
 
 
 function $$(id){
	return document.getElementById(id);  
 }
 

</script>  
  

<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Recursos > Reporte de Planillas </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Reporte de Planillas"); 
   $option = "";
   for ($i = 0; $i < count($privilegios); $i++) {	
	   $link = "location.href='".$privilegios[$i]["Enlace"]."'";
	   $option = "<div class='privilegioMenu' onclick=$link>".$privilegios[$i]['Texto']."</div>". $option;
   } 
   echo $option;
 ?>
</div>
</div>
  
  
<div class="contenedorPrincipal">  
<form id="form2" name="form2" method="post" action="planillas/reporte_asistencia.php" target="_blank" enctype='multipart/form-data'>
<table width='100%' border='0' align="center" > <tr><td bgcolor='#FFF'>
  
<table width="100%" border="0" align="center">
  <tr >
    <td height="35" align="center">
 
      <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="Generar2" id="Generar2"  value="Realizar [F2]" style="width:100px" class="botonseycon"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 </td>
<td></td>
<td colspan="3" align='right'>
<table width="356" border="0">
  <tr>
    <td width="26" align="right"></td>
    <td width="320" align="center"> Reporte Planilla de Asistencia</td>
  </tr>
  <tr>
    <td colspan="2" align="center"></td>  
  </tr>
</table>
</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table>
    
    </td>
  </tr>
  <tr>
    <td>
    <div id="mensajeRespuesta" class="mensajeRespuesta"></div></td>
  </tr>
  <tr>
    <td height="4"></td>
  </tr>
</table>


<table width="100%" height="138" border="0" align="center">
    <tr>
      <td>&nbsp;</td>
      <td class="titulos"></td>
      <td>&nbsp;</td>
      <td class="titulos">&nbsp;</td>
      <td>&nbsp;</td>
      <td class="titulos">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="1%">&nbsp;</td>
      <td width="17%" class="titulos">&nbsp;</td>
      <td width="15%">&nbsp;</td>
      <td width="16%" class="titulos">&nbsp;</td>
      <td width="11%">&nbsp;</td>
      <td width="9%" class="titulos">&nbsp;</td>
      <td width="16%">&nbsp;</td>
      <td width="15%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="8"><table width="90%" border="0" align="center" class="bordeContenido">
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="19%">&nbsp;</td>
          <td width="28%" align="right"><span class="titulos">Trabajador:</span><span class='rojo'>*</span></td>
          <td width="24%"><select name="trabajador" id="trabajador" style="width:140px;" class="required">
            <option value="" selected="selected">-- Seleccione --</option>
            <?php
			  $sql = "select idtrabajador,concat(nombre,' ',apellido)as 'nombre1' from trabajador where estado=1;";
			  $db->imprimirCombo($sql);     
			?>
          </select></td>
          <td width="29%">&nbsp;</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="right"><span class="titulos">Seleccione el Mes:</span></td>
          <td align="left">
          <select name="meses" id="meses">
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="right"><span class="titulos">Seleccione el año:</span></td>
          <td><select name="anio" id="anio" style="width:60px;">
            <?php	  
			  for($i=2010;$i<=2080;$i++){
			   echo  "<option value='$i'>$i</option>";		
			  }
			 ?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
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
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <br />


</td></tr></table></form></div>
<script>
    leerURL();
	seleccionarCombo("anio","<?php echo date("Y");?>");
</script>
<br />
<br  />
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
