<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');
	session_start();
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Ventas'],'Venta de Servicios','nuevo_ventamasiva.php')) {
	    header("Location: cerrar.php");	
	}
	
	$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	$tc = $db->getCampo('dolarcompra',$sql); 
	
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
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="factura/servicio/NVentaMasiva.js"></script>
<style type="text/css">
.bordeContenido {  
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
<div class="menuTituloFormulario"> Ventas > Venta de Servicios </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Ventas'];
   $privilegios = $db->getOpciones($menus, "Venta de Servicios"); 
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
        <input name='enviar' type='button' class='botonNegro' id='enviar'
         value='Guardar [F2]' onclick="realizarTransaccion()" /> 
     </td>
    <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden"  id="idporcobrar" name="idporcobrar" value="<?php echo $_GET['idporcobrar'];?>" /></td>
    <td colspan="3" align='right'><table width="356" border="0">
      <tr>
        <td align="center">
        <strong>Venta Masiva</strong>    
        </td>
        </tr>
      <tr>
        <td align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>  
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
      <td width="11%">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      Verifique que sus parámetros sean los correctos antes de guardar.</td>
      <td align="right">T.C.:<?php
	  if (isset($datoTransaccion['idporcobrar']))
	   $tc = $datoTransaccion['tipocambio'];	  
	   echo $tc;
	   ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="22%">&nbsp;</td>
      <td width="23%">&nbsp;</td>
      <td width="7%">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="34%">&nbsp;</td>
      <td width="3%">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Mes:</td>
      <td>
      <select name="mes" id="mes"  style="width:130px;" onchange="realizarConsulta2();"> 
      <?php
	     $selec = (int)date("m");
		 $texto = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto"
		 ,"Septiembre","Octubre","Noviembre","Diciembre");
		 for ($i = 1; $i <= count($texto); $i++) {
			 if ($selec == $i)
		         echo "<option value='$i' selected='selected'>".$texto[$i-1]."</option>";
			 else
			     echo "<option value='$i'>".$texto[$i-1]."</option>";
		 }	  
	  ?>
      </select></td>
      <td align="right"></td>
      <td align="right">Año:</td>
      <td align="left">
      <select id="anio" name="anio" onchange="realizarConsulta2();" style="width:80px">
        <?php
		 $selec = date("Y");
		 for ($i = 2010; $i <= 2025; $i++) {
			 if ($selec == $i)
		         echo "<option value='$i' selected='selected'>$i</option>";
			 else
			     echo "<option value='$i'>$i</option>";
		 }
		?>
      </select></td>
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
      <td align="right">Moneda:</td>
      <td><select name="moneda" id="moneda" 
       style="width:100px;background:#FFF;border:solid 1px #999;">
        <?php           
		   $selec = $ModificarT['moneda']; 
		   $tipo = array("Bolivianos","Dolares");
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
      <td align="right">Factura:</td>
      <td align="left"><input type='checkbox'  name='facturado' id='facturado' onclick='solicitarNumFactura();' /></td>
      <td align="left">&nbsp;</td>
      <td align="right" >&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
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
		  if ($datoTransaccion['tipocuenta'] == "cuentaCaja") {
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