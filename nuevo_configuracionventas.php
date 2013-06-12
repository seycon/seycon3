<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
  include('conexion.php');
  session_start();
  $db = new MySQL(); 
  if (!isset($_SESSION['softLogeoadmin'])) {
    header("Location: index.php");	
  }
  $estructura = $_SESSION['estructura'];
  if (!$db->tieneAccesoFile($estructura['Administracion'],'Configuración Contable','nuevo_configuracionventas.php')) {
	header("Location: cerrar.php");	
  }  
  
  $consulta = "select * from configuracioncontable;";
  $valores = $db->arrayConsulta($consulta);
  
  $consultaPlanCuenta = "select (select pp.cuenta from plandecuenta pp where pp.codigo=( left(ph.codigo,2) ))
  as 'padre',ph.codigo,ph.cuenta,ph.nivel from plandecuenta  ph  where ph.nivel>=5 and estado=1 order by ph.codigo;";
	   
  mysql_query("SET NAMES 'utf8'");  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateadministracion.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="configuracion/estiloConfiguracion.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script async="async" src="configuracion/NConfiguracion.js"></script>
<script async="async" src="lib/Jtable.js"></script>
<script>
 $(document).ready(function()
 {
   document.getElementById('cortinaInicio').style.visibility = "hidden";
   document.getElementById('gif').style.visibility = "hidden"; 
 });

 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if(tecla == 113){ //F2
	 $$("Guadar").click();	  
	}
 }
 
  var viewMenu = function(id){
	var menu = ['tabs-1','tabs-2','tabs-3','tabs-4'];
	var menu2 = ['tabs1','tabs2','tabs3','tabs4'];
		for (var j=0;j<menu.length;j++){
	  if (menu[j] == id){
		$$(menu[j]).style.display = "block"; 
		$$(menu2[j]).style.background = "#8E8E8E"; 
		$$(menu2[j]).style.color = "#FFF"; 
	  }else{
		$$(menu[j]).style.display = "none";
		$$(menu2[j]).style.background = "#F6F6F6"; 
		$$(menu2[j]).style.color = "#666";  
	  }
	}	 
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
<div id="overlay" class="overlays"></div>
<div id="gif" class="gifLoaderconfig"></div>
<div id="cortinaInicio" class="overlaysInicio"></div>


<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Administración > Configuración Contable </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Administracion'];
   $privilegios = $db->getOpciones($menus, "Configuración Contable"); 
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
<form id="form1" name="form1" method="post" action="configuracion.php">
  <div class="contemHeaderTop">
     <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
     <input type="button" name="Guadar" id="Guadar" class="botonNegro"  onclick="ejecutarTransaccionventa()" value="Guardar [F2]" />
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      <td> <input type="hidden" id="transaccion" name="transaccion" value="registrar" />
      </td>
      <td colspan="3" align='right'><table width="356" border="0">
        <tr>
          <td width="142" colspan="2" align="center"><strong>Configuración Ventas</strong></td>
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
  
  <table width="100%" align="center" >
    <tr>
      <td colspan="6">
      
      
        
      </td>
    </tr>
    <tr>
      <td colspan="6"></td>
      </tr>
    <tr>
      <td width="25%">
        
      </td>
      <td></td>
      <td width="75%" colspan="4"><div align="left" id="mensaje" class="mensaje"> </div></td>
    </tr>
    
    <tr>
      <td colspan="6">
      <div>    
         <ul  class="menujs">
          <li id="tabs1" class="listajs" onclick="viewMenu('tabs-1')" style="background-color:#8E8E8E;color:#FFF"><a>Ventas</a></li>
          <li id="tabs2" class="listajs" onclick="viewMenu('tabs-2')"><a>Cliente</a></li>
          <li id="tabs3" class="listajs" onclick="viewMenu('tabs-3')"><a>Cuenta por cobrar</a></li>
          <li id="tabs4" class="listajs" onclick="viewMenu('tabs-4')"><a>Cuenta por pagar</a></li>
         </ul>
         
         
       <div id='tabs-1' style="display:block; height:400px;">
         <table width="100%" border="0">
         <tr>
            <td colspan="5">
            <?php
			  $arrayVentas = $db->getDatosArray($consultaPlanCuenta,4);
			?>          
            </td>
          </tr>
  <tr>
    <td colspan="5" align="right">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:#E2E2E2">
        <tr>
          <td colspan="2" align="right"><span class="letras">I.T. Gastos(D):</span></td>
          <td width="37%"><select name="itgastos" id="itgastos"  style="width:190px;font-size:12px;" >
            <optgroup >
              <option value="">--Seleccione una Cuenta--</option>
              </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['itgastos']);?>
          </select></td>
          <td width="4%"><span class="letras">P % :</span></td>
          <td width="9%"><input type="text" name="porcentajeITG" id="porcentajeITG" size="8" 
          onkeypress="return soloNumeros(event)"  value='<?php echo $valores['porcentajeitgastos'];?>'/></td>
          <td width="9%"><div id="msjp1" class="mensajeCantidad"></div></td>
          <td width="2%">&nbsp;</td>
          </tr>
        <tr>
          <td width="24%" align="right"><select name="modeloplan" id="modeloplan"  
          style="width:190px;font-size:12px;visibility:hidden">
            <option value="">--Seleccione una Cuenta--</option>
            <?php
	          $db->imprimirComboGrupoArray($arrayVentas,'','');	     
		    ?>
          </select></td>
          <td width="15%" align="right"><span class="letras">I.T. Pasivo(H):</span></td>
          <td><select name="itpasivo" id="itpasivo" style="width:190px;font-size:12px;">
            <optgroup >
              <option value="">--Seleccione una Cuenta--</option>
              </optgroup>
               <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['itpasivo']);?>
            </select></td>
          <td><span class="letras">P % :</span></td>
          <td><input type="text" name="porcentajeITP" id="porcentajeITP" size="8"
           onkeypress="return soloNumeros(event)"  value='<?php echo $valores['porcentajeitpasivo'];?>'/></td>
          <td><div id="msjp2" class="mensajeCantidad"></div></td>
          <td>&nbsp;</td>
          </tr>
        </table></td>
  </tr>
  <tr>
    <td width="39%" height="5" align="right"></td>
    <td width="37%"></td>
    <td width="4%"></td>
    <td width="18%"></td>
    <td width="2%"></td>
  </tr>
    <tr>
    <td colspan="5" align="right"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2">
      <tr>
        <td width="39%" align="right"><span class="letras">Debito Fiscal I.V.A(H).:</span></td>
        <td width="37%"><select name="debitofiscal" id="debitofiscal"  style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta--</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['debitofiscal']);?>
          </select></td>
        <td width="4%" class="letras">P % :</td>
        <td width="9%"><input type="text" name="porcentajeDF" id="porcentajeDF" size="8"
         onkeypress="return soloNumeros(event)" value='<?php echo $valores['porcentajedebitofiscal'];?>'/></td>
        <td width="9%"><div id="msjp3" class="mensajeCantidad"></div></td>
        <td width="2%">&nbsp;</td>
      </tr>
    </table></td>
    </tr>

  <tr>
    <td align="right" height="5"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
   <tr>
    <td colspan="5" align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#E2E2E2">
      <tr>
        <td width="39%" align="right"><span class="letras">Descuento sobre Venta(D):</span></td>
        <td width="37%">
        <select name="descuentoventa" id="descuentoventa"  style="width:190px;font-size:12px;">
        <option value="">--Seleccione una Cuenta--</option>
        <?php
	        $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['descuentoventa']);	     
		?>
        </select></td>
        <td width="4%">&nbsp;</td>
        <td width="18%">&nbsp;</td>
        <td width="2%">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><span class="letras">Recargo Sobre Venta(H):</span></td>
        <td>
        <select name="recargo" id="recargo"  style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta--</option>
            </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['recargo']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td align="right" height="5"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="5" align="right"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2">
      <tr>
        <td width="39%" align="right"><span class="letras">Costo Sobre Venta(D):</span></td>
        <td width="38%"><select name="costoventa" id="costoventa"  style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta--</option>
            </optgroup>
             <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['costoventa']);?>
        </select></td>
        <td width="4%">&nbsp;</td>
        <td width="17%">&nbsp;</td>
        <td width="2%">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><span class="letras">Inventario de Almacén(H):</span></td>
        <td><select name="inventario" id="inventario" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['inventario']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td align="right" height="5"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="5" align="right"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2"> 
      <tr>
        <td align="right">Caja(D):</td>
        <td><select name="cajalibroCV" id="cajalibroCV" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['cajalibroCV']);?>
        </select></td>
        <td>&nbsp;</td>
        <td width="15%">&nbsp;</td>
        <td width="7%">&nbsp;</td>
      </tr>
      <tr>
        <td width="39%" align="right"><span class="letras">Venta de Servicio(H).:</span></td>
        <td width="35%"><select name="libroCV" id="libroCV" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['cuentalcv']);?>
        </select></td>
        <td width="4%">&nbsp;</td>
        <td colspan="2" class="letras">Libro de Ventas</td>
        </tr>
      <tr>
        <td align="right"><span class="letras">Venta de Producto(H).:</span></td>
        <td><select name="libroCVproducto" id="libroCVproducto" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
           <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['cuentalcvproductos']);?>
        </select></td>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
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
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

  </div> 
        

        
   <div id='tabs-2' class="optionjs" style="height:400px;">
    <table width="100%" border="0">
    <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2">
      <tr>
        <td width="3%">&nbsp;</td>
        <td width="37%" align="right" class="letras">Clientes por Cobrar(H):</td>
        <td width="38%">
        <select name="clientescobrar" id="clientescobrar" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
          </optgroup>
          <?php
		      $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['clientescobrar']);
		  ?>
        </select></td>
        <td width="14%">&nbsp;</td>
        <td width="8%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Anticipo de Cliente(D):</td>
        <td>
        <select name="anticipocliente" id="anticipocliente"  style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
          </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['anticipocliente']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Devolucion sobre Ventas(D):</td>
        <td>
        <select name="devolucion" id="devolucion" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta--</option>
          </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['devolucion']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
