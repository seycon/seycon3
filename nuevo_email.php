<?php 
	session_start();
	include('conexion.php'); 
	$db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Agenda'],'Email','nuevo_email.php')){
	 header("Location: cerrar.php");	
	}
	
	require_once('email/class.phpmailer.php');	
	$mail  = new PHPMailer(); 

    if ($_POST['asunto'] != '' && isset($_POST['mensaje'])) { 	
		$body  = 
		"<body style='margin:10px;'>
		<div style='width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;'>
		<br>
		<p>$_POST[mensaje]</p>
		</div>
		</body>";
		$mail->SetFrom("$_POST[remitente]", 'Sistema Empresarial y Contable');	
		$mail->AddReplyTo("$_POST[remitente]","Sistema Empresarial y Contable");
		cargarDirecciones($mail);	
		$mail->Subject  = "$_POST[asunto]";	
		$mail->MsgHTML($body);
		getArchivos($mail);
		if(!$mail->Send()) {
		  header("Location: nuevo_email.php?data=error");
		} else {
		  header("Location: nuevo_email.php?data=ok");
		}	
	}
	

	function getArchivos($mail)
	{
		$dim = count($_FILES["archivos"]["name"]);
		for ($i = 0; $i < $dim; $i++) {	
		  $archivo = $_FILES["archivos"]['name'][$i];			
		  if ($archivo != '') {
			$mail->AddAttachment($_FILES["archivos"]['tmp_name'][$i], $archivo);
		  }
		}
	}



	function cargarDirecciones($mail) 
	{
	  $correo = obtenerCorreos($_POST['para']);
	  for($j = 0; $j<count($correo); $j++) {
		  $mail->AddAddress($correo[$j], "");
	  }			 
	}

	function obtenerCorreos($texto)
	{
	 $texto = $texto.",";
	 $correo = array(); 
	 $j = 0; 
	 $divisiones = explode(',',$texto);
	 for ($i = 0; $i < count($divisiones)-1; $i++) {
	   $cadena = trim($divisiones[$i]);	
	   $posinicial = strpos($cadena,"[");
	   $posfinal = strpos($cadena,"]");
		if ($posinicial != "" && $posfinal != "") {
		  $correo[$j] = substr($cadena,$posinicial+1,$posfinal-($posinicial+1)); 
		  $j++;
		}	  
		if ($posinicial == "" && $posfinal == "") {
		  $correo[$j] = $cadena;
		  $j++;  
		}
	 }
	return $correo;	
	}


    $sql = "select t.emailcorporativo as 'email' from trabajador t,usuario u 
	where u.idtrabajador=t.idtrabajador and u.idusuario=$_SESSION[id_usuario];";
	$correoPersonal = $db->arrayConsulta($sql);
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



<!-- TinyMCE aumentar un simbolo de mayor para activar el editor de texto avanzado TinyMCE --> 

<!-- /TinyMCE -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<link href="email/email.css" rel="stylesheet" type="text/css" />
<script src="autocompletar/funciones.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="email/Nemail.js"></script>
<script type="text/javascript" src="email/nicEdit.js"></script>

<script>	
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
$(function() {	$( '#tabs' ).tabs();	}); 
</script>



<script>
  function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
  
  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115) //F4
     document.getElementById("cancelar").click();
	
   if(tecla == 113){ //F2
	 document.getElementById('enviar').click();  
	}
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

<div id="modal" class="modal"></div> 
 <div id="modalInterno" class="modalInterno"> 
    
    <div class="posicionCloseSub" onclick="accion();"><img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="accion();"></div>
    
  <div id='tabs' style="position:relative;vertical-align:top;height:200px;">
  <ul  style='height:27px;'>
  <li><a href='#tabs-2'>Clientes</a></li>
  <li><a href='#tabs-3'>Proveedor</a></li>
  <li><a href='#tabs-4'>Trabajador</a></li>
  </ul>
  
  <div id='tabs-2' class="opciones_telefono">
   <table width="100%" border="0">
   <?php
   $sql ="select left(nombre,15)as 'nombre',emailcorporativocontacto
   ,concat(nombre,' [',emailcorporativocontacto,']')as 'dato' from cliente where emailcorporativocontacto!='' and estado=1;";
   $clientes = $db->consulta($sql);
   $i=1;
   while ($datoCliente = mysql_fetch_array($clientes)){
	 echo "
	  <tr>
       <td width='5%'><input type='checkbox' value='$datoCliente[dato],' id='CBC$i' onclick='estadoCheck(this.id)' /> </td>
       <td width='35%'>$datoCliente[nombre]</td>
       <td width='60%'>$datoCliente[emailcorporativo]</td>
      </tr>	 
	 "; 
	 $i++; 
   }
   ?>
   </table>


  </div>
  <div id='tabs-3' class="opciones_telefono">
   <table width="100%" border="0">
   <?php
   $sql ="select left(nombre,15)as 'nombre',email,concat(nombre,' [',email,']')as 'dato' from proveedor where email!='' and estado=1;";
   $clientes = $db->consulta($sql);
   $i=1;
   while ($datoCliente = mysql_fetch_array($clientes)){
	 echo "
	  <tr>
       <td width='5%'><input type='checkbox' value='$datoCliente[dato],' id='CBP$i' onclick='estadoCheck(this.id)' /> </td>
       <td width='35%'>$datoCliente[nombre]</td>
       <td width='60%'>$datoCliente[email]</td>
      </tr>	 
	 "; 
	 $i++; 
   }
   ?>
   </table>
  </div> 
  <div id='tabs-4' class="opciones_telefono">
  <table width="100%" border="0">
   <?php
   $sql ="select left(nombre,15)as 'nombre',emailcorporativo,concat(nombre,' [',emailcorporativo,']')as 'dato' from trabajador where emailcorporativo!='' and estado=1;";
   $clientes = $db->consulta($sql);
   $i=1;
   while ($datoCliente = mysql_fetch_array($clientes)){
	 echo "
	  <tr>
       <td width='5%'><input type='checkbox' value='$datoCliente[dato],' id='CBT$i' onclick='estadoCheck(this.id)' /> </td>
       <td width='35%'>$datoCliente[nombre]</td>
       <td width='60%'>$datoCliente[emailcorporativo]</td>
      </tr>	 
	 "; 
	 $i++; 
   }
   ?>
   </table>
  </div> 
   
  </div> 
    
    
  
 </div>  


    <div id="overlay" class="overlays"></div>    
    <div id="gif" class="gifLoader"></div>


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

