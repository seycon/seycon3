<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');
	$db = new MYSQL();
	$dirListado = "listar_activo.php#t1";
	if (!isset($_SESSION['softLogeoadmin'])) {
	   header("Location: index.php");	
	}
	
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Activo'], 'Alta de Activo', 'nuevo_activo.php', 'listar_activo.php');
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	  $transaccion = "modificar";	
	  $sql = "SELECT * FROM activo WHERE idactivo = ".$_GET['idactivo'];
	  $datoActivo = $db->arrayConsulta($sql);  
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateactivo.dwt.php" codeOutsideHTMLIsLocked="false" -->
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
<link rel="stylesheet" href="altaActivo/activo.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="altaActivo/activo.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script>
  $(document).ready(function()
  {
  $("#fechacompra").datepicker({
  showOn: "button",
  buttonImage: "css/images/calendar.gif",
  buttonImageOnly: true,
  dateFormat: 'dd/mm/yy'
  });
  });
</script>
<style>
.bordeContenido{
  border: 1px solid #CCC;	
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

<div id="overlays" class="overlays"></div>
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


<div id="subventana" class="contenedorframeID">    
    <div class="modal_interiorframeID"></div>  

    <div  class="subVentana"> 
     <div class="caption_modalCabecera">
        <div class="posicionCloseSub" onclick="cerrarSubVentana();">
         <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="cerrarSubVentana();">
        </div>
        <div class="titleHeadCaption">Datos Factura</div>
     </div>
          
          
        <table width="100%" border="0" >
           
          <tr>
            <td colspan="2"><strong>DIA
                <br />
            </strong>              
             <input name="diaF" type="text" id="diaF" size="12" onkeypress="return cambiarFoco(event)"  />
            <input type="hidden" id="ItemF" name="ItemF" value=""  /></td>
          </tr>
           
          <tr>
            <td width="40%" align="left"><strong>NIT<br />
            </strong>
            <input name="nitF" type="text" id="nitF" size="13" 
            onchange="recuperaNombre(this.value,'altaActivo/Dactivo.php')" onkeypress="return soloNumeros(event)" /></td>
            <td width="60%" align="left"><input name="ItemTabla" type="hidden" id="ItemTabla" 
            size="13" onkeypress="return soloNumeros(event)" /></td>
          </tr>           
          <tr>
             <td colspan="2"><strong>Nombre o Razon Social</strong><br />
              <input name="razonsocialF" type="text" id="razonsocialF" size="29" /></td>
          </tr>           
          <tr>
            <td ><strong>N° Factura</strong><br />
              <input type="text" id="numerofactura" name="numerofactura" size="10" onkeypress="return soloNumeros(event)" />
            </td>
            <td align="left"><strong>N° Autorizacion</strong><br />

               <input name="numeroautorizacionF" type="text" id="numeroautorizacionF" value="" style="width:90%"/>
            </td>
          </tr>
          <tr>
            <td align="right" style="font-size:12px;">Imp. Total (a): </td>
            <td>
              <input name="importetotalF" onkeypress="return soloNumeros(event)" type="text" id="importetotalF" style="width:90%" />
            </td>
          </tr>
          <tr>
            <td align="right"  style="font-size:12px;">Imp. ICE (b):</td>
            <td>
              <input name="iceF" onkeypress="return soloNumeros(event)" type="text" id="iceF" style="width:90%" /></td>
          </tr>
          <tr>
            <td  align="right"style="font-size:12px;">Imp. Excento (c):</td>
            <td>
              <input name="excentoF" onkeypress="return soloNumeros(event)" type="text" id="excentoF" style="width:90%" /></td>
               
          </tr>
          <tr>
            <td align="right" bgcolor="#999999"style="font-size:12px;"> Imp. Neto (a-b-c):</td>
            <td bgcolor="#999999">
              <input name="netoF" onkeypress="return false" type="text" id="netoF" style="width:90%" onfocus="calculaNeto();" /></td>
          </tr>
          <tr>
            <td align="right" bgcolor="#999999"style="font-size:12px;">Imp. IVA (13%):</td>
            <td bgcolor="#999999">
              <input name="ivaF" onkeypress="return false" type="text" id="ivaF" style="width:90%" onfocus="calculaIva();" /></td>
          </tr>
            
          <tr>
              <td align="right">Codigo Control:            </td>
              <td align="left"><input name="codigocontrolF" type="text" id="codigocontrolF" style="width:90%" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center" ></td>
          </tr>
     </table>
     <div class="boton1_subventana">
       <input type="button" name="Guardar" id="Guardar" value="Guardar" class="botonNegro"  onclick="insertarNewFactura();"/>
     </div>
     <div class="boton2_subventana">
       <input onclick="cerrarSubVentana();" type="button" value="Cancelar" id="aceptar_modal"  class="botonNegro"/>
     </div>          
   </div>
</div>





<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Activos > Alta de Activo </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Activo'];
   $privilegios = $db->getOpciones($menus, "Alta de Activo"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_activo.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='button' class='botonNegro' id='enviar' value='Guardar [F2]' onclick="ejecutarTransaccion();" />
  <?php 
	if ($fileAcceso['File'] == "Si"){
	 echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
	 value="Cancelar [F4]" onClick="location.href=&#039listar_activo.php#t1&#039"/>';	
	}
  ?>
 
 </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idactivo" name="idactivo" value="<?php echo  $datoActivo['idactivo'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right">
    <?php 
	if(isset($_GET['idactivo'])) {
	  echo "<strong>Transacción N&deg; </strong>";
	} 
    ?>
    </td>
    <td width="142">
	<?php 
	if(isset($_GET['idactivo'])) {
	  echo $datoActivo['numero'];
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
<td colspan='5' align='center' >
</td>
</tr>


<tr>
<td colspan="2"></td>
<td>&nbsp;</td>
<td align="right">T.C.: <?php 
  $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
  $tc = $db->getCampo('dolarcompra',$sql);   
  echo $tc;
?>
  <input id="tipoCambioBs" name="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" /></td>
<td width='118' align='center'></td>
</tr>
<tr>
  <td colspan='5'><table width="90%" align="center" border="0" class="bordeContenido">
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="29%">&nbsp;</td>
      <td width="16%">&nbsp;</td>
      <td colspan="2" align="right"><input name='enviar2' type='button' class='botonNegro' value='Factura' 
      onclick="openSubVentana();" /></td>
      <td width="1%">&nbsp;</td>
    </tr>
    <tr>
      <td height="42" align="right">Nombre<span class='rojo'>*</span>:</td>
      <td><input type='text' id="nombre" name="nombre"  class="required" size="25" value="<?php echo $datoActivo['nombre'];?>" /></td>
      <td align="right">Tipo de Activo<span class="rojo">*</span>:</td>
      <td colspan="2"><select name="idtipoactivo" class="required" id="idtipoactivo" style="width:180px;">
        <option value="" selected="selected">-- Seleccione --</option>
        <?php
	  $sql = "select idtipoactivo,left(nombre,20)as 'nombre' from tipoactivo where estado=1;";
	  $db->imprimirCombo($sql,$datoActivo['idtipoactivo']);     
      ?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Sucursal<span class="rojo">*</span><span class='rojo'></span>:</td>
      <td><select name="idsucursal" id="idsucursal" style="width:140px;" class="required">
        <option value="" selected="selected">-- Seleccione --</option>
        <?php
   $sql = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1;";
   $db->imprimirCombo($sql,$datoActivo['idsucursal']);
   ?>
      </select></td>
      <td>&nbsp;</td>
      <td colspan="2">Detalle del Activo Fijos:</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Responsable<span class='rojo'></span>:</td>
      <td><select name="idtrabajador" id="idtrabajador" style="width:140px;">
        <option value="" selected="selected">-- Seleccione --</option>
        <?php
	  $sql = "select idtrabajador,concat(nombre,' ',apellido)as 'nombre1' from trabajador where estado=1;";
	  $db->imprimirCombo($sql,$datoActivo['idtrabajador']);     
      ?>
      </select></td>
      <td>&nbsp;</td>
      <td colspan="2"><input type='text' id="detalle" name="detalle"   size="32" value="<?php echo $datoActivo['detalle'];?>"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Ubicación:</td>
      <td colspan="4"><input type='text' id="ubicacion" name="ubicacion"   style="width:90%" value="<?php echo $datoActivo['ubicacion'];?>"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Fecha de Compra:</td>
      <td><input type='text' id="fechacompra" name="fechacompra" class="date" size="12" 
              value="<?php
			   if (isset($datoActivo['idactivo']))
			   echo $db->GetFormatofecha($datoActivo['fechacompra'],'-');
			   else
			   echo date("d/m/Y");
			   ?>"/></td>
      <td align="right">Tipo Cuenta:</td>
      <td width="15%" align="center">Caja
      <input type="radio" id="tipocuenta" name="tipocuenta" value="caja" checked="checked" onclick="cambiarTipoCuenta(this.value)"/>      </td>
      <td width="23%">Cuenta Contable
        <input type="radio" id="tipocuenta" name="tipocuenta" value="cuentacontable" onclick="cambiarTipoCuenta(this.value)"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Cantidad<span class="rojo">*</span>:</td>
      <td><input type='text' id="cantidad" name="cantidad" onkeypress="return soloNumeros(event)" value="<?php echo $datoActivo['cantidad'];?>" class="required" size="15" onkeyup="calcularTotal()"/></td>
      <td valign="middle" align="right"><div id="textoCuenta">Caja/Banco<span class='rojo'>*</span>:</div></td>
      <td colspan="2">
      <select name="cuenta" id="cuenta"  style="width:140px;" class="required">
      <option value="" >-- Seleccione --</option>
      <?php   	  
		  
		   $sql = "select t.modalidadcontrato as 'modalidad' from trabajador t,usuario u where 
					 u.idtrabajador=t.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
					 $MTrabajador = $db->arrayConsulta($sql);
					 if ($MTrabajador['modalidad'] == "Consultor"){
						$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
						from cajero c,trabajador t where t.idtrabajador=c.idtrabajador and t.estado=1;"; 					
					 }else{				 
						$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
						from cajero c,usuario u,trabajador t where t.idtrabajador=u.idtrabajador 
						and u.idtrabajador=c.idtrabajador and u.idusuario=$_SESSION[id_usuario]";				 
					 }
					   $cajas = $db->consulta($sql);
						while ($data = mysql_fetch_array($cajas)) {
						echo "<optgroup label='$data[nombre]'>";	
						if ($data['cuentacaja1']!= "" && $data['textocaja1'] != "")  
						  echo "<option value='$data[cuentacaja1]'>$data[textocaja1]</option>";  
						if ($data['cuentacaja2']!= "" && $data['textocaja2'] != "")  
						  echo "<option value='$data[cuentacaja2]'>$data[textocaja2]</option>"; 
						if ($data['cuentacaja3']!= "" && $data['textocaja3'] != "")  
						  echo "<option value='$data[cuentacaja3]'>$data[textocaja3]</option>"; 
						if ($data['cuentacaja4']!= "" && $data['textocaja4'] != "")  
						  echo "<option value='$data[cuentacaja4]'>$data[textocaja4]</option>"; 
						if ($data['cuentacaja5']!= "" && $data['textocaja5'] != "")  
						  echo "<option value='$data[cuentacaja5]'>$data[textocaja5]</option>"; 
						if ($data['cuentacaja6']!= "" && $data['textocaja6'] != "")  
						  echo "<option value='$data[cuentacaja6]'>$data[textocaja6]</option>";        
						if ($data['cuentabanco1']!= "" && $data['textobanco1'] != "")    
						  echo "<option value='$data[cuentabanco1]'>$data[textobanco1]</option>"; 
						if ($data['cuentabanco2']!= "" && $data['textobanco2'] != "")    
						  echo "<option value='$data[cuentabanco2]'>$data[textobanco2]</option>"; 
						if ($data['cuentabanco3']!= "" && $data['textobanco3'] != "")    
						  echo "<option value='$data[cuentabanco3]'>$data[textobanco3]</option>"; 
						if ($data['cuentabanco4']!= "" && $data['textobanco4'] != "")    
						  echo "<option value='$data[cuentabanco4]'>$data[textobanco4]</option>"; 
						if ($data['cuentabanco5']!= "" && $data['textobanco5'] != "")    
						  echo "<option value='$data[cuentabanco5]'>$data[textobanco5]</option>"; 
						if ($data['cuentabanco6']!= "" && $data['textobanco6'] != "")    
						  echo "<option value='$data[cuentabanco6]'>$data[textobanco6]</option>";       
						  echo "</optgroup>";
						}
		  ?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Precio de Compra<span class="rojo">*</span>:</td>
      <td>
      <input type='text' id="precio" name="precio" value="<?php echo $datoActivo['precio'];?>"  onkeypress="return soloNumeros(event)" size="15" onkeyup="calcularTotal()"/>
      </td>
      <td>&nbsp;</td>
      <td colspan="2"><select id="caja" name="caja" style="background:#FFF;border:solid 1px #999;width:130px;display:none" >
        <option value="">-- Seleccione --</option>
        <?php
		 	  $sql = "select t.modalidadcontrato as 'modalidad' from trabajador t,usuario u where 
					 u.idtrabajador=t.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
					 $MTrabajador = $db->arrayConsulta($sql);
					 if ($MTrabajador['modalidad'] == "Consultor"){
						$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
						from cajero c,trabajador t where t.idtrabajador=c.idtrabajador and t.estado=1;"; 					
					 }else{				 
						$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
						from cajero c,usuario u,trabajador t where t.idtrabajador=u.idtrabajador 
						and u.idtrabajador=c.idtrabajador and u.idusuario=$_SESSION[id_usuario]";				 
					 }
					   $cajas = $db->consulta($sql);
						while ($data = mysql_fetch_array($cajas)) {
						echo "<optgroup label='$data[nombre]'>";	
						if ($data['cuentacaja1']!= "" && $data['textocaja1'] != "")  
						  echo "<option value='$data[cuentacaja1]'>$data[textocaja1]</option>";  
						if ($data['cuentacaja2']!= "" && $data['textocaja2'] != "")  
						  echo "<option value='$data[cuentacaja2]'>$data[textocaja2]</option>"; 
						if ($data['cuentacaja3']!= "" && $data['textocaja3'] != "")  
						  echo "<option value='$data[cuentacaja3]'>$data[textocaja3]</option>"; 
						if ($data['cuentacaja4']!= "" && $data['textocaja4'] != "")  
						  echo "<option value='$data[cuentacaja4]'>$data[textocaja4]</option>"; 
						if ($data['cuentacaja5']!= "" && $data['textocaja5'] != "")  
						  echo "<option value='$data[cuentacaja5]'>$data[textocaja5]</option>"; 
						if ($data['cuentacaja6']!= "" && $data['textocaja6'] != "")  
						  echo "<option value='$data[cuentacaja6]'>$data[textocaja6]</option>";        
						if ($data['cuentabanco1']!= "" && $data['textobanco1'] != "")    
						  echo "<option value='$data[cuentabanco1]'>$data[textobanco1]</option>"; 
						if ($data['cuentabanco2']!= "" && $data['textobanco2'] != "")    
						  echo "<option value='$data[cuentabanco2]'>$data[textobanco2]</option>"; 
						if ($data['cuentabanco3']!= "" && $data['textobanco3'] != "")    
						  echo "<option value='$data[cuentabanco3]'>$data[textobanco3]</option>"; 
						if ($data['cuentabanco4']!= "" && $data['textobanco4'] != "")    
						  echo "<option value='$data[cuentabanco4]'>$data[textobanco4]</option>"; 
						if ($data['cuentabanco5']!= "" && $data['textobanco5'] != "")    
						  echo "<option value='$data[cuentabanco5]'>$data[textobanco5]</option>"; 
						if ($data['cuentabanco6']!= "" && $data['textobanco6'] != "")    
						  echo "<option value='$data[cuentabanco6]'>$data[textobanco6]</option>";       
						  echo "</optgroup>";
						}
		  
		  ?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Precio Total:</td>
      <td><input type='text' id="total" name="total"  class="required" size="15" disabled="disabled" /></td>
      <td>
	  <?php
	  $consultaPlanCuenta = "select (select pp.cuenta from plandecuenta pp 
	  where pp.codigo=( left(ph.codigo,2) ))as 'padre',ph.codigo,ph.cuenta,ph.nivel
	   from plandecuenta  ph  where ph.nivel>=5 and estado=1 order by ph.codigo;";
	  $arrayPlan = $db->getDatosArray($consultaPlanCuenta,4);	  
	  ?></td>
      <td colspan="2">
      <select name="cuentacontable" id="cuentacontable"  style="width:140px;display:none" >
        <optgroup>
          <option value="">-- Seleccione --</option>
          </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayPlan,'',''); ?>
          <?php
		      	  
			    $codigo = ($datoActivo['idactivo'] == "") ? 0 : $datoActivo['idactivo'];
			 		  
				$sql = "
				select day(fechadeemision)as 'dia',numdenitproveedor as 'nit',nomrazonsociprove as 'razonsocial',
                numfacturaopoliza as 'factura',numautorizacion as 'autorizacion',
                codigodecontrol,totalfactura,totalice,importeneto,importeexcento,creditofiscal from 
                librocomprasiva where idtransaccion=$codigo and transaccion='Alta Activo';
				";
				$datoFactura = $db->arrayConsulta($sql);	  
					  
	          	?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  </tr>
</table>
</form>
</div>
</td></tr></table>
<script>
  seleccionarRadio("formValidado","tipocuenta","<?php echo $datoActivo['tipocuenta'];?>");
  cambiarTipoCuenta("<?php echo $datoActivo['tipocuenta'];?>");
  seleccionarCombo("cuenta","<?php echo $datoActivo['cuenta'];?>");
  calcularTotal();
  transaccion = "<?php echo $transaccion;?>";  
  datosFactura.dia = "<?php echo $datoFactura['dia'];?>";
  datosFactura.nit = "<?php echo $datoFactura['nit'];?>";
  datosFactura.razonsocial = "<?php echo $datoFactura['razonsocial'];?>";
  datosFactura.numfactura = "<?php echo $datoFactura['factura'];?>";
  datosFactura.numeroautorizacion = "<?php echo $datoFactura['autorizacion'];?>";
  datosFactura.importetotal = "<?php echo $datoFactura['totalfactura'];?>";
  datosFactura.ice = "<?php echo $datoFactura['totalice'];?>";
  datosFactura.excento = "<?php echo $datoFactura['importeexcento'];?>";
  datosFactura.neto = "<?php echo $datoFactura['importeneto'];?>";
  datosFactura.iva = "<?php echo $datoFactura['creditofiscal'];?>";
  datosFactura.codigocontrol = "<?php echo $datoFactura['codigodecontrol'];?>";  
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