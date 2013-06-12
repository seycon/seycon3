<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])) {
         header("Location: index.php");	
     }
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
     $fileAcceso = $db->privilegiosFile($estructura['Agenda'],'Informes de Gastos'
	 ,'nuevo_informegastos.php','listar_informegasto.php');
	  if ($fileAcceso['Acceso'] == "No") {
		  header("Location: cerrar.php");	
	  }
	 $numrecibo = $db->getMaxCampo('idinformegasto', 'informegasto');
     $sql = "select left(tituloinformegasto,30)as 'tituloinformegasto' from impresion where idimpresion = 1";
     $tituloPrincipal = $db->getCampo('tituloinformegasto', $sql);
	 $transaccion = "insertar";
	 
	 if (isset($_GET['idinforme'])) {
		$sql = "select *from informegasto where idinformegasto=".$_GET['idinforme'];
		$maestro = mysql_query($sql);
		$maestro = mysql_fetch_array($maestro); 		 
		$transaccion = "modificar";
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
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" href="informegastos/style_gastos.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="informegastos/NInforme.js"></script>
<script src="lib/Jtable.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script>
$(document).ready(function()
{
$("#fecha").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fechadetalle").datepicker({
showOn: "button",
onSelect: function(textoFecha, objDatepicker){
			          $v('input').focus();    
	      },
buttonImage: "css/images/calendar.gif",
autoSize: true,
buttonImageOnly: true,
dateFormat:'dd/mm/yy'
});
});	 
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
<form id="form1" name="form1" method="" action="">
<input type="hidden" value="<? echo generarCodigo("RI-20".date("y")."-") ?>" id="codigo" />
 <div id="overlay_vendido" class="overlays"></div> 
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
            <td align="right">Imprimir Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
            </table>
        
      </div>
  </div>
</div>



<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Agenda > Informes de Gastos </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Informes de Gastos"); 
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
    
 <table id="tablaContenido" class="cssFromGlobal" align="center"> 
 <tr>
 <td>   
 <div id="factura" class="cen">

<table cellpadding='0' cellspacing='0' width='99%' align="center">
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp; 
    <input type="button" name="aceptar" id="aceptar" value="Guardar [F2]" onclick="ejecutarTransaccion();"  class="aceptar"/>
    <?php 
	  if ($fileAcceso['File'] == "Si"){
	   echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="aceptar" id="cancelar" 
	   value="Cancelar [F4]" onClick="salir();"/>';	
	  }
	?> 
    </td>
<td>
</td>
<td colspan="3" align='right'>

        <table width="356" border="0">
          <tr>
            <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
            <td width="142">
			<?php  
			$idinforme=0; 
			if (isset($_GET['idinforme'])) { 
			  $idinforme = $_GET['idinforme'];
			  echo $idinforme;
			} else {
			   echo $db->getNextID("idinformegasto","informegasto");	
			} 
			?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
            <input type="hidden" id="idinforme" name="idinforme" value="<?php echo $idinforme;?>"  />
          </tr>
        </table>     

    </td>
  </tr>
</table>


       
   <div id="datos_factura" class="datos_cliente1">
        <table width="100%" border="0">
            <tr>
              <td width="20%" align="right">&nbsp;</td>
              <td width="20%"><div class="radio"></div></td>
              <td width="15%">&nbsp;</td>
              <td width="22%"><div class="radio"></div></td>
              <td width="12%"> </td>
              <td width="11%">&nbsp;</td>
            </tr>
            <tr>
              <td align="right">Monto de Rendición Bs<span class="rojo">*</span>:</td>
              <td><input type="text" name="rendicion" id="rendicion" onkeypress="return soloNumeros(event)" 
              style="width:50%" value="<?php if (isset($_GET['idinforme'])) echo $maestro['montorendicion'] ?>" /></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><div align="right">Privado:</div></td>
              <td><input type="checkbox" id="privado" name="privado" 
              <?php if (isset($_GET['idinforme'])) echo "checked='checked' "; ?>  value="1" />
                <label for="pagado"></label></td>
          </tr>
            <tr>
              <td align="right">Nro Doc. Rendición:<br />
             </td>
              <td><label for="rendicionnumero"></label>
       <input type="text" id="nrodocumentos" name="nrodocumentos" style="width:50%" 
       value="<?php if (isset($_GET['idinforme'])) echo $maestro['nrodocumentos'] ?>"/></td>
              <td align="right">Fecha</td>
              <td><input name="fecha" type="text" id="fecha" size="10" 
              value="<? if (isset($_GET['idinforme'])) echo $db->GetFormatofecha($maestro['fecha'],'-');
                else echo date("d/m/Y"); ?>"  /></td>
              <td><div align="right">Firma Digital:</div></td>
              <td><input type="checkbox" name="firmadigital" id="firmadigital" />
                </td>
          </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>