<div class="menuTituloFormulario"> Agenda > Email </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Email"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_email.php' enctype="multipart/form-data" >
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3'>
<tr >
<td colspan='6' align='center' >
  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='button' class='botonseycon' onclick="enviarDatos()" id='enviar' value='Enviar  [F2]' />
    &nbsp;&nbsp;<input name='cancelar' type='reset' class='botonseycon' id='cancelar' value='Cancelar  [F4]' on/>
   <input type="button" value="Guardar" id="pasarDatos" style="display:none"/>
 </td>
<td></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td align="center"><strong>
    <?php
	 if (isset($_GET['data'])){
		if ($_GET['data'] == "ok"){
		  echo "Mensaje Enviado Correctamente";
		} else {
		  if ($_GET['data'] == "error") {
		      echo "Mensaje No Enviado";
		  }				
		}
		      
	 }
	 
	 
	?>
    
    </strong></td>
    </tr>
  <tr>
    <td align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
  
  </tr>
</table>
</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table> 
    
    
    
</td>
</tr>


<tr>
<td colspan='4' align="center">  </td>
<td width='16' align='left' valign="top">&nbsp;</td>
<td width='300' rowspan='8' align='left' valign="top">
<div class="adjuntados">Archivos Adjuntos</div>
<div id="adjuntos" class="adjuntos"></div></td>
</tr>
<tr>
<td width='140' align='right' valign='top'>
<input type="button" value="Para:" onclick="openLista()"/><span class='rojo'>*</span></td>
<td colspan="3" valign='top'>
<input type='text' id="para" name="para" onKeyUp="ejecutar(event,this.id)"  style="width:80%;" autocomplete="off"/>
<div  id="resultados2"  class="divresultado" style="width:350px;"></div><br />Ej. nombre [nombre@tudominio.com],
</td>
<td width='16' align='left' valign="top"></td>
 </tr>
<tr>
  <td height="30" align='right' valign='top'>Asunto<span class='rojo'>*</span>:</td>
  <td colspan="3" valign='top'>
    <input type="file" name="archivo[]" id="archivo20" onchange="cambio()" style="display:none"/>
    <input type='text' id="asunto" name="asunto"  style="width:80%" /></td>
  <td align='left' valign="top">&nbsp;</td>
</tr>
<tr>
  <td height="30" align='right' valign='top'>Remitente<span class='rojo'>*</span>:</td>
  <td colspan="3" valign='top'><strong>
  <?php
     if (isset($correoPersonal['email']) && $correoPersonal['email'] != "") {
		echo $correoPersonal['email']; 
	 } else {
		echo "Registre su Correo en el módulo de RRHH."; 
	 }
  ?>
  </strong>
  <input type="hidden" name="remitente" id="remitente" value="<?php echo $correoPersonal['email'];?>"/>
  </td>
  <td align='left' valign="top">&nbsp;</td>
</tr>
<tr>
  <td height="30" align='right' valign='top'>Insertar:</td>
  <td colspan="3" valign='top'><img src="iconos/abjuntar.png" title="Adjuntar" />
  <div class="eventoAdjunto" onclick="addCampo()">Adjuntar archivo</div></td>
  <td align='left' valign="top">&nbsp;</td>
</tr>


<tr>
  <td colspan='6' > 
    <div style="position:relative;overflow:auto;width:98%;height:350px;margin:0 auto;">
    <textarea id="mensaje" name="mensaje" style="width:98%;height:300px;"></textarea>
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