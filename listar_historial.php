<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	if (!isset($_SESSION)) {
	  session_start();
	}
	include('conexion.php');
	$db = new MySQL();
	$MM_authorizedUsers = "";
	$MM_donotCheckaccess = "true";
	
	
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Recursos'],'Historial','listar_historial.php')){
	  header("Location: cerrar.php");	
	}	

	function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
	  $isValid = False; 
	  if (!empty($UserName)) { 
		$arrUsers = Explode(",", $strUsers); 
		$arrGroups = Explode(",", $strGroups); 
		if (in_array($UserName, $arrUsers)) { 
		  $isValid = true; 
		} 
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
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templaterecursos.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
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
if (confirm("Desea anular este elemento de la tabla historial?")==false){
return false;
}else{
return true;
}
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

<div class="cabeceraFormulario">
    <div class="menuTituloFormulario"> Recursos > Historial </div>
    <div class="menuFormulario"> 
     <?php
       $estructura = $_SESSION['estructura'];
       $menus = $estructura['Recursos'];
       $privilegios = $db->getOpciones($menus, "Historial"); 
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
<div style="position:relative;top:-17px;">
<form name="listado" id="listado" method='get' action='listar_historial.php#t7' enctype='multipart/form-data'>
<table width='75%' border='0' class="contenedorPrincipal"> <tr><td bgcolor='#FFF'>

 <div class="contenedorFechaListar">
       <div class="borde1UsuarioLF"> </div>
       <div class="interiorLF">
     <input name="fecha" type="text" id="fecha"  style="height:14px;color:#FFF;background:#000;border:none;" size="10" 
     value="<? echo date("d/m/Y");?>" />
       </div>
   </div>

   <div class="contenedorSearch2">
      <div class="borde1Listar"> </div>
        <input type="submit" id='buscar' name="buscar" class="iconSearch" value=""/>
        <input type="text"  name="abuscar" id="abuscar" class="borde2Listar"/>      
   </div> 

      <div class="listadoCombo2">
          <div class="borde1Listar"> </div>
            <select id="campo" name="campo" class="borde2Combo1">
              <?php
				echo "<option value=idhistorial>N°</option>";
				echo "<option value=t.nombre>Trabajador</option>";		  
			  ?>
            </select>
   </div> 

	<?php
       $e = (!isset($_GET['estado'])) ? 1 : $_GET['estado'];
    ?>


<br />
<table width='100%' border='0' id='tabla' style='margin-top:5px;'>
<tr>
<td width='544' ><? if (isset($_GET['estado']) && ($_GET['estado'] == 0))  echo ' Registros con estado inactivo ';?></td>
<td width='151' style='font-family: Verdana, Geneva, sans-serif;'> 
  <div align="right"></div></td>
<?php
	$currentPage = $_SERVER["PHP_SELF"];
	
	$hasta = 27;
	$numeroPagina = 0;
	if (isset($_GET['numPage'])) {
	  $numeroPagina = $_GET['numPage'];
	}
	$desde = $numeroPagina * $hasta;
	
	
	if (isset($_GET['abuscar'])){
	 $cFecha = ($_GET['fecha'] == "") ? "" : " h.fecha ='".$db->GetFormatofecha($_GET['fecha'],'/')."' and ";
	 $condicion= " where h.idtrabajador=t.idtrabajador and $cFecha ".$_GET['campo']." like '%".$_GET['abuscar']."%'";}
	else 
	if (isset($_GET['estado'])) 
	$condicion= " where h.idtrabajador=t.idtrabajador ";
	else 
	$condicion= " where h.idtrabajador=t.idtrabajador ";
	
	
	
	$sql = "SELECT idhistorial,left(concat(t.nombre,' ',t.apellido),25),date_format(h.fecha,'%d/%m/%Y')
	,h.descripcion from historial h,trabajador t ".$condicion." order by idhistorial desc";
	mysql_query("SET NAMES 'utf8'");
	
	$sqllimit = sprintf("%s LIMIT %d, %d", $sql, $desde, $hasta);
	$res = $db->consulta($sqllimit);
	$n = mysql_num_rows($res);
	
	
	if (isset($_GET['totalFilas'])) {
	  $totalPaginas = $_GET['totalFilas'];
	} else {
	  $totalPaginas = $db->getnumRow($sql);
	}
	$totalPages_Recordset1 = ceil($totalPaginas/$hasta)-1;
	
	$consultaRegistro = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "numPage") == false && 
			stristr($param, "totalFilas") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$consultaRegistro = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$consultaRegistro = sprintf("&totalFilas=%d%s", $totalPaginas, $consultaRegistro);
?>


<td width='47'>&nbsp;</td>

<td width='18'>
<?php if ($numeroPagina > 0) { ?>
   <a href="<?php printf("%s?numPage=%d%s#t7", $currentPage, 0, $consultaRegistro); ?>"><img src="images/first.png" title="Primero"/></a>
<?php } ?>
</td>
<td width='17'>
<?php if ($numeroPagina > 0) {  ?>  
    <a href="<?php printf("%s?numPage=%d%s#t7", $currentPage, max(0, $numeroPagina - 1), $consultaRegistro); ?>">
    <img src="images/prev.png" title="Anterior"/></a>
<?php }?>
</td>
<td width='17'>
<?php if ($numeroPagina < $totalPages_Recordset1) {  ?>
<a href="<?php printf("%s?numPage=%d%s#t7", $currentPage, min($totalPages_Recordset1, $numeroPagina + 1), $consultaRegistro); ?>"><img src="images/next.png" title="Siguiente"/></a>
 <?php } ?>
</td>
<td width='21'>
<?php if ($numeroPagina < $totalPages_Recordset1) {  ?>
  <a href="<?php printf("%s?numPage=%d%s#t7", $currentPage, $totalPages_Recordset1, $consultaRegistro); ?>"><img src="images/last.png" 
        title="Ultimo"/></a>
<?php }  ?>
</td>
<td width='32'>&nbsp;</td>
</tr>
</table>

<div style="overflow:auto;position:relative;height:613px;">
<?php
  $nombres = array(  "N°", "Trabajador", "Fecha", "Descripción");
  echo "<table border='0'  align='center'>";
  echo "<table width='100%' border='0' align='center'>";
  echo "<thead><tr class='cabeceraInicialListar'>";

  for( $i = 0 ; $i <=3; $i++ ) {
	  echo "<th>".ucfirst($nombres[$i])."</th>";
  }
  if( $n > 0 ){
  $par = 0;
  while( $fila = mysql_fetch_row($res)) {
  if ($par%2 != 0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";
  $par++;	
  
	  
  $id = $fila[0];

	for( $i = 0 ; $i <=3 ; $i++ ){
		if($fila[$i] == "")	{
		    echo "<td class='tdpar'>&nbsp;</td>";
		} else {
          switch($i){
			case 1:
			  echo "<td class='centro'>".$fila[$i]."</td>";
			break;
			case 2:
			  echo "<td class='centro'>".$fila[$i]."</td>";
			break;
			case 3:
			  echo "<td>".$fila[$i]."</td>";
			break;
			default:  
    		   echo "<td class='centro'>".$fila[$i]."</td>";
		  }		
		}
	}
	echo "</tr>";
}
}
echo "</table>";
?>
</div>
</tr></td></table>
</form></div>
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