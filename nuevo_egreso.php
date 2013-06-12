<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
     }
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
	 $fileAcceso = $db->privilegiosFile($estructura['Contabilidad'],'Egreso de Dinero','nuevo_egreso.php','listar_egreso.php');
	 if ($fileAcceso['Acceso'] == "No"){
		header("Location: cerrar.php");	
	 }
	 
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql);
	 $sql = "select left(tituloegreso,30)as 'tituloegreso' from impresion where idimpresion = 1";
     $tituloPrincipal = $db->getCampo('tituloegreso', $sql);
	 $transaccion = "insertar";
	 if (isset($_GET['idegreso'])){		 
		$sql = "select * from egreso where idegreso=".$_GET['idegreso']; 
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
<link rel="stylesheet" type="text/css" href="egresos/style.css" />
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="lib/Jtable.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="egresos/NEgreso.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script>
$(document).ready(function()
{
<?php	
 if (!isset($ModificarRegistro['idegreso'])){
echo "	
$('#fecha').datepicker({
showOn: 'button',
buttonImage: 'css/images/calendar.gif',
buttonImageOnly: true,
dateFormat: 'dd/mm/yy' });";
 }
?>

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

<form id="formVentana" name="formVentana" method="" action="">    
<div id="subventana2" class="contenedorframeIDCP">    
    <div class="modal_interiorframeIDCP"></div>      
    <div class="subVentana2"> 
         <div class="cabezeraSubVentana">
          <div class="titleHeadCaptionCP"> Cuentas Pendientes</div>
           <div class="modalCerrar">
           <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="cerrarSubVentana2()"></div>
          </div>

            <div style="position:relative;height:170px;overflow:scroll;overflow-x:hidden;">
            <table width="100%" border="0" align="center">
               <tr class="filadetalleui">
                 <th width="5%" align="center">Nº</th>
                 <th width="10%" align="center">Fecha</th>
                 <th width="20%" align="center">Cuenta</th>
                 <th width="21%" align="center">Transacción</th>
                 <th width="24%" align="center" style="display:none">codigo</th>
                 <th width="35%" align="center">Detalle</th>
                 <th width="30%" align="center">Importe</th>
                 <th width="3%" align="center">&nbsp;</th>
                 <td width="3%" style='display:none'>idtransaccion</td>
               </tr>
               <tbody id="detallePendientes">
                             
               </tbody>
            </table>
            </div>
            <br />
            <div class="seccionPago">
            <table width="100%" class="submenuPago">
              <tr>
                <td width="15%" >Descripción</td>
                <td width="30%" ><input type="text" name="descripcionPago" id="descripcionPago" style="width:85%" /></td>
                <td width="6%" >Bs.:</td>
                <td width="16%" >
                <input type="text" name="bolivianosPago" id="bolivianosPago" value="" size="9"
                 onkeypress="return soloNumeros(event);"/>
                </td>
                <td width="8%" >$us.:</td>
                <td width="25%" >
                <input type="text" name="dolaresPago" id="dolaresPago" value="" size="9" onkeypress="return soloNumeros(event);"/>              
                </td>
              </tr>
            </table>
            </div>
          <div class="boton3_subventana">
          <input onclick="cargarCuentaSeleccionada();" type="button" value="Aceptar" id="aceptar_modal"  class="botonNegro"/>
          </div>
          <div class="boton4_subventana">
          <input onclick="cerrarSubVentana2();" type="button" value="Cancelar" id="aceptar_modal"  class="botonNegro"/>
          </div>           
     </div> 
   </div>       
 </form>       
  
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

<div id="subventana" class="contenedorframeID">    
    <div class="modal_interiorframeID"></div>     
  
    <div class="subVentana"> 
     <div class="caption_modalCabecera">
       <div class="posicionCloseSub" onclick="cerrarSubVentana();">
        <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="cerrarSubVentana();"></div>
       <div class="titleHeadCaption">Datos Factura</div>
     </div>          
          
        <table width="100%" border="0" >           
          <tr>
            <td colspan="2"><strong>DIA
                <br />
            </strong>              
             <input name="diaF" type="text" id="diaF" size="12" onkeypress="return cambiarFoco(event)"  />
            <input type="hidden" id="ItemF" name="ItemF" value=""  /></td>
          </tr>
           
          <tr>
            <td width="40%" align="left"><strong>NIT<br />
            </strong>
            <input name="nitF" type="text" id="nitF" size="13" onchange="recuperaNombre(this.value,'egresos/DEgreso.php')"
             onkeypress="return soloNumeros(event)" /></td>
            <td width="60%" align="left"><input name="ItemTabla" type="hidden" id="ItemTabla" size="13"
             onkeypress="return soloNumeros(event)" /></td>
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
              <input name="excentoF" onkeypress="return soloNumeros(event)" type="text" id="excentoF" style="width:90%" /></td>
               
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
    <div class="boton1_subventana">
     <input type="button" name="Guardar" id="Guardar" value="Guardar" class="botonNegro"  onclick="insertarNewFactura();"/>
    </div>
    <div class="boton2_subventana">
     <input onclick="cerrarSubVentana();" type="button" value="Cancelar" id="aceptar_modal"  class="botonNegro"/>
    </div>
          
  </div>
</div>  
  
  
  
<form id="form1" name="form1" method="" action="">
<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Contabilidad > Egreso de Dinero </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Contabilidad'];
   $privilegios = $db->getOpciones($menus, "Egreso de Dinero"); 
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
        if ($fileAcceso['File'] == "Si"){
         echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
		 value="Cancelar [F4]" onclick="salir()"/>';	
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
             	  if (isset($_GET['idegreso'])){
					echo $ModificarRegistro['idegreso'];
				  } else {
					echo $db->getNextID("idegreso", "egreso");  
				  }
			?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
            <input type="hidden" id="tc" value="<?php echo $tc?>" />
          </tr>
        </table>     

    </td>
  </tr>
</table>

    
      
       <div id="datos_factura" class="datos_cliente1">
            <table width="100%" border="0">
                <tr>
                  <td colspan="3"></td>
                  <td colspan="2">
                    <input type="hidden" name="idTransaccion" id="idTransaccion" value="<?php echo $ModificarRegistro['idegreso']?>"/>
                  </td>
                  <td width="2%">&nbsp;</td>
                  <td width="10%">&nbsp;</td>
                  <td width="16%">
                  
                  
                  </td>
                </tr>
                <tr>
                  <td width="22%">&nbsp;</td>
                  <td width="21%" colspan="2">&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td> T.C.: <?php 
				  if (isset($ModificarRegistro['idegreso']))
	              $tc = $ModificarRegistro['tipocambio'];
				  echo $tc;?>
                   <input id="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" ></td>
                </tr>
                <tr>
                  <td align="right">Sucursal<span class="rojo">*</span>: </td>
                  <td colspan="2"><select name="sucursal" id="sucursal" style="width:143px;">
                    <option value='' selected='selected'>-- Seleccione --</option>
                    <?php
                       $almacen = "select idsucursal, left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1";
                       $db->imprimirCombo($almacen,$ModificarRegistro['idsucursal']);
                      ?>
                  </select></td>
                  <td width="11%" align="right">Caja/Banco<span class="rojo">*</span>:</td>
                  <td width="18%" align="left">
                  <select id="cuenta" name="cuenta" style="width:120px;background:#FFF;border:solid 1px #999;" >
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
		  
		  
		  
		  
    	  $codigo = ($datoTransaccion['idporpagar'] == "") ? 0 : $datoTransaccion['idporpagar'];
		  $sql = "
				select day(fechadeemision)as 'dia',numdenitproveedor as 'nit',nomrazonsociprove as 'razonsocial',
                numfacturaopoliza as 'factura',numautorizacion as 'autorizacion',
                codigodecontrol,totalfactura,totalice,importeneto,importeexcento,creditofiscal from 
                librocomprasiva where idtransaccion=$codigo and transaccion='Cuenta por pagar';
				";
				$datoFactura = $db->arrayConsulta($sql);
		  ?>
                  </select></td>
                  <td align="right">&nbsp;</td>
                  <td align="right">Fecha:
                  </td>
                  <td><input name="fecha" <?php if (isset($ModificarRegistro['idegreso'])) echo "disabled='disabled'"; ?>
                   type="text" id="fecha" 
                  value="<?php
				  if (isset($ModificarRegistro['fecha']))
				   echo $db->GetFormatofecha($ModificarRegistro['fecha'],'-');
				  else
				   echo date("d/m/Y"); 
				   ?>" size="9"  /></td>
                </tr>
                <tr>
                  <td align="right"><select name="receptor" id="receptor"  onchange="cambiarDependencias();"
                   style="width:100px; background:#FFF;border:solid 1px #999;">

                       <?php
					   $selec = $ModificarRegistro['tipopersona']; 
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
                  <td>
     <input style="width:90%" type="text" id="texto" onclick="this.select()" onkeyup="tipoBusqueda(event);" 
    value="<?php  
	if (isset($ModificarRegistro['tipopersona'])){
	  if ($ModificarRegistro['tipopersona'] == "otros"){
		echo $ModificarRegistro['nombrepersona'];  
	  }else{		    
	    echo $ModificarRegistro['nombrepersona'];
	  }
	}?>" autocomplete="off"/>
    <div id="cliente" class="divresultado"></div>
    <input type="hidden" id="idpersonarecibida" value="<?php echo $ModificarRegistro['idpersona'];?>" />
    </td>
                  <td><div id="autoL1" class="autoLoading"></div></td>
                  <td align="right">Cheque:</td>
                  <td align="left"><input name="cheque" id="cheque" size="16" value="<?php echo $ModificarRegistro['cheque'];?>"/></td>
                  <td align="right">&nbsp;</td>
                  <td align="right">Recibo:</td>
                  <td><input name="recibo" id="recibo" size="9" value="<?php echo $ModificarRegistro['recibo'];?>"/></td>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
            </table>

</div>
       
       <div id="producto" class="producto">
         <div id="submenu" class="submenu">
           <table width="100%" border="0" id="datosorigen">
             <tr>
               <td width="55" align="right">Cuenta:</td>
               <td width="154">               
               <select name="cuentaD" id="cuentaD"  style="width:160px;" >
                <option value="">-- Seleccione --</option>
                    <?php
		            $sql = "select *from tipoconfiguracion where tipo='Egreso Dinero';";
		            $cuenta = $db->consulta($sql);
		             while ($dato = mysql_fetch_array($cuenta)){
		              echo "<option value='$dato[cuenta]'>$dato[descripcion]</option>";
		             }
		           ?>  
                
              </select>
               <input type="button" id="buscar" name="buscar" value="" class='botonseyconBuscar' onclick="consultarPendientes();"/>
               </td>
               <td width="76" align="right">Descripción:</td>
               <td width="144" align="left"><input type="text" name="descripcionD" id="descripcionD" style="width:85%" /></td>
               <td width="24">Bs.:</td>
               <td width="62"><input type="text" name="bolivianosD" id="bolivianosD" onkeypress="return soloNumeros(event)"
                value="" size="9" onkeyup="eventoText(event);"/></td>
               <td width="34">$us.:</td>
               <td width="60"><input type="text" name="dolaresD" id="dolaresD" onkeypress="return soloNumeros(event)"
                onkeyup="eventoText(event);" value="" size="9"/></td>
               <td width="66"><input type="button"  class='botonNegro' value="Registrar [Enter]"
                onclick="insertarNewItem('detalleTransaccion')" style="width:110px;"/></td>
             </tr>
           </table>
         </div>
       </div>
        
      <div id="cuerpo" class="cuerpo" >
        <div id="cuerpo2" style="position:relative;height:330px;overflow:auto;">
          <table width="100%" border="0" id="tabla" >
            <tr class="filadetalleui">
              <th width="19" >&nbsp;</th>
              <th width="20" align="center" style='display:none'>Factura</th>
              <th width="20" align="center">Nº</th>
              <th width="127" align="center" style='display:none'>Codigo Cuenta</th>
              <th width="127" align="center">Cuenta</th>
              <th width="196" align="center">Descripción</th>
              <th width="60" align="center">Bs.:</th>
              <th width="60" align="center">$us.:</th> 
              <th width="30" align="center">Facturar</th>    
              <th width="30" align="center" style='display:none'>IDDetalle</th> 
              <th width="30" align="center" style='display:none'>Transaccion</th>
              <th width="30" align="center" style='display:none'>IDTransaccion</th>         
            </tr>
            <tbody id="detalleTransaccion">
            <?php
			  $totalBs = 0;
			  $totalDolares = 0;
			  $condicionFactura = "";
			 if (isset($ModificarRegistro['idegreso'])) {
               $sql = "select d.iddetalleegreso,d.idcuenta,d.descripcion,d.montobolivianos
			   ,d.montodolares,p.cuenta,d.transaccion,d.idtransaccion from detalleegreso d,
			   plandecuenta p 
			   where idegreso=$ModificarRegistro[idegreso] and p.estado=1 and d.idcuenta=p.codigo order by iddetalleegreso asc";
			   $detalle = $db->consulta($sql);
			   $i = 0;
			     while($dato = mysql_fetch_array($detalle)){
				  $i++; 
				  $totalBs = $totalBs + $dato['montobolivianos'];
				   $dolares = round(($dato['montodolares']/$ModificarRegistro['tipocambio']),4);
				  $totalDolares = $totalDolares + $dolares;
		          $condicionFactura = ($condicionFactura == "") ? ("idtransaccion=".$dato['iddetalleegreso']) 
				  : ($condicionFactura." or idtransaccion=".$dato['iddetalleegreso']);
			       echo "
				    <tr> 
 					  <td align='center'>
					  <img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' /></td>
					  <td align='center' style='display:none'>-1</td>
					  <td align='center'>$i</td>
                      <td style='display:none'>$dato[idcuenta]</td>
					  <td >$dato[cuenta]</td>
                      <td >$dato[descripcion]</td> 
					  <td align='center'>".number_format($dato['montobolivianos'],2)."</td>   
					  <td align='center'>".number_format($dolares,2)."</td>  
					  <td align='center'>
					  <input type='button' style='width:60px;' class='botonNegro' 
					  value='Factura' onclick='mostrarVentanaFactura(this)'/></td> 
					  <td style='display:none'>$dato[iddetalleegreso]</td>
					  <td style='display:none'>$dato[transaccion]</td>
					  <td style='display:none'>$dato[idtransaccion]</td>
                    </tr>";	 
			     }
			 }		 
			 $numRegistros = $i - 1;		
			?>
            </tbody>
          </table>
        </div>
      </div>

        <table width="100%" border="0">
          <tr>
            <td width="18%" align="right">Glosa:</td>
            <td width="51%" align="left">
            <input name="glosa" type="text" id="glosa" style="width:80%" value="<?php echo $ModificarRegistro['glosa'];?>"/></td>
            <td width="9%" align="right"><strong>TOTAL:</strong></td>
            <td width="22%">
            <div style="border:2px solid #CCC">
            <table width="100%" border="0">
  <tr>
    <td width="12%"><strong>Bs.</strong></td>
    <td width="38%"><input type="text" name="totalbs" id="totalbs" value="" size="9" readonly="readonly"/></td>
    <td width="16%"><strong>$us.</strong></td>
    <td width="34%"><input type="text" name="totaldolares" id="totaldolares" value="" size="9" readonly="readonly"/></td>
  </tr>
</table>
 </div>
            
            </td>
          </tr>
       </table>
       
       <table width="100%" border="1" id="datosFacturas" style="display:none">
       <?php 	  
	     if ($condicionFactura != "") {
		  $sql = "select *from librocomprasiva where  transaccion='Egreso Dinero' 
		  and ($condicionFactura) order by idtransaccion asc;";	
		  
		  $libros = $db->consulta($sql);
		  $indice = 0;
		   while($datos = mysql_fetch_array($libros)){			   
			  $dia = explode("-",$datos['fechadeemision']); 
			 echo "<tr> 
 					  <td>$dia[2]</td>
					  <td>$indice</td>
					  <td>$datos[numdenitproveedor]</td>
                      <td>$datos[nomrazonsociprove]</td>
                      <td>$datos[numfacturaopoliza]</td> 
					  <td>$datos[numautorizacion]</td>   
					  <td>$datos[totalfactura]</td>  
					  <td>$datos[totalice]</td> 
					  <td>$datos[importeexcento]</td>
					  <td>$datos[importeneto]</td>
					  <td>$datos[creditofiscal]</td>
					  <td>$datos[codigodecontrol]</td>
					  <td>$datos[idtransaccion]</td>
                  </tr>";	
				$indice++;     
		   }
		 }
	   ?>
       </table>
  </div></td></tr></table>
</form>
<script>
   seleccionarCombo("cuenta",'<? echo $ModificarRegistro['cuenta'];?>');
   cargarTotales(<? echo $totalBs;?>,'<? echo $totalDolares;?>');
   transaccion ='<? echo $transaccion;?>';	
   setFacturasDetalle(); 	
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