</div>

<div id="producto" class="producto">
 <table width="100%" border="0">
  <tr>
    <td width="4%">Fecha:</td>
    <td width="15%"><input name="fechadetalle" type="text" id="fechadetalle" style="width:60%" onkeypress="return false;" /></td>
    <td width="8%" align="right">Descripción:</td>
    <td width="27%"><input  type="text" id="descripcion" name="descripcion"  onfocus="this.select()" style="width:90%" /> 
    </td>
    <td width="4%" align="right">DOC.</td>
    <td width="13%">
      <input name="documento" type="text" id="documento" onfocus="this.select()" style="width:80%"/></td>
    <td width="3%" align="right">Bs.:</td>
    <td width="14%"><label for="montobs"></label>
      <input name="montobs" type="text" id="montobs" style="width:80%"
       onkeypress="return soloNumeros(event)" onkeyup="enterInput(event)"/></td>
    <td width="12%">
      <input type="button" onclick="insertarNewItem('detalleTransaccion');"
       name="agregar" value="Registrar [Enter]" class="aceptar"  /></td>
  </tr>
</table>
</div>
 
      
  <div id="cuerpo" class="cuerpo">
      <table width="100%" border="0" id="tabla" style="margin-top:5px;">
        <tr class="filadetalleui">
          <td width="15" >&nbsp;</td>
          <th width="69"><div align="center">Nro.</div></th>
          <th width="70"><div align="center">Fecha</div></th>
          <th width="492"><div align="center">Descripción</div></th>
          <th width="108"><div align="center">DOC.</div></th>
          <th width="100"><div align="center">IMPORTE</div></th>
        </tr>
        <tbody id="detalleTransaccion">
        <?php 
        $totalTransaccion = 0;
        if (isset($_GET['idinforme'])) {
          $sql = "select *from detalleinforme where idinformegasto=".$_GET['idinforme']." order by iddetalleinforme asc";
          $detalle = mysql_query($sql);
          $i = 0;
        
         while ($dato = mysql_fetch_array($detalle)) {
             $totalTransaccion = $totalTransaccion + $dato['importe'];
             $i++;
             echo "
              <tr >
                <td width='15' ><img src='css/images/borrar.gif' style='cursor:pointer' onclick='eliminarFila(this)'/></td>
                <td width='69' align='center'>$i</td>
                <td width='70' align='center'>".$db->GetFormatofecha($dato['fecha'],'-')."</td>
                <td width='492' align='left'>$dato[detalle]</td>
                <td width='108' align='center'>$dato[documento]</td>
                <td width='100' align='center'>".number_format($dato['importe'],2)."</td>
             </tr>
             ";				 
         }			
        }
        ?>
        </tbody>
         
      </table> 
</div>
<div class="pie_informe">
  <table width="100%" border="0">
    <tr>
      <td width="18%" align="right">Comentario:</td>
      <td width="48%"><label for="comentario"></label>
      <input name="comentario" type="text" id="comentario" style="width:70%"
       value="<?php if (isset($_GET['idinforme'])) echo $maestro['comentario'] ?>"/></td>
      <td width="17%" align="right"><strong>TOTAL:</strong></td>
      <td width="17%">
      <input type="text" value="" size="15" id="total_ingreso" name="total_ingreso" readonly="readonly"/> 
      </td>
    </tr>
 </table>
</div> 
</div>

</td></tr></table>  
</form>
<script>
	seleccionarCheck('form1','firmadigital','<?php echo $maestro['firmadigital'] ?>');
	cargarTotales(<?php  echo $totalTransaccion; ?>);
	transaccion = "<?php echo $transaccion;?>";
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