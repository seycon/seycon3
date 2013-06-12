<?php 
    include("admin/conexion.php");
	$db = new MySQL();
    $sql = "select * from pagina where idplantilla=1 and numero=1;";  
    $pagina = $db->arrayConsulta($sql);
	$sql = "select * from plantilla where idplantilla=1;";
	$plantilla = $db->arrayConsulta($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagina['titulo']; ?></title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $pagina['estilo'];?>" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.2" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {
			$('.fancybox').fancybox();

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(0,0,0,0.85)'
						}
					}
				}
			});

	});
	</script>

	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
	</style>
    <script src="js/mobilynotes.js" type="text/javascript"></script>
    <script src="js/init.js" type="text/javascript"></script>
</head>

<body>
<div style="width:100%;">
 <?php
	  $sql = "select * from contenido where session='titulo principal' and idpagina=$pagina[idpagina]";
	  $tp = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='titulo secundario' and idpagina=$pagina[idpagina]";
	  $ts = $db->arrayConsulta($sql);
	  
	  $sql = "select *from contenido where session='imagen logo' and idpagina=$pagina[idpagina]";
	  $img = $db->arrayConsulta($sql);
	  
	  
	  
 ?>
<div class="header">

  <div class="acopleCabecera">
  <div class="imagenLogo"><img src="<?php echo $img['contem'];?>" width="60" height="50" /> </div>
    <div class="titlePrincipal"><?php echo $tp['contem'];?></div>
    <div class="titleSecundario"><?php echo $ts['contem'];?></div>
  </div>
</div>


<div class="menuAcople">
<ul class="menu1">
<?php
    $sql = "select * from pagina where idplantilla=$pagina[idplantilla] and estado=1 order by numero asc";  
    $dato = $db->consulta($sql);
	$n = 0;
	while ($data = mysql_fetch_array($dato)) {
		$n++;
		$clase = "";
		if ( $pagina['idpagina'] == $data['idpagina'] ) {
		    $clase = "current";	
		}
		
		if ($n == 1) {
	        echo "<li class='$clase'><a href='index.php'><b>$data[textomenu]</b></a></li>";	
	    } else {
		    echo "<li class='$clase'><a href='$data[url]'><b>$data[textomenu]</b></a></li>";	
		}
	}
?>
</ul>
</div>

<div class="acopleContem">
<div class="monitor"></div>
<div id="content">
		
		
		<div class="wrap">
			<div class="notes_img">
            <?php
			 $sql = "select *from contenido where idpagina=$pagina[idpagina] and (
			  session='imagen galeria 1' or session='imagen galeria 2' 
			  or session='imagen galeria 3' or session='imagen galeria 4' or session='imagen galeria 5') ";
	         $galerias = $db->consulta($sql);
			 while ($data = mysql_fetch_array($galerias)) {
				echo "<div class='note'>
					<img src='$data[contem]' alt='' width='200' height='200'/>
				</div>"; 
			 }
			
			?>			
			</div>	
		</div>	
		
	</div>


<div class="contem">
<div class="contemData">
 <?php  
	  
	  $sql = "select * from contenido where session='titulo contenido lateral' and idpagina=$pagina[idpagina]";
	  $tcl = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido lateral' and idpagina=$pagina[idpagina]";
	  $contl = $db->arrayConsulta($sql);
	  
	  $sql = "select c.*,p.dominio from compartido c,plantilla p where c.idplantilla=p.idplantilla and
	   c.idcompartido=$_GET[compartido]";
	  $avisoCompartido = $db->arrayConsulta($sql);
	  $fecha = $db->GetFormatofecha($avisoCompartido['fecha'],'-');
	  $nImages = 0;
	  for ($i = 1; $i <= 5; $i++) {
		  if ($avisoCompartido["imagen$i"] != "") {
			$nImages++;  
		  }
	  }
	  $stilo = "";
	  if ($nImages > 0) {
		$stilo = "contemAuxiliar";  
	  }
	  if ($nImages > 3) {
		$stilo = "contemAuxiliar2";  
	  }
	  
 ?>


  <div class="contemDataTitle"><?php echo $avisoCompartido['titulo'];?></div>
  <div class="contemDataConten">
  <div class="<?php echo $stilo;?>" >
  <?php if ( $avisoCompartido['imagen'] != "" ) {?>
  <a class="fancybox" href="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen'];?>" 
   data-fancybox-group="gallery" >
  <img src="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen'];?>" class="bordeImagen" 
  style="position:relative;float:left;margin:5px 10px 8px 0;" width="150" height="150"/>
  </a>
  <?php }?>
  <?php if ( $avisoCompartido['imagen1'] != "" ) {?>
  <a class="fancybox" href="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen1'];?>" 
   data-fancybox-group="gallery" >
  <img src="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen1'];?>" class="bordeImagen" 
  style="position:relative;float:left;margin:5px 10px 8px 0;" width="150" height="150"/>
  </a>
  <?php }?>
  <?php if ( $avisoCompartido['imagen2'] != "" ) {?>
  <a class="fancybox" href="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen2'];?>" 
   data-fancybox-group="gallery" >
  <img src="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen2'];?>" class="bordeImagen" 
  style="position:relative;float:left;margin:5px 10px 8px 0;" width="150" height="150"/>
  </a>
  <?php }?>
  <?php if ( $avisoCompartido['imagen3'] != "" ) {?>
  <a class="fancybox" href="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen3'];?>" 
   data-fancybox-group="gallery" >
  <img src="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen3'];?>" class="bordeImagen" 
  style="position:relative;float:left;margin:5px 10px 8px 0;" width="150" height="150"/>
  </a>
  <?php }?>
  <?php if ( $avisoCompartido['imagen4'] != "" ) {?>
  <a class="fancybox" href="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen4'];?>" 
   data-fancybox-group="gallery" >
  <img src="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen4'];?>" class="bordeImagen" 
  style="position:relative;float:left;margin:5px 10px 8px 0;" width="150" height="150"/>
  </a>
  <?php }?>
  <?php if ( $avisoCompartido['imagen5'] != "" ) {?>
  <a class="fancybox" href="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen5'];?>" 
   data-fancybox-group="gallery" >
  <img src="http://<?php echo $avisoCompartido['dominio']."/".$avisoCompartido['imagen5'];?>" class="bordeImagen" 
  style="position:relative;float:left;margin:5px 10px 8px 0;" width="150" height="150"/>
  </a>
  <?php }?>
  
  </div>
  
   <?php  
    echo $avisoCompartido['descripcion'];
	?></div>
  <div class="contemDataFoter"><span style="font-size:12px;font-weight:bold;">Fecha Publicación:</span>
  <span style="font-size:11px;font-weight:bold;"> <?php echo $fecha;?></span></div>
  </div>

