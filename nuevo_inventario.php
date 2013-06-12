<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
 	 $db = new MySQL();
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
     }
	 $estructura = $_SESSION['estructura'];
	 $fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Control Inventario','nuevo_inventario.php'
	 ,'listar_inventario.php');
	  if ($fileAcceso['Acceso'] == "No"){
		header("Location: cerrar.php");	
	  }

	 $transaccion = "insertar";	
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql); 
	 
		 
	 if (isset($_GET['idinventario'])){		 
		$sql = "select * from inventario where idinventario=".$_GET['idinventario']; 
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
<link rel="stylesheet" type="text/css" href="inventario/inventario.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script async="async" src="autocompletar/FuncionesUtiles.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="inventario/NInventario.js"></script>
<script async="async" src="lib/Jtable.js"></script>

<script>
$(document).ready(function()
{

<?php	
 if (!isset($ModificarRegistro['idinventario'])){
echo "	
$('#fecha').datepicker({
showOn: 'button',
buttonImage: 'css/images/calendar.gif',
buttonImageOnly: true,
dateFormat: 'dd/mm/yy' });";
 }
?>
$("#fechafinal").datepicker({
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
             <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer"  onclick="accion();">
           </div>
             <div class="titleHeadCaption"> Registro de Datos</div>
          </div>
          <table align="center" width="90%" style="position:relative;top:10px;">
            <tr>
              <th></th>
              <th><div id="unidadI">Unidad I</div></th>
              <th><div id="unidadII">Unidad II</div></th>
              <th width="13%" rowspan="3" align="left">&nbsp;</th>
            </tr>
            <tr>
              <th width="29%">Cantidad:</th>
              <th width="28%">
              <input id="cunidadI" name="cunidadI"  type="text" onkeyup="eventoSubVentana(event);" size="10" 
              onkeypress="return soloNumeros(event);"/>
              </th>
              <th>
              <input id="cunidadII" name="cunidadII"  type="text"  size="10" onkeypress="return soloNumeros(event);" 
              onkeyup="eventoSubVentana(event);"/>
              </th>
            </tr>
            <tr>
              <th height="10">&nbsp;</th>
              <th height="10" colspan="2"><div id="msjsubnumero" class="msjVentana">Cantidad Incorrecta</div></th>
            </tr>
          </table>
          <div class="boton1_subventana">
          <input onclick="insertarNewItem('detalleTransaccion');" type="button" value="Aceptar" 
          id="aceptar_modal"  class="botonNegro"/>
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
        <table width="85%" align="center" style="margin-top:40px;">
            <tr>            
            <td width="90" align="center">
            <input type="button" value="Ver Reporte" onclick="accionPostRegistro();" class="botonNegro"/></td>
            <td width="54" align="right">Grupo:</td>
            <td width="120">
            <select id="grupoR" name="grupoR" style="border:1px solid #666;">
            <option value="todos">Todos</option>
             <?php 
			     $sql = "select idgrupo,nombre from grupo where estado=1";
			     $db->imprimirCombo($sql);  
			 ?>
            </select>
            </td>
            <td width="108" align="right">Logo:</td>
            <td width="29"><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
            </table>
      </div>
  </div>
</div>
     

  
<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Inventario > Control Inventario </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Control Inventario"); 
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
    <input type="button" value="Guardar [F2]" id="vender" onclick="ejecutarTransaccion()" class="botonNegro" />
	<?php 
    if ($fileAcceso['File'] == "Si"){
     echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
     style="width:90px;" value="Cancelar [F4]" onclick="salir();"/>';	
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
            if (isset($_GET['idinventario'])){
             echo $_GET['idinventario'];
            } else {
              echo $db->getNextID("idinventario","inventario");	
            } 
			?></td>
          </tr>
          <tr>
            <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
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
    <td width="27%" align="right">Fecha Inicio:</td>
    <td colspan="2"><span class="radio">&nbsp;
      <input name="fecha" type="text" id="fecha" <?php if (isset($ModificarRegistro['idinventario'])) echo "disabled='disabled'"; ?>  style="border:solid 1px #999;" size="12" class="date" value="<? 
	   if (isset($_GET['idinventario'])){
	    echo $db->GetFormatofecha($ModificarRegistro['fechainicio'],"-"); 	
	   }
	   else{ 
	    echo date("d/m/Y"); 
	   }
	  ?>"  />
      </span><br />

    </td>
    <td width="17%" align="right">Fecha de Finalizacion:</td>
    <td width="27%" ><span class="radio">
      <input name="fechafinal" type="text" id="fechafinal" style="border:solid 1px #999;" size="12" class="date" value="<? 
	   if (isset($_GET['idinventario'])){
	    echo $db->GetFormatofecha($ModificarRegistro['fechafinal'],"-"); 	
	   }
	   else{ 
	    echo date("d/m/Y"); 
	   }
	  ?>"  />
    </span></td>
  </tr>
  <tr>
    <td align="right">Almacén<span class="rojo">*</span>:</td>
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
    <td align="right">Supervisor:</td>
    <td><div id="cuenta_label" align="right" style="display:none">Nro Cuenta: </div>
      <div id="plazo_label" align="right" style="display:none">Plazo: </div>
      <div style="display:none" id="cuenta_div">
        <input name="cuenta" onkeypress="return permite(event,'num')" id="cuenta" type="text" />
      </div>
      <div style="display:none" id="plazo_div">
        <input name="plazo" id="plazo" type="text" />
      </div>
      <input type="text" id="supervisor" name="supervisor" value="<?php echo $ModificarRegistro['supervisor'];?>"/></td>
    </tr>
  <tr>
    <td align="right">Administrador:</td>
    <td width="19%"><input type="text" id="administrador" name="administrador" value="<?php echo $ModificarRegistro['administrador'];?>"/></td>
    <td width="10%"></td>
    <td align="right"><input type='hidden' id='idTransaccion' value='<?php echo $ModificarRegistro['idinventario'];?>' /></td>
    <td></tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td><div id="cliente" class="divresultado"></div></td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td></tr>
            </table>

</div>
      <div id="producto" class="producto">    
     
         <table width="100%" border="0">
           <tr>
    <td width="13%"><div align="right"><strong>Producto:</strong></div></td>
    <td width="20%">
      <input type="text"  id="dato" name="dato" autocomplete="off" onkeyup="autocompletar(event,this.id)" style="width:170px"/><br>
    <div  id="resultados" style="width:170px;" class="divresultado" >
        </div>
       <input type="hidden" id="codidproducto" />
        </td>
    <td width="56%"><div id="autoL1" class="autoLoading"></div></td>
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
              <td width="21" >&nbsp;</td>
              <th width="93"><div align="center">Código</div></th>
              <th width="362" align="center">Descripción</th>
              <th width="97"><div align="center">Cantidad</div></th>
              <th width="120">Unidad Medida</th>
              <th width="84" align="center">Cantidad</th>
              <th width="157" >Unidad Alternativa</th>
            </tr>         
                      
            <tbody id="detalleTransaccion">
               <?php
			     if (isset($_GET['idinventario'])){
					$sql = "select i.idproducto,p.nombre,i.cantidadum,i.unidadmedida,i.cantidadua,i.unidadalternativa from 
					detalleinventario i,producto p where i.idproducto=p.idproducto and i.idinventario=$_GET[idinventario] 
					order by i.iddetalleinventario"; 					 
			        $consulta = $db->consulta($sql);
					while($dato = mysql_fetch_array($consulta)){ 
				      echo "<tr>";
                      echo "  <td align='center'>
					  <img src='css/images/borrar.gif' style='cursor:pointer' title='eliminar' onclick='eliminarFila(this)'/></td>";
                      echo "  <td align='center'>$dato[idproducto]</td>";
                      echo "  <td>$dato[nombre]</td>";
					  echo "  <td align='center'>".number_format($dato['cantidadum'], 2)."</td>";
				      echo "  <td align='center'>$dato[unidadmedida]</td>";
					  echo "  <td align='center'>".number_format($dato['cantidadua'], 2)."</td>";
                      echo "  <td align='center'>$dato[unidadalternativa]</td>";
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
              <td width="14%">Glosa:</td>
              <td width="86%" rowspan="2">
              <textarea id="glosa" style="width:98%; height:50px;" ><?php echo $ModificarRegistro['glosa'];?></textarea>
              </td>
            </tr>
            <tr>
              <td height="35">&nbsp;</td>
            </tr>
          </table>          
        </div>           
        <div id="pie_der" class="pie_der"></div>     
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
<script> transaccion ='<?php echo $transaccion;?>';</script>