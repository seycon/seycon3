<?php 
    include("admin/conexion.php");
	$db = new MySQL();
    $sql = "select * from pagina where idplantilla=3 and numero=2;";  
    $pagina = $db->arrayConsulta($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $pagina['estilo'];?>" />
<link rel="stylesheet" media="screen" type="text/css" href="css/spacegallery.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="css/custom.css" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/spacegallery.js"></script>
<script>
 window.onload = function() {
	$('#myGallery').spacegallery({loadingClass: 'loading'}); 	 
 }
</script>
</head>

<body>
<div class="franjaHeader"></div>
  <?php
	  $sql = "select * from contenido where session='titulo principal' and idpagina=$pagina[idpagina]";
	  $tp = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='titulo secundario' and idpagina=$pagina[idpagina]";
	  $ts = $db->arrayConsulta($sql);	    
 ?>
 <div class="acopleCabecera">
  <div class="header">  
     <div class="tituloPrincipal"><?php echo $tp['contem'];?></div> 
     <div class="tituloSecundario"><?php echo $ts['contem'];?></div>
     
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
     <div style="position:absolute;left:700px; width:400px;border:0px solid;">
     <div id="myGallery" class="spacegallery">
            <?php
			 $sql = "select *from contenido where idpagina=$pagina[idpagina] and (
			  session='imagen galeria 1' or session='imagen galeria 2' 
			  or session='imagen galeria 3' or session='imagen galeria 4' or session='imagen galeria 5') ";
	         $galerias = $db->consulta($sql);
			 while ($data = mysql_fetch_array($galerias)) {
				echo "<div class='note'>
					<img src='$data[contem]' alt='' width='300' height='180'/>
				</div>"; 
			 }
			
			?>
				
     </div>
     </div>
  </div>
 
  
<div class="divisionIzquierda"></div>
<div class="divisionCentro"></div>
<div class="divisionDerecha"></div>
     <?php
	  $sql = "select * from contenido where session='titulo contenido central 1' and idpagina=$pagina[idpagina]";
	  $tcs1 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido central 1' and idpagina=$pagina[idpagina]";
	  $conts1 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='titulo contenido central 2' and idpagina=$pagina[idpagina]";
	  $tcs2 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido central 2' and idpagina=$pagina[idpagina]";
	  $conts2 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='titulo contenido central 3' and idpagina=$pagina[idpagina]";
	  $tcs3 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido central 3' and idpagina=$pagina[idpagina]";
	  $conts3 = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='contenido lateral' and idpagina=$pagina[idpagina]";
	  $contl = $db->arrayConsulta($sql);
      $sql = "select *from contenido where session='imagen lateral' and idpagina=$pagina[idpagina]";
   	  $img1 = $db->arrayConsulta($sql);
	 ?> 

<?php if (trim($conts1['contem']) != "" || trim($conts2['contem']) != "" || trim($conts3['contem']) != "") { ?> 
 
<div class="contemIntro">
  <div class="titleIizq"><?php echo $tcs1['contem'];?></div>
  <div class="titleIcent"><?php echo $tcs2['contem'];?></div>
  <div class="titleIder"><?php echo $tcs3['contem'];?></div>
  <div class="introIzquierda"><div class="dataIConten"><?php echo $conts1['contem'];?></div></div>
  <div class="introCentro"><div class="dataIConten2"><?php echo $conts2['contem'];?></div></div>
  <div class="introDerecha"><div class="dataIConten"><?php echo $conts3['contem'];?></div></div>
</div> 
<?php } ?>

<?php if (trim($contl['contem']) != "" ) { ?>
<div class="introSecundario">
<div class="introInterior"> 
<img src="<?php echo $img1['contem'];?>"  style="float:left;margin:5px 15px 8px 0;"  width="60" height="50"/>
<?php echo $contl['contem'];?></div>
</div>
<?php } ?>

    <?php
	  $sql = "select * from contenido where session='titulo contenido principal' and idpagina=$pagina[idpagina]";
	  $tc = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido principal' and idpagina=$pagina[idpagina]";
	  $cont = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo contenido secundario' and idpagina=$pagina[idpagina]";
	  $tcs = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido secundario' and idpagina=$pagina[idpagina]";
	  $conts = $db->arrayConsulta($sql);
	  $sql = "select *from contenido where session='imagen contenido 1' and idpagina=$pagina[idpagina]";
	  $img1 = $db->arrayConsulta($sql);
	  $sql = "select *from contenido where session='imagen contenido 2' and idpagina=$pagina[idpagina]";
	  $img2 = $db->arrayConsulta($sql);
	?>
    
<?php if (trim($cont['contem']) != "") {?>    
<div class="contemData">
  <div class="contemDataTitle"><?php echo $tc['contem'];?></div>
  <div class="contemDataConten"><p><img src="<?php echo $img1['contem'];?>" style="float:left;margin:5px 15px 8px 0;" align="left"/>
  <?php echo $cont['contem'];?></p></div>
  <div class="contemDataFoter"></div>
</div>
<?php }?>

<?php if (trim($conts['contem']) != "") {?>
<div class="contemData">
  <div class="contemDataTitle"><?php echo $tcs['contem'];?></div>
  <div class="contemDataConten"><p><img src="<?php echo $img2['contem'];?>" style="float:left;margin:5px 15px 8px 0;" align="left"/> 
  <?php echo $conts['contem'];?></p></div>
  <div class="contemDataFoter"></div>
</div>
<?php }?>
</div>
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

<div class="pie">
<div class="acoplePie">
<div class="titlepieSub1"><?php echo $tp1['contem'];?></div>
<div class="pieSub1"><?php echo $cont1['contem'];?></div>
<div class="titlepieSub2"><?php echo $tp2['contem'];?></div>
<div class="pieSub2"><?php echo $cont2['contem'];?></div>
<div class="titlepieSub3"><?php echo $tp3['contem'];?></div>
<div class="pieSub3"><?php echo $cont3['contem'];?></div>


<div class="subDivision"></div>
<div class="author"><?php echo $at['contem'];?></div>
</div>

</div>
</div>



</body>
</html>