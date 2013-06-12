<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include("conexion.php");
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])){
		   header("Location: index.php");	
	}
	
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Grupo y Subgrupo','nuevo_grupo.php','listar_grupo.php');
	if ($fileAcceso['Acceso'] == "No"){
	  header("Location: cerrar.php");	
	}
	
	$transaccion = "insertar";
	if (isset($_GET['idgrupo'])){
	 $sql = "select *from grupo where idgrupo=$_GET[idgrupo]";
	 $grupo = $db->arrayConsulta($sql);
	 $transaccion = "modificar";
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
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<link rel="stylesheet" href="grupo/grupo.css" type="text/css"/>
<script src="grupo/Ngrupo.js"></script>
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
  
<div id="overlay" class="overlays"></div>  
<div id="gif" class="gifLoader"></div>

 
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
        <div class="modal_boton1MsgBox"><input type="button" value="Aceptar" class="botonNegro" onclick="closeMensaje()"/></div>
      </div>
  </div>
</div>
 
  
  
<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Inventario > Grupo y Subgrupo </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Grupo y Subgrupo"); 
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
    <input name='enviar' type='button' class='botonNegro' id='enviar' value='Guardar [F2]' onclick="enviarDetalle();"/>
	<?php 
        if ($fileAcceso['File'] == "Si"){
         echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro"
		  id="cancelar" value="Cancelar [F4]" onclick="salir();"/>';	
        }
    ?>
 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idgrupo" name="idgrupo" value="<?php echo $grupo['idgrupo'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
    <?php
	 if (isset($_GET['idgrupo'])) {
		echo $_GET['idgrupo']; 
	 } else {
		echo $db->getNextID("idgrupo","grupo");
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
<tr >
<td colspan='5' align='center' ></td>
</tr>

<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td align='center'></td>
</tr>
<tr>
  <td width="49" align='right' valign='top'>&nbsp;</td>
  <td width="116" align="right">Nombre<span class='rojo'></span> Grupo<span class='rojo'>*</span>:</td>
  <td valign='top'>
  <input type='text' id="nombregrupo" name="nombregrupo"  class="required" 
  size="28" value="<?php echo $grupo['nombre'];?>" /></td>
  <td valign='top'>&nbsp;</td>
  <td align='center'></td>
</tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td align='center'></td>
</tr>
<tr>
  <td colspan='5' valign="top">  
  <div id="submenuGrupo" class="submenuGrupo">
  <table width="100%" border="0" id="datosorigen">
    <tr>
      <td width="100" align="right">Nombre Subgrupo:</td>
      <td width="122"><input type="text" name="nombreD" id="nombreD" style="width:80%"/></td>
      <td width="100" align="right">Descripción:</td>
      <td width="150"><input type="text" name="descripcionD" id="descripcionD" style="width:85%" onkeyup="eventoText(event);"/></td>
      <td width="65"><input type="button"  class='botonNegro' style="width:110px;" value="Registrar [Enter]"
       onclick="cargarDatos('datosorigen','detallegrupo')"/></td>
    </tr>
  </table>  
  </div>
  
  <div id="cuerpo" style="position:relative;height:420px;overflow:auto;">
    <table width="100%" border="0" id="tabla" >
        <tr class="filadetalleui">
         <th width="19" >&nbsp;</th>
         <th width="20" align="center">Nº</th>
         <th width="167" align="center">Nombre</th>
         <th width="306" align="center">Descripción</th>     
         <th width="306" align="center" style="display:none">Idsubgrupo</th> 
        </tr>
                
      <tbody id="detallegrupo">
      <?php
       if (isset($grupo['idgrupo'])){
         $sql = "select * from subgrupo where idgrupo=$grupo[idgrupo] and estado=1";
         $detalle = $db->consulta($sql);
         $i = 0;
           while($dato = mysql_fetch_array($detalle)) {
            $i++; 
             echo "
              <tr >
                <td align='center'><img src='css/images/borrar.gif' title='Anular' 
                alt='borrar' onclick='eliminarFila(this)' /></td>
                <td align='center'>$i</td>
                <td >$dato[nombre]</td>
                <td >$dato[descripcion]</td>     
                <td style='display:none'>$dato[idsubgrupo]</td> 
              </tr>";	 
           }
       }
      ?>	       
      </tbody>             
    </table>          
  </div>  
  </td>
</tr>
<tr>
<td colspan='5' >
 
</td>
</tr>
</table>
</form>
</div>
</td></tr></table>
<script>
  transaccion = '<?php echo $transaccion;?>';
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