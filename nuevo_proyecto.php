<?php 
  session_start();
  include('conexion.php');  
  if (!isset($_SESSION['softLogeoadmin'])) {
	  header("Location: index.php");	
  }
   $db = new MySQL();
   $estructura = $_SESSION['estructura'];
  $fileAcceso = $db->privilegiosFile($estructura['Agenda'],'Proyecto','nuevo_proyecto.php','listar_proyecto.php');
  if ($fileAcceso['Acceso'] == "No") {
	  header("Location: cerrar.php");	
  }

 $sql = "select left(titulodeproyecto,30)as 'titulodeproyecto' from impresion where idimpresion = 1";
 $tituloPrincipal = $db->getCampo('titulodeproyecto', $sql);

 $numproyecto= $db->getNextID('idproyecto','proyecto');
 $transaccion = "insertar";
 $idproyecto = 0;
 if (isset($_GET['idproyecto'])) {
	 $idproyecto = $_GET['idproyecto'];
	 $transaccion = "modificar";	 
	 $sql = "select * from proyecto where idproyecto=$idproyecto";
	 $maestro = $db->arrayConsulta($sql);
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
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="proyecto/estilo.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="proyecto/NProyecto.js"></script>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script type="text/javascript" src="proyecto/graphs.js"></script>
<script src="lib/Jtable.js"></script>
<script src="autocompletar/funciones.js"></script>

<script>
$(document).ready(function()
{
$("#fecha").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#fechaincio").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechafinalizacion").datepicker({
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
            <td><input type="button" value="Ver Reporte" onclick="accionPostRegistro();" class="botonseycon"/></td>
            <td align="right">Imprimir Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
          </table>  
        
      </div>
  </div>
</div>
 
 
 <div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Agenda > Proyecto </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Proyecto"); 
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

<table style="width:75%;top:38px;margin: 0 auto;position:relative;" border="0">
 <tr>
 <td> 
<div class="contenedorPrincipal">
<form id='formValidado' name='formValidado' method='post' action='proyecto/nuevo_detalleproyecto.php' enctype='multipart/form-data'>

  <table width="100%" border='0' align='center' cellpadding='4' cellspacing='3'>
  <tr >
  <td colspan='4' align='center' >
  
  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='button' id='enviar' value='Guardar [F2]' onclick="ejecutarTransaccion()" class="botonseycon"/>
    <?php 
	if ($fileAcceso['File'] == "Si"){
	 echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonseycon" 
	 id="cancelar" value="Cancelar [F4]" onclick="salir()"/>';	
	}
	?>
 
 </td>
<td><input type="hidden"  id="codProyecto" name="codProyecto" value="<?php echo $idproyecto;?>"/></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
    <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
    <td width="142">
	<?php  
	if (isset($_GET['idproyecto'])) 
	    echo $idproyecto ; 
	else 
	    echo $numproyecto;
	?>
</td>
  </tr>
  <tr>
    <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
  
  </tr>
</table>
</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table>

  </td>
</tr>

<tr>
  <td colspan='4' ><div align='left' class='masagua'>
    <table width="100%" border="0">
      <tr>
        <td align="right">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td colspan="4"><div align="left">
         &nbsp;&nbsp;&nbsp;Titulo<span class="rojo">*</span>:
<input type="text" name="titulo" id="titulo" size="70" value="<?php echo $maestro['titulo']?>"/>
        </div></td>
        <td><div align="right"><strong class='titulostablas'>Terminado:</strong></div></td>
        <td><label>
          <input type="checkbox" name="terminadoP" id="terminadoP" <?php if (isset($_GET['idproyecto'])) 
		  if ($maestro['proyectoterminado']==1)
		  echo  "checked='checked'"; ?>/>
        </label></td>
      </tr>
      <tr>
        <td width="1%">&nbsp;</td>
        <td width="21%">Fecha Inicio:</td>
        <td width="21%">Fecha Final:</td>
        <td>Presupuesto utilizado:</td>
        <td width="19%">Presupuesto:</td>
        <td width="11%">
          <div align="right">
            <div align="right"><strong class='titulostablas'>Privado:</strong></div>
            </div>
          </td>
        <td width="8%"><input type="checkbox" name="privadoP" id="privadoP" <?php if (isset($_GET['idproyecto'])) 
		  if ($maestro['privado']==1)
		  echo  "checked='checked'"; ?> /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><label>
         <input type='text' id="fechaincio" name="fechaincio"  class="date" size="10" value="<?php 
		  if (isset($_GET['idproyecto']))
		  echo $db->GetFormatofecha($maestro['fechainicio'],'-');
		  else
		  echo date("d/m/Y");?>"/>
        </label></td>
        <td><label>
          <input type='text' id="fechafinalizacion" name="fechafinalizacion"  class="date" size="10" value="<?php 
		  if (isset($_GET['idproyecto']))
		  echo $db->GetFormatofecha($maestro['fechafinalizacion'],'-');
		  ?>" />
        </label></td>
        <td width="19%"><input type="text" name="presupuestoUtil" id="presupuestoUtil" onkeypress="return soloNumeros(event)" value="<?php if (isset($_GET['idproyecto'])) 
echo $maestro['presupuestoutil'];
?>" /></td>
        <td align="left">
          <input type="text" name="presupuesto" id="presupuesto" onkeypress="return soloNumeros(event)" value="<?php if (isset($_GET['idproyecto'])) 
echo $maestro['presupuesto'];
?>"/></td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
      </tr>
    </table>
  </div></td>
</tr>

<tr>
<td height="295" colspan='4' >
<div id='tabs'>

<div class="barraDatos">
  <table width="100%" border="0" >
    <tr>
      <td width="10%">Descripción<span class='rojo'>*</span>:</td>
      <td width="21%"><input type='text' id="descripcion" name="descripcion"  class="required" size="25" /></td>
      <td width="18%">Fecha:<input type='text' id="fecha" name="fecha"  class="date" style="width:50%" /> </td>
      <td width="11%">Hrs.:<input type='text' id="horas" name="horas"  class="number" style="width:50%" onkeypress="return soloNumeros(event)" /></td>
      <td width="21%">Bs.:
      <input type='text' id="costo" name="costo"  class="number" style="width:40%" onkeypress="return soloNumeros(event)" onkeyup="enterInput(event);"/></td>
      <td width="19%">
      <input name="BTagreagarD" type="button" id="BTagreagarD" onclick="insertarNewItem('detalleTransaccion')" value="Registrar [Enter]" class="botonseycon"/></td>
    </tr>
  </table>

</div>




<div id='tabs-1'  align="center" style="position:relative;width:100%;">

<div align="center" style="position:relative; width:100%;">
<br>

<table  width='100%' border='0' align="center">

<tr height="30px">
  <td width="533" valign="top">
    <div style="overflow-y:scroll;position:relative;width:100%;height:240px;top:-10px;">        
      <table width='100%' height="22" border='0'>
        <tr style='background-image: url(fondo.jpg); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;'>
          <th width='16' align="center">&nbsp;</th>
          <th width='33' align="center">Nº<br /></th>
          <th width='192' align="center">Desarrollo</th>
          <th width='64' align="center">Fecha</th>
          <th width='61' align="center">Horas</th>
          <th width='61' align="center">Costo</th>
          </tr>
        
        <tbody id="detalleTransaccion">  
          <?php
		   $totalH = 0;
	       $totalC = 0;
   if (isset($_GET['idproyecto'])){
	 $sql = "select * from detalleproyecto where idproyecto=$idproyecto order by iddetalleproyecto";  
	 $detalle = $db->consulta($sql);
	 $i = 1;
	
	 while ($dato = mysql_fetch_array($detalle)){   
	  $totalH = $totalH + $dato['horas'];
	  $totalC = $totalC + $dato['costo'];
      echo "
       <tr>
         <td   align='center'><img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' /></td>
         <td  align='center'>$i</td>
         <td >$dato[descripcion]</td>
		 <td  align='center'>".$db->GetFormatofecha($dato['fecha'],'-')."</td>		 
         <td align='center'>".number_format($dato['horas'],2)."</td>
         <td  align='center'>".number_format($dato['costo'],2)."</td>
       </tr>";  
	   $i++;
	 }
   }

  ?>
          </tbody>   
        </table>       
    </div>  
  </td>
  
  
  <td width="169">
    
  <div id="graficaD" align="center">
    
  </div></td>
</tr>
<tr height="30px">
  <td>
  <table width='100%' border='0' >
    
  <tr>
  <td width='300' align="center">&nbsp;</td>
  <td width='119'></td>
  <td width='110' >Horas:<input type='text' id="Thoras" name="Thoras" size="13" disabled="disabled"/></td>
  <td width='100'>Costo:<input type='text' id="Tcosto" name="Tcosto" size="13"  disabled="disabled"/></td>
  </tr>
    
  </table>
    
  </td>
  <td>
  <table>
  <tr>
  <td width="83"><div align="right" class="negrita">Avance:
  </div></td>
  <td width='53'><input type="text" size="10" onkeyup="createGraph(this.value)" name="avanceP" id="avanceP" value="<?php $avance = 0;  if (isset($_GET['idproyecto'])) 
$avance = $maestro['porcentajeavance'];
echo $avance;
?>"></td>
  </tr>
  </table>
  </td>
</tr>



</table>

<table width="100%" border="0" align="center" style="border-top:1px solid #CCC;">
<tr height="40">
  <td align="right">Glosa:</td>
  <td width="38%" align="left">
  <?php if (isset($_GET['idproyecto'])) $glosa=$maestro['glosa'];?>
    <textarea name="glosa"  rows="2" style="width:250px;" id="glosa"><?php echo $glosa;?></textarea>
  </td>
  <td width="13%" align="right">Recursos:</td>
  <td width="40%" align="left">
  <?php if (isset($_GET['idproyecto'])) $recursos=$maestro['recursos'];?>
  <textarea name="recursos"  rows="2" style="width:250px;" id="recursos"><?php echo $recursos;?></textarea></td>
</tr>
<tr height="30">
  <td width="9%" align="right"></td>
  <td colspan="3" align="center"></td>
</tr>
</table>
</div>

</div>

<script>
cargarTotales(<?php echo $totalH;?>,<?php echo $totalC;?>);
transaccion = "<?php echo $transaccion;?>";
createGraph(<?php echo $avance;?>);
</script>

</div> 

</td>
</tr>

  
  </table>     

</form>
</div>
</td>
</tr>
</table>

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