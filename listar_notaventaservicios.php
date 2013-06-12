<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	if (!isset($_SESSION)) {
	  session_start();
	}
	include('conexion.php');
	include('aumentaComa.php');
	$db = new MySQL();
	$MM_authorizedUsers = "";
	$MM_donotCheckaccess = "true";
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Ventas'],'Venta de Servicios'
	,'listar_notaventaservicios.php','nuevo_notaventa_servicio.php');
	if ($fileAcceso['Acceso'] == "No") {
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
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateventas.dwt.php" codeOutsideHTMLIsLocked="false" -->
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

function eliminarTransaccion(){
  location.href = "anular_registro.php?idnotaventa="+$$("idDelete").value
  +"&tabla=notaventa&menu=3&salto=notaventaservicios&libro=Nota Venta Servicios&tipolibro=ingreso";
}

var $$ = function(id){
  return document.getElementById(id);
}

var openMensaje = function(codigo){
	$$("idDelete").value = codigo; 
	$$("modal_tituloCabecera").innerHTML = 'Advertencia';
	$$("modal_contenido").innerHTML = '¿Desea anular esta Nota de Venta?';
	$$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";  
}

var closeMensaje = function(){
	$$("modal_mensajes").style.visibility = "hidden";
    $$("overlay").style.visibility = "hidden";    
}

var openVentanaImprimir = function(codigo){
    $$("idDelete").value = codigo; 
    $$('overlay').style.visibility = "visible";
    $$('modal_vendido').style.visibility = "visible"; 
}

function accionPostRegistro(){
   window.open('factura/servicio/imprimir_notaventa_servicios.php?idnotaventa='+$$("idDelete").value+'&logo='+$$("logo").checked,'target:_blank');	
   cerrarPagina();
}

function cerrarPagina(){
    $$('overlay').style.visibility = "hidden";
    $$('modal_vendido').style.visibility = "hidden"; 
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


<div id="overlay" class="overlays"></div>

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
        <div class="modal_boton1MsgBox"><input type="button" value="Cancelar" class="botonNegro" onclick="closeMensaje()"/></div>
        <div class="modal_boton2MsgBox">
        <input type="button" value="Aceptar" class="botonNegro" onclick="eliminarTransaccion()"/></div>
      </div>
  </div>
 </div>




<div id="modal_vendido" class="contenedorMsgBoxOption">
  <div class="modal_interiorMsgBoxOption"></div>
  <div class="modalContenidoMsgBoxOption">
      <div class="cabeceraMsgBoxOption">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBoxOption">Opciones del Sistema</div>
        <div class="modal_cerrarMsgBoxOption">
         <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="cerrarPagina()"></div>
      </div>
      <div class="contenidoMsgBoxOption">
        <div class="modal_datosMsgBoxOption" id="modal_contenido"> Seleccione los parámetros para 
        poder visualizar el reporte.  </div>
         <table width="311" style="margin-top:40px;" align="center">
            <tr>            
            <td><input type="button" value=" Ver Reporte " onclick="accionPostRegistro();" class="botonNegro"/></td>
            <td align="right">Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
         </table>   
        
      </div>
  </div>
</div>




<div class="cabeceraFormulario">
  <div class="menuTituloFormulario"> Ventas > Venta de Servicios </div>
  <div class="menuFormulario"> 
   <?php
     $estructura = $_SESSION['estructura'];
     $menus = $estructura['Ventas'];
     $privilegios = $db->getOpciones($menus, "Venta de Servicios"); 
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

<form name="listado" id="listado" method='get' action='listar_notaventaservicios.php#t3' enctype='multipart/form-data'>
<table width='75%' border='0' class="contenedorPrincipal"> <tr><td bgcolor='#FFF'>

  <div class="contenedorFechaListar">
       <div class="borde1UsuarioLF"> </div>
       <div class="interiorLF">
     <input name="fecha" type="text" id="fecha"  style="height:14px;color:#FFF;background:#000;border:none;" size="10" 
     value="" />
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
				   echo "<option value=n.numero>Nº</option>";
	               echo "<option value=c.nombre>Nombre Cliente</option>";
	               echo "<option value=numfactura>Nº Factura</option>";
	               echo "<option value=s.nombrecomercial>Sucursal</option>";
     	           echo "<option value=n.moneda>Moneda</option>";
				   echo "<option value=t.nombre>Usuario</option>";
			  ?>
            </select>
   </div> 

	<?php
       $e = (!isset($_GET['estado'])) ? 1 : $_GET['estado'];
    ?>


<input type="hidden" id="estado" name="estado" value="<?php echo $e;?>" />
<input type="hidden" name="idDelete" id="idDelete" />
<br />
<table width='100%' border='0' id='tabla' style='margin-top:5px;'>
<tr>
<td width='544' ><?php if (isset($_GET['estado']) && ($_GET['estado'] == 0))  echo '';?></td>
<td width='151' style='font-family: Verdana, Geneva, sans-serif;'> 
  <div align="right">Activos </div></td>
<?php
	$currentPage = $_SERVER["PHP_SELF"];
	
	$hasta = 27;
	$numeroPagina = 0;
	if (isset($_GET['numPage'])) {
	  $numeroPagina = $_GET['numPage'];
	}
	$desde = $numeroPagina * $hasta;	
	
	if (isset($_GET['abuscar'])) {
	$cFecha = ($_GET['fecha'] == "") ? "" : " fecha ='".$db->GetFormatofecha($_GET['fecha'],'/')."' and ";
	$condicion = " where n.idcliente = c.idcliente and n.idsucursal=s.idsucursal 
	 and n.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador and $cFecha n.tiponota='servicios' and "
	.$_GET['campo']." like '".$_GET['abuscar']."%' and n.estado=".$_GET['estado'];
	} else {
		if (isset($_GET['estado'])) {
			$condicion = " where n.idcliente = c.idcliente and n.idsucursal=s.idsucursal and 
			 n.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador 
			 and n.tiponota='servicios' and n.estado = ".$_GET['estado'];
		} else  {
			$condicion = " where n.idcliente = c.idcliente and  
			 n.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador 
			 and n.idsucursal=s.idsucursal and n.tiponota='servicios' and n.estado = 1";
		}
	}
	
	
	$sql = "SELECT idnotaventa,left(concat(c.nombre),25),numfactura,fecha
	,round(monto,2),left(s.nombrecomercial,25),n.moneda
	,n.tipocambio,n.numero,left(concat(t.nombre,' ',t.apellido),20)
	 from notaventa n,cliente c,sucursal s,usuario u,trabajador t "
	.$condicion." order by idnotaventa desc";
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

<td width='47'><span style="font-family: Verdana, Geneva, sans-serif;">
  <input type='checkbox'  title= 'Click Aqui para ver solo los registros activos'
<?php if (isset($_GET['estado']) && ($_GET['estado'] == 1))  echo ' checked ';
 if (!isset($_GET['estado']))
 echo 'checked';
?>  name='estad' id='estad'  onclick='estados();'/>
</span></td>
<td width='18'>
<?php if ($numeroPagina > 0) { ?>
   <a href="<?php printf("%s?numPage=%d%s#t3", $currentPage, 0, $consultaRegistro); ?>"><img src="images/first.png" title="Primero"/></a>
<?php } ?>
</td>
<td width='17'>
<?php if ($numeroPagina > 0) {  ?>  
    <a href="<?php printf("%s?numPage=%d%s#t3", $currentPage, max(0, $numeroPagina - 1), $consultaRegistro); ?>">
    <img src="images/prev.png" title="Anterior"/></a>
<?php }?>
</td>
<td width='17'>
<?php if ($numeroPagina < $totalPages_Recordset1) {  ?>
<a href="<?php printf("%s?numPage=%d%s#t3", $currentPage, min($totalPages_Recordset1, $numeroPagina + 1), $consultaRegistro); ?>"><img src="images/next.png" title="Siguiente"/></a>
 <?php } ?>
</td>
<td width='21'>
<?php if ($numeroPagina < $totalPages_Recordset1) {  ?>
  <a href="<?php printf("%s?numPage=%d%s#t3", $currentPage, $totalPages_Recordset1, $consultaRegistro); ?>"><img src="images/last.png" 
        title="Ultimo"/></a>
<?php }  ?>
</td>
<td width='32'>&nbsp;</td>
</tr>
</table>

<div style="overflow:auto;position:relative;height:613px;">
<?php
$nombres = array(  "Nº", "Cliente", "Nº Factura", "Fecha", "Total", "Sucursal", "Moneda", "Usuario");
echo "<table border='0'  align='center'>";
echo "<table width='100%' border='0' align='center'>";
echo "<thead><tr class='cabeceraInicialListar'>";
echo "<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>";

for( $i = 0 ; $i <= 7; $i++ ){
echo "<th>".ucfirst($nombres[$i])."</th>";
}
if( $n > 0 ){
$par=0;
while( $fila = mysql_fetch_row($res)){
if ($par%2!=0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";
$par++;	

	
$id = $fila[0];
if (isset($_GET['estado']) && ($_GET['estado'] == 0)){	
	echo "<td><div align='center'> <img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /></div></td>";	
	echo "<td><div align='center'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' /></div></td>";
}
else{
if ($fileAcceso['Modificar'] == "Si"){ 
	 echo "<td><div align='center'><a href='nuevo_notaventa_servicio.php?idnotaventa=".$id."&sw=1#t3' onclick='return modificar(this)'> <img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /><br /></a></div></td>";
	}else{
	 echo "<td><div align='center'><img src='css/images/edit.gif' title='Sin Privilegios para Modificar' alt='editar' border='0' /></div></td>";	
	}

if ($fileAcceso['Eliminar'] == "Si"){ 
	 echo "<td><div align='center' class='cursorDelete'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' onclick='openMensaje($id)'/><br /></div></td>";
	}else{
	 echo "<td><div align='center'><img src='css/images/borrar.gif'  title='Sin Privilegios para Anular' alt='borrar' border='0' /><br /></div></td>";	
	}
}

 echo "<td><div align='center' class='cursorDelete'><img src='css/images/imprimir.gif' title='Imprimir' alt='imprimir' border='0' onclick='openVentanaImprimir($id)'/><br /></div></td>";

	for( $i = 0 ; $i <= 7; $i++ ) {
		if ($fila[$i] == "") {
		echo "<td class='tdpar'>&nbsp;</td>";
		} else {
          switch($i){
			   case 0:
			  echo "<td align='center'>".$fila[8]."</td>";
			break;
			 case 1:
			  echo "<td>".$fila[$i]."</td>";
			break; 
			case 2:
			  echo "<td class='centro'>".$fila[$i]."</td>";
			break;
			case 3:
			  echo "<td class='centro'>".$db->GetFormatofecha($fila[$i],'-')."</td>";
			break;
			case 4:
			   if ($fila[6] == "Dolares") {
				 $monto = round(($fila[$i] / $fila[7]),4);
			   } else {
				 $monto = round($fila[$i],4);  
			   }		
			   echo "<td class='centro'>".number_format($monto,2)."</td>";
			break;
			case 5:
			  echo "<td class='centro'>".$fila[$i]."</td>";
			break;
			case 6:
			  echo "<td class='centro'>".$fila[$i]."</td>";
			break;
			case 7:
			  echo "<td class='centro'>".$fila[9]."</td>";
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