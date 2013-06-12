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
	    return htmlspecialchars(strip_tags($cadena));
	}
	
	function filtro2($cadena)
	{
	    return htmlspecialchars(addslashes(strip_tags($cadena)));
	}
	
	function obtenerPrecios($idservicio, $db)
	{
		$sql = "select * from servicio where idservicio=$idservicio";
		$datoServicio = $db->arrayConsulta($sql);	
		$sql = "select * from configuracionprecios";
		$precios = $db->arrayConsulta($sql);
		echo "<option value=''>--Seleccione--</option>";
		
		if ($datoServicio['precio1'] != "") {
		   echo "<option value='$datoServicio[precio1]'>$precios[textoprecio1]</option>"; 
		}
		if ($datoServicio['precio2'] != "") {
		   echo "<option value='$datoServicio[precio2]'>$precios[textoprecio2]</option>"; 
		}
		if ($datoServicio['precio3'] != "") {
		   echo "<option value='$datoServicio[precio3]'>$precios[textoprecio3]</option>"; 
		}
		if ($datoServicio['precio4'] != "") {
		   echo "<option value='$datoServicio[precio4]'>$precios[textoprecio4]</option>"; 
		}			
	}
	
	if ($tipo == "consultarPrecios") {
		obtenerPrecios($_GET['idservicio'], $db);
	}
	
	if ($tipo == "cuenta") {
		$tipocuenta = $_GET['tipo'];
		$sql = "select c.* from usuario u,cajero c where 
		c.idtrabajador=u.idtrabajador and u.idusuario=$_SESSION[id_usuario];";
		$cuenta = $db->arrayConsulta($sql);
		echo "<option value='' selected='selected'>-- Seleccione --</option>";
		if ($tipocuenta == "Caja") {		
			echo "<option value='$cuenta[cuentacaja1]'>$cuenta[textocaja1]</option>";
			echo "<option value='$cuenta[cuentacaja2]'>$cuenta[textocaja2]</option>";
		}
		if ($tipocuenta == "Banco") {
			echo "<option value='$cuenta[cuentabanco1]'>$cuenta[textobanco1]</option>";
			echo "<option value='$cuenta[cuentabanco2]'>$cuenta[textobanco2]</option>";
			echo "<option value='$cuenta[cuentabanco3]'>$cuenta[textobanco3]</option>";
		}	
	}

	if ($tipo == "pendientes") {
	   $iddeudor = $_GET['iddeudor'];
	   $receptor = $_GET['receptor'];
 
	   $sql1 = "select n.idporpagar as 'id',n.idporpagar as 'nro',date_format(n.fecha,'%d/%m/%Y')as 'fecha'
	   ,n.cuenta as 'codigo',p.cuenta,
		  left(n.glosa,30)as 'detalle',(n.monto-n.montoactualpagado)as 'monto','Cuenta Por Pagar' as 'transaccion' 
		  from cuentaporpagar n,plandecuenta p where n.cuenta=p.codigo and (monto-montoactualpagado)>0 and 
		  iddeudor=$iddeudor and n.tipodeudor='".$receptor."' and n.estado=1 and p.estado=1;";
	   $sql2 = "select n.idingresoprod as 'id',n.idingresoprod as 'nro',date_format(n.fecha,'%d/%m/%Y')as 'fecha'
	   ,cc.proveedorpagar as 'codigo',p.cuenta,
		  left(n.glosa,30)as 'detalle',(n.diascredito-n.montoactualcredito)as 'monto','Ingreso Producto' as 'transaccion' 
		  from ingresoproducto n,plandecuenta p,configuracioncontable cc 
		   where cc.proveedorpagar=p.codigo and (diascredito-montoactualcredito)>0 and 
		  n.idpersonarecibida=$iddeudor and n.receptor='".$receptor."' and n.estado=1 and p.estado=1;";  	
		
		$Vconsulta = array();
		$Vconsulta[0] = $sql1;  
		$Vconsulta[1] = $sql2; 
	
		$i = 1;
	    for ($j = 0; $j < count($Vconsulta); $j++) {
		    $cuenta = $db->consulta($Vconsulta[$j]);   
			while ($dato = mysql_fetch_array($cuenta)) {
		
			   echo "
					   <tr bgColor='#E6E6E6'>
						<td align='center'>$dato[nro]</td>
						<td align='center'>$dato[fecha]</td>
						<td align='center'>$dato[cuenta]</td>
						<td align='center'>$dato[transaccion]</td>
						<td style='display:none'>$dato[codigo]</td>
						<td>$dato[detalle]</td>
						<td>".number_format($dato['monto'],2)."</td>
						<td>
						<input type='radio' id='selectorCuenta' name='selectorCuenta' value='$i'
						 onclick='selectorPago(this);' />
						</td>
						<td style='display:none'>$dato[id]</td>
					   </tr>
					   ";
				$i++;	   
			 }
	   }	
		
	   exit();
	}


	if ($tipo == "nit") {
		$sql_nit = "SELECT nomrazonsociprove, numautorizacion, fechadeemision
			FROM librocomprasiva WHERE numdenitproveedor ='$_GET[nit]' order by fechadeemision desc limit 1;";	   
		 echo $db->getCampo('nomrazonsociprove',$sql_nit)."---".$db->getCampo('numautorizacion',$sql_nit);
		 exit();
	}     

	if ($tipo == "insertar") {
		$facturado = false;
		$sql = "select *from configuracioncontable;";
		$contabilidad = $db->arrayConsulta($sql);
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));	
		$numero = $db->getNextID("numero","egreso where idsucursal='".filtro($_GET['sucursal'])."'");
		$tDolares = 0;
	
		if ($_GET['egresoDolares'] > 0) {
			$tDolares = round(($_GET['egresoDolares'] * $_GET['tipocambio']),4);	
		}
			
		$sql = "insert into egreso values(null,'$numero','$fecha','".filtro($_GET['cuenta'])
		."','".filtro($_GET['cheque'])."','".filtro($_GET['idpersona'])
		."','".filtro($_GET['tipopersona'])."','".filtro($_GET['nombrepersona'])."','".filtro($_GET['recibo'])
		."','".filtro($_GET['sucursal'])
		."','".filtro($_GET['egresobs'])."','".filtro($tDolares)."','".filtro($_GET['glosa'])
		."','".filtro($_GET['tipocambio'])."','$_SESSION[id_usuario]',1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idegreso","egreso");
		
		$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		$tc = $db->getCampo('dolarcompra',$sql); 
		
		$sql = "select egrdirectoimprimir,egrhablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);
		if ($dato['egrhablibrodiario'] == "1") {
		  $idlibro = insertarLibro(filtro($_GET['sucursal']),'Bolivianos',$fecha,$codigo,$tc,$_SESSION['id_usuario']
		  ,$db,filtro($_GET['glosa']));
		  $detalleLibro = getDetalleLibro($db,filtro($_GET['sucursal']),$codigo,filtro($_GET['tipopersona'])
		  ,filtro($_GET['nombrepersona']));	
		}
		$tipoC = $_GET['tipocambio'];
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));
		$datos =  json_decode(stripcslashes($_GET['detalle'])); 
		$facturas =   json_decode(stripcslashes($_GET['factura'])); 	
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];
			if ($fila[4] != "-1") {
				$facturado = true;
			}
		}
				
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$cuenta = filtro2($fila[0]);
			$descripcion = filtro2($fila[1]);
			$totalbs = filtro2(desconvertir($fila[2]));
			$totaldolares = filtro2(desconvertir($fila[3]));
			$totalD = round(($totaldolares * $tipoC),4);
			$transaccionPago= $fila[5];
			$idtransaccionPago = $fila[6];
			
			if ($transaccionPago == "Cuenta Por Pagar") {
				$sql = "update cuentaporpagar set montoactualpagado=montoactualpagado+".($totalbs+$totalD).
				" where idporpagar=".$idtransaccionPago.";";
				$db->consulta($sql);
			}
			
			if ($transaccionPago == "Ingreso Producto") {
				$sql = "update ingresoproducto set montoactualcredito=montoactualcredito+".($totalbs+$totalD).
				" where idingresoprod=".$idtransaccionPago.";";
				$db->consulta($sql);
			}
			
			$consulta = "insert into detalleegreso values(null,'$codigo','$cuenta','$descripcion','$totalbs','$totalD'
			,'$transaccionPago','$idtransaccionPago');";
			$db->consulta($consulta);	
			$iddetalle = $db->getMaxCampo('iddetalleegreso','detalleegreso');
			$docLibro = $_GET['recibo'];
			if ($fila[4] != "-1") {
			  insertarLibroCompras($facturas[$fila[4]],$fecha,$iddetalle,$_GET['sucursal'],$db);	
			  $docLibro = $facturas[$fila[4]][3];
			}
			if ($dato['egrhablibrodiario'] == "1") { 
			 $montoLibro = 	($totalbs + $totalD);
			  if ($facturado) {
				$porcentaje = (100 - $contabilidad['porcentajecreditofiscal'])/100;	  
				$montoLibro = $montoLibro * $porcentaje;
			  } 
			
			  insertarDetalle($db, $idlibro, $cuenta, $detalleLibro, $montoLibro, 0, $docLibro);		
			}
		}	
		if ($dato['egrhablibrodiario'] == "1") {  
		  if ($facturado) {
			  $porcentaje = ($contabilidad['porcentajecreditofiscal'])/100;	  
			  $montoLibro = ($_GET['egresobs'] + $tDolares) * $porcentaje;
			  insertarDetalle($db, $idlibro, $contabilidad['creditofiscal'], $detalleLibro, $montoLibro, 0, $_GET['recibo']);
		  } 
		  insertarDetalle($db, $idlibro, $_GET['cuenta'], $detalleLibro, 0, ($_GET['egresobs']+$tDolares), $_GET['recibo']);  
		}
		echo $codigo."---";  
		echo $dato['egrdirectoimprimir']."---";
		exit();  
		
	}
	
	function insertarLibroCompras($datoLibro, $fecha, $iddetalle, $sucursal, $db)
	{
		$fechas = explode("/",$fecha);
		$fechaLibro = $fechas[0]."-".$fechas[1]."-".$datoLibro[0];
		$sqlCompras = "insert into librocomprasiva(folio,fechadeemision,numdenitproveedor,nomrazonsociprove
		,numfacturaopoliza,numautorizacion,codigodecontrol,
				totalfactura,totalice,importeexcento,importeneto,creditofiscal
				,idtransaccion,transaccion,tipo,tipolibro,idcuenta,idusuario,estado)
				values ($sucursal,'$fechaLibro','$datoLibro[1]','$datoLibro[2]','$datoLibro[3]'
				,'$datoLibro[4]', '$datoLibro[10]','$datoLibro[5]','$datoLibro[6]','$datoLibro[7]'
				,'$datoLibro[8]','$datoLibro[9]',$iddetalle,'Egreso Dinero',
				'ED','0','0','$_SESSION[id_usuario]',1)";
	   $db->consulta($sqlCompras);
	}
	
	
	function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $db, $glosa) 
	{
		$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' from librodiario l
		 where l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
		$num = $db->arrayConsulta($sql); 
		if (!isset($num['num'])) {
		  $num['num'] = 1;
		  $num['sucursal'] = $sucursal;
		}		 	
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa
		,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','egreso','$fecha','$glosa','$codigo','$tc','$usuario',1,'Egreso dinero');"; 
		$db->consulta($sql);
		$libro = $db->getMaxCampo("idlibrodiario","librodiario"); 
		return $libro;
	}
	
	function getDetalleLibro($db, $sucursal, $codigo, $tipopersona, $nombrepersona) 
	{	
	  $sql = "select * from sucursal where idsucursal=$sucursal";
	  $datosSucursal = $db->arrayConsulta($sql);
	  $descripcionLibro = "Egreso Dinero Nº $codigo/$tipopersona: $nombrepersona/Sucursal:
	   $datosSucursal[nombrecomercial]";
	  return $descripcionLibro;	
	}
	
	function insertarDetalle($db, $idlibro, $cuenta, $descripcion, $debe, $haber, $documento) 
	{
	   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
	   values ($idlibro,'$cuenta','$descripcion',$debe,$haber,'$documento')";
	   $db->consulta($sql);
	}
	
	function modificarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $db, $glosa) 
	{
		$sql = "select idlibrodiario,idsucursal from librodiario where 
		transaccion='Egreso dinero' and idtransaccion=$codigo;";  
		$libro = $db->arrayConsulta($sql); 
	 
		if ($libro['idsucursal'] != $sucursal) {
			$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' from librodiario l
			 where l.idsucursal=$sucursal GROUP BY l.idsucursal;";
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
		return $libro['idlibrodiario'];
	}
		
	
	
	
	if ($tipo == "modificar") {
		$facturado = false;
		$sql = "select *from configuracioncontable;";
		$contabilidad = $db->arrayConsulta($sql);
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
		$egreso = filtro($_GET['idegreso']);
		$tDolares = 0;
		if ($_GET['egresoDolares'] > 0) {
		  $tDolares = round(($_GET['egresoDolares'] * $_GET['tipocambio']),4);	
		}	
		$sql = "update egreso set fecha='$fecha',cuenta='".filtro($_GET['cuenta'])
		."',cheque='".filtro($_GET['cheque'])."',idpersona='".filtro($_GET['idpersona'])."'
		,tipopersona='".filtro($_GET['tipopersona'])."',nombrepersona='".filtro($_GET['nombrepersona'])."',
		recibo='".filtro($_GET['recibo'])."',idsucursal='".filtro($_GET['sucursal'])
		."',egresoBolivianos='".filtro($_GET['egresobs'])."',egresoDolares='"
		.filtro($tDolares)."',glosa='".filtro($_GET['glosa'])."',idusuario='"
		.filtro($_SESSION['id_usuario'])."'  where idegreso=$egreso";
		$db->consulta($sql);
		
		$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		$tc = $db->getCampo('dolarcompra',$sql); 
		
		$sql = "select egrdirectoimprimir,egrhablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);
		
		if ($dato['egrhablibrodiario'] == "1") {
		  $idlibro = modificarLibro(filtro($_GET['sucursal']),'Bolivianos',$fecha,$egreso,$tc
		  ,$_SESSION['id_usuario'],$db,filtro($_GET['glosa']));
		  $detalleLibro = getDetalleLibro($db,filtro($_GET['sucursal']),$codigo
		  ,filtro($_GET['tipopersona']),filtro($_GET['nombrepersona']));		
		  $sql = "delete from detallelibrodiario where idlibro=$idlibro";
		  $db->consulta($sql);	
		}
		
		
		$sql = "select *from detalleegreso where idegreso=$egreso";		
		$dConsulta = $db->consulta($sql);
		while ($data = mysql_fetch_array($dConsulta)) {
			if ($data['transaccion'] == "Cuenta Por Pagar") {
			  $sql = "update cuentaporpagar set montoactualpagado=montoactualpagado-"
			  .($data['montobolivianos']+($data['montodolares']*$tc)).
				" where idporpagar=".$data['idtransaccion'].";";
			  $db->consulta($sql);
			}
			
			if ($data['transaccion'] == "Ingreso Producto") {
			$sql = "update ingresoproducto set montoactualcredito=montoactualcredito-"
			.($data['montobolivianos']+($data['montodolares']*$tc)).
			" where idingresoprod=".$data['idtransaccion'].";";
			$db->consulta($sql);
			}
		}
		
		
		$sql = "delete from librocomprasiva where idtransaccion 
		in(select iddetalleegreso from detalleegreso where idegreso=$egreso)and transaccion='Egreso Dinero';";	
		$db->consulta($sql);	
		$sql = "delete from detalleegreso where idegreso=$egreso";
		$db->consulta($sql);
		$tipoC = $_GET['tipocambio'];
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));
		$datos =  json_decode(stripcslashes($_GET['detalle'])); 
		$facturas =   json_decode(stripcslashes($_GET['factura']));
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];
			if ($fila[4] != "-1") {
				$facturado = true;
			}
		}
					
		for ($i = 0; $i < count($datos); $i++) {
		  $fila = $datos[$i];                 
		  $cuenta = filtro2($fila[0]);
		  $descripcion = filtro2($fila[1]);
		  $totalbs = filtro2(desconvertir($fila[2]));
		  $totaldolares = filtro2(desconvertir($fila[3]));
		  $totalD = round(($totaldolares * $tipoC),4);
		  $transaccionPago= $fila[5];
		  $idtransaccionPago = $fila[6];
		  
		  if ($transaccionPago == "Cuenta Por Pagar") {
			$sql = "update cuentaporpagar set montoactualpagado=montoactualpagado+".($totalbs+$totalD).
			" where idporpagar=".$idtransaccionPago.";";
			$db->consulta($sql);
		  }
		  
		  if ($transaccionPago == "Ingreso Producto") {
			$sql = "update ingresoproducto set montoactualcredito=montoactualcredito+".($totalbs+$totalD).
			" where idingresoprod=".$idtransaccionPago.";";
			$db->consulta($sql);
		  }
		  
		  $consulta = "insert into detalleegreso values(null,'$egreso','$cuenta','$descripcion','$totalbs','$totalD',
		  '$transaccionPago','$idtransaccionPago');";
		  $db->consulta($consulta);	
		  $iddetalle = $db->getMaxCampo('iddetalleegreso','detalleegreso');
		  $docLibro = $_GET['recibo'];
		  if ($fila[4] != "-1") {
			  insertarLibroCompras($facturas[$fila[4]],$fecha,$iddetalle,filtro($_GET['sucursal']),$db);			  
			  $docLibro = $facturas[$fila[4]][3];
		  }
		  if ($dato['egrhablibrodiario'] == "1") {
			$montoLibro = ($totalbs+$totalD);
			if ($facturado) {
			    $porcentaje = (100 - $contabilidad['porcentajecreditofiscal']) / 100;	  
			    $montoLibro = $montoLibro * $porcentaje;
			}	
			insertarDetalle($db, $idlibro, $cuenta, $detalleLibro, $montoLibro, 0, filtro($docLibro));		
		  }
					  
		}	
		if ($dato['egrhablibrodiario'] == "1") { 
			if ($facturado) {
				$porcentaje = ($contabilidad['porcentajecreditofiscal'])/100;	  
				$montoLibro = ($_GET['egresobs']+$tDolares) * $porcentaje;
				insertarDetalle($db, $idlibro, $contabilidad['creditofiscal'], $detalleLibro, $montoLibro, 0, $_GET['recibo']);
			 }  
			  insertarDetalle($db,$idlibro,filtro($_GET['cuenta']),$detalleLibro,0
			  ,(filtro($_GET['egresobs'])+$tDolares),filtro($_GET['recibo']));   
		}
		echo $egreso."---";
		echo $dato['egrdirectoimprimir']."---";  
		exit();  
	}


?>