</table>

  </div>
        
        
        
        
        
   <div id='tabs-3' class="optionjs" style="height:400px;">
 
    <table width="100%" border="0">
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
  <tr>
    <td width="6%" align="right">&nbsp;</td>
    <td width="32%" align="right" class="letras">Cuenta Debe (Descripción):</td>
    <td width="26%">
     <input type="text"  id="descripcionS5" onkeyup="insertarCuenta(event,'agregarS5')"/>
    </td>
    <td width="35%">
     <input type="button" onclick="insertarNewItem('detalleS5','descripcionS5');"
      id="agregarS5" value="Agregar [Enter]" class="botonNegro"  />
    </td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<div style="position:relative;overflow:auto;height:100px;border:1px solid #E2E2E2;width:70%;margin:0 auto;">
  <table width="100%" border="0" id="tabla" style="margin-top:5px;">
    <tr style="background-image: url(iconos/fondo.jpg);">
      <td width="38" >&nbsp;</td>
      <td width="38" style="display:none" >Id</td>
      <th width="400" align="center" class="letras">Descripción</th>
      <th width="250" align="center" class="letras">Cuenta</th>
    </tr>
    <tbody id="detalleS5">
    <?php
        $contadorCuentas = 0;
        $codigoCuentas = array();
        $sql = "select *from tipoconfiguracion where tipo='Por Cobrar' order by idtipoconfiguracion";
        $dato = $db->consulta($sql);
        $sqlcuentas = "select (select pp.cuenta from plandecuenta pp where 
        pp.codigo=( left(ph.codigo,2) ))as 'padre',ph.codigo,ph.cuenta from plandecuenta ph 
         where ph.nivel=5 order by padre;";
        while ($configuracion = mysql_fetch_array($dato)){
            $id = "DS5_".$contadorCuentas;	
            echo "
            <tr>
              <td width='38' align='center'><img src='css/images/borrar.gif'
               title='Borrar' onclick='eliminarFila(this)' /></td>
              <td width='38' style='display:none' >$id</td>
              <td width='400' class='letras'>$configuracion[descripcion]</td>
              <td width='250' align='left'>
              <select name='$id' id='$id'  style='width:190px;font-size:12px;'>
               <option value='' >--Seleccione una Cuenta--</option>";
               $db->imprimirComboGrupoArray($arrayVentas,'','',$configuracion['cuenta']);
             echo " </select>
              </td>
            </tr>
            ";
            $contadorCuentas++;	
        }            
   
   ?>
  
    
     </tbody>                        
  </table> 

