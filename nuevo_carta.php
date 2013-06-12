<?php 
	session_start();
	include_once('conexion.php');
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Agenda'],'Carta','nuevo_carta.php','listar_carta.php');
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena));
	}
	
	if (isset($_POST['transaccion'])) {
		$fecha = filtro($db->GetFormatofecha($_POST['fecha'],'/'));	
		if ($_POST['transaccion'] == "insertar") {
			$sql = "INSERT INTO carta(titulo,destinado,para,fecha,referencia,contenido,pie
			,nombrefirma,cargo,tipo,idusuario,estado) 
			VALUES ('".filtro($_POST['titulo'])."','".filtro($_POST['destinado'])."','"
			.filtro($_POST['para'])."','$fecha','"
			.filtro($_POST['referencia'])."','".$_POST['contenido']."','"
			.$_POST['pie']."','".filtro($_POST['nombrefirma'])."','"
			.filtro($_POST['cargo'])."','".filtro($_POST['tipo'])."',$_SESSION[id_usuario],1);";
		}
		if ($_POST['transaccion'] == "modificar") {
			$sql = "UPDATE carta SET destinado='".filtro($_POST['destinado'])
			."',para='".filtro($_POST['para'])."', titulo='".filtro($_POST['titulo'])
			."',fecha='$fecha', referencia='".filtro($_POST['referencia'])
			."',contenido='".$_POST['contenido']."',pie='".$_POST['pie']
			."',nombrefirma='".filtro($_POST['nombrefirma'])."',cargo='"
			.filtro($_POST['cargo'])."',tipo='".filtro($_POST['tipo'])
			."',idusuario=$_SESSION[id_usuario]  WHERE idcarta= '".$_POST['idcarta']."';";
		}
		$db->consulta($sql);
		header("Location: nuevo_carta.php#t14");
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	    $transaccion = "modificar";	
	    $sql = "SELECT * FROM carta WHERE idcarta= ".$_GET['idcarta'];
	    $datoCarta = $db->arrayConsulta($sql);  
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateagenda.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->

<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="js/jquery.validate.js"></script>
<script type="text/javascript" src="email/nicEdit.js"></script>

<script>


$(document).ready(function()
{
$("#fecha").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});

bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });

  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115){ //F4
    if (document.getElementById("cancelar") != null)
	 location.href = 'listar_carta.php#t14';
   }
	
   if(tecla == 113){ //F2
	 document.getElementById("enviar").click();	  
	}
 }

</script>

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
<div class="menuTituloFormulario"> Agenda > Carta </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Carta"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_carta.php' enctype='multipart/form-data'>
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr >
<td colspan='5' align='center' >

