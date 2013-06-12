<?php 
	session_start();
	include_once('conexion.php');
	$db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Agenda'],'Memorándum','nuevo_memorandum.php','listar_memorandum.php');
	if ($fileAcceso['Acceso'] == "No")
	{
	  header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena));
	}
	
	$sql = "select left(titulomemorandum,30)as 'titulomemorandum' from impresion where idimpresion = 1";
	$tituloPrincipal = $db->getCampo('titulomemorandum', $sql);
	
	
	if (isset($_POST['transaccion'])) {
	  $fecha = $db->GetFormatofecha($_POST['fecha'],'/');	
	  if ($_POST['transaccion'] == "insertar") {
		  $sql = "INSERT INTO memorandum(para,referencia,fecha,contenido,cc,estado,idusuario) 
		  VALUES ('".filtro($_POST['para'])."','".filtro($_POST['referencia'])."','$fecha','"
		  .filtro($_POST['contenido'])."','".filtro($_POST['cc'])."',1,$_SESSION[id_usuario]);";
	  }
	  if ($_POST['transaccion'] == "modificar") {
		  $sql = "UPDATE memorandum SET para='".filtro($_POST['para'])."', referencia='"
		  .filtro($_POST['referencia'])."', fecha='$fecha', contenido='".filtro($_POST['contenido'])."',
		  cc='".filtro($_POST['cc'])."',idusuario=$_SESSION[id_usuario]  
		  WHERE idmemorandum= '".filtro($_POST['idmemorandum'])."';";
	  }
	  $db->consulta($sql);
	  header("Location: nuevo_memorandum.php#t13");
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
		$transaccion = "modificar";	
		$sql = "SELECT * FROM memorandum WHERE idmemorandum= ".$_GET['idmemorandum'];
		$datoMemorandum = $db->arrayConsulta($sql);  
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateagenda.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->

<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="js/jquery.validate.js"></script>

<script>
 
  $(document).ready(function()
  {
  $("#fecha").datepicker({
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
	 location.href = 'listar_memorandum.php#t13';
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

    <div class="menuTituloFormulario"> Agenda > Memorándum </div>
    <div class="menuFormulario"> 
     <?php
       $estructura = $_SESSION['estructura'];
       $menus = $estructura['Agenda'];
       $privilegios = $db->getOpciones($menus, "Memorándum"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_memorandum.php' enctype='multipart/form-data'>
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr >
<td colspan='5' align='center' >

<table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonseycon' id='enviar' value='Guardar [F2]' />
	<?php 
        if ($fileAcceso['File'] == "Si"){
         echo '<input name="cancelar" type="button" class="botonseycon" id="cancelar" 
		 value="Cancelar [F4]" onClick="location.href=&#039listar_memorandum.php#t13&#039"/>';	
        }
    ?>
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idmemorandum" name="idmemorandum" value="<?php echo $datoMemorandum['idmemorandum'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php 
	if (isset($_GET['idmemorandum'])){
	  echo $_GET['idmemorandum'];
	}
	else{
	  echo $db->getNextID("idmemorandum","memorandum");
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


</td>
</tr>

<tr>
<td colspan='4'>

</td>
<td width='137' rowspan='3' align='center'></td>
</tr>
<tr>
<td width='132' align='right' valign='top'>Para<strong><span class="rojo">*</span></strong><span class='rojo'></span>:</td>
<td width='180' valign='top'>
 <input type='text' id="para" name="para" class="required" value="<?php echo $datoMemorandum['para'];?>" size="30" />
</td>
<td width='78'  align='right' valign='top'>Fecha<span class='rojo'></span>:</td>
<td width='148' valign='top'><input type='text' id="fecha" name="fecha"  class="date" 
value="<?php 
if (isset($datoMemorandum['fecha']))
  echo $db->GetFormatofecha($datoMemorandum['fecha'],'-');
else
  echo date("d/m/Y");
?>"
size="10" /></td>
 </tr>
<tr>
<td width='132' align='right' valign='top'>Referencia<strong><span class="rojo">*</span></strong><span class='rojo'></span>:</td>
<td width='180' valign='top'>
 <input type='text' id="referencia" name="referencia" class="required"
  size="30" value="<?php echo $datoMemorandum['referencia'];?>"/>
</td>
<td width='78'  align='right' valign='top'>&nbsp;</td>
<td width='148' valign='top'>&nbsp;</td>
</tr>
<tr>
  <td colspan="5" align='right' valign='top'><hr /></td>
  </tr>




<tr>
  <td colspan='5' >
  <div id='tabs'>

  <div id='tabs-1'>
  <table width='556' border='0' align='center'>
  <tr>
    <td align="right">Cc:</td>
    <td>
    <input type='text' id="cc" name="cc" size="32" value="<?php echo $datoMemorandum['cc'];?>"/>
    </td>
  </tr>
  <tr>
  <td colspan="2" align="left">Contenido<span class='rojo'></span>:</td>
  </tr>
  <tr>
  <td width='20' align="right">&nbsp;</td>
  <td width='416'>
  <textarea name="contenido" id="contenido" cols="80" rows="10"><?php echo $datoMemorandum['contenido'];?></textarea></td>
  </tr>
  </table>
  <br />
<br />
<br />
  </div>
 
  </div> 
  </td>
</tr>

</table>
</form>
</div>
<br />
<br />
<br />

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