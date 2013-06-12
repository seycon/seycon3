<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: index.php");	
     }
	 include('conexion.php');
	 include('aumentaComa.php');
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
	  $fileAcceso = $db->privilegiosFile($estructura['Ventas'],'Venta de Servicios'
	  ,'nuevo_notaventa_servicio.php','listar_notaventaservicios.php');
	  if ($fileAcceso['Acceso'] == "No") {
	      header("Location: cerrar.php");	
	  }

	 $transaccion = "insertar";	
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql); 
	 
	 $sql = "select left(titulonotadeventas,30)as 'titulonotadeventas' from impresion where idimpresion = 1";
     $tituloPrincipal = $db->getCampo('titulonotadeventas', $sql); 
	 
	 if (isset($_GET['idnotaventa'])) {		 
		$sql = "select * from notaventa where idnotaventa=".$_GET['idnotaventa']; 
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
<link rel="stylesheet" type="text/css" href="factura/servicio/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="factura/servicio/NVenta.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script>
$(document).ready(function()
{

<?php	
 if (!isset($ModificarT['idnotaventa'])){
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
       <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer"
        title="Cerrar"  onclick="accion();"></div> 
       <div class="titleHeadCaption"> Registro de Datos</div>
    </div>
    <table align="center" width="100%" border="0" style="margin-top:10px;">
    <tr>
      <th colspan="3"> 
      <input type="hidden" id="precioProducto"  />
      </th>
    </tr>
    <tr>
       <th width="40%" align="right">Precio:</th>
       <th width="26%" align="left"><input id="Sprecio" name="Sprecio"  onkeyup="eventoIngresoCantidad(event)" 
            type="text"  size="12" onkeypress="return soloNumeros(event);"/></th>
       <th width="34%" align="left"><div id="msjsubnumero" class="mensajeCantidad">Incorrecto</div></th>
    </tr>
    <tr>
       <th height="10" align="right">Cantidad:</th>
       <td height="10"><input id="Scantidad" name="Scantidad" onkeyup="eventoIngresoCantidad(event)"  
            type="text"  size="12" onkeypress="return soloNumeros(event);"/></td>
       <td height="10"><div id="msjsubcantidad" class="mensajeCantidad">Incorrecto</div></td>
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
            <td><input type="button" value="Ver Reporte" onclick="accionPostRegistro();" class="botonNegro"/></td>
            <td align="right">Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
          </table>
        
      </div>
   </div>
 </div>  
      

<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Ventas > Ventas de Servicios </div>
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
      <td width="204" align="right">
        <?php
            if (isset($ModificarT['numero'])) {
                echo "<strong>Transacción N&deg; </strong>";  
            }	
        ?>
      </td>
      <td width="142">
        <?php
            if (isset($ModificarT['numero'])) {
                echo $ModificarT['numero'];  
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
    <td width="11%">&nbsp;</td>
    <td colspan="3"><div id="mensajeLibro" class="msjFacturas"></div>      
    </td>
    <td width="24%"><input type='hidden' id='idTransaccion' value='<?php echo $ModificarT['idnotaventa'];?>' /></td>
    <td width="15%" align="right">&nbsp;</td>
    <td width="19%" >&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Cliente<span class="rojo">*</span>:</td>
    <td width="11%">
    <?php		     
		if (isset($ModificarT['idcliente'])) {
			$sql = "select c.nit,c.nombrenit,r.nombre,c.nombre as 'cliente',c.idcliente from cliente c,ruta r 
			where r.idruta=c.ruta and idcliente='$ModificarT[idcliente]'";
			$datoCliente = $db->arrayConsulta($sql);
		}	
	?>  
    
    <input  type="text" id="texto" name="texto" onclick="this.select()" onkeyup="tipoBusquedaCliente(event);" autocomplete="off"  
    value="<?php echo $datoCliente['cliente'];	?>"/>
    <div id="clienteResult" class="divresultado"></div>
    <input type="hidden" id="cliente" name="cliente" value="<?php echo $datoCliente['idcliente'];?>" />
    </td>
    <td width="11%"><div id="autoL2" class="autoLoading"></div></td>
    <td width="9%" align="right">Factura:</td>
    <td><input type="text" name="facturas" id="facturas" style="width:140px;" value="<?php echo $ModificarT['numfactura'];?>"
     disabled="disabled" onkeypress="return soloEnteros(event)"/>
     <input type='checkbox' style="display:none;"  name='facturado' id='facturado' onclick='solicitarNumFactura();' />
      </td>
    <td align="right">Fecha:</td>
    <td><div id="cuenta_label" align="right" style="display:none">Nro Cuenta: </div>
      <div id="plazo_label" align="right" style="display:none">Plazo: </div>
      <div style="display:none" id="cuenta_div">
        <input name="cuenta" onkeypress="return permite(event,'num')" id="cuenta" type="text" />
      </div>
      <div style="display:none" id="plazo_div">
        <input name="plazo" id="plazo" type="text" />
      </div>
      <span class="radio">
      <input name="fecha" type="text" id="fecha" <?php if (isset($ModificarT['idnotaventa'])) echo "disabled='disabled'"; ?>
       style="border:solid 1px #999;" size="10" class="date" value="<? 
	   if (isset($_GET['idnotaventa'])){
	    echo $db->GetFormatofecha($ModificarT['fecha'],"-"); 	
	   }else{ 
	    echo date("d/m/Y"); 
	   }
	  ?>"  />
      </span></td>
    </tr>
  <tr>
    <td align="right">Sucursal<span class="rojo">*</span>:</td>
    <td colspan="2">    
    <select name="sucursal" id="sucursal" class="poscombo" style="width:140px;" onchange="consultarFactura()">
      <option value="" selected="selected">-- Seleccione --</option>
      <?php
		  $sucursal = "select  sl.idsucursal,left(sl.nombrecomercial,25)
          from sucursaltrabajador s,sucursal sl,usuario u  
          where u.idtrabajador=s.idtrabajador
          and s.idsucursal=sl.idsucursal  
          and sl.estado=1 
          and u.idusuario=$_SESSION[id_usuario] 
          order by sl.nombrecomercial;";		
	      $db->imprimirCombo($sucursal,$ModificarT['idsucursal']);			
	   ?>
    </select>
    
   </td>
    <td align="right">Ruta:</td>
    <td><input type="text" name="vendedor" id="vendedor" style="width:140px;"
     value="<?php echo $datoCliente['nombre'];?>" disabled="disabled"/>
    <input type="hidden" name="idvendedor" id="idvendedor" /></td>
    <td align="right">Moneda:</td>
    <td><select name="moneda" id="moneda" onchange="limpiarDetalle();" class="poscombo">
      <?php         
 
		  $selec = $ModificarT['moneda']; 
		 $tipo = array("Bolivianos","Dolares");
		 for ($i=0;$i<count($tipo);$i++){
			$atributo = ""; 
			if ($selec == $tipo[$i]){
			$atributo = "selected='selected'";	
			}
			echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
		 }	
		  
      ?>
    </select>    </tr>
  <tr>
    <td align="right">Nombre Nit:</td>
    <td colspan="2"><input type="text" id="nombrenit" name="nombrenit" value="<?php echo $datoCliente['nombrenit'];?>"/></td>
    <td align="right">C.I./N.I.T:</td>
    <td><input type="text" name="documento" id="documento" style="width:140px;" 
    value="<?php echo $datoCliente['nit'];?>"  onkeypress="return soloNumeros(event)"/></td>
    <td align="right">Precio:</td>
    <td><select name="tipoprecio" id="tipoprecio" class="poscombo">
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
      
    </select>    </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
      
  </tr>
 </table>

</div>
 
 
 <div id="producto" class="producto">         
    <table width="100%" border="0">
      <tr>
        <td width="13%"><div align="right"><strong>Servicios:</strong></div></td>
        <td width="17%">
          <input type="text" autocomplete="off" id="dato" name="dato" onkeyup="tipoBusqueda(event)"/><br>
          <div  id="resultados"  class="divresultado" style="width:200px">
          </div>
           <input type="hidden" id="codidservicio" />
            </td>
        <td width="59%"><div id="autoL1" class="autoLoading"></div></td>
        <td width="11%">T.C.: <?php
        if (isset($ModificarT['idnotaventa']))
           $tc = $ModificarT['tipocambio'];
         echo $tc;?>
         <input id="tipoCambioBs" name="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" ></td>
      </tr>
    </table>
 </div>
        
  <div id="cuerpo" class="cuerpo">
       <table width="100%" border="0" id="tabla">
        <tr class="filadetalleui">
          <th width="8" >&nbsp;</th>
          <th width="49" align="center">Código</th>
          <th align="center" width="411">Descripción </th>
          <th align="center" width="101">Precio</th>
          <th align="center" width="101">Cantidad</th>
          <th width="78" align="center">Total</th>
        </tr>
      
        <tbody id="detalleT">
         <?php
           $totalDNota = 0;
             if (isset($_GET['idnotaventa'])){
               $consulta =mysql_query("select p.idservicio,p.nombre,ds.precio,ds.cantidad 
                from detallenotaventaserv ds,servicio p,notaventa s
                where ds.idnotaventa=s.idnotaventa and ds.idservicio=p.idservicio
                 and s.idnotaventa=".$_GET['idnotaventa']." order by iddetallenotaventaserv");
                $fil = 0;
                $aux = 1;
                if ($ModificarT['moneda'] == "Dolares"){
                    $aux = $ModificarT['tipocambio'];  
                }
                
                while($dato = mysql_fetch_array($consulta)){
				  $precio =  round(($dato['precio'] / $aux),4);
				  $total = $precio * $dato['cantidad'];	
                  $totalDNota = $totalDNota + (round(($total),4));
                  $color = '#F6F6F6';
                  $fil++;				   
                  
                  echo "<tr bgColor='$color'>";
                  echo "  <td align='center'><img src='css/images/borrar.gif' style='cursor:pointer'
                   title='eliminar' onclick='eliminarFila(this)'/></td>";
                  echo "  <td align='center'>$dato[idservicio]</td>";
                  echo "  <td>$dato[nombre]</td>";
				  echo "  <td align='center'>".number_format($precio, 4)."</td>";
				  echo "  <td align='center'>".number_format($dato['cantidad'],4)."</td>";
                  echo "  <td align='center'>".number_format($total, 4)."</td>";
                  echo "</tr>";
                }
             }
           ?>        
        </tbody>         
      </table>
              

</div>
     </div>

     
     <div style="margin: 0 auto; width:100.2%; margin-top:2px;position:relative;">
     
       <div id="pie_izq" class="pie_izq">
        <table width="100%" border="0">
          <tr>
            <td width="14%" align="right">Glosa:</td>
            <td width="86%" rowspan="2">
            <textarea id="glosa" style="width:98%; height:50px;" ><?php echo $ModificarT['glosa'];?></textarea>
            </td>
          </tr>
          <tr>
            <td height="35">&nbsp;</td>
          </tr>
        </table>
       </div>
        
        <div id="pie_centro" class="pie_centro" >
          <table width="99%">
             <tr class="filadetalleui">
               <th colspan="3" align="center">Forma de Pago</th>
               </tr>
             <tr >
               <td width="23%" align="right">&nbsp;</td>
               <td width="27%">&nbsp;</td>
               <td width="50%">&nbsp;</td>
             </tr>
             <tr>
               <td align="right">Efectivo:</td>
               <td><input type="text" id="efectivo" name="efectivo" style="width:70px;" 
                onkeyup="calcularCambio2();" onkeypress="return soloNumeros(event)"/></td>
               <td>
               <?php			 
			     $dias = $ModificarT['caja'];
				 $atributo = "disabled='disabled'";
				 if ($dias > 0) {
				     $atributo = "";	 
				 }
			  ?>
               <select id="caja" name="caja" <?php echo $atributo;?> 
                style="background:#FFF;border:solid 1px #999;width:100px;" class="required">
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
		       ?>
               </select></td>
             </tr>
             <tr >
             <?php			 
			     $dias = $ModificarT['diascredito'];
				 $atributo = "disabled='disabled'";
				 if ($dias > 0) {
				     $atributo = "";	 
				 }
			 ?>             
               <td align="right">Crédito:</td>
               <td><input type="text" id="cambio" name="cambio" value="<?php echo $ModificarT['credito'];?>"
                style="width:70px;"  disabled="disabled"/></td>
               <td>
               <select name="diascredito" <?php echo $atributo;?> id="diascredito"
                style="background:#FFF;border:solid 1px #999;width:40px;">
                 <?php 
				  for ($i = 0; $i <= 31; $i++) {
					$atributo = ""; 
					if ($i == $dias) {
					    $atributo = "selected='selected'";	
					}		  
					echo "<option value='$i' $atributo>$i</option>";
				  }
				  ?>
               </select></td>
             </tr>
           </table>      
        </div>
        
        
         <div id="pie_der" class="pie_der" >
 <table width="99%"  cellspacing="1">
  <tr >
    <td width="3%"><div align="right" ></div></td>
    <td width="34%"><div align="right">Sub Total:</div></td>
    <td colspan="2"><?php echo $totalDCotizacion;?>      <input id="subtotal" name="subtotal" style="width:70%" value="<?php echo $totalDNota;?>" readonly="readonly"/></td>
    </tr>
  <tr >
    <td><div align="right"></div></td>
    <td ><div align="right">Descuento:</div></td>
    <td width="23%" ><input id="pdescuento" name="pdescuento" style="width:60%" onkeyup="calcularDescuento();" onkeypress="return soloNumeros(event)" value="<?php echo $ModificarT['descuento'];?>"/>%</td>
    <td width="40%"><input id="descuento" name="descuento" style="width:60px" readonly="readonly" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Recargo:<div id="totalbs"></div></td>
    <td><input id="precargo" name="precargo" style="width:60%" onkeyup="calcularRecargo()"
     onkeypress="return soloNumeros(event)" value="<?php echo $ModificarT['recargo'];?>"/>%</td>
    <td><input id="recargo" name="recargo" style="width:60px" readonly="readonly"/></td>
  </tr>
  <tr >
    <td>&nbsp;</td>
    <td align="right" class="regionTotal">TOTAL Bs.:</td>
    <td colspan="2" align="left" class="regionTotal">    
    <input type="text" id="subtotalBS" name="subtotalBS" class="cuadroBS" value="0.00" readonly="readonly" />
    </td>
    </tr>
  <tr >
    <td><div align="right"></div></td>
    <td align="right" class="textoTotal">TOTAL $us.:</td>
    <td colspan="2" align="left">
    <input type="text" id="subtotalDL" name="subtotalDL" class="cuadroBS" value="0.00" readonly="readonly" />
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
	 seleccionarCombo('caja','<?php echo $ModificarT['caja'];?>'); 	
	 setSubTotal(<?php echo $totalDNota;?>);
     transaccion ='<? echo $transaccion;?>';
</script>