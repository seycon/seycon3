<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['idtrabajador'])) {
		   header("Location: ../index.php");	
	}	

	$tituloGeneral = "HOJA DE VIDA";
	$logo = $_GET['logo'];
	$idtrabajador = $_GET['idtrabajador'];
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabeceraHijos()
	{
	   echo "<tr>
		<td width='17%' class='session1_cabecera1'>Tipo</td>
		<td width='50%' class='session1_cabecera1'>Nombre</td>
		<td width='9%' class='session1_cabecera1'>Sexo</td>
		<td width='14%' class='session1_cabecera1'>F/Nacimiento</td>
		<td width='10%' class='session1_cabecera2_1'>Edad</td>
       </tr>";
	}
	
	function setcabeceraFamiliares()
	{
	   echo "<tr>
		<td width='13%' class='session1_cabecera1'>Parentesco</td>
		<td width='42%' class='session1_cabecera1'>Nombre</td>
		<td width='9%' class='session1_cabecera1'>F/Nacimiento</td>
		<td width='24%' class='session1_cabecera1'>Dirección</td>
		<td width='12%' class='session1_cabecera2_1'>Teléfono</td>
       </tr>";
	}
	
	function pie()
	{
	  echo "<div class='session4_pie'> 
	  <table width='93%' border='0' align='center'>
	  <tr>
		<td width='120' align='right'></td>
		<td width='324'></td>
		<td width='93'>&nbsp;</td>
		<td width='189'>&nbsp;</td>
		<td width='201'>&nbsp;</td>
		<td width='170' >Impreso:".date('d/m/Y');echo"</td>
		<td width='130'>Hora:".date('H:i:s');echo"</td>
	  </tr>
	  </table>
	  </div>";
    }
	
	function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}	

	function setDato($tipodependencia, $nombre, $sexo, $fecha, $edad, $num, $tipo)
	{
		 $clase1 = "";	
		 if ($tipo == "cierre") {
			 $clase1 = "border-bottom:1.5px solid";
		 }	
		 $clase2 = "";
		 if ($num % 2 == 0) {
			 $clase2 = "cebra";
		 }
	 
	 echo "<tr class='$clase2'>
		     <td class='session3_datosF1_1' style='$clase1' align='center'>".strtoupper($tipodependencia)."</td>
		     <td class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;".strtoupper($nombre)."</td>
			 <td class='session3_datosF1_2' style='$clase1'>".strtoupper($sexo)."</td>
			 <td class='session3_datosF1_2' style='$clase1'>".strtoupper($fecha)."</td>
			 <td class='session3_datosF1_3' style='$clase1'>".strtoupper($edad)."</td>
		   </tr>";

	}	
	
	function setFilas($cantidad)
	{
		for ($i = $cantidad; $i <= 11; $i++) {
		 $clase1 = "";	
		 if ($i == 11) {
			 $clase1 = "border-bottom:1.5px solid";
		 }	
		 $clase2 = "";
		 if ($i % 2 == 0) {
			 $clase2 = "cebra";
		 }
	 
	     echo "<tr class='$clase2'>
				 <td class='session3_datosF1_1' style='$clase1' align='left'>&nbsp;</td>
				 <td class='session3_datosF1_2' style='$clase1'>&nbsp;</td>
				 <td class='session3_datosF1_2' style='$clase1'>&nbsp;</td>
				 <td class='session3_datosF1_2' style='$clase1'>&nbsp;</td>
				 <td class='session3_datosF1_3' style='$clase1'>&nbsp;</td>
			   </tr>";
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_imprimir.css"/>
<title>Reporte de Trabajador</title>
</head>

<body>
<?php
  $sql = "select idtrabajador,left(nombre,17)as 'nombre',left(apellido,17)as 'apellido'
          ,carnetidentidad,origenci,fechanacimiento,sexo,left(direccion,17)as 'direccion'
		  ,telefono,celular,left(nacionalidad,17)as 'nacionalidad',left(ciudad,17)as 'ciudad'
		  ,numerolibreta,left(unidad,20)as 'unidad' 
		  ,left(vivienda,17)as 'vivienda',left(descripcionvivienda,17)as 'descripcionvivienda'
		  ,idcargo,seccion
		  ,left(emailpersonal,25)as 'emailpersonal',left(emailcorporativo,25)as 'emailcorporativo'
		  ,estadocivil,fotoprincipal
		  ,fotosecundaria,hijos,fechaingreso,sueldobasico,modalidadcontrato
		  ,fechafinalizacion,diastrabajo,tipohorario,horariodetrabajo
		  ,puntualidad,formadepago,nombrebanco,transporte,bonoproduccion
		  ,asistencia,idsucursal,numerocuenta,seguromedico,left(observacion,400)as 'observacion' 
		  ,licenciamoto,licenciaauto,fechamoto,fechaauto
		  ,categoriaauto,idusuario,estado from trabajador where idtrabajador=$idtrabajador";
  $datoTrabajador = $db->arrayConsulta($sql);
  $sql = "select left(concat(t.nombre,' ',t.apellido),23)as 'usuario',left(c.cargo,15)as 'cargo'
          from trabajador t,usuario u,cargo c where t.idtrabajador=u.idtrabajador 
          and u.estado=1 and t.idcargo=c.idcargo and u.idusuario=$datoTrabajador[idusuario]";
  $datoUsuario = $db->arrayConsulta($sql);		  
  $sql = "select left(lugarprimaria,20)as 'lugarprimaria',anioprimaria,left(nivelprimaria,24)as 'nivelprimaria'
                ,left(lugarsecundaria,20)as 'lugarsecundaria'
				,aniosecundaria,left(nivelsecundaria,24)as 'nivelsecundaria'
				,left(lugaruniversitaria,20)as 'lugaruniversitaria'
				,aniouniversitaria,left(niveluniversitaria,24)as 'niveluniversitaria'
				,idtrabajador
		 from niveleducacion where idtrabajador=$idtrabajador";
  $datoEducacion = $db->arrayConsulta($sql);
  $sql = "select left(idioma1,20)as 'idioma1',nivel1,left(idioma2,20)as 'idioma2',nivel2
				,left(idioma3,20)as 'idioma3',nivel3,idtrabajador from idiomas where idtrabajador=$idtrabajador";
  $datoIdioma = $db->arrayConsulta($sql);
  $sql = "select left(habilidad1,20)as 'habilidad1',nivel1,left(habilidad2,20)as 'habilidad2',nivel2
  ,left(habilidad3,20)as 'habilidad3',nivel3,idtrabajador from habilidad where idtrabajador=$idtrabajador";
  $datoHabilidad = $db->arrayConsulta($sql);
  $sql = "select alcohol,fumar,medicamento,drogas,mariguana,
					left(descripcionmedicamentos,24)as 'descripcionmedicamentos',
					left(descripciondroga,24)as 'descripciondroga',left(descripcionalcohol,24)as 'descripcionalcohol',
					left(descripcionfuma,24)as 'descripcionfuma',salud,enfermedad,sida,accidente
					,left(descripcionenfermedad,20)as 'descripcionenfermedad',
					left(descripcionaccidente,20)as 'descripcionaccidente',idtrabajador
					 from habitos where idtrabajador=$idtrabajador";
  $datoHabitos = $db->arrayConsulta($sql);
  $sql = "select left(nombre,24)as 'nombre',left(apellido,24)as 'apellido'
                 ,left(direccion,24)as 'direccion',telefono,left(parentesco,24)as 'parentesco',
				 left(tiempo,24)as 'tiempo',vivienda,estadocivil,left(nacionalidad,20)as 'nacionalidad'
				 ,left(profesion,20)as 'profesion',
				   ingresomensual,left(nombreconyugue,20)as 'nombreconyugue'
				   ,ingresoconyugue,idtrabajador from garante where idtrabajador=$idtrabajador";
  $datoGarante = $db->arrayConsulta($sql);
  $sql = "select left(nombrecomercial, 15)as 'nombrecomercial' from sucursal 
   where idsucursal=$datoTrabajador[idsucursal]";
  $datoSucursal = $db->arrayConsulta($sql);
?>

<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php 
if ($logo == 'true') {
    echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
}
  ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2"></td></tr>    
     <tr><td align="center" class="session1_titulo2"></td></tr>  
  </table>
</div>
<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal">
	  <?php echo strtoupper($datoSucursal['nombrecomercial']); ?>
      </td></tr>
    </table>
</div>
<?php 
if ($datoTrabajador['fotoprincipal'] != "") {
  $url = $datoTrabajador['fotoprincipal'];	
} else {
  $url = "files/modelo_sombraSeycon.png";
}
?>
<div class="session1_fotoprincipal"> 
<img src='../<?php echo $url;?>' alt='camara' width='119' height='119' style="position:relative;margin:0 auto;"/>
</div>
<?php 
if ($datoTrabajador['fotosecundaria'] != "") {
  $url = $datoTrabajador['fotosecundaria'];	
} else {
  $url = "files/modelo_sombraSeycon.png";
}
?> 
<div class="session1_fotosecundaria">
<img src='../<?php echo $url;?>' alt='camara' style="position:relative;margin:0 auto;" width='119' height='119' />
</div>

<div class="session3_datos">
  <table width="90%" border="0" align="center">
  <tr>
    <td colspan="2" class="session1_subtitulo">1. DATOS PERSONALES</td>
    <td align="right" class="session1_subtitulo1">&nbsp;</td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo1">&nbsp;</td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Nombre:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['nombre']);?></td>
    <td align="right" class="session1_subtitulo1">&nbsp;</td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td width="19%" align="right" class="session1_subtitulo">Apellido:</td>
    <td width="29%" class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['apellido']);?></td>
    <td width="18%" align="right" class="session1_subtitulo1">&nbsp;</td>
    <td width="34%" class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Fecha Nacimiento:</td>
    <td class="session1_subtitulo1">
	<?php echo $db->GetFormatofecha($datoTrabajador['fechanacimiento'], "-");?>
    </td>
    <td class="session1_subtitulo1"></td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">C.I.:</td>
    <td class="session1_subtitulo1"><?php echo $datoTrabajador['carnetidentidad'];?>-
    <?php echo $datoTrabajador['origenci'];?></td>
    <td class="session1_subtitulo1"></td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Sexo:</td>
    <td class="session1_subtitulo1"><?php echo $datoTrabajador['sexo'];?></td>
    <td class="session1_subtitulo1"></td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Nacionalidad:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['nacionalidad']);?></td>
    <td class="session1_subtitulo1"></td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
  <?php 
    $vivienda = $datoTrabajador['vivienda'];
    if ($datoTrabajador['vivienda'] == "Otros") {
	  $vivienda = $datoTrabajador['descripcionvivienda'];	
	}  
  ?>
    <td align="right" class="session1_subtitulo">Domicilio:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['direccion']);?></td>
    <td class="session1_subtitulo" align="right">Vive:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($vivienda);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Ciudad:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['ciudad']);?></td>
    <td align="right" class="session1_subtitulo">Telefono:</td>
    <td class="session1_subtitulo1"><?php echo $datoTrabajador['telefono'];?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Celular:</td>
    <td class="session1_subtitulo1"><?php echo $datoTrabajador['celular'];?></td>
    <td align="right" class="session1_subtitulo">Email:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['emailpersonal']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Libreta de S.M. Nº:</td>
    <td class="session1_subtitulo1"><?php echo $datoTrabajador['numerolibreta'];?></td>
    <td align="right" class="session1_subtitulo">Unidad:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['unidad']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;Licencia de conducir</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
    <?php
	    $atributo = "";
	    if ($datoTrabajador['licenciamoto'] == 1) {
			$atributo = "checked='checked'";
		}
	?>
  <tr>
    <td align="right" class="session1_subtitulo">Moto:</td>
    <td class="session1_subtitulo1"><input type="checkbox" <?php echo $atributo;?>/></td>
    <td align="right" class="session1_subtitulo">F/Vencimiento:</td>
    <td class="session1_subtitulo1"><?php echo $db->GetFormatofecha($datoTrabajador['fechamoto'], "-");?></td>
  </tr>
    <?php
	    $atributo = "";
	    if ($datoTrabajador['licenciaauto'] == 1) {
			$atributo = "checked='checked'";
		}
	?>
  <tr>
    <td align="right" class="session1_subtitulo">Auto:</td>
    <td class="session1_subtitulo1"><input type="checkbox" <?php echo $atributo;?>/></td>
    <td align="right" class="session1_subtitulo">F/Vencimiento:</td>
    <td class="session1_subtitulo1"><?php echo $db->GetFormatofecha($datoTrabajador['fechaauto'], "-");?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span class="session1_subtitulo"> Categoria:</span>
      <?php echo $datoTrabajador['categoriaauto'];?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">2. EDUCACION</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td class="session1_subtitulo">&nbsp;&nbsp;2.1 Nivel</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="center" class="session1_subtitulo">Año</td>
    <td class="session1_subtitulo">Nivel de Aprovación</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Primaria:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoEducacion['lugarprimaria']);?></td>
    <td class="session1_subtitulo1" align="center"><?php echo $datoEducacion['anioprimaria'];?></td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoEducacion['nivelprimaria']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Secundaria:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoEducacion['lugarsecundaria']);?></td>
    <td align="center" class="session1_subtitulo1"><?php echo $datoEducacion['aniosecundaria'];?></td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoEducacion['nivelsecundaria']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Universitaria:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoEducacion['lugaruniversitaria']);?></td>
    <td align="center" class="session1_subtitulo1"><?php echo $datoEducacion['aniouniversitaria'];?></td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoEducacion['niveluniversitaria']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td class="session1_subtitulo">&nbsp;&nbsp;2.2 Idiomas</td>
    <td class="session1_subtitulo1"></td>
    <td align="center" class="session1_subtitulo">Dominio</td>
    <td class="session1_subtitulo">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">1.</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoIdioma['idioma1']);?></td>
    <td class="session1_subtitulo1" ><?php echo strtoupper($datoIdioma['nivel1']);?></td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">2.</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoIdioma['idioma2']);?></td>
    <td  class="session1_subtitulo1"><?php echo strtoupper($datoIdioma['nivel2']);?></td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">3.</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoIdioma['idioma3']);?></td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoIdioma['nivel3']);?></td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td class="session1_subtitulo">&nbsp;&nbsp;2.3 Habilidades</td>
    <td class="session1_subtitulo1"></td>
    <td align="center" class="session1_subtitulo">Dominio</td>
    <td class="session1_subtitulo">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">1.</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoHabilidad['habilidad1']);?></td>
    <td class="session1_subtitulo1" ><?php echo strtoupper($datoHabilidad['nivel1']);?></td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">2.</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoHabilidad['habilidad2']);?></td>
    <td  class="session1_subtitulo1"><?php echo strtoupper($datoHabilidad['nivel2']);?></td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">3.</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoHabilidad['habilidad3']);?></td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoHabilidad['nivel3']);?></td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">3. HABITOS</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;3.1 ¿Consume algún tipo de droga?</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo">Descripción</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1"><span class="session1_subtitulo">a) Alcohol</span></td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['alcohol'] == 1) echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    NO<input type="checkbox" <?php 
	if ($datoHabitos['alcohol'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"><?php 
	if ($datoHabitos['alcohol'] == 1) echo strtoupper($datoHabitos['descripcionalcohol']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1"><span class="session1_subtitulo">b) Fuma</span></td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['fumar'] == "1") echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    NO<input type="checkbox" <?php 
	if ($datoHabitos['fumar'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"><?php 
	if ($datoHabitos['fumar'] == "1") echo strtoupper($datoHabitos['descripcionfuma']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1"><span class="session1_subtitulo">c) Medicamento</span></td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['medicamento'] == "1") echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    NO<input type="checkbox" <?php 
	if ($datoHabitos['medicamento'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"><?php 
	if ($datoHabitos['medicamento'] == "1") echo strtoupper($datoHabitos['descripcionmedicamentos']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo">d) Droga</td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['drogas'] == "1") echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    NO<input type="checkbox" <?php 
	if ($datoHabitos['drogas'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"><?php 
	if ($datoHabitos['drogas'] == "1") echo strtoupper($datoHabitos['descripciondroga']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo">e) Mariguana</td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['mariguana'] == "1") echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    NO<input type="checkbox" <?php 
	if ($datoHabitos['mariguana'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>  
</table>
</div>
<?php
    pie();
	nextPage();
?>
<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php 
if ($logo == 'true') {
    echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
}
  ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2"></td></tr>    
     <tr><td align="center" class="session1_titulo2"></td></tr>  
  </table>
</div>
<div class="session3_datosHoja2">
<table width="90%" border="0" align="center">
  <tr>
    <td width="21%" ></td>
    <td width="33%" ></td>
    <td width="19%" ></td>
    <td width="27%" ></td>
  </tr>
<tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;3.2 Estado de salud</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo">Descripción</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) Se encuentra actualmente sano/a</td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['salud'] == 1) echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      NO<input type="checkbox" <?php 
	if ($datoHabitos['salud'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)Tiene alguna enfermedad diagnosticada</td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['enfermedad'] == "1") echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      NO<input type="checkbox" <?php 
	if ($datoHabitos['enfermedad'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"><?php 
	if ($datoHabitos['enfermedad'] == "1") echo strtoupper($datoHabitos['descripcionenfermedad']);?></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)Tiene SIDA o es portador de VIH</td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['sida'] == "1") echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      NO<input type="checkbox" <?php 
	if ($datoHabitos['sida'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;&nbsp;&nbsp;
    d)Ha Sufrido algún accidente o tiene alguna alteración física</td>
    <td class="session1_subtitulo">SI<input type="checkbox" <?php 
	if ($datoHabitos['accidente'] == "1") echo "checked='checked'";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      NO<input type="checkbox" <?php 
	if ($datoHabitos['accidente'] == "0") echo "checked='checked'";?>/></td>
    <td class="session1_subtitulo1"><?php 
	if ($datoHabitos['accidente'] == "1") echo strtoupper($datoHabitos['descripcionaccidente']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">4. GARANTE PERSONAL</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Nombre:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['nombre']);?></td>
    <td align="right" class="session1_subtitulo">Estado Civil:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['estadocivil']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Apellido:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['apellido']);?></td>
    <td align="right" class="session1_subtitulo">Nacionalidad:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['nacionalidad']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Dirección:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['direccion']);?></td>
    <td align="right" class="session1_subtitulo">Profesión:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['profesion']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Teléfono:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['telefono']);?></td>
    <td align="right" class="session1_subtitulo">Ingreso Mensual: </td>
    <td class="session1_subtitulo1"><?php echo number_format($datoGarante['ingresomensual'],2);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Parentesco:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['parentesco']);?></td>
    <td align="right" class="session1_subtitulo">Nombre Conyugue:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['nombreconyugue']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Tiempo:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['tiempo']);?></td>
    <td align="right" class="session1_subtitulo">Ingreso Conyugue:</td>
    <td class="session1_subtitulo1"><?php echo number_format($datoGarante['ingresoconyugue'],2);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Tenencia de vivienda:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoGarante['vivienda']);?></td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">5. CONTRATO LABORAL</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">
	<?php
		$sql = "select idcargo, left(cargo,30)as 'cargo' from cargo where idcargo=$datoTrabajador[idcargo]";
		$datoCargo = $db->arrayConsulta($sql);
	?></td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Cargo:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoCargo['cargo']);?></td>
    <td align="right" class="session1_subtitulo">Sueldo Básico</td>
    <td class="session1_subtitulo1"><?php echo number_format($datoTrabajador['sueldobasico'], 2);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Fecha Ingreso:</td>
    <td class="session1_subtitulo1"><?php echo $db->GetFormatofecha($datoTrabajador['fechaingreso'],"-");?></td>
    <td align="right" class="session1_subtitulo">Bono  Producción</td>
    <td class="session1_subtitulo1"><?php echo number_format($datoTrabajador['bonoproduccion'], 2);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Fecha Finalización:</td>
    <td class="session1_subtitulo1"><?php echo $db->GetFormatofecha($datoTrabajador['fechafinalizacion'],"-");?></td>
    <td align="right" class="session1_subtitulo">Bono Transporte:</td>
    <td class="session1_subtitulo1"><?php echo number_format($datoTrabajador['transporte'], 2);?></td>
  </tr>
  <?php 
  if (isset($datoTrabajador['seccion'])) {
	  $sql = "select left(nombre,15)as 'nombre' from departamento where iddepartamento=$datoTrabajador[seccion]";
	  $departamento = $db->arrayConsulta($sql);
  }
  ?>
  <tr>
    <td align="right" class="session1_subtitulo">Departamento:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($departamento['nombre']);?></td>
    <td align="right" class="session1_subtitulo">Bono Puntualidad:</td>
    <td class="session1_subtitulo1"><?php echo number_format($datoTrabajador['puntualidad'],2);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Forma de Pago:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['formadepago']);?></td>
    <td align="right" class="session1_subtitulo">Bono Asistencia:</td>
    <td class="session1_subtitulo1"><?php echo number_format($datoTrabajador['nombreconyugue'], 2);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Nombre del Banco:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['nombrebanco']);?></td>
    <td align="right" class="session1_subtitulo">Nº de Cuenta:</td>
    <td class="session1_subtitulo1"><?php echo $datoTrabajador['numerocuenta'];?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Correo Corporativo:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['emailcorporativo']);?></td>
    <td align="right" class="session1_subtitulo">Nº de Seguro:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['seguromedico']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Sucursal Planilla :</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoSucursal['nombrecomercial']);?></td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  
  
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">6. DATOS MATRIMONIALES</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <?php
     $sql = "select left(nombre,22)as 'nombre',left(lugarnacimiento,22)as 'lugarnacimiento'
	           ,left(empresa,20)as 'empresa',situacion
			   ,left(celular,20)as 'celular',fechanacimiento,left(direccion,20)as 'direccion',idtrabajador
			 from conyugue where idtrabajador=$datoTrabajador[idtrabajador]";
     $datoConyugue = $db->arrayConsulta($sql);
  ?>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;6.1 Estado Civil : 
    <span class="session1_subtitulo1"><?php echo strtoupper($datoTrabajador['estadocivil']);?></span></td>
    <td align="right" class="session1_subtitulo">Situación:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoConyugue['situacion']); ?></td>
  </tr>
  <tr>
    <td class="session1_subtitulo" align="right">Nombre Conyugue:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoConyugue['nombre']);?></td>
    <td align="right" class="session1_subtitulo">Empresa:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoConyugue['empresa']);?></td>
  </tr>
  <tr>
    <td class="session1_subtitulo" align="right">Fecha de nacimiento:</td>
    <td class="session1_subtitulo1"><?php echo $db->GetFormatofecha($datoConyugue['fechanacimiento'],"-");?></td>
    <td align="right" class="session1_subtitulo">Celular:</td>
    <td class="session1_subtitulo1"><?php echo $datoConyugue['celular'];?></td>
  </tr>
  <tr>
    <td class="session1_subtitulo" align="right">Lugar de Nacimiento:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoConyugue['lugarnacimiento']);?></td>
    <td align="right" class="session1_subtitulo">Dirección:</td>
    <td class="session1_subtitulo1"><?php echo strtoupper($datoConyugue['direccion']);?></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;&nbsp;6.1 Hijos</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo">&nbsp;</td>
  </tr>
</table>
<?php
 
	
	$sql = "select tipodependencia,left(nombre,40)as 'nombre',genero,fechanacimiento,
				(YEAR(CURDATE())-YEAR(fechanacimiento))- (RIGHT(CURDATE(),5)<RIGHT(fechanacimiento,5))as 'edad' 
				 from hijos where idtrabajador=$datoTrabajador[idtrabajador] order by idhijos";
    $dato = $db->consulta($sql);
	
?>

<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">  
  <?php
    setcabeceraHijos();
	$cant = 0;
	while ($data = mysql_fetch_array($dato)){
		$fecha = $db->GetFormatofecha($data['fechanacimiento'], "-");
		$genero = ($data['genero'] == "Masculino") ? "M" : "F";
		$cant++;
		$tipo = "normal";
		if ($cant == 11) {
		    $tipo = "cierre";	
		}		
		setDato($data['tipodependencia'], $data['nombre'], $genero, $fecha, $data['edad'], $cant, $tipo);		
	}
	$cant++;
    setFilas($cant);
  ?>
</table>

</div>

<?php
    pie();
	nextPage();
?>
<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php 
if ($logo == 'true') {
    echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
}
  ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2"></td></tr>    
     <tr><td align="center" class="session1_titulo2"></td></tr>  
  </table>
</div>
<div class="session3_datosHoja2">
<table width="90%" border="0" align="center">
  <tr>
    <td width="21%" ></td>
    <td width="33%" ></td>
    <td width="19%" ></td>
    <td width="27%" ></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">7. DATOS FAMILIARES</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo"></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo"></td>
  </tr>
</table>
<?php
  $sql = "select parentesco,left(nombre,35)as 'nombre',fechanacimiento
				,left(direccion,18)as 'direccion',left(telefono,15)as 'telefono',idtrabajador
				 from familiares where idtrabajador=$datoTrabajador[idtrabajador] 
  		  order by idfamiliares";
  $dato = $db->consulta($sql);		  
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">  
  <?php
    setcabeceraFamiliares();
	$cant = 0;
	while ($data = mysql_fetch_array($dato)){
		$fecha = $db->GetFormatofecha($data['fechanacimiento'], "-");
		$cant++;
		$tipo = "normal";
		if ($cant == 11) {
		    $tipo = "cierre";	
		}		
		setDato($data['parentesco'], $data['nombre'], $fecha, $data['direccion'], $data['telefono'], $cant, $tipo);		
	}
	$cant++;
    setFilas($cant);
  ?>
</table>
<table width="90%" border="0" align="center">
  <tr>
    <td width="21%" >&nbsp;</td>
    <td width="33%" ></td>
    <td width="19%" ></td>
    <td width="27%" ></td>
  </tr>
  <tr>
    <td colspan="2" class="session1_subtitulo">8. OBSERVACIONES</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo"></td>
  </tr>
  <tr>
    <td colspan="4" class="session1_subtitulo1"><?php 
	echo strtoupper($datoTrabajador['observacion']);
	?></td>
  </tr>
</table>

</div>

 <div class="session3_subPie1"> 
  <table width="70%" border="0" align="center">
  
  <tr>    
    <td width="20%" >&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="15%">&nbsp;</td>    
    <td width="20%">&nbsp;</td>    
  </tr>
   <tr>   
    <td style="font-weight:bold">............................................</td>
    <td>&nbsp;</td>
    <td style="font-weight:bold">...........................................</td>
    <td>&nbsp;</td>    
    <td style="font-weight:bold">..........................................</td>    
  </tr>
  <tr>   
    <td align="center" style="font-weight:bold;"><?php echo $datoUsuario['usuario'];?><br />
    <span style="font-weight:bold;font-size:10px;"><?php echo $datoUsuario['cargo'];?></span></td>
     <td>&nbsp;</td>    
    <td align="center" style="font-weight:bold">Revisado</td>
    <td>&nbsp;</td>    
    <td align="center" style="font-weight:bold">Aprobado</td>
    
  </tr>
</table> 
 </div>
 
 <div class="session3_subPie2"> 
  <table width="30%" border="0" align="center">  
   <tr>    
    <td align="center">....................................</td>   
   </tr>
   <tr>   
    <td align="center" style="font-weight:bold;font-size:11px;">Trabajador</td>       
   </tr>  
  </table> 
 </div>

<?php
    pie();	
?>
  
</body>
</html>

<?php
	$header = "
	<table align='right' width='16%' >  
	  <tr><td align='center' style='border:1px solid;font-size:11px;' bgcolor='#E6E6E6' >Nº $datoTrabajador[idtrabajador]
	    Pag. {PAGENO}/{nb}</td></tr>
	</table>";
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>