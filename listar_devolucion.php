<?php
if (!isset($_SESSION)) {
  session_start();
}
include('conexion.php');
$db = new MySQL();
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
  header("$_POST: ". $MM_restrictGoTo); 
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateventas.dwt.php" codeOutsideHTMLIsLocked="false" -->
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
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script language="javascript">
function anular(){
 return confirm("Desea anular esta Devolucion ?");
}

function estados(){
  if (document.getElementById('estad').checked){
    document.getElementById('estado').value = "1";
  }else{
    document.getElementById('estado').value = "0";
  }
  document.listado.submit();
}

$(document).ready(function()
{
$("#fecha").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
});

</script>

<form name="listado" id="listado" method='post' action='listar_devolucion.php#t5' enctype='multipart/form-data'>
<table width='75%' border='0' class="contenedorPrincipal"> <tr><td bgcolor='#FFF'>
<table width='100%' border='0'>
<tr >
<td align='center' >
 <div class="tituloFondo">
    <div class="tituloSobreponeFondo">
     DEVOLUCION
    </div>
 </div>

</td>
</tr>
</table>

<span class='letrastabla'>Buscar Devolución:</span><br /><input type='text' id='abuscar' name="abuscar"/><?php
tieneacceso('devolucion');
function fechaDMA($fecha){ list($a,$m,$d)=explode('-',$fecha); return $d.'-'.$m.'-'.$a;  }

echo "<select name='campo' id='campo'>";
	echo "<option value=iddevolucion>Nº</option>";
	echo "<option value=producto>Producto</option>";
	echo "<option value=responsable>Responsable</option>";
echo '</select>';

$e = (!isset($_POST['estado'])) ? 1 : $_POST['estado'];
?>
<input type='text' id="fecha" name="fecha" class="date" size="12" />
<input type="hidden" id="estado" name="estado" value="<?php echo $e;?>" />
&nbsp;<input name='buscar' type='submit' class='botongeneral' id='buscar' value='Buscar' />
<input name='nuevo' type='button' class='botongeneral' id='nuevo' value='Nuevo' onClick="location.href='nuevo_devolucion.php'"/>
<br />
<table width='100%' border='0' id='tabla' style='margin-top:5px;'>
<tr style='background-image: url(a.jpg);'>
<td width='544' ><? if (isset($_POST['estado']) && ($_POST['estado'] == 0))  echo ' Registros con estado inactivo ';?></td>
<td width='151' style='font-family: Verdana, Geneva, sans-serif;'> 
  <div align="right">Activos </div></td>
<td width='101'><span style="font-family: Verdana, Geneva, sans-serif;">
<input type='checkbox'  title= 'Click Aqui para ver solo los registros activos'
<?php if (isset($_POST['estado']) && ($_POST['estado'] == 1))  echo ' checked ';
 if (!isset($_POST['estado']))
 echo 'checked';
?>  name='estad' id='estad'  onclick='estados();'/>
</span></td>
</tr>
</table>
<div style="overflow:auto;position:relative;height:380px;">

<?php
include('includes/funciones.php');
if (isset($_POST['abuscar'])){
 $cFecha = ($_POST['fecha'] == "") ? "" : " fecha ='".$db->GetFormatofecha($_POST['fecha'],'/')."' and ";	 
 $condicion = " where ".$_POST['campo']." like '".$_POST['abuscar']."%' and $cFecha estado=".$_POST['estado'];
}
else 
if (isset($_POST['estado'])) 
 $condicion = " where estado = ".$_POST['estado'];
else 
 $condicion = ' where estado = 1';


$sql = "SELECT iddevolucion,producto,date_format(fecha,'%d/%m/%Y'),left(responsable,25),left(observaciones,30) from devolucion".$condicion." order by iddevolucion desc";
mysql_query("SET NAMES 'utf8'");
$res = consulta($sql);
$n = mysql_num_rows($res);




$nombres = array(  "Nº", "Producto", "Fecha", "Responsable", "Observaciones" );
echo "<table border='0'  align='center'>";
echo "<table width='100%' border='0' align='center'>";
echo "<thead><tr style='background-image: url(fondo.jpg); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;'>";
echo "<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>";

for( $i = 0 ; $i <=4; $i++ ){
echo "<th>".ucfirst($nombres[$i])."</th>";
}
if( $n > 0 ){
$par=0;
while( $fila = mysql_fetch_row($res)){
if ($par%2!=0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";
$par++;	

	
$id = $fila[0];

if (isset($_POST['estado']) && ($_POST['estado'] == 0)){	
	echo "<td><div align='center'> <img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /></div></td>";	
	echo "<td><div align='center'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' /></div></td>";
}
else{
echo "<td><div align='center'><a href='modificar_devolucion.php?iddevolucion=".$id."&sw=1#t5' onclick='return modificar(this)'> <img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /><br /></a></div></td>";
	echo "<td><div align='center'><a href='anular_registro.php?iddevolucion=".$id."
&tabla=devolucion&menu=5' onclick='return anular(this)'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' /><br /></a></div></td>";
}
	echo "<td><div align='center'><a href='imprimir_devolucion.php?iddevolucion=".$id."' onclick='return imprimir(this)'><img src='css/images/imprimir.gif' title='Imprimir' alt='imprimir' border='0' /><br /></a></div></td>";
		for( $i = 0 ; $i <= 4 ; $i++ ){
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

}
echo "</table>";
?>
</div>
</tr></td></table>
</form>
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
