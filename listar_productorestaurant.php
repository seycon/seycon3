<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateinventario.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->


<link href='estiloslistado.css' rel='stylesheet' type='text/css' />
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
<script language="javascript">
function anular(){
if (confirm("Desea anular este elemento de la tabla productorestaurant?")==false){
return false;
}else{
return true;
}
}
</script>
<table width='75%' border='0' class="contenedorPrincipal"> <tr><td bgcolor='#FFF'>
<table width='100%' border='0'>
<tr >
<td align='center' >

<div class="tituloFondo">
      <div class="tituloSobreponeFondo">
        PRODUCTOS DE RESTAURANT
      </div>
    </div>

</td>
</tr>
</table>

<span class='letrastabla'>Buscar Producto Restaurant:</span><br /><input type='text' id='input'/><?php
echo "<select name='filtro' id='filtro'>";
include_once('bdlocal.php');
tieneacceso('productorestaurant');

function fechaDMA($fecha){ list($a,$m,$d)=explode('-',$fecha); return $d.'-'.$m.'-'.$a;  }
$consulta = 'SHOW FIELDS FROM productorestaurant';
$resultado = mysql_query($consulta) or die ( mysql_error() );
while($datos = mysql_fetch_array($resultado))
{
	echo "<option value= ".$datos['Field'].">".$datos['Field']."</option>";
}
echo '</select>';
?>
&nbsp;<input name='buscar' type='button' class='botongeneral' id='buscar' value='Buscar' onclick="location.href='listar_productorestaurant.php?abuscar='+document.getElementById('input').value+'&campo='+document.getElementById('filtro').value"/>
<input name='nuevo' type='button' class='botongeneral' id='nuevo' value='Nuevo' onClick="location.href='nuevo_productorestaurant.php'"/>
<input name='imprimir' type='button' class='botongeneral' id='imprimir' value='Imprimir' onClick="location.href='imprimir_productorestaurant.php'"/>
<input name='exportar' type='button' class='botongeneral' id='exportar' value='Exportar' onClick="location.href='excel_productorestaurant.php'"/>
<input name='buscarav' type='button' class='botongeneral' id='buscarav' value='Reportes' onClick="location.href='buscar_productorestaurant.php'"/>
<br />
<table width='100%' border='0' id='tabla' style='margin-top:5px;'>
<tr style='background-image: url(a.jpg);'>
<td width='544' ><? if (isset($_GET['estado']) && ($_GET['estado'] == 0))  echo ' Registros con estado inactivo ';?></td>
<td width='151' style='font-family: Verdana, Geneva, sans-serif;'> 
  <div align="right">Estado Activo </div></td>

<script>
function estado(){
if (document.getElementById('estado').checked)
location.href='listar_productorestaurant.php?abuscar='+document.getElementById('input').value+'&campo='+document.getElementById('filtro').value+'&estado=1';
else
location.href='listar_productorestaurant.php?abuscar='+document.getElementById('input').value+'&campo='+document.getElementById('filtro').value+'&estado=0';
}
</script>



<td width='101'><span style="font-family: Verdana, Geneva, sans-serif;">
  <input type='checkbox'  title= 'Click Aqui para ver solo los registros activos'
<? if (isset($_GET['estado']) && ($_GET['estado'] == 1))  echo ' checked ';?>  name='estado' id='estado'  onclick='estado();'/>
</span></td>
</tr>
</table>

<div style="overflow:auto;position:relative;height:280px;">
<?php
include('includes/funciones.php');
include_once('bdlocal.php');
if ($_GET['abuscar']!=''){
if (isset($_GET['estado']))
$condicion= ' where estado = '.$_GET['estado'];
else $condicion= " where ".$_GET['campo']." like '%".$_GET['abuscar']."%'";}
else 
if (isset($_GET['estado'])) 
$condicion= " where estado = ".$_GET['estado'];
else 
$condicion= '';





$sql = 'SELECT * from productorestaurant'.$condicion.' order by idrestaurant desc';
mysql_query("SET NAMES 'utf8'");
$res = consulta($sql);
$n = mysql_num_rows($res);



if( $n > 0 ){
$nombres = array(  "Id Restaurant", "Producto", "Precio de Venta", "Precio de Costo", "Precio de Venta Final", "Descripción" );
echo "<table border='0'  align='center'>";
echo "<table width='100%' border='0' align='center'>";
echo "<thead><tr style='background-image: url(fondo.jpg); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;'>";
echo "<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>";

for( $i = 0 ; $i <=5; $i++ ){
if  ($i==0) echo '<th>Id</th>';else
echo "<th>".ucfirst($nombres[$i])."</th>";
}

$par=0;
while( $fila = mysql_fetch_row($res)){
if ($par%2!=0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";
$par++;	

	
$id = $fila[0];
echo "<td><div align='center'><a href='modificar_productorestaurant.php?idrestaurant=".$id."&sw=1' onclick='return modificar(this)'> <img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /><br /></a></div></td>";
	echo "<td><div align='center'><a href='anular_productorestaurant.php?idrestaurant=".$id."' onclick='return anular(this)'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' /><br /></a></div></td>";
	echo "<td><div align='center'><a href='imprimir_productorestaurant.php?idrestaurant=".$id."' onclick='return imprimir(this)'><img src='css/images/imprimir.gif' title='Imprimir' alt='imprimir' border='0' /><br /></a></div></td>";
		for( $i = 0 ; $i <=5 ; $i++ ){
		if($fila[$i] == "")
		{
		echo "<td class='tdpar'>&nbsp;</td>";
		}
		else
		{


		echo "<td class='tdpar'>".$fila[$i]."</td>";
		}
	}
	echo "</tr>";
}
echo "</table>";
}
else{
echo "No se obtuvieron resultados";
}
?>



</div>


</tr></td></table>
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
