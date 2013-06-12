<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include('conexion.php');
	$db = new MYSQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: index.php");	
	}
	
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Recursos'],'Parámetros de RRHH','nuevo_datosplanilla.php')) {
	  header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena));
	}
	
	
	if (isset($_POST['transaccion'])) {
	
	$sql = "select iddatosplanilla from datosplanilla";
	$datos = $db->arrayConsulta($sql);
	
	  if ($datos['iddatosplanilla'] == '') {
		$sql =  "INSERT INTO datosplanilla(iddatosplanilla,seguromedico
		,provivienda,seguroprofesional,salariominimo,aportepatronal,
		segurovejez,riesgocomun,comisionafp,aportesolidario,antiguedad1
		,antiguedad2,antiguedad3,antiguedad4,antiguedad5
		,antiguedad6,antiguedad7,planaportelaboral,planaportepatronal
		,planseguromedico,estado,planbonoantiguedad) 
		VALUES (NULL,'".filtro($_POST['seguromedico'])."','"
		.filtro($_POST['provivienda'])."','".filtro($_POST['seguroprofesional'])
		."','".filtro($_POST['salariominimo'])."','"
		.filtro($_POST['aportepatronal'])."','".filtro($_POST['segurovejez'])
		."','".filtro($_POST['riesgocomun'])
		."','".filtro($_POST['comisionafp'])."','".filtro($_POST['aportesolidario'])."','"
		.filtro($_POST['antiguedad1'])."','".filtro($_POST['antiguedad2'])
		."','".filtro($_POST['antiguedad3'])."','".filtro($_POST['antiguedad4'])."','"
		.filtro($_POST['antiguedad5'])."','".filtro($_POST['antiguedad6'])
		."','".filtro($_POST['antiguedad7'])."','".filtro($_POST['planaportelaboral'])
		."','".filtro($_POST['planaportepatronal'])."','".filtro($_POST['planseguromedico'])
		."','1','".filtro($_POST['planantiguedad'])."');"; 
	  } else {
		 $sql = "update datosplanilla set seguromedico='".filtro($_POST['seguromedico'])."',provivienda='"
		 .filtro($_POST['provivienda'])
		 ."',seguroprofesional='".filtro($_POST['seguroprofesional'])."',salariominimo='"
		 .filtro($_POST['salariominimo'])."',aportepatronal='"
		 .filtro($_POST['aportepatronal'])."',segurovejez='".filtro($_POST['segurovejez'])
		 ."',riesgocomun='".filtro($_POST['riesgocomun'])
		 ."',comisionafp='".filtro($_POST['comisionafp'])
		 ."',aportesolidario='".filtro($_POST['aportesolidario'])
		 ."',antiguedad1='".filtro($_POST['antiguedad1'])
		 ."',antiguedad2='".filtro($_POST['antiguedad2'])
		 ."',antiguedad3='".filtro($_POST['antiguedad3'])
		 ."',antiguedad4='".filtro($_POST['antiguedad4'])
		 ."',antiguedad5='".filtro($_POST['antiguedad5'])
		 ."',antiguedad6='".filtro($_POST['antiguedad6'])
		 ."',antiguedad7='".filtro($_POST['antiguedad7'])
		 ."',planaportelaboral='".filtro($_POST['planaportelaboral'])
		 ."',planaportepatronal='".filtro($_POST['planaportepatronal'])
		 ."',planseguromedico='".filtro($_POST['planseguromedico'])
		 ."',planbonoantiguedad='".filtro($_POST['planantiguedad'])."'"; 
	  }
	
	  $db->consulta($sql);
	  header("Location: nuevo_datosplanilla.php?msj#t3");
	}
	
	$sql = "select * from datosplanilla";
	$datosPlanilla = $db->arrayConsulta($sql);
	$sql = "select * from configuracioncontable;";
	$datoConfiguracion = $db->arrayConsulta($sql);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templaterecursos.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<link rel="stylesheet" href="planillas/datosplanilla.css" type="text/css" />
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script>
  $(document).ready(function()
  {
    $("#formValidado").validate({});
  });

  var leerURL = function() {
	 var param =  location.search;
	 if (param.length > 0){
	 	 document.getElementById("mensajeRespuesta").innerHTML = "Sus datos fueron guardados correctamente."; 
	 }
  }
 
  document.onkeydown = function(e) {
   tecla = (window.event) ? event.keyCode : e.which;  
   if (tecla == 113){ //F2
	 document.formValidado.submit();
	  
	}
  }
  
  function $$(id) {
	 return document.getElementById(id); 
  }
  
  function sumaAL() {
	var sum = parseFloat($$("segurovejez").value) + parseFloat($$("riesgocomun").value)
	 + parseFloat($$("comisionafp").value) + 
	parseFloat($$("aportesolidario").value);
	$$("totalAL").innerHTML = sum.toFixed(4);
  }
  
  function sumaAP() {
	var sum = parseFloat($$("seguroprofesional").value) + parseFloat($$("provivienda").value) 
	+ parseFloat($$("aportepatronal").value);  
    $$("totalAP").innerHTML = sum.toFixed(4);
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
<div class="menuTituloFormulario"> Recursos > Parámetros de RRHH </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Parámetros de RRHH"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_datosplanilla.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
   <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
        <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
     </td>
    <td><input type="hidden" id="transaccion" name="transaccion" value="insertar"  />
      <div id="mensajeRespuesta" class="mensajeRespuesta"></div></td>
    <td colspan="3" align='right'><table width="356" border="0">
      <tr>
        <td width="142" colspan="2" align="center"><strong>Parametros de Sueldos</strong></td>
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
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr>
<td colspan='4' align='center' ></td>
</tr>
<tr>
<td colspan='4' >

<table width="90%" align="center" border="0" class="session1_bordes">
  <tr>
    <td colspan="11">
      <table width="297" border="0" align="center">
        <tr>
          <td class="cabeceras">PLANILLA AFP</td>
          </tr>
        </table></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_cabeceraIzquierda">APORTE LABORAL</td>
    <td width="4%">&nbsp;</td>
    <td width="10%" class="session1_cabeceraIzquierda"><div id="totalAL"></div></td>
    <td width="4%">&nbsp;</td>
    <td width="6%">&nbsp;</td>
    <td colspan="2" class="session1_cabeceraIzquierda">APORTE PATRONAL</td>
    <td width="4%">&nbsp;</td>
    <td width="11%" class="session1_cabeceraIzquierda"><div id="totalAP"></div></td>
    <td width="8%">&nbsp;</td>
  </tr>
  <tr>
    <td width="9%">&nbsp;</td>
    <td width="18%" class="session1_bordes">Seguro de Vejez</td>
    <td>&nbsp;</td>
    <td><input type='text' id="segurovejez" name="segurovejez" class="number" size="10" onkeyup="sumaAL()" value="<?php echo $datosPlanilla['segurovejez'];?>" /></td>
    <td>%</td>
    <td>&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="17%" class="session1_bordes">Seguro Profesional</td>
    <td>&nbsp;</td>
    <td>
    <input type='text' id="seguroprofesional" name="seguroprofesional" onkeyup="sumaAP()" class="number" size="10" value="<?php echo $datosPlanilla['seguroprofesional'];?>" /></td>
    <td>%</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="session1_bordes">Riesgo Común</td>
    <td>&nbsp;</td>
    <td><input type='text' id="riesgocomun" name="riesgocomun" class="number" size="10" onkeyup="sumaAL()" value="<?php echo $datosPlanilla['riesgocomun'];?>" /></td>
    <td>%</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="session1_bordes">Provivienda</td>
    <td>&nbsp;</td>
    <td><input type='text' id="provivienda" name="provivienda" onkeyup="sumaAP()" class="number" size="10" value="<?php echo $datosPlanilla['provivienda'];?>" /></td>
    <td>%</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="session1_bordes">Comisión AFP</td>
    <td>&nbsp;</td>
    <td><input type='text' id="comisionafp" name="comisionafp" class="number" size="10" onkeyup="sumaAL()" value="<?php echo $datosPlanilla['comisionafp'];?>" /></td>
    <td>%</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="session1_bordes">Aporte Solidario</td>
    <td>&nbsp;</td>
    <td><input type='text' id="aportepatronal" name="aportepatronal" onkeyup="sumaAP()" class="number" size="10" value="<?php echo $datosPlanilla['aportepatronal'];?>" /></td>
    <td>%</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="session1_bordes">Aporte Solidario</td>
    <td>&nbsp;</td>
    <td><input type='text' id="aportesolidario" name="aportesolidario" class="number" onkeyup="sumaAL()" size="10" value="<?php echo $datosPlanilla['aportesolidario'];?>" /></td>
    <td>%</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
	<?php
	$consultaPlanCuenta = "select (select pp.cuenta from plandecuenta pp where pp.codigo=( left(ph.codigo,2) ))as 'padre',ph.codigo,ph.cuenta,ph.nivel from plandecuenta          ph  where ph.nivel>=5 and estado=1 order by ph.codigo;";
	$arrayPlan = $db->getDatosArray($consultaPlanCuenta,4);
   ?>
</td>
  </tr>
  <tr>
    <td colspan="5"><table width="100%" border="0">
      <tr>
        <td width="49%" align="right">Aporte Laboral P/P:</td>
        <td width="51%">
        <select name="planaportelaboralV" id="planaportelaboralV"  style="width:180px;"  disabled="disabled">
          <optgroup><option value="">--Seleccione una Cuenta--</option></optgroup>
          <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoConfiguracion['aportelaboral']); ?>                
        </select>
        <input type="hidden" id="planaportelaboral" name="planaportelaboral" 
        value="<?php echo $datoConfiguracion['aportelaboral'];?>" />
        </td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
    <td colspan="5"><table width="100%" border="0">
      <tr>
        <td width="44%" align="right">Aporte Patronal P/P:</td>
        <td width="56%" align="left">
        <select name="planaportepatronalV" id="planaportepatronalV"  style="width:180px;" disabled="disabled">
          <optgroup><option value="">--Seleccione una Cuenta--</option></optgroup>
            <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoConfiguracion['aportepatronal']); ?>
        </select>
        <input type="hidden" id="planaportepatronal" name="planaportepatronal" 
        value="<?php echo $datoConfiguracion['aportepatronal'];?>" />
        </td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="52%" valign="top"><table width="100%" border="0" class="session1_bordes2">
      <tr>
        <td width="27">&nbsp;</td>
        <td width="107">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="13">&nbsp;</td>
        <td width="19">&nbsp;</td>
        <td width="10">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="3" class="cabeceras">SEGURO MEDICO</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="session1_bordes">Seguro Medico:</td>
        <td><input type='text' id="seguromedico" name="seguromedico"  class="number"
         size="10" value="<?php echo $datosPlanilla['seguromedico'];?>" />
          %</td>
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
        <td colspan="4"><table width="100%" border="0">
          <tr>
            <td width="48%" align="right">Seguro Medico P/P:</td>
            <td width="52%">
            <select name="planseguromedicoV" id="planseguromedicoV"  style="width:180px;" disabled="disabled">
              <optgroup>
                <option value="">--Seleccione una Cuenta--</option>
                </optgroup>                
                <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoConfiguracion['seguromedico']); ?>
            </select>
            <input type="hidden" id="planseguromedico" name="planseguromedico" 
             value="<?php echo $datoConfiguracion['seguromedico'];?>" />
            </td>
          </tr>
        </table></td>
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
    </table></td>
    <td width="48%" rowspan="2" valign="top"><table width="100%" border="0" class="session1_border3">
      <tr>
        <td width="9%">&nbsp;</td>
        <td width="7%">&nbsp;</td>
        <td width="33%">&nbsp;</td>
        <td width="8%">&nbsp;</td>
        <td width="26%">&nbsp;</td>
        <td width="8%">&nbsp;</td>
        <td width="9%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="5" class="cabeceras">BONO DE ANTIGUEDAD</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="session1_bordes">2-4 Años</td>
        <td>&nbsp;</td>
        <td><input type='text' id="antiguedad1" name="antiguedad1" class="number" size="10" value="<?php echo $datosPlanilla['antiguedad1'];?>" /></td>
        <td>%</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="session1_bordes">5-7 Años</td>
        <td>&nbsp;</td>
        <td><input type='text' id="antiguedad2" name="antiguedad2" class="number" size="10" value="<?php echo $datosPlanilla['antiguedad2'];?>" /></td>
        <td>%</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="session1_bordes">8-10 Años</td>
        <td>&nbsp;</td>
        <td><input type='text' id="antiguedad3" name="antiguedad3" class="number" size="10" value="<?php echo $datosPlanilla['antiguedad3'];?>" /></td>
        <td>%</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="session1_bordes">11-14 Años</td>
        <td>&nbsp;</td>
        <td><input type='text' id="antiguedad4" name="antiguedad4" class="number" size="10" value="<?php echo $datosPlanilla['antiguedad4'];?>" /></td>
        <td>%</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="session1_bordes">15-19 Años</td>
        <td>&nbsp;</td>
        <td><input type='text' id="antiguedad5" name="antiguedad5" class="number" size="10" value="<?php echo $datosPlanilla['antiguedad5'];?>" /></td>
        <td>%</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="session1_bordes">20-24 Años</td>
        <td>&nbsp;</td>
        <td><input type='text' id="antiguedad6" name="antiguedad6" class="number" size="10" value="<?php echo $datosPlanilla['antiguedad6'];?>" /></td>
        <td>%</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="session1_bordes">25 Adelante</td>
        <td>&nbsp;</td>
        <td><input type='text' id="antiguedad7" name="antiguedad7" class="number" size="10" value="<?php echo $datosPlanilla['antiguedad7'];?>" /></td>
        <td>%</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="32" colspan="7"><table width="100%" border="0">
          <tr>
            <td width="41%" align="right">Bono Antigüedad:</td>
            <td width="59%"><select name="planantiguedadV" id="planantiguedadV"  style="width:180px;" disabled="disabled">
              <optgroup>
                <option>--Seleccione una Cuenta--</option>
                </optgroup>
                <?php $db->imprimirComboGrupoArray($arrayPlan,'','',$datoConfiguracion['bonoantiguedad']); ?>
              </select>
              <input type="hidden" id="planantiguedad" name="planantiguedad" 
             value="<?php echo $datoConfiguracion['bonoantiguedad'];?>" />
              </td>
            </tr>
        </table></td>
        </tr>
      <tr>
        <td height="20">&nbsp;</td>
        <td>&nbsp;</td>
        <td >&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr >
    <td valign="top" >
      
      <table width="100%" border="0" class="session1_bordes2">
        <tr>
          <td width="11%">&nbsp;</td>
          <td width="19%">&nbsp;</td>
          <td width="36%">&nbsp;</td>
          <td width="11%">&nbsp;</td>
          <td width="12%">&nbsp;</td>
          <td width="11%">&nbsp;</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="4" class="cabeceras">SALARIO MINIMO NACIONAL</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">S.M.N:</td>
          <td><input type='text' id="salariominimo" name="salariominimo" class="number"
           size="15" value="<?php echo $datosPlanilla['salariominimo'];?>" />
            Bs.</td>
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

        <tr height="29">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        </table>      
    </td>
    </tr>
</table>
</td>
</tr>
</table>
</form>
</div>
</td></tr></table>
<script>
  leerURL();
  sumaAL();
  sumaAP();
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