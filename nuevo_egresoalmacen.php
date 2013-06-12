<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
 	 $db = new MySQL();
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
     }
	 $estructura = $_SESSION['estructura'];
	 $fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Egreso de Productos','nuevo_egresoalmacen.php'
	 ,'listar_egresoproducto.php');
	  if ($fileAcceso['Acceso'] == "No"){
		header("Location: cerrar.php");	
	  }

	 $transaccion = "insertar";	
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql); 
	 
	 $sql = "select left(tituloegresoalm,30)as 'tituloegresoalm' from impresion where idimpresion = 1";
	 $tituloPrincipal = $db->getCampo('tituloegresoalm', $sql);	 
	 
	 if (isset($_GET['idegresoprod'])){		 
		$sql = "select * from egresoproducto where idegresoprod=".$_GET['idegresoprod']; 
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
<link rel="stylesheet" type="text/css" href="egresoalmacen/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="egresoalmacen/NEgreso.js"></script>
<script>
$(document).ready(function()
{

<?php	
 if (!isset($ModificarRegistro['idegresoprod'])){
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



<form id="form1" name="form1" method="" action="">


<div id="modal" class="contenedorframeID">    
    <div class="modal_interiorframeID"></div>  
	<div class="modal"> 
         <div class="caption_modalNuevo">           
           <div class="posicionClose">
             <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer"  onclick="accion();"></div>
             <div class="titleHeadCaption"> Registro de Datos</div>
           </div>
          <table align="center" width="100%" >
            <tr>
               <th colspan="6"><input type="hidden" id="precioProducto"  /></th>
            </tr>
            <tr>
              <th align="right" colspan="2">Elija un Lote:</th>
              <th width="14%" align="left">
              <select id="idLotes" name="idLotes" style="border:1px solid #333;"></select>
              </th>
              <th width="15%">&nbsp;</th>
              <th align="left">&nbsp;</th>
              <th align="left" >Producto en Almacén</th>
            </tr>
            <tr>
              <th></th>
              <th>Cantidad</th>
              <th>Unidad M</th>
              <th>Precio Und</th>
              <th align="left">Total Precio</th>
              <th width="41%" rowspan="5" align="left">
              
              <div class="divProductosDisponibles">
              <table width="100%" border="0">
                <tr style="background-image:url(iconos/fondo.jpg);">
                  <td width="108" >Fecha  Venc.</td>
                  <td width="59">Lote</td>
                  <td width="78">Cantidad</td>
                  <td width="78">UM</td>
                  <td width="78" style="display:none">Cantidad</td>
                </tr>
                               
                <tbody id="detalleProdDisponible">
                 
                </tbody>              
                
              </table>
              </div>
              
              </th>
            </tr>
            <tr>
              <th width="5%"><input type="radio" id="pselectorU" name="pselectorU" value="UP" checked="checked"/></th>
              <th width="13%">
              <input id="cant" name="cant" onkeyup="calcularUnidadMedida(this.value,'UM');" type="text" 
              size="10" onkeypress=""/></th>
              <th><input id="punidadmedida" name="punidadmedida"  type="text"  size="10" onkeypress="" disabled="disabled"/></th>
              <th><input id="ppreciounitario" name="ppreciounitario"  disabled="disabled" type="text" 
               size="10" onkeyup="calcularUnidadMedida(this.value,'precioU');"/></th>
              <th width="12%"><input id="ptotal" name="ptotal"  type="text"  size="10" onkeypress="" disabled="disabled"/></th>
            </tr>
            <tr>
              <th height="10"><input type="radio" id="pselectorU2" name="pselectorU" value="UA" /></th>
              <th height="10">
              <input id="cantUM" name="cantUM" onkeyup="calcularUnidadMedida(this.value,'UA');" type="text"
                size="10" onkeypress=""/></th>
              <th height="10">
              <input id="punidadalternativa" name="punidadalternativa"  type="text" size="10" onkeypress=""
               disabled="disabled"/></th>
              <th height="10">
              <input id="ppreciounitarioalternativa" name="ppreciounitarioalternativa"  type="text" 
               size="10"  onkeyup="calcularUnidadMedida(this.value,'precioUA');" disabled="disabled"/></th>
              <th height="10">&nbsp;</th>
            </tr>
            <tr>
              <th height="10"><input type="hidden" id="pconversiones" name="pconversiones" /></th>
              <th height="10" colspan="3" align="left"><div id="msjsubnumero" class="mensajeCantidad">Incorrecto</div></th>
              <th height="10">&nbsp;</th>
            </tr>
            <tr>
             <th height="10">&nbsp;</th>
             <th height="10">&nbsp;</th>
             <th height="10"></th>
             <th height="10"></th>
             <th height="10">&nbsp;</th>
            </tr>
          </table>
            <div class="boton1_subventana">
             <input onclick="agrega_celda();" type="button" value="Aceptar" id="aceptar_modal"  class="botonNegro"/>
            </div>
            <div class="boton2_subventana">
             <input onclick="accion();" type="button" value="Cancelar" id="aceptar_modal"  class="botonNegro"/>
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
        <table width="85%" style="margin-top:40px;" align="center">
            <tr>            
            <td width="108"><input type="button" class="botonNegro" value="Ver Reporte"
             onclick="accionPostRegistro();"/></td>
            <td width="91" align="right">Logo:</td>
            <td width="20"><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            <td width="106" align="right">Precios:</td>
            <td width="21"><input type="checkbox" name="Mprecios" id="Mprecios" checked="checked"/></td>
            </tr>
            </table>
        
      </div>
  </div>
</div>

  
    
   
  
<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Inventario > Egreso de Productos </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Egreso de Productos"); 
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
        </td>
        <td colspan="3" align='right'>
    
            <table width="356" border="0">
              <tr>
                <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
                <td width="142">
                  <?php 
                      if (isset($_GET['idegresoprod'])) {
                        echo "Nro.:". $ModificarRegistro['idegresoprod'];
                      }	else {
						echo $db->getNextID("idegresoprod","egresoproducto");   
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
  </tr>
  <tr>
    <td width="27%" align="right">Fecha:</td>
    <td colspan="2"><span class="radio">&nbsp;
      <input name="fecha" type="text" id="fecha" <?php if (isset($ModificarRegistro['idegresoprod'])) echo "disabled='disabled'"; ?>  style="border:solid 1px #999;" size="12" class="date" value="<? 
	   if (isset($_GET['idegresoprod'])){
	    echo $db->GetFormatofecha($ModificarRegistro['fecha'],"-"); 	
	   }
	   else{ 
	    echo date("d/m/Y"); 
	   }
	  ?>"  />
      </span><br />

    </td>
    <td width="17%" align="right">Motivo:</td>
    <td width="27%" ><input type="text" id="motivo" name="motivo" value="<?php echo $ModificarRegistro['motivo'];?>"/></td>
  </tr>
  <tr>
    <td align="right">Sucursal Origen(Producto)<span class="rojo">*</span>:</td>
    <td><select name="almacen" id="almacen" style="width:130px;background:#FFF;border:solid 1px #999;" onchange="limpiarDetalle()">
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
    <td>&nbsp;</td>
    <td align="right">Moneda:</td>
    <td><div id="cuenta_label" align="right" style="display:none">Nro Cuenta: </div>
      <div id="plazo_label" align="right" style="display:none">Plazo: </div>
      <div style="display:none" id="cuenta_div">
        <input name="cuenta" onkeypress="return permite(event,'num')" id="cuenta" type="text" />
      </div>
      <div style="display:none" id="plazo_div">
        <input name="plazo" id="plazo" type="text" />
      </div>
      <select name="moneda" id="moneda" onchange="limpiarDetalle();" style="width:130px;background:#FFF;border:solid 1px #999;">
      <?php
	 $selec = $ModificarRegistro['moneda']; 
	 $tipo = array("Bolivianos");
	 for ($i=0;$i<count($tipo);$i++){
		$atributo = ""; 
		if ($selec == $tipo[$i]){
		$atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
	 }	
	?>        
      </select></td>
    </tr>
  <tr>
    <td align="right">Sucursal Destino(Gasto)<span class="rojo">*</span>:</td>
    <td width="18%"><select name="almacendestino" id="almacendestino" style="width:130px;background:#FFF;border:solid 1px #999;" >
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
	      $db->imprimirComboGrupo($almacen,"","A- ",$ModificarRegistro['idalmacendestino']);			
	   ?>
    </select></td>
    <td width="11%"></td>
    <td align="right"><input type='hidden' id='idRegistro' value='<?php echo $ModificarRegistro['idegresoprod'];?>' />
      Cuenta Contable<span class="rojo">*</span>:</td>
    <td><select name="cuentacontable" id="cuentacontable"  style="background:#FFF;border:solid 1px #999;width:130px;"  >
      <option value="">-- Seleccione --</option>       
      <?php
		  $sql = "select cuenta,descripcion from tipoconfiguracion where tipo='Egreso';";
		  $db->imprimirCombo($sql,$ModificarRegistro['cuentacontable']);
		 
		?>
    </select>    </tr>
  <tr>
    <td align="right"><select name="receptor" id="receptor"  onchange="cambiarDependencias();"
     style="width:87px; background:#FFF;border:solid 1px #999;">
    <?php
	 $selec =  $ModificarRegistro['tipopersona']; 
	 $tipo = array("cliente","proveedor","trabajador","Otros");
	 for ($i=0;$i<count($tipo);$i++){
		$atributo = ""; 
		if ($selec == $tipo[$i]){
		$atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
	 }	
	?>
    </select></td>
    <td><input style="width:80%" type="text" id="texto" name="texto" onclick="this.select()" onkeyup="tipoBusqueda(event);" autocomplete="off"  
    value="<?php echo $ModificarRegistro['nombreasignado'];	?>"/>
      <div id="cliente" class="divresultado"></div>
      <input type="hidden" id="idpersonarecibida" value="<?php echo $ModificarRegistro['idpersona'];?>" />
      </td>
    <td><div id="autoL1" class="autoLoading"></div></td>
    <td align="right">Precio:</td>
    <td>
     <select name="tipoprecio" id="tipoprecio" style="background:#FFF;border:solid 1px #999;width:130px;">
         <option value="costo" >Costo</option>
        <?php
       $sql = "select *from configuracionprecios;";
       $datosprecios = $db->arrayConsulta($sql);
       if ($datosprecios['textoprecio1'] != "")
        echo "<option value='precio1'>$datosprecios[textoprecio1]</option>";
	   if ($datosprecios['textoprecio2'] != "")	
        echo "<option value='precio2'>$datosprecios[textoprecio2]</option>";
	   if ($datosprecios['textoprecio3'] != "")	
        echo "<option value='precio3'>$datosprecios[textoprecio3]</option>";
	   if ($datosprecios['textoprecio4'] != "")	
        echo "<option value='precio4'>$datosprecios[textoprecio4]</option>";
	 ?>
      </select>      
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>  
  </tr>
 </table>

</div>
      <div id="producto" class="producto">    
     
         <table width="100%" border="0">
           <tr>
    <td width="13%"><div align="right"><strong>Producto:</strong></div></td>
    <td width="20%">
      <input type="text"  id="dato" name="dato" autocomplete="off" onkeyup="consultorAutocompletar(event,this.id,this.value)" 
      style="width:170px"/><br>
    <div  id="resultados" style="width:170px;" class="divresultado"></div>
       <input type="hidden" id="codidproducto" />
        </td>
    <td width="56%"><div id="autoL2" class="autoLoading"></div></td>
    <td width="11%">T.C.: <?php
	if ($ModificarRegistro['idegresoprod'])
	 $tc = $ModificarRegistro['tipocambio'];
		
	 echo $tc;?>
     <input id="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" ></td>
  </tr>
</table>
</div>
        
      <div id="cuerpo" class="cuerpo">
          <table width="100%" border="0" id="tabla">
            <tr class="filadetalleui">
              <td width="10" >&nbsp;</td>
              <th width="50"><div align="center">Código</div></th>
              <th width="260" align="center">Descripción</th>
              <th width="40" align="center" style='display:none;'>lote</th>
              <th width="85"><div align="center">Fecha Venc.</div></th>
              <th width="70"><div align="center">Cantidad</div></th>
              <th width="82" align="center">U.M.</th>
              <th width="79"><div align="center">P/Unit.</div></th>
              <th width="82"><div align="center">Total</div></th>
              <th width="20" style='display:none;'>Id </th>
            </tr>
          
            <tbody id="detalleSolicitud">
               <?php
			     if (isset($_GET['idegresoprod'])){
			       $consulta =mysql_query("select p.idproducto,p.nombre,ds.precio as 'precio',date_format(ds.fecha,'%d/%m/%Y') 
				   as 'fecha',ds.cantidad,ds.total as 'total',ds.lote,ds.unidadmedida,ds.iddetalleingreso 
				   from detalleegresoproducto ds,producto p,egresoproducto s
                    where ds.idegresoprod=s.idegresoprod and ds.idproducto=p.idproducto and s.idegresoprod=".$_GET['idegresoprod']);
					$fil = 0;
					while($dato = mysql_fetch_array($consulta)){
					  $color = '#F6F6F6' ;
					  $fil++;				    
				      echo "<tr bgColor='$color'>";
                      echo "  <td align='center'><img src='css/images/borrar.gif'
					   style='cursor:pointer' title='eliminar' onclick='eliminarFila(this)'/></td>";
                      echo "  <td align='center'>$dato[idproducto]</td>";
                      echo "  <td>$dato[nombre]</td>";
					  echo "  <td align='center' style='display:none'>$dato[lote]</td>";
					  echo "  <td align='center'>$dato[fecha]</td>";
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
        <center><div id="literal" style="margin-top:-3px;"></div></center>
     </div>
    
     <div style="margin: 0 auto; width:100.2%; margin-top:2px;position:relative;">
     
         <div id="pie_izq" class="pie_izq" >
           <table width="100%" border="0">
            <tr>
              <td width="14%">Glosa:</td>
              <td width="86%" rowspan="2"><textarea id="glosa" style="width:98%; height:50px;" ><?php echo $ModificarRegistro['glosa'];?></textarea></td>
            </tr>
            <tr>
              <td height="35">&nbsp;</td>
            </tr>
          </table>

        </div>
        
         
         <div id="pie_der" class="pie_der" >
           <table width="99%">
             <tr >
               <td width="8%"><div align="right" ></div></td>
               <td width="29%"><div align="right">TOTAL Bs.:</div></td>
               <td width="63%">
               <input type="text" id="subtotalBS" name="subtotalBS" class="cuadroBS" value="0.00" readonly="readonly"/>
               </td>
             </tr>
             <tr >
               <td><div align="right"></div></td>
               <td ><div align="right">TOTAL $us.:</div></td>
               <td>
               <input type="text" id="subtotalDL" name="subtotalDL" class="cuadroBS" value="0.00" readonly="readonly"/>
               </td>
             </tr>
             <tr>
               <td><div align="right"></div></td>
               <td><div id="totalbs"></div></td>
               <td>&nbsp;</td>
             </tr>
             <tr >
               <td><div align="right"></div></td>
               <td><div id="totalsus"></div></td>
               <td>&nbsp;</td>
             </tr>
           </table>             
       </div>
       
       
     </div>
     
     
      </td></tr>
     </table>
     </form>
<script>
	   setTotal(<? if (isset($ModificarRegistro['monto'])) echo $ModificarRegistro['monto']; else echo 0;?>
	   ,'<? echo $ModificarRegistro['moneda'];?>');
	   transaccion ='<? echo $transaccion;?>';	 	
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