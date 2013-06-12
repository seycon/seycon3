<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	 
     }

	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
 	 $fileAcceso = $db->privilegiosFile($estructura['Contabilidad'],'Impuesto Mensual','nuevo_impuestos.php','listar_impuestos.php');
	if ($fileAcceso['Acceso'] == "No"){
	  header("Location: cerrar.php");	
	}

	 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	 $tc = $db->getCampo('dolarcompra',$sql); 
	 
	 $transaccion = "insertar";
	 if (isset($_GET['idimpuesto'])) {		 
		$sql = "select * from impuestos where idimpuesto=".$_GET['idimpuesto']; 
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
<link rel="stylesheet" type="text/css" href="impuestos/impuestos.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="lib/Jtable.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
<script src="impuestos/NImpuestos.js"></script>
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
          <table align="center" width="85%" style="margin-top:40px;">
            <tr>            
             <td><input type="button" value="Ver Reporte [F6]" onclick="accionPostRegistro();" class="botonNegro"/></td>
             <td></td>
             <td></td>
            </tr>
           </table>   
        
      </div>
  </div>
</div>



<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Contabilidad > Impuesto Mensual </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Contabilidad'];
   $privilegios = $db->getOpciones($menus, "Impuesto Mensual"); 
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
     <input type="button" name="aceptar" id="aceptar" value="Guardar [F2]" onclick="ejecutarTransaccion();"
       class="botonNegro"/> 	          
	 <?php 
      if ($fileAcceso['File'] == "Si") {
	      echo '&nbsp;&nbsp; <input name="cancelar" type="button" class="botonNegro" id="cancelar" 
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
             	 if (isset($_GET['idimpuesto'])) {
					  echo $ModificarRegistro['idimpuesto'];		  
				 } else {
					  echo $db->getNextID("idimpuesto","impuestos");
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


       
       <div id="datos_factura" class="datos_cliente1"><br />
            <table width="100%" border="0">
                <tr>
                  <td width="13%"></td>
                  <td width="15%"></td>
                  <td width="18%">
                  <input type="hidden" id="idTransaccion" name="idTransaccion" 
                  value="<?php echo $ModificarRegistro['idimpuesto'];?>"/> </td>
                  <td width="19%"><div class="radio"></div></td>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td >&nbsp;</td>
                  <td align="right">Mes:                  </td>
                  <td align="left">
                  <select name="mes" id="mes" >
                    <option value="">--Seleccione--</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                   </select>
                  </td>
                  <td align="right">Fecha Vencimiento:</td>
                  <td width="19%">
                  <input name="fecha" type="text" id="fecha" value="<?php 
				  if (isset($_GET['idimpuesto'])){
					  echo $db->GetFormatofecha($ModificarRegistro['fechavencimiento'],'-');
				  }else{				  
				  echo date("d/m/Y");} ?>" size="12"  /></td>
                  <td width="16%">T.C.:<?php echo $tc;?>
                  <input type="hidden" id="tipocambio" name="tipocambio" value="<?php echo $tc;?>"/>
                  </td>
                </tr>
                <tr>
                  <td >&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
            </table>

</div>
       <div id="cuerpo" class="cuerpo">
        <table width="100%" height="315" border="0">
  <tr>
    <td height="29">&nbsp;</td>
    <td class="tituloFormulario">FORMULARIO 200</td>
    <td>&nbsp;</td>
    <td class="tituloFormulario">FORMULARIO 400</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="6%" height="280">&nbsp;</td>
    <td width="43%" class="borderFormulario" valign="top">
    <table width="100%" border="0">
  <tr>
    <td width="59%">&nbsp;</td>
    <td width="41%">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Ventas del Mes:</td>
    <td><input name="ventas200" type="text" id="ventas200" value="" size="8"  /></td>
  </tr>
  <tr>
    <td align="right">Compras del Mes:</td>
    <td><input name="compras" type="text" id="compras" value="" size="8"  /></td>
  </tr>
  <tr>
    <td align="right">Pago de IVA (a):</td>
    <td><input name="saldoiva" type="text" id="saldoiva" value="" size="8"  /></td>
  </tr>
  <tr>
    <td colspan="2" class="borderSuperior">&nbsp;</td>
    </tr>
    </table>

    <table width="100%" border="0">
  <tr>
    <td width="65%" align="right">Saldo IVA Actualizado del Anterior Mes (b):</td>
    <td width="35%"><input name="actualizacioniva" type="text" id="actualizacioniva" value="" size="8"  /></td>
  </tr>
  <tr>
    <td align="right">Suma Total de IVA(a+b):</td>
    <td><input name="sumaTotal" type="text" id="sumaTotal" value="" size="8"  /></td>
  </tr>
  <tr>
    <td align="right">Pago de IVA del Mes:</td>
    <td><input name="pagoiva" type="text" id="pagoiva" value="" size="8"  /></td>
  </tr>
</table>

    
    
    </td>
    <td width="3%">&nbsp;</td>
    <td width="43%" class="borderFormulario" valign="top">
    <table width="100%" border="0">
      <tr>
        <td width="59%">&nbsp;</td>
        <td width="41%">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Ventas del Mes:</td>
        <td><input name="ventas400" type="text" id="ventas400" value="" size="8"  /></td>
      </tr>
      <tr>
        <td align="right">Saldo IUE:</td>
        <td><input name="saldoiue" type="text" id="saldoiue" value="" size="8"  /></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" class="borderSuperior">&nbsp;</td>
        </tr>
    </table>
    <table width="100%" border="0">
      <tr>
        <td width="65%" align="right">Pago de IT del Mes:</td>
        <td width="35%"><input name="pagoit" type="text" id="pagoit" value="" size="8"  /></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    <td width="5%">&nbsp;</td>
  </tr>
</table>

       
       </div>

    <div>
        <table width="100%" border="0">
          <tr>
            <td width="6%" height="88" align="right">&nbsp;</td>
            <td class="borderFormulario">
            <table width="100%" border="0">
          <tr>
            <td width="13%">Sucursal Nº</td>
            <td width="4%"> 0</td>
            <td width="16%" align="right">Ultima factura:</td>
            <td width="9%"><input name="facturaS0" type="text" id="facturaS0" value="" size="8"  /></td>
            <td width="20%" align="right">Talonario Actual:</td>
            <td width="9%"><input name="talonarioS0" type="text" id="talonarioS0" value="" size="8"  /></td>
            <td width="18%" align="right">Ultimo Talonario:</td>
            <td width="11%"><input name="UtalonarioS0" type="text" id="UtalonarioS0" value="" size="8"  /></td>
          </tr>
          <tr>
            <td colspan="8" class="borderSuperior">&nbsp;</td>
            </tr>
          <tr>
            <td>Sucursal Nº</td>
            <td>1</td>
            <td align="right">Ultima factura:</td>
            <td><input name="facturaS1" type="text" id="facturaS1" value="" size="8"  /></td>
            <td align="right">Talonario Actual:</td>
            <td><input name="talonarioS1" type="text" id="talonarioS1" value="" size="8"  /></td>
            <td align="right">Ultimo Talonario:</td>
            <td><input name="UtalonarioS1" type="text" id="UtalonarioS1" value="" size="8"  /></td>
          </tr>
        </table>            
            
            </td>
            <td width="5%">&nbsp;</td>
          </tr>
       </table>
     </div>   
  </div></td></tr></table>
</form>
<script>
   seleccionarCombo("mes",'<? echo $ModificarRegistro['mes'];?>');
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