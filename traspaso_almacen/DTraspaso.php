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

	if ($tipo == "consulta") {
		$consulta = "select * from producto WHERE idproducto =".$_GET['codigo'];
		$respuesta = $db->arrayConsulta($consulta);
		echo $respuesta['unidaddemedida']."---";
		echo $respuesta['unidadalternativa']."---";
		echo $respuesta['conversiones']."---";
		echo $respuesta['costo']."---";
		$sql = "select dp.iddetalleingreso,date_format(dp.fecha,'%d/%m/%Y')as 'fecha'
		,lote,cantidadactual as 'cantidad',unidadmedida 
		from detalleingresoproducto dp,ingresoproducto i 
		where  i.idingresoprod=dp.idingresoprod and i.idalmacen=$_GET[idalmacen]
		 and dp.cantidadactual>0 and dp.idproducto=$_GET[codigo]
		  and dp.estado=1 and i.estado=1 order by dp.lote;";
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
					  <td style='display:none'>$lote[iddetalleingreso]</td>
				  </tr>";
		}
		echo "---";
		
		echo "<option value='$respuesta[costo]'>costo</option>";
		echo "<option value='$respuesta[costoespecial]'>costoespecial</option>";
		if ($respuesta['textoprecio1'] != "")
			echo "<option value='$respuesta[precio1]'>$respuesta[textoprecio1]</option>";
		if ($respuesta['textoprecio2'] != "")  
			echo "<option value='$respuesta[precio2]'>$respuesta[textoprecio2]</option>";
		if ($respuesta['textoprecio3'] != "") 
			echo "<option value='$respuesta[precio3]'>$respuesta[textoprecio3]</option>";
		echo "---";
		exit();
	}

	if ($tipo == "insertar") {
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		$sql = "insert into traspaso values(null,'".filtro($_GET['solicitado'])
		."','$fecha','".filtro($_GET['almacenorigen'])."','".filtro($_GET['almacendestino'])
		."','".filtro($_GET['moneda'])."','".filtro($_GET['monto'])."','"
		.filtro($_GET['glosa'])."','$_GET[receptor]','$_GET[idcliente]',$_SESSION[id_usuario],1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idtraspaso","traspaso");	
		$sql = "insert into ingresoproducto values(null,0,'traspaso','$fecha','".filtro($_GET['moneda'])."','".
		filtro($_GET['almacendestino'])."','0','','".filtro($_GET['monto'])."','','','".filtro($_GET['glosa'])."','".
		filtro($_GET['monto'])."','','','','','',$_SESSION[id_usuario],'TI',$codigo,'".$_GET['tc']."',0,0,1)";
		$db->consulta($sql);
		$codigoTraspasoI = $db->getMaxCampo("idingresoprod","ingresoproducto");
				
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = filtro($fila[0]);
			$idlote = filtro($fila[1]);
			$fechaD = $db->GetFormatofecha($fila[2],"/");
			$cantidad = filtro($fila[3]);
			$um = filtro($fila[4]);
			$precio = desconvertir($fila[5]);
			$total = desconvertir($fila[6]);
			$lote =  $fila[7];	
			
			$consulta = "insert into detalletraspaso values('$id','$codigo','$fechaD','$idlote'
			,'$lote','$cantidad','$um','$precio','$total');";
			$db->consulta($consulta);				
			$sql = "update detalleingresoproducto di set 
			di.cantidadactual=di.cantidadactual-$cantidad where di.iddetalleingreso=$idlote;";
			$db->consulta($sql);
			$consulta = "insert into detalleingresoproducto values(null,'$id','$codigoTraspasoI','$fechaD'
			,'$lote','$cantidad','$cantidad','$um','$precio','$total',1);";
			$db->consulta($consulta);	
		}	
		
		$sql = "select trapasoimprimir,traphablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);  
		if ($dato['traphablibrodiario'] == "1") {  
		  insertarLibro($_GET['almacenorigen'],filtro($_GET['moneda']),$fecha,$codigo
		  ,$_GET['tc'],$_SESSION['id_usuario'],$_GET['monto'],$db,
		  $_GET['almacenorigen'],$_GET['almacendestino'],filtro($_GET['solicitado']),filtro($_GET['glosa']));
		}
		echo $codigo."---";  
		echo $dato['trapasoimprimir']."---";
		exit();  
	}


	function aumentarStock($db, $idtraspaso) 
	{
	  $sql = "select de.iddetalleingreso,de.cantidad as 'cantidadEgreso'
	  ,de.unidadmedida as 'unidadmedidaegreso',di.cantidadactual,di.unidadmedida
	  ,p.unidaddemedida as 'UM',p.unidadalternativa as 'UA',p.conversiones
		from detalletraspaso de,detalleingresoproducto di,producto p 
		where de.iddetalleingreso=di.iddetalleingreso 
		and de.idproducto=p.idproducto and de.idtraspaso=$idtraspaso;";	
	  $dato = $db->consulta($sql);
		while($detalle = mysql_fetch_array($dato)) {
			if ($detalle['unidadmedidaegreso'] == $detalle['unidadmedida']) {
			    $saliente = $detalle['cantidadEgreso'];
			} else {
			   if ($detalle['unidadmedidaegreso'] == $detalle['UA']) {
				   $saliente = $detalle['cantidadEgreso'] / $detalle['conversiones'];
			   } else {
				   $saliente = $detalle['cantidadEgreso'] * $detalle['conversiones'];
			   }
			}
			$sql = "update detalleingresoproducto di set 
			di.cantidadactual=di.cantidadactual+$saliente where
			 di.iddetalleingreso=$detalle[iddetalleingreso];";
			$db->consulta($sql);
		}	
	}


	if ($tipo == "modificar") {
		$codigo = $_GET['idregistro'];
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		$sql = "update traspaso set solicitado='".filtro($_GET['solicitado'])
		."',fecha='$fecha',moneda='".filtro($_GET['moneda'])."',idalmacenorigen='"
		.filtro($_GET['almacenorigen'])."',idalmacendestino='"
		.filtro($_GET['almacendestino'])."',glosa='".filtro($_GET['glosa'])
		."',total='".desconvertir($_GET['monto']).    
		"',idusuario=$_SESSION[id_usuario],receptor='$_GET[receptor]'
		,idcliente='$_GET[idcliente]' where idtraspaso=".$_GET['idregistro'].";";  	
		$db->consulta($sql);
		 aumentarStock($db,$codigo);
		
		$sql = "select idingresoprod from ingresoproducto where tipo='TI' and idtransaccion=$codigo";
		$codIngreso = $db->arrayConsulta($sql);
		
		$sql = "update ingresoproducto set fecha='$fecha',moneda='".filtro($_GET['moneda'])
		."',idalmacen='".filtro($_GET['almacendestino'])."',monto='"
		.filtro($_GET['monto'])."',idusuario='$_SESSION[id_usuario]' 
		where idingresoprod=$codIngreso[idingresoprod]";
		$db->consulta($sql);
		$sql = "delete from detalleingresoproducto where idingresoprod=$codIngreso[idingresoprod]";
		$db->consulta($sql);
		$sql = "delete from detalletraspaso where idtraspaso=$codigo";
		$db->consulta($sql);
		$datos =  json_decode(stripcslashes($_GET['detalle']));
				
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = filtro($fila[0]);
			$idlote = filtro($fila[1]);
			$fechaD = $db->GetFormatofecha($fila[2],"/");
			$cantidad = filtro($fila[3]);
			$um = filtro($fila[4]);
			$precio = desconvertir($fila[5]);
			$total = desconvertir($fila[6]);
			$lote =  $fila[7];	
			$consulta = "insert into detalletraspaso values('$id','$codigo','$fechaD'
			,'$idlote','$lote','$cantidad','$um','$precio','$total');";
			$db->consulta($consulta);		
			$sql = "update detalleingresoproducto di set 
			di.cantidadactual=di.cantidadactual-$cantidad where di.iddetalleingreso=$idlote;";
			$db->consulta($sql);		
			$consulta = "insert into detalleingresoproducto values(null,'$id','$codIngreso[idingresoprod]'
			,'$fechaD','$lote','$cantidad','$cantidad','$um','$precio','$total',1);";
			$db->consulta($consulta);	
		}	
		
		$sql = "select trapasoimprimir,traphablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);  
		if ($dato['traphablibrodiario'] == "1") {   
		  modificarLibro(filtro($_GET['almacenorigen']),filtro($_GET['moneda']),$fecha
		  ,$codigo,$_GET['tc'],$_SESSION['id_usuario'],filtro($_GET['monto']),$db,
		  $_GET['almacenorigen'],$_GET['almacendestino'],filtro($_GET['solicitado']),filtro($_GET['glosa']));
		}
		echo $codigo."---";  
		echo $dato['trapasoimprimir']."---";
		exit();  
	}


	function insertarLibro($almacen, $moneda, $fecha, $codigo, $tc
							, $usuario, $monto, $db, $origen, $destino, $solicitado, $glosa)
	{
		$sql = "select max(l.numero)+1 as 'num',a.sucursal from librodiario l,almacen a 
		where l.idsucursal=a.sucursal and a.idalmacen=$almacen GROUP BY l.idsucursal;";  
		$num = $db->arrayConsulta($sql); 
		if (!isset($num['num'])) {
			$sql = "select 1 as 'num',sucursal from almacen where idalmacen=$almacen";	
			$num = $db->arrayConsulta($sql);
		}	
			
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha
		,glosa,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','traspaso','$fecha','$glosa'
		,'$codigo','$tc','$usuario',1,'Traspaso Almacen');"; 
		$db->consulta($sql);
		$libro = $db->getMaxCampo("idlibrodiario","librodiario"); 
		$nombreorigen = $db->arrayConsulta("select nombre from almacen where idalmacen=$origen");
		$nombredestino = $db->arrayConsulta("select nombre from almacen where idalmacen=$destino");
		$descripcionLibro = "Traspaso de Almacen Nº $codigo/Traspaso de 
		$nombreorigen[nombre] a $nombredestino[nombre]/$solicitado";
		$sql = "select *from configuracioncontable";
		$inventario = $db->arrayConsulta($sql);	
		$sql = "insert into detallelibrodiario(idlibro,idcuenta
		,descripcion,debe,haber,documento) values($libro,'$inventario[inventario]'
		,'$descripcionLibro',$monto,0,'')";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion
		,debe,haber,documento) values($libro,'$inventario[inventario]'
		,'$descripcionLibro',0,$monto,'')";
		$db->consulta($sql);
	}

	function modificarLibro($almacen, $moneda, $fecha, $codigo, $tc, $usuario
							 , $monto, $db, $origen, $destino, $solicitado, $glosa)
	{
		$sql = "select idlibrodiario,idsucursal from librodiario 
		where transaccion='Traspaso Almacen' and idtransaccion=$codigo;";  
		$libro = $db->arrayConsulta($sql); 
		
		if ($libro['idlibrodiario'] != "") {
			$sql = "select a.sucursal from almacen a where  a.idalmacen=$almacen ";  
			$num = $db->arrayConsulta($sql);  
			if ($libro['idsucursal'] != $num['sucursal']) {
				$sql = "select max(l.numero)+1 as 'num',a.sucursal from librodiario l,almacen a 
				where l.idsucursal=a.sucursal and a.idalmacen=$almacen GROUP BY l.idsucursal;";  
				$num = $db->arrayConsulta($sql);  	
				  if (!isset($num['num'])) {
					 $sql = "select 1 as 'num',sucursal from almacen where idalmacen=$almacen";	
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
			$nombreorigen = $db->arrayConsulta("select nombre from almacen where idalmacen=$origen");
			$nombredestino = $db->arrayConsulta("select nombre from almacen where idalmacen=$destino");
			$descripcionLibro = "Traspaso de Almacen Nº $codigo/Traspaso de 
			$nombreorigen[nombre] a $nombredestino[nombre]/$solicitado";
			$sql = "select *from configuracioncontable";
			$inventario = $db->arrayConsulta($sql);	
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
			values($libro[idlibrodiario],'$inventario[inventario]','$descripcionLibro',$monto,0,'')";
			$db->consulta($sql);
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
			values($libro[idlibrodiario],'$inventario[inventario]','$descripcionLibro',0,$monto,'')";
			$db->consulta($sql);
		}
	}

?>