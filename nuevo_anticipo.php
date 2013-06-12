<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
  session_start();
  include('conexion.php');
  include('aumentaComa.php');
  $db = new MYSQL();

  if (!isset($_SESSION['softLogeoadmin'])) {
		 header("Location: index.php");	
  }
  $estructura = $_SESSION['estructura'];
  $fileAcceso = $db->privilegiosFile($estructura['Recursos'],'Anticipo','nuevo_anticipo.php','listar_anticipo.php');
  if ($fileAcceso['Acceso'] == "No") {
	header("Location: cerrar.php");	
  }

  $transaccion = "insertar";
  if (isset($_GET['sw'])) {
	$transaccion = "modificar";	
	$sql = "SELECT * FROM anticipo WHERE idanticipo = ".$_GET['idanticipo'];
	$datoAnticipo = $db->arrayConsulta($sql);  
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
<link rel="stylesheet" type="text/css" href="anticipo/anticipo.css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script src="anticipo/anticipo.js"></script>
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

  function checkclick(id){ 
  if (document.getElementById(id).checked) 
  document.getElementById(id).value=1;
   else document.getElementById(id).value=0;}
  
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115){ //F4
    if ($$("cancelar") != null)
	 location.href = 'listar_anticipo.php#t2';
   }
	
   if(tecla == 113){ //F2
	  $$("enviar").click();  
	}
  }
  
  var $$ = function(id){
	return document.getElementById(id);  
  }

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

<div class="menuTituloFormulario"> Recursos > Anticipo </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Anticipo"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_anticipo.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type="button" onclick="ejecutarTransaccion();" class='botonNegro' id='enviar' value='Guardar [F2]'/>
	<?php 
      if ($fileAcceso['File'] == "Si") {
       echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
       value="Cancelar [F4]" onClick="location.href=&#039listar_anticipo.php#t2&#039"/>';	
      }
    ?> 
    </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idanticipo" name="idanticipo" value="<?php echo $datoAnticipo['idanticipo'];?>" /></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td align="center"><strong>
      <?php 
	 if (isset($_GET['idanticipo'])){
	  echo "Transacción Nº ". $datoAnticipo['idanticipo'];	
	 }	
	?>
    </strong>
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
<tr>
<td colspan='5' align='center'></td>
</tr>
<tr>
<td width="116"></td>
<td width="117" align="right">&nbsp;</td>
<td width="139" align="right">&nbsp;</td>
<td width="115" align="right">&nbsp;</td>
<td width='207' align='center'>TC.:
<?php
   $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
   $tc = $db->getCampo('dolarcompra',$sql); 
   echo $tc;
?> 
 <input id="tipoCambioBs" name="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" />
</td>
</tr>
<tr>
  <td colspan="5"  valign='top'>
    <table width="90%" border="0" align="center" class="bordeContenido">
      <tr>
        <td width="17%">&nbsp;</td>
        <td width="26%">&nbsp;</td>
        <td width="12%">&nbsp;</td>
        <td width="19%">&nbsp;</td>
        <td width="26%">&nbsp;</td>
        </tr>
      <tr>
        <td align="right">Fecha:<span class='rojo'></span></td>
        <td><input type='text' id="fecha" name="fecha"  class="date" onchange="consultarAnticipos()" size="20"
         value="<?php 
                if (isset($datoAnticipo['fecha'])) {
                echo $db->GetFormatofecha($datoAnticipo['fecha'],"-");
                } else {
                echo date("d/m/Y");
                }?>" style="width:80px;"/></td>
        <td>&nbsp;</td>
        <td align="right">Caja/Banco<span class="rojo">*</span>:</td>
        <td><select id="egreso" name="egreso" style="width:50%" class="required">
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
        </tr>
      <tr>
        <td align="right">Sucursal<span class="rojo">*</span>:</td>
        <td><select name="idsucursal" id="idsucursal" style="width:130px;" onchange="consultarTrabajadores()" class="required">
          <option value="" selected="selected">-- Seleccione --</option>
          <?php
            $sql = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1;";
            $db->imprimirCombo($sql,$datoAnticipo['idsucursal']);
          ?>
          </select></td>
        <td>&nbsp;</td>
        <td align="right">Recibo:</td>
        <td><input type="text" name="documento" id="documento" value="<?php echo $datoAnticipo['documento'];?>" size="10"/></td>
        </tr>
      <tr>
        <td align="right">Nombre<span class="rojo">*</span>:</td>
        <td><select id="idtrabajador" name="idtrabajador" style="width:99%;" onchange="consultarSueldo()" class="required">
          <option value=""> -- Seleccione -- </option>
          <?php
		  if (isset($datoAnticipo['idsucursal'])){
	        $sql = "select idtrabajador,left(concat(nombre,' ',apellido),25)as 'sucursal' 
			from trabajador where idsucursal=$datoAnticipo[idsucursal];";
	        $db->imprimirCombo($sql,$datoAnticipo['idtrabajador']);
		  }
	      ?>
          </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td align="right">Glosa:</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td colspan="5" align="center">
        <textarea name="glosa" cols="65" rows="3" id="glosa"><?php echo $datoAnticipo['glosa'];?></textarea>
        </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">Sueldo Básico:</td>
        <td><input type="text" name="sueldobasico" id="sueldobasico" style="width:100px" 
        value="<?php echo number_format($datoAnticipo['sueldobasico'],2);?>" onkeypress="return false;"/></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">Anticipos del Mes:</td>
        <td>
        <?php
		  if (isset($datoAnticipo['idanticipo'])) {
		  $fecha = explode("-", $datoAnticipo['fecha']);
		  $idtrabajador = $datoAnticipo['idtrabajador'];
		  $sql = "select sum(anticipo)as 'anticipo' from anticipo where month(fecha)=$fecha[1]  
		   and year(fecha)=$fecha[0] and idtrabajador=$idtrabajador and estado=1;";
		   $datoAnticipoAnterior = $db->arrayConsulta($sql);
		  }
		?>       
        <input type="text" name="anticiposanteriores" id="anticiposanteriores" style="width:100px" 
        value="<?php echo number_format($datoAnticipoAnterior['anticipo'],2);?>" disabled="disabled"/>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">Anticipo<span class="rojo">*</span>:</td>
        <td><input type="text" name="anticipo" id="anticipo" style="width:100px" class="required number" 
        value="<?php echo $datoAnticipo['anticipo'];?>" onkeypress="return soloNumeros(event)"/></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      </table></td>
</tr>
<tr>
<td colspan='4' >&nbsp;</td>
</tr>
</table>
</form>
</div>

</td></tr></table>
<script> seleccionarCombo('egreso','<?php echo $datoAnticipo['egreso'];?>');</script>
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