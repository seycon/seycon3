<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include("../conexion.php");
	include("../aumentaComa.php");
	$db = new MySQL();
	$tipo = $_GET['transaccion'];
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	if ($tipo == "insertar") {
	  $fecha = $db->GetFormatofecha($_GET['fecha'],'/');		
	  $sql = "insert into impuestos values(null,'$_GET[mes]','$fecha'
	  ,'$_GET[tipocambio]','$_SESSION[id_usuario]',1);";
	  $db->consulta($sql);  
	  $codigo = $db->getMaxCampo("idlibrodiario","librodiario");
	  echo $codigo;  	
	}
	
	if ($tipo == "modificar") {
	  $fecha = $db->GetFormatofecha($_GET['fecha'],'/');	
	  $codigo = $_GET['idimpuesto'];
	  $sql = "update impuestos set mes='$_GET[mes]',fechavencimiento='$fecha'
	  ,tipocambio='$_GET[tipocambio]',idusuario='$_SESSION[id_usuario]' where  
	  idimpuesto='$codigo';";
	  $db->consulta($sql);
	  echo $codigo;
	}

?>