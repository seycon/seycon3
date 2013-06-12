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
	
	if ($tipo == "consulta") {	
		$consulta = "select * from producto WHERE idproducto =".$_GET['codigo'];
		$respuesta = $db->arrayConsulta($consulta);
		echo $respuesta['unidaddemedida']."---";
		echo $respuesta['unidadalternativa']."---";
		echo $respuesta['conversiones']."---";
		echo $respuesta[$_GET['tipoprecio']]."---";
		$sql = "SELECT DATE_FORMAT( dp.fecha,  '%d/%m/%Y' ) AS  'fecha', lote
		, cantidadactual as 'cantidad', unidadmedida,dp.iddetalleingreso 
				FROM detalleingresoproducto dp, ingresoproducto i
				WHERE dp.idingresoprod = i.idingresoprod
				AND i.idalmacen =$_GET[idalmacen]
				AND dp.cantidadactual >0
				AND dp.idproducto =$_GET[codigo] 
				AND dp.estado=1 and i.estado=1 
				ORDER BY dp.lote;";
		$dato = $db->consulta($sql);
		$i = 0;
		while ($lote = mysql_fetch_array($dato)) {
		    echo "<option value='$i'>$lote[lote]</option>";	
		    $i++;
		}
		echo "---";
		$dato = $db->consulta($sql);
		while ($lote = mysql_fetch_array($dato)) {
			echo "<tr bgcolor='#FFFFFF'>
					  <td>$lote[fecha]</td>
					  <td>$lote[lote]</td>
					  <td>$lote[cantidad]</td>
					  <td>$lote[unidadmedida]</td>
					  <td style='display:none'>$lote[cantidad]</td>
					  <td style='display:none;'>$lote[iddetalleingreso]</td>
				  </tr>";
		}
		echo "---";
		exit();
	}

	if ($tipo == "insertar") {
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		$sql = "insert into egresoproducto values(null,'$_GET[nombrereceptor]','$fecha','"
		.$_GET['motivo']."','".$_GET['almacen']
		."','".$_GET['almacendestino']."','".$_GET['idpersonarecibida']
		."','".$_GET['monto']."','".$_GET['glosa']."','".$_GET['moneda']
		."','$_GET[receptor]','$_GET[cuentacontable]','$_GET[tc]',$_SESSION[id_usuario],1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idegresoprod","egresoproducto");
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = $fila[0];
			$lote = $fila[1];
			$fechaD = $db->GetFormatofecha($fila[2],"/");
			$cantidad = round($fila[3],4);
			$um = trim($fila[4]);
			$precio = round(desconvertir($fila[5]),4);		
			$total = round(desconvertir($fila[6]),4);	
			$iddetalle = $fila[7];		
			$consulta = "insert into detalleegresoproducto values(null,$iddetalle
			,'$id','$codigo','$fechaD','$lote','$cantidad','$um','$precio','$total');";
			$db->consulta($consulta);			
			$sql = "select dp.cantidadactual,dp.unidadmedida,p.unidaddemedida as 'UM',p.unidadalternativa as 'UA'
			,p.conversiones from detalleingresoproducto dp,producto p where 
			dp.iddetalleingreso=$iddetalle and dp.idproducto=p.idproducto;";
			$saliente = 0;
			$datosingreso = $db->arrayConsulta($sql);
			if ($datosingreso['unidadmedida'] == $um) {
			  $saliente = $cantidad;
			} else {
			   if ($um == $datosingreso['UA']) {
				   $saliente = $cantidad / $datosingreso['conversiones'];
			   } else {
				   $saliente = $cantidad * $datosingreso['conversiones'];
			   }
			}
			$sql = "update detalleingresoproducto di set 
			di.cantidadactual=di.cantidadactual-$saliente 
			where di.iddetalleingreso=$iddetalle;";
			$db->consulta($sql);
		  }	
		  
		$sql = "select ealmimprimir,ealmhablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);  
		if ($dato['ealmhablibrodiario'] == "1") {  
		  insertarLibro($_GET['almacendestino'],$_GET['moneda'],$fecha,$codigo
		  ,$_GET['tc'],$_SESSION['id_usuario'],$_GET['monto']
		  ,$_GET['cuentacontable'],$_GET['glosa'],$db,$_GET['receptor']
		  ,$_GET['nombrereceptor'],$_GET['motivo']);
		}	  
		echo $codigo."---";
		echo $dato['ealmimprimir']."---";  
		exit();  
	}

	if ($tipo == "modificar") {
		$codigo = $_GET['idregistro'];
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		$sql = "update egresoproducto set nombreasignado='$_GET[nombrereceptor]'
		,fecha='$fecha',moneda='".$_GET['moneda']."',idalmacen='".$_GET['almacen']
		."',idalmacendestino='".$_GET['almacendestino']."',idpersona='"
		.$_GET['idpersonarecibida']."',motivo='".$_GET['motivo']."',glosa='"
		.$_GET['glosa']."',monto='".desconvertir($_GET['monto']).    
	   "',idusuario=$_SESSION[id_usuario],tipopersona='$_GET[receptor]'
	   ,cuentacontable='$_GET[cuentacontable]' where idegresoprod=".$_GET['idregistro'].";";  
	   $db->consulta($sql);
	   aumentarStock($db,$codigo);
	   $sql = "delete from detalleegresoproducto where idegresoprod=$codigo";
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
			$iddetalle = $fila[7];
			$consulta = "insert into detalleegresoproducto values(null,$iddetalle,'$id'
			,'$codigo','$fechaD','$lote ','$cantidad','$um','$precio','$total');";
			$db->consulta($consulta);
			$sql = "select dp.cantidadactual,dp.unidadmedida,p.unidaddemedida as 'UM',p.unidadalternativa as 'UA'
			,p.conversiones from detalleingresoproducto dp,producto p where 
			dp.iddetalleingreso=$iddetalle and dp.idproducto=p.idproducto;";
			$saliente = 0;
			$datosingreso = $db->arrayConsulta($sql);
			if ($datosingreso['unidadmedida'] == $um) {
			  $saliente = $cantidad;
			} else {
			   if ($um == $datosingreso['UA']) {
				$saliente = $cantidad / $datosingreso['conversiones'];
			   } else {
				$saliente = $cantidad * $datosingreso['conversiones'];
			   }
			}
			$sql = "update detalleingresoproducto di set di.cantidadactual=
			di.cantidadactual-$saliente where di.iddetalleingreso=$iddetalle;";
			$db->consulta($sql);				
		  }	
		  
		$sql = "select ealmimprimir,ealmhablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);  
		if ($dato['ealmhablibrodiario'] == "1"){   
		modificarLibro($_GET['almacendestino'],$_GET['moneda'],$fecha
		,$codigo,$_GET['tc'],$_SESSION['id_usuario'],$_GET['monto']
		,$_GET['cuentacontable'],$_GET['glosa'],$db,
		$_GET['receptor'],$_GET['nombrereceptor'],$_GET['motivo']);
		}
		echo $codigo."---";  
		echo $dato['ealmimprimir']."---";  
		exit();  
	}

	function aumentarStock($db, $idegreso)
	{
	  $sql = "select de.iddetalleingreso,de.cantidad as 'cantidadEgreso'
	  ,de.unidadmedida as 'unidadmedidaegreso',di.cantidadactual
	  ,di.unidadmedida,p.unidaddemedida as 'UM',p.unidadalternativa as 'UA'
	  ,p.conversiones  from detalleegresoproducto de,detalleingresoproducto di
	  ,producto p where de.iddetalleingreso=di.iddetalleingreso 
	  and de.idproducto=p.idproducto and de.idegresoprod=$idegreso;";	
	  $dato = $db->consulta($sql);
	  while ($detalle = mysql_fetch_array($dato)) {
		  if ($detalle['unidadmedidaegreso'] == $detalle['unidadmedida']) {
			$saliente = $detalle['cantidadEgreso'];
		  } else {
			 if ($detalle['unidadmedidaegreso'] == $detalle['UA']) {
			  $saliente = $detalle['cantidadEgreso'] / $detalle['conversiones'];
			 } else {
			  $saliente = $detalle['cantidadEgreso'] * $detalle['conversiones'];
			 }
		  }
		  $sql = "update detalleingresoproducto di set di.cantidadactual=
		  di.cantidadactual+$saliente where di.iddetalleingreso=$detalle[iddetalleingreso];";
		  $db->consulta($sql);
		}	
	}


	function insertarLibro($almacen, $moneda, $fecha, $codigo, $tc, $usuario
	                        , $monto, $cuenta, $glosa, $db, $tipopersona, $nombre, $motivo)
	{
		$sql = "select max(l.numero)+1 as 'num',a.sucursal,a.nombre 
		 from librodiario l,almacen a where l.idsucursal=a.sucursal
		 and a.idalmacen=$almacen GROUP BY l.idsucursal;";  
		$num = $db->arrayConsulta($sql);  	
		if (!isset($num['num'])) {
		    $sql = "select 1 as 'num',sucursal,nombre from almacen where idalmacen=$almacen";	
		    $num = $db->arrayConsulta($sql);
		}	
		
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha
		,glosa,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','egreso','$fecha','$glosa'
		,'$codigo','$tc','$usuario',1,'Egreso Almacen');"; 
		$db->consulta($sql);
		$libro = $db->getMaxCampo("idlibrodiario", "librodiario"); 
		
		$descripcionLibro = "Egreso Almacen Nº $codigo/$tipopersona: $nombre/Almacen: $num[nombre]/$motivo";
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro,'$cuenta','$descripcionLibro',$monto,0,'')";
		$db->consulta($sql);
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);	
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro,'$inventario[inventario]','$descripcionLibro',0,$monto,'')";
		$db->consulta($sql);
	}

	function modificarLibro($almacen, $moneda, $fecha, $codigo, $tc, $usuario, $monto
	                         , $cuenta, $glosa, $db, $tipopersona, $nombre, $motivo)
	{
		$sql = "select idlibrodiario,idsucursal from librodiario where 
		transaccion='Egreso Almacen' and idtransaccion=$codigo;";  
		$libro = $db->arrayConsulta($sql);	
		$sql = "select a.sucursal,a.nombre from almacen a where  a.idalmacen=$almacen ";  
		$num = $db->arrayConsulta($sql);  
		if ($libro['idsucursal'] != $num['sucursal']) {
			$sql = "select max(l.numero)+1 as 'num',a.sucursal,a.nombre 
			from librodiario l,almacen a where l.idsucursal=a.sucursal and 
			a.idalmacen=$almacen GROUP BY l.idsucursal;";  
			$num = $db->arrayConsulta($sql);  	
			if (!isset($num['num'])) {
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
		
		$descripcionLibro = "Egreso Almacen Nº $codigo/$tipopersona: $nombre/Almacen: $num[nombre]/$motivo";
		$sql = "delete from detallelibrodiario where idlibro=$libro[idlibrodiario]";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento
		) values($libro[idlibrodiario],'$cuenta','$descripcionLibro',$monto,0,'')";
		$db->consulta($sql);
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);	
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
		values($libro[idlibrodiario],'$inventario[inventario]','$descripcionLibro',0,$monto,'')";
		$db->consulta($sql);
	}

?>

