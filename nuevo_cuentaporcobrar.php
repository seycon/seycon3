<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');
	session_start();
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Ventas'],'Cuenta por Cobrar'
	,'nuevo_cuentaporcobrar.php','listar_cuentaporcobrar.php');
	if ($fileAcceso['Acceso'] == "No"){
	    header("Location: cerrar.php");	
	}	
	$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	$tc = $db->getCampo('dolarcompra',$sql); 
	
	$sql = "select left(tituloctaxcobrar,30)as 'tituloctaxcobrar' from impresion where idimpresion = 1";
	$tituloPrincipal = $db->getCampo('tituloctaxcobrar', $sql);
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	  $transaccion = "modificar";	
	  $sql = "SELECT * FROM cuentaporcobrar WHERE idporcobrar = ".$_GET['idporcobrar'];
	  $datoTransaccion = $db->arrayConsulta($sql);  
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
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<link rel="stylesheet" href="cuentaporcobrar/cuentacobrar.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="cuentaporcobrar/cuentaporcobrar.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script>
$(document).ready(function()
{

<?php	
 if (!isset($datoTransaccion['idporcobrar'])){
echo "	
$('#fecha').datepicker({
showOn: 'button',
buttonImage: 'css/images/calendar.gif',
buttonImageOnly: true,
dateFormat: 'dd/mm/yy' });";
 }
?>


$("#fechavencimiento").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

});
</script>

