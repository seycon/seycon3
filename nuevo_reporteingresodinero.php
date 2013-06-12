<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
     session_start();
	 include('conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	 
     }
	 $db = new MySQL();	
	 $estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Contabilidad'],'Ingreso de Dinero','nuevo_reporteingresodinero.php')){
	  header("Location: cerrar.php");	
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
<link rel="stylesheet" type="text/css" href="ingresos/style.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
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

$("#fecha5").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha6").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha7").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha8").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha9").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha10").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha11").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});

$("#fecha12").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
});
});


var $$ = function(id){
  return document.getElementById(id);	
}

var viewOptionReporte = function(value) {
   var opciones = ['r1','r2', 'r3', 'r4', 'r5', 'r6'];
   var contenedor = ['r-1', 'r-2', 'r-3', 'r-4', 'r-5', 'r-6'];
   for (var k=0; k < opciones.length; k++){
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
  window.open('ingresos/reporte_ingresodinero.php?tipo=caja&desde='+$$('fecha').value
  +'&hasta='+$$("fecha2").value+'&cuenta='+$$("cuenta").value
  +"&moneda=" + $$("monedacaja").value,'target:_blank');	
}

var viewreporteBanco = function(){
  window.open('ingresos/reporte_ingresodinero.php?tipo=banco&desde='+$$('fecha3').value
  +'&hasta='+$$("fecha4").value+'&cuenta='+$$("cuenta2").value+"&moneda="
  + $$("moneda").value,'target:_blank');	
}

var viewreporteMovimientoCaja = function(){
  window.open('ingresos/reporte_movimientodinero.php?tipo=caja&desde='+$$('fecha5').value
  +'&hasta='+$$("fecha6").value+'&cuenta='+$$("cuenta5").value,'target:_blank');	
}

var viewreporteMovimientoBanco = function(){
  window.open('ingresos/reporte_movimientodinero.php?tipo=banco&desde='+$$('fecha7').value
  +'&hasta='+$$("fecha8").value+'&cuenta='+$$("cuenta7").value,'target:_blank');	
}

var viewreporteIngresoCliente = function(){
  window.open('ingresos/reporte_ingresocliente.php?idbeneficiario='
  +$$("idpersonarecibida").value + "&tipobeneficiario=" + $$("receptor").value 
  +'&desde='+$$('fecha11').value+'&hasta='+$$("fecha12").value+'&cuenta='+$$("cuenta9").value
  +"&moneda="+ $$("monedacliente").value,'target:_blank');	
}

var viewreporteIngresoCuenta = function(){
  window.open('ingresos/reporte_ingresocuenta.php?cuenta='+$$("modeloplan").value+'&desde='+$$('fecha9').value
  +'&hasta='+$$("fecha10").value+'&caja='+$$("cuenta8").value
  +"&moneda="+ $$("monedacuenta").value,'target:_blank');	
}


 var tipoBusqueda = function(e){
	 var sql;
	 tipocliente = $$('receptor').value;
	  idconsulta = "id"+tipocliente;   
	 switch(tipocliente){
	  case 'trabajador':
	  sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t where estado=1 and  ";
	  break;	
	 case 'cliente':
	   sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
	 break;
	 case 'proveedor':
	   sql = "select idproveedor,left(nombre,20) as 'nombre' from proveedor where estado=1 and ";
	 break;
	  }		
	  eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultadoEgreso'
	  ,'autocompletar/consultor.php',sql,'','autoL1');

 }
	 
	 
 var eventoResultadoEgreso = function(resultado,codigo){
	  $$("texto").value= resultado;
	  $$("idpersonarecibida").value = codigo;	  	   
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
<form id="form1" method="" action="" autocomplete="off">
        
    
     <div id="overlay" class="overlays"></div>
     
     <div id="modal_mensajes" class="modal_mensajes">
      <div class="modal_cabecera">
         <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
         <div class="modal_cerrar">
         <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()">
         </div>
      </div>
      <div class="modal_icono_modal"><img src="iconos/alerta.png" width="24" height="24"></div>
      <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
      <div class="modal_boton1"><input type="button" value="Aceptar" class="boton_modal" onclick="closeMensaje()"/></div>
    </div>
     
	 

<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Contabilidad > Ingreso de Dinero </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Contabilidad'];
   $privilegios = $db->getOpciones($menus, "Ingreso de Dinero"); 
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
    <td height="92" colspan='2' valign="middle">&nbsp;&nbsp; <div class="titleFromReport">
     Reportes de Ingreso de Dinero</div>
    </td>
    <td></td>
    <td colspan="3" align='right'>

        <table width="356" border="0">
          <tr>
            <td width="204" align="right"></td>
            <td width="142">         </td>
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
        <div class="botonreporte" id="r1" onclick="viewOptionReporte('r1')"> Ingreso de Caja</div>
        <div class="espacioreporte"></div>
        <div class="botonreporte2" id="r2" onclick="viewOptionReporte('r2')">Ingreso de Banco</div>
        <div class="espacioreporte"></div>
        <div class="botonreporte2" id="r3" onclick="viewOptionReporte('r3')">Movimiento de Caja</div>
        <div class="espacioreporte"></div>
        <div class="botonreporte2" id="r4" onclick="viewOptionReporte('r4')">Movimiento de Banco</div>
        <div class="espacioreporte"></div>
        <div class="botonreporte2" id="r5" onclick="viewOptionReporte('r5')">Ingreso por Cliente</div>
        <div class="espacioreporte"></div>
        <div class="botonreporte2" id="r6" onclick="viewOptionReporte('r6')">Ingreso por Cuenta</div>
      </div></td>
      <td rowspan="2">&nbsp;</td>
            <td colspan="2" rowspan="2" valign="top">
                   
  <!-- Ingreso de Caja -->                   
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
				  
    	   $sql = "select t.modalidadcontrato as 'modalidad' from trabajador t,usuario u where 
				 u.idtrabajador=t.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
				 $MTrabajador = $db->arrayConsulta($sql);
				 if ($MTrabajador['modalidad'] == "Consultor") {
					$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
					from cajero c,trabajador t where t.idtrabajador=c.idtrabajador and t.estado=1;"; 					
				 } else {				 
				    $sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
					from cajero c,usuario u,trabajador t where t.idtrabajador=u.idtrabajador 
					and u.idtrabajador=c.idtrabajador and u.idusuario=$_SESSION[id_usuario]";				 
 			     }
			   $cajas = $db->consulta($sql);
				while ($data = mysql_fetch_array($cajas)) {
				    if ($data['cuentacaja1'] == "" && $data['cuentacaja2'] == "")
					    continue;
					
					 echo "<optgroup label='$data[nombre]'>";	
				     if ($data['cuentacaja1'] != "" && $data['textocaja1'] != "")  
				        echo "<option value='$data[cuentacaja1]'>$data[textocaja1]</option>";  
				     if ($data['cuentacaja2'] != "" && $data['textocaja2'] != "")  
				        echo "<option value='$data[cuentacaja2]'>$data[textocaja2]</option>"; 		
					 if ($data['cuentacaja3']!= "" && $data['textocaja3'] != "")  
						echo "<option value='$data[cuentacaja3]'>$data[textocaja3]</option>"; 
					 if ($data['cuentacaja4']!= "" && $data['textocaja4'] != "")  
						echo "<option value='$data[cuentacaja4]'>$data[textocaja4]</option>"; 
					 if ($data['cuentacaja5']!= "" && $data['textocaja5'] != "")  
						echo "<option value='$data[cuentacaja5]'>$data[textocaja5]</option>"; 
					 if ($data['cuentacaja6']!= "" && $data['textocaja6'] != "")  
						echo "<option value='$data[cuentacaja6]'>$data[textocaja6]</option>"; 			
				     echo "</optgroup>";
				}		  
    	 
	?>
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Moneda:</td>
    <td>
    <select id="monedacaja" name="monedacaja">
        <option value="Bolivianos">Bolivianos</option>
        <option value="Dolares">Dolares</option> 
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
    <td colspan="2">
    <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte"
     onclick="viewreporteCaja();"  class="botonNegro" style="width:110px;"/>
    </td>
    </tr>
</table>

                   
                   
 </div>
 
 <!-- Ingreso de Banco --> 
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
					while ($data = mysql_fetch_array($cajas)){
					    if ($data['cuentabanco1'] == "" && $data['cuentabanco2'] == "" && $data['cuentabanco3'] == "")
						continue;
						
					    echo "<optgroup label='$data[nombre]'>";	
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Moneda:</td>
    <td><select id="moneda" name="moneda">
        <option value="Bolivianos">Bolivianos</option>
        <option value="Dolares">Dolares</option> 
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    </td>
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
    <td colspan="2">
    <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte"
     onclick="viewreporteBanco();"  class="botonNegro" style="width:110px;"/>
    </td>
    </tr>
</table>                   
                   
</div>
                  
<!-- Reporte Movimiento de Caja -->              
<div class="contenedorreportecentro" id="r-3">
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
    <td><input name="fecha5" type="text" id="fecha5" value="<?php echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Hasta:</td>
    <td><input name="fecha6" type="text" id="fecha6" value="<?php  echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Cuenta:</td>
    <td><select id="cuenta5" name="cuenta5" style="width:150px;background:#FFF;border:solid 1px #999;" >
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
				while ($data = mysql_fetch_array($cajas)){
				    if ($data['cuentacaja1'] == "" && $data['cuentacaja2'] == "")
					    continue;
					
					echo "<optgroup label='$data[nombre]'>";	
				     if ($data['cuentacaja1'] != "" && $data['textocaja1'] != "")  
				        echo "<option value='$data[cuentacaja1]'>$data[textocaja1]</option>";  
				     if ($data['cuentacaja2'] != "" && $data['textocaja2'] != "")  
				        echo "<option value='$data[cuentacaja2]'>$data[textocaja2]</option>"; 	
					 if ($data['cuentacaja3']!= "" && $data['textocaja3'] != "")  
						echo "<option value='$data[cuentacaja3]'>$data[textocaja3]</option>"; 
					 if ($data['cuentacaja4']!= "" && $data['textocaja4'] != "")  
						echo "<option value='$data[cuentacaja4]'>$data[textocaja4]</option>"; 
					 if ($data['cuentacaja5']!= "" && $data['textocaja5'] != "")  
						echo "<option value='$data[cuentacaja5]'>$data[textocaja5]</option>"; 
					 if ($data['cuentacaja6']!= "" && $data['textocaja6'] != "")  
						echo "<option value='$data[cuentacaja6]'>$data[textocaja6]</option>"; 					
				    echo "</optgroup>";
				}		  
    	 
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
    <td colspan="2">
     <input type="button" name="aceptar" id="aceptar" 
      value="Realizar Reporte" onclick="viewreporteMovimientoCaja();"  class="botonNegro" style="width:110px;"/></td>
    </tr>
</table>
 </div>
                   
                   
 <!-- Reporte Movimiento de Banco -->               
 <div class="contenedorreportecentro" id="r-4"> 
                   
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
    <td><input name="fecha7" type="text" id="fecha7" value="<?php echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Hasta:</td>
    <td><input name="fecha8" type="text" id="fecha8" value="<?php  echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Cuenta:</td>
    <td><select id="cuenta7" name="cuenta7" style="width:150px;background:#FFF;border:solid 1px #999;" >
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
					    if ($data['cuentabanco1'] == "" && $data['cuentabanco2'] == "" && $data['cuentabanco3'] == "")
						continue;
						
					    echo "<optgroup label='$data[nombre]'>";	
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
    <td colspan="2">
    <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte" 
    onclick="viewreporteMovimientoBanco();"  class="botonNegro" style="width:110px;"/></td>
    </tr>
</table>           
                   
                   
 </div>
         
 <!-- Reporte Ingreso por Cliente -->          
  <div class="contenedorreportecentro" id="r-5"> 
                   
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
    <td><input name="fecha11" type="text" id="fecha11" value="<?php echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Hasta:</td>
    <td><input name="fecha12" type="text" id="fecha12" value="<?php  echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

    
  <tr>
    <td>&nbsp;</td>
    <td align="right">
    <select name="receptor" id="receptor" style="width:100px; background:#FFF;border:solid 1px #999;">                    
	 <?php
       $tipo = array("cliente", "proveedor", "trabajador");
       for ($i = 0; $i < count($tipo); $i++) {
           echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
       }	
     ?>                                        
    </select>
    </td>
    <td>
    <input style="width:90%" type="text" id="texto" onclick="this.select()" onkeyup="tipoBusqueda(event);" 
    value="" autocomplete="off"/>    
    <div id="cliente" class="divresultado"></div>
    <input type="hidden" id="idpersonarecibida" value="-1" />
    </td>
    <td><div id="autoL1" class="autoLoading"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Caja/Banco:</td>
    <td><select id="cuenta9" name="cuenta9" style="width:150px;background:#FFF;border:solid 1px #999;" >
      <option value="">-- Seleccione --</option>
      <?php
		 		  
    	  $sql = "select t.modalidadcontrato as 'modalidad' from trabajador t,usuario u where 
				 u.idtrabajador=t.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
				 $MTrabajador = $db->arrayConsulta($sql);
				 if ($MTrabajador['modalidad'] == "Consultor") {
					$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
					from cajero c,trabajador t where t.idtrabajador=c.idtrabajador and t.estado=1;"; 					
				 } else {				 
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Moneda:</td>
    <td><select id="monedacliente" name="monedacliente">
        <option value="Bolivianos">Bolivianos</option>
        <option value="Dolares">Dolares</option> 
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
    <td colspan="2">
    <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte" 
    onclick="viewreporteIngresoCliente();"  class="botonNegro" style="width:110px;"/></td>
    </tr>
</table>      
</div>

 <!-- Reporte Ingreso por Cuenta -->         
  <div class="contenedorreportecentro" id="r-6">                   
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
    <td><input name="fecha9" type="text" id="fecha9" value="<?php echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Hasta:</td>
    <td><input name="fecha10" type="text" id="fecha10" value="<?php  echo date("d/m/Y"); ?>" size="9"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td align="right">Cuenta:</td>
    <td>
      <select name="modeloplan" id="modeloplan"  style="width:190px;">
         <option value="">--Seleccione una Cuenta--</option>
          <?php
		  $consultaPlanCuenta = "select (select pp.cuenta from plandecuenta pp 
		  where pp.codigo=( left(ph.codigo,2) ))as 'padre',ph.codigo,ph.cuenta,ph.nivel
		   from plandecuenta ph  where ph.nivel>=5 and estado=1 and left(ph.codigo,1)=4 order by ph.codigo;";
		  $arrayPlan = $db->getDatosArray($consultaPlanCuenta,4);
 	      $db->imprimirComboGrupoArray($arrayPlan,'','');	     
		?>
      </select>
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr> 
  
  <tr>
    <td>&nbsp;</td>
    <td align="right">Caja/Banco:</td>
    <td><select id="cuenta8" name="cuenta8" style="width:150px;background:#FFF;border:solid 1px #999;" >
      <option value="">-- Seleccione --</option>
      <?php
		 		  
    	         $sql = "select t.modalidadcontrato as 'modalidad' from trabajador t,usuario u where 
				 u.idtrabajador=t.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
				 $MTrabajador = $db->arrayConsulta($sql);
				 if ($MTrabajador['modalidad'] == "Consultor") {
					$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
					from cajero c,trabajador t where t.idtrabajador=c.idtrabajador and t.estado=1;"; 					
				 } else {				 
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Moneda:</td>
    <td><select id="monedacuenta" name="monedacuenta">
        <option value="Bolivianos">Bolivianos</option>
        <option value="Dolares">Dolares</option> 
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
    <td colspan="2">
    <input type="button" name="aceptar" id="aceptar" value="Realizar Reporte" 
    onclick="viewreporteIngresoCuenta();"  class="botonNegro" style="width:110px;"/></td>
    </tr>
</table>       
</div>         
                   
        
        </td>
        <td>&nbsp;</td>
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