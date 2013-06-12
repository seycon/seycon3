<?php
     session_start();
	 include('conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])) {
         header("Location: index.php");	
     }
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
     $fileAcceso = $db->privilegiosFile($estructura['Agenda'],'Informe de Trabajo'
	 ,'nuevo_informetrabajo.php','listar_informetrabajo.php');
	 if ($fileAcceso['Acceso'] == "No") {
		header("Location: cerrar.php");	
	 }
	 $numrecibo = $db->getMaxCampo('idinformetrabajo', 'informetrabajo');
	 $sql = "select left(titulonotacobranza,30)as 'titulonotacobranza' from impresion where idimpresion = 1";
     $tituloPrincipal = $db->getCampo('titulonotacobranza', $sql);
  	 $transaccion = "insertar";     
	 if (isset($_GET['idinformetrabajo'])) {
		$sql = "select *from informetrabajo where idinformetrabajo=".$_GET['idinformetrabajo']; 
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
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->

<style type="text/css">
body {
scrollbar-arrow-color: #CCC;
scrollbar-base-color: #333;
scrollbar-dark-shadow-color: #666;
scrollbar-track-color: #999;
scrollbar-face-color: #666;
scrollbar-shadow-color: #333;
scrollbar-highlight-color: #CCCCCC;
}
</style>

<link rel="stylesheet" type="text/css" href="informetrabajo/style_ingreso.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="autocompletar/funciones.js"></script>
<script src="informetrabajo/NInforme.js"></script>
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
  
  $("#tiempoentrega").datepicker({
  showOn: "button",
  buttonImage: "css/images/calendar.gif",
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
<form id="form1" method="" action="">
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
<div class="menuTituloFormulario"> Agenda > Informe de Trabajo </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Informe de Trabajo"); 
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



  <table id="tablaContenido" class="cssFromGlobal" align="center" > 
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
<td></td>
<td colspan="3" align='right'>

        <table width="356" border="0">
          <tr>
            <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
            <td width="142">
			<?php 
			$idconbranza=0; 
			if (isset($_GET['idinformetrabajo'])) {
				$idconbranza = $_GET['idinformetrabajo'];
				echo $idconbranza; 
			} else {
				echo " ".$numrecibo+1;
			}
		    ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
            <input type="hidden" id="idReciboI" name="idReciboI" value="<?php echo $idconbranza;?>" />
          </tr>
        </table>     

    </td>
  </tr>
</table>

       
 <div id="datos_factura" >
      <table width="104%" border="0">
          <tr>
            <td width="11%"></td>
            <td width="16%" colspan="2"></td>
            <td width="11%">&nbsp;</td>
            <td width="16%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="16%"><div class="radio"></div></td>
            <td width="12%">&nbsp;</td>
            <td width="11%">&nbsp;</td>
          </tr>
          <tr>
            <td><div align="right"></div></td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><div align="right">Estado:</div></td>
            <td><select name="estado" id="estado" >
               <?php
               $selec = $maestro['estadocobranza']; 
               $tipo = array("terminado","proceso","cumplido");
               for ($i=0;$i<count($tipo);$i++){
                  $atributo = ""; 
                  if ($selec == $tipo[$i]){
                  $atributo = "selected='selected'";	
                  }
                  echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
               }	
              ?>
              
              
            </select></td>
            <td><div align="right">Privado:</div></td>
            <td><input type="checkbox" name="privado" id="privado" <?php if(isset($_GET['idinformetrabajo'])) 
            if ($maestro['privado']==1)echo "checked='checked'"; ?> />
            </td>
        </tr>
          <tr>
            <td><div>
              <div align="right">Cliente<span class="rojo">*</span>:</div>
            </div></td>
            <td><input style="width:95%" type="text" id="texto" onclick="this.select()" autocomplete="off" 
                onKeyUp="tipoBusqueda(event);" value="<?php 
                if(isset($_GET['idinformetrabajo'])){
                   $idcliente = $maestro['idcliente'];
                   if ($idcliente!=""){
                    $sql = "select nombre from cliente where idcliente=$idcliente";  
                    $consulta = mysql_query($sql);
                    $consulta = mysql_fetch_array($consulta);
                    echo $consulta['nombre'];
                   }
                }
                
                ?>"/>
              <div id="cliente" class="divresultado"></div>
                <input type="hidden" id="idpersonarecibida" value="<?php if(isset($_GET['idinformetrabajo'])) echo $idcliente?>"> 
            </td>
            <td><div id="autoL1" class="autoLoading"></div></td>
            <td><div align="right">N° Factura:</div></td>
            <td><input type="text"  style="width:70%" id="nrofactura" name="nrofactura" 
            value="<?php if(isset($_GET['idinformetrabajo'])) echo $maestro['nrofactura'];?>"/></td>
            <td><div align="right">Fecha:</div></td>
            <td><label for="receptor">
              <input name="fecha"  type="text" id="fecha" value="<?php if(isset($_GET['idinformetrabajo'])) 
			  echo $db->GetFormatofecha($maestro['fecha'],'-'); else  echo date("d/m/Y"); ?>" style="width:50%;" />
            </label></td>
            <td><div align="right">Firma Digital:</div></td>
            <td><input type="checkbox" name="firmadigital" id="firmadigital" <?php if(isset($_GET['idinformetrabajo'])) 
            if ($maestro['firmadigital']==1)echo "checked='checked'"; ?> />
              <label for="firmadigital"></label></td>
        </tr>
      </table>

</div>

<div id="producto" class="producto">
    <table width="100%" border="0">
      <tr>
        <td width="13%"><div align="right">DESCRIPCION:</div></td>
        <td width="29%"><input name="descripcion" type="text" id="descripcion" style="width:90%;" onfocus="this.select()" /> 
        </td>
        <td width="15%"><div align="right">IMPORTE:</div></td>
        <td width="18%">
          <input type="text" name="importe" id="importe" onkeyup="enterInput(event);" 
          onkeypress="return soloNumeros(event)" onfocus="this.select()"  style="width:80%;"/></td>
        <td width="8%"></td>
        <td width="17%">
         <input type="button" onclick="insertarNewItem('detalleTransaccion');" name="agregar" 
         id="agregar" value="Agregar [Enter]" class="aceptar"  /></td>
      </tr>
    </table>
</div>

  <div id="cuerpo" class="cuerpo">
      <table width="100%" border="0" id="tabla" style="margin-top:5px;">
        <tr style="background-image: url(iconos/fondo.jpg);">
          <td width="38" >&nbsp;</td>
          <th width="80" align="center">Nro.</th>
          <th width="732" align="center"> Descripción</th>
          <th width="206" align="center">Total</th>
        </tr>
        <tbody id="detalleTransaccion">
       
        <?php
        $totalT = 0;
          if (isset($_GET['idinformetrabajo'])){
            $sql = "select descripcion,importe from detalleinformetrabajo where idinformetrabajo="
			.$_GET['idinformetrabajo'];
            $detalle = mysql_query($sql);
            $i = 0;
             while($datos = mysql_fetch_array($detalle)){
                 $totalT = $totalT + $datos['importe'];
            $i++;	 
               echo "<tr>
               <td align='center'><img src='css/images/borrar.gif' style='cursor:pointer' onclick=eliminarFila(this) /></td>
               <td align='center'>$i</td>
               <td>$datos[descripcion]</td>
               <td align='center'>".number_format($datos['importe'],2)."</td>
               </tr>";					
             } 
          }			
        ?>            
         </tbody>                        
      </table> 
</div>
<br />        
        
  <table width="100%;" border="0">
  <tr> 
    <td width="110"><div align="right">Comentario</div></td>
    <td width="286">
    <input type="text"  id="comentario" name="comentario"
     style="width:70%" value="<?php if (isset($_GET['idinformetrabajo'])) echo $maestro['comentario']; ?>"/></td>
    <td width="85"><div align="right"><b>TOTAL:</b></div></td>
    <td width="158">
    <input type="text" id="total_ingreso" name="total_ingreso" value="0.00" readonly="readonly" />
    </td>
  </tr>
</table>
</div>
</td></tr></table> 

<script>
	cargarTotales(<?php echo $totalT;?>);		  
	transaccion = "<?php echo $transaccion;?>";
	seleccionarCombo("estado","<?php if(isset($_GET['idinformetrabajo'])) echo $maestro['estadocobranza']; else echo "";?>");		  
</script>

</form>
<br />
<br />     
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
