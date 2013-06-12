<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
   session_start();
   include('../conexion.php');
   $db = new MySQL();
   
   // Las 2 lineas anteriores se deben remplazar con su conexion con la BD
   $sql = "";
   $filtro = trim($_GET['filtro']);
   $sql = $_GET['sql'];
	if ($sql == "") {
		$sql = "select ".$_GET['idtabla'].",".$_GET['campo']." from ".$_GET['tabla']." where "
		.$_GET['campo']." like '".$filtro."%' limit 9";
	} else {
		if ($filtro == "<sinfiltro>") {
			$sql = $sql;
		} else { 
			$sql = $sql." ".$_GET['campo']." like '".$filtro."%' limit 9";
		}
		$sql = str_replace('\\','',$sql);
	}
	$consulta = mysql_query($sql);
	$campo = $_GET['campo'];
	$divR = $_GET['divResultado'];
	$divID = $_GET['divResultado']."AC";
	$textEntrada = $_GET['textEntrada'];
	$hiddenResul = $_GET['HDresult'];
	$i = mysql_num_rows($consulta);
	echo $i."---";
	$i = 1;
  
	while($dato = mysql_fetch_array($consulta)) {
	  $idDiv = $divID.$i;
	  $idHiden = $idDiv."HD";
	  $idtabla = $dato[$_GET['idtabla']];
	  $textDato = $dato[$campo];
	  echo "<div id='$idDiv' class='divmenu' onmouseover='mouseSobreLista(this.id,&quot;$divR&quot;)'
	   onclick='selecciono(this.id,&quot;$textEntrada&quot;,&quot;$divR&quot;,&quot;$textDato&quot;,&quot;$hiddenResul&quot;)'>
	  &nbsp;&nbsp;".ucfirst(strtolower($textDato))." 
	  <input name='$idHiden' id='$idHiden' type='hidden' value='$idtabla'></div>";
	  $i = $i + 1;
	}
?>