<style type="text/css">
.bordeContenido {  border: 1px solid #CCC;}
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
   
<div id="modal_vendido" class="contenedorMsgBoxOption">
  <div class="modal_interiorMsgBoxOption"></div>
  <div class="modalContenidoMsgBoxOption">
      <div class="cabeceraMsgBoxOption">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBoxOption">Opciones del Sistema</div>
        <div class="modal_cerrarMsgBoxOption">
         <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="cerrarPagina()"></div>
      </div>
      <div class="contenidoMsgBoxOption">
        <div class="modal_datosMsgBoxOption" id="modal_contenido">Su transacción fue registrada exitosamente. A continuación 
        le presentamos las opciones del registro.  </div>
          <table width="311" align="center" style="margin-top:40px;">
            <tr>            
            <td><input type="button" value="Ver Reporte " onclick="accionPostRegistro();" class="botonNegro"/></td>
            <td align="right">Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
          </table> 
        
      </div>
  </div>
</div>


<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Ventas > Cuenta por Cobrar </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Ventas'];
   $privilegios = $db->getOpciones($menus, "Cuenta por Cobrar"); 
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
 <form id='formdatos' name='formdatos' method='post' action='nuevo_cuentaporcobrar.php' enctype='multipart/form-data'>
 <div class="contemHeaderTop">
     <table cellpadding='0' cellspacing='0' width='100%'>
        <tr class='cabeceraInicialListar'> 
          <td height="92" colspan='2' >&nbsp;&nbsp;
          <input name='enviar' type='button' class='botonNegro' id='enviar' value='Guardar [F2]' onclick="registrarDatos()" />
          <?php 
              if ($fileAcceso['File'] == "Si"){
               echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
               value="Cancelar [F4]" onClick="location.href=&#039listar_cuentaporcobrar.php#t7&#039"/>';	
              }
          ?>
       
       </td>
      <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
      <input type="hidden"  id="idporcobrar" name="idporcobrar" value="<?php echo $_GET['idporcobrar'];?>" /></td>
      <td colspan="3" align='right'><table width="356" border="0">
        <tr>
          <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
          <td width="142">
          <?php 
          if (isset($_GET['idporcobrar']))
             echo $datoTransaccion['idporcobrar'];
          else
             echo $db->getNextID("idporcobrar","cuentaporcobrar");   
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
<td colspan='4'></td>
<td width='118' align='center'></td>
</tr>
<tr>
  <td colspan="5" align='right' valign='top'><table width="90%" border="0" align="center" class="bordeContenido">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td align="right">T.C.:<?php
	  if (isset($datoTransaccion['idporcobrar']))
	   $tc = $datoTransaccion['tipocambio'];
	  
	   echo $tc;?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="13%">&nbsp;</td>
      <td width="20%">&nbsp;</td>
      <td width="17%">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="28%">&nbsp;</td>
      <td width="3%">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Fecha:</td>
      <td><input type='text' id="fecha" name="fecha" 
	  <?php if (isset($datoTransaccion['idporcobrar'])) echo "disabled='disabled'"; ?> class="date" size="10" value="<?php 
	  if (isset($_GET['idporcobrar']))
	  echo $db->GetFormatofecha($datoTransaccion['fecha'],'-');
	  else
	  echo date('d/m/Y');
	  
	  ?>" /></td>
      <td align="right"></td>
      <td colspan="2" align="right">Moneda:</td>
      <td align="left">
      <select name="moneda" id="moneda" style="width:100px;background:#FFF;border:solid 1px #999;">
        <?php          
		   $selec = $datoTransaccion['moneda']; 
	 $tipo = array("Bolivianos","Dolares");
	 for ($i=0;$i<count($tipo);$i++){
		$atributo = ""; 
		if ($selec == $tipo[$i]){
		$atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
	 }
		  
      ?>
      </select>
      </td>
      <td><input type="hidden" id="tipocambio" name="tipocambio"  value="<?php echo $tc;?>"/></td>
    </tr>
    <tr>
      <td align="right">Sucursal<span class="rojo">*</span>:</td>
      <td><select name="sucursal" id="sucursal" style="width:130px; background:#FFF;border:solid 1px #999;" >
        <option value="" selected="selected">-- Seleccione --</option>
        <?php
		    $sucursal = "select idsucursal, left(nombrecomercial,20) from sucursal where estado=1";			
	        $db->imprimirCombo($sucursal,$datoTransaccion['idsucursal']);			
	    ?>
      </select></td>
      <td>&nbsp;</td>
      <td colspan="2" align="right">Doc.:</td>
      <td><input type='text' id="documento" name="documento" size="15" value="<?php 
	  if (isset($_GET['idporcobrar']))
	    echo $datoTransaccion['documento'];	  
	  ?>" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Deudor:</td>
      <td align="left">
      <select name="receptor" id="receptor"  onchange="limpiarDeudor()" style="width:130px; background:#FFF;border:solid 1px #999;">
        <?php
		 $selec = $datoTransaccion['tipodeudor']; 
		 $tipo = array("cliente","proveedor","trabajador");
		 for ($i=0;$i<count($tipo);$i++){
			$atributo = ""; 
			if ($selec == $tipo[$i]){
			$atributo = "selected='selected'";	
			}
			echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
		 }	
		?>
      </select></td>
      <td align="left">
      <?php
	  switch($datoTransaccion['tipodeudor']){
		 case 'cliente':
           $sql = "select left(nombre,15)as 'nombre' from cliente where idcliente=$datoTransaccion[iddeudor]";
        break;
        case 'proveedor':
           $sql = "select left(nombre,15)as 'nombre' from proveedor where idproveedor=$datoTransaccion[iddeudor]";
        break;
        case 'trabajador':
           $sql = "select left(concat(nombre,' ',apellido),15)as 'nombre' from trabajador 
		   where idtrabajador=$datoTransaccion[iddeudor]";
        break;			
       }
	   $persona = $db->arrayConsulta($sql);
	  ?>
      
      
       <input type="text" name="texto" id="texto"  style="width:90%" onKeyUp="tipoBusqueda(event);"
        autocomplete="off" value="<?php echo $persona['nombre'];?>"/>
       <div id="cliente" class="divresultado"></div>                    
       <input type="hidden" name="idpersonarecibida" id="idpersonarecibida" value="<?php echo $datoTransaccion['iddeudor'];?>">      
      </td>
      <td width="5%" ><div id="autoL1" class="autoLoading"></div></td>
      <td width="14%" align="right">Cuenta<span class="rojo">*</span>:</td>
      <td align="left">
      <select name="cuenta" id="cuenta" style="width:130px;background:#FFF;border:solid 1px #999;">
      <option value="" selected="selected">-- Seleccione --</option>
      <?php
		  $sql = "select cuenta,descripcion from tipoconfiguracion where tipo='Por Cobrar';";
		  $db->imprimirCombo($sql,$datoTransaccion['cuenta']);
		 
		?>
      </select></td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Glosa:</td>
      <td colspan="5"><textarea name="glosa" id="glosa" cols="65" rows="2"><?php echo $datoTransaccion['glosa'];?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Vencimiento:</td>
      <td><input type='text' id="fechavencimiento" name="fechavencimiento" class="date" size="10" value="<?php 
	   if (isset($_GET['idporcobrar']))
	  echo $db->GetFormatofecha($datoTransaccion['fechavencimiento'],'-');
	  else
	  echo date('d/m/Y')?>" /></td>
      <td align="right">&nbsp;</td>
      <td colspan="2" align="right">Caja/Banco:
        <input type="radio" id="tipocuenta" name="tipocuenta" value="cuentaCaja" checked="checked"
         onclick="cambiarTipoCuenta(this.value)"/></td>
      <td><select id="cuentacaja" name="cuentacaja" style="width:130px;background:#FFF;border:solid 1px #999;" >
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
      <td align="right">Importe<span class="rojo">*</span>:</td>
      <td>
        <input type='text' id="importe" name="importe" size="15" onkeypress="return soloNumeros(event);" 
        value="<?php 
		if ($transaccion == "modificar") {
			if ($datoTransaccion['moneda'] == "Dolares")
			 echo round(($datoTransaccion['monto']/$datoTransaccion['tipocambio']),2);
			else
			 echo round($datoTransaccion['monto'],2);
		}
		?>"/>
      </td>
      <td align="right">&nbsp;</td>
      <td colspan="2" align="right">Cuenta Contable:
        <input type="radio" id="tipocuenta2" name="tipocuenta" value="cuentaApertura" onclick="cambiarTipoCuenta(this.value)"/></td>
      <td><select id="cuentaapertura" name="cuentaapertura" style="width:130px;background:#FFF;border:solid 1px #999;" >
        <option value="">-- Seleccione --</option>
        <?php		   
		  $sql = "select cuenta,descripcion from tipoconfiguracion where tipo='Por Cobrar Apertura'
		    order by idtipoconfiguracion";
		  $db->imprimirCombo($sql);
		  ?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  </tr>
<tr>
  <td >
  <?php 
	  if (!isset($_GET['idporcobrar'])) {
	   $cuentaT = "cuentaCaja";
	   $tipoCombo = "cuentacaja";
	  } else {
		  if ($datoTransaccion['tipocuenta'] == "cuentaCaja"){
			 $cuentaT = "cuentaCaja";
	         $tipoCombo = "cuentacaja";
		  } else {
			 $cuentaT = "cuentaApertura";
	         $tipoCombo = "cuentaapertura";
		  }		  
	  }
  ?></td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
</tr>
</table>
</form>
</div>
</td></tr></table>
<script>
cambiarTipoCuenta("<?php echo $cuentaT; ?>");
seleccionarRadio("formdatos","tipocuenta","<?php echo $cuentaT;?>");
seleccionarCombo('<?php echo $tipoCombo; ?>','<?php echo $datoTransaccion['cuentacaja']; ?>');
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