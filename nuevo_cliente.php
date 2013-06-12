<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();
	include('conexion.php'); 
	$db = new MySQL();

	include("cliente/Dcliente.php");
	$dbcliente = new Dcliente($db);
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Ventas'],'Clientes','nuevo_cliente.php','listar_cliente.php');
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}

    if ($_POST['transaccion'] == "insertar") {
        $dbcliente->insertarCliente();  
		header("Location: nuevo_cliente.php");	
	}
	
	if ($_POST['transaccion'] == "modificar") {
		$dbcliente->modificarCliente();
		header("Location: nuevo_cliente.php");
	}

	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	    $transaccion = "modificar";	
	    $sql = "SELECT * FROM cliente WHERE idcliente= ".$_GET['idcliente'];
	    $datoCliente = $db->arrayConsulta($sql);  
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
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script async="async" src="lib/Jtable.js"></script>
<script async="async" src="cliente/NCliente.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script>
$(function() {	$( '#tabs' ).tabs();	});

$(document).ready(function()
{
$("#fechacontacto").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechapropietario").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechainicio").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechafinal").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechaaniversario").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
});
</script>


<style>
.subIngreso2{
  border-radius: 5px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  position:relative;
  background:#E2E2E2;
  border:1px solid #CCC;
  width:90%;
  margin: 0 auto;
}

