<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();
	include("conexion.php");
    $db = new MySQL();
	include("trabajador/Dtrabajador.php");
	$dbtrabajador = new Dtrabajador($db);
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Recursos'],'Trabajadores','nuevo_trabajador.php','listar_trabajador.php');
	if ($fileAcceso['Acceso'] == "No") {
	  header("Location: cerrar.php");	
	}

    $idtrabajador = 0;
	$transaccion = "insertar";
	
	if ($_POST['transaccion'] == "insertar") {
	    $dbtrabajador->insertarTrabajador();
		header("Location: nuevo_trabajador.php");	
	}
	
	if ($_POST['transaccion'] == "modificar") {
		$dbtrabajador->modificarTrabajador();
		header("Location: nuevo_trabajador.php");
	}	
	
	if (isset($_GET['sw'])) {
	    $transaccion = "modificar";	
	    $idtrabajador = $_GET['idtrabajador'];
	    $sql = "SELECT * FROM trabajador WHERE idtrabajador= ".$_GET['idtrabajador'];
	    $datoTrabajador = $db->arrayConsulta($sql);  
	    $sql = "select * from cajero where idtrabajador=".$_GET['idtrabajador'];
	    $datoCajero = $db->arrayConsulta($sql);
	    $sql = "select * from vendedor where idtrabajador=".$_GET['idtrabajador'];
	    $datoVendedor = $db->arrayConsulta($sql);
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
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<link rel="stylesheet" href="trabajador/trabajador.css" type="text/css"/>
<script async="async" src="trabajador/NTrabajador.js"></script>
<script async="async" src="lib/Jtable.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script async="async" src="js/jquery.filestyle.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script> $(function() {	$( '#tabs' ).tabs();	});</script>
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


 <div id="overlay_vendido" class="overlays"></div> 
 <div id="overlay" class="overlays"></div> 
 <div id="cortinaInicio" class="overlaysInicio"></div>
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
 


<!-- Sub Ventana de Sucursales de Trabajo--> 
<div id="subventana" class="contenedorframeID"> 
  <div class="modal_interiorframeID"></div> 
  <div class="subventana">

        <div class="caption_modalCabecera">
           <div class="posicionCloseSub" onclick="ventanaCompartido('subventana','cerrar');">
           <img src="iconos/borrar2.gif" title="" width="12" height="12" style="cursor:pointer" 
           onclick="ventanaCompartido('subventana','cerrar');"></div>
           <div class="titleHeadCaption"> Seleccione las sucursales de trabajo</div>
        </div>

       <div class="subventana_scroll">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr class="filadetalleui">
            <th width="6%" style="border-right:1px solid #E2E2E2;">Nº</th>
            <th width="64%" style="border-right:1px solid #E2E2E2;">Sucursal</th>
            <td width="5%">&nbsp;</td>
            <td width="25%" style="display:none">id</td>
          </tr>
          <tbody id="detalleCompartir">
          <?php
			  $asignadas = array();
			  $sql = "select *from sucursaltrabajador where idtrabajador=$idtrabajador;";
			  $consulta = $db->consulta($sql);
			  while ($dato = mysql_fetch_array($consulta)) {
				  $asignadas[$dato['idsucursal']]= $dato['idsucursal'];	
			  }		
			
			  $sql = "select idsucursal,left(nombrecomercial,20)as 'nombrecomercial' from sucursal where estado=1";
			  $usuarios = $db->consulta($sql);
			  $i = 1;
			  while ($data = mysql_fetch_array($usuarios)) {
				  if (isset($asignadas[$data['idsucursal']])) {
					$selector = "checked='checked'";	
				  } else {
					$selector = "";	
				  }				
					
				  echo "<tr class='subventana_itemtabla'>
						  <td align='center'>$i</td>
						  <td>$data[nombrecomercial]</td>
						  <td><input type='checkbox' id='ts$i' name='ts$i' $selector/></td>
						  <td style='display:none'>$data[idsucursal]</td>
						</tr>";
				  $i++;
			  }			
		  ?>  
          
          </tbody>          
       </table>
        </div>
        
        <div class="modal_boton2">
           <input type="button" value="Todos" onclick="setCompartido(true);" class="botonNegro"/>
         </div>
        <div class="modal_boton3">
            <input type="button" value="Desmarcar" onclick="setCompartido(false);" class="botonNegro" />
        </div>
      
   </div>  
</div>




<!-- Sub Ventana de Ruta de Trabajo--> 
<div id="subventanaRuta" class="contenedorframeID"> 
  <div class="modal_interiorframeID"></div> 
  <div class="subventana">

        <div class="caption_modalCabecera">
           <div class="posicionCloseSub" onclick="ventanaCompartido('subventanaRuta','cerrar');">
           <img src="iconos/borrar2.gif" title="" width="12" height="12" style="cursor:pointer" 
           onclick="ventanaCompartido('subventanaRuta','cerrar');"></div>
           <div class="titleHeadCaption"> Seleccione las rutas de Ventas</div>
        </div>

       <div class="subventana_scroll">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr class="filadetalleui">
            <th width="6%" style="border-right:1px solid #E2E2E2;">Nº</th>
            <th width="64%" style="border-right:1px solid #E2E2E2;">Ruta</th>
            <td width="5%">&nbsp;</td>
            <td width="25%" style="display:none">id</td>
          </tr>
          <tbody id="detalleRutas">
          <?php
			  $rutasAsignadas = array();
			  $sql = "select * from rutatrabajo where idtrabajador=$idtrabajador and estado=1;";
			  $consulta = $db->consulta($sql);
			  while ($dato = mysql_fetch_array($consulta)) {
				  $rutasAsignadas[$dato['idruta']]= $dato['idruta'];	
			  }		
			
			  $sql = "select r.idruta,left(r.nombre,20)as 'nombre' from ruta r where r.estado=1 order by r.nombre";
			  $usuarios = $db->consulta($sql);
			  $i = 1;
			  while ($data = mysql_fetch_array($usuarios)) {
				  if (isset($rutasAsignadas[$data['idruta']])) {
					  $selector = "checked='checked'";	
				  } else {
					  $selector = "";	
				  }					
				  echo "<tr class='subventana_itemtabla'>
						  <td align='center'>$i</td>
						  <td>$data[nombre]</td>
						  <td><input type='checkbox' id='rt$i' name='rt$i' $selector/></td>
						  <td style='display:none'>$data[idruta]</td>
						</tr>";
				  $i++;
			  }			
		  ?>  
          
          </tbody>          
       </table>
        </div>
        
        <div class="modal_boton2">
           <input type="button" value="Todos" onclick="setRuta(true);" class="botonNegro"/>
         </div>
        <div class="modal_boton3">
            <input type="button" value="Desmarcar" onclick="setRuta(false);" class="botonNegro" />
        </div>
      
   </div>  
</div>





<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Recursos > Trabajadores </div>
<div class="menuFormulario"> 
 <?php
	 $estructura = $_SESSION['estructura'];
	 $menus = $estructura['Recursos'];
	 $privilegios = $db->getOpciones($menus, "Trabajadores"); 
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
 <div class="contenedorPrincipalTrabajador">
<form id='formValidado' name='formValidado' method='post' action='nuevo_trabajador.php'  enctype='multipart/form-data'>
<div class="contemHeaderTop">
     <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;<input name='enviar' type='button' class='botonNegro'
     id='enviar' value='Guardar [F2]' onclick="ejecutarTransaccion()"/>
        <?php 
			if ($fileAcceso['File'] == "Si") {
			   echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar"
				value="Cancelar [F4]" onClick="location.href=&#039listar_trabajador.php#t6&#039"/>';	
			}
		?>
 
 </td>
<td>
    <input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden" id="idtrabajador" name="idtrabajador" value="<?php echo $datoTrabajador['idtrabajador'];?>" />
    <input type="hidden" id="sucursalAsignada" name="sucursalAsignada" />
    <input type="hidden" id="rutasAsignada" name="rutasAsignada" />  
</td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
    <?php 
		  if (isset($_GET['idtrabajador'])) {
			  echo $_GET['idtrabajador'];
		  } else {
			  echo $db->getNextID('idtrabajador', 'trabajador');
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
  <tr><td colspan="6"></td></tr>
</table>
</div>
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr>
  <td width="62"  align='left' valign='top' style="font-size:10px">
  <table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
    <tr>
      <td colspan='5'></td>
      <td width='146' rowspan='4' align='left'>
      <?php
		  $src = "files/modelo_sombraSeycon.png"; 
		  if (isset($datoTrabajador['fotoprincipal']) && $datoTrabajador['fotoprincipal'] != "") {
			  $src = $datoTrabajador['fotoprincipal'];
		  }
	  ?>     
      <img src='<?php echo $src; ?>' alt='camara' name="idcamara" width='120' height='120' id="idcamara" 
      style='cursor:pointer;' onclick="$('#foto').click();" /></td>
    </tr>
    <tr>
      <td width='89' height="25" align='right' valign='top'>Nombres<span class='rojo'>*</span>:</td>
      <td width='225' valign='top'>
      <input type='text' id="nombre" name="nombre"  class="required" size="32"
       value="<?php echo $datoTrabajador['nombre'];?>"/></td>
      <td colspan="2"  align='right' valign='top'>Apellidos<span class='rojo'>*</span>:</td>
      <td width='269' valign='top'><input type='text' id="apellido" name="apellido"  class="required" size="32"
       value="<?php echo $datoTrabajador['apellido'];?>"/></td>
    </tr>
    <tr>
      <td height="25" align='right' valign='top'>Foto 1:</td>
      <td valign='top'><input type='file'  id="foto1" name="foto1" class="" size="20" />
        <br /><span class="dimension">(180 Ancho X 180 Alto)</span></td>
      <td colspan="2"  align='right' valign='top'>Foto 2:</td>
      <td valign='top'><input type='file'  id="foto2" name="foto2" class="" size="20" />
        <br /><span class="dimension">(180 Ancho X 180 Alto)</span></td>
    </tr>
    <tr>
      <td width='89' align='right' valign='top'><span style="font-size:12px">Cajero</span></td>
      <td width='225' valign='top'><input type="checkbox" value="" name="cajero_check" id="cajero_check"
       onclick="active_v(this,'cajeroMenu','tabs-9','cajero')" />&nbsp;&nbsp;       
       Vendedor
       <input type="checkbox" value="" name="vende_active_check" id="vende_active_check"
        onclick="active_v(this,'vendedorMenu','tabs-10','vendedor')" />
       </td>
      <td colspan="2"  align='right' valign='top'></td>
      <td width='269' valign='top'></td>
    </tr>
    <tr>
      <td colspan='6' >
      <div id='tabs'>
        <ul style='height:40px;'>
          <li id="tr" ><a id="t" href='#tabs-1'>Trabajador</a></li>
          <li><a href='#tabs-2'>Educación</a></li>
          <li><a href='#tabs-3'>Hábitos</a></li>
          <li><a href='#tabs-4'>Datos Matrimoniales</a></li>
          <li><a href='#tabs-5'>Datos Familiares</a></li>
          <li><a href='#tabs-6'>Garante Personal</a></li>
          <li><a href='#tabs-7'>Contrato Laboral</a></li>
          <li><a href='#tabs-8'>Horario</a></li>
          <li id="cajeroMenu" style="display:none"><a href='#tabs-9'>Cajero</a></li>
          <li id="vendedorMenu" style="display:none"><a href='#tabs-10'>Vendedor</a></li>
        </ul> 
                
        <!--  Datos del Trabajador  -->        
        <div id='tabs-1'>
              
          <table width="100%" border="0">
            <tr>
              <td colspan="2">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                1. Datos Personales</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="15%"><div align="right">Fecha Nacimiento<span class="rojo">*</span>:</div></td>
              <td width="22%"><input type='text' id="fechanacimiento" name="fechanacimiento" class="date" size="12" 
              value="<?php 
			  if (isset($datoTrabajador['idtrabajador'])) {
				  echo $db->GetFormatofecha($datoTrabajador['fechanacimiento'], "-");
			  } else {
			      echo date("d/m/Y");
			  }
			  ?>" 
              onchange="getfecha(this.value)"/></td>
              <td width="12%" align="right">Nacionalidad:</td>
              <td width="20%" align="left"><input type='text' id="nacionalidad" name="nacionalidad" class="" size="20"
               value="<?php echo $datoTrabajador['nacionalidad'];?>" /></td>
              <td width="13%" align="right">Ciudad de Origen<span class="rojo">*</span>:</td>
              <td width="17%"><input type='text' id="ciudad" name="ciudad" class="" size="20"
               value="<?php echo $datoTrabajador['ciudad'];?>"/></td>
              <td width="1%">&nbsp;</td>
            </tr>
            <tr>
              <td ><div align="right">C.I.<span class='rojo'>*</span>:</div></td>
              <td><input type='text' id="carnetidentidad" name="carnetidentidad"  class="required"
               size="15" value="<?php echo $datoTrabajador['carnetidentidad'];?>"/>Origen:
                <select name="origenci" id="origenci">
                <?php
				   $selec = $datoTrabajador['origenci']; 
				   $tipo = array("SCZ","CHQ","CBA","LPZ", "ORU", "PND", "PSI", "BNI", "TJA");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
				   }	
	            ?>
                </select></td>
              <td align="right">Domicilio<span class="rojo">*</span>:</td>
              <td align="left"><input type='text' id="direccion" name="direccion" class="required"
               size="25" value="<?php echo $datoTrabajador['direccion'];?>"/></td>
              <td align="right">Email Personal:</td>
              <td><input type='text' id="email" name="email" 
               size="20" value="<?php echo $datoTrabajador['emailpersonal'];?>"/></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Sexo:</div></td>
              <td><select name="sexo" id="sexo">
                <?php
					 $selec =  $datoTrabajador['sexo']; 
					 $tipo = array("M","F");
					 $texto = array("Masculino","Femenino");
					 for ($i = 0; $i < count($tipo); $i++) {
						$atributo = ""; 
						if ($selec == $tipo[$i]) {
						    $atributo = "selected='selected'";	
						}
						echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
					 }	
	              ?>
              </select></td>
              <td align="right">&nbsp;&nbsp;Celular: </td>
              <td align="left"><input type='text' id="celular" name="celular"
               class="number" size="10" value="<?php echo $datoTrabajador['celular'];?>"/></td>
              <td align="right">Teléfono:</td>
              <td><input type='text' id="telefono" name="telefono"
               class="required" size="15" value="<?php echo $datoTrabajador['telefono'];?>"/></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Libreta de S.M. Nº:</td>
              <td><input type='text' id="libreta" name="libreta"
               size="20" value="<?php echo $datoTrabajador['numerolibreta'];?>"/></td>
              <td align="right">&nbsp;Unidad:</td>
              <td align="left"><input type='text' id="unidad" name="unidad" class="number" 
               size="20" value="<?php echo $datoTrabajador['unidad'];?>"/></td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              2. Licencia de conducir</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <?php
			      $atributo1 = "";
				  $atributo2 = "disabled='disabled'";
				  $value = 0;
			      if (isset($datoTrabajador['idtrabajador'])) {
					if ($datoTrabajador['licenciamoto'] == "1") {
						$atributo1 = "checked='checked'";
						$atributo2 = "";
						$value = 1;
					}					  
				  }
			   ?>             
              <td align="right">Moto:</td>
              <td><input type="checkbox"  name="moto" id="moto" 
              onclick="bloquearMoto(this.checked,this.id)" value="<?php echo $value;?>" 
			  <?php echo $atributo1;?>/></td>
              <td align="right">F/Vencimiento:</td>
              <td align="left"><input type='text' id="fechamoto" name="fechamoto"  
               size="12" value="<?php 
			   if ($datoTrabajador['licenciamoto'] == "1") {
				   echo $db->GetFormatofecha($datoTrabajador['fechamoto'], "-");
			   } else {
				   echo date("d/m/Y");
			   }?>" 
               <?php echo $atributo2;?>/></td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <?php
			      $atributo1 = "";
				  $atributo2 = "disabled='disabled'";
				  $value = 0;
			      if (isset($datoTrabajador['idtrabajador'])) {
					if ($datoTrabajador['licenciaauto'] == "1") {
						$atributo1 = "checked='checked'";
						$atributo2 = "";
						$value = 1;
					}					  
				  }
			   ?>  
              <td><div align="right">Auto:</div></td>
              <td><input type="checkbox"  name="auto" id="auto"
               onclick="bloquearAuto(this.checked,this.id)" <?php echo $atributo1;?> 
               value="<?php echo $value;?>"/></td>
              <td align="right">F/Vencimiento:</td>
              <td align="left"><input type='text' id="fechaauto" name="fechaauto" 
               size="12" value="<?php 
			   if ($datoTrabajador['licenciaauto'] == "1") {
				   echo $db->GetFormatofecha($datoTrabajador['fechaauto'], "-");
			   } else {
			       echo date("d/m/Y");
			   }?>" <?php echo $atributo2;?>/></td>
              <td align="right">Categoría:</td>
              <td>
               <select id="categoriaauto" name="categoriaauto" <?php echo $atributo2;?>>
               <?php
			       $selec =  $datoTrabajador['categoriaauto']; 
				   $tipo = array("P", "A", "B", "C", "D");
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
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;3. Donde vive, en que estado esta:</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td colspan="4"><table width="100%" border="0">
                <tr>
                  <td width="16%" align="right">Alquiler</td>
                  <td width="8%">
                  <input type="radio" name="vivencia" id="vivencia" value="Alquiler" 
                  onclick="bloquearVivencia(this.value)" checked="checked"/>
                  </td>
                  <td width="24%" align="right">Anticretico</td>
                  <td width="7%">
                  <input type="radio" name="vivencia" id="vivencia" value="Anticretico" onclick="bloquearVivencia(this.value)"/>
                  </td>
                  <td width="15%" align="right">Propio</td>
                  <td width="7%">
                  <input type="radio" name="vivencia" id="vivencia" value="Propio" onclick="bloquearVivencia(this.value)"/>
                  </td>
                  <td width="23%" >&nbsp;</td>
                  </tr>
                <tr>
                  <td align="right" height="20">Padres</td>
                  <td>
                  <input type="radio" name="vivencia" id="vivencia" value="Padres" onclick="bloquearVivencia(this.value)"/>
                  </td>
                  <td align="right">Con Amigo</td>
                  <td>
                  <input type="radio" name="vivencia" id="vivencia" value="Amigos" onclick="bloquearVivencia(this.value)"/>
                  </td>
                  <td align="right">Otros</td>
                  <td>
                  <input type="radio" name="vivencia" id="vivencia" value="Otros" onclick="bloquearVivencia(this.value)"/>
                  </td>
                  <td>
                  <?php
				    $estilo = "style='display:none'";
				    if (isset($datoTrabajador['vivienda']) && $datoTrabajador['vivienda'] == "Otros") {
						$estilo = "style='display:block'";
					}
				  ?>
                  
                  <input type="text" name="descripcionvivienda" id="descripcionvivienda"
                   value="<?php echo $datoTrabajador['descripcionvivienda'];?>" <?php echo $estilo;?>>
                  </td>
                  </tr>
                </table></td>
              <td align="left">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td valign="top">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              4. Observaciones</td>
              <td colspan="4" align="center">
              <textarea id="observaciones" name="observaciones" 
              style="width:80%"><?php echo $datoTrabajador['observacion'];?></textarea>                
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            </table>
        </div>
        
        <!--  Datos de Educacion  --> 
        <div id='tabs-2'>
         <table width="100%" border="0">
          <tr>
            <td width="11%">&nbsp;</td>
            <td width="3%">&nbsp;</td>
            <td width="16%" class="subtituloTrabajador">1. Nivel</td>
            <td width="20%" align="center" class="subtituloTrabajador">Lugar</td>
            <td width="9%" align="center" class="subtituloTrabajador">Año</td>
            <td width="34%" class="subtituloTrabajador">Nivel de Aprobación</td>
            <td width="3%">&nbsp;</td>
            <td width="4%">&nbsp;</td>
          </tr>
          <tr>
          <?php
		      if (isset($datoTrabajador['idtrabajador'])) {
		          $sql = "select * from niveleducacion where idtrabajador=$datoTrabajador[idtrabajador]";
			      $datoEducacion = $db->arrayConsulta($sql);
			  }
		  ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Primaria</td>
            <td align="center">
            <input type="text" name="lugarprimaria" id="lugarprimaria"
             value="<?php echo $datoEducacion['lugarprimaria'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="anioprimaria" id="anioprimaria" style="width:60px;">            
              <?php
			      $selec = $datoEducacion['anioprimaria'];
			      for ($i = 1900; $i <= 2025; $i++) {
					  $atributo = ""; 
					  if ($selec == $i) {
						  $atributo = "selected='selected'";	
					  }
					  echo "<option value='$i' $atributo>$i</option>";
				  }
			  ?>
            </select>
            </td>
            <td><input type="text" name="aprovacionprimaria" id="aprovacionprimaria" 
             value="<?php echo $datoEducacion['nivelprimaria'];?>" style="width:250px;" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Secundaria</td>
            <td align="center">
            <input type="text" name="lugarsecundaria" id="lugarsecundaria"
             value="<?php echo $datoEducacion['lugarsecundaria'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="aniosecundaria" id="aniosecundaria" style="width:60px;">            
              <?php
			      $selec = $datoEducacion['aniosecundaria'];
			      for ($i = 1900; $i <= 2025; $i++) {
					  $atributo = ""; 
					  if ($selec == $i) {
						  $atributo = "selected='selected'";	
					  }
					  echo "<option value='$i' $atributo>$i</option>";
				  }
			  ?>
            </select>
            </td>
            <td><input type="text" name="aprovacionsecundaria" id="aprovacionsecundaria"
             value="<?php echo $datoEducacion['nivelsecundaria'];?>" style="width:250px;" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Universitaria</td>
            <td align="center">
            <input type="text" name="lugaruniversitaria" id="lugaruniversitaria" 
             value="<?php echo $datoEducacion['lugaruniversitaria'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="aniouniversitaria" id="aniouniversitaria" style="width:60px;">            
              <?php
			      $selec = $datoEducacion['aniouniversitaria'];
			      for ($i = 1900; $i <= 2025; $i++) {
					  $atributo = ""; 
					  if ($selec == $i) {
						  $atributo = "selected='selected'";	
					  }
					  echo "<option value='$i' $atributo>$i</option>";
				  }
			  ?>
            </select>            
            </td>
            <td><input type="text" name="aprovacionuniversitaria" id="aprovacionuniversitaria" 
             value="<?php echo $datoEducacion['niveluniversitaria'];?>" style="width:250px;" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><span class="subtituloTrabajador">2. Idiomas</span></td>
            <td class="subtituloTrabajador" align="center">Descripción</td>
            <td class="subtituloTrabajador" align="center">Dominio</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
          <?php
		      if (isset($datoTrabajador['idtrabajador'])) {
		          $sql = "select * from idiomas where idtrabajador=$datoTrabajador[idtrabajador]";
			      $datoIdioma = $db->arrayConsulta($sql);
			  }
		  ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="text" name="descripcionidioma1" id="descripcionidioma1"
             style="width:170px;" value="<?php echo $datoIdioma['idioma1'];?>"/></td>
            <td align="center">
            <select name="nivelidioma1" id="nivelidioma1" style="width:108px;">            
              <?php
			       $selec = $datoIdioma['nivel1']; 
					 $tipo = array("Perfectamente", "Bien", "Regular");
					 $texto = array("Perfectamente","Bien", "Regular");
					 for ($i=0; $i<count($tipo); $i++) {
						$atributo = ""; 
						if ($selec == $tipo[$i]) {
						    $atributo = "selected='selected'";	
						}
						echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
					 }	
			  ?>
            </select>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="text" name="descripcionidioma2" id="descripcionidioma2"
             value="<?php echo $datoIdioma['idioma2'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="nivelidioma2" id="nivelidioma2" style="width:108px;">            
              <?php
			       $selec =  $datoIdioma['nivel2']; 
					 $tipo = array("Perfectamente", "Bien", "Regular");
					 $texto = array("Perfectamente","Bien", "Regular");
					 for ($i=0; $i<count($tipo); $i++) {
						$atributo = ""; 
						if ($selec == $tipo[$i]) {
						    $atributo = "selected='selected'";	
						}
						echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
					 }	
			  ?>
            </select>
            
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="text" name="descripcionidioma3" id="descripcionidioma3"
             value="<?php echo $datoIdioma['idioma3'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="nivelidioma3" id="nivelidioma3" style="width:108px;">            
              <?php
			         $selec = $datoIdioma['nivel3']; 
					 $tipo = array("Perfectamente", "Bien", "Regular");
					 $texto = array("Perfectamente","Bien", "Regular");
					 for ($i=0; $i<count($tipo); $i++) {
						$atributo = ""; 
						if ($selec == $tipo[$i]){
						    $atributo = "selected='selected'";	
						}
						echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
					 }	
			  ?>
            </select>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ><span class="subtituloTrabajador">3. Habilidades</span></td>
            <td align="center"><span class="subtituloTrabajador">Descripción</span></td>
            <td align="center">Dominio</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
          <?php
		      if (isset($datoTrabajador['idtrabajador'])) {
		          $sql = "select * from habilidad where idtrabajador=$datoTrabajador[idtrabajador]";
			      $datoHabilidad = $db->arrayConsulta($sql);
			  }
		  ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="text" name="descripcionhabilidad1" id="descripcionhabilidad1" 
             value="<?php echo $datoHabilidad['habilidad1'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="nivelhabilidad1" id="nivelhabilidad1" style="width:108px;">            
              <?php
			       $selec = $datoHabilidad['nivel1']; 
					 $tipo = array("Perfectamente", "Bien", "Regular");
					 $texto = array("Perfectamente","Bien", "Regular");
					 for ($i=0; $i<count($tipo); $i++) {
						$atributo = ""; 
						if ($selec == $tipo[$i]){
						    $atributo = "selected='selected'";	
						}
						echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
					 }	
			  ?>
            </select>
            
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="text" name="descripcionhabilidad2" id="descripcionhabilidad2" 
             value="<?php echo $datoHabilidad['habilidad2'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="nivelhabilidad2" id="nivelhabilidad2" style="width:108px;">            
              <?php
			       $selec = $datoHabilidad['nivel2']; 
					 $tipo = array("Perfectamente", "Bien", "Regular");
					 $texto = array("Perfectamente","Bien", "Regular");
					 for ($i=0; $i<count($tipo); $i++) {
						$atributo = ""; 
						if ($selec == $tipo[$i]) {
						    $atributo = "selected='selected'";	
						}
						echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
					 }	
			  ?>
            </select>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="text" name="descripcionhabilidad3" id="descripcionhabilidad3" 
             value="<?php echo $datoHabilidad['habilidad3'];?>" style="width:170px;"/></td>
            <td align="center">
            <select name="nivelhabilidad3" id="nivelhabilidad3" style="width:108px;">            
              <?php
			       $selec = $datoHabilidad['nivel3']; 
					 $tipo = array("Perfectamente", "Bien", "Regular");
					 $texto = array("Perfectamente","Bien", "Regular");
					 for ($i=0; $i<count($tipo); $i++) {
						$atributo = ""; 
						if ($selec == $tipo[$i]){
						    $atributo = "selected='selected'";	
						}
						echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
					 }	
			  ?>
            </select>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>

        </div>
        
        <!-- Datos de Habitos --> 
        <div id='tabs-3'>
         <table width="100%" border="0">
          <tr>
            <td width="9%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="31%">&nbsp;</td>
            <td width="5%">&nbsp;</td>
            <td width="5%">&nbsp;</td>
            <td width="5%">&nbsp;</td>
            <td width="3%">&nbsp;</td>
            <td width="35%">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="subtituloTrabajador">1. ¿Consume algún tipo de droga?</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
          <?php
		      if (isset($datoTrabajador['idtrabajador'])) {
		          $sql = "select * from habitos where idtrabajador=$datoTrabajador[idtrabajador]";
			      $datoHabitos = $db->arrayConsulta($sql);
			  }
		  ?>          
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left" height="21">a) Alcohol</td>
            <td align="right">SI</td>
            <td align="left"><input type="radio" name="salcohol" id="salcohol" value="1" 
            onclick="bloquearCampo(this.value,'msalcohol','cualalcohol')"
            <?php if ($datoHabitos['alcohol'] == 1) echo "checked='checked'";?>/></td>
            <td align="right">NO</td>
            <td align="left"><input name="salcohol" type="radio" id="salcohol" value="0"  
            onclick="bloquearCampo(this.value,'msalcohol','cualalcohol')" 
            <?php if (!isset($datoHabitos['alcohol']) || $datoHabitos['alcohol'] == 0) { 
			  echo "checked='checked'";
			}?>/></td>
            <td>
            <?php if ($datoHabitos['alcohol'] == 1) $estilo = "style='display:block'"; 
			else $estilo="style='display:none'";?>
            <div id="msalcohol" <?php echo $estilo;?> >Frecuencia:
                <input name="cualalcohol" type="text" id="cualalcohol" style="width:130px;" 
                value="<?php echo $datoHabitos['descripcionalcohol'];?>"/>
            </div>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left" height="21">b) Fuma</td>
            <td align="right">SI</td>
            <td align="left"><input type="radio" name="sfuma" id="sfuma" value="1" 
            onclick="bloquearCampo(this.value,'msfuma','cualfuma')" 
            <?php if ($datoHabitos['fumar'] == 1) echo "checked='checked'";?>/></td>
            <td align="right">NO</td>
            <td ><input name="sfuma" type="radio" id="sfuma" value="0" 
            onclick="bloquearCampo(this.value,'msfuma','cualfuma')" 
            <?php if (!isset($datoHabitos['fumar']) || $datoHabitos['fumar'] == 0) { 
			  echo "checked='checked'";
			}?>/></td>
            <td>
            <?php if ($datoHabitos['fumar'] == 1) $estilo = "style='display:block'"; 
			else $estilo="style='display:none'";?>
            <div id="msfuma" <?php echo $estilo;?>>Frecuencia:
                <input name="cualfuma" type="text" id="cualfuma" style="width:130px;" 
                value="<?php echo $datoHabitos['descripcionfuma'];?>"/>
            </div>
            </td>
          </tr>
          <tr>
            <td height="21">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>c) Medicamento</td>
            <td align="right">SI</td>
            <td>
            <input type="radio" name="smedicamento" id="smedicamento" value="1" 
             <?php if ($datoHabitos['medicamento'] == 1) echo "checked='checked'";?> 
             onclick="bloquearMedicamento(this.value)"/>
            </td>
            <td align="right">NO</td>
            <td>
            <input name="smedicamento" type="radio" id="smedicamento"
             onclick="bloquearMedicamento(this.value)" value="0" 
             <?php if (!isset($datoHabitos['medicamento']) || $datoHabitos['medicamento'] == 0) { 
			  echo "checked='checked'";
			}?> />
            </td>
            <td>
            <?php if ($datoHabitos['medicamento'] == 1) $estilo = "style='display:block'"; 
			else $estilo="style='display:none'";?>
            <div id="msgmedicamento" <?php echo $estilo;?>>Cual:
                <input name="cualmedicamento" type="text" id="cualmedicamento" style="width:130px;" 
                value="<?php echo $datoHabitos['descripcionmedicamentos'];?>"/>
            </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td height="21">d) Droga</td>
            <td class="subtituloTrabajador" align="right">SI</td>
            <td class="subtituloTrabajador" align="left">
            <input type="radio" name="sdroga" id="sdroga" value="1" 
            <?php if ($datoHabitos['drogas'] == 1) echo "checked='checked'";?> onclick="bloquearFarmaco(this.value)"/>
            </td>
            <td align="right">NO</td>
            <td><input name="sdroga" type="radio" id="sdroga"
             onclick="bloquearFarmaco(this.value)" value="0" 
             <?php if (!isset($datoHabitos['drogas']) || $datoHabitos['drogas'] == 0) { 
			  echo "checked='checked'";
			}?>/></td>
            <td>
            <?php if ($datoHabitos['drogas'] == 1) $estilo = "style='display:block'"; 
			else $estilo="style='display:none'";?>
            <div id="msgdroga" <?php echo $estilo;?>>Cual:
              <input name="cualdroga" type="text" id="cualdroga" style="width:130px;" 
              value="<?php echo $datoHabitos['descripciondroga'];?>"/>
            </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>e) Mariguana</td>
            <td align="right">SI</td>
            <td><input type="radio" name="smariguana" id="smariguana" value="1" 
            <?php if ($datoHabitos['mariguana'] == 1) echo "checked='checked'";?>/></td>
            <td align="right">NO</td>
            <td><input name="smariguana" type="radio" id="smariguana" value="0" 
            <?php if (!isset($datoHabitos['mariguana']) || $datoHabitos['mariguana'] == 0) { 
			  echo "checked='checked'";
			}?> /></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4">2. Estado de salud</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>a) Se encuentra actualmente sana</td>
            <td align="right">SI</td>
            <td><input type="radio" name="salud1" id="salud1" value="1" 
            <?php if ($datoHabitos['salud'] == 1) echo "checked='checked'";?>/></td>
            <td align="right">NO</td>
            <td><input name="salud1" type="radio" id="salcohol3" value="0" 
             <?php if (!isset($datoHabitos['salud']) || $datoHabitos['salud'] == 0) { 
			  echo "checked='checked'";
			}?> /></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td height="21">b)Tiene alguna enfermedad diagnosticada</td>
            <td align="right">SI</td>
            <td>
            <input type="radio" name="salud2" id="salud2" value="1"
             onclick="bloquearCampo(this.value,'msgenfermedad')" 
             <?php if ($datoHabitos['enfermedad'] == 1) echo "checked='checked'";?>/>
            </td>
            <td align="right">NO</td>
            <td>
            <input name="salud2" type="radio" id="salud2" value="0" 
             onclick="bloquearCampo(this.value,'msgenfermedad','cualenfermedad')" 
            <?php if (!isset($datoHabitos['enfermedad']) || $datoHabitos['enfermedad'] == 0) { 
			  echo "checked='checked'";
			}?>/>
            </td>
            <td>
            <?php if ($datoHabitos['enfermedad'] == 1) $estilo = "style='display:block'"; 
			else $estilo="style='display:none'";?>
            <div id="msgenfermedad" <?php echo $estilo;?>>Cual:
              <input name="cualenfermedad" type="text" id="cualenfermedad" style="width:130px;" 
              value="<?php echo $datoHabitos['descripcionenfermedad'];?>"/>
            </div>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>c)Tiene SIDA o es portador de VIH</td>
            <td align="right">SI</td>
            <td><input type="radio" name="salud3" id="salud3" value="1" 
            <?php if ($datoHabitos['sida'] == 1) echo "checked='checked'";?>/></td>
            <td align="right">NO</td>
            <td><input name="salud3" type="radio" id="salud3" value="0" 
            <?php if (!isset($datoHabitos['sida']) || $datoHabitos['sida'] == 0) { 
			  echo "checked='checked'";
			}?> /></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td >d)Ha Sufrido algún accidente o tiene alguna alteración física</td>
            <td align="right" valign="top">SI</td>
            <td valign="top">
            <input type="radio" name="salud4" id="salud4" value="1" 
            onclick="bloquearCampo(this.value,'msgaccidente')" 
            <?php if ($datoHabitos['accidente'] == 1) echo "checked='checked'";?>/></td>
            <td align="right" valign="top">NO</td>
            <td valign="top">
            <input name="salud4" type="radio" id="salud4"
             value="0" onclick="bloquearCampo(this.value,'msgaccidente','cualaccidente')" 
             <?php if (!isset($datoHabitos['accidente']) || $datoHabitos['accidente'] == 0) { 
			  echo "checked='checked'";
			}?>/></td>
            <td>
            <?php if ($datoHabitos['accidente'] == 1) $estilo = "style='display:block'"; 
			else $estilo="style='display:none'";?>
            <div id="msgaccidente" <?php echo $estilo;?>>Cual:
              <input name="cualaccidente" type="text" id="cualaccidente" style="width:130px;" 
              value="<?php echo $datoHabitos['descripcionaccidente'];?>"/>
            </div>
            </td>
          </tr>
         </table>
        </div>        
        
        <!-- Datos Matrimonial -->   
        <div id='tabs-4'>
         <table width="100%" border="0">
          <tr>
            <td width="4%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="22%">&nbsp;</td>
            <td width="25%">&nbsp;</td>
            <td width="15%">&nbsp;</td>
            <td width="21%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left">1. Estado Civil</td>
            <td align="left">
             <select name="estadocivil" id="estadocivil" onchange="bloquearEstadoCivil(this.value)">
              <?php
				   $selec = $datoTrabajador['estadocivil']; 
				   $tipo = array("Soltero","Casado","Viudo","Divorciado");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
				   }	
	            ?>
              </select></td>
            <td align="right">En que situación esta:</td>
            <?php
			  $atributoestado = "disabled='disabled'";
			  if (isset($datoTrabajador['estadocivil'])) {				
				if ($datoTrabajador['estadocivil'] == "Casado") {
				    $atributoestado = "";	
				}
			  }
			  
			  if (isset($datoTrabajador['estadocivil']) && $datoTrabajador['estadocivil'] == "Casado") {				 
		          $sql = "select * from conyugue where idtrabajador=$datoTrabajador[idtrabajador]";
			      $datoConyugue = $db->arrayConsulta($sql);			 
			  }
			  
			?>
            
            <td><select name="situacioncivil" id="situacioncivil" <?php echo $atributoestado;?>>
              <?php
				   $selec = $datoConyugue['situacion']; 
				   $tipo = array("Casado", "Concubinato");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
				   }	
	            ?>
            </select></td>
            <td align="left">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">Nombre Conyugue<span class="rojo">*</span>:</td>
            <td align="left">
            <input name="nombreconyugue" type="text" <?php echo $atributoestado;?>
             id="nombreconyugue" style="width:170px;" 
             value="<?php echo $datoConyugue['nombre'];?>"/>
            </td>
            <td align="right"><span class="subtituloTrabajador">Celular:</span></td>
            <td >
              <input name="celularconyugue" type="text" <?php echo $atributoestado;?>
               id="celularconyugue" style="width:120px;" value="<?php echo $datoConyugue['celular'];?>"/>
            </td>
            <td >&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Lugar de Nacimiento:</td>
            <td align="left">
              <input name="nacimientoconyugue" type="text" <?php echo $atributoestado;?>
               id="nacimientoconyugue" style="width:170px;" value="<?php echo $datoConyugue['lugarnacimiento'];?>"/>
            </td>
            <td align="right">Fecha de Nacimiento:</td>
            <td align="left">
            <input type="text"  name="fechaconyugue" id="fechaconyugue" style="width:80px;" 
            value="<?php 
			if (isset($datoConyugue['fechanacimiento'])) {
				echo $db->GetFormatofecha($datoConyugue['fechanacimiento'], "-");
			} else {
			    echo date("d/m/Y");
			}?>" <?php echo $atributoestado;?>/>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Empresa de Trabajo:</td>
            <td align="left">
              <input name="empresatrabajo" type="text" <?php echo $atributoestado;?>
               id="empresatrabajo" style="width:170px;" value="<?php echo $datoConyugue['empresa'];?>"/>
              </td>
            <td align="right">Dirección:</td>
            <td align="left">
              <input type="text"  name="direccionconyugue" id="direccionconyugue" style="width:120px;" 
            <?php echo $atributoestado;?> value="<?php echo $datoConyugue['direccion'];?>"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="left">2. Tiene Hijos</td>
            <td>
             <select name="hijos" id="hijos" onchange="bloquearHijos(this.value)">
              <?php
				   $selec = $datoTrabajador['hijos']; 
				   $dato = array("1", "0");
				   $texto = array("Si", "No");
				   for ($i = 0; $i < count($dato); $i++) {
					  $atributo = ""; 
					  if ($selec == $dato[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$dato[$i]' $atributo>$texto[$i]</option>";
				   }	
	           ?>
              </select></td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="5">
            
            <div class="subIngreso">
            <?php
			  $atributo = "";
			  if (isset($datoTrabajador['hijos']) && $datoTrabajador['hijos'] == "0") {
				  $atributo = "disabled='disabled'";
			  }
			?>
            
            <table width="100%" border="0">
              <tr>
                <td width="15%">Tipo</td>
                <td width="19%">Nombre Completo</td>
                <td width="13%">Genero</td>
                <td width="16%">F/Nacimiento</td>
                <td width="16%">Edad</td>
                <td width="21%" rowspan="2"><input name="agregarhijo" type="button" class="botonNegro"
                 id="agregarhijo" onclick="insertarNewItem('detallehijos');" value="Agregar" 
                 <?php echo $atributo;?>/></td>
              </tr>
              <tr>
                <td>
                <select name="tipohijo" id="tipohijo" <?php echo $atributo;?>>
                  <?php                               
					 $tipo = array("Dependiente", "Hijo");
					 for ($i = 0; $i < count($tipo); $i++) {					  
						echo "<option value='$tipo[$i]'>$tipo[$i]</option>";
					 }	
				  ?>
                </select></td>
                <td><span class="subtituloTrabajador">
                  <input type="text" name="nombrehijo" id="nombrehijo" style="width:110px;" <?php echo $atributo;?>/>
                </span></td>
                <td><select name="generohijo" id="generohijo" <?php echo $atributo;?>>
                  <?php                               
					 $tipo = array("Masculino", "Femenino");
					 for ($i = 0; $i < count($tipo); $i++) {					  
						echo "<option value='$tipo[$i]'>$tipo[$i]</option>";
					 }	
				  ?>
                </select></td>
                <td><span class="subtituloTrabajador">
                  <input type="text" name="fechahijo" id="fechahijo" style="width:80px;" 
                  value="<?php echo date("d/m/Y");?>" onchange="setEdad(this.value);" <?php echo $atributo;?>/>
                </span></td>
                <td><span class="subtituloTrabajador">
                  <input type="text" name="edadhijo" id="edadhijo" 
                  style="width:80px;" disabled="disabled" value="0" />
                </span></td>
                </tr>
            </table>
            </div>
            
            
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="5">
            
          <input type="hidden" name="datosHijos" id="datosHijos" /> 
          <div style="position:relative;overflow:auto;height:130px;border:1px solid #E2E2E2;width:90%;margin:0 auto;">
          <table width="100%" border="0" id="tabla">
            <tr class="filadetalleui">
              <td width="38" >&nbsp;</td>
              <th width="100" >Tipo</th>
              <th width="300" align="center" class="letras">Nombre</th>
              <th width="110" align="center" class="letras">Genero</th>
              <th width="100" align="center" class="letras">F/Nacimiento</th>
              <th width="100" align="center" class="letras">Edad</th>
            </tr>
            <tbody id="detallehijos">
            <?php
		    if (isset($datoTrabajador['idtrabajador'])) {
				$sql = "select tipodependencia,nombre,genero,fechanacimiento,
				(YEAR(CURDATE())-YEAR(fechanacimiento))- (RIGHT(CURDATE(),5)<RIGHT(fechanacimiento,5))as 'edad' 
				 from hijos where idtrabajador=$datoTrabajador[idtrabajador] order by idhijos";
				$dato = $db->consulta($sql);
				while ($data = mysql_fetch_array($dato)){
					$fecha = $db->GetFormatofecha($data['fechanacimiento'], "-");
					echo "
					<tr>
					  <td align='center'>
					  <img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' /></td>              
					  <td align='center'>$data[tipodependencia]</td>
					  <td>$data[nombre]</td>
					  <td align='center' >$data[genero]</td>
					  <td align='center'>$fecha</td>              
					  <td align='center' >$data[edad]</td>			 
					</tr>
					";
				}
			}          
		    ?>           
            </tbody>                        
          </table> 
          </div>
            &nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>

        </div>   
    
    
       <!-- Datos de Padre -->   
        <div id='tabs-5'>
         <table width="100%" border="0">
          <tr>
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="24%">&nbsp;</td>
            <td width="23%">&nbsp;</td>
            <td width="24%">&nbsp;</td>
            <td width="24%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4">
              <div class="subIngreso2">
                <table width="100%" border="0">
                  <tr>
                    <td width="17%">Parentesco</td>
                    <td width="14%">Nombre Completo</td>
                    <td width="15%">F/Nacimiento</td>
                    <td width="17%">Dirección</td>
                    <td width="15%">Teléfono</td>
                    <td width="22%" rowspan="2">
                    
                    <input type="button" onclick="insertarNewItemFamilia('detalleFamilia');"
                 id="agregarS3" value="Agregar" class="botonNegro"  /></td>
                    </tr>
                  <tr>
                    <td align="center">
                      <select name="parentesco" id="parentesco">
                        <?php                               
                               $tipo = array("Padre", "Madre", "Hermano", "Hermana", "Primo", "Prima");
                               for ($i = 0; $i < count($tipo); $i++) {					  
                                  echo "<option value='$tipo[$i]'>$tipo[$i]</option>";
                               }	
                         ?>
                       </select>
                      </td>
                    <td>
                      <input type="text" name="nombrefamiliar" id="nombrefamiliar" style="width:100px;"/>
                    </td>
                    <td>
                      <input type="text" name="fechafamiliar" id="fechafamiliar" style="width:80px;" 
                      value="<?php echo date("d/m/Y");?>"/>
                    </td>
                    <td>
                      <input type="text" name="direccionfamiliar" id="direccionfamiliar" style="width:120px;"/>
                    </td>
                    <td>
                      <input type="text" name="telefonofamiliar" id="telefonofamiliar" style="width:80px;"/>
                    </td>
                    </tr>
                  </table>
                </div>
              
              </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" >
         <input type="hidden" name="datosFamilia" id="datosFamilia" />
         <div style="position:relative;overflow:auto;height:220px;border:1px solid #E2E2E2;width:90%;margin:0 auto;">
          <table width="100%" border="0" id="tabla">
            <tr class="filadetalleui">
              <td width="38" >&nbsp;</td>              
              <th width="80" >Parentesco</th>
              <th width="220" align="center" class="letras">Nombre</th>
              <th width="100" align="center" class="letras">F/Nacimiento</th>
              <th width="180" align="center" class="letras">Dirección</th>              
              <th width="100" align="center" class="letras">Teléfono</th>
            </tr>
            <tbody id="detalleFamilia">
            <?php
		    if (isset($datoTrabajador['idtrabajador'])) {
				$sql = "select * from familiares where idtrabajador=$datoTrabajador[idtrabajador] 
				order by idfamiliares";
				$dato = $db->consulta($sql);
				while ($data = mysql_fetch_array($dato)){
					$fecha = $db->GetFormatofecha($data['fechanacimiento'], "-");
					echo "
					<tr>
					  <td align='center'>
					  <img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' /></td>              
					  <td align='center'>$data[parentesco]</td>
					  <td>$data[nombre]</td>
					  <td align='center' >$fecha</td>
					  <td >$data[direccion]</td>              
					  <td align='center' >$data[telefono]</td>			 
					</tr>
					";
				}
			}          
		    ?>
            </tbody>                        
          </table> 
         </div>       
            
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </div>   
        
        
        
        <!-- Datos del Garante Personal -->   
        <div id='tabs-6'>
         <table width="100%" border="0">
          <tr>
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="16%">&nbsp;</td>
            <td width="23%">&nbsp;</td>
            <td width="17%">&nbsp;</td>
            <td width="34%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
          </tr>
          <tr>
            <?php
		      if (isset($datoTrabajador['idtrabajador'])) {
		          $sql = "select * from garante where idtrabajador=$datoTrabajador[idtrabajador]";
			      $datoGarante = $db->arrayConsulta($sql);
			  }
		    ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">1. Datos del Garante</td>
            <td align="left">&nbsp;</td>
            <td>&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Nombre<span class="rojo">*</span>:</td>
            <td align="left"><input type="text" name="nombregarante" id="nombregarante" 
             value="<?php echo $datoGarante['nombre'];?>" style="width:170px;"/></td>
            <td align="right" >Estado Civil:</td>
            <td ><select name="estadocivilgarante" id="estadocivilgarante">
              <?php
				   $selec = $datoGarante['estadocivil']; 
				   $tipo = array("Soltero","Casado","Viudo","Divorciado");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
				   }	
	            ?>
            </select></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">Apellido<span class="rojo">*</span>:</td>
            <td>
              <input type="text" name="apellidogarante" id="apellidogarante"  
              value="<?php echo $datoGarante['apellido'];?>" style="width:170px;"/>
            </td>
            <td align="right">Nacionalidad:</td>
            <td><input type="text" name="nacionalidadgarante" id="nacionalidadgarante" 
             value="<?php echo $datoGarante['nacionalidad'];?>" style="width:170px;"/></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td  class="subtituloTrabajador" >&nbsp;</td>
            <td class="subtituloTrabajador" align="right">Dirección<span class="rojo">*</span>:</td>
            <td class="subtituloTrabajador" align="left">
            <input type="text" name="direcciongarante" id="direcciongarante" 
            value="<?php echo $datoGarante['direccion'];?>" style="width:170px;"/></td>
            <td align="right" >Profesión:</td>
            <td><input type="text" name="profesiongarante" id="profesiongarante" 
            value="<?php echo $datoGarante['profesion'];?>" style="width:170px;"/></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right"><span class="subtituloTrabajador">Teléfono:</span></td>
            <td>
              <input type="text" name="telefonogarante" id="telefonogarante" 
               value="<?php echo $datoGarante['telefono'];?>" style="width:170px;"/>
            </td>
            <td align="right">Ingreso Mensual<span class="rojo">*</span>:</td>
            <td><input type="text" name="ingresogarante" id="ingresogarante" 
            value="<?php echo $datoGarante['ingresomensual'];?>" style="width:170px;"/></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">Parentesco:</td>
            <td><input type="text" name="parentescogarante" id="parentescogarante" 
            value="<?php echo $datoGarante['parentesco'];?>" style="width:170px;"/></td>
            <td align="right">Nombre Conyugue:</td>
            <td><input type="text" name="conyuguegarante" id="conyuguegarante" 
            value="<?php echo $datoGarante['nombreconyugue'];?>" style="width:170px;"/></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Tiempo:</td>
            <td align="left"><input type="text" name="tiempogarante" id="tiempogarante" 
            value="<?php echo $datoGarante['tiempo'];?>" style="width:170px;"/></td>
            <td align="right">Ingreso Conyugue:</td>
            <td><input type="text" name="ingresoconyugue" id="ingresoconyugue" 
            value="<?php echo $datoGarante['ingresoconyugue'];?>" style="width:170px;"/></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Tenencia de vivienda: </td>
            <td align="left">            
            <select id="viviendagarante" name="viviendagarante" class="required">
              <?php
				   $selec = $datoGarante['vivienda']; 
				   $tipo = array("Propia","Alquilada","Anticretico","Familiares");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
				   }	
	          ?>
              
            </select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </div>   
        
        
        
        <!--  Datos de Contrato Laboral  --> 
        <div id='tabs-7'>
          <table width="100%" border="0">
            <tr>
              <td align="right">Cargo<span class='rojo'>*</span>:</td>
              <td><select id="idcargo" name="idcargo" style="width:150px;">
                <option value=""> -- Seleccione -- </option>
                <?php
					$sql = "select idcargo, left(cargo,30) from cargo where estado=1";
					$db->imprimirCombo($sql,$datoTrabajador['idcargo']);
				?>
              </select></td>
              <td></td>
              <td align="right">
              <?php
               $titulo = "Sueldo Básico:";
			   $Matributo = "";
			   if ($datoTrabajador['modalidadcontrato'] == "Consultor") {
				  $titulo = "Honorario:";
				  $Matributo = "disabled='disabled'";
			   }			  
			  ?>
              <div align="right" id="LabelSueldo"><?php echo $titulo;?></div>              
              </td>
              <td><input type='text' id="sueldobasico" name="sueldobasico"  class="required number" size="10"
               value="<?php echo $datoTrabajador['sueldobasico'];?>"/></td>
            </tr>
            <tr>
              <td width="22%"><div align="right">Fecha Ingreso:</div></td>
              <td width="35%"><input type='text' id="fechaingreso" name="fechaingreso" class="date" size="12" 
              value="<?php 
			  if (isset($datoTrabajador['idtrabajador'])) {
			      echo $db->GetFormatofecha($datoTrabajador['fechaingreso'],'-');
			  } else {
				  echo date("d/m/Y");
			  }
			  ?>"/></td>
              <td width="3%"></td>
              <td align="right">Bono  Producción:</td>
              <td><input type='text' id="bonoproduccion" <?php echo $Matributo;?> name="bonoproduccion" class="number" size="10"
               value="<?php echo $datoTrabajador['bonoproduccion'];?>"/></td>
            </tr>
            <tr>            
              <td align="right">Fecha Finalización:
              </td>
              <td><input type='text' id="fechafinalizacion" name="fechafinalizacion" class="date" size="12"
               value="<?php 
			   if (isset($datoTrabajador['idtrabajador'])) {
			      echo $db->GetFormatofecha($datoTrabajador['fechafinalizacion'],'-');
			   } else {
				  echo date("d/m/Y");
			  }
			   ?>"/></td>
              <td>&nbsp;</td>
              <td><div align="right">Bono Transporte:</div></td>
              <td><input type='text' id="transporte" name="transporte" <?php echo $Matributo;?> class="number" size="10"
               value="<?php echo $datoTrabajador['transporte'];?>" /></td>
            </tr>
            <tr>
              <td><div align="right">Departamento<span class="rojo">*</span>:</div></td>
              <td><select id="departamento" name="departamento" style="width:150px;">
                <option value="" selected="selected">-- Seleccione --</option>
                  <?php
                 $sql = "select iddepartamento,left(nombre,20) from departamento where estado=1";
                 $db->imprimirCombo($sql,$datoTrabajador['seccion']);
               ?>
              </select></td>
              <td>&nbsp;</td>
              <td align="right">Bono Puntualidad:</td>
              <td><input type='text' id="puntualidad" name="puntualidad" <?php echo $Matributo;?> class="number" size="10"
               value="<?php echo $datoTrabajador['puntualidad'];?>"/></td>
            </tr>
            <tr>
              <td><div align="right">Forma de Pago de Sueldo:</div></td>
              <td>
              <select name="formadepago" id="formadepago" onchange="bloquear(this.value)" style="width:150px;">
                <?php
				 $selec = $datoTrabajador['formadepago']; 
				 $tipo = array("Efectivo", "Transferencia Bancaria");
				 for ($i = 0; $i < count($tipo); $i++) {
					$atributo = ""; 
					if ($selec == $tipo[$i]) {
					    $atributo = "selected='selected'";	
					}
					echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
				 }	
				 $atributo = "disabled='disabled'";
				 if ($selec == "Transferencia Bancaria") {
					 $atributo = "";
				 }				 
	             ?>
               </select></td>
              <td>&nbsp;</td>
              <td><div align="right">Bono Asistencia:</div></td>
              <td><input type='text' id="asistencia" <?php echo $Matributo;?> name="asistencia" class="number" size="10"
               value="<?php echo $datoTrabajador['asistencia'];?>"/></td>
            </tr>
            <tr>
              <td>
                <div align="right">Nombre del Banco:</div>
              </td>
              <td><input type='text' id="nombrebanco" <?php echo $atributo;?> name="nombrebanco" 
               style="width:145px;" value="<?php echo $datoTrabajador['nombrebanco'];?>"/></td>
              <td>&nbsp;</td>
              <td width="14%"><div align="right">Nº de cuenta Bancaria:</div></td>
              <td width="26%"><input type='text' id="numerocuenta" <?php echo $atributo;?> name="numerocuenta" class="number"
               size="10" value="<?php echo $datoTrabajador['numerocuenta'];?>"/></td>
            </tr>
            <tr>
              <td><div align="right">Correo Corporativo:</div></td>
              <td><input type='text' id="emailcorporativo" name="emailcorporativo" style="width:145px;"
               value="<?php echo $datoTrabajador['emailcorporativo'];?>"/></td>
              <td>&nbsp;</td>
              <td><div align="right">Nº de Seguro Medico:</div></td>
              <td><input type='text' id="seguromedico" <?php echo $Matributo;?> name="seguromedico"
               size="10" value="<?php echo $datoTrabajador['seguromedico'];?>"/>
                F/Inicio
                <input type='text' id="fechaseguro" name="fechaseguro"
               size="10" value="<?php 
			  if (isset($datoTrabajador['idtrabajador'])) {
			      echo $db->GetFormatofecha($datoTrabajador['fechaseguro'],'-');
			  } else {
				  echo date("d/m/Y");
			  }
			  ?>"/></td>
            </tr>
            <tr>
              <td align="right">Sucursal Planilla<span class='rojo'>*</span>:</td>
              <td><select id="idsucursal" name="idsucursal" style="width:150px;" class="required">
                <option value=""> -- Seleccione -- </option>
                <?php
                  $db->imprimirCombo("select idsucursal,left(nombrecomercial,20) from sucursal where estado=1"
				  ,$datoTrabajador['idsucursal']);
                ?>
              </select></td>
              <td>&nbsp;</td>
              <td><div align="right">N° de A.F.P.:</div></td>
              <td><input type='text' id="afp" name="afp" 
               size="10" value="<?php echo $datoTrabajador['afp'];?>"/>
                F/Inicio
                  <input type='text' id="fechaafp" <?php echo $Matributo;?> name="fechaafp"
               size="10" value="<?php 
			  if (isset($datoTrabajador['idtrabajador'])) {
			      echo $db->GetFormatofecha($datoTrabajador['fechaafp'],'-');
			  } else {
				  echo date("d/m/Y");
			  }
			  ?>"/></td>
            </tr>
            <tr>
              <td><div align="right">Días de Trabajo:</div></td>
              <td><table width="75%" border="0">
                <tr height="10">
                  <td width="16%" align="center">L</td>
                  <td width="15%" align="center">M</td>
                  <td width="13%" align="center">M</td>
                  <td width="13%" align="center">J</td>
                  <td width="13%" align="center">V</td>
                  <td width="13%" align="center">S</td>
                  <td width="17%" align="center">D</td>
                </tr>
                <tr>
                <?php	
					if (isset($datoTrabajador['diastrabajo'])) {
						$dias = $datoTrabajador['diastrabajo'];	 
					} else {
						$dias = '1-1-1-1-1-1-1';	
					}    
					$dias = explode('-', $dias);
				?>
                
                  <td height="22"><input type='checkbox' onclick='setValorCheck(this.id)'  
                  value='<?php if ($dias[0] == '1') echo 1; else echo 0;?>' 
                   <?php if ($dias[0] == '1')  echo "checked='checked'";?> id="lunes" name="lunes" size="32" /></td>
                  <td><input type='checkbox' onclick='setValorCheck(this.id)'  
                  value='<?php if ($dias[1] == '1') echo 1; else echo 0;?>' 
                    <?php if ($dias[1] == '1')  echo "checked='checked'";?> id="martes" name="martes" size="32" /></td>
                  <td><input type='checkbox' onclick='setValorCheck(this.id)'
                    value='<?php if ($dias[2] == '1') echo 1; else echo 0;?>' 
                    <?php if ($dias[2] == '1')  echo "checked='checked'";?> id="miercoles" name="miercoles" size="32" /></td>
                  <td><input type='checkbox' onclick='setValorCheck(this.id)' 
                   value='<?php if ($dias[3] == '1') echo 1; else echo 0;?>' 
                    <?php if ($dias[3] == '1')  echo "checked='checked'";?> id="jueves" name="jueves" size="32" /></td>
                  <td><input type='checkbox' onclick='setValorCheck(this.id)'  
                    value='<?php if ($dias[4] == '1') echo 1; else echo 0;?>' 
                    <?php if ($dias[4] == '1')  echo "checked='checked'";?> id="viernes" name="viernes" size="32" /></td>
                  <td><input type='checkbox' onclick='setValorCheck(this.id)'  
                  value='<?php if ($dias[5] == '1') echo 1; else echo 0;?>' 
                    <?php if ($dias[5] == '1')  echo "checked='checked'";?> id="sabado" name="sabado" size="32" /></td>
                  <td><input type='checkbox' onclick='setValorCheck(this.id)'
                   value='<?php if ($dias[6] == '1') echo 1; else echo 0;?>' 
                    <?php if ($dias[6] == '1')  echo "checked='checked'";?> id="domingo" name="domingo" size="32" /></td>
                </tr>
              </table></td>
              <td>&nbsp;</td>
              <td>Sucursal de Trabajo:</td>
              <td><input type="button" value="Seleccione" style="width:120px;"
                class="botonNegro" onclick="ventanaCompartido('subventana','abrir');"/></td>
            </tr>
          </table>
        </div>
        
        
        
        <!--  Datos del Horario  --> 
        <div id='tabs-8' >
        
          <table width="100%" border="0">
            <tr>
              <td width="24%" align="right">&nbsp;</td>
              <td width="10%">&nbsp;</td>
              <td width="13%">&nbsp;</td>
              <td width="15%">&nbsp;</td>
              <td width="16%"></td>
              <td width="22%">&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Modalidad de Contrato<span class='rojo'>*</span>:</div></td>
              <td><select name="modalidadcontrato" id="modalidadcontrato" onchange="setModalidad(this.value)">
                <?php
				   $selec = $datoTrabajador['modalidadcontrato']; 
				   $tipo = array("Temporal", "Indefinido", "Prueba","Consultor");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
				   }	
	              ?>
              </select></td>
              <td colspan="3">(Temporal e indefinido figuran en planilla de sueldos)</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Contrato para.:</td>
              <td>
              <select name="contratopara" id="contratopara" onchange="bloquearContrato(this.value)">
                <?php
				   $selec = $datoTrabajador['tipohorario']; 
				   $tipo = array("Administracion", "Ciudad", "Campo");
				   $texto = array("Administración", "Ciudad", "Campo");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
				   }	
	            ?>
              </select>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Control de Asistencia:</td>
              <td><select name="control" id="control">
                <?php
				   $selec = $datoTrabajador['control']; 
				   $tipo = array("Sin Monitoreo", "Monitorizado");
				   $texto = array("Sin Monitoreo", "Monitorizado");
				   for ($i = 0; $i < count($tipo); $i++) {
					  $atributo = ""; 
					  if ($selec == $tipo[$i]) {
					      $atributo = "selected='selected'";	
					  }
					  echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
				   }	
	            ?>
              </select></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>            
            <tr>
            <?php	
			    $atributo1 = "";
				$atributo2 = "disabled='disabled'";
				$atributo3 = "disabled='disabled'";
			  	
					   
			   if (isset($datoTrabajador['tipohorario'])) {
				   $atributo1 = "disabled='disabled'";
				   $atributo2 = "disabled='disabled'";
				   $atributo3 = "disabled='disabled'"; 
				   switch($datoTrabajador['tipohorario']) {
					  case "Administracion":
						  $atributo1 = "";
						  $horarioAdmin = $dbtrabajador->getHorario($datoTrabajador['horariodetrabajo']);
					  break;
					  case "Ciudad":
					      $atributo2 = "";
						  $horarioCiudad = explode("/", $datoTrabajador['horariodetrabajo']);
					  break;
					  case "Campo":
					      $atributo3 = "";
						  $horarioCampo = explode("/", $datoTrabajador['horariodetrabajo']);
					  break;
					   
				   }
			   }			   
			?>
            
              <td align="right">Administración<span class="rojo">*</span>:</td>
              <td colspan="4">
              <div class="subIngreso3">
              <table width="100%" border="0">
                <tr>
                  <td width="10%" align="right">AM </td>
                  <td width="40%">
                  <select name="am1" id="am1" style="width:38px;" <?php echo $atributo1;?>>
                  <?php
				   $selec = $horarioAdmin[0];
				   for ($i = 1; $i <= 12; $i++) {	
				      $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  }				 
					  echo "<option value='$i' $atributo>$i:</option>";
				   }	
	              ?>
                  </select>
                  <select name="am1_minutos" id="am1_minutos" <?php echo $atributo1;?>>
                  <?php
				   $selec = $horarioAdmin[1];
				   for ($i = 0; $i <= 45; $i = $i + 15) {
					  $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  }	 
					  if ($i == 0) {
						  echo "<option value='$i' $atributo>00</option>";
					  } else {
					      echo "<option value='$i' $atributo>$i</option>";
					  }
				   }	
	              ?>
                  </select>
                  -
                  <select name="am2" id="am2" style="width:38px;" <?php echo $atributo1;?>>
					<?php
					 $selec = $horarioAdmin[2];
					 for ($i = 1; $i <= 12; $i++) {
						$atributo = "";
				        if ($selec == $i) {
					        $atributo = "selected='selected'";	
					    }	  					 
						echo "<option value='$i' $atributo>$i:</option>";
					 }	
					?>
                  </select>
                  <select name="am2_minutos" id="am2_minutos" <?php echo $atributo1;?>>
                  <?php
				   $selec = $horarioAdmin[3];
				   for ($i = 0; $i <= 45; $i = $i + 15) {
					  $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  } 
					  if ($i == 0) {
						  echo "<option value='$i' $atributo>00</option>";
					  } else {
					      echo "<option value='$i' $atributo>$i</option>";
					  }
				   }	
	              ?>
                  </select></td>
                  <td width="11%" align="right">PM</td>
                  <td width="39%">
                  <select name="pm1" id="pm1" style="width:38px;" <?php echo $atributo1;?>>
                  <?php
				   $selec = $horarioAdmin[4];
				   for ($i = 13; $i <= 24; $i++) {	
				      $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  } 				 
					  echo "<option value='$i' $atributo>$i:</option>";
				   }	
	              ?>
                  </select>
                  <select name="pm1_minutos" id="pm1_minutos" <?php echo $atributo1;?>>
                  <?php
				   $selec = $horarioAdmin[5];
				   for ($i = 0; $i <= 45; $i = $i + 15) {
					  $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  } 
					  if ($i == 0) {
						  echo "<option value='$i' $atributo>00</option>";
					  } else {
					      echo "<option value='$i' $atributo>$i</option>";
					  }
				   }	
	              ?>
                  </select>
                    -
                  <select name="pm2" id="pm2" style="width:38px;" <?php echo $atributo1;?>>
                   <?php
				   $selec = $horarioAdmin[6];
				   for ($i = 13; $i <= 24; $i++) {
					  $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  } 					 
					  echo "<option value='$i' $atributo>$i:</option>";
				   }	
	              ?>
                  </select>
                  <select name="pm2_minutos" id="pm2_minutos" <?php echo $atributo1;?>>
                  <?php
				   $selec = $horarioAdmin[7];
				   for ($i = 0; $i <= 45; $i = $i + 15) {
					  $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  } 
					  if ($i == 0) {
						  echo "<option value='$i' $atributo>00</option>";
					  } else {
					      echo "<option value='$i' $atributo>$i</option>";
					  }
				   }	
	              ?>
                  </select>
                </td>                  
                </tr>
              </table>
              </div>
              
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="4"></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Ciudad<span class="rojo">*</span>:</td>
              <td colspan="4">
              <div class="subIngreso3">
              <table width="100%" border="0">
                <tr>
                  <td width="24%" align="right">INGRESA </td>
                  <td width="27%">
                  <select name="ingreso1" id="ingreso1" <?php echo $atributo2;?>>
                  <?php
					$selec = $horarioCiudad[0];
				    for ($i = 1; $i <= 24; $i++) {
					  $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  } 					 
					  echo "<option value='$i' $atributo>$i</option>";
				    }	
	              ?>
                  </select></td>
                  <td width="18%" align="right">SALE</td>
                  <td width="31%">
                  <select name="sale1" id="sale1" <?php echo $atributo2;?>>
                  <?php
				   $selec = $horarioCiudad[1];
				   for ($i = 1; $i <= 24; $i++) {
					  $atributo = "";
				      if ($selec == $i) {
					      $atributo = "selected='selected'";	
					  } 					 
					  echo "<option value='$i' $atributo>$i</option>";
				   }	
	              ?>
                  </select></td>
                  
                </tr>
              </table>
              </div>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="4">
              
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Campo<span class="rojo">*</span>:</td>
              <td colspan="4" >
              <div class="subIngreso3">
              <table width="100%" border="0">
                <tr>
                  <td width="24%" align="right">DIAS TRABAJO</td>
                  <td width="27%">
                  <select name="dias1" id="dias1" <?php echo $atributo3;?>>
                  <?php
				     $selec = $horarioCampo[0]; 
				     for ($i = 1; $i <= 30; $i++) {	
					     $atributo = "";
				         if ($selec == $i) {
					         $atributo = "selected='selected'";	
					     } 				 
					     echo "<option value='$i' $atributo>$i</option>";
				     }	
	              ?>
                  </select></td>
                  <td width="18%" align="right">DIAS DESCANSO </td>
                  <td width="31%">
                  <select name="dias2" id="dias2" <?php echo $atributo3;?>>
                   <?php
				      $selec = $horarioCampo[1]; 
				      for ($i = 1; $i <= 30; $i++) {
						  $atributo = "";
				          if ($selec == $i) {
					          $atributo = "selected='selected'";	
					      }					 
					      echo "<option value='$i' $atributo>$i</option>";
				      }	
	              ?>
                  </select></td>
                  
                </tr>
              </table>
              </div>
              </td>
              <td>&nbsp;</td>
            </tr>
          </table>    
                 
        </div>
                
        
        <!--  Datos del Vendedor  --> 
        <div id='tabs-10' >
        
          <table width="100%" border="0">
            <tr>
              <td width="20%" align="right">Comisión Sobre Venta:</td>
              <td width="19%"><input type='text' id="comisionventas" name="comisionventas" class="" size="10"
               value="<?php echo $datoVendedor['comisionventas'];?>"/></td>
              <td width="7%">&nbsp;</td>
              <td width="18%"><div align="right">Comisión Sobre Cobros:</div></td>
              <td width="36%"><input type='text' id="comisioncobros" name="comisioncobros" class="" size="10"
               value="<?php echo $datoVendedor['comisioncobros'];?>"/></td>
            </tr>
            <tr>
              <td><div align="right">Ruta de Ventas:</div></td>
              <td>
               <input type="button" value="Seleccionar" style="width:120px;"
                class="botonNegro" onclick="ventanaCompartido('subventanaRuta','abrir');"/>
              </td>
              <td>&nbsp;</td>
              <td></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5">         
              </td>
            </tr>
          </table>                            
        </div>
        
        
        
           <!--  Datos del Cajero  --> 
           <div id='tabs-9' >          
           <?php
			   $consultaPlanCuenta = "select (select pp.cuenta from plandecuenta pp 
			   where pp.codigo=( left(ph.codigo,2) ))as 'padre',ph.codigo,ph.cuenta,ph.nivel
				from plandecuenta ph  where ph.nivel>=5 and estado=1 order by ph.codigo;";
			   $arrayPlan = $db->getDatosArray($consultaPlanCuenta, 4);
           ?>
            <table width="100%" border="0">
            <tr>
              <td width="18%"><div align="right"></div></td>
              <td width="18%">&nbsp;</td>
              <td width="14%">&nbsp;</td>
              <td width="29%"><div align="right"></div></td>
              <td width="21%">&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Titulo Caja 1:</div></td>
              <td>
              <input type='text' id="textocaja1"  name="textocaja1" 
               size="20" value="<?php echo $datoCajero['textocaja1']?>"/>
              </td>
              <td align="right">Cuenta Caja 1:</td>
              <td align="left">
                <select name="cuentacaja1" id="cuentacaja1"  style="width:200px;" >
                  <optgroup >
                    <option value="">--Seleccione una Cuenta--</option>
                    </optgroup>
                  <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentacaja1']); ?>
                </select>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Titulo Caja 2:</div></td>
              <td>
              <input type='text' id="textocaja2" name="textocaja2" 
               size="20" value="<?php echo $datoCajero['textocaja2']?>"/>
              </td>
              <td align="right">Cuenta Caja 2:</td>
              <td align="left">
                <select name="cuentacaja2" id="cuentacaja2"  style="width:200px;" >
                  <optgroup >
                    <option value="">--Seleccione una Cuenta--</option>
                    </optgroup>
                   <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentacaja2']); ?>
                </select>
              </td>
              <td>&nbsp;</td>
            </tr>
                        
             <tr>
              <td><div align="right">Titulo Caja 3:</div></td>
              <td>
              <input type='text' id="textocaja3" name="textocaja3" 
               size="20" value="<?php echo $datoCajero['textocaja3']?>"/>
              </td>
              <td align="right">Cuenta Caja 3:</td>
              <td align="left">
                <select name="cuentacaja3" id="cuentacaja3"  style="width:200px;" >
                  <optgroup >
                    <option value="">--Seleccione una Cuenta--</option>
                    </optgroup>
                   <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentacaja3']); ?>
                </select>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Titulo Caja 4:</div></td>
              <td>
              <input type='text' id="textocaja4" name="textocaja4" 
               size="20" value="<?php echo $datoCajero['textocaja4']?>"/>
              </td>
              <td align="right">Cuenta Caja 4:</td>
              <td align="left">
                <select name="cuentacaja4" id="cuentacaja4"  style="width:200px;" >
                  <optgroup >
                    <option value="">--Seleccione una Cuenta--</option>
                    </optgroup>
                   <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentacaja4']); ?>
                </select>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Titulo Caja 5:</div></td>
              <td>
              <input type='text' id="textocaja5" name="textocaja5" 
               size="20" value="<?php echo $datoCajero['textocaja5']?>"/>
              </td>
              <td align="right">Cuenta Caja 5:</td>
              <td align="left">
                <select name="cuentacaja5" id="cuentacaja5"  style="width:200px;" >
                  <optgroup >
                    <option value="">--Seleccione una Cuenta--</option>
                    </optgroup>
                   <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentacaja5']); ?>
                </select>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Titulo Caja 6:</div></td>
              <td>
              <input type='text' id="textocaja6" name="textocaja6" 
               size="20" value="<?php echo $datoCajero['textocaja6']?>"/>
              </td>
              <td align="right">Cuenta Caja 6:</td>
              <td align="left">
                <select name="cuentacaja6" id="cuentacaja6"  style="width:200px;" >
                  <optgroup >
                    <option value="">--Seleccione una Cuenta--</option>
                    </optgroup>
                   <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentacaja6']); ?>
                </select>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Titulo Banco 1:</div></td>
              <td>
              <input type='text' id="textobanco1" name="textobanco1" 
              size="20" value="<?php echo $datoCajero['textobanco1']?>"/></td>
              <td align="right">Cuenta Banco 1:</td>
              <td>
                <select name="cuentabanco1" id="cuentabanco1"  style="width:200px;">
                  <optgroup >
                    <option value="">--Seleccione una Cuenta--</option>
                    </optgroup>
                   <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentabanco1']); ?>
                </select>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Titulo Banco 2:</div></td>
              <td><input type='text' id="textobanco2" name="textobanco2" 
               size="20" value="<?php echo $datoCajero['textobanco2']?>" /></td>
              <td align="right">Cuenta Banco 2:</td>
              <td align="left"><select name="cuentabanco2" id="cuentabanco2"  style="width:200px;" >
                <optgroup >
                  <option value="">--Seleccione una Cuenta--</option>
                  </optgroup>
                 <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentabanco2']); ?>
              </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Titulo Banco 3: </td>
              <td>
              <input type='text' id="textobanco3" name="textobanco3" 
               size="20" value="<?php echo $datoCajero['textobanco3']?>"/>
              </td>
              <td align="right">Cuenta Banco 3:</td>
              <td align="left"><select name="cuentabanco3" id="cuentabanco3"  style="width:200px;">
                <optgroup >
                  <option value="">--Seleccione una Cuenta--</option>
                  </optgroup>
                 <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentabanco3']); ?>
              </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Titulo Banco 4: </td>
              <td>
              <input type='text' id="textobanco4" name="textobanco4" 
               size="20" value="<?php echo $datoCajero['textobanco4']?>"/>
              </td>
              <td align="right">Cuenta Banco 4:</td>
              <td align="left"><select name="cuentabanco4" id="cuentabanco4"  style="width:200px;">
                <optgroup >
                  <option value="">--Seleccione una Cuenta--</option>
                  </optgroup>
                 <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentabanco4']); ?>
              </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Titulo Banco 5: </td>
              <td>
              <input type='text' id="textobanco5" name="textobanco5" 
               size="20" value="<?php echo $datoCajero['textobanco5']?>"/>
              </td>
              <td align="right">Cuenta Banco 5:</td>
              <td align="left"><select name="cuentabanco5" id="cuentabanco5"  style="width:200px;">
                <optgroup >
                  <option value="">--Seleccione una Cuenta--</option>
                  </optgroup>
                 <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentabanco5']); ?>
              </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Titulo Banco 6: </td>
              <td>
              <input type='text' id="textobanco6" name="textobanco6" 
               size="20" value="<?php echo $datoCajero['textobanco6']?>"/>
              </td>
              <td align="right">Cuenta Banco 6:</td>
              <td align="left"><select name="cuentabanco6" id="cuentabanco6"  style="width:200px;">
                <optgroup >
                  <option value="">--Seleccione una Cuenta--</option>
                  </optgroup>
                 <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoCajero['cuentabanco6']); ?>
              </select></td>
              <td>&nbsp;</td>
            </tr>
          </table>          
          
        </div>      
       
      </div></td>
    </tr>
  </table></td>
</tr>
</table>
 </table>
</form>
</div>
</td></tr></table>
<br /><br />
<script>  
  seleccionarRadio('formValidado','vivencia','<?php echo $datoTrabajador['vivienda'];?>');
  recuperaAuxiliares('<?php echo $datoCajero['idcajero'];?>', 'cajero');
  recuperaAuxiliares('<?php echo $datoVendedor['idvendedor'];?>', 'vendedor'); 
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