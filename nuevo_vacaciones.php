<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();  
	include('conexion.php');
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Recursos'],'Vacaciones'
	,'nuevo_vacaciones.php','listar_vacaciones.php');
	
	if ($fileAcceso['Acceso'] == "No") {
	  header("Location: cerrar.php");	
	}	
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	  $transaccion = "modificar";	
	  $sql = "SELECT * FROM vacaciones WHERE idvacaciones = ".$_GET['idvacaciones'];
	  $datoVacaciones = $db->arrayConsulta($sql);  
	}
	
   $cadena = "";
	if (isset($datoVacaciones['idvacaciones'])) {
	  $sql = "select date_format(fecha,'%m-%d-%Y')as 'fecha' 
	  from detallevacaciones where idvacaciones=$datoVacaciones[idvacaciones]";
	  $dato = $db->consulta($sql);		
	  while ($data = mysql_fetch_array($dato)) {
		$cadena .= $data['fecha'].",";	
	  }
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
<link rel="stylesheet" href="css/jquery.datepick.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="vacaciones/vacaciones.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/jquery.datepick.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script>
$(document).ready(function()
{
$('#fecha').datepick({ multiSelect: 999, monthsToShow: 2, showTrigger: '#calImg'
,dateFormat: 'mm-dd-yyyy',yearRange: '2010:2040',
onSelect: function(dates) { 
 $$("valores").value = "";
 for (var i=0; i<dates.length; i++) {
     $$("valores").value = $$("valores").value + $.datepick.formatDate(dates[i]) + ",";
 }
}
});
var dates = '<?php echo $cadena;?>';
$('#fecha').datepick('setDate', dates.split(","));
});
</script>

<style>
.bordeContenido{
  border: 1px solid #CCC;	
}

.overlays{
  position:fixed; top:0px; left:0px; width: 100%; height: 100%; 
  z-index:3009; 
  background-color: #000;
  opacity:.50;
  -moz-opacity: 0.50;
  filter: alpha(opacity=50);
  visibility:hidden;
}

.gifLoader{
  position:fixed;
  top:65%;
  left:45%;  
  width:128px;
  height:25px; 
  background-image:url(images/cargando.gif);  
  z-index:4000;   
  visibility:hidden;
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


 <div id="overlay" class="overlays"></div> 
 <div id="gif" class="gifLoader"></div>

 <!-- Page Mensajes de Advertencias --> 
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
<div class="menuTituloFormulario"> Recursos > Vacaciones </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Vacaciones"); 
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

<table style="width:75%;top:38px;margin:0 auto;position:relative;" border="0">
 <tr>
 <td>
 <div class="contenedorPrincipal">
<form id='formValidado' name='formValidado' method='post' action='nuevo_vacaciones.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='button' class='botonNegro' id='enviar' value='Guardar [F2]' onclick="ejecutarTransaccion()" />
<?php 
	if ($fileAcceso['File'] == "Si"){
	 echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" value="Cancelar [F4]" onClick="location.href=&#039listar_vacaciones.php#t4&#039"/>';	
	}
?>
 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idvacaciones" name="idvacaciones" value="<?php echo $datoVacaciones['idvacaciones'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php 
	if (isset($_GET['idvacaciones'])) {
	    echo $_GET['idvacaciones'];
	} else {
	    echo $db->getNextID('idvacaciones', 'vacaciones');
	}
	?></td>
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
<table border='0' width='100%' align='center' cellpadding='4' cellspacing='3'>
<tr >
<td colspan='4' align='center' ></td>
</tr>
<tr>
  <td colspan="4"  valign='top'>
  <br />
  <table width="90%" border="0" align="center" class="bordeContenido">
    <tr>
      <td width="19%">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="18%">&nbsp;</td>
      <td width="29%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php
	  if (isset($datoVacaciones['idvacaciones'])) {
    	$sql = "select t.fechaingreso,c.cargo,t.idtrabajador,
		left(concat(t.nombre,' ',t.apellido),20)as 'nombre' from trabajador t,cargo c 
		 where t.idcargo=c.idcargo and t.idtrabajador=$datoVacaciones[idtrabajador]";;
		 $datoTrabajador = $db->arrayConsulta($sql); 
	  }
	?>
    <tr>
      <td align="right">Nombre<span class='rojo'>*</span>:</td>
      <td width="24%"><input type='text' id="nombre" name="nombre"
       size="25" autocomplete="off" onkeyup="autocompletar(event,this.id)" value="<?php echo $datoTrabajador['nombre'];?>"/>
       <div  id="resultados"  class="divresultado" >
       </div>
           <input type="hidden" id="idtrabajador" value="<?php echo $datoTrabajador['idtrabajador'];?>"/>
      </td>
      <td width="10%"><div id="autoL1" class="autoLoading"></div></td>
      <td align="right">Fecha de Inicio:</td>
      <td><input type='text' id="fechainicio" name="fechainicio"  size="25"
       value="<?php echo $db->GetFormatofecha($datoTrabajador['fechaingreso'],"-");?>" disabled="disabled"/></td>
    </tr>
    <tr>
      <td align="right">Cargo:</td>
      <td colspan="2"><input type='text' id="cargo" name="cargo" size="25"
       disabled="disabled" value="<?php echo $datoTrabajador['cargo'];?>"/></td>
      <td align="right">Días que le corresponden:</td>
      <td><input type='text' id="derecho" name="derecho" disabled="disabled" size="25" 
      value="<?php echo $datoVacaciones['diashabilitado'];?>"/></td>
      </tr>
    <tr>
      <td align="right">Observación:</td>
      <td colspan="4">
      <input type='text' id="motivo" name="motivo" size="100" 
      value="<?php echo $datoVacaciones['motivo'];?>"/></td>
      </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="right">Seleccione los dias de vacaciones asignados al trabajador.</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" align="center">
      <div id="fecha"></div>     
      
      <input type="hidden" id="valores" name="valores" value="<?php echo $cadena;?>" size="40"/>
            
      </td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    </table></td>
</tr>
<tr>
  <td colspan='4' ></td>
</tr>

<tr>
<td colspan='4' >

</td>
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