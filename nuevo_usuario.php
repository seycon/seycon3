<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
    session_start();
    include('conexion.php');
    $db = new MySQL();

	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
    }
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Administracion'],'Usuarios de Sistema','nuevo_usuario.php','listar_usuario.php');
	if ($fileAcceso['Acceso'] == "No") {
		header("Location: cerrar.php");	
	}

	function filtro($cadena)
	{
		return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}

	if (isset($_POST['transaccion'])) {
	    $archivoFirma = "";
	    $archivoFirma = $_FILES['firmadigital']['name'];
	    $imagenFirma = ""; 
		
		$claveUser = md5($_POST['password']);
        $claveUser2 = crc32($claveUser);
        $claveUser3 = crypt($claveUser2, "xmas");
        $claveFinal = sha1("xmas".$claveUser3);
	  
		if ($archivoFirma != '') {
			$ahora=time();
			$destinoFirma = "files/$ahora".$archivoFirma;
			copy($_FILES['firmadigital']['tmp_name'],$destinoFirma);
			$imagenFirma = "firmadigital='$destinoFirma',";
		}
	 
		if($_POST['transaccion'] == "insertar") {
			$sql = "INSERT INTO usuario(idusuario,idtrabajador,login,password,fechacreacion,firmadigital,"
				."idgrupousuario,cambiapass,idusuariosistema,estado) "
				."VALUES (NULL,'".filtro($_POST['idtrabajador'])."','".filtro($_POST['login'])
				."','".filtro($claveFinal)."',now(),'$destinoFirma','"
				.filtro($_POST['grupo'])."','".filtro($_POST['cambiapass'])
				."',$_SESSION[id_usuario],'1');";
			mysql_query($sql);
		}
	
		if($_POST['transaccion'] == "modificar") {
			$sql = "UPDATE usuario SET login='"
				.filtro($_POST['login'])."', password='".filtro($claveFinal)."',".$imagenFirma
				." idgrupousuario='".filtro($_POST['grupo'])."',cambiapass='"
				.filtro($_POST['cambiapass'])."',idusuariosistema=$_SESSION[id_usuario] WHERE idusuario= '"
				.$_POST['idusuario']."';";
			mysql_query($sql);
		 }
	
	    header("Location: listar_usuario.php#t2");
	}

    $transaccion = "insertar";
    if (isset($_GET['sw'])) {
        $transaccion = "modificar";	
        $sql = "SELECT * FROM usuario WHERE idusuario= ".$_GET['idusuario'];
        $datoUsuario = $db->arrayConsulta($sql);  
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
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script async="async" src="usuario/NUsuario.js"></script>
<script>
$(document).ready(function()
{
$("#formValidado").validate({});
});

var resultConsulta = function(resultado){
	if (resultado == "si"){
		$$("msjError").style.display = "block";
	}else{
		$$("msjError").style.display = "none";
		$$("enviar").type = "submit";		
	}
}

  function checkclick(id){ 
    if (document.getElementById(id).checked) 
	    document.getElementById(id).value=1; 
	else 
	    document.getElementById(id).value=0;
  }
  
   document.onkeydown = function(e){
     tecla = (window.event) ? event.keyCode : e.which;   
      if (tecla == 115){ //F4
       if ($$("cancelar") != null)
	     location.href = 'listar_usuario.php#t2';	   
	  }
	
      if(tecla == 113){ //F2
	   $$("enviar").click();
	  }
  }
 
 var $$ = function(id){
	return document.getElementById(id); 
 }
 
 var viewMenu = function(id){
	var menu = ['tabs-1','tabs-2','tabs-3','tabs-4','tabs-5','tabs-6','tabs-7'];
	var menu2 = ['tabs1','tabs2','tabs3','tabs4','tabs5','tabs6','tabs7'];
		for (var j=0;j<menu.length;j++){
	  if (menu[j] == id){
		$$(menu[j]).style.display = "block"; 
		$$(menu2[j]).style.background = "#8E8E8E"; 
		$$(menu2[j]).style.color = "#FFF"; 
	  }else{
		$$(menu[j]).style.display = "none";
		$$(menu2[j]).style.background = "#F6F6F6"; 
		$$(menu2[j]).style.color = "#666";  
	  }
	}	 
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
<div class="menuTituloFormulario"> Administración > Usuarios de Sistema </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Administracion'];
   $privilegios = $db->getOpciones($menus, "Usuarios de Sistema"); 
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
    <form id='formValidado' name='formValidado' method='post' action='nuevo_usuario.php' enctype='multipart/form-data'>
  <div class="contemHeaderTop">
      <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
        <input name='enviar' type='button' class='botonNegro' onclick="consultar();" id='enviar' value='Guardar [F2]' />
        <?php 
            if ($fileAcceso['File'] == "Si") {
             echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
             value="Cancelar [F4]" onClick="location.href=&#039listar_usuario.php#t2&#039"/>';	
            }
        ?>
     
     </td>
    <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden"  id="idusuario" name="idusuario" value="<?php echo $datoUsuario['idusuario'];?>" /></td>
    <td colspan="3" align='right'><table width="356" border="0">
      <tr>
        <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
        <td width="142">
        <?php 
        if (isset($_GET['idusuario'])) {
            echo $_GET['idusuario'];
        } else {
            echo $db->getNextID("idusuario","usuario");
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
  

  <table width='100%' border='0' align='center' cellpadding='2' cellspacing='3' >
<tbody>
<tr>
<td colspan='4' align='center' ></td>
</tr>

<tr>
<td colspan='2'></td>
<td width="189" align="right"></td>
<td width='10' rowspan='5' align='left' valign="top">&nbsp;</td>
</tr>
<tr height="40">
<td width='106' align='right' >&nbsp;</td>
<td ><div id="msjError" style="display:none;">El Login ingresado ya existe, debe ingresar otro nombre de usuario.</div>

</td>
<?php
  if ($transaccion != "insertar") {
	$atributo = " disabled='disabled'";  
  }
?>
<td >&nbsp;</td>
</tr>
<tr>
<td colspan='4' >

   <table width="90%" border="0" align="center" class="bordeContenido">
    <tr>
      <td width="19%">&nbsp;</td>
      <td width="34%">&nbsp;</td>
      <td width="18%">&nbsp;</td>
      <td width="29%">&nbsp;</td>
      </tr>
    <tr>
      <td align="right">Login<span class='rojo'>*</span>:</td>
      <td><input type='text' style="width:99%;" id="login" name="login"  class="required" 
      size="20" value="<?php echo  $datoUsuario['login'];?>" minlength="5"/></td>
      <td align="right">Trabajador<span class='rojo'>*</span>:</td>
      <td>
      <select id="idtrabajador" name="idtrabajador" style="width:70%;" <?php echo $atributo;?> class="required">
      <option value=""> -- Seleccione -- </option>
      <?php
        $sql = "select idtrabajador,left(concat(nombre,' ',apellido),20)as 'nombre'
	      from trabajador where estado=1 ;";
	    $db->imprimirCombo($sql,$datoUsuario['idtrabajador']);
      ?>
      </select></td>
      </tr>
    <tr>
      <td align="right">Password<span class='rojo'>*</span>:</td>
      <td>
      <input type='password' id="password" name="password" style="width:99%;" class="required" minlength="8" size="20" value="" />
      </td>
      <td align="right">Firma Digital:</td>
      <td><input type='file' id="firmadigital" name="firmadigital" size="20" /></td>
    </tr>
    <tr>
      <td align="right">Grupo de Usuario<span class="rojo">*</span>:</td>
      <td><select id="grupo" name="grupo" style="width:70%;" class="required">
        <option value=""> -- Seleccione -- </option>
        <?php
	    $sql = "select idgrupousuario,nombre from grupousuario where estado=1;";
	    $db->imprimirCombo($sql,$datoUsuario['idgrupousuario']);
	   ?>
      </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="right">Cambiar Contraseña al iniciar sesión
        <input type='checkbox' onclick='checkclick(this.id)' 
    id="cambiapass"  name="cambiapass" size="32" value='0'/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    </table>

</td>
</tr>
</tbody>
</table>

</form>

</div>
</td></tr></table>
<br />
<br />




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