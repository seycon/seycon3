<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();
	include('conexion.php');
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Administracion'],'Grupo de Usuarios'
	,'nuevo_grupousuario.php','listar_grupousuario.php');
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	  $transaccion = "modificar";	
	  $sql = "SELECT * FROM grupousuario WHERE idgrupousuario= ".$_GET['idgrupousuario'];
	  $datoGrupo = $db->arrayConsulta($sql);  
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
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script async="async" src="grupousuario/Ngrupousuario.js"></script>

<style>

.overlays{
  position:fixed; 
  top:0px; 
  left:0px; 
  width:100%; 
  height:100%; 
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
 <div id="gif" class="gifLoader"></div>
 <div id="overlay" class="overlays"></div>
 
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

 <table style="width:75%;top:38px;margin: 0 auto;position:relative;" border="0">
 <tr>
 <td>
<div class="contenedorPrincipal">
 <form id='formValidado' name='formValidado' method='post' action='' enctype='multipart/form-data'>  
 <div class="contemHeaderTop">
  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='button' class='botonNegro' id='enviar' value='Guardar [F2]' onclick="transaccionGrupo()" />
	  <?php 
          if ($fileAcceso['File'] == "Si"){
           echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
		   value="Cancelar [F4]" onClick="location.href=&#039listar_grupousuario.php#t8&#039"/>';	
          }
      ?>

 
   </td>
  <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
  <input type="hidden"  id="idgrupousuario" name="idgrupousuario" value="<?php echo $datoGrupo['idgrupousuario'];?>" /></td>
  <td colspan="3" align='right'><table width="356" border="0">
    <tr>
      <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
      <td width="142">
      <?php 
       if (isset($datoGrupo['idgrupousuario'])) {
         echo $datoGrupo['idgrupousuario'];
       } else {
         echo $db->getNextID("idusuario","grupousuario");
       }
  
      ?>
  </td>
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
<tbody>
<tr>
<td colspan='5' align='center' ></td>
</tr>
<tr>
<td colspan='3'>

</td>
<td align="right"></td>
<td width='108' rowspan='2' align='left' valign="top">&nbsp;</td>
</tr>

<tr height="40">
  <td width='174' align='right' valign='bottom'></td>
  <td width='101' align="right" >Nombre Grupo<span class='rojo'>*</span>:</td>
  <td width='138' align="left" ><input type='text' style="width:99%;" id="nombre" name="nombre"  class="required" value="<?php echo $datoGrupo['nombre'];?>"/></td>
  <td align="left"><div id="requerido" class="requerido">Este campo es requerido.</div></td>
</tr>
<?php
$gPrivilegio = array();
 if (isset($datoGrupo['idgrupousuario'])){
  $sql = "SELECT d.idaccion FROM grupousuario u,detalleaccion d where d.idgrupo=u.idgrupousuario and idgrupousuario=$datoGrupo[idgrupousuario] ORDER BY d.idaccion;";
  $result = $db->consulta($sql);  
   while($data = mysql_fetch_array($result)){
     $gPrivilegio["$data[idaccion]"] = $data["idaccion"];	
   }
 }
?>

<tr>
<td colspan='5' >
<div >
<ul  class="menujs">
<li id="tabs1" class="listajs" onclick="viewMenu('tabs-1')" style="background-color:#8E8E8E;color:#FFF"><a >Administración</a></li>
<li id="tabs2" class="listajs" onclick="viewMenu('tabs-2')"><a  >Inventario</a></li>
<li id="tabs3" class="listajs" onclick="viewMenu('tabs-3')"><a  >Recursos Humanos</a></li>
<li id="tabs4" class="listajs" onclick="viewMenu('tabs-4')"><a  >Activos Fijos</a></li>
<li id="tabs5" class="listajs" onclick="viewMenu('tabs-5')"><a  >Ventas</a></li>
<li id="tabs6" class="listajs" onclick="viewMenu('tabs-6')"><a  >Contabilidad</a></li>
<li id="tabs7" class="listajs" onclick="viewMenu('tabs-7')"><a  >Agenda</a></li>
</ul>
<div id='tabs-1' style="display:block; height:300px;">

<table width="90%" border="0">
  <tr>
    <td width="21%" align="right"><div style="font-weight:bold;" id="mensaje_adm">Marcar Todos:</div></td>
    <td width="64%"><input type="checkbox" id="tadmin"
     onclick="setMarcado('numadm','adm',this.id,'mensaje_adm')"/></td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>


<div class="optionUsuario">
<table width='100%' border='0' align='center'>
  <tr style="background-image: url(iconos/fondo.jpg);">
    <td align="center" width='296' class="negro">Privilegios del Grupo</td>
    <td class="negro" align="center" width='76'>Nuevo</td>
    <td class="negro" align="center" width='69'>Modificar</td>
    <td class="negro" align="center" width='77'>Eliminar</td>
    <td class="negro" align="center" width='53'>Listar</td>
    <td class="negro" align="center" width='52'>Reporte </td>    
  </tr>
  <?php 
  $sql = "
select a.casouso,
(select a1.idaccion from accion a1 where a1.accion='nuevo' and a1.casouso=a.casouso) as 'nuevo',
(select a2.idaccion from accion a2 where a2.accion='modificar' and a2.casouso=a.casouso) as 'modificar',
(select a3.idaccion from accion a3 where a3.accion='eliminar' and a3.casouso=a.casouso) as 'eliminar',
(select a4.idaccion from accion a4 where a4.accion='listar' and a4.casouso=a.casouso) as 'listar',
(select a5.idaccion from accion a5 where a5.accion='reporte' and a5.casouso=a.casouso) as 'reporte'
  from accion a where modulo='Administracion' group by casouso order by a.idaccion;
";
  $consulta = $db->consulta($sql);
  $num = 1;
    while($data = mysql_fetch_array($consulta)){
	  echo "<tr>";
	  echo "<td  align='left'>$data[casouso]</td>";
	  for ($j = 1; $j <= 5; $j++) {
		  if ($data[$j] != "") {
			  $atributo = ($gPrivilegio[$data[$j]] != "") ?  $atributo = "checked='checked'" : ""; 			  
			  echo " <td align='center'><input type='checkbox' id='adm$num' name='adm$num' value='$data[$j]' $atributo/></td>";
			  $num++;
		  } else {
			  echo " <td align='center'><img src='iconos/eraser.png' /></td>";  
		  }
		  
	  }
	  
	  echo "</tr>";
    }
  
  ?>
<input type="hidden" id="numadm" name="numadm" value="<?php echo $num;?>" />
</table>
</div>
</div>

<div id='tabs-2' class="optionjs" style="height:300px;">
<table width="90%" border="0">
  <tr>
    <td width="21%" align="right"><div style="font-weight:bold;" id="mensaje_inv">Marcar Todos:</div></td>
    <td width="64%"><input type="checkbox" id="tinventario"
     onclick="setMarcado('numinv','inv',this.id,'mensaje_inv')"/></td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<div class="optionUsuario">
<table width='100%' border='0' align='center'>
  <tr style="background-image: url(iconos/fondo.jpg);">
    <td align="center" width='296' class="negro">Privilegios del Grupo</td>
    <td class="negro" align="center" width='76'>Nuevo</td>
    <td class="negro" align="center" width='69'>Modificar</td>
    <td class="negro" align="center" width='77'>Eliminar</td>
    <td class="negro" align="center" width='53'>Listar</td>
    <td class="negro" align="center" width='52'>Reporte</td>    
  </tr>
  <?php 
  $sql = "
select a.casouso,
(select a1.idaccion from accion a1 where a1.accion='nuevo' and a1.casouso=a.casouso) as 'nuevo',
(select a2.idaccion from accion a2 where a2.accion='modificar' and a2.casouso=a.casouso) as 'modificar',
(select a3.idaccion from accion a3 where a3.accion='eliminar' and a3.casouso=a.casouso) as 'eliminar',
(select a4.idaccion from accion a4 where a4.accion='listar' and a4.casouso=a.casouso) as 'listar',
(select a5.idaccion from accion a5 where a5.accion='reporte' and a5.casouso=a.casouso) as 'reporte'
  from accion a where modulo='Inventario' group by casouso order by a.idaccion;
";
  $consulta = $db->consulta($sql);
  $num = 1;
    while($data = mysql_fetch_array($consulta)) {
	  echo "<tr>";
	  echo "<td  align='left'>$data[casouso]</td>";
	  for ($j = 1; $j <= 5; $j++) {
		  if ($data[$j] != "") {
			  $atributo = ($gPrivilegio[$data[$j]] != "") ?  $atributo = "checked='checked'" : ""; 
			  echo " <td align='center'><input type='checkbox' id='inv$num' name='inv$num' value='$data[$j]' $atributo/></td>";
			  $num++;
		  } else {
			  echo " <td align='center'><img src='iconos/eraser.png' /></td>";  
		  }
		  
	  }
	  
	  echo "</tr>";
    }
  
  ?>
<input type="hidden" id="numinv" name="numinv" value="<?php echo $num;?>" />
</table>
</div>
</div>

<div id='tabs-3' class="optionjs" style="height:300px;">
<table width="90%" border="0">
  <tr>
    <td width="21%" align="right"><div style="font-weight:bold;" id="mensaje_rec">Marcar Todos:</div></td>
    <td width="64%"><input type="checkbox" id="trecursos"
     onclick="setMarcado('numrec','rec',this.id,'mensaje_rec')"/></td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<div class="optionUsuario">
<table width='100%' border='0' align='center'>
  <tr style="background-image: url(iconos/fondo.jpg);">
    <td align="center" width='296' class="negro">Privilegios del Grupo</td>
    <td class="negro" align="center" width='76'>Nuevo</td>
    <td class="negro" align="center" width='69'>Modificar</td>
    <td class="negro" align="center" width='77'>Eliminar</td>
    <td class="negro" align="center" width='53'>Listar</td>
    <td class="negro" align="center" width='52'>Reporte</td>    
  </tr>
  <?php 
  $sql = "
select a.casouso,
(select a1.idaccion from accion a1 where a1.accion='nuevo' and a1.casouso=a.casouso) as 'nuevo',
(select a2.idaccion from accion a2 where a2.accion='modificar' and a2.casouso=a.casouso) as 'modificar',
(select a3.idaccion from accion a3 where a3.accion='eliminar' and a3.casouso=a.casouso) as 'eliminar',
(select a4.idaccion from accion a4 where a4.accion='listar' and a4.casouso=a.casouso) as 'listar',
(select a5.idaccion from accion a5 where a5.accion='reporte' and a5.casouso=a.casouso) as 'reporte'
  from accion a where modulo='Recursos' group by casouso order by a.idaccion;
";
  $consulta = $db->consulta($sql);
  $num = 1;
    while($data = mysql_fetch_array($consulta)){
	  echo "<tr>";
	  echo "<td  align='left'>$data[casouso]</td>";
	  for ($j = 1; $j <= 5; $j++) {
		  if ($data[$j] != ""){
			  $atributo = ($gPrivilegio[$data[$j]] != "") ?  $atributo = "checked='checked'" : ""; 
			  echo " <td align='center'><input type='checkbox' id='rec$num' name='rec$num' value='$data[$j]' $atributo/></td>";
			  $num++;
		  }else{
			  echo " <td align='center'><img src='iconos/eraser.png' /></td>";  
		  }
		  
	  }
	  
	  echo "</tr>";
    }
  
  ?>
<input type="hidden" id="numrec" name="numrec" value="<?php echo $num;?>" />
</table>
</div>
</div>

<div id='tabs-4' class="optionjs" style="height:300px;">
<table width="90%" border="0">
  <tr>
    <td width="21%" align="right"><div style="font-weight:bold;" id="mensaje_act">Marcar Todos:</div></td>
    <td width="64%"><input type="checkbox" id="tactivos" 
     onclick="setMarcado('numact','act',this.id,'mensaje_act')"/></td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<div class="optionUsuario">
<table width='100%' border='0' align='center'>
  <tr style="background-image: url(iconos/fondo.jpg);">
    <td align="center" width='296' class="negro">Privilegios del Grupo</td>
    <td class="negro" align="center" width='76'>Nuevo</td>
    <td class="negro" align="center" width='69'>Modificar</td>
    <td class="negro" align="center" width='77'>Eliminar</td>
    <td class="negro" align="center" width='53'>Listar</td>
    <td class="negro" align="center" width='52'>Reporte</td>    
  </tr>
  <?php 
  $sql = "
select a.casouso,
(select a1.idaccion from accion a1 where a1.accion='nuevo' and a1.casouso=a.casouso) as 'nuevo',
(select a2.idaccion from accion a2 where a2.accion='modificar' and a2.casouso=a.casouso) as 'modificar',
(select a3.idaccion from accion a3 where a3.accion='eliminar' and a3.casouso=a.casouso) as 'eliminar',
(select a4.idaccion from accion a4 where a4.accion='listar' and a4.casouso=a.casouso) as 'listar',
(select a5.idaccion from accion a5 where a5.accion='reporte' and a5.casouso=a.casouso) as 'reporte'
  from accion a where modulo='Activo' group by casouso order by a.idaccion;
";
  $consulta = $db->consulta($sql);
  $num = 1;
    while($data = mysql_fetch_array($consulta)) {
	  echo "<tr>";
	  echo "<td  align='left'>$data[casouso]</td>";
	  for ($j = 1; $j <= 5; $j++) {
		  if ($data[$j] != ""){
			  $atributo = ($gPrivilegio[$data[$j]] != "") ?  $atributo = "checked='checked'" : ""; 
			  echo " <td align='center'><input type='checkbox' id='act$num' name='act$num' value='$data[$j]' $atributo/></td>";
			  $num++;
		  } else {
			  echo " <td align='center'><img src='iconos/eraser.png' /></td>";  
		  }
		  
	  }
	  
	  echo "</tr>";
    }
  
  ?>
<input type="hidden" id="numact" name="numact" value="<?php echo $num;?>" />
</table></div>
</div>

<div id='tabs-5' class="optionjs" style="height:300px;">
<table width="90%" border="0">
  <tr>
    <td width="21%" align="right"><div style="font-weight:bold;" id="mensaje_vent">Marcar Todos:</div></td>
    <td width="64%"><input type="checkbox" id="tventas" 
     onclick="setMarcado('numvent','ven',this.id,'mensaje_vent')"/></td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<div class="optionUsuario">
<table width='100%' border='0' align='center'>
  <tr style="background-image: url(iconos/fondo.jpg);">
    <td align="center" width='296' class="negro">Privilegios del Grupo</td>
    <td class="negro" align="center" width='76'>Nuevo</td>
    <td class="negro" align="center" width='69'>Modificar</td>
    <td class="negro" align="center" width='77'>Eliminar</td>
    <td class="negro" align="center" width='53'>Listar</td>
    <td class="negro" align="center" width='52'>Reporte</td>    
  </tr>
  <?php 
  $sql = "
select a.casouso,
(select a1.idaccion from accion a1 where a1.accion='nuevo' and a1.casouso=a.casouso) as 'nuevo',
(select a2.idaccion from accion a2 where a2.accion='modificar' and a2.casouso=a.casouso) as 'modificar',
(select a3.idaccion from accion a3 where a3.accion='eliminar' and a3.casouso=a.casouso) as 'eliminar',
(select a4.idaccion from accion a4 where a4.accion='listar' and a4.casouso=a.casouso) as 'listar',
(select a5.idaccion from accion a5 where a5.accion='reporte' and a5.casouso=a.casouso) as 'reporte'
  from accion a where modulo='Ventas' group by casouso order by a.idaccion;
";
  $consulta = $db->consulta($sql);
  $num = 1;
    while($data = mysql_fetch_array($consulta)) {
	  echo "<tr>";
	  echo "<td  align='left'>$data[casouso]</td>";
	  for ($j=1; $j<=5; $j++) {
		  if ($data[$j] != ""){
			  $atributo = ($gPrivilegio[$data[$j]] != "") ?  $atributo = "checked='checked'" : ""; 
			  echo " <td align='center'><input type='checkbox' id='ven$num' name='ven$num' value='$data[$j]' $atributo/></td>";
			  $num++;
		  }else{
			  echo " <td align='center'><img src='iconos/eraser.png' /></td>";  
		  }
		  
	  }
	  
	  echo "</tr>";
    }
  
  ?>
<input type="hidden" id="numvent" name="numvent" value="<?php echo $num;?>" />
</table>
</div>
</div>

<div id='tabs-6' class="optionjs" style="height:300px;">
<table width="90%" border="0">
  <tr>
    <td width="21%" align="right"><div style="font-weight:bold;" id="mensaje_cont">Marcar Todos:</div></td>
    <td width="64%"><input type="checkbox" id="tcontabilidad" 
     onclick="setMarcado('numcont','con',this.id,'mensaje_cont')"/></td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<div class="optionUsuario">
<table width='100%' border='0' align='center'>
  <tr style="background-image: url(iconos/fondo.jpg);">
    <td align="center" width='296' class="negro">Privilegios del Grupo</td>
    <td class="negro" align="center" width='76'>Nuevo</td>
    <td class="negro" align="center" width='69'>Modificar</td>
    <td class="negro" align="center" width='77'>Eliminar</td>
    <td class="negro" align="center" width='53'>Listar</td>
    <td class="negro" align="center" width='52'>Reporte</td>    
  </tr>
  <?php 
  $sql = "
select a.casouso,
(select a1.idaccion from accion a1 where a1.accion='nuevo' and a1.casouso=a.casouso) as 'nuevo',
(select a2.idaccion from accion a2 where a2.accion='modificar' and a2.casouso=a.casouso) as 'modificar',
(select a3.idaccion from accion a3 where a3.accion='eliminar' and a3.casouso=a.casouso) as 'eliminar',
(select a4.idaccion from accion a4 where a4.accion='listar' and a4.casouso=a.casouso) as 'listar',
(select a5.idaccion from accion a5 where a5.accion='reporte' and a5.casouso=a.casouso) as 'reporte'
  from accion a where modulo='Contabilidad' group by casouso order by a.idaccion;
";
  $consulta = $db->consulta($sql);
  $num = 1;
    while($data = mysql_fetch_array($consulta)){
	  echo "<tr>";
	  echo "<td  align='left'>$data[casouso]</td>";
	  for ($j = 1; $j <= 5; $j++) {
		  if ($data[$j] != ""){
			  $atributo = ($gPrivilegio[$data[$j]] != "") ?  $atributo = "checked='checked'" : ""; 
			  echo " <td align='center'><input type='checkbox' id='con$num' name='con$num' value='$data[$j]' $atributo/></td>";
			  $num++;
		  }else{
			  echo " <td align='center'><img src='iconos/eraser.png' /></td>";  
		  }
		  
	  }
	  
	  echo "</tr>";
    }
  
  ?>
<input type="hidden" id="numcont" name="numcont" value="<?php echo $num;?>" />
</table>
</div>
</div>

<div id='tabs-7' class="optionjs" style="height:300px;">
<table width="90%" border="0">
  <tr>
    <td width="21%" align="right"><div style="font-weight:bold;" id="mensaje_age">Marcar Todos:</div></td>
    <td width="64%"><input type="checkbox" id="tagenda" 
     onclick="setMarcado('numage','age',this.id,'mensaje_age')"/></td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<div class="optionUsuario">
<table width='100%' border='0' align='center'>
  <tr style="background-image: url(iconos/fondo.jpg);">
    <td align="center" width='296' class="negro">Privilegios del Grupo</td>
    <td class="negro" align="center" width='76'>Nuevo</td>
    <td class="negro" align="center" width='69'>Modificar</td>
    <td class="negro" align="center" width='77'>Eliminar</td>
    <td class="negro" align="center" width='53'>Listar</td>
    <td class="negro" align="center" width='52'>Reporte</td>    
  </tr>
  <?php 
  $sql = "
select a.casouso,
(select a1.idaccion from accion a1 where a1.accion='nuevo' and a1.casouso=a.casouso) as 'nuevo',
(select a2.idaccion from accion a2 where a2.accion='modificar' and a2.casouso=a.casouso) as 'modificar',
(select a3.idaccion from accion a3 where a3.accion='eliminar' and a3.casouso=a.casouso) as 'eliminar',
(select a4.idaccion from accion a4 where a4.accion='listar' and a4.casouso=a.casouso) as 'listar',
(select a5.idaccion from accion a5 where a5.accion='reporte' and a5.casouso=a.casouso) as 'reporte'
  from accion a where modulo='Agenda' group by casouso order by a.idaccion;
";
  $consulta = $db->consulta($sql);
  $num = 1;
    while($data = mysql_fetch_array($consulta)){
	  echo "<tr>";
	  echo "<td  align='left'>$data[casouso]</td>";
	  for ($j = 1; $j <= 5; $j++) {
		  if ($data[$j] != ""){
			  $atributo = ($gPrivilegio[$data[$j]] != "") ?  $atributo = "checked='checked'" : ""; 
			  echo " <td align='center'><input type='checkbox' id='age$num' name='age$num' value='$data[$j]' $atributo/></td>";
			  $num++;
		  } else {
			  echo " <td align='center'><img src='iconos/eraser.png' /></td>";  
		  }
		  
	  }
	  
	  echo "</tr>";
    }
  
  ?>
<input type="hidden" id="numage" name="numage" value="<?php echo $num;?>" />
</table>
</div>
</div>

</div> 

</td>
</tr>
</tbody>
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