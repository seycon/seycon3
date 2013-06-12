<?php
	session_start();
	include('../conexion.php');
	include("../aumentaComa.php");
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	   header("Location: ../index.php");	
	}	 
	
	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena));
	}	 
	
	function filtro2($cadena)
	{
	    return htmlspecialchars(addslashes(strip_tags($cadena)));
	}	 
	 
    if ($_GET['transaccion'] == "insertar") { 
	    $sqlinforme = "insert into informegasto(montorendicion,nrodocumentos,fecha
		,privado,comentario,firmadigital,idusuario,estado)";
        $fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
	    $sqlinforme .= "values('".filtro($_GET['montorendicion'])."','"
		.filtro($_GET['nrodocumentos'])."','$fecha',
		".filtro($_GET['privado']).",'".filtro($_GET['comentario'])."',"
		.filtro($_GET['firma']).",'$_SESSION[id_usuario]',1)";		
		 $db->consulta($sqlinforme);
		$id = $db->getMaxCampo('idinformegasto', 'informegasto');
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));		 
		$datos =  json_decode(stripcslashes($_GET['detalle']));		
		for ($i = 0; $i < count($datos); $i++) {
		   $fila = $datos[$i];
		   $fecha = filtro($db->GetFormatofecha($fila[0],'/'));
		   $sql = "insert into detalleinforme(idinformegasto,fecha,detalle,documento,importe) "
		   ."values('$id', '$fecha', '".filtro2($fila[1])."','".filtro2($fila[2])."','"
		   .filtro2(desconvertir($fila[3]))."')";			 
		   $db->consulta($sql);
		}			 
		echo $id;
		exit();
    }
	
	if ($_GET['transaccion'] == "modificar") { 
	    $idinforme = filtro($_GET['idinforme']);
        $fecha = date("Y-m-d", strtotime(fechaAMD($_GET['fecha'])));
		 $sql = "update informegasto set firmadigital=".filtro($_GET['firma'])
		 .",montorendicion='".filtro($_GET['montorendicion'])."'
		 ,nrodocumentos='".filtro($_GET['nrodocumentos'])
		 ."',fecha='$fecha',privado=".filtro($_GET['privado']).",
		 comentario='".filtro($_GET['comentario'])
		 ."',idusuario='$_SESSION[id_usuario]' where idinformegasto=$idinforme";	
		 $db->consulta($sql);
		 
		 $sql = "delete from detalleinforme where idinformegasto=$idinforme";
		 $db->consulta($sql);
		 $_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));		 
		 $datos =  json_decode(stripcslashes($_GET['detalle']));	
		 for ($i = 0; $i < count($datos); $i++) {
  		     $fila = $datos[$i];
			 $fecha = filtro($db->GetFormatofecha($fila[0],'/'));
			 $sql = "insert into detalleinforme(idinformegasto,fecha,detalle,documento,importe) "
			 ."values('$idinforme', '$fecha', '".filtro2($fila[1])."','"
			 .filtro2($fila[2])."','".filtro2(desconvertir($fila[3]))."')";
			 $db->consulta($sql);
	     }			 
		 echo $idinforme;
		 exit();
    }	
   
?>