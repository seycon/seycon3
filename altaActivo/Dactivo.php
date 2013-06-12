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
	
	if ($tipo == "insertar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fechacompra'],"/"));
		$numero = $db->getNextID("numero","activo where idsucursal='$_GET[idsucursal]'");   
		$sql = "INSERT INTO activo (idactivo,numero,nombre,idtipoactivo,idtrabajador,idsucursal
		,detalle,cuenta,fechacompra,cantidad,precio,idusuario,estado,ubicacion,tipocuenta)
		  VALUES (NULL,'$numero','".filtro($_GET['nombre'])."','"
		  .filtro($_GET['idtipoactivo'])."','".filtro($_GET['idtrabajador'])
		  ."','".filtro($_GET['idsucursal']).
		  "','".filtro($_GET['detalle'])."','".filtro($_GET['cuenta'])
		  ."','$fecha','".filtro($_GET['cantidad'])."','".filtro($_GET['precio'])."',$_SESSION[id_usuario],'1',
		  '".filtro($_GET['ubicacion'])."','".filtro($_GET['tipocuenta'])."');";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo('idactivo','activo');
		$total = $_GET['cantidad']*$_GET['precio'];
		insertarLibro($_GET['idsucursal'],'Bolivianos',$fecha,$codigo
		,$_GET['tipoCambioBs'],$_SESSION['id_usuario'],$total,$db,$_GET['cuenta'],filtro($_GET['detalle']),
		$_GET['idtrabajador'],$_GET['idtipoactivo']);
		
		$factura = json_decode(stripcslashes($_GET['factura']),true);  
		
		if ($factura['dia'] != "") {
		    insertarLibroCompras($factura, $fecha, $codigo, $_GET['idsucursal'], $db);	
		}
		exit();
	}


	if ($tipo == "modificar") {
		 $fecha = filtro($db->GetFormatofecha($_GET['fechacompra'],"/"));
		 $sql = "UPDATE activo SET nombre='".filtro($_GET['nombre'])
		 ."', idtipoactivo='".filtro($_GET['idtipoactivo'])
		 ."', idtrabajador='".filtro($_GET['idtrabajador'])
		  ."', idsucursal='".filtro($_GET['idsucursal'])
		  ."', detalle='".filtro($_GET['detalle'])."', cuenta='".filtro($_GET['cuenta']).
		  "', fechacompra='$fecha', cantidad='".filtro($_GET['cantidad'])."', precio='".filtro($_GET['precio']).
		  "',idusuario=$_SESSION[id_usuario],ubicacion='".filtro($_GET['ubicacion'])
		  ."',tipocuenta='".filtro($_GET['tipocuenta'])."' WHERE idactivo= '".$_GET['idactivo']."';";
		  $db->consulta($sql);
		  $total = $_GET['cantidad']*$_GET['precio'];
		  modificarLibro($_GET['idsucursal'],'Bolivianos',$fecha
		  ,$_GET['idactivo'],$_GET['tipoCambioBs'],$_SESSION['id_usuario'],$total,$db,$_GET['cuenta'],
		  filtro($_GET['detalle']),$_GET['idtrabajador'],$_GET['idtipoactivo']);	  
		  
		  $sql = "delete from librocomprasiva where idtransaccion=$_GET[idactivo] and transaccion='Alta Activo';";
		  $db->consulta($sql);
		  
		  $factura = json_decode(stripcslashes($_GET['factura']),true);  	
		  if ($factura['dia'] != "") {
			  insertarLibroCompras($factura, $fecha, $_GET['idactivo'], $_GET['idsucursal'], $db);	
		  }	  		  
		  exit();
	}
	
	if ($tipo == "nit") {
		  $sql_nit = "SELECT nomrazonsociprove, numautorizacion, fechadeemision
			  FROM librocomprasiva WHERE numdenitproveedor ='$_GET[nit]' order by fechadeemision desc limit 1;";	   
		   echo $db->getCampo('nomrazonsociprove',$sql_nit)."---".$db->getCampo('numautorizacion',$sql_nit);
		   exit();
	}
	
	function insertarLibroCompras($datoLibro, $fecha, $codigo, $sucursal, $db) 
	{
		$fechas = explode("/",$fecha);
		$fechaLibro = $fechas[0]."-".$fechas[1]."-".$datoLibro['dia'];
		$sqlCompras = "insert into librocomprasiva(folio,fechadeemision,numdenitproveedor
		,nomrazonsociprove,numfacturaopoliza,numautorizacion,codigodecontrol,
				totalfactura,totalice,importeexcento,importeneto,creditofiscal
				,idtransaccion,transaccion,tipo,tipolibro,idcuenta,idusuario,estado)
				values ($sucursal,'$fechaLibro','$datoLibro[nit]','$datoLibro[razonsocial]'
				,'$datoLibro[numfactura]','$datoLibro[numeroautorizacion]'
				, '$datoLibro[codigocontrol]','$datoLibro[importetotal]','$datoLibro[ice]'
				,'$datoLibro[excento]','$datoLibro[neto]','$datoLibro[iva]',$codigo,'Alta Activo',
				'AA','0','0','$_SESSION[id_usuario]',1)";
	   $db->consulta($sqlCompras);
	}
	
	
	function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario
	                         , $monto, $db, $cuentacaja, $glosa, $trabajador, $tipoactivo)
	{
		$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal'
		 from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
		$num = $db->arrayConsulta($sql); 
		if (!isset($num['num'])) {
		   $num['num'] = 1;
		   $num['sucursal'] = $sucursal;
		}		 	
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha
		,glosa,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','ingreso','$fecha'
		,'$glosa','$codigo','$tc','$usuario',1,'Nuevo Activo');"; 
		$db->consulta($sql);
		$sql = "select * from sucursal where idsucursal=$sucursal";
		$datosSucursal = $db->arrayConsulta($sql);
		$sql = "select *from trabajador where idtrabajador=$trabajador;";
		$datoTrabajador = $db->arrayConsulta($sql);	
		$descripcionLibro = "Alta Activo Nº $codigo/Trabajador: $datoTrabajador[nombre]
		 $datoTrabajador[apellido]/Sucursal: $datosSucursal[nombrecomercial]";
		$libro = $db->getMaxCampo("idlibrodiario","librodiario"); 
		setDetalleLibro($tipoactivo, $libro, $descripcionLibro, $monto, $cuentacaja, $db);
	}
	
	
	function modificarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario
	                        , $monto, $db, $cuentacaja, $glosa, $trabajador, $tipoactivo)
	{
		$sql = "select idlibrodiario,idsucursal from librodiario where transaccion='Nuevo Activo' and idtransaccion=$codigo;";  
		$libro = $db->arrayConsulta($sql); 
	 
		if ($libro['idsucursal'] != $sucursal) {
			 $sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal'
			 from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";
			 $num = $db->arrayConsulta($sql);  	
			 if (!isset($num['num'])) {
			     $num['num'] = 1;
			     $num['sucursal'] = $sucursal;
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
		$sql = "select * from sucursal where idsucursal=$sucursal";
		$datosSucursal = $db->arrayConsulta($sql);
		$sql = "select *from trabajador where idtrabajador=$trabajador;";
		$datoTrabajador = $db->arrayConsulta($sql);	
		$descripcionLibro = "Alta Activo Nº $codigo/Trabajador: $datoTrabajador[nombre]
		 $datoTrabajador[apellido]/Sucursal: $datosSucursal[nombrecomercial]";
		setDetalleLibro($tipoactivo,$libro['idlibrodiario'],$descripcionLibro,$monto,$cuentacaja,$db);	
	}
	
	function setDetalleLibro($tipoactivo, $libro, $descripcion, $monto, $cuentacaja, $db)
	{
		$sql = "select *from tipoactivo where idtipoactivo=$tipoactivo";
		$datoTipoActivo = $db->arrayConsulta($sql);		
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
		values($libro,'$datoTipoActivo[cuentaactivofijo]','$descripcion',$monto,0,'')";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
		values($libro,'$cuentacaja','$descripcion',0,$monto,'')";
		$db->consulta($sql);
	}
?>