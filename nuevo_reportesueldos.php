<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 $db = new MySQL();	
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	 
     }
	 $estructura = $_SESSION['estructura'];
	 if (!$db->tieneAccesoFile($estructura['Recursos'],'Planilla de Sueldos','nuevo_reportesueldos.php')){
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
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" href="ingresos/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script>
$(document).ready(function()
{
$("#desde").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});
$("#hasta").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});
$("#fecha3").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});
$("#fecha4").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});
});

var $$ = function(id) {
  return document.getElementById(id);	
}

var viewOptionReporte = function(value){
   var opciones = ['r1', 'r2', 'r3', 'r4'];
   var contenedor = ['r-1', 'r-2', 'r-3', 'r-4'];
   for (var k=0; k<opciones.length; k++){
	   if (opciones[k] == value){	
		$$(opciones[k]).style.background = "#666";
		$$(opciones[k]).style.border = "2px solid #51532e";   
		$$(opciones[k]).style.color = "#FFF";
		$$(contenedor[k]).style.display = "block";
	   }else{
		$$(opciones[k]).style.background = "#D4D4D4";
		$$(opciones[k]).style.border = "2px solid #CCC";		   
		$$(opciones[k]).style.color = "#51532e";
		$$(contenedor[k]).style.display = "none";
	   }
   }
}
	 
var imprimir_planillasueldo = function() {
  window.open('planillas/imprimir_planillaSueldos.php?sucursal='+$$("sucursal").value
  + "&meses=" + $$("meses").value + "&anio=" + $$("anio").value,'target:_blank');	
}

var imprimir_boletapago = function() {
  window.open('planillas/imprimir_boletaPago.php?trabajador='+$$('trabajadorr2').value
  + "&meses=" + $$("mesesr2").value + "&anio=" + $$("anior2").value,'target:_blank');	
}

var imprimir_planillaseguro = function() {
  window.open('planillas/imprimir_planillaSeguro.php?meses='+$$('mesesr3').value
   + "&anio=" + $$("anior3").value,'target:_blank');	
} 

