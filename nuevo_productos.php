<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();
	include('conexion.php');  
	$db = new MySQL();
	$irDireccion = "nuevo_productos.php#t6";
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Tipos de Productos'
	,'nuevo_productos.php','listar_producto.php');
	
	if ($fileAcceso['Acceso'] == "No") {
	    header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	
	if (isset($_POST['transaccion'])) {
		$destino = '';
		$ahora = time();
		$archivo1 = $_FILES['imagen']['name'];
		$fotoimgen = "";
		if ($archivo1 != '') {
		   $destino =  "files/$ahora".$archivo1;
		   copy($_FILES['imagen']['tmp_name'],$destino);
		   $fotoimgen = "imagen='$destino',";
		}	
	
		if ($_POST['transaccion'] == "insertar") {		
			$sql = "INSERT INTO producto VALUES (NULL,'".filtro($_POST['nombre'])."','"
			.filtro($_POST['subgrupo'])."','".filtro($_POST['descripcion'])."','".
			filtro($_POST['unidaddemedida'])."','".filtro($_POST['unidadalternativa'])
			."','".filtro($_POST['conversiones'])."','".filtro($_POST['presentacion'])."','"
			.filtro($_POST['procedencia'])."','".filtro($_POST['stockminimo']).
			"','$destino','".filtro($_POST['costo'])."','".filtro($_POST['costoespecial'])
			."','".filtro($_POST['precio1'])."','".filtro($_POST['precio2']).
			"','".filtro($_POST['precio3'])."','".filtro($_POST['precio4'])."','$_SESSION[id_usuario]',1);";
		}
		
		if ($_POST['transaccion'] == "modificar") {
			$sql = "UPDATE producto SET nombre='".filtro($_POST['nombre'])."', descripcion='"
			.filtro($_POST['descripcion'])."', idsubgrupo='".filtro($_POST['subgrupo']).
			"', presentacion='".filtro($_POST['presentacion'])
			."', procedencia='".filtro($_POST['procedencia'])
			."', stockminimo='".filtro($_POST['stockminimo']).
			"', ".$fotoimgen." costo='".filtro($_POST['costo'])
			."', costoespecial='".filtro($_POST['costoespecial']).
			"', precio1='".filtro($_POST['precio1'])
			."', precio2='".filtro($_POST['precio2'])."', precio3='"
			.filtro($_POST['precio3'])."', precio4='".filtro($_POST['precio4']).
			"',idusuario=$_SESSION[id_usuario]  WHERE idproducto= '".$_POST['idproducto']."';";
		}
		
		$db->consulta($sql);
		header("Location: $irDireccion");
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	  $transaccion = "modificar";	
	  $sql = "SELECT * FROM producto WHERE idproducto= ".$_GET['idproducto'];
	  $datoProducto = $db->arrayConsulta($sql);  
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
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script> 
$(document).ready(function()
{
$("#formValidado").validate({});
});

  $(function() {	$( '#tabs' ).tabs();	});
  
   function cargarCuenta(plan){
	var contenido = document.getElementById(plan).value;	
	if (contenido == "--Seleccione una Cuenta--")
	return;
	 switch(plan){
		case 1:	
        		document.getElementById("cuentaalmacen").value =  contenido;
		break; 
		case 2:
				document.getElementById("cuentaingreso").value = contenido;
		break;
		case 3:
				document.getElementById("cuentaegreso").value = contenido;
		break;
	}
  }
  
  function cargaFotoImagen(dir){
	if (dir != ''){ 
	document.getElementById('idcamara').src = dir;}   
  }
 
 document.onkeydown = function(e) {
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115) { //F4
    if ($$("cancelar") != null)
	 location.href='listar_producto.php#t6';
   }
	
   if(tecla == 113) { //F2
     $$("enviar").click();  
   }
 }
 
 var $$ = function(id) {
	return document.getElementById(id); 
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

<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Inventario > Tipos de Productos </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Tipos de Productos"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_productos.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
   <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
        <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
        <?php 
          if ($fileAcceso['File'] == "Si") {
            echo '&nbsp;&nbsp;<input name="cancelar" type="button" class="botonNegro" 
            id="cancelar" value="Cancelar [F4]" onClick="location.href=&#039listar_producto.php#t6&#039"/>';	
          }
       ?>
     
     </td>
    <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden" id="idproducto" name="idproducto" value="<?php echo $datoProducto['idproducto'];?>" /></td>
    <td colspan="3" align='right'><table width="356" border="0">
      <tr>
        <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
        <td width="142">
        <?php 
         if (isset($_GET['idproducto'])) {
           echo $_GET['idproducto'];
         } else {
           echo $db->getNextID("idproducto","producto");
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
        <td colspan='7' align='center'></td>
      </tr>
      <tr>
        <td colspan='2' ></td>
        <td></td>
        <td width="105" align='right'></td>
        <td width="47" align='right'></td>
        <td width="78" align='left'></td>
        <td width="7" align='right'>&nbsp;</td>
      </tr>

      <tr>
        <td width='149' height="31" align='right' valign='top'>Nombre<span class='rojo'>*</span>:</td>
        <td width='170' valign='top'><input type='text' id="nombre" name="nombre"
         class="required" size="28" value="<?php echo $datoProducto['nombre']; ?>"/></td>
        <td width='116'  align='right' valign='top'>Unidad de Medida<span class='rojo'>*</span>:</td>
        <td valign='top'>        
        <?php
		    $atributo = "";
		    if ($transaccion == "modificar") {
			    $atributo = "disabled='disabled'";  
		    }
		?>       
        <input type='text' id="unidaddemedida" name="unidaddemedida"
         <?php echo $atributo;?>  size="10" value="<?php echo $datoProducto['unidaddemedida']; ?>" class="required"/></td>
        <td colspan="2" rowspan="3" valign='top'><img src='iconos/camara.png' alt='camara'
         name="idcamara" width='120' height='120' id="idcamara" style='cursor:pointer;'
          onclick="$('#imagen').click();" /></td>
      </tr>
      <tr>
        <td width='149' align='right' valign='top'>Sub Grupo<span class='rojo'>*</span>:</td>
        <td width='170' valign='top'>
        <select name="subgrupo" id="subgrupo"  style="width:170px;"  class="required">
          <optgroup ><option value="">-- Seleccione --</option></optgroup>
          <?php
		   $consultaPlanCuenta = "select g.nombre as 'padre'
		   ,sg.idsubgrupo as 'codigo',sg.nombre  as 'cuenta' from subgrupo sg,grupo g 
           where sg.idgrupo=g.idgrupo and g.estado=1 and sg.estado=1 order by padre,codigo,cuenta;";
	       $db->imprimirComboGrupo($consultaPlanCuenta,'','',$datoProducto['idsubgrupo']);
		  ?>
        </select></td>
        <td width='116'  align='right' valign='top'>Unidad Alternativa:</td>
        <td valign='top'><input type='text' id="unidadalternativa" name="unidadalternativa"
         <?php echo $atributo;?>  class="" size="10" value="<?php echo $datoProducto['unidadalternativa']; ?>"/></td>
        </tr>
      <tr>
        <td align='right' valign='top'>&nbsp;</td>
        <td valign='top'>&nbsp;</td>
        <td  align='right' valign='top'>Conversiones<span class='rojo'>*</span>:</td>
        <td valign='top'><input type='text' id="conversiones" name="conversiones" 
         <?php echo $atributo;?> size="10" value="<?php echo $datoProducto['conversiones']; ?>" 
         class="required number" min="1"/></td>
        <td align='center'>&nbsp;</td>
      </tr>
      <tr>
        <td colspan='7' ><hr /></td>
      </tr>
      <tr>
        <td colspan='7' >        
          
            <table width='100%' border='0' align='center'>
              <tr>
                <td width='155' align="right">Presentación:</td>
                <td width='171'><input type="text" id="presentacion"
                 name="presentacion" value="<?php echo $datoProducto['presentacion']; ?>"/></td>
                <td width='90' rowspan="2" align="right" valign="top">Descripción<span class='rojo'>*</span>:
                <br />(IMPRESIÓN)</td>
                <td width='300' rowspan="3"><textarea name="descripcion" class="required"
                 id="descripcion" cols="25" rows="4"><?php echo $datoProducto['descripcion']; ?></textarea></td>
              </tr>
              <tr>
                <td width='155' align="right">Procedencia:</td>
                <td width='171'>
                  <input type="text" id="procedencia" name="procedencia"
                 value="<?php echo $datoProducto['procedencia']; ?>"/>
                </td>
                </tr>
              <tr>
                <td width='155' align="right">Stock Mínimo:</td>
                <td width='171'><input type='text' id="stockminimo" name="stockminimo"
                 class="number" size="13" value="<?php echo $datoProducto['stockminimo']; ?>"/></td>
                <td width='90'><div align="right"></div></td>
                </tr>
              <tr>
                <td width='155' align="right">Costo Normal<span class='rojo'>*</span>:</td>
                <td width='171'><input type='text' id="costo" name="costo"
                 class="required number" size="10" value="<?php echo $datoProducto['costo']; ?>"/></td>
                <td width='90' align="right">Imagen:</td>
                <td width='300'><input type='file' id="imagen" name="imagen" class="" size="26" /><br />
                (180 Ancho X 180 Alto)</td>
              </tr>
              <tr>
                <td width='155' align="right">Costo Especial<span class='rojo'>*</span>:</td>
                <td width='171'><input type='text' id="costoespecial" name="costoespecial"
                 class="required number" size="10" value="<?php echo $datoProducto['costoespecial']; ?>"/></td>
                <td width='90'><div align="right"></div></td>
                <td width='300'>&nbsp;</td>
              </tr>
              <tr>
                <td width='155' height="27" align="right">&nbsp;</td>
                <td width='171' align="center">Tipo de Precio</td>
                <td width='90' align="center">Precio Venta</td>
                <td width='300'>&nbsp;</td>
              </tr>
              <?php
			    $sql = "select *from configuracionprecios";
				$precios = $db->arrayConsulta($sql);
			  ?>
              
          <tr>
            <td width='155' align="right">Precio 1<span class='rojo'>*</span>:</td>
            <td width='171'><input type="text" id="textoprecio1"
             name="textoprecio1" disabled="disabled" style="width:90%;" value="<?php echo $precios['textoprecio1']; ?>"/>
            </td>
            <td width='90'><input type='text' id="precio1"
             name="precio1" class="required number" size="15" value="<?php echo $datoProducto['precio1']; ?>" /></td>
            <td width='300'>Bs.</td>
          </tr>
              <tr>
                <td width='155' align="right">Precio 2:</td>
                <td width='171'><input type="text" id="textoprecio2"
                 name="textoprecio2" disabled="disabled" style="width:90%;" value="<?php echo $precios['textoprecio2']; ?>"/></td>
                <td width='90'><input type='text' id="precio2" name="precio2"
                 class="number" size="15" value="<?php echo $datoProducto['precio2']; ?>"/></td>
                <td width='300'>Bs.</td>
              </tr>
              <tr>
                <td width='155' height="20" align="right">Precio 3:</td>
                <td width='171'><input type="text" id="textoprecio3" 
                disabled="disabled" name="textoprecio3" style="width:90%;"
                 value="<?php echo $precios['textoprecio3']; ?>"/></td>
                <td width='90'><input type='text' id="precio3" name="precio3"
                 class="number" size="15" value="<?php echo $datoProducto['precio3']; ?>"/></td>
                <td width='300'><div align="left" >Bs.</div></td>
              </tr>
              <tr>
                <td width='155' align="right">Precio 4:</td>
                <td width='171'><input type="text" id="textoprecio4" disabled="disabled"
                 name="textoprecio4" style="width:90%;" value="<?php echo $precios['textoprecio4']; ?>"/></td>
                <td width='90'><input type='text' id="precio4" name="precio4" class="number"
                 size="15" value="<?php echo $datoProducto['precio4']; ?>"/></td>
                <td width='300'>Bs.</td>
              </tr>
            </table>          
<br />
          
        </td>
      </tr>
    </table>
</form>
</div>
</td></tr></table>
<script>
  cargaFotoImagen("<?php echo $datoProducto['imagen']; ?>");
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