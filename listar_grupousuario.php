<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['softLogeoadmin'])){
header("Location: index.php");	
}
include('conexion.php');
$db = new MySQL();
$estructura = $_SESSION['estructura'];
$fileAcceso = $db->privilegiosFile($estructura['Administracion'],'Grupo de Usuarios','listar_grupousuario.php','nuevo_grupousuario.php');
if ($fileAcceso['Acceso'] == "No"){
  header("Location: cerrar.php");	
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
  location.href = "anular_registro.php?idgrupousuario="+$$("idDelete").value+"&tabla=grupousuario&menu=8";
}

var $$ = function(id){
  return document.getElementById(id);
}

 var openMensaje = function(codigo){
	$$("idDelete").value = codigo; 
	$$("modal_tituloCabecera").innerHTML = 'Advertencia';
	$$("modal_contenido").innerHTML = '¿Desea anular este Grupo de Usuario?';
	$$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";  
}

var closeMensaje = function(){
	$$("modal_mensajes").style.visibility = "hidden";
    $$("overlay").style.visibility = "hidden";    
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



<div class="cabeceraFormulario">
  <div class="menuTituloFormulario"> Administración > Grupo de Usuarios </div>
  <div class="menuFormulario"> 
   <?php
     $estructura = $_SESSION['estructura'];
     $menus = $estructura['Administracion'];
     $privilegios = $db->getOpciones($menus, "Grupo de Usuarios"); 
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

<form name="listado" id="listado" method='post' action='listar_grupousuario.php#t8' enctype='multipart/form-data'>
<table width='75%' border='0' align="center" class="contenedorPrincipal"> <tr><td bgcolor='#FFF'>

   <div class="contenedorSearch">
      <div class="borde1Listar"> </div>
        <input type="submit" id='buscar' name="buscar" class="iconSearch" value=""/>
        <input type="text"  name="abuscar" id="abuscar" class="borde2Listar"/>      
   </div> 

      <div class="listadoCombo1">
          <div class="borde1Listar"> </div>
            <select id="campo" name="campo" class="borde2Combo1">
              <?php
				echo "<option value=g.idgrupousuario>Nº</option>";
				echo "<option value=g.nombre>Nombre Grupo</option>";			  
			  ?>
            </select>
   </div> 

	<?php
       $e = (!isset($_GET['estado'])) ? 1 : $_GET['estado'];
    ?>

<input type="hidden" id="estado" name="estado" value="<?php echo $e;?>" />
<br />
<table width='100%' border='0' id='tabla' style='margin-top:5px;'>
<tr >
<td width='544' ><?php if (isset($_POST['estado']) && ($_POST['estado'] == 0))  echo '';?></td>
<td width='151' style='font-family: Verdana, Geneva, sans-serif;'> 
  <div align="right">Activos </div></td>

<script>
function estados(){
  if (document.getElementById('estad').checked){
    document.getElementById('estado').value = "1";
  }else{
    document.getElementById('estado').value = "0";
  }
  document.listado.submit();
}
</script>



<td width='101'><span style="font-family: Verdana, Geneva, sans-serif;">
  <input type='checkbox'  title= 'Click Aqui para ver solo los registros activos'
<?php
 if (isset($_POST['estado']) && ($_POST['estado'] == 1)) { 
 echo ' checked ';
 }
 if (!isset($_POST['estado'])){
 echo ' checked';
 }
 
?>  name='estad' id='estad' onclick='estados();'/>

<input type="hidden" name="idDelete" id="idDelete" />
</span></td>
</tr>
</table>
<div style="overflow:auto;position:relative;height:613px;">

<?php
include('includes/funciones.php');
if (isset($_POST['abuscar'])){
$condicion= "  and ".$_POST['campo']." like '".$_POST['abuscar']."%' and g.estado=".$_POST['estado'];
}
else 
if (isset($_POST['estado'])) 
$condicion= " and g.estado = ".$_POST['estado'];
else 
$condicion= ' and g.estado =1';




$sql = "SELECT g.idgrupousuario, left(g.nombre,25) , concat(t.nombre,' ',t.apellido)AS  nombre FROM usuario u,trabajador t,grupousuario g 
where t.idtrabajador = u.idtrabajador and g.idusuario=u.idusuario ".$condicion." ORDER BY g.idgrupousuario desc";
		
mysql_query("SET NAMES 'utf8'");
$res = consulta($sql);
$n = mysql_num_rows($res);




$nombres = array( "Nº","Nombre Grupo","Responsable");
echo "<table border='0'  align='center'>";
echo "<table width='100%' border='0' align='center'>";
echo "<thead><tr class='cabeceraInicialListar'>";
echo "<th>&nbsp;</th><th>&nbsp;</th>";

for( $i = 0 ; $i <=2; $i++ ){
if  ($i==0) echo '<th>Nº</th>';else
echo "<th>".ucfirst($nombres[$i])."</th>";
}
if( $n > 0 ){
$par=0;
while( $fila = mysql_fetch_row($res)){
if ($par%2!=0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";
$par++;	

	
$id = $fila[0];
 if (isset($_POST['estado']) && ($_POST['estado'] == 0)){
    echo "<td><div align='center'><img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /></div></td>";
	echo "<td><div align='center'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' /><br /></div></td>";
 }
 else{			
 if ($fileAcceso['Modificar'] == "Si"){ 
	 echo "<td><div align='center'><a href='nuevo_grupousuario.php?idgrupousuario=".$id."&sw=1#t8' > <img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /><br /></a></div></td>";
	} else {
	 echo "<td><div align='center'><img src='css/images/edit.gif' title='Sin Privilegios para Modificar' alt='editar' border='0' /></div></td>";	
	}
	
	if ($fileAcceso['Eliminar'] == "Si") { 
	 echo "<td><div align='center' class='cursorDelete'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' onclick='openMensaje($id)'/><br /></div></td>";
	} else {
	 echo "<td><div align='center'><img src='css/images/borrar.gif'  title='Sin Privilegios para Anular' alt='borrar' border='0' /><br /></div></td>";	
	}
 
 }

 
	for( $i = 0 ; $i <=2 ; $i++ ){
		if($fila[$i] == ""){
		echo "<td class='tdpar'>&nbsp;</td>";
		}
		else{        
		  echo "<td class='tdpar' align='center'>".$fila[$i]."</td>";	
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
</div>

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