.overlays{
  position:fixed; 
  top:0px; 
  left:0px; 
  width: 100%; 
  height: 100%; 
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




<!-- fin base semi-transparente --> 
 
<!-- ventana modal -->  

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

<div class="menuTituloFormulario"> Ventas > Clientes </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Ventas'];
   $privilegios = $db->getOpciones($menus, "Clientes"); 
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


<table style="width:75%;top:38px;left:0px; margin: 0 auto;position:relative;"  >
 <tr>
 <td>
 <div class="contenedorPrincipal">
<form id='formValidado' name='formValidado' method='post' action='nuevo_cliente.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
          <input name='enviar' type='button' class='botonNegro' id='enviar' value='Guardar[F2]' onclick="ejecutarTransaccion()"/>
          <?php 
			  if ($fileAcceso['File'] == "Si") {
			   echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
			   value="Cancelar [F4]" onClick="location.href=&#039listar_cliente.php#t8&#039"/>';	
			  }
		  ?>          
          </td>
        <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
        <input type="hidden" id="idcliente" name="idcliente" value="<?php echo $datoCliente['idcliente'];?>" /></td>
        <td colspan="3" align='right'><table width="356" border="0">
          <tr>
            <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
            <td width="142">
              <?php 
                if (isset($_GET['idcliente'])) {
                    echo $_GET['idcliente'];
                } else {
                    echo $db->getNextID("idcliente","cliente");
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
<table  width='100%' border='0' align='center' cellpadding='4' cellspacing='3'>
<tr >
  <td colspan='6' align='center' ></td>
</tr>

<tr>
  <td width='249' align='right' valign='top'>Nombre Comercial<span class='rojo'>*</span>:</td>
  <td width='163' valign='top'> 	 <input type='text' id="nombre" name="nombre"  class="required" style="width:80%" value="<?php echo $datoCliente['nombre'];?>" />
  </td>
  <td width='238'  align='right' valign='top'>Teléfono<span class='rojo'></span>/Fax Of.:</td>
  <td width="137" colspan="3" valign='top'><input type='text' id="telefono" name="telefono"   style="width:50%" value="<?php echo $datoCliente['telefono'];?>" />
    </td>
</tr>
<tr>
<td width='249' align='right' valign='top'>Nombre Nit<span class='rojo'></span>:</td>
<td width='163' valign='top'><input type='text' id="nombrenit" name="nombrenit"  class="" style="width:80%" value="<?php echo $datoCliente['nombrenit'];?>" /> 	
</td>
<td width='238'  align='right' valign='top'>Nit<span class='rojo'>*</span>:</td>
<td colspan="3" valign='top'><input type='text' id="nit" name="nit" style="width:50%" value="<?php echo $datoCliente['nit'];?>"/></td>
</tr>
<tr>
  <td align='right' valign='top'>Ruta<span class="rojo">*</span>:</td>
  <td valign='top'><select id="ruta" name="ruta"  style="width:80%" class="required">
    <option value=""> -- Seleccione -- </option>
    <?php
	  $sql = "select idruta, left(nombre,25) from ruta where estado=1";
	  $db->imprimirCombo($sql,$datoCliente['ruta']);
    ?>
  </select></td>
  <td  align='right' valign='top'>Cuidad:</td>
  <td colspan="3" valign='top'>
  <select id="ciudad" name="ciudad">
    <?php $db->getDepartamentos($datoCliente['ciudad']); ?>
  </select></td>
</tr>
<tr>
  <td align='right' valign='top'>Fecha de Aniversario:</td>
  <td valign='top'><input type='text' id="fechaaniversario" name="fechaaniversario" 
  size="12" value="<?php 
  if (isset($datoCliente['idcliente']))
    echo $db->GetFormatofecha($datoCliente['fechaaniversario'], "-");
  else
    echo date("d/m/Y");	
	?>" /></td>
  <td  align='right' valign='top'>Dirección:</td>
  <td colspan="3" valign='top'>
  <input type='text' id="direccionoficina" name="direccionoficina" 
   style="width:50%" value="<?php echo $datoCliente['direccionoficina'];?>" /></td>
</tr>
<tr>
  <td align='right' valign='top'>Sucursal<span class="rojo">*</span>:</td>
  <td valign='top'>
  <select id="idsucursal" name="idsucursal" style="width:150px;">
    <option value=""> -- Seleccione -- </option>
    <?php
      $db->imprimirCombo("select idsucursal,left(nombrecomercial,20) from sucursal where estado=1"
      ,$datoCliente['idsucursal']);
    ?>
  </select>  
  </td>
  <td  align='right' valign='top'>&nbsp;</td>
  <td colspan="3" valign='top'>&nbsp;</td>
</tr>

<tr>
  <td colspan='6' >
  <div id='tabs'>
  <ul style='height:40px;'>
          <li id="tr" ><a id="t" href='#tabs-1'>Cliente</a></li>
          <li><a href='#tabs-2'>Contrato</a></li>                  
  </ul>
  <div id='tabs-1' style="font-size:17px;">
  <table width='83%' border='0' align='center'>
  <tr>
    <td colspan="2" align="center" style="font-weight:bold;">CONTACTO</td>
    <td colspan="2" align="center" style="font-weight:bold;">PROPIETARIO / REPRESENTANTE</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
  <td align="right"><div align="right">Nombre:</div></td>
  <td width='144'>
    <input type='text' id="nombrecontacto" name="nombrecontacto" style="width:80%" 
     value="<?php echo $datoCliente['nombrecontacto'];?>"/>
  </td>
  <td width='117'><div align="right">Nombre:</div></td>
  <td width='157' align="left">
    <input type='text' id="nombrepropietario" name="nombrepropietario" class="" style="width:80%"
     value="<?php echo $datoCliente['nombrepropietario'];?>"/>
  </td>
  </tr>
  <tr>
  <td align="right"><div align="right">Celular:</div></td>
  <td width='144'>
    <input type='text' id="celularcontacto" name="celularcontacto" style="width:80%"
     value="<?php echo $datoCliente['celular'];?>"  />
  </td>
  <td width='117' align=""><div align="right">Celular.:</div></td>
  <td width='157'align="left">    
    <input type='text' id="celularpropietario" name="celularpropietario" style="width:80%"
     value="<?php echo $datoCliente['emailpropietario'];?>" />
    </td>
  </tr>
  <tr>
  <td width='122' align="right"><div align="right">Mail Personal:</div></td>
  <td width='144'><input type='text' id="emailcontacto" name="emailcontacto" class="" style="width:80%"
   value="<?php echo $datoCliente['emailcontacto'];?>"/></td>
  <td width='117'><div align="right">Mail Personal:</div></td>
  <td width='157'align="left"><input type='text' id="emailpropietario" name="emailpropietario" 
  style="width:80%" value="<?php echo $datoCliente['emailpropietario'];?>"/></td>
  </tr>
  <tr>
  <td width='122' align="right">Fecha de Nacimiento:</td>
  <td width='144'><input type='text' id="fechacontacto" name="fechacontacto" 
  size="12" value="<?php 
  if (isset($datoCliente['idcliente']))
    echo $db->GetFormatofecha($datoCliente['fechacontacto'],"-");
  else
    echo date("d/m/Y");	
	?>" /></td>
  <td width='117'><div align="right">Fecha de Nacimiento:</div></td>
  <td width='157'align="left"><input type='text' id="fechapropietario" name="fechapropietario"
    size="12" value="<?php 
  if (isset($datoCliente['idcliente']))
    echo $db->GetFormatofecha($datoCliente['fechapropietario'],"-");
  else
    echo date("d/m/Y");	
	?>" /></td>
  </tr>
  <tr>
  <td width='122' align=""><div align="right">Mail Corporativo:</div></td>
  <td width='144'><input type='text' id="emailcorporativocontacto" name="emailcorporativocontacto" 
   style="width:80%"  value="<?php echo $datoCliente['emailcorporativocontacto'];?>"/></td>
  <td width='117'><div align="right">Mail Corporativo:</div></td>
  <td width='157'align=""><input type='text' id="emailcorporativopropietario" name="emailcorporativopropietario"
   style="width:80%" value="<?php echo $datoCliente['emailcorporativopropietario'];?>"/></td>
  </tr>
  <tr>
  <td width='122' align=""><div align="right">Skype Contacto:</div></td>
  <td width='144'><input type='text' id="skypecontacto" name="skypecontacto" class="" style="width:80%"
   value="<?php echo $datoCliente['skypecontacto'];?>"/></td>
  <td width='117'><div align="right">Skype Contacto:</div></td>
  <td width='157'align=""><input type='text' id="skypepropietario" name="skypepropietario"
   style="width:80%" value="<?php echo $datoCliente['skypepropietario'];?>"/></td>
  </tr>
  <tr>
  <td width='122' align=""></td>
  <td width='144'></td>
  <td width='117'>&nbsp;</td>
  <td width='157'align="">&nbsp;</td>
  </tr>
  <tr>
    <td width='122' align="right">&nbsp;</td>
    <td width='144'>&nbsp;</td>
    <td width='117' align="right">&nbsp;</td>
    <td width='157'align="">&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td width='144'>&nbsp;</td>
    <td width='117'>&nbsp;</td>
    <td width='157'align="right">&nbsp;</td>
  </tr>
  </table>
  </div>
    
    <!--Contrato-->
    <div id='tabs-2'>
      <table width="90%" border="0" align="center">
        <tr>
          <td width="14%" align="right">Modalidad:</td>
          <td width="9%">
          <select name="modalidad" id="modalidad" onchange="bloquear(this.value)">
			<?php
               $selec = $datoCliente['modalidad']; 
               $tipo = array("Contrato Abierto","Definido");
               for ($i = 0; $i < count($tipo); $i++) {
                  $atributo = ""; 
                  if ($selec == $tipo[$i]) {
                      $atributo = "selected='selected'";	
                  }
                  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
               }	
            ?>
          </select>
          </td>
          <td width="14%" align="right">Nº Guardias:</td>
          <td width="8%"><input type="text" name="nroguardias" id="nroguardias" style="width:50px;" 
                        value="<?php echo $datoCliente['nroguardias'];?>" /></td>
          <td width="9%" align="right">Recargo:</td>
          <td width="11%"><input type="text" name="recargo" id="recargo" style="width:50px;" 
                        value="<?php echo $datoCliente['recargo'];?>" />
            %</td>
          <td width="18%" align="right">Forma de Registro:</td>
          <td width="17%">
          <select name="registro" id="registro">
			<?php
               $selec = $datoCliente['registro']; 
               $tipo = array("Por Servicio","Agrupar");
               for ($i = 0; $i < count($tipo); $i++) {
                  $atributo = ""; 
                  if ($selec == $tipo[$i]) {
                      $atributo = "selected='selected'";	
                  }
                  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
               }	
            ?>
          </select>
          </td>
        </tr>
        <input type="hidden" name="datosContrato" id="datosContrato" />
        <?php
		   $atributo = "disabled='disabled'";
		   if ($datoCliente['modalidad'] == "Definido") {
			  $atributo = ""; 
		   }
		?>
        <tr>
          <td colspan="8">
          <div class="subIngreso2">
                <table width="100%" border="0">
                  <tr>
                    <td width="22%">Servicio</td>
                    <td width="4%">&nbsp;</td>
                    <td width="16%">Precio</td>
                    <td width="14%">Cantidad</td>                    
                    <td width="15%">F/Inicio</td>
                    <td width="14%">F/Final</td>
                    <td width="14%" rowspan="2">
                      
                      <input type="button" onclick="insertarNewItem('detalleContrato');"
                 id="agregar" name="agregar" value="Agregar" class="aceptar"
                 <?php echo $atributo;?> /></td>
                    </tr>
                  <tr>
                    <td align="center">
                      <input type="text" autocomplete="off" name="nombreservicio" id="nombreservicio" style="width:140px;"
                      <?php echo $atributo;?> onkeyup="tipoBusqueda(event)"/>
                     <div  id="resultados"  class="divresultado" style="width:200px;text-align:left">
                     </div>
                     <input type="hidden" name="idservicio" id="idservicio" />
                     </td>
                    <td align="center"><div id="autoL1" class="autoLoading"></div></td>
                    <td>
                    <select name="precioservicio" id="precioservicio" <?php echo $atributo;?>>
                    <option value="">--Seleccione--</option>
                      
                    </select></td>
                    <td><input type="text" name="cantidadservicio" id="cantidadservicio" style="width:80px;" 
                      value="" <?php echo $atributo;?>/></td>
                   
                    <td>
                      <input type="text" name="fechainicio" id="fechainicio" style="width:68px;" 
                      value="<?php echo date("d/m/Y");?>" <?php echo $atributo;?>/>
                    </td>
                    <td><input type="text" name="fechafinal" id="fechafinal" style="width:68px;" 
                      value="<?php echo date("d/m/Y");?>" <?php echo $atributo;?>/></td>
                    </tr>
                  </table>
            </div>          
          </td>
          </tr>
        <tr>
          <td colspan="8" align="center">
            <div style="position:relative;overflow:auto;height:220px;border:1px solid #E2E2E2;width:90%;margin:0 auto;">
              <table width="100%" border="0" id="tabla">
                <tr class="filadetalleui">
                  <td width="38" >&nbsp;</td>                            
                  <th width="280" align="center" class="letras">Nombre</th>
                  <th width="100" align="center" class="letras">Precio</th>
                  <th width="100" align="center" class="letras">Cantidad</th>
                  <th width="100" align="center" class="letras">F/Inicio</th>              
                  <th width="100" align="center" class="letras">F/Final</th>
                  <th width="80" style="display:none">Idservicio</th>
                  <th width="80" style="display:none">Idprecio</th>
                  </tr>
                <tbody id="detalleContrato">
                  <?php
					if (isset($datoCliente['idcliente'])) {
						$sql = "select left(s.nombre,35)as 'nombre',cc.precio,cc.cantidad,s.idservicio
						,cc.fechainicio,cc.fechafinal,s.precio1,s.precio2,s.precio3   
						from contratocliente cc,servicio s
						where s.idservicio=cc.idservicio 
						and cc.idcliente=$datoCliente[idcliente] order by idcontratocliente;";				
						$dato = $db->consulta($sql);
						$sql = "select * from configuracionprecios";
						$precios = $db->arrayConsulta($sql);
						while ($data = mysql_fetch_array($dato)) {
							$fechainicio = $db->GetFormatofecha($data['fechainicio'], "-");
							$fechafinal = $db->GetFormatofecha($data['fechafinal'], "-");
							$parametro = "texto".$data['precio'];
							echo "
							<tr>
							  <td align='center'>
							  <img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' /></td>              
							  <td align='left'>$data[nombre]</td>
							  <td>".number_format($data['precio'], 2)."</td>
							  <td>".number_format($data['cantidad'], 2)."</td>							  
							  <td align='center' >$fechainicio</td>
							  <td align='center'>$fechafinal</td>              
							  <td align='center' style='display:none'>$data[idservicio]</td>
							  <td align='center' style='display:none'>$data[precio]</td>			 
							</tr>
							";
						}
					}          
				  ?>
                  </tbody>                        
                </table> 
              
              </div>
            </td>
        </tr>
        </table>
  
     </div>
  </div> 
  </td>
</tr>
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