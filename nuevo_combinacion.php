<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 $db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
    }
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Combinación','nuevo_combinacion.php','listar_combinacion.php');
	if ($fileAcceso['Acceso'] == "No"){
	  header("Location: cerrar.php");	
	}

	
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql);	 
	 $sql = "select tituloegresoalm from impresion where idimpresion = 1";
	 $tituloC = $db->getCampo('tituloegresoalm', $sql);
	 $transaccion = 'insertar';
	 
	 if (isset($_GET['idcombinacion'])){
		$transaccion = 'modificar'; 
		$sql = "select * from combinacion where idcombinacion=$_GET[idcombinacion]";
		$combinacion = $db->arrayConsulta($sql);	 
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
<link rel="stylesheet" type="text/css" href="combinacion/style.css" />
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script async="async" src="autocompletar/funciones.js"></script>
<script src="combinacion/Ncombinacion.js"></script> 
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
       <div class="posicionClose">
       <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer"  onclick="accion();"></div>
       <div class="titleHeadCaption"> Registro de Datos</div>
    </div>
    <table align="center" width="100%">
    <tr>
      <th colspan="4"> 
      <input type="hidden" id="precioProducto" name="precioProducto" value=""/>
      <input type="hidden" id="conversion" name="conversion" value="" />
      </th>
    </tr>
    <tr>
      <th align="right">U.M.</th>
      <th align="center">Cantidad</th>
      <th width="30%">Unidad M.</th>
      <th width="17%">&nbsp;</th>
    </tr>
    <tr>
       <th width="27%" align="right"><input type="radio" id="pselectorU" name="pselectorU" value="UM" checked="checked"/></th>
       <th width="26%">
       <input id="cant" name="cant" type="text"  size="12" onkeypress="return soloNumeros(event);" 
       onkeyup="eventoIngresoCantidad(event);"/></th>
       <th><input type="text" id="unidadmedida" name="unidadmedida" style="width:80px;" disabled="disabled"/></th>
       <th>&nbsp;</th>
    </tr>
    <tr>
      <th height="10" align="right"><input type="radio" id="pselectorU" name="pselectorU" value="UA"/></th>
      <th height="10"><input id="cantA" name="cantA" onkeypress="return soloNumeros(event);"
            type="text"  size="12" onkeyup="eventoIngresoCantidad(event);"/></th>
      <th height="10"><input type="text" id="unidadalternativa" name="unidadalternativa" style="width:80px;" 
      disabled="disabled"/></th>
      <th height="10">&nbsp;</th>
    </tr>
    <tr>
      <th height="10">&nbsp;</th>
      <th height="10" colspan="2">&nbsp;</th>
      <th height="10">&nbsp;</th>
    </tr>
    <tr>
       <th height="10">&nbsp;</th>
       <th height="10" colspan="2"></th>
       <th height="10">&nbsp;</th>
     </tr>
  </table>
  <div class="boton1_subventana">
    <input onclick="validarEntradaVentana();" type="button" value="Aceptar"  class="botonNegro"/>
  </div>
  <div class="boton2_subventana">
    <input onclick="accion();" type="button" value="Cancelar" id="aceptar_modal"  class="botonNegro"/>
  </div>
</div> 
</div>    
    
    <div id="gif" class="gifLoader"></div>
    <div id="overlay" class="overlays"></div>
 
	<div id="modal_vendido" class="modal_vendidos"> 
             <div class="caption_modal">
               <img style="cursor:pointer" onclick="location.href='solicitud.php';">&nbsp;&nbsp;&nbsp; 
              <strong>Que desea hacer ?</strong>
            </div>
            <br />
            <br />
            <table align="center">
            <tr>
            
            <td><input id="fac" type="button" value="Ver Reporte [F1]" 
            onclick="irDireccion('reportes/reporte_solicitud.php?idsolicitud='+codigo_solicitud)" 
            class="aceptar"/></td>
            <td><input type="button" value="Ver en pdf [F2]" 
            onclick="irDireccion('reportes/imprimir_SolitudProductos.php?idsolicitud='+codigo_solicitud)" 
            class="aceptar"/></td>
            <td></td>
            </tr>
            </table>   
    </div> 
    

<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Inventario > Combinación </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Combinación"); 
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
        <input type="button" value="Guardar [F2]" id="vender" onclick="enviarDetalle()" class="botonNegro" />&nbsp;&nbsp;
	   <?php 
        if ($fileAcceso['File'] == "Si"){
         echo '<input name="cancelar" type="button" class="botonNegro" id="cancelar" value="Cancelar [F4]" onclick="salir()"/>';	
        }
        ?> 
    </td>
<td>&nbsp;</td>
<td colspan="3" align='right'>

        <table width="356" border="0">
          <tr>
            <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
            <td width="142">
			<?php 
			  if (isset($_GET['idcombinacion'])) {
				echo $_GET['idcombinacion'];  
			  } else {
				echo  $db->getNextID('idcombinacion','combinacion'); 
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
    <td width="25%"><div align="right"></div></td>
    <td width="27%"><input type="hidden" id="idcombinacion" name="idcombinacion" value="<?php echo $combinacion['idcombinacion'];?>" /><br />

    </td>
    <td width="21%"><div align="right"></div></td>
    <td width="27%"><div id="anticipodiv" style="visibility:hidden; width:1px; display:inline;">
  
    </div></td>
  </tr>
  <tr>
    <td align="right">Nombre Combinación<span class='rojo'>*</span>:</td>
    <td><div align="left">
          <input type="text" name="nombre" id="nombre" value="<?php echo $combinacion['nombre'];?>" style="width:80%" />
    </div></td>
    <td align="right">Tipo de Combinación:</td>
    <td>
    <select id="tipocombinacion" name="tipocombinacion" class="eCombo">
      <option value="">-- Seleccionar --</option>
      <?php
       $sql = "select idtipocombinacion,nombre from tipocombinacion where estado=1;";
       $db->imprimirCombo($sql);
	  ?>        
    </select></td>
    </tr>
  <tr>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
    <td><input type="hidden" id="Ttransaccion" value="<?php 
	if (isset($_GET['idsolicitud']))
	 echo "modificarSolicitud";
	 else
	 echo "insertarSolicitud";	
	
	?>" /></td>
    <td><input size="17" type="hidden" id="responsable" style="border:solid 1px #999;" 
    value="<?php echo $_SESSION['nombre_usuario']; ?>"/></tr>
  </table>

</div>
      <div id="producto" class="producto">     
         <table width="100%" border="0">
           <tr>
            <td width="13%"><div align="right"><strong>Producto:</strong></div></td>
            <td width="18%">
              <input type="text" autocomplete="off" id="dato" onkeyup="autocompletar(event,this.id)"/><br>
            <div  id="resultados"  class="divresultado" >
                </div>
               <input type="hidden" id="codidproducto" />
                </td>
            <td width="58%"><div id="autoL1" class="autoLoading"></div></td>
            <td width="11%">T.C.: <? echo $tc;?>
             <input id="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" ></td>
          </tr>
        </table>
</div>
        
      <div id="cuerpo" class="cuerpo">
      <table width="100%" border="0" id="tabla" style="margin-top:5px;">
        <tr style="background-image: url(iconos/fondo.jpg);">
          <th width="18" >&nbsp;</th>
          <th width="59"><div align="center">Código</div></th>
          <th width="332"><div align="center">Descripción</div></th>
          <th width="77"><div align="center">Cantidad</div></th>
          <th width="77" align="center">U.M.</th>
          <th width="90"><div align="center">P/Unit.</div></th>
          <th width="113"><div align="center">Total</div></th>
        </tr>   
                  
        <tbody id="detalleSolicitud">
           <?php
           $totalG = 0;
             if (isset($combinacion['idcombinacion'])){
              $sql = "select p.idproducto,p.nombre,dc.cantidad,round(dc.precio,4)as 'precio',round(dc.total,4)
              as 'total',dc.unidadmedida from detallecombinacion dc,combinacion c,producto p
              where dc.idcombinacion = c.idcombinacion and dc.idproducto=p.idproducto 
              and c.idcombinacion=$combinacion[idcombinacion]; ";
              $detalle = $db->consulta($sql);
              $cant = 0;				  
               while($dato = mysql_fetch_array($detalle)){	
                $totalG = $totalG + $dato['cantidad'] * $dato['precio'];		    
                $cant = $cant +1 ;
                  echo "<tr bgcolor='#F6F6F6'>";
                  echo "  <td><img src='css/images/borrar.gif' style='cursor:pointer' onclick='eliminarFila(this)'/></td>";
                  echo "  <td align='center'>$dato[idproducto]</td>";
                  echo "  <td>$dato[nombre]</td>";
                  echo "  <td align='center'>$dato[cantidad]</td>";
                  echo "  <td align='center'>$dato[unidadmedida]</td>";
                  echo "  <td align='center'>".number_format($dato['precio'],4)."</td>";
                  echo "  <td align='center'>".number_format($dato['total'],4)."</td>";
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
    <textarea id="glosa" style="width:98%; height:50px;" ><?php echo $combinacion['glosa']; ?></textarea></td>
  </tr>
  <tr>
    <td height="35">&nbsp;</td>
  </tr>
</table>
 </div>
 <div id="pie_der" class="pie_der" >
 <table width="99%">
  <tr >
    <td width="10%"><div align="right" ></div></td>
    <td width="35%"><div align="right">TOTAL Bs.:</div></td>
    <td width="55%"><input type="text" id="subtotalBS" name="subtotalBS" onkeyup="calcularTotalDolares();"/>
    </td>
  </tr>
  <tr >
    <td><div align="right"></div></td>
    <td ><div align="right">TOTAL $us.:</div></td>
    <td>
    <input type="text" id="subtotalDL" name="subtotalDL" onkeyup="calcularTotalBs();"/>
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
  setTotal(<?php echo $combinacion['total']; ?>);  
  seleccionarCombo("tipocombinacion","<?php echo $combinacion['idtipocombinacion']; ?>");
  transaccion = '<?php echo $transaccion; ?>';
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