<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include("../conexion.php");
	$db = new MySQL();
	$tipo = $_GET['transaccion'];
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: ../index.php");	
	} 
	
	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	if ($tipo == "insertar") {	
		$sql = "insert into grupousuario(idgrupousuario,nombre,idusuario,estado)
		 values(null,'".filtro($_GET['nombre'])."','$_SESSION[id_usuario]',1)";
		$db->consulta($sql);
		$idgrupo = $db->getMaxCampo("idgrupousuario","grupousuario");
		$datos =  json_decode(stripcslashes($_GET['privilegios']));
		insertarGrupo($datos, $idgrupo, $db);
		echo "exito";
		exit();
	} 
	
	if ($tipo == "modificar") {	
		$sql = "update grupousuario set nombre='".filtro($_GET['nombre'])
		."',idusuario='$_SESSION[id_usuario]' where idgrupousuario=$_GET[idgrupo]";
		$db->consulta($sql); 
		$sql = "delete from detalleaccion where idgrupo=$_GET[idgrupo]";
		$db->consulta($sql);
		$datos =  json_decode(stripcslashes($_GET['privilegios']));
		insertarGrupo($datos, $_GET['idgrupo'], $db);
		echo "exito";
		exit();
	}  
	
	function insertarGrupo($privilegios, $idgrupo, $db) 
	{
	  for ($i = 0; $i < count($privilegios); $i++) {
		 $sql = "insert into detalleaccion(iddetalleatencion,idgrupo,idaccion)
		  values(null,$idgrupo,$privilegios[$i])";
		 $db->consulta($sql);	 
	  }
	}
?>