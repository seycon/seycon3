<?php 
    include("admin/conexion.php");
	$db = new MySQL();
    $sql = "select * from pagina where idplantilla=2 and numero=5;";  
    $pagina = $db->arrayConsulta($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $pagina['estilo'];?>" />
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
<div style="position:relative;width:100%;">
<?php
    $sql = "select * from contenido where session='titulo principal' and idpagina=$pagina[idpagina]";
	$tp = $db->arrayConsulta($sql);
    $sql = "select * from contenido where session='titulo contenido presentacion' and idpagina=$pagina[idpagina]";
	$tc = $db->arrayConsulta($sql);
	$sql = "select * from contenido where session='contenido presentacion' and idpagina=$pagina[idpagina]";
	$cont = $db->arrayConsulta($sql);
    $sql = "select *from contenido where session='imagen lateral' and idpagina=$pagina[idpagina]";
	$img1 = $db->arrayConsulta($sql);
	$sql = "select * from contenido where session='contenido lateral' and idpagina=$pagina[idpagina]";
	$contl = $db->arrayConsulta($sql);
?>


<div class="headers">
<div class="franja"></div>

<div class="acopleCabecera">
  <div class="titlePrincipal"><?php echo $tp['contem'];?></div>
   <div class="franjaCierre"></div>
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
</div>

<div class="acopleContenido">
<div class="intro">
<div class="introTitulo"><?php echo $tc['contem'];?></div>
<?php if (trim($cont['contem']) != "") {?>
<div class="introConten"><?php echo $cont['contem'];?></div>
<?php }?>
</div>


<?php if (trim($contl['contem']) != "") {?>
<div class="introSecundario">
<div class="introInterior"> 
<img src="<?php echo $img1['contem'];?>"  style="float:left;margin:5px 15px 8px 0;"  width="60" height="50"/>
<?php echo $contl['contem'];?></div>
</div>
<?php }?>


<div class="galeri">
 <div id="header"><div class="wrap">
   <div id="slide-holder">


<div id="slide-runner">


            <?php
			 $sql = "select *from contenido where idpagina=$pagina[idpagina] and (
			  session='imagen galeria 1' or session='imagen galeria 2' 
			  or session='imagen galeria 3' or session='imagen galeria 4' or session='imagen galeria 5') ";
	         $galerias = $db->consulta($sql);
			 $i = 1;
			 while ($data = mysql_fetch_array($galerias)) {
				echo "<img id='slide-img-$i' src='$data[contem]' class='slide' alt='' />"; 
				$i++;
			 }			  
			
			 $data = array();
			 $sql = "select * from contenido where session='descripcion imagen 1' and idpagina=$pagina[idpagina]";
			 $desc1 = $db->arrayConsulta($sql);
			 $data[0] = $desc1['contem'];
			 $sql = "select * from contenido where session='descripcion imagen 2' and idpagina=$pagina[idpagina]";
			 $desc2 = $db->arrayConsulta($sql);
			 $data[1] = $desc2['contem'];
			 $sql = "select * from contenido where session='descripcion imagen 3' and idpagina=$pagina[idpagina]";
			 $desc3 = $db->arrayConsulta($sql);
			 $data[2] = $desc3['contem'];
			 $sql = "select * from contenido where session='descripcion imagen 4' and idpagina=$pagina[idpagina]";
			 $desc4 = $db->arrayConsulta($sql);
			 $data[3] = $desc4['contem'];
			 $sql = "select * from contenido where session='descripcion imagen 5' and idpagina=$pagina[idpagina]";
			 $desc5 = $db->arrayConsulta($sql);
			 $data[4] = $desc5['contem'];
			?>

    <div id="slide-controls">
     <p id="slide-client" class="text"><span></span></p>
     <p id="slide-desc" class="text"></p>
     <p id="slide-nav"></p>
    </div>
</div>
	
   </div>
   <script type="text/javascript">
    if(!window.slider) var 
	slider={};
	slider.data=[<?php
	for ($j = 1; $j < $i; $j++) {
	 $img = $j-1;	
	 if ($j == ($i-1)) 
	 echo "{'id':'slide-img-$j','client':'$data[$img]','desc':''}";
	 else	
	 echo "{'id':'slide-img-$j','client':'$data[$img]','desc':''},";}?>	
	 ];
   </script>
  </div></div>
</div>

<div class="contenGeneral">
	 <?php
      $sql = "select * from contenido where session='titulo contenido principal' and idpagina=$pagina[idpagina]";
	  $tcp = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido principal' and idpagina=$pagina[idpagina]";
	  $contp = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo contenido secundario' and idpagina=$pagina[idpagina]";
	  $tcs = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido secundario' and idpagina=$pagina[idpagina]";
	  $conts = $db->arrayConsulta($sql);	
	  $sql = "select *from contenido where session='imagen contenido 1' and idpagina=$pagina[idpagina]";
	  $img1 = $db->arrayConsulta($sql);
	  $sql = "select *from contenido where session='imagen contenido 2' and idpagina=$pagina[idpagina]";
	  $img2 = $db->arrayConsulta($sql);  
	 ?>


<?php if (trim($contp['contem']) != "") {?>
 <div class="contenData">
   <div class="contenDataTitulo"><?php echo $tcp['contem'];?></div>
   <div class="contenDataConten">
   <img src="<?php echo $img1['contem'];?>"  style="float:left;margin:5px 15px 8px 0;"  width="200" height="200"/>
   <?php echo $contp['contem'];?></div>
   <div class="contenDataEspace"></div>
 </div>
<?php }?>

<?php if (trim($conts['contem']) != "") {?>
 <div class="contenData">
   <div class="contenDataTitulo"><?php echo $tcs['contem'];?></div>
   <div class="contenDataConten">
   <img src="<?php echo $img2['contem'];?>"  style="float:left;margin:5px 15px 8px 0;"  width="200" height="200"/>
   <?php echo $conts['contem'];?></div>
   <div class="contenDataEspace"></div>
 </div>
<?php }?>



</div>
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