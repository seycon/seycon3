<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	if (!isset($_SESSION)) {
	    session_start();
	}
	include ('bdlocal.php');
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: cerrar.php");	
	}
	
	if (isset($_POST['modificador']) && isset($_POST['pass']) && $_POST['modificador'] == "master") {
		$claveUser = md5($_POST['pass']);
		$claveUser2 = crc32($claveUser);
		$claveUser3 = crypt($claveUser2, "xmas");
		$claveFinal = sha1("xmas".$claveUser3);
		$sql = "update usuario set cambiapass=0,password='$claveFinal' where idusuario = '$_SESSION[id_usuario]'";
		$res = mysql_query($sql);
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
<link rel="stylesheet" href="css/inicio.css" type="text/css"/>
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
  
   <div class="cuadroG1">
       <div class="cuadroS1">
           <div class="cuadroDatosSG"></div>
           <div class="logoSG">Seycon 3.0</div>
           <div class="cuadroDatosSGParalelo">Seycon es un Software On-Line de gestión empresarial y control contable, permite de forma sencilla administrar varias áreas de una empresa u organización, el panel le guiara en el proceso de 
           inicialización y configuración de su empresa. Se le recomienda de ser la primera ves que ingresa a este lugar de trabajo
           leer el <a href="Manual_de_Usuario.pdf" target="_blank">Manual de Usuario</a>. 
           Seycon está destinado a sistematizar y simplificar las tareas de contabilidad. El Software contable registra y procesa las           transacciones históricas que se generan en una empresa o actividad productiva: las funciones de compras, ventas, cuentas por cobrar, cuentas por pagar, control de inventarios, balance general, recursos humanos, etc.     </div>
           
       </div>
   </div>
   
   
   <div class="cuadroG2">
       <div class="cuadroTitleS2">&nbsp;Accesos Rapidos</div>
       <div class="cuadroS2">
           <div class="cuadroDatosSG2">
           A continuación te presentamos un listado de la principales operaciones que puedes realizar 
           dentro de cada paquete que forma parte
           de Seycon 3.0, Esperamos de que tu estadia como usuario del Sistema sea agradable y 
           permita que realices tu trabajo con mayor facilidad.<br /><hr />
           
             <div class="icoInventario"></div><div class="tituloPunto">Inventario</div><br /><br />
            
            Puedes Acceder a  <a href="Manual_de_Usuario.pdf" >Ingreso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Egreso Almacén</a>,
              <a href="Manual_de_Usuario.pdf">Traspaso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Tipo de Productos</a>,
              <a href="Manual_de_Usuario.pdf">Control de Inventario</a>,
             <a href="Manual_de_Usuario.pdf" >Productos</a>, 
             <a href="Manual_de_Usuario.pdf" >Tipos de Servicios</a>, 
             <a href="Manual_de_Usuario.pdf" >Proveedores</a>, etc.
             <hr />                   
            <div class="icoRRHH"></div><div class="tituloPunto"> Recursos Humanos</div><br /><br />
            Puedes Acceder a  <a href="Manual_de_Usuario.pdf" >Ingreso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Egreso Almacén</a>, 
             <a href="Manual_de_Usuario.pdf" >Traspaso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Tipo de Productos</a>,
              <a href="Manual_de_Usuario.pdf">Control de Inventario</a>,
             <a href="Manual_de_Usuario.pdf" >Productos </a>, etc.
             <hr />
              
            <div class="icoActivos"></div><div class="tituloPunto">Activos</div><br /><br />                     
            Puedes Acceder a  <a href="Manual_de_Usuario.pdf" >Ingreso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Egreso Almacén</a>, 
             <a href="Manual_de_Usuario.pdf" >Traspaso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Tipo de Productos</a>,
              <a href="Manual_de_Usuario.pdf">Control de Inventario</a>,
             <a href="Manual_de_Usuario.pdf" >Productos </a>, etc.             
             <hr />    
            <div class="icoVentas"></div><div class="tituloPunto">Ventas</div><br /><br />        
            Puedes Acceder a  <a href="Manual_de_Usuario.pdf">Ingreso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Egreso Almacén</a>, 
             <a href="Manual_de_Usuario.pdf" >Traspaso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Tipo de Productos</a>,
              <a href="Manual_de_Usuario.pdf">Control de Inventario</a>,
             <a href="Manual_de_Usuario.pdf" >Productos </a>, etc.
             
             <hr />        
            <div class="icoContabilidad"></div><div class="tituloPunto">Contabilidad</div><br /><br />               
            Puedes Acceder a  <a href="Manual_de_Usuario.pdf" >Ingreso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Egreso Almacén</a>, 
             <a href="Manual_de_Usuario.pdf" >Traspaso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Tipo de Productos</a>,
              <a href="Manual_de_Usuario.pdf">Control de Inventario</a>,
             <a href="Manual_de_Usuario.pdf" >Productos </a>, etc.
             
             
             <hr />  
            <div class="icoAgenda"></div><div class="tituloPunto">Agenda</div><br /><br />         
            Puedes Acceder a  <a href="Manual_de_Usuario.pdf">Ingreso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Egreso Almacén</a>, 
             <a href="Manual_de_Usuario.pdf" >Traspaso Almacén</a>,
             <a href="Manual_de_Usuario.pdf" >Tipo de Productos</a>,
              <a href="Manual_de_Usuario.pdf">Control de Inventario</a>,
             <a href="Manual_de_Usuario.pdf" >Productos </a>, etc.         
             
             
            </div>
           
       </div>
   </div>
   <br />
   <br />
    
      <?php	    
	    $sql = "select cambiapass from usuario where idusuario = '$_SESSION[id_usuario]'";
		$res = mysql_query($sql);
		$fila = mysql_fetch_row($res);
		if ($fila[0] == 1) {		
	  ?>	  
      <div id="overlay_vendido" class="overlay" style="visibility:visible"></div>  
	  <div id="modal_vendido" class="modal_password"> 
       <div class="caption_modal">
         <div class="tituloModal">Ingrese su nueva contraseña</div>         
       </div>
       <br />
       <br />
       <table align="center" cellpadding="0" cellspacing="0">
       <tr>  
       <td>
          <form action="index.php" method="post" >
          <input type="password" class="aceptar" id="pass" name="pass" />
          <input type="submit" value="Guardar" class="boton_modal"/>
          <input type="hidden" value="master" id="modificador" name="modificador" />
          </form>           
       </td>
       </tr>
       </table>   
    </div> 
    <?
		}
	?> 
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