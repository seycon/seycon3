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
	 $fileAcceso = $db->privilegiosFile($estructura['Ventas'],'Ruta de Entrega','nuevo_rutaentrega.php','listar_rutaentrega.php');
	 if ($fileAcceso['Acceso'] == "No") {
		 header("Location: cerrar.php");	
	 }	 
	 $transaccion = "insertar";	
	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql); 
	 
     if (isset($_GET['idrutaentrega'])) {		 
	     $sql = "select * from rutaentrega where idrutaentrega=".$_GET['idrutaentrega']; 
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
<link rel="stylesheet" type="text/css" href="rutaentrega/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="rutaentrega/rutaentrega.js"></script>
<script src="lib/Jtable.js"></script>
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
  
  $("#fechaEntrega").datepicker({
  showOn: "button",
  buttonImage: "css/images/calendar.gif",
  buttonImageOnly: true,
  dateFormat: 'dd/mm/yy'
  });
  
  $("#fechaFinal").datepicker({
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
  
  
  
 <!-- Page Mensajes de Advertencias --> 
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

 <!-- Page de Registro de La transacción -->     
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
      


 <!-- Page Principal de Ruta de Entrega --> 

<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Ventas > Ruta de Entrega </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Ventas'];
   $privilegios = $db->getOpciones($menus, "Ruta de Entrega"); 
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
      <input type="button" value="Guardar [F2]" id="vender" onclick="enviarTransaccion()" class="botonNegro" />
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
			if (isset($_GET['idrutaentrega'])) {
			 echo $_GET['idrutaentrega'];
			} else {
			  echo $db->getNextID("idrutaentrega","rutaentrega");	
			}					
		   ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
            <input type="hidden" id="tc" value="<? echo $tc?>" />
            <input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"  />
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
      <td><input type='hidden' id='idTransaccion' value='<?php echo $ModificarT['idrutaentrega'];?>' /></td>
      <td width="15%">&nbsp;</td>
      <td width="19%" colspan="2" >&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Fecha:</td>
      <td width="19%">
        <input name="fecha" type="text" id="fecha"  style="border:solid 1px #999;" size="10" class="date" value="<? 
         if (isset($_GET['idrutavisita'])){
          echo $db->GetFormatofecha($ModificarT['fecha'],"-"); 	
         }else{ 
          echo date("d/m/Y"); 
         }
        ?>"  />
        </td>
      <td width="16%" align="right">&nbsp;</td>
      <td width="19%">&nbsp;</td>
      <td align="right">T.C.:</td>
      <td colspan="2">
       <? echo $tc;?>
       <input id="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" >
      </td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td colspan="2">&nbsp;</td>   
    </tr>
  </table>
  </div>

  <div id="producto" class="producto">    
 
     <table width="100%" border="0">
       <tr>
        <td width="19%" align="right"><strong>Trabajador:</strong></td>
        <td width="20%">
          <input type="text"  id="dato" autocomplete="off" onkeyup="autocompletar(event,this.id)"/><br>
            <div  id="resultados"  class="divresultado" >
            </div>
           <input type="hidden" id="codigotrabajador" />
            </td>
        <td width="3%"><div id="autoL1" class="autoLoading"></div></td>
        <td width="7%" align="right"><strong>Sucursal:</strong></td>
        <td width="8%">
           <select name="sucursal" id="sucursal" style="width:130px;background:#FFF;border:solid 1px #999;">
            <option value="" selected="selected">-- Todas --</option>
            <?php
                $sucursal = "select  sl.idsucursal,left(sl.nombrecomercial,25) 
                from sucursal sl where sl.estado=1 order by sl.nombrecomercial;";		
                $db->imprimirCombo($sucursal);	
             ?>
          </select>
        </td>
        <td width="15%" align="right"><strong>Fecha Entrega:</strong></td>
        <td width="12%">
          <input name="fechaEntrega" type="text" id="fechaEntrega"  style="border:solid 1px #999;" 
          size="10" class="date" value="<?php echo date("d/m/Y"); ?>"  />
        </td>
        <td width="5%" align="right">&nbsp;</td>
        <td width="10%"><input type="button"  class='botonNegro' value="Buscar" onclick="insertarNewItem()"/></td>
        <td width="1%">&nbsp;</td>
       </tr>
    </table>

  </div>
        
      <div id="cuerpo" class="cuerpo">
          <table width="100%" border="0" id="tabla">
           <tr class="filadetalleui">
              <td width="17" >&nbsp;</td>
              <th width="83" align="center">Nº Venta</th>
              <th width="153" align="center">Sucursal</th>
              <th width="207" align="center">Trabajador</th>
              <th width="143" align="center">Cliente</th>
              <th width="123" align="center">Ruta</th>
              <th width="81" align="center">F/Entrega</th>
              <th width="20" align="center"></th>
            </tr>      
                      
            <tbody id="detalleT">
               <?php			   
			     if (isset($_GET['idrutaentrega'])) {
				  $sql = "select nv.numero,nv.idnotaventa,nv.fechaentrega,left(c.nombre,15)as 'cliente'
				  ,left(concat(t.nombre,' ',t.apellido),20)as 'trabajador',
				  left(ru.nombre,15)as 'ruta',left(s.nombrecomercial,20) as 'sucursal',
				  t.idtrabajador,nv.idnotaventa,dr.idruta,dr.iddetallerutaentrega      
				  from detallerutaentrega dr,rutaentrega r,notaventa nv,ruta ru,
				  trabajador t,cliente c,sucursal s 
				  where dr.idrutaentrega=r.idrutaentrega 
				  and dr.idnotaventa=nv.idnotaventa 
				  and dr.idtrabajador=t.idtrabajador 
				  and nv.idcliente=c.idcliente 
				  and dr.idruta=ru.idruta 
				  and nv.idsucursal=s.idsucursal 
				  and r.idrutaentrega=$_GET[idrutaentrega] order by dr.iddetallerutaentrega asc;";
					 
			       $consulta = $db->consulta($sql);
					$fil = -1;
					while($dato = mysql_fetch_array($consulta)) {
					  $fil++;				
					  $fechaEntrega = $db->GetFormatofecha($dato['fechaentrega'], "-");  
					  $idcheck = "codentrega$fil";
				      echo "<tr bgColor='#F6F6F6'>";
                      echo "  <td align='center'>
					  <img src='css/images/borrar.gif' style='cursor:pointer' title='eliminar' onclick='eliminarFila(this)'/>
					  </td>";
                      echo "  <td align='center'>NV-$dato[numero]</td>";
                      echo "  <td>$dato[sucursal]</td>";
				      echo "  <td>".$dato['trabajador']."</td>";
					  echo "  <td>".$dato['cliente']."</td>";					  
                      echo "  <td align='center'>".$dato['ruta']."</td>";
					  echo "  <td align='center'>$fechaEntrega</td>";
					  echo "  <td align='center'><input type='checkbox' id='$idcheck' name='$idcheck' checked='checked'/></td>";
					  echo "  <td align='center' style='display:none;'>".$dato['idtrabajador']."</td>";
					  echo "  <td align='center' style='display:none;'>".$dato['idnotaventa']."</td>";
					  echo "  <td align='center' style='display:none;'>".$dato['idruta']."</td>";
                      echo "</tr>";
					}
				 }
			   ?>
            
            </tbody >
             
          </table>
                  
         
</div>
     </div>
    </td></tr></table></form>
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