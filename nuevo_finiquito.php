<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();  
	include('conexion.php');
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])){
		   header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Recursos'],'Liquidación','nuevo_finiquito.php','listar_finiquitos.php');
	if ($fileAcceso['Acceso'] == "No"){
	  header("Location: cerrar.php");	
	}
	
	$transaccion = "insertar";
	if(isset($_GET['sw'])){
	  $transaccion = "modificar";	
	  $sql = "SELECT * FROM finiquitos WHERE idfiniquitos = ".$_GET['idfiniquitos'];
	  $datoTransaccion = $db->arrayConsulta($sql);  
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
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script src="finiquitos/finiquitos.js"></script>

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
</script>
<style>
.bordeContenido{
  border: 1px solid #CCC;	
}

.subtitulosFiniquitos{
  background:#E2E2E2;	
  font-weight:bold;
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

.overlays{
  position:fixed; top:0px; left:0px; width: 100%; height: 100%; 
  z-index:3009; 
  background-color: #000;
  opacity:.50;
  -moz-opacity: 0.50;
  filter: alpha(opacity=50);
  visibility:hidden;
}

.modal_mensajes{
  position:fixed;
  left:38%;
  top:37%;
  width:350px;
  height:160px;
  background-color:#FFFFFF;
  visibility:hidden;
  border:4px solid #DB6104;
  z-index:500;
  border-radius: 6px;
  -moz-border-radius: 6px;
  -webkit-border-radius: 6px;
  -khtml-border-radius: 6px;
  -webkit-box-shadow: #666 3px 4px 3px;
  -moz-box-shadow: #666 3px 4px 3px;
  box-shadow: #666 3px 4px 3px;	
  z-index:4010; 
}

.modal_cabecera{
  position:relative;
  width:100%;
  height:30px;
  background-color:#DB6104;	
  border-bottom:2px solid #FF9900;
  border-radius: 1px 1px 0 0;
  -moz-border-radius: 1px 1px 0 0;
  -webkit-border-radius: 1px 1px 0 0;
  -khtml-border-radius: 1px 1px 0 0;	
}

.modal_tituloCabecera{
  position:absolute;
  top:7px;
  left:8px;
  font-size:12px;
  font-weight:bold;
  color:#FFF;	
}

.modal_cerrar{
  position:absolute;
  top:7px;
  left:92%;	
}

.modal_icono_modal{
  position:absolute;
  top:45px;
  left:20px;
  width:30px;
  height:30px;	
}

.modal_boton1{
  position:absolute;
  top:120px;
  left:250px;
  width:75px;	
  height:25px;
  border: 0.2px solid #DB6104; 
  border-radius: 2px;
  -webkit-border-radius: 2px;
  -moz-border-radius: 2px;
  border-radius: 2px;
}

.boton_modal{
  background-color:#FFF;
  width:75px;
  height:25px;
  cursor:pointer;	
}

.modal_contenido{
  position:absolute;
  top:50px;
  left:55px;
  width:260px;
  height:65px;
  border:0px solid;	
  text-align:left;
  font-size:11px;
  color:#333;
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

<div class="menuTituloFormulario"> Recursos > Liquidación </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Liquidación"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_departamento.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;<input name='enviar' type='button' class='botonNegro' 
    id='enviar' value='Guardar [F2]' onclick="enviarDetalle()" />
	<?php 
        if ($fileAcceso['File'] == "Si") {
            echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar"
		    value="Cancelar [F4]" onClick="location.href=&#039listar_finiquitos.php#t11&#039"/>';	
        }
    ?>
 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idfiniquitos" name="idfiniquitos" value="<?php echo $datoTransaccion['idfiniquitos'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php 
	if(isset($_GET['idfiniquitos'])) {
	  echo $_GET['idfiniquitos'];
	} else {
	  echo $db->getNextID('idfiniquitos', 'finiquitos');
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
<table border='0' width='100%' align='center' cellpadding='4' cellspacing='3' >
<tr >
<td colspan='4' align='center' ></td>
</tr>
<tr>
  <td colspan='4'><table width="98%" border="0" align="center" class="bordeContenido">
    <tr>
      <td align="right">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" align="right">Fecha:
        <input type='text' id="fecha" name="fecha" class="date" size="10" 
              value="<?php 
			  if (isset($_GET['idfiniquitos']))
			  echo $db->GetFormatofecha($datoTransaccion['fecha'],"-");
			  else
			  echo date("d/m/Y");?>"/></td>
      <td colspan="2" align="right">Trabajador<span class='rojo'>*</span>:</td>
      <td><select name="trabajador" id="trabajador" class="required">
        <option value="" >-- Seleccione --</option>
        <?php
			$sql = "select idtrabajador,left(concat(nombre,' ',apellido),20)as 'nombre' from trabajador where estado=1;";
			$db->imprimirCombo($sql,$datoTransaccion['idtrabajador']);      
        ?>
      </select></td>
      <td width="18%" align="right">Motivo de Retiro:</td>
      <td width="27%">
        <select name="motivo" id="motivo">
  
          <?php
			 $selec = $datoTransaccion['motivo']; 
			 $tipo = array("Despido","Voluntario","Preaviso");
			 for ($i = 0; $i < count($tipo); $i++) {
				$atributo = ""; 
				if ($selec == $tipo[$i]) {
				    $atributo = "selected='selected'";	
				}
				echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
			 }	
		  ?>
          
        </select></td>
      </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td width="1%" align="center">&nbsp;</td>
      <td width="16%" align="right" class="subtitulosFiniquitos">Vacación De</td>
      <td width="18%"><input type='text' id="mesesvacaciones" name="mesesvacaciones"  
      onkeypress="return soloNumeros(event)" size="10" value="<?php echo $datoTransaccion['mesesvacaciones'];?>"/>
        Meses Y</td>
      <td><input type='text' id="diasvacaciones" name="diasvacaciones"  
      onkeypress="return soloNumeros(event)" size="10" value="<?php echo $datoTransaccion['diasvacaciones'];?>"/>
        Días</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="right" class="subtitulosFiniquitos">Prima Legal</td>
      <td><input type='text' id="mesesprima" name="mesesprima"  onkeypress="return soloNumeros(event)" 
      size="10" value="<?php echo $datoTransaccion['mesesprima'];?>"/>Meses Y</td>
      <td><input type='text' id="diasprima" name="diasprima"  onkeypress="return soloNumeros(event)"
       size="10" value="<?php echo $datoTransaccion['diasprima'];?>"/>Días</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="right" class="subtitulosFiniquitos">Otros por Motivo</td>
      <td><input type='text' id="descripcionotros" name="descripcionotros"  class="required" size="15"
       value="<?php echo $datoTransaccion['descripcionotros'];?>"/>
        Total:</td>
      <td><input type='text' id="montootros" name="montootros"  onkeypress="return soloNumeros(event)"
       size="10" value="<?php echo $datoTransaccion['totalotros'];?>"/>
        Bs</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" align="center">&nbsp;</td>
      </tr>
  </table>
  <div style="position:relative;width:99%;left:6px;">
    <div id="submenu" class="submenu">
      <table width="98%" border="0" id="datosorigen" align="center">
        <tr>
          <td width="129" align="left"><strong>Sección de Descuentos</strong></td>
          <td width="71" align="right">Descripción:</td>
          <td width="107"><input type="text" name="descripcionD" id="descripcionD" style="width:95%"/></td>
          <td width="60" align="right">Total:</td>
          <td width="100"><input type="text" name="totalD" id="totalD" style="width:70%"
           onkeypress="return soloNumeros(event)"  onkeyup="eventoText(event);"/></td>
          <td width="68"><input type="button"  class='botonNegro' value="Agregar [Enter]"
           onclick="cargarDatos('datosorigen','detallegrupo')"/></td>
          </tr>
        </table>
    </div>  
   </div> 
    </td>
</tr>
<tr>
  <td colspan='4' ><div id="cuerpo" style="position:relative;height:200px;overflow:auto;border:1px solid #E2E2E2;left:6px;width:98%">
    <table width="100%" border="0" id="tabla" >
      <tr class="filadetalleui">
        <th width="19" >&nbsp;</th>
        <th width="20" align="center">Nº</th>
        <th width="366" align="center">Descripción</th>
        <th width="107" align="center">Total</th>
        </tr>
      <tbody id="detallegrupo">
        <?php
			 if (isset($datoTransaccion['idfiniquitos'])){
               $sql = "select *from descuentofiniquitos where idfiniquitos=$datoTransaccion[idfiniquitos] order by iddescuento asc";
			   $detalle = $db->consulta($sql);
			   $i = 0;
			     while($dato = mysql_fetch_array($detalle)){
				  $i++; 
			       echo "
				    <tr >
                      <td align='center'><img src='css/images/borrar.gif' title='Anular' alt='borrar'
					   onclick='eliminarFila(this)' /></td>
                      <td align='center'>$i</td>
                      <td>$dato[descripcion]</td>
                      <td align='center'>".number_format($dato['monto'],2)."</td>      
                    </tr>";	 
			     }
			 }
			?>
        </tbody>
      </table>
    </div></td>
</tr>

<tr>
<td colspan='4' ></td>
</tr>
</table>
</form>
</div>
</td></tr></table>
<script>  transaccion = '<?php echo $transaccion; ?>';</script>
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