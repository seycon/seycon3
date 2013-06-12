<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include('conexion.php');
	if (!isset($_SESSION['softLogeoadmin'])) {
	 header("Location: index.php");	
	}
	$db = new MySQL();
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Contabilidad'],'Libro Diario','nuevo_librodiario.php','listar_librodiario.php');
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}
	$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	$tc = $db->getCampo('dolarcompra',$sql); 
	
	$transaccion = "insertar";
	if (isset($_GET['idlibrodiario'])) {		 
	    $sql = "select * from librodiario where idlibrodiario=".$_GET['idlibrodiario']; 
	    $ModificarRegistro = $db->arrayConsulta($sql);		 
	    $transaccion = "modificar";	
	}	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templatecontabilidad.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" href="librodiario/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="lib/Jtable.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="librodiario/NLibro.js"></script>
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

$("#fechadocumento").datepicker({
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
<form id="form1" method="" action="" autocomplete="off">
   
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
            <td><input type="button" value="Ver Reporte" onclick="accionPostRegistro();" class="botonNegro"/></td>
            <td align="right">Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
            </table>    
        
      </div>
  </div>
</div>



<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Contabilidad > Libro Diario </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Contabilidad'];
   $privilegios = $db->getOpciones($menus, "Libro Diario"); 
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
    <input type="button" name="aceptar" id="aceptar" value="Guardar [F2]"
     onclick="ejecutarTransaccion();"  class="botonNegro"/>   	     
	 <?php 
	  if ($fileAcceso['File'] == "Si") {
		  echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar"
		   value="Cancelar [F4]" onclick="salir()"/>';	
	  }
	 ?> 
    </td>
<td></td>
<td colspan="3" align='right'>

        <table width="356" border="0">
          <tr>
            <td colspan="2">
			<?php 
              if (isset($_GET['idlibrodiario'])) {
				   echo "<strong>Transacción N°</strong> ".$ModificarRegistro['numero'];
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
</table>
       
       <div id="datos_factura" class="datos_cliente1">
            <table width="100%" border="0">
                <tr>
                  <td width="13%"></td>
                  <td width="19%"></td>
                  <td width="17%"><input type="hidden" id="idTransaccion" name="idTransaccion" 
                  value="<?php echo $ModificarRegistro['idlibrodiario'];?>"/> </td>
                  <td width="17%"><div class="radio"></div></td>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td> Sucursal<span class="rojo">*</span>:
                  <select name="sucursal" id="sucursal" style="width:120px;">
                    <option value='' selected='selected'>-- Seleccione --</option>
                    <?php
                        $almacen = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1";
                        $db->imprimirCombo($almacen,$ModificarRegistro['idsucursal']);
                    ?>
                  </select></td>
                  <td align="left">Moneda:<br />
                    <label for="moneda"></label>
                    <select name="moneda" id="moneda">
                        <?php
					   $selec = $ModificarRegistro['moneda']; 
					   $tipo = array("Bolivianos","Dolares");
					   for ($i = 0; $i < count($tipo); $i++) {
					       $atributo = ""; 
						   if ($selec == $tipo[$i]){
						       $atributo = "selected='selected'";	
						   } 
						   echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
					   }	
	                  ?>
                      
                      
                  </select></td>
                  <td><label for="tipo">
                    Transacción:
                        <select name="tipo" id="tipo">       
                      <?php
					   $selec = $ModificarRegistro['tipotransaccion']; 
					   $tipo = array("ingreso","egreso","traspaso");
					   for ($i = 0; $i < count($tipo); $i++) {
						   $atributo = ""; 
					       if ($selec == $tipo[$i]) {
						       $atributo = "selected='selected'";	
						   }
						   echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
					   }	
	                  ?>
                      
                      
                    </select>
                  </label></td>
                  <td width="18%">Fecha:<br />
                  <input name="fecha" type="text" id="fecha" value="<?php 
				  if (isset($_GET['idlibrodiario'])) {
					  echo $db->GetFormatofecha($ModificarRegistro['fecha'],'-');
				  } else {				  
				      echo date("d/m/Y");
				  } ?>" size="10"  /></td>
                  <td width="16%">T.C.:<?php echo $tc;?>
                  <input type="hidden" id="tipocambio" name="tipocambio" value="<?php echo $tc;?>"/>
                  </td>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
            </table>

</div>
       
       <div id="producto" class="producto">

         <table width="100%" border="0">
  <tr>
    <td>Cuenta:</td>
    <td valign="bottom">&nbsp;</td>
    <td align="left" valign="bottom">Descripción: </td>
    <td align="left" valign="bottom">Debe:</td>
    <td align="left" valign="bottom">Haber:</td>
    <td align="left" valign="bottom">Documento:</td>
    <td valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td width="19%">
    <select name="cuentaD" id="cuentaD"  style="width:100%;" >
      <optgroup >
        <option value="">-- Seleccione --</option>
        </optgroup>
      <?php
       $consultaPlanCuenta = "select (select pp.cuenta from plandecuenta pp where pp.codigo=( left(ph.codigo,2) )) 
       as 'padre',ph.codigo,ph.cuenta,ph.nivel from plandecuenta   
       ph  where ph.nivel>=5 and estado=1 order by ph.codigo;";
       $arrayPlan = $db->getDatosArray($consultaPlanCuenta,4);
       $db->imprimirComboGrupoArray($arrayPlan,'',''); 
      ?>
    </select>
    
    
    </td>
    <td width="8%" ></td>
    <td width="23%"  ><input size="25" type="text" id="descripcionD" name="descripcionD" /></td>
    <td width="11%" align="left" >
    <input id="debeD" name="debeD" type="text" size="12" onkeypress="return soloNumeros(event)" onkeyup="eventoText(event);"/></td>
    <td width="11%" align="left" >
    <input type="text" id="haberD" name="haberD" size="13" onkeypress="return soloNumeros(event)"  onkeyup="eventoText(event);"/></td>
    <td width="12%" align="left" >
      <input type="text" id="documentoD" name="documentoD" size="12"  onkeypress="return soloNumeros(event)" onkeyup="eventoText(event);"/></td>
    <td width="16%" valign="middle">
      <input type="button" onclick="insertarNewItem('detalleTransaccion');" name="agregar"
       id="agregar" value="Registrar [Enter]" class="botonNegro" style="width:110px;" /></td>
  </tr>

  </table>


</div>
        
      <div id="cuerpo" class="cuerpo">
          <table width="100%" border="0" id="tabla">
            <tr class="filadetalleui">
              <td width="15" >&nbsp;</td>
              <th width="15" >Nº</th>
              <th width="187" align="center" style='display:none'>Codigo Cuenta</th>
              <th width="187" align="center">Cuenta</th>
              <th width="380" align="center">Descripción</th>
              <th width="108" align="center">Debe</th>
              <th width="70" align="center">Haber</th>
              <th width="100" align="center">Doc.</th>
            </tr>
            <tbody id="detalleTransaccion">
            <?php
			 $totalDebe = 0;
			 $totalHaber = 0;
			 if (isset($ModificarRegistro['idlibrodiario'])) {
		         $sql = "select d.idcuenta,p.cuenta,d.descripcion,d.debe,d.haber,d.documento from detallelibrodiario d,
				 plandecuenta p where idlibro=$ModificarRegistro[idlibrodiario] and d.idcuenta=p.codigo and p.estado=1 
				  order by iddetallelibro asc";
				 $detalle = $db->consulta($sql);
			     $i = 0;
			     while($dato = mysql_fetch_array($detalle)) {
				     $totalDebe = $totalDebe + $dato['debe'];
					 $totalHaber = $totalHaber + $dato['haber'];
				     $i++; 
					 echo "
					  <tr> 
						<td align='center'><img src='css/images/borrar.gif' title='Anular' alt='borrar'
						 onclick='eliminarFila(this)'/></td>
						<td align='center'>$i</td>
						<td style='display:none'>$dato[idcuenta]</td>
						<td >$dato[cuenta]</td>
						<td >$dato[descripcion]</td> 
						<td align='center'>".number_format($dato['debe'],2)."</td>   
						<td align='center'>".number_format($dato['haber'],2)."</td>  
						<td align='center'>$dato[documento]</td>
					  </tr>";	 
			     }
			 }
			?>            
            </tbody>
             
          </table> 
        <table border="0" bordercolor="#CCCCCC" style="bottom:35px; position:absolute;" cellspacing="0" width="100%;">
              <tr>
                <td width="68%" >&nbsp;</td>
                <td width="12%"><div id="debe_div" style="width:80px;"></div></td>
                <td width="10%"><div id="haber_div" style="width:80px;"></div></td>
                <td width="10%">&nbsp;</td>
              </tr>
       </table>
       
    </div>

    <div class="pie_libro">
        <table width="100%" border="0">
          <tr>
            <td width="12%" align="right">Glosa:</td>
            <td width="45%"><label for="glosa"></label>
            <input type="text" id="glosa" name="glosa" style="width:60%;" value="<?php echo $ModificarRegistro['glosa'];?>"/></td>
            <td width="9%" align="right"><strong>TOTAL:</strong></td>
            <td width="34%">
            <div style="border:2px solid #CCC">
            <table width="100%" border="0">
            <tr>
              <td width="19%" align="right"><strong>Debe</strong></td>
              <td width="31%"><input type="text" name="totaldebe" id="totaldebe" value="" size="9" readonly="readonly"/></td>
              <td width="23%" align="right"><strong>Haber</strong></td>
              <td width="27%"><input type="text" name="totalhaber" id="totalhaber" value="" size="9" readonly="readonly"/></td>
            </tr>
            </table>

            </div></td>
          </tr>
       </table>
     </div>   

  </div></td></tr></table>
</form>
<script>
   cargarTotales(<? if (isset($ModificarRegistro['idlibrodiario'])) echo $totalDebe; else echo 0;?>,
   '<? if (isset($ModificarRegistro['idlibrodiario'])) echo $totalHaber; else echo 0;?>');
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