</div>

          <table width="100%" border="0">
            <tr>
              <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
              <td width="6%">&nbsp;</td>
              <td width="32%" align="right" class="letras">Cuenta Haber (Descripción):</td>
              <td width="26%"><input type="text"  id="descripcionPS5" onkeyup="insertarCuenta(event,'agregarPS5')"/></td>
              <td width="35%">
              <input type="button" onclick="insertarNewItem('detallePS5','descripcionPS5');"
               id="agregarPS5" value="Agregar [Enter]" class="botonNegro"  />              </td>
              <td width="1%">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>


          <div style="position:relative;overflow:auto;height:100px;border:1px solid #E2E2E2;width:70%;margin:0 auto;">
          <table width="100%" border="0" id="tabla" style="margin-top:5px;">
            <tr style="background-image: url(iconos/fondo.jpg);">
              <td width="38" >&nbsp;</td>
              <td width="38" style="display:none" >Id</td>
              <th width="400" align="center" class="letras">Descripción</th>
              <th width="250" align="center" class="letras">Cuenta</th>
            </tr>
            <tbody id="detallePS5">
            <?php
				$contadorCuentas = 0;
				$codigoCuentas = array();
				$sql = "select *from tipoconfiguracion where tipo='Por Cobrar Apertura' order by idtipoconfiguracion";
				$dato = $db->consulta($sql);
				$sqlcuentas = "select (select pp.cuenta from plandecuenta pp where pp.codigo=( left(ph.codigo,2) ))as 'padre'
				,ph.codigo,ph.cuenta from plandecuenta ph where ph.nivel=5 order by padre;";
				while ($configuracion = mysql_fetch_array($dato)) {
					$id = "DPS5_".$contadorCuentas;	
					echo "
					<tr>
					  <td width='38' align='center'><img src='css/images/borrar.gif'
					   title='Borrar' onclick='eliminarFila(this)' /></td>
					  <td width='38' style='display:none' >$id</td>
					  <td width='400' class='letras'>$configuracion[descripcion]</td>
					  <td width='250' align='left'>
					  <select name='$id' id='$id'  style='width:190px;font-size:12px;'>
					   <option value='' >--Seleccione una Cuenta--</option>";
					   $db->imprimirComboGrupoArray($arrayVentas,'','',$configuracion['cuenta']);
					 echo " </select>
					  </td>
					</tr>
					";
					$contadorCuentas++;	
				}           
           ?>             
           </tbody>                        
         </table> 
          
          </div>



        </div>
        
        
        
   <div id='tabs-4' class="optionjs" style="height:400px;">
    <table width="100%" border="0">
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
  <tr>
    <td width="6%">&nbsp;</td>
    <td width="32%" align="right" class="letras">PASIVO(descripción):</td>
    <td width="26%"><input type="text"  id="descripcionS4" onkeyup="insertarCuenta(event,'agregarS4')"/></td>
    <td width="35%"><input type="button" onclick="insertarNewItem('detalleS4','descripcionS4');"
     id="agregarS4" value="Agregar [Enter]" class="botonNegro"  /></td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<div style="position:relative;overflow:auto;height:230px;border:1px solid #E2E2E2;width:90%;margin:0 auto;">
  <table width="100%" border="0" id="tabla" style="margin-top:5px;">
    <tr style="background-image: url(iconos/fondo.jpg);">
      <td width="38" >&nbsp;</td>
      <td width="38" style="display:none" >Id</td>
      <th width="480" align="center" class="letras">Descripción</th>
      <th width="210" align="center" class="letras">Pasivo</th>
      <th width="210" align="center" class="letras">Contra Cuenta</th>
    </tr>
    <tbody id="detalleS4">
    <?php
	  $contadorCuentas = 0;
	  $codigoCuentas = array();
	  $sql = "select *from tipoconfiguracion where tipo='Por Pagar' order by idtipoconfiguracion";
	  $dato = $db->consulta($sql);
	  while ($configuracion = mysql_fetch_array($dato)) {
		  $id = "DS4_".$contadorCuentas;	
		  $idgasto = "DS8_".$contadorCuentas;
		  $sql = "select *from tipoconfiguracion where tipo='Por Pagar Gasto' and descripcion='$configuracion[descripcion]'";
		  $configuracionGasto = $db->arrayConsulta($sql);
		  
		  echo "
		  <tr>
			<td  align='center'><img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' /></td>
			<td  style='display:none' >$id</td>
			<td  class='letras'>$configuracion[descripcion]</td>
			<td  align='left'>
			<select name='$id' id='$id'  style='width:190px;font-size:12px;'>
			 <option value='' >--Seleccione una Cuenta--</option>";
			 $db->imprimirComboGrupoArray($arrayVentas,'','',$configuracion['cuenta']);
		   echo " </select>
			</td>
			<td  align='left'>
			<select name='$idgasto' id='$idgasto'  style='width:190px;font-size:12px;'>
			 <option value='' >--Seleccione una Cuenta--</option>";
			 $db->imprimirComboGrupoArray($arrayVentas,'','',$configuracionGasto['cuenta']);
		   echo " </select>			  
			</td>
		  </tr>
		  ";
		  $contadorCuentas++;	
	  }  
   
   ?>
    
     </tbody>                        
  </table>
</div>          
  <table width="90%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2" align="center">
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="23%" align="right" class="letras">Crédito Fiscal(D).:</td>
    <td width="41%"><select name="creditofiscalporpagar" id="creditofiscalporpagar"  style="width:190px;font-size:12px;">
      <optgroup >
        <option value="">--Seleccione una Cuenta--</option>
        </optgroup>
        <?php $db->imprimirComboGrupoArray($arrayVentas,'','',$valores['creditofiscalporpagar']);?>
    </select></td>
    <td width="7%" align="right" class="letras">P % :</td>
    <td width="11%" align="left">
    <input type="text" name="porcreditoporpagar" id="porcreditoporpagar"
     size="8" style="font-size:12px;" onkeypress="return soloNumeros(event)" 
       value='<?php echo $valores['porcreditoporpagar'];?>'/></td>
    <td width="15%" align="left"><div id="msjp5" class="mensajeCantidad"></div></td>
  </tr>
  </table>
 </div>
     
     </div>
</td>
      </tr>
</table>

</form></div></td></tr></table>
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
