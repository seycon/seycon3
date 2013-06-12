<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include_once('conexion.php');  
	$db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
	   header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Administracion'],'Parámetros Sistema','nuevo_impresion.php')){
	  header("Location: cerrar.php");	
	}
	
	if(($_GET['idimpresion']!='' && $_GET['sw'] != 1) || $_POST['idimpresion']!='' ) {
		$destino='';
		$ahora=time();
		$archivo = $_FILES['foto']['name'];
		$archivo1 = $_FILES['imagen']['name'];
		if ($archivo != '') {
		  $destino =  "files/$ahora".$archivo;
		  copy($_FILES['foto']['tmp_name'],$destino);
		}
		if ($archivo1 != '') {
		$destino =  "files/$ahora".$archivo1;
		copy($_FILES['imagen']['tmp_name'],$destino);
		}

mysql_query("UPDATE impresion SET  titulonotadeventa='".$_POST['titulonotadeventa']."', nvrecibo='".$_POST['nvrecibo']."', nvlibrodiario='1', nvimprimirfactura='".$_POST['nvimprimirfactura']."', nvcuentaporcobrar='".$_POST['nvcuentaporcobrar']."', nvhabillibroventa='".$_POST['nvhabillibroventa']."', titulonotadeventas='".$_POST['titulonotadeventas']."', nvsrecibo='".$_POST['nvsrecibo']."', nvslibrodiario='1', nvsimprimirfactura='".$_POST['nvsimprimirfactura']."', nvscuentaporcobrar='".$_POST['nvscuentaporcobrar']."', nvshablcv='".$_POST['nvshablcv']."', titulocotizacion='".$_POST['titulocotizacion']."', cotimprimir='".$_POST['cotimprimir']."', tituloingresoalm='".$_POST['tituloingresoalm']."', ialmimprimir='".$_POST['ialmimprimir']."', ialmhablibrodiario='1', tituloegresoalm='".$_POST['tituloegresoalm']."', ealmimprimir='".$_POST['ealmimprimir']."', ealmhablibrodiario='1', tituloinformegasto='".$_POST['tituloinformegasto']."', infgastoimprimir='".$_POST['infgastoimprimir']."', titulomemorandum='".$_POST['titulomemorandum']."', titulonotacobranza='".$_POST['titulonotacobranza']."', notacobranzaimprimir='".$_POST['notacobranzaimprimir']."', tituloreciboingr='".$_POST['tituloreciboingr']."', mensajereciboingr='".$_POST['mensajereciboingr']."', recibingimprimir='".$_POST['recibingimprimir']."', tituloreciboegreso='".$_POST['tituloreciboegreso']."', mensajereciboegr='".$_POST['mensajereciboegr']."', recibrgresoimprimir='".$_POST['recibrgresoimprimir']."', titulosolicitud='".$_POST['titulosolicitud']."', solicitudimprimir='".$_POST['solicitudimprimir']."', titulotraspaso='".$_POST['titulotraspaso']."', trapasoimprimir='".$_POST['trapasoimprimir']."', traphablibrodiario='1', tituloingreso='".$_POST['tituloingreso']."', ingdirecimprimir='".$_POST['ingdirecimprimir']."', inghablibrodiario='1', tituloegreso='".$_POST['tituloegreso']."', egrdirectoimprimir='".$_POST['egrdirectoimprimir']."', egrhablibrodiario='1', tituloctaxcobrar='".$_POST['tituloctaxcobrar']."', ctaxcobdireimprimir='".$_POST['ctaxcobdireimprimir']."', tituloctaxpagar='".$_POST['tituloctaxpagar']."', ctaxpagdireimprimir='".$_POST['ctaxpagdireimprimir']."', titulodeproyecto='".$_POST['titulodeproyecto'].
"',titulotraspasodinero='".$_POST['titulotraspasodinero']."',trapasodineroimprimir='".$_POST['trapasodineroimprimir']."',porcobrarhablibrodiario='1',trapdinerohablibrodiario='1',porpagarhablibrodiario='1', dvdireimprimir='".$_POST['dvdireimprimir']."' WHERE idimpresion= '1';") or die(mysql_error());
	header("Location: nuevo_impresion.php#t3");
}
if ($_GET['idimpresion']== NULL) $consulta_Recordset1 = "SELECT * FROM impresion WHERE idimpresion= 1"; else
$consulta_Recordset1 = "SELECT * FROM impresion WHERE idimpresion= ".$_GET['idimpresion'];
$Recordset1 = mysql_query($consulta_Recordset1) or die(mysql_error());
$row_Record = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script>

