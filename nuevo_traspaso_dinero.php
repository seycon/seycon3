<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
     }
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
	 $fileAcceso = $db->privilegiosFile($estructura['Contabilidad'],'Traspaso de Dinero'
	 ,'nuevo_traspaso_dinero.php','listar_traspasoDinero.php');
	  if ($fileAcceso['Acceso'] == "No"){
		header("Location: cerrar.php");	
	  }
	 $sql = "select left(titulotraspasodinero,30)as 'titulotraspasodinero' from impresion where idimpresion = 1";
     $tituloPrincipal = $db->getCampo('titulotraspasodinero', $sql);
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql);
	 $transaccion = "insertar";
	 if (isset($_GET['idtraspaso'])){		 
		$sql = "select * from traspasodinero where idtraspaso=".$_GET['idtraspaso']; 
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
<link rel="stylesheet" type="text/css" href="traspaso/style.css" />
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="lib/Jtable.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="traspaso/NTraspaso.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script>
$(document).ready(function()
{

<?php	
 if (!isset($ModificarRegistro['idtraspaso'])){
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

  
<form id="form1" name="form1" method="" action="">

<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Contabilidad > Traspaso de Dinero </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Contabilidad'];
   $privilegios = $db->getOpciones($menus, "Traspaso de Dinero"); 
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
            <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
            <td width="142">
			<?php 
              if (isset($_GET['idtraspaso'])) {
				echo $ModificarRegistro['idtraspaso'];
			  }	else {
				echo $db->getNextID("idtraspaso", "traspasodinero");  
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
              <td colspan="3">
                
             </td>
              <td colspan="2">
              <input type="hidden" name="idTransaccion" id="idTransaccion" value="<?php echo $ModificarRegistro['idtraspaso']?>"/>
              </td>
              <td width="2%">&nbsp;</td>
              <td width="10%">&nbsp;</td>
              <td width="16%">            
              </td>
            </tr>
            <tr>
              <td width="21%">&nbsp;</td>
              <td width="21%" colspan="2">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>T.C.: <?php 
              if (isset($ModificarRegistro['idtraspaso']))
              $tc = $ModificarRegistro['tipocambio'];
              echo $tc;?>
               <input id="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" ></td>
            </tr>
            <tr>
              <td align="right">Sucursal<span class="rojo">*</span>:</td>
              <td colspan="2"><select name="sucursal" id="sucursal" style="width:143px;">
                <option value='' selected='selected'>-- Seleccione --</option>
                <?php
                  $almacen = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1";
                  $db->imprimirCombo($almacen,$ModificarRegistro['idsucursal']);
                ?>
              </select></td>
              <td width="12%" align="right">Caja/Banco<span class="rojo">*</span>:</td>
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
              <td align="right">Fecha:</td>
              <td><input name="fecha" <?php if (isset($ModificarRegistro['idtraspaso'])) echo "disabled='disabled'"; ?> type="text" id="fecha" 
              value="<?php
              if (isset($ModificarRegistro['fecha']))
               echo $db->GetFormatofecha($ModificarRegistro['fecha'],'-');
              else
               echo date("d/m/Y"); 
               ?>" size="9"  /></td>
            </tr>
            <tr>
              <td align="right"><select name="receptor" id="receptor"  onchange="cambiarDependencias();"
               style="width:130px; background:#FFF;border:solid 1px #999;">
               <?php
                 $selec = $ModificarRegistro['tipopersona']; 
                 $tipo = array("trabajador","otros");
                 for ($i = 0; $i < count($tipo); $i++) {
                    $atributo = ""; 
                    if ($selec == $tipo[$i]){
                    $atributo = "selected='selected'";	
                    }
                    echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
                 }	
	           ?>
                    
                    
                  </select></td>
                  <td><input style="width:90%" type="text" id="texto" onclick="this.select()" onkeyup="tipoBusqueda(event);" 
    value="<?php  
	if (isset($ModificarRegistro['tipopersona'])){
	  if ($ModificarRegistro['tipopersona'] == "otros"){
		echo $ModificarRegistro['nombrepersona'];  
	  }else{
	    $sql = "select nombre from $ModificarRegistro[tipopersona] where id$ModificarRegistro[tipopersona]=$ModificarRegistro[idpersona]"; 
	    $dato = $db->arrayConsulta($sql);
	    echo $dato['nombre'];
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
               <td width="60" align="right">Destino:</td>
               <td width="180">               
               <select name="trabajadorD" id="trabajadorD"  style="width:75%;" onchange="consultarCuentasTrabajador()">          
                <option>- Seleccione -</option>
                <?php
		         $sucursal = "select left(s.nombrecomercial,40),t.idtrabajador,left(concat(nombre,' ',apellido),20)
				  from trabajador t,sucursal s,cajero c 
                  where t.idsucursal=s.idsucursal and t.estado=1  
				  and t.idtrabajador=c.idtrabajador order by s.nombrecomercial;";			
	             $db->imprimirComboGrupo($sucursal,"Sucursal:","");			
	            ?>
              </select>
              <input type="hidden" id="nombreTrabajadorD" name="nombreTrabajadorD" />
              </td>
               <td width="108" align="right">Caja-Banco:</td>
               <td width="157" align="left">
               <select id="cuentaD" name="cuentaD" style="width:70%">
                 <option value='' selected='selected'>- Seleccione --</option>                				
               </select></td>
               <td width="26">Bs.:</td>
               <td width="67">
               <input type="text" name="bolivianosD" id="bolivianosD" value="" size="9" 
               onkeypress="return soloNumeros(event);" onkeyup="eventoText(event);"/></td>
               <td width="37">$us.:</td>
               <td width="65">
               <input type="text" name="dolaresD" id="dolaresD" value="" size="9" onkeypress="return soloNumeros(event);"
                onkeyup="eventoText(event);"/></td>
               <td width="87"><input type="button"  class='botonNegro' style="width:110px;" value="Registrar [Enter]"
                onclick="insertarNewItem('detalleTransaccion')"/></td>
             </tr>
           </table>
         </div>
       </div>
        
      <div id="cuerpo" class="cuerpo" >
        <div id="cuerpo2" style="position:relative;height:330px;overflow:auto;">
          <table width="100%" border="0" id="tabla" >
            <tr class="filadetalleui">
              <th width="19" >&nbsp;</th>
              <th width="20" align="center">Nº</th>
              <th width="147" align="center" style="display:none;">Cuenta</th>
              <th width="147" align="center">Cuenta</th>
              <th width="206" align="center">Trabajador</th>
              <th width="60" align="center">Bs.:</th>
              <th width="60" align="center">$us.:</th>   
              <th width="60" align="center" style="display:none">idtrabajador</th>            
            </tr>
            <tbody id="detalleTransaccion">
              <?php			  
			  $totalBs = 0;
			  $totalDolares = 0;
			 if (isset($ModificarRegistro['idtraspaso'])){
               $sql = "select left(concat(t.nombre,' ',t.apellido),40)as 'nombre' , dt.*,p.cuenta from 
			   detalletraspasodinero dt,trabajador t,plandecuenta p where 
			   dt.idtrabajador=t.idtrabajador and p.estado=1 and p.codigo=dt.idcuenta and idtraspaso=$ModificarRegistro[idtraspaso]";
			   $detalle = $db->consulta($sql);
			   $i = 0;
			     while($dato = mysql_fetch_array($detalle)){
					$totalBs = $totalBs + $dato['montobolivianos'];
  				    $dolares = round(($dato['montodolares']/$ModificarRegistro['tipocambio']),4);
					$totalDolares = $totalDolares + $dolares;
				  $i++; 
			       echo "
				    <tr> 
 					  <td align='center'><img src='css/images/borrar.gif' title='Anular' alt='borrar' 
					  onclick='eliminarFila(this)' /></td>
					  <td align='center'>$i</td>
                      <td style='display:none'>$dato[idcuenta]</td>
					  <td >$dato[cuenta]</td>
                      <td >$dato[nombre]</td> 
					  <td align='center'>".number_format($dato['montobolivianos'],2)."</td>   
					  <td align='center'>".number_format($dolares,2)."</td> 
					  <td align='center' style='display:none'>$dato[idtrabajador]</td>  
                    </tr>";	 
			     }
			 }
			?>
            </tbody>
          </table>
        </div>
      </div>

        <table width="100%" border="0">
          <tr>
            <td width="16%" align="right">Glosa:</td>
            <td width="48%" align="left"><input name="glosa" type="text" id="glosa" style="width:80%" 
            value="<?php echo $ModificarRegistro['glosa'];?>"/></td>
            <td width="11%" align="right"><strong>TOTAL:</strong></td>
            <td width="25%">
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
       

  </div></td></tr></table>
</form>
<script>
   seleccionarCombo("cuenta",'<? echo $ModificarRegistro['cuenta'];?>');
   cargarTotales(<? echo $totalBs;?>,'<? echo $totalDolares;?>');
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