var imprimir_planillaafp = function() {
  window.open('planillas/imprimir_planillaAFP.php?meses='+$$('mesesr4').value
   + "&anio=" + $$("anior4").value,'target:_blank');	
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

<form id="form1" method="" action="" autocomplete="off">     

<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Recursos > Planilla de Sueldos </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Planilla de Sueldos"); 
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

<table id="tablaContenido" class="cssFromGlobal" align="center"> 
 <tr>
 <td>    
<div id="factura" class="cen">
<table cellpadding='0' cellspacing='0' width='99%' align="center" class="contemHeaderTop">
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' valign="middle">&nbsp;&nbsp; <div class="titleFromReport">
     Reportes de Planilla de Sueldos</div>
    </td>
    <td></td>
    <td colspan="3" align='right'>
        <table width="356" border="0">
          <tr>
            <td width="204" align="right"></td>
            <td width="142">         </td>
          </tr>
          <tr>
            <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>            
          </tr>
        </table>     

    </td>
  </tr>
</table>           
        
               
 <div id="datos_factura" class="datos_cliente1">
      <table width="100%" border="0">
          <tr>
            <td width="4%">&nbsp;</td>
            <td width="19%"><div class="radio"></div></td>
            <td width="2%">&nbsp;</td>
            <td width="40%"><div class="radio"></div></td>
            <td width="31%">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td rowspan="2" valign="top">
            <div class="menuopcionesreporte">
              <div class="botonreporte" id="r1" onclick="viewOptionReporte('r1')">Planilla de Sueldos</div>
              <div class="espacioreporte"></div>
              <div class="botonreporte2" id="r2" onclick="viewOptionReporte('r2')">Boleta de Pago</div>
              <div class="espacioreporte"></div>
              <div class="botonreporte2" id="r3" onclick="viewOptionReporte('r3')">Planilla de Seguro</div>
              <div class="espacioreporte"></div>
              <div class="botonreporte2" id="r4" onclick="viewOptionReporte('r4')">Planilla de A.F.P.</div>
            </div></td>
            <td rowspan="2">&nbsp;</td>
            <td colspan="2" rowspan="2" valign="top">
            
     <!-- Planilla de Sueldos -->       
     <div class="contenedorreportecentro" id="r-1" style="display:block">
      <table width="100%" border="0">
        <tr>
          <td width="6%">&nbsp;</td>
          <td width="33%">&nbsp;</td>
          <td width="27%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="18%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="4">Seleccione los datos correspondientes para realizar el reporte.</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Sucursal:</td>
          <td>
            <select name="sucursal" id="sucursal" style="width:80%;">
              <?php
				$sql = "SELECT idsucursal, left(nombrecomercial,20)as nombrecomercial FROM sucursal WHERE estado =1;"; 
				$res = $db->consulta($sql);
				while($dato = mysql_fetch_array($res)){
				 echo  "<option value='$dato[idsucursal]'>$dato[nombrecomercial]</option>";		
				}
			  ?>
            </select></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Mes:</td>
          <td>
           <select name="meses" id="meses">
			<?php
               $selec = (int)date("m");
               $texto = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto"
               ,"Septiembre","Octubre","Noviembre","Diciembre");
               for ($i = 1; $i <= count($texto); $i++) {
                   if ($selec == $i)
                       echo "<option value='$i' selected='selected'>".$texto[$i-1]."</option>";
                   else
                       echo "<option value='$i'>".$texto[$i-1]."</option>";
               }	  
            ?>
           </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Año:</td>
          <td><select name="anio" id="anio" style="width:60px;">
              <?php	  
				$selec = date("Y");
			   for ($i = 2010; $i <= 2025; $i++) {
				   if ($selec == $i)
					   echo "<option value='$i' selected='selected'>$i</option>";
				   else
					   echo "<option value='$i'>$i</option>";
			   }
			  ?>
            </select></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">
          <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte"
           onclick="imprimir_planillasueldo();"  class="botonNegro" style="width:110px;"/>
          </td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
    
    
    <!-- Boleta de Pago -->        
    <div class="contenedorreportecentro" id="r-2" style="display:none;">
      <table width="100%" border="0">
        <tr>
          <td width="6%">&nbsp;</td>
          <td width="33%">&nbsp;</td>
          <td width="27%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="18%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="4">Seleccione los datos correspondientes para realizar el reporte.</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Trabajador:</td>
          <td>
          <select name="trabajadorr2" id="trabajadorr2" style="width:140px;" class="required">
            <?php
			  $sql = "select idtrabajador,concat(nombre,' ',apellido)as 'nombre1' from trabajador where estado=1;";
			  $db->imprimirCombo($sql);     
			?>
          </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Mes:</td>
          <td>
           <select name="mesesr2" id="mesesr2">
			<?php
               $selec = (int)date("m");
               $texto = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto"
               ,"Septiembre","Octubre","Noviembre","Diciembre");
               for ($i = 1; $i <= count($texto); $i++) {
                   if ($selec == $i)
                       echo "<option value='$i' selected='selected'>".$texto[$i-1]."</option>";
                   else
                       echo "<option value='$i'>".$texto[$i-1]."</option>";
               }	  
            ?>
          </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Año:</td>
          <td><select name="anior2" id="anior2" style="width:60px;">
              <?php	  
				$selec = date("Y");
				 for ($i = 2010; $i <= 2025; $i++) {
					 if ($selec == $i)
						 echo "<option value='$i' selected='selected'>$i</option>";
					 else
						 echo "<option value='$i'>$i</option>";
				 }
			  ?>
            </select></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">
          <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte"
           onclick="imprimir_boletapago();"  class="botonNegro" style="width:110px;"/>
          </td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>        
            
    <!-- Planilla de Seguro -->        
    <div class="contenedorreportecentro" id="r-3" style="display:none;">
      <table width="100%" border="0">
        <tr>
          <td width="6%">&nbsp;</td>
          <td width="33%">&nbsp;</td>
          <td width="27%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="18%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="4">Seleccione los datos correspondientes para realizar el reporte.</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Año:</td>
          <td>
          <select name="anior3" id="anior3" style="width:60px;">
              <?php	  
				$selec = date("Y");
				 for ($i = 2010; $i <= 2025; $i++) {
					 if ($selec == $i)
						 echo "<option value='$i' selected='selected'>$i</option>";
					 else
						 echo "<option value='$i'>$i</option>";
				 }
			  ?>
          </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Mes:</td>
          <td>
           <select name="mesesr3" id="mesesr3">
			<?php
               $selec = (int)date("m");
               $texto = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto"
               ,"Septiembre","Octubre","Noviembre","Diciembre");
               for ($i = 1; $i <= count($texto); $i++) {
                   if ($selec == $i)
                       echo "<option value='$i' selected='selected'>".$texto[$i-1]."</option>";
                   else
                       echo "<option value='$i'>".$texto[$i-1]."</option>";
               }	  
            ?>
          </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right"></td>
          <td></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">
          <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte"
           onclick="imprimir_planillaseguro();"  class="botonNegro" style="width:110px;"/>
          </td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>                
                  
     
    <!-- Planilla de AFP -->        
    <div class="contenedorreportecentro" id="r-4" style="display:none;">
      <table width="100%" border="0">
        <tr>
          <td width="6%">&nbsp;</td>
          <td width="33%">&nbsp;</td>
          <td width="27%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
          <td width="18%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="4">Seleccione los datos correspondientes para realizar el reporte.</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Año:</td>
          <td>
          <select name="anior4" id="anior4" style="width:60px;">
              <?php	  
				$selec = date("Y");
			   for ($i = 2010; $i <= 2025; $i++) {
				   if ($selec == $i)
					   echo "<option value='$i' selected='selected'>$i</option>";
				   else
					   echo "<option value='$i'>$i</option>";
			   }
			  ?>
          </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">Mes:</td>
          <td>
           <select name="mesesr4" id="mesesr4">
			<?php
               $selec = (int)date("m");
               $texto = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto"
               ,"Septiembre","Octubre","Noviembre","Diciembre");
               for ($i = 1; $i <= count($texto); $i++) {
                   if ($selec == $i)
                       echo "<option value='$i' selected='selected'>".$texto[$i-1]."</option>";
                   else
                       echo "<option value='$i'>".$texto[$i-1]."</option>";
               }	  
            ?>
          </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right"></td>
          <td></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">
          <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte"
           onclick="imprimir_planillaafp();"  class="botonNegro" style="width:110px;"/>
          </td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>                                  
      </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="100" >&nbsp;</td>
        <td width="4%">&nbsp;</td>
      </tr>
  </table>
</div>
  </div>
  </td></tr></table>
</form>
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