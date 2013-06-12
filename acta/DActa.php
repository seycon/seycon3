<?php
    session_start();
    include_once('../conexion.php');  
    $db = new MySQL();
 
    if (!isset($_SESSION['softLogeoadmin'])) {
        header("Location: ../index.php");	
    }

    function filtro($cadena)
    {
        return htmlspecialchars(strip_tags($cadena));
    }

    if ( $_GET['transaccion'] == "insertar") {
	    $fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));  
	    $titulo = filtro($_GET['titulo']);
	    $sql = "INSERT INTO acta(idacta,horainicio,horacierre,fecha,privado,firmadigital,titulo"
		    .",agendareunion,asistentes,desarrolloreunion,idusuario,estado) VALUES (null,'"
	        .filtro($_GET['horainicio'])."','".filtro($_GET['horacierre'])."','$fecha','"
	        .filtro($_GET['privado'])."','".filtro($_GET['firma'])."','".$titulo."','"
	        .filtro($_GET['agendareunion'])."','".filtro($_GET['asistentes'])."','"
	        .filtro($_GET['desarrolloreunion'])."','$_SESSION[id_usuario]',1);";

	    $db->consulta($sql); 
	    $idacta = $db->getMaxCampo('idacta','acta');	
        exit();	 
    }

    if ($_GET['transaccion'] == "modificar") { 
        $fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
	    $idacta = filtro($_GET['idacta']);
        $sql = "UPDATE acta SET horainicio='".filtro($_GET['horainicio'])."', horacierre='"
		     .filtro($_GET['horacierre'])."', fecha='$fecha', privado='".filtro($_GET['privado'])
			 ."',titulo='".filtro($_GET['titulo'])."',agendareunion='".filtro($_GET['agendareunion'])
			 ."',asistentes='".filtro($_GET['asistentes'])."',desarrolloreunion='".filtro($_GET['desarrolloreunion'])
	         ."',idusuario=$_SESSION[id_usuario],firmadigital='".filtro($_GET['firma'])
	         ."'  WHERE idacta= '$idacta';";
	    $db->consulta($sql);	
	    exit();	 
    }

?>