<table cellpadding='0' cellspacing='0' width='100%'>
 <tr class='cabeceraInicialListar'> 
 <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonseycon' id='enviar' value='Guardar [F2]' />
    <?php 
	    if ($fileAcceso['File'] == "Si") {
	       echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonseycon"
		    id="cancelar" value="Cancelar [F4]" onClick="location.href=&#039listar_carta.php#t14&#039"/>';	
	    }
	?> 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden" id="idcarta" name="idcarta" value="<?php echo $datoCarta['idcarta'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php 
		if (isset($_GET['idcarta'])) {
		    echo $_GET['idcarta'];
		} else {
		    echo $db->getNextID("idcarta", "carta");
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
</td>
</tr>
<tr>
  <td colspan='4'></td>
  <td width='87' rowspan='3' align='center'></td>
</tr>
<tr>
  <td width='132' align='right' valign='top'>Titulo<span class='rojo'>*</span>:</td>
  <td width='180' valign='top'>
  <input type='text' id="titulo" name="titulo" class="required"  value="<?php echo $datoCarta['titulo'];?>" size="30" />
  </td>
  <td width='85'  align='right' valign='top'>Fecha<span class='rojo'></span>:</td>
  <td width='191' valign='top'>
  <input type='text' id="fecha" name="fecha"  class="date" 
value="<?php 
if (isset($datoCarta['fecha']))
  echo $db->GetFormatofecha($datoCarta['fecha'],'-');
else
  echo date("d/m/Y");
?>"
size="10" /></td>
</tr>
<tr>
<td width='132' align='right' valign='top'>Para<span class='rojo'>*</span>:</td>
<td width='180' valign='top'><select id="destinado" name="destinado">
  <?php
	 $selec = $datoCarta['destinado']; 
	 $tipo = array("Señor", "Señora", "Señores");
	 for ($i = 0; $i < count($tipo); $i++) {
		$atributo = ""; 
		if ($selec == $tipo[$i]) {
		    $atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
	 }	
	?>
</select>  <input type='text' id="para" name="para" class="required"  value="<?php echo $datoCarta['para'];?>" size="20" />
</td>
<td width='85'  align='right' valign='top'>Referencia<span class='rojo'></span>:</td>
<td width='191' valign='top'>
<input type='text' id="referencia" name="referencia"  size="30" value="<?php echo $datoCarta['referencia'];?>"/>
</td>
</tr>
<tr>
  <td colspan="5" align='right' valign='top'><hr /></td>
  </tr>
<tr>
  <td colspan='5' >
  <div id='tabs'>
  
  <div id='tabs-1'>
  <table width='851' border='0' align='center'>
  <tr>
    <td align="right" valign="top">Contenido<span class='rojo'></span>:</td>
    <td colspan="7">
     <div style="position:relative;width:90%;height:210px;overflow:auto;border:0px solid;"> 
       <textarea name="contenido" id="contenido" maxlength="4000" 
         style="width:99%" rows="10"><?php echo $datoCarta['contenido'];?></textarea>
     </div> 
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Pie:</td>
    <td colspan="7">
    <div style="position:relative;width:90%;height:120px;overflow:auto;border:0px solid;"> 
      <textarea name="pie" id="pie" maxlength="1000" style="width:99%" 
       rows="4"><?php echo $datoCarta['pie'];?></textarea>
    </div>
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="8" align="left"><table width="100%" border="0">
      <tr>
        <td width="19%" align="right">Nombre Firma:</td>
        <td width="17%">
        <input type='text' id="nombrefirma" name="nombrefirma"  value="<?php echo $datoCarta['nombrefirma'];?>" size="20" />
        </td>
        <td width="9%" align="right">Cargo:</td>
        <td width="17%"><input type='text' id="cargo" name="cargo"  value="<?php echo $datoCarta['cargo'];?>" size="20" /></td>
        <td width="11%" align="right">Tipo:</td>
        <td width="27%"><select id="tipo" name="tipo">
        <?php
		 $selec = $datoCarta['tipo']; 
		 $tipo = array("cliente", "proveedor", "socios", "trabajador", "alcaldia"
		 , "prefectura", "impuestos", "sedes", "fundaempresa", "Cotizacion", "otros");
		 $texto = array("cliente", "proveedor", "socios", "trabajador", "alcaldia"
		 , "prefectura", "impuestos", "sedes", "fundaempresa", "Cotización", "otros");
		 for ($i = 0; $i < count($tipo); $i++) {
			$atributo = ""; 
			if ($selec == $tipo[$i]) {
			    $atributo = "selected='selected'";	
			}
			echo "<option value='$tipo[$i]' $atributo>".ucfirst($texto[$i])."</option>";
		 }	
		?>
        </select></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td colspan="2" align="right" valign="top">&nbsp;</td>
    <td width="89" align="left">&nbsp;</td>
    <td width="90" align="left">&nbsp;</td>
    <td width="87" align="right">&nbsp;</td>
    <td width="128" align="left">&nbsp;</td>
    <td width="39" align="right" valign="top">&nbsp;</td>
    <td width="166" valign="top">&nbsp;</td>
  </tr>
  <tr>
  <td width='62' align="right">&nbsp;</td>
  <td colspan="7">&nbsp;</td>
  </tr>
  </table>

  </div>
 
  </div> 
  </td>
</tr>

</table>
</form>
</div>
<br />
<br />
<br />

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