</div>



<div class="cabeaviso1"><div class="tituloAviso"><?php echo $tcl['contem'];?></div></div>
<div class="avisos1">
  <div class="contenAvisos"><?php echo $contl['contem'];?></div>
</div>



<div class="contemCompartido">
    <div class="contemCompartidoC">Avisos Compartidos</div>
    <div class="contemCompartidoI">
    <?php
	 $sql = "select c.idcompartido,p.dominio,c.imagen,left(c.titulo,28)as 'titulo',left(c.descripcion,60)as 'descripcion' 
	 from compartido c,plantilla p where c.idplantilla=p.idplantilla and c.estado=1 and 
	 (c.tipo='publico' or c.idplantilla=1) order by c.idcompartido desc;";
	 $datoC = $db->consulta($sql);
	 while ($data = mysql_fetch_array($datoC)) {
	?>
    
     <div class="datoCompartido">
     <?php if($data['imagen'] != "") {?>
     <a class="fancybox-effects-d" href="<?php echo $data['imagen'];?>"  title="">
     <img src="http://<?php echo $data['dominio']."/".$data['imagen'];?>"  
     style="float:left;margin:5px 7px 8px 0;" width="40" height="40"/></a>
      <?php }?>
     <span style="font-weight:bold;"><?php echo $data['titulo'];?></span> <?php echo $data['descripcion'];?>...
     <a href='avisoCompartido.php?compartido=<?php echo $data['idcompartido'];?>' style="color:#030">Leer Mas.</a>
     </div>
     
     <?php }?>
          
    </div>
</div>

</div>




<div class="pie">
 <?php
	  $sql = "select * from contenido where session='titulo pie session1' and idpagina=$pagina[idpagina]";
	  $tp1 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido pie session1' and idpagina=$pagina[idpagina]";
	  $cont1 = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo pie session2' and idpagina=$pagina[idpagina]";
	  $tp2 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido pie session2' and idpagina=$pagina[idpagina]";
	  $cont2 = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo pie session3' and idpagina=$pagina[idpagina]";
	  $tp3 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido pie session3' and idpagina=$pagina[idpagina]";
	  $cont3 = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='autor pagina' and idpagina=$pagina[idpagina]";
	  $at = $db->arrayConsulta($sql);
 ?>

<div class="acoplePie">
<div class="titlepieSub1"><?php echo $tp1['contem'];?></div>
<div class="pieSub1"><?php echo $cont1['contem'];?></div>
<div class="titlepieSub2"><?php echo $tp2['contem'];?></div>
<div class="pieSub2"><?php echo $cont2['contem'];?></div>
<div class="titlepieSub3"><?php echo $tp3['contem'];?></div>
<div class="pieSub3"><?php echo $cont3['contem'];?></div>

<div class="subDivision"></div>
<div class="author"><?php echo $at['contem'];?></div>
<a href="http://<?php echo $plantilla['dominio'];?>:2095/" target="_blank"><div class="mensajeria"></div></a>
</div>


</div>

</div>
</body>
</html>