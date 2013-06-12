<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();
	include_once('conexion.php');  	
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Administracion'],'Empresa','nuevo_empresa.php')){
	    header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena));
	}
	
	if (($_GET['idempresa'] != '' && $_GET['sw'] != 1) || $_POST['idempresa'] != '' ) {
		$destino = '';
		$archivo1 = $_FILES['imagen']['name'];
		$ahora = time();
		$actualizar_imagen = '';
		
		if ($archivo1 != '') {
		   $destino =  "files/$ahora".$archivo1;
		   copy($_FILES['imagen']['tmp_name'],$destino);
		   $actualizar_imagen = "imagen='$destino',";
		}
	  
		$consulta = mysql_query("select max(idempresa) as codigo from empresa");
		$empresa = mysql_fetch_array($consulta);
		$empresa = $empresa['codigo'];
	
		if ($empresa == '') {
			$sql = "INSERT INTO empresa
			(idempresa,nombrecomercial,nit,nombrenit,rubro,fechavencimientopago
			,ciudad,website,copiamail,reprepropietario,numinfocal,numrex,numcns,numempleador
			,numfundempresa,numlicenciafunciona,imagen,encabezadomenbrete,piemembrete
			,salariominimonac,salariominimonoimp,observaciones,cipropietario
			,numafiliado,idusuario,estado) VALUES (NULL,'".filtro($_POST['nombrecomercial'])
			."','".filtro($_POST['nit'])."','".filtro($_POST['nombrenit'])
			."','".filtro($_POST['rubro'])."','".filtro($_POST['fechavencimientopago'])
			."','".filtro($_POST['ciudad'])."','".filtro($_POST['website'])."','".
			filtro($_POST['copiamail'])."','".filtro($_POST['reprepropietario'])."','".
			filtro($_POST['numinfocal'])."','".filtro($_POST['numrex'])."','".
			filtro($_POST['numcns'])."','".filtro($_POST['numempleador'])."','".
			filtro($_POST['numfundempresa'])."','".filtro($_POST['numlicenciafunciona'])."','".
			filtro($destino)."','','','".filtro($_POST['salariominimonac'])."','".
			filtro($_POST['salariominimonoimp'])."','".filtro($_POST['observaciones'])."','".
			filtro($_POST['cipropietario'])."','".filtro($_POST['numafiliado'])."',$_SESSION[id_usuario],1);";
		} else {	
			$sql = "update empresa set nombrecomercial='".filtro($_POST['nombrecomercial'])
			."',rubro='".filtro($_POST['rubro'])."',fechavencimientopago='"
			.filtro($_POST['fechavencimientopago'])."',nit='".filtro($_POST['nit'])."',
			ciudad='".filtro($_POST['ciudad'])."',website='".filtro($_POST['website'])
			."',nombrenit='".
			filtro($_POST['nombrenit'])."',copiamail='".filtro($_POST['copiamail'])
			."',reprepropietario='".filtro($_POST['reprepropietario'])
			."',numinfocal='".filtro($_POST['numinfocal'])."',numrex='"
			.filtro($_POST['numrex'])."',numcns='".
			filtro($_POST['numcns'])."',
			numempleador='".filtro($_POST['numempleador'])."',numfundempresa='"
			.filtro($_POST['numfundempresa'])."',numlicenciafunciona='"
			.filtro($_POST['numlicenciafunciona'])."',$actualizar_imagen 
			encabezadomenbrete='',piemembrete='',salariominimonac='"
			.filtro($_POST['salariominimonac'])."',cipropietario='".
			filtro($_POST['cipropietario'])."',
			salariominimonoimp='".filtro($_POST['salariominimonoimp'])
			."',observaciones='".htmlspecialchars(strip_tags($_POST['observaciones']),ENT_QUOTES)
			."',numafiliado='".htmlspecialchars(strip_tags($_POST['numafiliado']),ENT_QUOTES)
			."',idusuario=$_SESSION[id_usuario] where idempresa=$empresa";
		}
		
		$_SESSION['nombreEmpresa'] = $_POST['nombrecomercial'];	      
		$_SESSION['nit'] = $_POST['nit'];
		mysql_query($sql);
		
		header("Location: nuevo_empresa.php?msj#t1");
	}
	if ($_GET['idempresa']== NULL) $sql = "SELECT * FROM empresa"; else
	$sql = "SELECT * FROM empresa WHERE idempresa= ".$_GET['idempresa'];
	$row = $db->arrayConsulta($sql);

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
<script async="async" src="empresa/empresa.js"></script>
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
<div class="menuTituloFormulario"> Administración > Empresa </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Administracion'];
   $privilegios = $db->getOpciones($menus, "Empresa"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_empresa.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
  <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
          <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
    
     </td>
    <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden"  id="idempresa" name="idempresa" value="<?php 
    if (isset($row['idempresa'])){
    echo $row['idempresa'];	
    }
    else
    echo "1";
    
    ?>" />
    
    </td>
    <td colspan="3" align='right'><table width="356" border="0">
      <tr>
        <td width="204" align="right"></td>
        <td width="142"></td>
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
<table width="100%" border='0' align='center' cellpadding='4' cellspacing='2' >
<tr >
<td colspan='7' align='center' ></td>
</tr>

<tr>
  <td colspan='6'></td>
  <td align='center'></td>
</tr>
<tr>
  <td width="211" align='right' valign='middle'>Nombre Comercial<span class='rojo'>*</span>:</td>
  <td width="232"  valign='middle'>
  <input type='text' id="nombrecomercial" name="nombrecomercial"  class="required" size="25" value="<?php echo $row['nombrecomercial'];?>"/></td>
  <td width="92"   align='right' valign='middle'>Rubro<span class='rojo'></span>:</td>
  <td width="92"   align='left' valign='middle'><select name="rubro" id="rubro">
    <?php
	 $selec = $row['rubro']; 
	 $tipo = array("Servicio","Comercial","Industrial");
	 for ($i=0;$i<count($tipo);$i++){
		$atributo = ""; 
		if ($selec == $tipo[$i]){
		$atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
	 }	
	?>
  </select></td>
  <td colspan="2" valign='middle'>&nbsp;</td>
  <td align='right'>&nbsp;</td>
</tr>
<tr>
  <td align='right' valign='middle'>Nombre Nit<span class='rojo'>*</span>:</td>
  <td  valign='middle'><input type='text' id="nombrenit" name="nombrenit"  class="required" size="25" value="<?php echo $row['nombrenit'];?>"/></td>
  <td   align='right' valign='middle'>Nit<span class='rojo'>*</span>:</td>
  <td   align='left' valign='middle'><input type='text' id="nit" onkeyup="asignarFecha(this.value)"  name="nit" class="required number" size="20" value="<?php echo $row['nit'];?>"/></td>
  <td colspan="2" align="left">&nbsp;</td>
  <td align='right'>&nbsp;</td>
</tr>
<tr>
  <td colspan="7" align='right' valign='middle'><hr /></td>
</tr>
<tr>
  <td colspan='7' ><div align='left' class='masagua'> Los campos con <span class='rojo'>(*) </span>son requeridos:</div></td>
</tr>
<tr>
<td colspan='7' >
<div>
<ul  class="menujs">
<li id="tabs1" class="listajs" onclick="viewMenu('tabs-1')" style="background-color:#8E8E8E;color:#FFF"><a>Empresa</a></li>
<li id="tabs2" class="listajs" onclick="viewMenu('tabs-2')"><a>Datos Legales</a></li>
</ul>
<div id='tabs-1' style="display:block; height:300px;">
<table width='97%' border='0' align='center'>
<tr>
  <td  align="right">&nbsp;</td>
  <td >&nbsp;</td>
  <td  align="right">&nbsp;</td>
  <td colspan="2" >&nbsp;</td>
</tr>
<tr>
<td width="172"  align="right">Representante/Propietario:</td>
<td width="183" >
  <div align="left">
    <input type='text' id="reprepropietario"  name="reprepropietario" class="" size="20" value="<?php echo $row['reprepropietario'];?>"/>
  </div></td>

<td width="163"  align="right">Copia de Correo:</td>
<td colspan="2" ><input type='text' id="copiamail"  name="copiamail" class="email" size="20" value="<?php echo $row['copiamail'];?>"/></td>
</tr>
<tr>
<td align="right"><div align="right">C.I. Propietario:</div></td>
<td >
  <div align="left">
    <input type='text' id="cipropietario"  name="cipropietario" class="" size="20" value="<?php echo $row['cipropietario'];?>"/>
  </div></td>

<td  align="right">Logotipo(200 Anc x 70 Alt):</td>
<td colspan="2" ><input type='file' id='foto'  name="imagen" class="" size="20" value="<?php echo $row['imagen'];?>"/></td>
</tr>
<tr>
<td  align="right"><div align="right">Ciudad<span class='rojo'>*</span>:</div></td>
<td >
  <div align="left">
    <select name="ciudad" id="ciudad">
      <?php echo $db->getDepartamentos($row['ciudad']);?>
    </select>
  </div></td>

<td   align="right">Fecha Vencimiento:</td>
<td colspan="2" ><input type='text' id="fechavencimientopago" name="fechavencimientopago" size="20" readonly="readonly" value="<?php echo $row['fechavencimientopago'];?>"/></td>
</tr>
<tr>
<td  align="right"><div align="right">Sitio Web (Url):</div></td>
<td >
  <div align="left">
    <input type='text' id="website"  name="website" class="url" size="30" value= '<?php echo $row['website'];?>'/>
  </div></td>

<td align="right">&nbsp;</td>
<td colspan="2" ><input type='file' id="encabezadomenbrete"  style="visibility:hidden" name="encabezadomenbrete" class="" size="20" value="<?php echo $row['encabezadomenbrete'];?>"/></td>
</tr>

<tr>
  <td  align="right">Observaciones:</td>
  <td colspan="4" align="left" valign="top" ><textarea name="observaciones" id="observaciones" cols="50" rows="2" ><?php echo $row['observaciones'];?></textarea></td>
</tr>
<tr>
<td  align="center">&nbsp;</td>
<td colspan="4"  align="center"><input type='file' id="piemembrete"  style="visibility:hidden" name="piemembrete" class="" size="20" value="<?php echo $row['piemembrete'];?>"/></td>
<td width="2"  align="center">&nbsp;</td>
</tr>

</table>
</div>
<div id='tabs-2' class="optionjs" style="height:300px;">
  <p>&nbsp;</p>
  <table width="97%" border="0" align="center">
    <tr>
      <td width="224"><div align="right">Nº de Fundaempresa:</div></td>
      <td width="139"><input type='text' id="numfundempresa"  name="numfundempresa" class="number" size="20" value="<?php echo $row['numfundempresa'];?>"/></td>
      <td width="128"><div align="right">Nº Infocal:</div></td>
      <td width="197"><input type='text' id="numinfocal"  name="numinfocal" class="number" size="20" value="<?php echo $row['numinfocal'];?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Nº de Licencia de Funcionamiento:</div></td>
      <td><input type='text' id="numlicenciafunciona"  name="numlicenciafunciona" class="number" size="20" value="<?php echo $row['numlicenciafunciona'];?>"/></td>
      <td><div align="right">Nº de R.E.X.:</div></td>
      <td><input type='text' id="numrex"  name="numrex" class="number" size="20" value="<?php echo $row['numrex'];?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Nº de Seguro Medico:</div></td>
      <td><input type='text' id="numcns"  name="numcns" class="number" size="20" value="<?php echo $row['numcns'];?>"/></td>
      <td><div align="right">Nº de Empleador:</div></td>
      <td><input type='text' id="numempleador"  name="numempleador" class="number" size="20" value="<?php echo $row['numempleador'];?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Nº de Afiliado:</div></td>
      <td><input type='text' id="numafiliado"  name="numafiliado" class="number" size="20"
       value="<?php echo $row['numafiliado'];?>"/></td>
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
    </tr>
    </table>
</div>
</div> 
</td>
</tr>
</table>
</form></div>
</td></tr></table>
<script>
leerURL();
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