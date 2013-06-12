<?php
	session_start();
	include('conexion.php');
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Recursos'],'Planilla de Aguinaldo','aguinaldos.php')) {
		header("Location: cerrar.php");	
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
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->

<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<link rel="stylesheet" href="bonos/bonos.css" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script src="aguinaldo/aguinaldo.js"></script>



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


  <div id="overlay" class="overlays"></div>    
  <div id="gif" class="gifLoader"></div>
    
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

<div class="menuTituloFormulario"> Recursos > Planilla de Aguinaldo </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Planilla de Aguinaldo"); 
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
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr>
<td colspan='5' align='center' >
  
  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Generar" class="botonseycon" onclick="realizarConsulta()" />

 
 </td>
<td></td>
<td colspan="3" align='right'>

<div>
<table width="100%" border="0">
  <tr>
    <td width="22%" align="right">Periodo:</td>
    <td width="15%"><select id="anio" name="anio"  style="width:90px">
      <?php
	 for ($i=2010;$i<=2025;$i++){
       echo "<option value='$i'>$i</option>";
	 }
	?>
    </select></td>
    <td width="2%">&nbsp;</td>
    <td width="10%" align="right">Sucursal:</td>
    <td width="45%"><select name="sucursal" id="sucursal" style="width:150px;" >
      <option value="0" selected="selected">-- Seleccione --</option>
      <?php
   $sql = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1;";
   $db->imprimirCombo($sql);
   ?>
    </select></td>
    <td width="4%"></td>
    <td width="2%">&nbsp;</td>
  </tr>
</table>

</div>


</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table>


</td>
</tr>

<tr>
<td colspan='5'>

</td>
</tr>
<tr>
  <td colspan="5">
  <table width="100%" height="289" border="0">
    <tr>
      <td width="80" valign="top">
      
      <div class="session2_lateralAguinaldo">
      <table width="100%" border="0">
  <tr>
    <td colspan="2" class="session2_titulo_lateral">Datos de Planilla</td>
    </tr>
  <tr>
    <td width="40">&nbsp;</td>
    <td width="100">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Nº</td>
    <td><input type="text" id="nro" name="nro"  style="width:80%" disabled="disabled"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center">Meses.</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="text" id="meses" name="meses"  style="width:80%" onkeypress="return soloNumeros(event);"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center">Días</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="text" id="dias" name="dias"  style="width:80%" onkeypress="return soloNumeros(event);"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="hidden" id="idtrabajador" name="idtrabajador"  />
    <input type="button" value="Guardar" class="botonNegro" onclick="registrarAguinaldo()"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
    <tr>
    <td  colspan="2" align="center">
    </td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

      
      </div>
      
      
      </td>
      <td width="500" valign="top">
      
     <div class="session2_contenedorAguinaldo">
       <table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr class="session2_cabecera1">
    <td colspan="2" rowspan="2">Nº</td>
    <td rowspan="2" >Nombre</td>
    <td width="86" rowspan="2">Fecha de Ingreso</td>
    <td width="80" rowspan="2">Base Para del Calculo</td>
    <td colspan="2" >Tiempo de Trabajo</td>
    <td width="10" colspan="4" rowspan="2">Liquido Pagable</td>
    </tr>
  <tr class="session2_cabecera1">
    <td width="70">Meses</td>
    <td width="50">Días</td>
    </tr>
   <tbody id="detalleBonos">
   
   </tbody>
  
</table>

   </div>  
      
      </td>
    </tr>
  </table>
</td>
</tr>
</table>
</form>
</div>

</td></tr></table>
<br />
<br />
<script>
  seleccionarCombo("anio","<?php echo date("Y");?>");
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