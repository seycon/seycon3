<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
  include('../conexion.php');
  session_start();
  if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: ../index.php");	
  }
  
  function filtro($cadena)
  {
      return htmlspecialchars(strip_tags($cadena));
  }
  
  $db = new MySQL(); 
  $consulta = "select * from configuracioncontable;";
  $valores = $db->arrayConsulta($consulta);
  $detalles = array('detalle','detalleEgreso','detalleBajaActivo'
  ,'detallePorPagar','detallePorCobrar','detalleIngresoDinero','detalleEgresoDinero');
  $tipos = array('Ingreso','Egreso','Baja activo','Por Pagar'
  ,'Por Cobrar','Ingreso Dinero','Egreso Dinero');
 
  $transaccion = $_GET['tipo'];  
   
  if ($transaccion == "activo") {
     $idconfiguracion = $db->getMaxCampo("codigo","configuracioncontable");	  
  	 $sql = "delete from tipoconfiguracion where tipo='Baja activo'"; 
	 $db->consulta($sql); 	  
	 $datos =  json_decode(stripcslashes($_GET['detalle']));		
	 $tipo = 'Baja activo';
		 for ($i = 0; $i < count($datos); $i++) {			   
		     $fila = $datos[$i];
		     $descripcion = filtro($fila[0]);
		     $cuenta = filtro($fila[1]);
		     $sql = "insert into tipoconfiguracion(idconfiguracion, descripcion, cuenta,tipo) ";
		     $sql .= "values('$idconfiguracion', '$descripcion', '$cuenta','$tipo')";
		     $db->consulta($sql);			 
		 }	
	  exit();
  }

  if ($transaccion == "inventario") {
    $idconfiguracion = $db->getMaxCampo("codigo","configuracioncontable");	  
  	$sql = "delete from tipoconfiguracion where tipo='Ingreso' or tipo='Egreso'"; 
	$db->consulta($sql); 	  
	$detalles = array('detalleingreso','detalleegreso');
    $tipos = array('Ingreso','Egreso');
    for ($k = 0; $k < count($detalles); $k++) {	  
	    $datos =  json_decode(stripcslashes($_GET[$detalles[$k]]));		
	    $tipo = $tipos[$k];
		 for ($i = 0; $i < count($datos); $i++) {			   
			 $fila = $datos[$i];
			 $descripcion = filtro($fila[0]);
			 $cuenta = filtro($fila[1]);
			 $sql = "insert into tipoconfiguracion(idconfiguracion, descripcion, cuenta,tipo) ";
			 $sql .= "values('$idconfiguracion', '$descripcion', '$cuenta','$tipo')";
			 $db->consulta($sql);			 
		  }	
	}
	$sql = "update configuracioncontable set anticipoproveedor='".filtro($_GET['anticipoproveedor'])
	."',proveedorpagar='".filtro($_GET['proveedorpagar'])."',
	creditofiscal='".filtro($_GET['creditofiscal'])."',porcentajecreditofiscal='"
	.filtro($_GET['porcentajeCF'])."',costooperativo='"
	.filtro($_GET['costooperativo'])."'";
    $db->consulta($sql);
	exit();
  }
 
  if ($transaccion == "contabilidad") {
    $idconfiguracion = $db->getMaxCampo("codigo","configuracioncontable");	  
  	$sql = "delete from tipoconfiguracion where tipo='Ingreso Dinero' or tipo='Egreso Dinero'"; 
	$db->consulta($sql); 	  
	$detalles = array('detalleIngresoDinero','detalleEgresoDinero');
    $tipos = array('Ingreso Dinero','Egreso Dinero');
    for ($k = 0; $k < count($detalles); $k++) {	  
	   $datos =  json_decode(stripcslashes($_GET[$detalles[$k]]));		
	   $tipo = $tipos[$k];
	   for ($i = 0; $i < count($datos); $i++) {			   
		   $fila = $datos[$i];
		   $descripcion = filtro($fila[0]);
		   $cuenta = filtro($fila[1]);
		   $sql = "insert into tipoconfiguracion(idconfiguracion, descripcion, cuenta,tipo) ";
		   $sql .= "values('$idconfiguracion', '$descripcion', '$cuenta','$tipo')";
		   $db->consulta($sql);			 
		}	
	} 
	exit();  
  }

  if ($transaccion == "recursos") {
	  $sql = "update configuracioncontable set anticiposueldo='".filtro($_GET['anticiposueldo'])
	  ."',recargoinformar='".filtro($_GET['recargoinformar'])."',
	  sueldossalarios='".filtro($_GET['sueldossalarios'])."',bonoantiguedad='"
	  .filtro($_GET['bonoantiguedad'])."',bonoproduccion='".filtro($_GET['bonoproduccion'])."',
	  horasextras='".filtro($_GET['horasextras'])."',otrosbonos='"
	  .filtro($_GET['otrosbonos'])."',salariospagar='".filtro($_GET['salariospagar'])
	  ."',aporteretenciones='".filtro($_GET['aporteretenciones'])."',seguromedico='"
	  .filtro($_GET['seguromedico'])."',aportepatronal='".filtro($_GET['aportepatronal'])
	  ."',aportelaboral='".filtro($_GET['aportelaboral'])."',aguinaldoporpagar='"
	  .filtro($_GET['aguinaldoporpagar'])."',aguinaldo='".filtro($_GET['aguinaldo'])."'";
	  $db->consulta($sql);
	  exit();	
  }
  
  
  if ($transaccion == "ventas") {
      $idconfiguracion = $db->getMaxCampo("codigo", "configuracioncontable");
	  $sql = "delete from tipoconfiguracion where tipo='Por Pagar' or tipo='Por Cobrar'
	   or tipo='Por Pagar Gasto' or tipo='Por Cobrar Apertura'"; 
	  $db->consulta($sql); 	  
      $detalles = array('detallePorPagar','detallePorCobrar','detallePasivoPorCobrar');
      $tipos = array('Por Pagar','Por Cobrar','Por Cobrar Apertura');
	  for ($k = 0; $k < count($detalles); $k++) {	  
	       $datos =  json_decode(stripcslashes($_GET[$detalles[$k]]));		
	       $tipo = $tipos[$k];
		   for ($i = 0; $i < count($datos); $i++) {			   
  		     $fila = $datos[$i];
			 $descripcion = filtro($fila[0]);
 			 $cuenta = filtro($fila[1]);
			 $sql = "insert into tipoconfiguracion(idconfiguracion, descripcion, cuenta,tipo) ";
			 $sql .= "values('$idconfiguracion', '$descripcion', '$cuenta','$tipo')";
			 $db->consulta($sql); 
			 if ($k == 0) {
				$cuenta = filtro($fila[2]); 
				$sql = "insert into tipoconfiguracion(idconfiguracion, descripcion, cuenta,tipo) ";
			    $sql .= "values('$idconfiguracion', '$descripcion', '$cuenta','Por Pagar Gasto')"; 
				$db->consulta($sql); 
			 }			 
	      }	
	   }
	   $sql = "update configuracioncontable set itgastos='"
	   .filtro($_GET['itgastos'])."',itpasivo='".filtro($_GET['itpasivo'])."',costoventa='"
	   .filtro($_GET['costoventa'])."',inventario='".filtro($_GET['inventario'])
	   ."',porcentajedebitofiscal='".filtro($_GET['porcentajeDF'])
	   ."',porcentajeitgastos='".filtro($_GET['porcentajeITG'])
	   ."',porcentajeitpasivo='".filtro($_GET['porcentajeITP'])
	   ."',cuentalcv='".filtro($_GET['libroCV'])."',cuentalcvproductos='"
	   .filtro($_GET['libroCVproducto'])."',
	   descuentoventa='".filtro($_GET['descuentoventa'])."',recargo='"
	   .filtro($_GET['recargo'])."',debitofiscal='".filtro($_GET['debitofiscal'])
	   ."',cajalibroCV='".filtro($_GET['cajalibroCV'])."',clientescobrar='"
	   .filtro($_GET['clientescobrar'])."',anticipocliente='"
	   .filtro($_GET['anticipocliente'])."',devolucion='".filtro($_GET['devolucion'])
	   ."',porcreditoporpagar='".filtro($_GET['porcreditoporpagar'])."',creditofiscalporpagar='"
	   .filtro($_GET['creditofiscalporpagar'])."'";
	   $db->consulta($sql);
	   exit();		
	}
 ?>