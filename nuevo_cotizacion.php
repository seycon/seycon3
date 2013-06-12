<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: index.php");	
     }
	 include('conexion.php');
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
	 $fileAcceso = $db->privilegiosFile($estructura['Ventas'],'Cotización','nuevo_cotizacion.php','listar_cotizacion.php');
	 if ($fileAcceso['Acceso'] == "No") {
		header("Location: cerrar.php");	
	 }
	 
	 $transaccion = "insertar";	
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql); 
	 
	 $sql = "select left(titulocotizacion,30)as 'titulocotizacion' from impresion where idimpresion = 1";
     $tituloPrincipal = $db->getCampo('titulocotizacion', $sql); 
	 
	 if (isset($_GET['idcotizacion'])) {		 
		$sql = "select * from cotizacion where idcotizacion=".$_GET['idcotizacion']; 
		$ModificarT = $db->arrayConsulta($sql);		 
	    $transaccion = "modificar";	
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
<link rel="stylesheet" type="text/css" href="cotizaciones/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="cotizaciones/NCotizacion.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script>
$(document).ready(function()
{
$("#fecha").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#tiempocredito").datepicker({
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
    <div class="caption_modalCabecera">
     <div class="posicionCloseSub">
     <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer"  onclick="accion();"></div>
     <div class="titleHeadCaption"> Registro de Datos</div>
    </div>
    <table align="center" width="100%" style="margin-top:27px;">
    <tr>
      <th colspan="3"><input type="hidden" id="precioProducto"  /></th>
    </tr>
    <tr>
       <th width="40%" align="right">Cantidad:</th>
       <th width="30%" align="left"><input id="cant" name="cant" onkeyup="eventoIngresoCantidad(event)" 
            type="text" value="1" size="12" onkeypress="return soloNumeros(event);"/></th>
       <th width="30%" align="left"><div id="msjsubnumero" class="mensajeCantidad">Incorrecto</div></th>
    </tr>
    <tr>
       <th height="10" colspan="3"></th>
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

<div class="menuTituloFormulario"> Ventas > Cotización </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Ventas'];
   $privilegios = $db->getOpciones($menus, "Cotización"); 
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
      
<table id="tablaContenido" class="cssFromGlobal" align="center"> <tr><td>
   

<div id="factura" class="cen">

<table cellpadding='0' cellspacing='0' width='99%' align="center" class="contemHeaderTop"> 
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp; 
      <input type="button" value="Guardar [F2]" id="vender" onclick="enviarMaestro()" class="botonNegro" />
	  <?php 
        if ($fileAcceso['File'] == "Si") {
         echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
		 style="width:90px;" value="Cancelar [F4]" onclick="salir();"/>';	
        }
      ?> 
    </td>
<td></td>
<td colspan="3" align='right'>

        <table width="356" border="0">
          <tr>
            <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
            <td width="142">
            <?php 
				if (isset($_GET['idcotizacion'])) {
				  echo $_GET['idcotizacion'];
				} else {
				  echo $db->getNextID("idcotizacion","cotizacion");	
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
    <td width="12%">&nbsp;</td>
    <td><span class="radio">&nbsp;</span><br />

    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='hidden' id='idTransaccion' value='<?php echo $ModificarT['idcotizacion'];?>' /></td>
    <td width="11%">&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Fecha:</td>
    <td width="20%"><span class="radio">
      <input name="fecha" type="text" id="fecha"  style="border:solid 1px #999;" size="10" class="date" value="<? 
	   if (isset($_GET['idcotizacion'])){
	    echo $db->GetFormatofecha($ModificarT['fecha'],"-"); 	
	   }else{ 
	    echo date("d/m/Y"); 
	   }
	  ?>"  />
    </span></td>
    <td width="3%">&nbsp;</td>
    <td width="17%" align="right">Fecha de Entrega:</td>
    <td width="19%">
    
      <select name="tiempoentrega" id="tiempoentrega"
       style="width:80%;background:#FFF;border:solid 1px #999;">
       <?php
		 $selec = $ModificarT['tiempoentrega']; 
		 $tipo = array("Inmediato", "A cordinar");
 		 $textos = array("Inmediato", "A cordinar");
		 for ($i = 0; $i < count($tipo); $i++) {
			$atributo = ""; 
			if ($selec == $tipo[$i]) {
			    $atributo = "selected='selected'";	
			}
			echo "<option value='$tipo[$i]' $atributo>".ucfirst($textos[$i])."</option>";
		 }	
		?>
    </select>
      
      </td>
    <td align="right">Moneda:</td>
    <td><div id="cuenta_label" align="right" style="display:none">Nro Cuenta: </div>
      <div id="plazo_label" align="right" style="display:none">Plazo: </div>
      <div style="display:none" id="cuenta_div">
        <input name="cuenta" onkeypress="return permite(event,'num')" id="cuenta" type="text" />
      </div>
      <div style="display:none" id="plazo_div">
        <input name="plazo" id="plazo" type="text" />
      </div>
      <select name="moneda" id="moneda" onchange="limpiarDetalle();" 
      style="width:70%;background:#FFF;border:solid 1px #999;">
        <?php          
		  $selec = $ModificarT['moneda'] ; 
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
  <?php
     if (isset($ModificarT['idcliente'])) {
		$sql = "select c.nombre as 'cliente' from cliente c where c.idcliente=$ModificarT[idcliente];";	
		$datoCliente = $db->arrayConsulta($sql); 
	 }
  ?>  
  <tr>
    <td align="right">Empresa<span class="rojo">*</span>:</td>
    <td align="left">    
    <input  type="text" id="texto" name="texto" style="width:80%"
     onclick="this.select()" onkeyup="tipoBusqueda(event);" autocomplete="off"  
    value="<?php echo $datoCliente['cliente'];	?>"/>
    <div id="clienteResult" class="divresultado"></div>
    <input type="hidden" id="cliente" name="cliente" value="<?php echo $ModificarT['idcliente'];?>" />
    
    
    </td>
    <td align="left"><div id="autoL2" class="autoLoading"></div></td>
    <td align="right">Sucursal<span class="rojo">*</span>:</td>
    <td><select name="almacen" id="almacen" style="width:80%;background:#FFF;border:solid 1px #999;">
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
	      $db->imprimirComboGrupo($almacen,"","A- ",$ModificarT['idalmacen']);			
	   ?>
    </select></td>
    <td align="right">Precio:</td>
    <td><select name="tipoprecio" id="tipoprecio" style="background:#FFF;border:solid 1px #999;width:70%;">
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
    <td align="right">Contacto:</td>
    <td><input type="text" id="contacto" name="contacto" style="width:80%" value="<?php echo $ModificarT['contacto'];?>"/></td>
    <td>&nbsp;</td>
    <td align="right">Validez propuesta:</td>
    <td>
    
    <select name="validez" id="validez" style="width:80%;background:#FFF;border:solid 1px #999;">
      <?php          
		 $selec = $ModificarT['validez'] ; 
  	     $tipo = array("5 Dias","10 Dias","15 Dias","20 Dias","25 Dias");
	     for ($i=0; $i<count($tipo); $i++) {
		  $atributo = ""; 
		   if ($selec == $tipo[$i]){
		    $atributo = "selected='selected'";	
		   }
		  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
	     }
		  
      ?>
    </select>
    
    </td>
    <td align="right">Forma de Pago:</td>
    <td>
    <select name="formapago" id="formapago" onchange="bloquearForma(this.value)" 
     style="background:#FFF;border:solid 1px #999;width:50%;">
     <?php
	 $selec = $ModificarT['formapago']; 
	 $tipo = array("efectivo", "credito");
	 for ($i=0; $i<count($tipo); $i++) {
		$atributo = ""; 
		if ($selec == $tipo[$i]){
		    $atributo = "selected='selected'";	
		}
		echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
	 }	
	 $atributo = "disabled='disabled'";
	 if ($selec == "credito") {
		 $atributo = ""; 
	 }
	 
	?>      
    </select>   
    <select name="tiempocredito" id="tiempocredito"  <?php echo $atributo; ?> 
    style="background:#FFF;border:solid 1px #999;width:25%;" >
      <?php
	  $tiempo = $ModificarT['tiempocredito'];
	  for ($i=0; $i<=31; $i++){
		$atributo = "";  
		if ($tiempo == $i) 
		 $atributo = "selected='selected'"; 
        echo "<option value='$i' $atributo>$i</option>";
	  }
      ?>
    </select>  </tr>
  <tr>
    <td align="right">Presentación:</td>
    <td>
     <select name="carta" id="carta" style="position:relative;width:80%;background:#FFF;left:-5px;border:solid 1px #999;">
      <option value="" selected="selected">-- Seleccione --</option>
      <?php
		  $sql = "select idcarta,left(titulo,20)as 'titulo' from
		   carta where tipo='Cotizacion' and estado=1;";
		  $db->imprimirCombo($sql, $ModificarT['idcarta']);		
	  ?>
     </select></td>
    <td>&nbsp;</td>
    <td align="right">Tipo:</td>
    <td><select name="tipocotizacion" id="tipocotizacion"
     onchange="cambiarTipo(this.value);" style="width:80%;background:#FFF;border:solid 1px #999;">
       <?php
		 $selec = $ModificarT['tipo']; 
		 $tipo = array("productos", "servicios");
 		 $textos = array("Productos", "Servicios");
		 for ($i = 0; $i < count($tipo); $i++) {
			$atributo = ""; 
			if ($selec == $tipo[$i]) {
			    $atributo = "selected='selected'";	
			}
			echo "<option value='$tipo[$i]' $atributo>".ucfirst($textos[$i])."</option>";
		 }	
		?>
    </select></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
      
  </tr>
 </table>

</div>
      <div id="producto" class="producto">    
     
         <table width="100%" border="0">
          <tr>
            <td width="12%"><div align="right" style="font-weight:bold" id="textotipo">Producto:</div></td>
            <td width="24%">
              <input type="text" style="width:170px;"  id="dato" autocomplete="off" onkeyup="autocompletar(event,this.id)"/><br>
            <div  id="resultados"  class="divresultado" style="width:180px;" >
                </div>
               <input type="hidden" id="codidproducto" />
                </td>
            <td width="54%"><div id="autoL1" class="autoLoading"></div></td>
            <td width="10%">T.C.: <? echo $tc;?>
             <input id="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" ></td>
          </tr>
        </table>

      </div>
        
      <div id="cuerpo" class="cuerpo">
          <table width="100%" border="0" id="tabla">
              <tr class="filadetalleui">
              <td width="19" >&nbsp;</td>
              <th width="57"><div align="center">Código</div></th>
              <th width="389"><div align="center">Descripción</div></th>
              <th width="78"><div align="center">Cantidad</div></th>
              <th width="113"><div align="center">P/Unit.</div></th>
              <th width="114"><div align="center">Total</div></th>
            </tr>
            
                      
            <tbody id="detalleT">
               <?php
			   $totalDCotizacion = 0;
			     if (isset($_GET['idcotizacion'])){
				   if ($ModificarT['tipo'] == "productos") {
				       $sql = "select p.idproducto,p.nombre,round(ds.precio,2)as 'precio'
					   ,ds.cantidad, round(ds.total,2)as 'total' from detallecotizacion ds,producto p
					   ,cotizacion s where ds.idcotizacion=s.idcotizacion and 
					   ds.idproducto=p.idproducto and s.idcotizacion=".$_GET['idcotizacion'].
					   " order by ds.iddetallecotizacion";	   
				   } else {
				       $sql = "select s.idservicio as 'idproducto',s.nombre,round(ds.precio,2)as 'precio'
					   ,ds.cantidad, round(ds.total,2)as 'total' from detallecotizacion ds,servicio s
					   ,cotizacion c where ds.idcotizacion=c.idcotizacion and 
					   ds.idproducto=s.idservicio and c.idcotizacion=".$_GET['idcotizacion'].
					   " order by ds.iddetallecotizacion";  
				   }
					 
			       $consulta =mysql_query($sql);
					$fil = 0;
					while($dato = mysql_fetch_array($consulta)){
					  $color = '#F6F6F6' ;
					  $fil++;				
					   $totalDCotizacion = $totalDCotizacion + ($dato['precio']*$dato['cantidad']);    
				      echo "<tr bgColor='$color'>";
                      echo "  <td align='center'><img src='css/images/borrar.gif' style='cursor:pointer'
					   title='eliminar' onclick='eliminarFila(this)'/></td>";
                      echo "  <td align='center'>$dato[idproducto]</td>";
                      echo "  <td>$dato[nombre]</td>";
				      echo "  <td align='center'>".$dato['cantidad']."</td>";
					  echo "  <td align='center'>".number_format($dato['precio'],2)."</td>";					  
                      echo "  <td align='center'>".number_format($dato['precio']*$dato['cantidad'],2)."</td>";
                      echo "</tr>";
					}
				 }
			   ?>
            
            </tbody>
             
          </table>
                  
         
</div>
     </div>

     
     <div style="margin: 0 auto; width:100.2%; margin-top:2px;position:relative;">     
      <div id="pie_izq" class="pie_izq" >
      <table width="100%" border="0">
        <tr>
          <td width="14%" align="center">Glosa:</td>
          <td width="86%" rowspan="2">
          <textarea id="glosa" style="width:98%; height:50px;" ><?php echo $ModificarT['glosa'];?></textarea></td>
        </tr>
        <tr>
          <td height="35">&nbsp;</td>
        </tr>
      </table>
      </div>
         <div id="pie_der" class="pie_der" >
 <table width="99%">
  <tr >
    <td width="4%"><div align="right" ></div></td>
    <td width="28%"><div align="right"></div></td>
    <td width="23%" align="right">Sub Total:</td>
    <td width="45%"><input id="subtotal" name="subtotal" style="width:70%" value="<?php echo $totalDCotizacion;?>" readonly="readonly"/></td>
  </tr>
  <tr >
    <td><div align="right"></div></td>
    <td ><div align="right">Descuento:</div></td>
    <td ><input id="pdescuento" name="pdescuento" style="width:60%" onkeyup="calcularDescuento();" onkeypress="return soloNumeros(event)" value="<?php echo $ModificarT['descuento'];?>"/>%</td>
    <td><input id="descuento" name="descuento" style="width:70%" readonly="readonly"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Recargo:<div id="totalbs"></div></td>
    <td><input id="precargo" name="precargo" style="width:60%" onkeyup="calcularRecargo()" onkeypress="return soloNumeros(event)" value="<?php echo $ModificarT['recargo'];?>"/>%</td>
    <td><input id="recargo" name="recargo" style="width:70%" readonly="readonly"/></td>
  </tr>
  <tr >
    <td>&nbsp;</td>
    <td colspan="2" align="right">TOTAL Bs.:</td>
    <td>
    <input type="text" name="subtotalBS" id="subtotalBS" class="cuadroBS" value="0.00" readonly="readonly"/>     
    </td>
  </tr>
  <tr >
    <td><div align="right"></div></td>
    <td colspan="2" align="right">TOTAL $us.:</td>
    <td>
    <input type="text" name="subtotalDL" id="subtotalDL" value="0.00" class="cuadroBS" readonly="readonly"/>
    </td>
  </tr>
  </table>
     </div>
  </div>
    </td></tr></table>      
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
	 seleccionarCombo('tipoprecio','<?php echo $ModificarT['tipoprecio'];?>'); 
	 setSubTotal(<?php echo $totalDCotizacion;?>);
     transaccion ='<? echo $transaccion;?>';
     idUTransaccion = '<? echo $ModificarT['idcotizacion'];?>'
</script>