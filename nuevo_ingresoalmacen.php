<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 $db = new MySQL();
	 if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: index.php");	
     }
	 $estructura = $_SESSION['estructura'];
     $fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Ingresar Productos'
	 ,'nuevo_ingresoalmacen.php','listar_ingresoproducto.php');
	 if ($fileAcceso['Acceso'] == "No") {
		header("Location: cerrar.php");	
	 }	 
	 $transaccion = "insertar";	
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql); 
	 
	 $sql = "select left(tituloingresoalm,30)as 'tituloingresoalm' from impresion where idimpresion = 1";
	 $tituloPrincipal = $db->getCampo('tituloingresoalm', $sql);	 
	 
	 if (isset($_GET['idingresoprod'])) {		 
		$sql = "select * from ingresoproducto where idingresoprod=".$_GET['idingresoprod']; 
		$ModificarRegistro = $db->arrayConsulta($sql);		 
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
<link rel="stylesheet" type="text/css" href="ingresoalmacen/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="ingresoalmacen/NIngreso.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script>
$(document).ready(function()
{

<?php	
 if (!isset($ModificarRegistro['idingresoprod'])) {
  echo "	
  $('#fecha').datepicker({
  showOn: 'button',
  buttonImage: 'css/images/calendar.gif',
  buttonImageOnly: true,
  dateFormat: 'dd/mm/yy' });";
   }
?>


$("#fvencimiento").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
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



<!-- Ventana de Factura -->
<div id="subventana" class="contenedorFacturaframeID"> 
 <div class="modal_interiorFacturaframeID"></div> 
 <div class="subVentana">
 <div class="caption_modalFacturaCabecera">
  <div class="posicionCloseFacturaSub" onclick="cerrarSubVentana();">
  <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="cerrarSubVentana();"></div>
  <div class="titleHeadCaption">Datos Factura</div>
 </div>          
          
    <table width="100%" border="0">           
      <tr>
        <td colspan="2">
        <strong>DIA<br /></strong>              
         <input name="diaF" type="text" id="diaF" size="12" onkeypress="return cambiarFoco(event)"  />
        <input type="hidden" id="ItemF" name="ItemF" value=""  /></td>
      </tr>
       
      <tr>
        <td width="40%" align="left"><strong>NIT<br />
        </strong>
        <input name="nitF" type="text" id="nitF" size="13"
         onchange="recuperaNombre(this.value,'ingresoalmacen/DIngreso.php')" onkeypress="return soloNumeros(event)" /></td>
        <td width="60%" align="left">
        <input name="ItemTabla" type="hidden" id="ItemTabla" size="13" onkeypress="return soloNumeros(event)" />
        </td>
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
          <input name="excentoF" onkeypress="return soloNumeros(event)" type="text" id="excentoF" style="width:90%" />
        </td>               
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
   <div class="boton_subventanaGuardar">
   <input type="button" name="Guardar" id="Guardar" value="Guardar" class="botonNegro"  onclick="insertarNewFactura();"/>
   </div>
   <div class="boton_subventanaCancelar">
   <input onclick="cerrarSubVentana();" type="button" value="Cancelar" id="aceptar_modal"  class="botonNegro"/>
   </div>
  </div>    
 </div>




<div id="overlays" class="overlays"></div>
<div id="overlay" class="overlays"></div>
<div id="gif" class="gifLoader"></div>


<!-- Ventana de Advertencia -->
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




<form id="form1" name="form1" method="" action="">

 
  <div class="contenedorframeID" id="modal">
   <div class="modal_interiorframeID"></div>
	<div  class="modal"> 
      <div class="caption_modalCabecera">
           <div class="posicionClose">
           <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer"  onclick="accion();"></div>
           <div class="titleHeadCaption"> Registro de Datos</div></div>
          <table align="center" width="100%" >
            <tr>
               <th colspan="8"><input type="hidden" id="precioProducto"  /></th>
            </tr>
            <tr>
              <th colspan="2" align="right">&nbsp;</th>
              <th align="left"><div id="ptextounidad"></div></th>
              <th align="left"><div id="ptextounidadalternativa"></div></th>
              <th align="right">&nbsp;</th>
              <th align="left">&nbsp;</th>
              <th align="left">&nbsp;</th>
              <th align="left">&nbsp;</th>
            </tr>
            <tr>
              <th colspan="2" align="right">Disponible:</th>
              <th width="11%" align="left">
              <input type="text" id="Pdisponible" name="Pdisponible" style="width:60px;"  disabled="disabled"/></th>
              <th width="13%" align="left">
              <input type="text" id="Pdisponible2" name="Pdisponible2" style="width:60px;" disabled="disabled"/></th>
              <th width="17%" align="right">Lote:</th>
              <th width="19%" align="left">
              <input type="text" id="plote" name="plote" style="width:90px;" />
              </th>
              <th width="7%" align="left"></th>
              <th width="19%" align="left">
              
              <input type="text" id="pfvencimiento" name="pfvencimiento" 
               style="width:80px;display:none;" value="<?php echo date("d/m/Y");?>"/>
              </th>
            </tr>
            <tr>
              <th colspan="2">&nbsp;</th>
              <th colspan="2">&nbsp;</th>
              <th>&nbsp;</th>
              <th colspan="3">&nbsp;</th>
            </tr>
            <tr>
              <th colspan="2" align="right">Ingreso</th>
              <th colspan="2">Cantidad</th>
              <th>Tipo Und.</th>
              <th>Precio Und.</th>
              <th colspan="2" align="left">Total</th>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th align="right">
              <input type="radio" id="pselectorU" name="pselectorU" value="UP" checked="checked"/></th>
              <th colspan="2">
              <input id="cant" name="cant" onkeyup="calcularUnidadMedida(this.value,'UM');" type="text" size="12" 
              onkeypress="return soloNumeros(event);"/></th>
              <th>
              <input id="punidadmedida" name="punidadmedida"  type="text"  size="12" onkeypress="" disabled="disabled"/></th>
              <th>
              <input id="ppreciounitario" name="ppreciounitario"  type="text"  size="12" 
              onkeyup="calcularUnidadMedida(this.value,'precioU');"/></th>
              <th colspan="2" align="left">
              <input id="ptotal" name="ptotal"  type="text"  size="12" onkeypress="" disabled="disabled"/></th>
            </tr>
            <tr>
              <th width="7%">&nbsp;</th>
              <th width="7%" align="right"><input type="radio" id="pselectorU" name="pselectorU" value="UA" /></th>
              <th colspan="2">
              <input id="cantUM" name="cantUM" onkeyup="calcularUnidadMedida(this.value,'UA');" 
              type="text"  size="12" onkeypress="return soloNumeros(event);"/></th>
              <th><input id="punidadalternativa" name="punidadalternativa"  type="text" size="12" 
              onkeypress="" disabled="disabled"/></th>
              <th><input id="ppreciounitarioalternativa" name="ppreciounitarioalternativa"  type="text"  size="12"
              disabled="disabled"  onkeyup="calcularUnidadMedida(this.value,'precioUA');"/></th>
              <th>&nbsp;</th>
              <th><input type="hidden" id="pconversiones" name="pconversiones" /></th>
            </tr>
            <tr>
             <td height="25" align="left"></td>
             <th height="25" align="left"></td>
             <td height="25" colspan="3" align="left">
             <div id="msjsubnumero" class="mensajeCantidad">Incorrecto</div>
             </td>
             <td height="25" align="left"></td>
             <td height="25" align="left"></th>
             <td height="25" align="left"></td>
            </tr>
          </table>
          
          <div class="boton1_subventana">
          <input onclick="ingresoProducto();" type="button" value="Aceptar" id="aceptar_modal"  class="botonNegro"/></div>
          <div class="boton2_subventana">
          <input onclick="accion();" type="button" value="Cancelar" id="aceptar_modal"  class="botonNegro"/></div>
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
         <table width="311" style="margin-top:40px;" align="center">
            <tr>            
            <td><input type="button" value=" Ver Reporte " onclick="accionPostRegistro();" class="botonNegro"/></td>
            <td align="right">Imprimir Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
         </table>   
        
      </div>
  </div>
</div>



    

<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Inventario > Ingresar Productos </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Ingresar Productos"); 
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

<table cellpadding='0' cellspacing='0' width='99%' align="center" class="contemHeaderTop">
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp; 
    <input type="button" value="Guardar [F2]" id="vender" onclick="enviarMaestro()" class="botonNegro" />
    <?php 
	if ($fileAcceso['File'] == "Si"){
	 echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" 
	 id="cancelar" style="width:90px;" value="Cancelar [F4]" onclick="salir();" />';	
	}
	?> 
    </td>
<td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
<input type="hidden" id="idalmacen" name="idalmacen" value="<?php echo $datoAlmacen['idalmacen'];?>" /></td>
<td colspan="3" align='right'>

  <table width="356" border="0">
    <tr>
      <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
      <td width="142">
        <?php 
            if (isset($_GET['idingresoprod'])) {
              echo $ModificarRegistro['nroingresoprod'];
            }	 else {
              echo $db->getNextID("nroingresoprod","ingresoproducto");  
            }
        ?>
       </td>
    </tr>
    <tr>
      <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
      <input type="hidden" id="tc" value="<? echo $tc?>" />
    </tr>
  </table>     

    </td>
  </tr>
</table>


         
        
       
 <div id="datos_factura" class="datos_cliente1">
  <table width="100%" border="0">
  <tr>
    <td align="right">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td >&nbsp;</td>
    <td height="25" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="19%" align="right">Fecha:</td>
    <td colspan="2"><span class="radio">&nbsp;
      <input name="fecha" type="text" id="fecha" <?php if (isset($ModificarRegistro['idingresoprod'])) echo "disabled='disabled'"; ?>  style="border:solid 1px #999;" size="12" class="date" value="<? 
	   if (isset($_GET['idingresoprod'])){
	    echo $db->GetFormatofecha($ModificarRegistro['fecha'],"-"); 	
	   }
	   else{ 
	    echo date("d/m/Y"); 
	   }
	  ?>"  />
      </span><br />

    </td>
    <td width="19%" align="right">Comprobante:</td>
    <td width="18%" >
    <select name="tipoingreso" id="tipoingreso"  style="width:130px;background:#FFF;border:solid 1px #999;">
      <option value="NotaVenta" >Nota Venta</option>
      <option value="NotaVentaProducto" >Nota Venta Producto</option>
      <option value="Factura" >Factura</option>
      <option value="Proforma" >Proforma</option>
      <option value="Otro" >Otro</option>
    </select></td>
    <td width="18%" height="25" align="left">Nro.:<input type="text" id="facproveedor" size="8" name="facproveedor"
      value="<?php echo $ModificarRegistro['facproveedor'];?>"/>
      </td>
  </tr>
  <tr>
    <td align="right">Sucursal<span class="rojo">*</span>:</td>
    <td colspan="2" align="left"><select name="almacen" id="almacen"
     style="width:170px;background:#FFF;border:solid 1px #999;" onchange="limpiarDetalle()">
      <option value="" selected="selected">-- Seleccione --</option>
      <?php	
		  $almacen = "select  left(sl.nombrecomercial,40),a.idalmacen, left(a.nombre,20) 
          from almacen a,sucursaltrabajador s,sucursal sl,usuario u  
          where a.sucursal=s.idsucursal 
          and u.idtrabajador=s.idtrabajador
          and s.idsucursal=sl.idsucursal  
          and a.estado=1 
          and u.idusuario=$_SESSION[id_usuario] 
          order by sl.nombrecomercial;";		
          $db->imprimirComboGrupo($almacen,"","A- ",$ModificarRegistro['idalmacen']);				
	   ?>
    </select></td>
    <td align="right">Moneda:</td>
    <td><div id="cuenta_label" align="right" style="display:none">Nro Cuenta: </div>
      <div id="plazo_label" align="right" style="display:none">Plazo: </div>
      <div style="display:none" id="cuenta_div">
        <input name="cuenta" onkeypress="return permite(event,'num')" id="cuenta" type="text" />
      </div>
      <div style="display:none" id="plazo_div">
        <input name="plazo" id="plazo" type="text" />
      </div>
    <select name="moneda" id="moneda" onchange="limpiarDetalle();" style="width:130px;background:#FFF;border:solid 1px #999;" > 
    
    <?php
	 $selec = $ModificarRegistro['moneda']; 
	 $tipo = array("Bolivianos");
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
    <td align="right">
    <select name="receptor" id="receptor"  onchange="cambiarDependencias();"
     style="width:100px; background:#FFF;border:solid 1px #999;">
      <?php
	 $selec =  $ModificarRegistro['receptor']; 
	 $tipo = array("cliente","proveedor","trabajador","otros");
	 for ($i=0;$i<count($tipo);$i++){
		$atributo = ""; 
		if ($selec == $tipo[$i]){
		$atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
	 }	
	?>    
    </select></td>
    <td width="19%"  height="25">
     
      &nbsp;<input style="width:90%" type="text" id="texto"  onkeyup="tipoBusqueda(event,this.id)" 
       value="<?php echo $ModificarRegistro['nombreasignado'];?>"/>
      <div id="cliente" class="divresultado" style="width:180px;"></div>
     <input type="hidden" id="idpersonarecibida" value="<?php echo $ModificarRegistro['idpersonarecibida'];?>" />
    
    </td>
    <td width="7%"><div id="autoL2" class="autoLoading"></div></td>
    <td align="right"><input type='hidden' id='idRegistro' value='<?php echo $ModificarRegistro['idingresoprod'];?>' />
      Cuenta Contable:</td>
    <td><select name="cuentacontable" id="cuentacontable" disabled="disabled"
     style="background:#FFF;border:solid 1px #999;width:130px;"  >
        <option value="">-- Seleccione --</option>
      <?php
		  $sql = "select cuenta,descripcion from tipoconfiguracion where tipo='Ingreso';";
		  $db->imprimirCombo($sql,$ModificarRegistro['cuentacontable']);
		  
		?>
    </select>        
    <td><input type="checkbox" id="tipocheck" value="
			   <?php if (isset($ModificarRegistro['devolucion'])) 
			   echo $ModificarRegistro['devolucion'];
			   else
			   echo "0";
			   ?>"  onclick="setDevolucion(this.checked)"/>
               Habilitar
   </td>            
   </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td  height="25">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>  
    <td align="center">  
  </tr>
  </table>

</div>
<div id="producto" class="producto">         
 <table width="100%" border="0">
   <tr>
    <td width="13%"><div align="right"><strong>Producto:</strong></div></td>
    <td width="17%">
      <input type="text"  id="dato"  autocomplete="off" onkeyup="autocompletar(event,this.id)"/><br>
      <div  id="resultados"  class="divresultado" style="width:190px;">
      </div>
       <input type="hidden" id="codidproducto" />
        </td>
    <td width="49%"><div id="autoL1" class="autoLoading"></div></td>
    <td width="11%">      T.C.: <?php
	if (isset($ModificarRegistro['idingresoprod']))
	 $tc = $ModificarRegistro['tipocambio'];
	
	 echo $tc;?>
      <input id="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" /></td>
    <td width="10%"><input name='enviar2' type='button' class='botonNegro' value='Factura' onclick="openSubVentana();" /></td>
  </tr>
</table>
</div>
        
      <div id="cuerpo" class="cuerpo">
          <table width="100%" border="0" id="tabla">
             <tr class="filadetalleui">
              <th width="10" >&nbsp;</th>
              <th width="50"><div align="center">Código</div></th>
              <th width="260" align="center">Descripción</th>
              <th width="40" align="center" style="display:none;">lote</th>
              <th width="85" style="display:none;"><div align="center">Fecha Venc.</div></th>
              <th width="70"><div align="center">Cantidad</div></th>
              <th width="82" align="center">U.M.</th>
              <th width="79"><div align="center">P/Unit.</div></th>
              <th width="82"><div align="center">Total</div></th>
              <th width="40" align="center" style="display:none;" >IDtransaccion</th>
            </tr>
      
            <tbody id="detalleSolicitud">
               <?php
			   $totalGeneral = 0;
			     if (isset($_GET['idingresoprod'])) {				 
					 
			       $consulta =mysql_query("select ds.iddetalleingreso,p.idproducto,p.nombre
				   ,ds.precio as 'precio',date_format(ds.fecha,'%d/%m/%Y') 
				   as 'fecha',ds.cantidadingresada as 'cantidad',
				   ds.total as 'total',ds.unidadmedida,ds.lote from detalleingresoproducto ds,producto p,ingresoproducto s
                    where ds.idingresoprod=s.idingresoprod and ds.idproducto=p.idproducto 
					and ds.estado=1 and s.idingresoprod=".$_GET['idingresoprod']
					." order by ds.iddetalleingreso");
					$fil = 0;
					while($dato = mysql_fetch_array($consulta)){
					  $color = '#F6F6F6' ;
					  $totalGeneral = $totalGeneral + $dato['precio']*$dato['cantidad'];
					  $fil++;				    
				      echo "<tr bgColor='$color'>";
                      echo "  <td align='center'>
					  <img src='css/images/borrar.gif' style='cursor:pointer' title='eliminar'
					   onclick='getReferencia(this)'/></td>";
                      echo "  <td align='center'>$dato[idproducto]</td>";
                      echo "  <td>$dato[nombre]</td>";
					  echo "  <td style='display:none'>$dato[lote]</td>";
					  echo "  <td style='display:none' align='center'>$dato[fecha]</td>";
				      echo "  <td align='center'>".$dato['cantidad']."</td>";
					  echo "  <td align='center'>$dato[unidadmedida]</td>";
                      echo "  <td align='center'>".number_format($dato['precio'],4)."</td>";
                      echo "  <td align='center'>".number_format($dato['precio']*$dato['cantidad'],4)."</td>";
  				      echo "  <td align='center' style='display:none'>$dato[iddetalleingreso]</td>";
                      echo "</tr>";
					}
				 }
			   ?>
            
            </tbody>
             
          </table>
                  
</div>
        
     </div>
    
     <div style="position:relative;margin:0 auto; width:100.2%; margin-top:2px;">
     
         <div id="pie_izq" class="pie_izq" >
           <table width="100%" border="0">
            <tr>
              <td width="14%">Glosa<strong>:</strong></td>
              <td width="86%" rowspan="2">
              <textarea id="glosa" style="width:90%; height:50px;" ><?php echo $ModificarRegistro['glosa'];?></textarea>
              </td>
            </tr>
            <tr>
              <td height="35">&nbsp;</td>
            </tr>
          </table>
          
        </div>
        
        <div id="pie_centro" class="pie_centro" >
          <table width="100%">
             <tr class="filadetalleui">
               <th colspan="3" align="center">Forma de Pago</th>
               </tr>
             <?php
			     $atributo = "";
			     if (isset($ModificarRegistro['devolucion'])) {
					if ($ModificarRegistro['devolucion'] == 1) {
					    $atributo = "disabled='disabled'";	
					}
				 }
			 ?>  
               
             <tr >
               <td width="20%" align="right">Efectivo:</td>
               <td width="33%">
               <input type="text" <?php echo $atributo;?> id="efectivo" name="efectivo" style="width:80%" 
               value="<?php echo $ModificarRegistro['efectivo'];?>" onkeyup="calcularCredito(this.value);"/></td>
               <td width="34%"><select id="caja" name="caja"
                style="position:relative;background:#FFF;border:solid 1px #999;left:-4px;width:110px;" 
               class="required" <?php echo $atributo;?>>
                 <option value="">- Seleccione -</option>
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
		  
		  
		  $codigo = ($ModificarRegistro['idingresoprod'] == "") ? 0 : $ModificarRegistro['idingresoprod'];
		  $sql = "
				select day(fechadeemision)as 'dia',numdenitproveedor as 'nit',nomrazonsociprove as 'razonsocial',
                numfacturaopoliza as 'factura',numautorizacion as 'autorizacion',
                codigodecontrol,totalfactura,totalice,importeneto,importeexcento,creditofiscal from 
                librocomprasiva where idtransaccion=$codigo and transaccion='Ingreso Almacen';
				";
				$datoFactura = $db->arrayConsulta($sql);
		  ?>
               </select></td>
             </tr>
             <tr>
               <td align="right">Crédito:</td>
               <td><input type="text" id="credito" name="credito" disabled="disabled" style="width:80%;" 
               value="<?php echo $ModificarRegistro['diascredito'];?>"/></td>
               <td><input type="text" id="fvencimiento" name="fvencimiento" style="width:80px;" class="date"
                value="<?php echo $db->GetFormatofecha($ModificarRegistro['fechavencimiento'],"-");?>"/></td>
             </tr>
             <tr >
               <td align="right">&nbsp;</td>
               <td align="left"></td>
               <td align="right">&nbsp;</td>
               </tr>
           </table>      
        </div>
        
         <div id="pie_der" class="pie_der" >
           <table width="99%">
             <tr >
               <td><div align="right">Sub Total:</div></td>
               <td width="57%">               
                 <input type="text" name="subtotalBS" id="subtotalBS"  class="cuadroBS" readonly="readonly"/>
               </td>
             </tr>
             <tr >
               <td align="right">                 Costo Op.:</td>
               <td><span class="session_pieDivisoria">
                 <input type="text" id="costooperativo" name="costooperativo" style="width:70%;" 
                 value="<?php echo $ModificarRegistro['costooperativo'];?>" 
                 onkeyup="cambiarTotal(this.value)" />
               </span></td>
             </tr>
             <tr >
               <td align="right">                 Total Bs.</td>
               <td class="regionTotal">
                 <input type="text" name="subtotalDL" id="subtotalDL" value="" class="cuadroBS" readonly="readonly"/>
               </td>
             </tr>
           </table>             
       </div>
       
       
     </div>
     
     
      </td></tr>
     </table>
     </form>
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
<script>
	   seleccionarCombo("caja",'<? echo $ModificarRegistro['caja'];?>');
	   seleccionarCombo("tipoingreso",'<? echo $ModificarRegistro['tipoingreso'];?>');	   
	   setTotal(<? if (isset($ModificarRegistro['monto'])) echo $totalGeneral; else echo 0;?>
	   ,'<? echo $ModificarRegistro['moneda'];?>');
	   transaccion ='<? echo $transaccion;?>';	 		   
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