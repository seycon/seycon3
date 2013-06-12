<?php 
    include("admin/conexion.php");
	$db = new MySQL();
    $sql = "select * from pagina where idplantilla=1 and numero=6;";  
    $pagina = $db->arrayConsulta($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagina['titulo']; ?></title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $pagina['estilo'];?>" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.js" type="text/javascript"></script>
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
	  $sql = "select * from contenido where session='titulo contenido principal' and idpagina=$pagina[idpagina]";
	  $tc = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido principal' and idpagina=$pagina[idpagina]";
	  $cont = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo contenido secundario' and idpagina=$pagina[idpagina]";
	  $tcs = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido secundario' and idpagina=$pagina[idpagina]";
	  $conts = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo contenido lateral' and idpagina=$pagina[idpagina]";
	  $tcl = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido lateral' and idpagina=$pagina[idpagina]";
	  $contl = $db->arrayConsulta($sql);
      $sql = "select *from contenido where session='imagen contenido 1' and idpagina=$pagina[idpagina]";
	  $img1 = $db->arrayConsulta($sql);
	  $sql = "select *from contenido where session='imagen contenido 2' and idpagina=$pagina[idpagina]";
	  $img2 = $db->arrayConsulta($sql);
 ?>


  <div class="contemDataTitle"><?php echo $tc['contem'];?></div>
  <div class="contemDataConten"><p><?php if ( $img1['contem'] != "" ) {?>
  <img src="<?php echo $img1['contem'];?>" style="float:left;margin:5px 15px 8px 0;" width="200" height="200"/>
  <?php }?>
   <?php echo $cont['contem'];?></span> </p></div>
  <div class="contemDataFoter"></div>
</div>
<div class="contemData">
  <div class="contemDataTitle"><?php echo $tcs['contem'];?></div>
  <div class="contemDataConten"><p>
  <?php if ( $img2['contem'] != "" ) {?>
  <img src="<?php echo $img2['contem'];?>" style="float:left;margin:5px 15px 8px 0;" width="200" height="200"/>
  <?php }?>
   <?php echo $conts['contem'];?> </p></div>
  <div class="contemDataFoter"></div>
</div>

</div>



<div class="cabeaviso1"><div class="tituloAviso"><?php echo $tcl['contem'];?></div></div>
<div class="avisos1">
  <div class="contenAvisos"><?php echo $contl['contem'];?></div>
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
</div>


</div>

</div>
</body>
</html>