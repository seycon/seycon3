<?php
     session_start();
	 include('conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	 
     }
	 $db = new MySQL();	 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateventas.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->



<!-- TinyMCE -- aumentar un simbolo de mayor para activar el editor de texto avanzado TinyMCE
<script language='javascript' type='text/javascript' src='jscripts/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript' src='conftiny.js'></script>
<!-- /TinyMCE -->
<link rel="stylesheet" type="text/css" href="ingresos/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
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

$("#fecha2").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha3").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha4").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

});


var $$ = function(id){
  return document.getElementById(id);	
}

var viewOptionReporte = function(value){
   var opciones = ['r1','r2'];
   var contenedor = ['r-1','r-2'];
   for (var k=0;k<opciones.length;k++){
	   if (opciones[k] == value){	
		$$(opciones[k]).style.background = "#666";
		$$(opciones[k]).style.border = "2px solid #51532e";   
		$$(opciones[k]).style.color = "#FFF";
		$$(contenedor[k]).style.display = "block";
	   }else{
		$$(opciones[k]).style.background = "#D4D4D4";
		$$(opciones[k]).style.border = "2px solid #CCC";		   
		$$(opciones[k]).style.color = "#51532e";
		$$(contenedor[k]).style.display = "none";
	   }
   }
}
	 
var viewreporteCaja = function(){
  window.open('traspaso/reporte_traspaso.php?tipo=caja&desde='+$$('fecha').value+'&hasta='+$$("fecha2").value+'&cuenta='+$$("cuenta").value,'target:_blank');	
}

var viewreporteBanco = function(){
  window.open('traspaso/reporte_traspaso.php?tipo=banco&desde='+$$('fecha3').value+'&hasta='+$$("fecha4").value+'&cuenta='+$$("cuenta2").value,'target:_blank');	
}

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





 
<!-- ventana modal -->

<form id="form1" method="" action="" autocomplete="off">
        
    
     <div id="overlay" class="overlays"></div>
     
     <div id="modal_mensajes" class="modal_mensajes">
      <div class="modal_cabecera">
         <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
         <div class="modal_cerrar"><img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
      </div>
      <div class="modal_icono_modal"><img src="iconos/alerta.png" width="24" height="24"></div>
      <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
      <div class="modal_boton1"><input type="button" value="Aceptar" class="boton_modal" onclick="closeMensaje()"/></div>
    </div>
     
	 

   <!-- <div id="ventana_modal2" class="ventana_modal"> 

    </div>-->

<table id="tablaContenido" width="70%;" align="center"> 
 <tr>
 <td>    
<div id="factura" class="cen">



     
         <div id="caption" class="caption">
           <table width="100%" border="0">
  <tr>
    <td width="9%">&nbsp;</td>
    <td width="78%" align="center">
           <div id="titulo">
           <strong style=" font-size:20px;" >
              REPORTES VENTA DE SERVICIOS</strong></div></td>
    <td width="13%">&nbsp;</td>
  </tr>
</table>

</div>
         
        
        <div id="datos_cliente" class="datos_cliente">

       </div>
       
       <div id="datos_factura" class="datos_cliente1"><br />
            <table width="100%" border="0">
                <tr>
                  <td width="4%">&nbsp;</td>
                  <td width="19%"><div class="radio"></div></td>
                  <td width="2%">&nbsp;</td>
                  <td width="40%"><div class="radio"></div></td>
                  <td width="31%">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td rowspan="2" valign="top">
                  <div class="menuopcionesreporte">
                    <div class="botonreporte" id="r1" onclick="viewOptionReporte('r1')"> Traspaso Caja</div>
                    <div class="espacioreporte"></div>
                    <div class="botonreporte2" id="r2" onclick="viewOptionReporte('r2')">Traspaso Banco</div>
                  
                  </div></td>
                  <td rowspan="2">&nbsp;</td>
                  <td colspan="2" rowspan="2" valign="top">
                   <div class="contenedorreportecentro" id="r-1" style="display:block">
                   <table width="100%" border="0">
  <tr>
    <td width="6%">&nbsp;</td>
    <td width="33%">&nbsp;</td>
    <td width="27%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="18%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4">Seleccione los datos correspondientes para realizar el reporte.</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Desde:</td>
    <td><input name="fecha" type="text" id="fecha" value="<?php echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Hasta:</td>
    <td><input name="fecha2" type="text" id="fecha2" value="<?php  echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Cuenta:</td>
    <td><select id="cuenta" name="cuenta" style="width:150px;background:#FFF;border:solid 1px #999;" >
      <option value="">-- Seleccione --</option>
      <?php
		  $sql = "select * from cajero c,usuario u where u.idtrabajador=c.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
		  $cajero = $db->arrayConsulta($sql);
		  if (isset($cajero['idcajero'])){
			echo "<option value='$cajero[cuentacaja1]'>$cajero[textocaja1]</option>";  
			echo "<option value='$cajero[cuentacaja2]'>$cajero[textocaja2]</option>"; 
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2"><input type="button" name="aceptar" id="aceptar" value="Realizar Reporte" onclick="viewreporteCaja();"  class="aceptar"/></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

                   
                   
                   </div>
                   <div class="contenedorreportecentro" id="r-2"> 
                   
        <table width="100%" border="0">
  <tr>
    <td width="6%">&nbsp;</td>
    <td width="33%">&nbsp;</td>
    <td width="27%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="18%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4">Seleccione los datos correspondientes para realizar el reporte.</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Desde:</td>
    <td><input name="fecha" type="text" id="fecha3" value="<?php echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Hasta:</td>
    <td><input name="fecha2" type="text" id="fecha4" value="<?php  echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Cuenta:</td>
    <td><select id="cuenta2" name="cuenta2" style="width:150px;background:#FFF;border:solid 1px #999;" >
      <option value="">-- Seleccione --</option>
      <?php
		  $sql = "select * from cajero c,usuario u where u.idtrabajador=c.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
		  $cajero = $db->arrayConsulta($sql);
		  if (isset($cajero['idcajero'])){
			echo "<option value='$cajero[cuentabanco1]'>$cajero[textobanco1]</option>";   
			echo "<option value='$cajero[cuentabanco2]'>$cajero[textobanco2]</option>"; 
			echo "<option value='$cajero[cuentabanco3]'>$cajero[textobanco3]</option>"; 
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2"><input type="button" name="aceptar" id="aceptar" value="Realizar Reporte" onclick="viewreporteBanco();"  class="aceptar"/></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>           
                   
                   
                   </div>
                  
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="100" >&nbsp;</td>
                  <td width="4%">&nbsp;</td>
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
     

  </div>
  </td></tr></table>
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