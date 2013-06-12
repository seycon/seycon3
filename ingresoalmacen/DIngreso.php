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
	
	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}

	if ($tipo == "nit") {
	   $sql_nit = "SELECT nomrazonsociprove, numautorizacion, fechadeemision
			FROM librocomprasiva WHERE numdenitproveedor ='$_GET[nit]' order by fechadeemision desc limit 1;";	   
	   echo $db->getCampo('nomrazonsociprove',$sql_nit)."---".$db->getCampo('numautorizacion',$sql_nit);
	   exit();
	}

	if ($tipo == "referencia") {
	    $sql = "
		select d.iddetalleingreso from detalleegresoproducto d,egresoproducto i 
		  where i.idegresoprod=d.idegresoprod and i.estado=1 and d.iddetalleingreso=$_GET[iddetalle] union all
		select d.iddetalleingreso from detalletraspaso d,traspaso t where d.idtraspaso=t.idtraspaso 
		and d.iddetalleingreso=$_GET[iddetalle] and t.estado=1 union all
		select d.iddetalleingreso from detallenotaventa d,notaventa n where n.idnotaventa=d.idnotaventa 
		and d.iddetalleingreso=$_GET[iddetalle] and n.estado=1 union all 
		select d.iddetalleingreso from detallerequerimiento d,
		detalleatencion da where da.iddetalleatencion=d.iddetalleatencion  
		and d.iddetalleingreso=$_GET[iddetalle] and da.estado=1;
		;"; 
	   $num = $db->getnumRow($sql);
	   echo $num."---";  
	}

	if ($tipo == "referenciaMaestro") {
	   $idingreso = $_GET['idingreso']; 
	   $sql = "select d.iddetalleingreso from detalleegresoproducto d,egresoproducto i 
			  where i.idegresoprod=d.idegresoprod and i.estado=1 and d.iddetalleingreso in 
			  (select iddetalleingreso from detalleingresoproducto where idingresoprod=$idingreso) union all
			  select d.iddetalleingreso from detalletraspaso d,traspaso t where d.idtraspaso=t.idtraspaso 
			  and d.iddetalleingreso in (select iddetalleingreso from detalleingresoproducto where idingresoprod=$idingreso)
			  and t.estado=1 union all select d.iddetalleingreso from detallenotaventa d,notaventa n 
			  where n.idnotaventa=d.idnotaventa and d.iddetalleingreso in  
			  (select iddetalleingreso from detalleingresoproducto where idingresoprod=$idingreso) and n.estado=1;"; 
	   $num = $db->getnumRow($sql);
	   echo $num;  
	}

	if ($tipo == "consulta") {	
		$consulta = "select * from producto WHERE idproducto =".$_GET['codigo'];
		$respuesta = $db->arrayConsulta($consulta);
		$sql = "select  
	   (select sum(dp.cantidadactual) from detalleingresoproducto dp,ingresoproducto ip where 
	   dp.unidadmedida=p.unidaddemedida and dp.idproducto=p.idproducto 
	   and dp.idingresoprod=ip.idingresoprod and ip.idalmacen=ap.idalmacen and ip.estado=1 and dp.estado=1)as 'unidadM',    
	   (select sum(dp.cantidadactual) from detalleingresoproducto dp,ingresoproducto ip where  
	   dp.unidadmedida=p.unidadalternativa and dp.idproducto=p.idproducto 
	   and dp.idingresoprod=ip.idingresoprod and ip.idalmacen=ap.idalmacen and ip.estado=1 and dp.estado=1)as 'unidadA'  
	   from producto p,almacen ap where ap.idalmacen=$_GET[idalmacen]  and p.idproducto=".$_GET['codigo'];
		$Ccantidad = $db->arrayConsulta($sql);
		echo $respuesta['unidaddemedida']."---";
		echo $respuesta['unidadalternativa']."---";
		echo $respuesta['conversiones']."---";
		echo $respuesta['costo']."---";
		echo $Ccantidad['unidadM']."---";
		echo $Ccantidad['unidadA']."---";
		exit();
	}

	if ($tipo == "insertar") {
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		$fechavenc = $db->GetFormatofecha($_GET['fvencimiento'],"/");
		$nroI = $db->getNextID('nroingresoprod',"ingresoproducto");
		
		if ($_GET['tipoingreso'] == "NotaVentaProducto") {
		  $sql = "update notaventa set montoactualcredito=montoactualcredito+"
		  .$_GET['monto']." where numero=".$_GET['facproveedor']." and tiponota='productos';";	
		  $db->consulta($sql);
		}
		
		
		$sql = "insert into ingresoproducto values(null,$nroI,'".filtro($_GET['tipoingreso'])
		."','$fecha','".filtro($_GET['moneda'])."','".
		filtro($_GET['almacen'])."','".filtro($_GET['idpersonarecibida'])."',
		'".filtro($_GET['nombreasignado'])."','".filtro($_GET['monto'])."','"
		.filtro($_GET['facproveedor'])."','".filtro($_GET['credito'])."','".filtro($_GET['glosa'])."','".
		filtro($_GET['efectivo'])."','$fechavenc','".filtro($_GET['costooperativo'])
		."','".filtro($_GET['caja'])."','".filtro($_GET['cuentacontable'])."','".
		filtro($_GET['receptor'])."',$_SESSION[id_usuario],'I',0,'".$_GET['tc']."',0,".$_GET['devolucion'].",1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idingresoprod", "ingresoproducto");		
		$factura = json_decode(stripcslashes($_GET['factura']),true);  
		
		if ($factura['dia'] != "") {
		    insertarLibroCompras($factura,$fecha,$codigo,$_GET['almacen'],$db);	
		}	
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = $fila[0];
			$lote = $fila[1];
			$fechaD = $db->GetFormatofecha($fila[2],"/");
			$cantidad = round($fila[3],4);
			$um = $fila[4];
			$precio = round(desconvertir($fila[5]),4);
			$total = round(desconvertir($fila[6]),4);
			$consulta = "insert into detalleingresoproducto 
			values(null,'$id','$codigo','$fechaD','$lote','$cantidad'
			,'$cantidad','$um','$precio','$total',1);";
			$db->consulta($consulta);				
		}
		  
		$sql = "select ialmimprimir,ialmhablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);  
		if ($dato['ialmhablibrodiario'] == "1") {
		  $caso = obtenerCasoLibro();
		  insertarLibro($_GET['almacen'],$_GET['moneda'],$fecha,$codigo,$_GET['tc']
		  ,$_SESSION['id_usuario'],$_GET['monto'],$db,$_GET['caja'],$caso
		  ,$_GET['efectivo'],$_GET['credito'],$_GET['nombreasignado']
		  ,$_GET['cuentacontable'],$_GET['tipoingreso'],$_GET['glosa'],$_GET['nombreasignado']);
		}
		  
		echo $codigo."---";  
		echo $dato['ialmimprimir']."---";
		exit();  
	}


	function insertarLibroCompras($datoLibro, $fecha, $codigo, $almacen, $db)
	{
		$sql = "select *from almacen where idalmacen=$almacen";
		$sucursal = $db->arrayConsulta($sql);
		$fechas = explode("/",$fecha);
		$fechaLibro = $fechas[0]."-".$fechas[1]."-".$datoLibro['dia'];
		$sqlCompras = "insert into librocomprasiva(folio,fechadeemision,numdenitproveedor
		,nomrazonsociprove,numfacturaopoliza,numautorizacion,codigodecontrol,
				totalfactura,totalice,importeexcento,importeneto,creditofiscal
				,idtransaccion,transaccion,tipo,tipolibro,idcuenta,idusuario,estado)
				values ($sucursal[sucursal],'$fechaLibro','$datoLibro[nit]'
				,'$datoLibro[razonsocial]','$datoLibro[numfactura]','$datoLibro[numeroautorizacion]'
				, '$datoLibro[codigocontrol]','$datoLibro[importetotal]'
				,'$datoLibro[ice]','$datoLibro[excento]','$datoLibro[neto]'
				,'$datoLibro[iva]',$codigo,'Ingreso Almacen',
				'IA','0','0','$_SESSION[id_usuario]',1)";
	   $db->consulta($sqlCompras);
	}

	function obtenerCasoLibro() 
	{
	  $efect = ($_GET['efectivo']=="") ? 0 : $_GET['efectivo'];
	  $credit = ($_GET['credito'] == "")? 0 : $_GET['credito'];	
	  $_GET['efectivo'] = ($_GET['efectivo'] == "") ? 0 : $_GET['efectivo'];
	  $_GET['credito'] = ($_GET['credito'] == "") ? 0 : $_GET['credito'];
		
	  if ($_GET['efectivo'] <= 0 && $_GET['credito'] > 0 && $_GET['cuentacontable'] == "" && $_GET['caja'] == ""){
		return 7;  
	  }
	  if ($_GET['efectivo'] > 0 && $_GET['credito'] <= 0){
		return 1;  
	  }
	  if ($_GET['efectivo'] > 0 && $_GET['credito'] > 0  && $_GET['caja'] != ""){
		return 2;  
	  }
	  if ($_GET['efectivo'] <= 0 && $_GET['credito'] <= 0 && $_GET['caja'] == ""){
		return 3;  
	  }
	  if ($_GET['efectivo'] > 0 && $_GET['credito'] > 0 && $_GET['caja'] != "" && $_GET['cuentacontable'] != ""){
		return 4;  
	  }
	  if ($_GET['efectivo'] <= 0 && $_GET['credito'] > 0 && $_GET['caja'] == "" && $_GET['cuentacontable'] != ""){
		return 5;  
	  }
 
	}

	function insertarLibro($almacen, $moneda, $fecha, $codigo, $tc, $usuario
	, $monto, $db, $cuentacaja, $caso, $efectivo, $credito, $proveedor, $cuentacontable, $tipo, $glosa, $asignado)
	{
		$sql = "select max(l.numero)+1 as 'num',a.sucursal,a.nombre 
		from librodiario l,almacen a where l.idsucursal=a.sucursal 
		and a.idalmacen=$almacen GROUP BY l.idsucursal;";  
		$num = $db->arrayConsulta($sql); 
		if (!isset($num['num'])){
		$sql = "select 1 as 'num',sucursal,nombre from almacen where idalmacen=$almacen";	
		$num = $db->arrayConsulta($sql);
		}	
			
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa
		,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','ingreso','$fecha','$glosa'
		,'$codigo','$tc','$usuario',1,'Ingreso Almacen');"; 
		$db->consulta($sql);
		$libro = $db->getMaxCampo("idlibrodiario","librodiario");	
		$nameProveedor = $proveedor;		
		
		$descripcionLibro = "Ingreso Almacen Nº $codigo/Asignado: $nameProveedor/Almacen: $num[nombre]";
		switch($caso) {
		  case 1:
		   caso1_todocaja($db, $monto, $cuentacaja, $tipo, $descripcionLibro, $libro);
		  break;	
		  case 2:
		   caso2_efectivoCredito($db,$monto,$efectivo,$credito,$cuentacaja,$proveedor,$tipo,$descripcionLibro,$libro);
		  break;
		  case 3:
		   caso3_cuentaContable($db, $monto, $cuentacontable, $tipo, $descripcionLibro, $libro);
		  break;
		  case 4:
		   caso4_cuentaContableEfectivo($db,$monto,$efectivo,$cuentacontable
		   ,$cuentacaja,$tipo,$descripcionLibro,$libro);
		  break;	
		  case 5:
		   caso5_cuentaContableCredito($db,$monto,$credito,$cuentacontable,$proveedor,$tipo,$descripcionLibro,$libro);
		  break; 
		  case 6:
		   caso6_efectivoCreditoContable($db,$monto,$efectivo,$credito,$cuentacontable
		   ,$cuentacaja,$proveedor,$tipo,$descripcionLibro,$libro);
		  break;
		  case 7:
		   caso7_credito($db, $monto, $proveedor, $tipo, $descripcionLibro, $libro);
		  break;
		}
		
	}

	function caso1_todocaja($db, $monto, $cuentacaja, $tipo, $descripcion, $libro)
	{
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);
		if ($tipo == "Factura"){
		  insertarFacturado($inventario,$libro,$monto,$db,$descripcion);
		}else{
		  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		  values($libro,'$inventario[inventario]','$descripcion',$monto,0,'')";
		  $db->consulta($sql);
		}
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion
		,debe,haber,documento) values($libro,'$cuentacaja','$descripcion',0,$monto,'')";
		$db->consulta($sql);
	}

	function caso7_credito($db, $monto, $proveedor, $tipo, $descripcion, $libro)
	{
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);
		if ($tipo == "Factura") {
		    insertarFacturado($inventario, $libro, $monto, $db, $descripcion);
		} else {
		    $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		    values($libro,'$inventario[inventario]','$descripcion',$monto,0,'')";
		    $db->consulta($sql);
		}
	
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro,'$inventario[proveedorpagar]','$descripcion',0,$monto,'')";
		$db->consulta($sql);
	}

	function caso2_efectivoCredito($db, $monto, $efectivo, $credito
	                                 , $cuentacaja, $proveedor, $tipo, $descripcion, $libro)
	{
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);
		if ($tipo == "Factura") {
		  insertarFacturado($inventario,$libro,$monto,$db,$descripcion);
		} else {
		  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		  values($libro,'$inventario[inventario]','$descripcion',$monto,0,'')";
		  $db->consulta($sql);
		}
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		 values($libro,'$cuentacaja','$descripcion',0,$efectivo,'')";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
		 values($libro,'$inventario[proveedorpagar]','$descripcion',0,$credito,'')";
		$db->consulta($sql);
	}

	function caso3_cuentaContable($db, $monto, $cuentacontable, $tipo, $descripcion, $libro)
	{
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);
		if ($tipo == "Factura") {
		    insertarFacturado($inventario, $libro, $monto, $db, $descripcion);
		} else {
		    $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		     values($libro,'$inventario[inventario]','$descripcion',$monto,0,'')";
		    $db->consulta($sql);
		}
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		 values($libro,'$cuentacontable','$descripcion',0,$monto,'')";
		$db->consulta($sql);
	}


	function caso4_cuentaContableEfectivo($db, $monto, $efectivo, $cuentacontable
	                                       , $cuentacaja, $tipo, $descripcion, $libro)
	{
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);
		if ($tipo == "Factura") {
		    insertarFacturado($inventario, $libro, $monto, $db, $descripcion);
		} else {
		    $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		     values($libro,'$inventario[inventario]','$descripcion',$monto,0,'')";
		    $db->consulta($sql);
		}
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		 values($libro,'$cuentacaja','$descripcion',0,$efectivo,'')";
		$db->consulta($sql);
		$montorestante = $monto - $efectivo;
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		 values($libro,'$cuentacontable','$descripcion',0,$montorestante,'')";
		$db->consulta($sql);
	}


	function caso5_cuentaContableCredito($db, $monto, $credito, $cuentacontable
	                                      , $proveedor, $tipo, $descripcion,$libro)
	{
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);
		if ($tipo == "Factura") {
		  insertarFacturado($inventario, $libro, $monto, $db, $descripcion);
		} else {
		  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		  values($libro,'$inventario[inventario]','$descripcion',$monto,0,'')";
		  $db->consulta($sql);
		}
	
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		 values($libro,'$inventario[proveedorpagar]','$descripcion',0,$credito,'')";
		$db->consulta($sql);
		$montorestante = $monto - $credito;
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro,'$cuentacontable','$descripcion',0,$montorestante,'')";
		$db->consulta($sql);
	}


	function insertarFacturado($inventario, $libro, $monto, $db, $descripcion)
	{
	   $porcentaje = 100 - $inventario['porcentajecreditofiscal'];
	   $montoalmacen = $monto * ($porcentaje/100);	
	   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
		  values($libro,'$inventario[inventario]','$descripcion',$montoalmacen,0,'')";
	   $db->consulta($sql);
	   $montocredito = $monto * ($inventario['porcentajecreditofiscal']/100);
	   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
	   ) values($libro,'$inventario[creditofiscal]','$descripcion',$montocredito,0,'')";
	   $db->consulta($sql);
	}

	function caso6_efectivoCreditoContable($db,$monto,$efectivo,$credito,$cuentacontable
	                                       ,$cuentacaja,$proveedor,$tipo,$descripcion,$libro) 
    {
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);
		if ($tipo == "Factura") {
		  insertarFacturado($inventario, $libro, $monto, $db, $descripcion);	
		} else {
		  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		  values($libro,'$inventario[inventario]','$descripcion',$monto,0,'')";
		  $db->consulta($sql);
		}
		$montorestante = $monto - ($credito+$efectivo);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro,'$cuentacontable','$descripcion',0,$montorestante,'')";
		$db->consulta($sql);	
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro,'$cuentacaja','$descripcion',0,$efectivo,'')";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro,'$inventario[proveedorpagar]','$descripcion',0,$credito,'')";
		$db->consulta($sql);
	}

	function modificarLibro($almacen, $moneda, $fecha, $codigo, $tc, $usuario, $monto, $db
	         ,$cuentacaja,$caso,$efectivo,$credito,$proveedor,$cuentacontable,$tipo,$glosa,$asignado)
    {
		$sql = "select idlibrodiario,idsucursal from librodiario where 
		transaccion='Ingreso Almacen' and idtransaccion=$codigo;";  
		$libro = $db->arrayConsulta($sql); 
		
		$sql = "select a.sucursal,a.nombre from almacen a where  a.idalmacen=$almacen ";  
		$num = $db->arrayConsulta($sql);  
		if ($libro['idsucursal'] != $num['sucursal']){
			$sql = "select max(l.numero)+1 as 'num',a.sucursal,a.nombre 
			from librodiario l,almacen a where l.idsucursal=a.sucursal 
			and a.idalmacen=$almacen GROUP BY l.idsucursal;";  
			$num = $db->arrayConsulta($sql);  	
			  if (!isset($num['num'])){
			   $sql = "select 1 as 'num',sucursal,nombre from almacen where idalmacen=$almacen";	
			   $num = $db->arrayConsulta($sql);
			  }
			  $update = "idsucursal='$num[sucursal]',numero=$num[num],";
		} else {
		  $update = "";	
		}
		
		
		$sql = "update librodiario set $update moneda='$moneda',fecha='$fecha'
		,tipocambio='$tc',idusuario='$usuario',glosa='$glosa'  
		where idlibrodiario=$libro[idlibrodiario];"; 
		$db->consulta($sql);
		$sql = "delete from detallelibrodiario where idlibro=$libro[idlibrodiario]";
		$db->consulta($sql);
		
		$nameProveedor = $proveedor;		
		$descripcionLibro = "Ingreso Almacen Nº $codigo/Proveedor: $nameProveedor/Almacen: $num[nombre]";
		
		switch ($caso) {
		  case 1:
		   caso1_todocaja($db,$monto,$cuentacaja,$tipo,$descripcionLibro,$libro['idlibrodiario']);
		  break;	
		  case 2:
		   caso2_efectivoCredito($db,$monto,$efectivo,$credito,$cuentacaja
		   ,$proveedor,$tipo,$descripcionLibro,$libro['idlibrodiario']);
		  break;
		  case 3:
		   caso3_cuentaContable($db,$monto,$cuentacontable,$tipo,$descripcionLibro,$libro['idlibrodiario']);
		  break;
		  case 4:
		   caso4_cuentaContableEfectivo($db,$monto,$efectivo,$cuentacontable
		   ,$cuentacaja,$tipo,$descripcionLibro,$libro['idlibrodiario']);
		  break;	
		  case 5:
		   caso5_cuentaContableCredito($db,$monto,$credito,$cuentacontable
		   ,$proveedor,$tipo,$descripcionLibro,$libro['idlibrodiario']);
		  break; 
		  case 6:
		   caso6_efectivoCreditoContable($db,$monto,$efectivo,$credito,$cuentacontable,$cuentacaja,$proveedor,$tipo
		   ,$descripcionLibro,$libro['idlibrodiario']);
		  break;
		  case 7:
		   caso7_credito($db,$monto,$proveedor,$tipo,$descripcionLibro,$libro['idlibrodiario']);
		  break;
		}
	
	}


	if ($tipo == "modificar") {
	  $codigo = $_GET['idregistro'];
	  $fecha = $db->GetFormatofecha($_GET['fecha'],"/");
	  $fechavenc = $db->GetFormatofecha($_GET['fvencimiento'],"/");
	  
	  $sql = "select *from ingresoproducto where idingresoprod=".$_GET['idregistro'].";";
	  $datosIngreso = $db->arrayConsulta($sql);
	  
	  if ($datosIngreso['tipoingreso'] == "NotaVentaProducto"){
		  $sql = "update notaventa set montoactualcredito=montoactualcredito-"
		  .$datosIngreso['monto']." where numero=".$datosIngreso['facproveedor']
		  ." and tiponota='productos';";	
		  $db->consulta($sql);
		}
		
		if ($_GET['tipoingreso'] == "NotaVentaProducto"){
		  $sql = "update notaventa set montoactualcredito=montoactualcredito+"
		  .$_GET['monto']." where numero=".$_GET['facproveedor']." and tiponota='productos';";	
		  $db->consulta($sql);
		}	
	  
	  
	  $sql = "update ingresoproducto set tipoingreso='".filtro($_GET['tipoingreso'])
	  ."',fecha='$fecha',moneda='".filtro($_GET['moneda'])."',idalmacen='"
	  .filtro($_GET['almacen'])."',idpersonarecibida='".$_GET['idpersonarecibida']
	  ."',facproveedor='".$_GET['facproveedor']."',glosa='"
	  .$_GET['glosa']."',diascredito='".$_GET['credito']."',monto='".desconvertir($_GET['monto']).   
	  "',efectivo='".$_GET['efectivo']."',fechavencimiento='$fechavenc',devolucion="
	  .$_GET['devolucion'].",nombreasignado='".$_GET['nombreasignado']
	  ."',idusuario=$_SESSION[id_usuario],costooperativo='"
	  .$_GET['costooperativo']."',caja='".$_GET['caja']."',receptor='"
	  .$_GET['receptor']."',cuentacontable='".$_GET['cuentacontable']
	  ."' where idingresoprod=".$_GET['idregistro'].";";  	
	  $db->consulta($sql);
	
	  
	  $sql = "delete from librocomprasiva where idtransaccion=$codigo and transaccion='Ingreso Almacen';";
	  $db->consulta($sql);	  
	  $factura = json_decode(stripcslashes($_GET['factura']),true);  	
	  if ($factura['dia'] != "") {
		  insertarLibroCompras($factura,$fecha,$codigo,$_GET['almacen'],$db);	
	  }	 
	  
	  $sql = "update detalleingresoproducto set estado=0 where idingresoprod=$codigo";
	  $db->consulta($sql);
	  $datos =  json_decode(stripcslashes($_GET['detalle']));
				
	  for ($i = 0; $i < count($datos); $i++) {
		  $fila = $datos[$i];                 
		  $id = $fila[0];
		  $lote = $fila[1];
		  $fechaD = $db->GetFormatofecha($fila[2],"/");
		  $cantidad = round($fila[3],4);
		  $um = $fila[4];
		  $precio = round(desconvertir($fila[5]),4);
		  $total = round(desconvertir($fila[6]),4);
		  if ($fila[7] == "-2") {
			$consulta = "insert into detalleingresoproducto values(null,'$id','$codigo','$fechaD','$lote','$cantidad'
			,'$cantidad','$um','$precio','$total',1);";
		  } else {
			$consulta = "update detalleingresoproducto set estado=1 where iddetalleingreso=$fila[7];";	
		  }
		  $db->consulta($consulta);					
	  }	
		
	  $sql = "select ialmimprimir,ialmhablibrodiario from impresion;";  
	  $dato = $db->arrayConsulta($sql);  
	  if ($dato['ialmhablibrodiario'] == "1") {  
		  $caso = obtenerCasoLibro();
		  modificarLibro($_GET['almacen'],$_GET['moneda'],$fecha,$codigo,$_GET['tc']
		  ,$_SESSION['id_usuario'],$_GET['monto'],$db,$_GET['caja'],$caso
			,$_GET['efectivo'],$_GET['credito'],$_GET['nombreasignado'],$_GET['cuentacontable']
			,$_GET['tipoingreso'],$_GET['glosa'],$_GET['nombreasignado']);
	  }
	  echo $codigo."---"; 
	  echo $dato['ialmimprimir']."---"; 
	  exit();  
	}

?>

