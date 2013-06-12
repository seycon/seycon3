<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
  include('conexion.php');
  session_start();
  $db = new MySQL();  
  if (!isset($_SESSION['softLogeoadmin'])) {
    header("Location: index.php");	
  }
  $estructura = $_SESSION['estructura'];
  if (!$db->tieneAccesoFile($estructura['Administracion'],'Configuración Contable','nuevo_configuracionrecursos.php')) {
	header("Location: cerrar.php");	
  }
 
  $consulta = "select * from configuracioncontable;";
  $valores = $db->arrayConsulta($consulta);
  
  $consultaPlanCuenta = "select (select pp.cuenta from plandecuenta pp where pp.codigo=( left(ph.codigo,2) ))as 'padre',ph.codigo,ph.cuenta,ph.nivel from plandecuenta          ph  where ph.nivel>=5 and estado=1 order by ph.codigo;";
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
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="configuracion/estiloConfiguracion.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="lib/Jtable.js"></script>
<script async="async" src="configuracion/NConfiguracion.js"></script>
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
	var menu = ['tabs-1'];
	var menu2 = ['tabs1'];
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
    <input type="button" name="Guadar" id="Guadar" class="botonNegro"  onclick="ejecutarTransaccionrecursos()" 
    value="Guardar [F2]" />
      </td>
      <td> <input type="hidden" id="transaccion" name="transaccion" value="registrar" />
      </td>
      <td colspan="3" align='right'><table width="356" border="0">
        <tr>
          <td width="142" colspan="2" align="center"><strong>Configuración de Recursos Humanos</strong></td>
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
  <table width="100%" align="center">
    <tr>
      <td colspan="6"></td>
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
      <div >

  <div id='tabs-1' style="display:block; height:400px;">
  <table width="100%" border="0">
  <tr>
      <td colspan="5">&nbsp;</td>
    </tr> 
         
  <tr>
    <td colspan="5"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2">
      <tr>
        <td width="6%">&nbsp;</td>
        <td width="34%" align="right" class="letras">Anticipo de Sueldo(H):</td>
        <td width="38%"><select name="anticiposueldo" id="anticiposueldo" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
          <?php 		  
		  $arrayPlan = $db->getDatosArray($consultaPlanCuenta,4);
		  $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['anticiposueldo']);?>
          </select></td>
        <td width="20%">&nbsp;</td>
        <td width="2%">&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Recargo por Informar(H):</td>
        <td><select name="recargoinformar" id="recargoinformar" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>          
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['recargoinformar']);?>
		
          </select></td>
        <td colspan="2" class="letras">Cuenta por cobrar</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="5"></td>
    <td align="right"></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="5"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2">
      <tr>
        <td width="3%">&nbsp;</td>
        <td width="37%" align="right" class="letras">Sueldos y Salarios(D):</td>
        <td width="38%"><select name="sueldossalarios" id="sueldossalarios" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
          
          <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['sueldossalarios']);?>
		
        </select></td>
        <td width="20%">&nbsp;</td>
        <td width="2%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Bono de Antigüedad(D):</td>
        <td><select name="bonoantiguedad" id="bonoantiguedad" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['bonoantiguedad']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Horas Extras(D):</td>
        <td><select name="horasextras" id="horasextras" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['horasextras']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Bono de Producción(D):</td>
        <td><select name="bonoproduccion" id="bonoproduccion" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['bonoproduccion']);?>
        </select></td>
        <td colspan="2" class="letras">Planilla de sueldos</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Otros Bonos(D):</td>
        <td><select name="otrosbonos" id="otrosbonos" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['otrosbonos']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Sueldos y Salarios P/Pagar(H):</td>
        <td><select name="salariospagar" id="salariospagar" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['salariospagar']);?>
		
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td height="5"></td>
    <td align="right"></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="5"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#E2E2E2">
      <tr>
        <td colspan="2" align="right" class="letras">Aporte y Retenciones P/Pagar(D):</td>
        <td width="37%"><select name="aporteretenciones" id="aporteretenciones" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['aporteretenciones']);?>
        </select></td>
        <td width="15%">&nbsp;</td>
        <td width="8%">&nbsp;</td>
      </tr>
      <tr>
        <td width="1%">&nbsp;</td>
        <td width="39%" align="right" class="letras">Seguro Medico(H):</td>
        <td><select name="seguromedico" id="seguromedico" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['seguromedico']);?>
        </select></td>
        <td colspan="2" class="letras">AFP y Seguro Medico</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Aporte Patronal(H):</td>
        <td><select name="aportepatronal" id="aportepatronal" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['aportepatronal']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Aporte Laboral(H):</td>
        <td><select name="aportelaboral" id="aportelaboral" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
          <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['aportelaboral']);?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td height="5"></td>
    <td align="right"></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#E2E2E2">
      <tr>
        <td width="1%">&nbsp;</td>
        <td width="39%" align="right" class="letras">Aguinaldo(gasto)(D):</td>
        <td width="38%"><select name="aguinaldo" id="aguinaldo" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
         <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['aguinaldo']);?>
        </select></td>
        <td width="12%">&nbsp;</td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right" class="letras">Aguinaldo por Pagar(H):</td>
        <td><select name="aguinaldoporpagar" id="aguinaldoporpagar" style="width:190px;font-size:12px;">
          <optgroup >
            <option value="">--Seleccione una Cuenta---</option>
            </optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['aguinaldoporpagar']);?>
        </select></td>
        <td colspan="2" class="letras">Planilla Aguinaldo</td>
        </tr>
    </table></td>
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