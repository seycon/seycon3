<?php
 session_start();
 include_once('conexion.php');  
 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
 }
 $db = new MySQL();
 $estructura = $_SESSION['estructura'];
 $fileAcceso = $db->privilegiosFile($estructura['Agenda'],'Block de Notas','nuevo_nota.php','listar_nota.php');
 if ($fileAcceso['Acceso'] == "No"){
  header("Location: cerrar.php");	
 }

 $idnota = 0;
 $transaccion = "insertar";
  if(isset($_GET['sw'])){
    $transaccion = "modificar";	
    $sql = "SELECT * FROM nota WHERE idnota= ".$_GET['idnota'];
    $idnota = $_GET['idnota'];
    $datoNota = $db->arrayConsulta($sql);  
  }

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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



<!-- TinyMCEaumentar un simbolo de mayor para activar el editor de texto avanzado TinyMCE-->


<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<link rel="stylesheet" href="nota/nota.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="nota/Nnota.js"></script>
<script src="lib/Jtable.js"></script>
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
</script>

<script>
  function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
</script>






<style type="text/css">
#formValidado table tr td #tabs ul li table {
	font-size: 1pc;
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
  
  <div id="overlay_vendido" class="overlays"></div> 
  <div id="overlay" class="overlays"></div>
  
  
 <div id="modal_mensajes" class="contenedorMsgBox">
  <div class="modal_interiorMsgBox"></div>
  <div class="modalContenidoMsgBox">
      <div class="cabeceraMsgBox">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBox">ADVERTENCIA</div>
        <div class="modal_cerrarMsgBox">
         <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
      </div>
      <div class="contenidoMsgBox">
        <div class="modal_ico1MsgBox"><img src="iconos/alerta.png" width="28" height="28"></div>
        <div class="modal_datosMsgBox" id="modal_contenido">Debe Seleccionar un Almacén de Origen.</div>
        <div class="modal_boton1MsgBox"><input type="button" value="Aceptar" class="botonNegro" onclick="closeMensaje()"/></div>
      </div>
  </div>
 </div>
  
  
  
<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Agenda > Block de Notas </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Block de Notas"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_nota.php' enctype='multipart/form-data'>
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3'>
<tr >
<td colspan='5' align='center' >

   <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='button' onclick="ejecutarTransaccion();" class='botonseycon' id='enviar' value='Guardar [F2]' />
    <?php 
	if ($fileAcceso['File'] == "Si"){
	 echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonseycon" id="cancelar" value="Cancelar [F4]" onClick="location.href=&#039listar_nota.php#t7&#039"/>';	
	}
	?>
 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type='hidden' id='idnota' name='idnota' value="<?php echo $_GET['idnota'];?>"  size='32' /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php 
	if (isset($_GET['idnota'])) {
	  echo $_GET['idnota'];
	} else {
	  echo $db->getNextID("idnota","nota");
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
<td colspan='4'>&nbsp;</td>
<td width="142" align='center'></td>
</tr>
<tr>
  <td width='235' align='right' valign='top'>Fecha<span class='rojo'></span>:</td>
  <td width='123' valign='top'><input type='text' id="fecha" name="fecha" 
value="<?php if (isset($datoNota['idnota']))
echo $db->GetFormatofecha($datoNota['fecha'],'-');
else
echo date("d/m/Y");
?>"
 class="date" size="12" />
  </td>
  <td width='91'  align='right' valign='top'>Privado:</td>
  <td width='103' valign='top'><input type='checkbox' id='privado' onclick='checkclick(this.id)' <?php if ($datoNota['privado']) echo ' checked ';?> name='privado'   class="" size="32" value= '<?php echo $datoNota['privado'];?>'/></td>
  <td align='left'></td>
</tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td  align='right' valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td align='left'></td>
</tr>
<tr>
  <td colspan='5' >
  
  <table width="100%" border="0" class="tablacentral">
  <tr>
    <td width="28%" align="right"><strong>Título<span class="rojo">*</span>:</strong></td>
    <td width="72%"><input type='text' name="titulo" id="titulo" class="" size="65" value="<?php echo $datoNota['titulo'];?>"/></td>
  </tr>
</table>
  
  <table width='583' border='0' align='center'>
  <tr>
    <td align="right">Contenido:</td>
    <td colspan="2">
  <tr>
    <td align="right">&nbsp;</td>
    <td width="365"><img src='images/anillado.jpg' width='370' height='17'/>
    <td width="119">    
  <tr>
  <td width='85' align="right">&nbsp;</td>
  <td>
  <textarea name="contenido" id="contenido" style="width:100%" rows="14" ><?php echo $datoNota['contenido'];?></textarea>
  <td>  
  <tr>
  <td width='85' align="right">&nbsp;</td>
  <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  <td width='85' align="right">&nbsp;</td>
  <td colspan="2">&nbsp;</td>
  </tr>
  </table>

  </td>
</tr>
</table>
</form>
</div>
</td></tr></table>

<BR />
<br />
<script>
 transaccion = '<?php echo $transaccion ?>';
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