<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
}
include('conexion.php');
$db = new MySQL();

$estructura = $_SESSION['estructura'];
if (!$db->tieneAccesoFile($estructura['Recursos'],'Planilla de Bonos','inicializar_reporteplanillabonos.php')){
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
<link href='estiloslistado.css' rel='stylesheet' type='text/css' />
<script src="autocompletar/FuncionesUtiles.js"></script>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
.bordeContenido {  border: 1px solid #CCC;	
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

<script>
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
    if(tecla == 113){ //F2
	 document.form1.submit();
	  
	}
  }
</script>


<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Recursos > Planilla de Bonos </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Planilla de Bonos"); 
   $option = "";
   for ($i = 0; $i < count($privilegios); $i++) {	
	   $link = "location.href='".$privilegios[$i]["Enlace"]."'";
	   $option = "<div class='privilegioMenu' onclick=$link>".$privilegios[$i]['Texto']."</div>". $option;
   } 
   echo $option;
 ?>
</div>
</div>

<div class="contenedorPrincipal">
<form id="form1" name="form1" method="post" action="planillas/imprimir_planillaBonos.php" target="_blank" >
<div class="contemHeaderTop">
     <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input type="submit" name="Generar2"  value="Realizar [F2]" style="width:100px" class="botonNegro" />
    &nbsp;&nbsp;&nbsp;&nbsp;
 </td>
<td></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"></td>
    <td width="142"></td>
  </tr>
  <tr>
    <td colspan="2" align="center">Imprimir Planilla de Bonos</td>
  
  </tr>
</table>
</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table>
</div>

<table width='100%' border='0' align="center" > <tr><td bgcolor='#FFF'>


<table width="100%" height="228" border="0" align="center">
    <tr>
      <td>&nbsp;</td>
      <td class="titulos"></td>
      <td>&nbsp;</td>
      <td class="titulos">&nbsp;</td>
      <td>&nbsp;</td>
      <td class="titulos">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="1%">&nbsp;</td>
      <td width="17%" class="titulos">&nbsp;</td>
      <td width="15%">&nbsp;</td>
      <td width="16%" class="titulos">&nbsp;</td>
      <td width="11%">&nbsp;</td>
      <td width="9%" class="titulos">&nbsp;</td>
      <td width="16%">&nbsp;</td>
      <td width="15%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="8">
        <table width="90%" border="0" align="center" class="bordeContenido">
          <tr>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="19%">&nbsp;</td>
            <td width="28%" align="right"><span class="titulos">Sucursal:</span></td>
            <td width="24%"><select name="sucursal" id="sucursal" style="width:80%;">
              <?php
	    $sql = "SELECT idsucursal, left(nombrecomercial,20)as nombrecomercial FROM sucursal WHERE estado =1;"; 
		$res = $db->consulta($sql);
	    while($dato = mysql_fetch_array($res)){
         echo  "<option value='$dato[idsucursal]'>$dato[nombrecomercial]</option>";		
		}
       ?>
            </select></td>
            <td width="29%">&nbsp;</td>
            </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right"><span class="titulos">Seleccione el Mes:</span></td>
            <td align="left"><select name="meses" id="meses">
              <option value="1">Enero</option>
              <option value="2">Febrero</option>
              <option value="3">Marzo</option>
              <option value="4">Abril</option>
              <option value="5">Mayo</option>
              <option value="6">Junio</option>
              <option value="7">Julio</option>
              <option value="8">Agosto</option>
              <option value="9">Septiembre</option>
              <option value="10">Octubre</option>
              <option value="11">Noviembre</option>
              <option value="12">Dicimbre</option>
              </select></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right"><span class="titulos">Seleccione el año:</span></td>
            <td><select name="anio" id="anio" style="width:60px;">
              <?php	  
				for($i=2010;$i<=2080;$i++){
				 echo  "<option value='$i'>$i</option>";		
				}
			   ?>
              </select></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="center">&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
         <div style="height:300px;"></div>  
        </td>
    </tr>
  </table>
<p></p>
</td></tr></table></form>
</div>
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
