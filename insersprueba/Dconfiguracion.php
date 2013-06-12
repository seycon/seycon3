<?php 
    include('../conexion.php');
    session_start();
    $db = new MySQL(); 
  
    if ($_GET['transaccion'] == "insertar") { 
        $sql = "delete from configuracionrestaurante"; 
        $db->consulta($sql); 
        $sql = "delete from configuracionsucursal"; 
        $db->consulta($sql);
        $sql = "INSERT INTO configuracionrestaurante(idconfiguracion,guardiam1,guardiam2"
            .",guardiam3,ayudantem1,ayudantem2,ayudantem3,"
            ."garzonm1,garzonm2,garzonm3,pantallapu,impresionpu,pantallapt,impresionpt,"
            ."pantallatv,impresiontv,cuentasocio) VALUES "
            ."(NULL,'$_GET[guardiam1]','$_GET[guardiam2]','$_GET[guardiam3]','$_GET[ayudantem1]','$_GET[ayudantem2]'"
		    .",'$_GET[ayudantem3]','$_GET[garzonm1]','$_GET[garzonm2]','$_GET[garzonm3]','$_GET[pantallapu]'"
		    .",'$_GET[impresionpu]','$_GET[pantallapt]'"
		    .",'$_GET[impresionpt]','$_GET[pantallatv]','$_GET[impresiontv]','$_GET[cuentasocio]');";
        $db->consulta($sql);  
        $datos =  json_decode(stripcslashes($_GET['detalle']));		
	    for ($i = 0; $i < count($datos); $i++) {
  		    $fila = $datos[$i];
		    $sql = "insert into configuracionsucursal(idconfiguracionsucursal, idsucursal, cuenta) ";
		    $sql .= "values(null, '$fila[0]', '$fila[1]')";
		    $db->consulta($sql);
        }	
 
		$sql = "delete from socio;";
		$db->consulta($sql);
		$datos =  json_decode(stripcslashes($_GET['socios']));		
		for ($i = 0; $i < count($datos); $i++){
	        $fila = $datos[$i];
			$sql = "insert into socio(idsocio, idtrabajador) ";
			$sql .= "values(null, '$fila[0]')";
			$db->consulta($sql);
		}
	    
		$sql = "delete from descuento;";
		$db->consulta($sql);
		$datos =  json_decode(stripcslashes($_GET['descuentos']));		
		for ($i = 0; $i < count($datos); $i++){
		    $fila = $datos[$i];
			$sql = "insert into descuento(iddescuento, idcombinacion, porcentaje) ";
			$sql .= "values(null, '$fila[0]','$fila[1]')";
			$db->consulta($sql);
		 }	 	    		
		
		$sql = "delete from turnorestaurante"; 
		$db->consulta($sql); 
		$sql = "insert into turnorestaurante values(null,'AM','$_GET[amdesde]','$_GET[amhasta]')";
		$db->consulta($sql);
		$sql = "insert into turnorestaurante values(null,'PM','$_GET[pmdesde]','$_GET[pmhasta]')";
		$db->consulta($sql);  
  } 
?>