<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include("../conexion.php");
	$db = new MySQL();
	$tipo = $_GET['transaccion'];
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	
	if ($tipo == "consulta") {
		$sql = sprintf("select login from usuario where estado=1 and 
		 login=%s COLLATE utf8_bin;",filtroSeguridad($_GET['login'], "text"));
		$num = $db->getnumRow($sql);
		if ($num > 0)
		 echo "si";
		else
		 echo "no";
		exit(); 
	}


	function filtroSeguridad($valor, $tipo)
	{
		if (PHP_VERSION < 6) {
		  $valor = get_magic_quotes_gpc() ? stripslashes($valor) : $valor;
		}
		$valor = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($valor) : mysql_escape_string($valor);
		switch ($tipo) {
		  case "text":
			$valor = ($valor != "") ? "'" . $valor . "'" : "''";
			break;        
		}
		return $valor;
	}

?>