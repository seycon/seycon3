<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Activo'],'Baja de Activo','nuevo_bajaactivo.php','listar_bajaactivo.php');
	if ($fileAcceso['Acceso'] == "No") {
	  header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	$dirListado = "nuevo_bajaactivo.php#t2";


	if (isset($_POST['transaccion'])) {
		$fecha = filtro($db->GetFormatofecha($_POST['fechabaja'],"/"));
		if ($_POST['transaccion'] == "insertar") {
		   $numero = $db->getNextID("numero","bajaactivo where idsucursal='$_POST[idsucursal]'"); 
		   $sql = "INSERT INTO bajaactivo (idbajaactivo,numero,idsucursal,idactivo
		   ,motivobaja,referencia,fechabaja,cantidad,idusuario,estado)
			VALUES (NULL,'$numero','".filtro($_POST['idsucursal'])."','"
			.filtro($_POST['idactivo'])."','".filtro($_POST['motivobaja'])."','".filtro($_POST['referencia']).
		   "','$fecha','".filtro($_POST['cantidadbaja'])."',$_SESSION[id_usuario],'1');";
			$db->consulta($sql);
			$codigo = $db->getMaxCampo('idbajaactivo','bajaactivo');	  
			$sql = "select *from activo where idactivo=".$_POST['idactivo'];
			$datosActivo = $db->arrayConsulta($sql);
			$total = $datosActivo['precio']*$_POST['cantidadbaja'];
			insertarLibro($_POST['idsucursal'],'Bolivianos',$fecha,$codigo
			,$_POST['tipoCambioBs'],$_SESSION['id_usuario'],$total,$db,filtro($_POST['referencia'])
			,filtro($_POST['motivobaja']),$_POST['idactivo']);
		}
	   
		if ($_POST['transaccion'] == "modificar") {
			$sql = "UPDATE bajaactivo SET idsucursal='".filtro($_POST['idsucursal'])
			."', idactivo='".filtro($_POST['idactivo'])
			."', motivobaja='".filtro($_POST['motivobaja'])."', referencia='".filtro($_POST['referencia']).
			"', fechabaja='$fecha', cantidad='".filtro($_POST['cantidadbaja']).
			"',idusuario=$_SESSION[id_usuario] WHERE idbajaactivo= '".$_POST['idbajaactivo']."';";
			$db->consulta($sql);	  
			$sql = "select *from activo where idactivo=".$_POST['idactivo'];
			$datosActivo = $db->arrayConsulta($sql);
			$total = $datosActivo['precio']*$_POST['cantidadbaja'];
			modificarLibro($_POST['idsucursal'],'Bolivianos',$fecha,$_POST['idbajaactivo']
			,$_POST['tipoCambioBs'],$_SESSION['id_usuario'],$total,$db,
			filtro($_POST['referencia']),filtro($_POST['motivobaja']),$_POST['idactivo']);
		}
		$db->consulta($sql);
		header("Location: $dirListado");
	}

	function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc
							   , $usuario, $monto, $db, $cuentacaja, $glosa, $activo)
	{
		$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal'
		 from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
		$num = $db->arrayConsulta($sql); 
		if (!isset($num['num'])) {
		    $num['num'] = 1;
		    $num['sucursal'] = $sucursal;
		}		 	
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha
		,glosa,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','egreso','$fecha'
		,'$glosa','$codigo','$tc','$usuario',1,'Baja Activo');"; 
		$db->consulta($sql);
		$sql = "select * from sucursal where idsucursal=$sucursal";
		$datosSucursal = $db->arrayConsulta($sql);
		$sql = "select t.* from activo a,trabajador t where 
		a.idtrabajador=t.idtrabajador and a.idactivo=$activo;";
		$datoTrabajador = $db->arrayConsulta($sql);	
		$descripcionLibro = "Baja Activo Nº $codigo/Trabajador: $datoTrabajador[nombre]
		 $datoTrabajador[apellido]/Sucursal: $datosSucursal[nombrecomercial]";
		$libro = $db->getMaxCampo("idlibrodiario","librodiario"); 
		setDetalleLibro($activo, $libro, $descripcionLibro, $monto, $cuentacaja, $db);
	}

	function modificarLibro($sucursal, $moneda, $fecha, $codigo
							 , $tc, $usuario, $monto, $db, $cuentacaja, $glosa, $activo)
	{
		$sql = "select idlibrodiario,idsucursal from librodiario where 
		transaccion='Baja Activo' and idtransaccion=$codigo;";  
		$libro = $db->arrayConsulta($sql); 
	 
		if ($libro['idsucursal'] != $sucursal) {
			$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal'
			 from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";
			$num = $db->arrayConsulta($sql);  	
			  if (!isset($num['num'])) {
			     $num['num'] = 1;
			     $num['sucursal'] = $sucursal;
			  }
			  $update = "idsucursal='$num[sucursal]',numero=$num[num],";
		} else {
		    $update = "";	
		}	
		
		$sql = "update librodiario set $update moneda='$moneda',fecha='$fecha'
		,tipocambio='$tc',idusuario='$usuario',glosa='$glosa'  
		where idlibrodiario=$libro[idlibrodiario];"; 
		$db->consulta($sql);
		$sql = "delete from detallelibrodiario where idlibro=$libro[idlibrodiario]";
		$db->consulta($sql);
		$sql = "select * from sucursal where idsucursal=$sucursal";
		$datosSucursal = $db->arrayConsulta($sql);
		$sql = "select t.* from activo a,trabajador t where a.idtrabajador=t.idtrabajador and a.idactivo=$activo;";
		$datoTrabajador = $db->arrayConsulta($sql);	
		$descripcionLibro = "Baja Activo Nº $codigo/Trabajador: $datoTrabajador[nombre]
		 $datoTrabajador[apellido]/Sucursal: $datosSucursal[nombrecomercial]";
		setDetalleLibro($activo,$libro['idlibrodiario'],$descripcionLibro,$monto,$cuentacaja,$db);	
	}

	function setDetalleLibro($activo, $libro, $descripcion, $monto, $cuentacaja, $db)
	{
		$sql = "select t.* from activo a,tipoactivo t where a.idtipoactivo=t.idtipoactivo and a.idactivo=$activo";
		$datoTipoActivo = $db->arrayConsulta($sql);		
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
		values($libro,'$cuentacaja','$descripcion',$monto,0,'')";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
		values($libro,'$datoTipoActivo[cuentaactivofijo]','$descripcion',0,$monto,'')";
		$db->consulta($sql);
	}
	
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	    $transaccion = "modificar";	
	    $sql = "SELECT * FROM bajaactivo WHERE idbajaactivo = ".$_GET['idbajaactivo'];
	    $datoActivo = $db->arrayConsulta($sql);  
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateactivo.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script src="activo/bajaactivo.js"></script>
<script>
$(document).ready(function()
{
$("#fechabaja").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});
</script>
<style>
.bordeContenido{
  border: 1px solid #CCC;	
}
</style>


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
<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Activos > Baja de Activo </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Activo'];
   $privilegios = $db->getOpciones($menus, "Baja de Activo"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_bajaactivo.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
      <table cellpadding='0' cellspacing='0' width='100%'>
        <tr class='cabeceraInicialListar'> 
          <td height="92" colspan='2' >&nbsp;&nbsp;
          <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
         <?php 
          if ($fileAcceso['File'] == "Si"){
           echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" id="cancelar" 
           value="Cancelar [F4]" onClick="location.href=&#039listar_bajaactivo.php#t2&#039"/>';	
          }
          ?>
       
       </td>
      <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
      <input type="hidden" id="idbajaactivo" name="idbajaactivo" value="<?php echo  $datoActivo['idbajaactivo'];?>" /></td>
      <td colspan="3" align='right'><table width="356" border="0">
        <tr>
          <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
          <td width="142">
          <?php 
            if (isset($_GET['idbajaactivo'])) {
                echo '<strong>Nº:</strong> '.$datoActivo['numero'];
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
        <tr><td colspan="6"></td> </tr>
      </table>
</div>
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3'>
<tr >
<td colspan='5' align='center' ></td>
</tr>

<tr>
<td colspan="2">
  </td>
<td>&nbsp;</td>
<td align="right">T.C.:
  <?php 
  $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
  $tc = $db->getCampo('dolarcompra',$sql);   
  echo $tc;
?>
  <input id="tipoCambioBs" name="tipoCambioBs" type="hidden" value="<?php echo $tc;?>" /></td>
<td width='118' align='center'></td>
</tr>
<tr>
  <td colspan='5'><table width="90%" align="center" border="0" class="bordeContenido">
    <tr>
      <td width="20%">&nbsp;</td>
      <td width="28%">&nbsp;</td>
      <td width="20%">&nbsp;</td>
      <td width="28%">&nbsp;</td>
      <td width="4%">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Sucursal<span class="rojo">*</span><span class='rojo'></span>:</td>
      <td><select name="idsucursal" id="idsucursal" onchange="consultarActivos()" style="width:180px;" class="required">
        <option value="" selected="selected">-- Seleccione --</option>
        <?php
   $sql = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1;";
   $db->imprimirCombo($sql,$datoActivo['idsucursal']);
   ?>
      </select></td>
      <td align="right">Nombre de Activo<span class="rojo">*</span>:</td>
      <td><select name="idactivo" id="idactivo" onchange="consultarDatosActivos()" style="width:180px;" class="required">
        <option value="" selected="selected">-- Seleccione --</option>
        <?php
		if (isset($datoActivo['idsucursal'])){
		 $sql = "select idactivo,left(nombre,25)as 'nombre' from activo where idsucursal=$datoActivo[idsucursal] and estado=1;";	     
    	 $db->imprimirCombo($sql,$datoActivo['idactivo']);
		}
		?>
        
      </select>
      
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Responsable<span class='rojo'></span>:</td>
      <td><input type='text' id="responsable" name="responsable"  class="required" size="32"
       value="<?php echo $datoActivo['responsable'];?>" disabled="disabled"/></td>
      <td align="right">Motivo de Baja<span class="rojo">*</span>:</td>
      <td><input type='text' id="motivobaja" name="motivobaja"  size="32" class="required"
       value="<?php echo $datoActivo['motivobaja'];?>"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Ubicacion:</td>
      <td colspan="3"><input type='text' id="ubicacion" name="ubicacion"  class="required"
       style="width:90%" value="<?php echo $datoActivo['ubicacion'];?>" disabled="disabled"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Cuenta Contable<span class="rojo">*</span>:</td>
      <td><select name="referencia" id="referencia"  style="width:180px;" class="required" >
        <option value="">-- Seleccione --</option>       
      <?php
		  $sql = "select cuenta,descripcion from tipoconfiguracion where tipo='Baja activo';";
          $db->imprimirCombo($sql,$datoActivo['referencia']);  
		?>
      </select></td>
      <td align="right">Fecha de Baja:</td>
      <td><input type='text' id="fechabaja" name="fechabaja" class="date" size="12" 
              value="<?php
			  if (isset($_GET['idbajaactivo'])) 
			  echo $db->GetFormatofecha($datoActivo['fechabaja'],'-');
			  else
			  echo date("d/m/Y");
			  ?>"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">Cantidad Disponible:</td>
      <td><input type='text' id="cantidad" name="cantidad"  value="<?php echo $datoActivo['cantidad'];?>"
       class="required" disabled="disabled" size="15" onkeyup="calcularTotal()"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">Cantidad de Baja<span class="rojo">*</span>:</td>
      <td><input type='text' id="cantidadbaja" name="cantidadbaja" value="<?php echo $datoActivo['cantidad'];?>"
        class="required number" size="15" onkeyup="calcularTotal()"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">Saldo Disponible:</td>
      <td><input type='text' id="total" name="total"  class="required" size="15" disabled="disabled" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
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
    </tr>
  </table></td>
  </tr>
</table>
</form>
</div>
</td></tr></table>
<script>
  consultarDatosActivos();  
  calcularTotal();
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