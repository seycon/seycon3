<?php 
  session_start();
  include_once('conexion.php');
  if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
  }  
  $db = new MySQL();
  $estructura = $_SESSION['estructura'];
  $fileAcceso = $db->privilegiosFile($estructura['Agenda'],'Actas de Reunión','nuevo_acta.php','listar_acta.php');
  if ($fileAcceso['Acceso'] == "No"){
	header("Location: cerrar.php");	
  }

  $idacta = 0;
  $transaccion = "insertar";
    if(isset($_GET['sw'])){
      $transaccion = "modificar";	
      $sql = "SELECT * FROM acta WHERE idacta= ".$_GET['idacta'];
      $idacta = $_GET['idacta'];
      $datoActa = $db->arrayConsulta($sql);  
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



<!-- TinyMCE --><!--- aumentar un simbolo de mayor para activar el editor de texto avanzado TinyMCE--->

<!-- /TinyMCE -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<link rel="stylesheet" href="acta/acta.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="acta/Nacta.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/documento.js"></script>
<script src="lib/Jtable.js"></script>




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
<!--hra-->
<script language="javascript" type="text/javascript">
function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
var RelojID12 = null
var RelojEjecutandose12 = false


function obtenerHora(){
var date =new Date();
var hora = date.getHours();
var minuto = date.getMinutes();
var segundo = date.getSeconds();
var meridiano;
var ValorHora;

if (hora > 12) {
		hora -= 12
		meridiano = " P.M."
	} else {
		meridiano = " A.M."
    }
	
	if (hora < 10)
		ValorHora = "0" + hora
	else
		ValorHora = "" + hora

	//establece los minutos
	if (minuto < 10)
		ValorHora += ":0" + minuto
	else
		ValorHora += ":" + minuto
        	
	//establece los segundos
	if (segundo < 10)
		ValorHora += ":0" + segundo
	else
		ValorHora += ":" + segundo
        
	ValorHora += meridiano;

 	document.getElementById("horainicio").value = ValorHora;	
	
}




</script>

<style type="text/css">
<!--
#formValidado table tr td #tabs #tabs-1 table tr td .rojo {
	color: #000;
}
#formValidado table tr td #tabs #tabs-1 table tr td .rojo {
	color: #F00;
}
#formValidado table tr td #tabs ul li {
	color: #000;
}
.nuevoacta {
	font-size: 2px;
}



-->
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

<div class="menuTituloFormulario">  Agenda > Actas de Reunión </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Actas de Reunión"); 
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
<form id='formValidado' name='formValidado' method='post' action='' enctype='multipart/form-data' autocomplete="off">
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3'>
<tr>
<td colspan='6' align='center' >
    
    
    <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;<input name='enviar' type='button' class='botonseycon' id='enviar' onclick="ejecutarTransaccion()" value='Guardar [F2]' />
<?php 
	if ($fileAcceso['File'] == "Si"){
	 echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonseycon" id="cancelar" value="Cancelar [F4]" onClick="location.href=&#039listar_acta.php#t8&#039"/>';	
	}
?>
 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idacta" name="idacta" value="<?php echo $datoActa['idacta'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php
	 if (isset($_GET['idacta'])){
	   echo $_GET['idacta'];
	 }else{
	   echo $db->getNextID("idacta","acta");
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
<td width='12' align='center'>&nbsp;</td>
<td width='37'>&nbsp;</td>
</tr>
<tr>
  <td align='right' valign='top'>Hora de Inicio (hh:mm)<span class='rojo'></span>:</td>
  <td width='90' valign='top'><input type="text" size="14" name="horainicio"  id="horainicio" onkeypress="return mascarcaHora(this.value,this.id,event)" value="<?php echo $datoActa['horainicio'];?>"/></td>
  <td width='113'  align='right' valign='top'>Privado<span class='rojo'></span>:</td>
  <td width='124' valign='top'><input type='checkbox' name="privado" <?php if ($datoActa['privado']) echo ' checked ';?> id="privado"  
   value="<?php echo $datoActa['privado'];?>" class="" size="32" onclick='checkclick(this.id)' /></td>
  <td colspan="2" rowspan="2" align='center'>&nbsp;</td>
</tr>
<tr>
  <td align='right' valign='top'>Hora de Cierre<span class='rojo'></span>(hh:mm):</td>
  <td width='90' valign='top'><input type="text" size="14" name="horacierre"  id="horacierre" onkeypress="return mascarcaHora(this.value,this.id,event)" value="<?php echo $datoActa['horacierre'];?>">
  </td>

  <td width='113'  align='right' valign='top'>Firma Digital:</td>
  <td width='124' valign='top'>
  <input type='checkbox' name="firma" id="firma" <?php if ($datoActa['firmadigital']) echo ' checked ';?>  class="" size="32" 
   onclick='checkclick(this.id)' value="<?php echo $datoActa['firmadigital'];?>" /></td>
</tr>
<tr>
<td width='134' align='right' valign='top'>Fecha<span class='rojo'></span>:</td>
<td width='90' valign='top'><input type='text' id="fecha" name="fecha"  class="date" size="10" value="
<?php 
if (isset($datoActa['fecha']))
 echo $db->GetFormatofecha($datoActa['fecha'],'-');
else
 echo date("d/m/Y");
?>"/>
</td>
<td width='113'  align='right' valign='top'>&nbsp;</td>
<td width='124' valign='top'><br />
</td>



<tr>
  <td colspan='6' >


  <table width="100%" border="0" class="tablacentral">
  <tr>
    <td width="22%" align="right"><strong>Título<span class="rojo">*</span>:</strong></td>
    <td width="78%"><input type='text' name="titulo" id="titulo" class="" size="65" value="<?php echo $datoActa['titulo'];?>"/></td>
  </tr>
</table>  


  <table width='90%' border='0' align='center'>
  <tr>
  <td width='257' align="right">&nbsp;</td>
  <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  <td colspan="2" align="left">Agenda Reunión:</td>
  <td width="400">Desarrollo Reunión: </td>
  </tr>
  <tr>
  <td colspan="2" align="left">
    <textarea name="agendareunion" id="agendareunion" style="width:80%" rows="8" ><?php echo $datoActa['agendareunion'];?></textarea>  </td>
  <td align="center">
    <textarea name="desarrolloreunion" id="desarrolloreunion" style="width:100%" rows="8" ><?php echo $datoActa['desarrolloreunion'];?></textarea>  
  <tr>
  <td width='257' align="left">Asistentes:</td>
  <td colspan="2">
  <tr>
  <td width='257' align="left"><textarea name="asistentes" id="asistentes" style="width:100%" rows="8" ><?php echo $datoActa['asistentes'];?></textarea></td>
  <td colspan="2">
  <tr>
  <td width='257' align="right"></td>
  <td colspan="2">
</td>
  </tr>
  <tr>
  <td width='257' align="right">&nbsp;</td>
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