$(document).ready(function()
{
$("#formValidado").validate({});
});

  function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
  
   document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115) //F4
     location.href = 'listar_servicio.php#t7';
	
   if(tecla == 113){ //F2
	 document.formValidado.submit();
	  
	}
 }
 
 var $$ = function(id){
	return document.getElementById(id); 
 }
 
 var viewMenu = function(id){
	var menu = ['tabs-1','tabs-2'];
	var menu2 = ['tabs1','tabs2'];
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






<style type="text/css">
#formValidado table tr td #tabs #tabs-1 table tr td table {
	text-align: center;
}
#formValidado table tr td #tabs #tabs-2 table tr td table {
	text-align: center;
}
#formValidado table tr td #tabs #tabs-1 table tr td table tr th {
	font-size: x-small;
}

.contenedorPrincipalImpresion{
	font-size: 11px;
	background:#FFF;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	width:100%;
	top:2px;
	bottom:10px;
	position:relative;
	border:1px solid #CCC;
	height:780px;
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

<div class="menuTituloFormulario"> Administración > Parámetros Sistema </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Administracion'];
   $privilegios = $db->getOpciones($menus, "Parámetros Sistema"); 
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
 <div class="contenedorPrincipalImpresion">
<form id='formValidado' name='formValidado' method='post' action='nuevo_impresion.php' enctype='multipart/form-data'>

<div class="contemHeaderTop">
   <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    <td><input type="hidden" id="idimpresion" name="idimpresion" value="1" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="142" colspan="2" align="center"><strong> Configuración de Sistema</strong></td>
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

<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr >
<td colspan='4' align='center'> </td>
</tr>
<tr>
<td width="269" ><strong class='titulostablas'>Configuración de Mensaje:</strong></td>
<td width="55"></td>
<td width="201" align='right'>&nbsp;</td>   
<td></td>
</tr>
<tr>
<td colspan='3'></td>
<td width='259' align='center'></td>
</tr>

<tr>
<td colspan='4' >
<div id='tabs'>
<ul  class="menujs">
<li id="tabs1" class="listajs" onclick="viewMenu('tabs-1')" style="background-color:#8E8E8E;color:#FFF"><a>Impresión</a></li>
<li id="tabs2" class="listajs" onclick="viewMenu('tabs-2')"><a>Mas datos</a></li>
</ul>
<div id='tabs-1' style="display:block; height:600px;">
<table width='100%' border='0' align='center'>
  <tr>
    <td width="171" align="right">Nota de Venta<span class='rojo'></span>(Producto):</td>
    <td width="196"><input type='text' id="titulonotadeventa" name="titulonotadeventa"  class="" size="32" value= '<?php echo htmlentities($row_Record['titulonotadeventa'], ENT_COMPAT, 'utf-8');?>'/></td>
    <td width="54">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
<tr>
  <td colspan="6" align="right"><table width="100%" border="0">
    <tr>
      <td width="28%" scope="col">Recibo de ingreso</th>
      <td width="22%" scope="col">Imprimir </th>
      <td width="21%" scope="col">Cuenta por Cobrar</th>
      <td width="25%" scope="col">Libro de Ventas</th>
      <td width="4%" scope="col">&nbsp;</th>
      </tr>
    <tr>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvrecibo']) echo ' checked ';?> id="nvrecibo"  name="nvrecibo" class="" size="32" value= '<?php echo htmlentities($row_Record['nvrecibo'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvimprimirfactura']) echo ' checked ';?> id="nvimprimirfactura"  name="nvimprimirfactura" class="" size="32" value= '<?php echo htmlentities($row_Record['nvimprimirfactura'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvcuentaporcobrar']) echo ' checked ';?> id="nvcuentaporcobrar"  name="nvcuentaporcobrar" class="" size="32" value= '<?php echo htmlentities($row_Record['nvcuentaporcobrar'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvhabillibroventa']) echo ' checked ';?> id="nvhabillibroventa"  name="nvhabillibroventa" class="" size="32" value= '<?php echo htmlentities($row_Record['nvhabillibroventa'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td>&nbsp;</td>
      </tr>
    </table></td>
</tr>
<tr>
  <td colspan="6" align="right"><hr /></td>
  </tr>
<tr>
  <td align="right">Nota de Venta<span class='rojo'></span>(Servicio):</td>
  <td><input type='text' id="titulonotadeventas"  name="titulonotadeventas" class="" size="32" value= '<?php echo htmlentities($row_Record['titulonotadeventas'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td>&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td colspan="6" align="right"><table width="100%" border="0">
    <tr>
      <td width="28%" height="20" scope="col">Recibo de ingreso</th>
      <td width="22%" scope="col">Imprimir</th>
      <td width="22%" scope="col">Cuenta por Cobrar</th>
      <td width="25%" scope="col">Libro de Ventas</th>
      <td width="3%" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvsrecibo']) echo ' checked ';?> id="nvsrecibo"  name="nvsrecibo" class="" size="32" value= '<?php echo htmlentities($row_Record['nvsrecibo'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvsimprimirfactura']) echo ' checked ';?> id="nvsimprimirfactura"  name="nvsimprimirfactura" class="" size="32" value= '<?php echo htmlentities($row_Record['nvsimprimirfactura'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvscuentaporcobrar']) echo ' checked ';?> id="nvscuentaporcobrar"  name="nvscuentaporcobrar" class="" size="32" value= '<?php echo htmlentities($row_Record['nvscuentaporcobrar'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['nvshablcv']) echo ' checked ';?> id="nvshablcv"  name="nvshablcv" class="" size="32" value= '<?php echo htmlentities($row_Record['nvshablcv'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  </tr>
<tr>
  <td colspan="10"><hr /></td>
</tr>
<tr>
  <td align="right">Cotización:</td>
  <td><input type='text' id="titulocotizacion"  name="titulocotizacion" class="" size="32" value= '<?php echo htmlentities($row_Record['titulocotizacion'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir</td>
  <td colspan="3"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['cotimprimir']) echo ' checked ';?> id="cotimprimir"  name="cotimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['cotimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
</tr>
<tr>
  <td colspan="6" align="right"><table width="100%" border="0">
    <tr>
      <td width="521"><hr /></td>
  </tr>
    </table></td>
</tr>
<tr>
  <td align="right">Ingreso Almacén:</td>
  <td><input type='text' id="tituloingresoalm"  name="tituloingresoalm" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloingresoalm'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['ialmimprimir']) echo ' checked ';?> id="ialmimprimir"  name="ialmimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['ialmimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right"></td>
  <td></td>
</tr>
<tr>
  <td colspan="7" align="right"><table width="100%" border="0">
    <tr>
      <td width="517"><hr /></td>
      </tr>
    </table></td>
</tr>

<tr>
  <td align="right">Egreso Almacén:</td>
  <td><input type='text' id="tituloegresoalm"  name="tituloegresoalm" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloegresoalm'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir</td>
  <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['ealmimprimir']) echo ' checked ';?> id="ealmimprimir"  name="ealmimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['ealmimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right"></td>
  <td></td>
</tr>
    <tr>
      <td colspan="12"><hr /></td>
</tr>
    <tr>
      <td align="right">Traspaso de Productos:</td>
      <td><input type='text' id="titulotraspaso"  name="titulotraspaso" class="" size="32" value= '<?php echo htmlentities($row_Record['titulotraspaso'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td align="right">Imprimir </td>
      <td width="23"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['trapasoimprimir']) echo ' checked ';?> id="trapasoimprimir"  name="trapasoimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['trapasoimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
      <td width="67" align="right">&nbsp;</td>
      <td width="28"></td>
    </tr>
    <tr>
      <td colspan="6" align="right"><hr /></td>
    </tr>
    <tr>
  <td align="right">Informe Gasto:</td>
  <td><input type='text' id="tituloinformegasto"  name="tituloinformegasto" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloinformegasto'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td colspan="3"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['infgastoimprimir']) echo ' checked ';?> id="infgastoimprimir"  name="infgastoimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['infgastoimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
</tr>
       <tr>
         <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Informe de Trabajo:</td>
  <td><input type='text' id="titulonotacobranza"  name="titulonotacobranza" class="" size="32" value= '<?php echo htmlentities($row_Record['titulonotacobranza'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir</td>
  <td colspan="3"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['notacobranzaimprimir']) echo ' checked ';?> id="notacobranzaimprimir"  name="notacobranzaimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['notacobranzaimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
</tr>
       <tr>
         <td colspan="12"><hr /></td>
</tr>
       <tr>
         <td align="right">Memorándum:</td>
         <td><input type='text' id="titulomemorandum"  name="titulomemorandum" class="" size="32" value= '<?php echo htmlentities($row_Record['titulomemorandum'], ENT_COMPAT, 'utf-8');?>'/></td>
         <td align="right">&nbsp;</td>
         <td colspan="3">&nbsp;</td>
       </tr>
       <tr>
         <td colspan="12"><hr /></td>
</tr>
</table>
</div>
<div id='tabs-2' class="optionjs" style="height:600px;">
 <!--Otros Datos-->
 <table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' style='minwidth:895px;'>
  <tr>
  <td width="26%" align="right">Recibo Ingreso:</td>
  <td width="36%"><input type='text' id="tituloreciboingr"  name="tituloreciboingr" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloreciboingr'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td width="12%" align="right">Imprimir </td>
  <td colspan="3"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['recibingimprimir']) echo ' checked ';?> id="recibingimprimir"  name="recibingimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['recibingimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
</tr>
<tr>
  <td align="right">Mensaje Recibo Ingreso:</td>
  <td><input type='text' id="mensajereciboingr"  name="mensajereciboingr" class="" size="32" value= '<?php echo htmlentities($row_Record['mensajereciboingr'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td>&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Recibo Egreso:</td>
  <td><input type='text' id="tituloreciboegreso"  name="tituloreciboegreso" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloreciboegreso'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td colspan="3"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['recibrgresoimprimir']) echo ' checked ';?> id="recibrgresoimprimir"  name="recibrgresoimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['recibrgresoimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
</tr>
<tr>
  <td align="right">Mensaje Recibo Egreso:</td>
  <td><input type='text' id="mensajereciboegr"  name="mensajereciboegr" class="" size="32" value= '<?php echo htmlentities($row_Record['mensajereciboegr'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td>&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Solicitud:</td>
  <td><input type='text' id="titulosolicitud"  name="titulosolicitud" class="" size="32" value= '<?php echo htmlentities($row_Record['titulosolicitud'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td colspan="3"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['solicitudimprimir']) echo ' checked ';?> id="solicitudimprimir"  name="solicitudimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['solicitudimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
</tr>
<tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Traspaso de Dinero:</td>
  <td><input type='text' id="titulotraspasodinero"  name="titulotraspasodinero" class="" size="32" value= '<?php echo htmlentities($row_Record['titulotraspasodinero'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir</td>
  <td width="6%"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['trapasodineroimprimir']) echo ' checked ';?> id="trapasodineroimprimir"  name="trapasodineroimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['trapasodineroimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td width="15%" align="right"></td>
  <td width="5%"></td>
</tr>
<tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Ingreso de Dinero:</td>
  <td><input type='text' id="tituloingreso"  name="tituloingreso" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloingreso'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['ingdirecimprimir']) echo ' checked ';?> id="ingdirecimprimir"  name="ingdirecimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['ingdirecimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right"></td>
  <td></td>
</tr>
<tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Egreso de Dinero:</td>
  <td><input type='text' id="tituloegreso"  name="tituloegreso" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloegreso'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['egrdirectoimprimir']) echo ' checked ';?> id="egrdirectoimprimir"  name="egrdirectoimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['egrdirectoimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right"></td>
  <td></td>
</tr>
<tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Cuenta por Cobrar</td>
  <td><input type='text' id="tituloctaxcobrar"  name="tituloctaxcobrar" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloctaxcobrar'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['ctaxcobdireimprimir']) echo ' checked ';?> id="ctaxcobdireimprimir"  name="ctaxcobdireimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['ctaxcobdireimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right"></td>
  <td></td>
</tr>
<tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Cuenta por Pagar:</td>
  <td><input type='text' id="tituloctaxpagar"  name="tituloctaxpagar" class="" size="32" value= '<?php echo htmlentities($row_Record['tituloctaxpagar'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['ctaxpagdireimprimir']) echo ' checked ';?> id="ctaxpagdireimprimir"  name="ctaxpagdireimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['ctaxpagdireimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right"></td>
  <td></td>
</tr>

       <tr>
  <td colspan="12"><hr /></td>
</tr>
<tr>
  <td align="right">Proyecto:</td>
  <td><input type='text' id="titulodeproyecto"  name="titulodeproyecto" class="" size="32" value= '<?php echo htmlentities($row_Record['titulodeproyecto'], ENT_COMPAT, 'utf-8');?>'/></td>
  <td align="right">Imprimir </td>
  <td colspan="3"><input type='checkbox' onclick='checkclick(this.id)' <? if ($row_Record['dvdireimprimir']) echo ' checked ';?> id="dvdireimprimir"  name="dvdireimprimir" class="" size="32" value= '<?php echo htmlentities($row_Record['dvdireimprimir'], ENT_COMPAT, 'utf-8');?>'/></td>
</tr>
<tr>
  <td colspan="6" align="right">&nbsp;</td>
</tr> 
 </table>
</div>
</div> 
</td>
</tr>
</table>
</form></div>
</td></tr></table>
<br />
<br />
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