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
		  FROM librocomprasiva WHERE numdenitproveedor ='$_GET[nit]'
		   order by fechadeemision desc limit 1;";	   
	  echo $db->getCampo('nomrazonsociprove',$sql_nit)."---".$db->getCampo('numautorizacion',$sql_nit);
	  exit();
	}

	if ($tipo == "numero") {
	   $sucursal = $_GET['sucursal'];  	
	   echo $db->getNextID("numerocuenta","cuentaporpagar where idsucursal=$sucursal");	
	}
	
	if ($tipo == "validar") {
		$sql = "select * from tipoconfiguracion where tipo='Por Pagar Gasto' and descripcion in
			    (select descripcion from tipoconfiguracion where cuenta='$_GET[cuenta]' and tipo='Por Pagar')";
 	    $cajas = $db->arrayConsulta($sql);
		echo $cajas['cuenta'];
		exit();
	}

	if ($tipo == "insertar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
		$fechaVencimiento = filtro($db->GetFormatofecha($_GET['fechavencimiento'],'/'));	
		$numTransaccion = $db->getNextID("numerocuenta","cuentaporpagar where idsucursal=$_GET[sucursal]");
		 if ($_GET['moneda'] == "Dolares") {
		     $_GET['importe'] =  round(($_GET['importe'] * $_GET['tipocambio']),4);	
		 }
		 
		 if ($_GET['tipocuenta'] == "cuentabanco") {
			 $cajacp = $_GET['cuenta'];
		 } else {
			 $cajacp = $_GET['cuentacaja']; 
		 }
		 
		 $sql = "INSERT INTO cuentaporpagar
		 (fecha,numerocuenta,tipodeudor,moneda,tipocambio,idsucursal,monto,glosa,fechavencimiento,iddeudor
		 ,cuenta,cuentacaja,tipocuenta,idusuario,montoactualpagado,estado,documento) 
		 values ('$fecha','$numTransaccion','".filtro($_GET['receptor'])."','".filtro($_GET['moneda'])."','"
		 .filtro($_GET['tipocambio'])."','".filtro($_GET['sucursal'])."','".
		 filtro($_GET['importe'])."','".filtro($_GET['glosa'])."','$fechaVencimiento','"
		 .filtro($_GET['idpersonarecibida'])."','".filtro($_GET['cuenta'])."','".
		 filtro($cajacp)."','".filtro($_GET['tipocuenta'])."','$_SESSION[id_usuario]',0,1,'"
		 .filtro($_GET['documento'])."');";
		 $db->consulta($sql);	
		 $codigo =  $db->getMaxCampo('idporpagar','cuentaporpagar');
		
		 $sql = "select porpagarhablibrodiario from impresion;";  
		 $dato = $db->arrayConsulta($sql);
		
		 if ($dato['porpagarhablibrodiario'] == "1") {
		  $idlibro = insertarLibro($_GET['sucursal'],'Bolivianos',$fecha,$codigo,$_GET['tipocambio']
		  ,$_SESSION['id_usuario'],$db,$_GET['glosa']);
		  $detalleLibro = getDetalleLibro($db,$_GET['sucursal'],$codigo,filtro($_GET['receptor'])
		  ,filtro($_GET['nombrepersona']));	
		  $caja = $_GET['cuentacaja']; 
		  $factura = json_decode(stripcslashes($_GET['factura']),true); 	
		  if ($_GET['cuentacaja'] == "") {
			$sql = "select *from tipoconfiguracion where tipo='Por Pagar Gasto' and descripcion in
			(select descripcion from tipoconfiguracion where cuenta='$_GET[cuenta]' and tipo='Por Pagar')";
			$cajas = $db->arrayConsulta($sql);
			$caja = $cajas['cuenta'];
		  }	  
		  if ($factura['dia'] == "") {		
			detalleSinFactura($db,$idlibro,$caja,$_GET['cuenta'],$detalleLibro,$_GET['importe']);  
		  } else {		  
			detalleFacturado($db,$idlibro,$caja,$_GET['cuenta'],$detalleLibro,$_GET['importe'],$factura['numfactura']);  	  
		  }	  
		 }
		 
		 if ($factura['dia'] != "") {
		     insertarLibroCompras($factura,$fecha,$codigo,$_GET['sucursal'],$db);	
		 }
		 echo $codigo."---";
		 $sql = "select ctaxpagdireimprimir from impresion;";  
		 $dato = $db->arrayConsulta($sql);
		 echo $dato['ctaxpagdireimprimir']."---";
	}

	function detalleSinFactura($db, $idlibro, $cuenta1, $cuenta2, $descripcion, $monto) 
	{  
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		values($idlibro,'$cuenta1','$descripcion',$monto,0,'')";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		values($idlibro,'$cuenta2','$descripcion',0,$monto,'')";
		$db->consulta($sql);
	}

	function detalleFacturado($db, $idlibro, $cuenta1, $cuenta2, $descripcion, $monto, $factura)
	{
		$sql = "select *from configuracioncontable";		
		$dato = $db->arrayConsulta($sql);	
		$porcentaje = (100 - $dato['porcreditoporpagar'])/100;	
		$monto1 = $porcentaje * $monto;	
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		values($idlibro,'$cuenta1','$descripcion',$monto1,0,'$factura')";
		$db->consulta($sql);
		$porcentaje = ($dato['porcreditoporpagar'])/100;	
		$monto1 = $porcentaje * $monto;
		$cuenta1 = $dato['creditofiscalporpagar'];
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		values($idlibro,'$cuenta1','$descripcion',$monto1,0,'$factura')";
		$db->consulta($sql);
		$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		values($idlibro,'$cuenta2','$descripcion',0,$monto,'$factura')";
		$db->consulta($sql);
	}

	function getDetalleLibro($db, $sucursal, $codigo, $tipopersona, $nombrepersona)
	{	
	    $sql = "select * from sucursal where idsucursal=$sucursal";
	    $datosSucursal = $db->arrayConsulta($sql);
	    $descripcionLibro = "Cuenta por Pagar Nº $codigo/$tipopersona: 
		$nombrepersona/Sucursal: $datosSucursal[nombrecomercial]";
	    return $descripcionLibro;	
	}
	
	
	function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $db, $glosa)
	{
		$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' 
		from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
		$num = $db->arrayConsulta($sql); 
		if (!isset($num['num'])){
		  $num['num'] = 1;
		  $num['sucursal'] = $sucursal;
		}		 	
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa
		,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','ingreso','$fecha','$glosa'
		,'$codigo','$tc','$usuario',1,'Cuenta por pagar');"; 
		$db->consulta($sql);
		$libro = $db->getMaxCampo("idlibrodiario","librodiario"); 
		return $libro;
	}
	
	
	function insertarDetalle($db, $idlibro, $cuenta, $descripcion, $debe, $haber, $documento)
	{
	   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
	   values($idlibro,'$cuenta','$descripcion',$debe,$haber,'$documento')";
	   $db->consulta($sql);
	}
	
	
	function modificarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $db, $glosa)
	{
	    $sql = "select idlibrodiario,idsucursal from librodiario where 
	     transaccion='Cuenta por pagar' and idtransaccion=$codigo;";  
		$libro = $db->arrayConsulta($sql); 
	 
		if ($libro['idsucursal'] != $sucursal) {
			$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' from librodiario l where 
			l.idsucursal=$sucursal GROUP BY l.idsucursal;";
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
	
	function insertarLibroCompras($datoLibro, $fecha, $codigo, $sucursal, $db)
	{
		$fechas = explode("/",$fecha);
		$fechaLibro = $fechas[0]."-".$fechas[1]."-".$datoLibro['dia'];
		$sqlCompras = "insert into librocomprasiva(folio,fechadeemision,numdenitproveedor,nomrazonsociprove
		,numfacturaopoliza,numautorizacion,codigodecontrol,
				totalfactura,totalice,importeexcento,importeneto,creditofiscal,idtransaccion
				,transaccion,tipo,tipolibro,idcuenta,idusuario,estado)
				values ($sucursal,'$fechaLibro','$datoLibro[nit]','$datoLibro[razonsocial]'
				,'$datoLibro[numfactura]','$datoLibro[numeroautorizacion]', '$datoLibro[codigocontrol]'
				,'$datoLibro[importetotal]','$datoLibro[ice]'
				,'$datoLibro[excento]','$datoLibro[neto]','$datoLibro[iva]',$codigo,'Cuenta por pagar',
				'CP','0','0','$_SESSION[id_usuario]',1)";
	   $db->consulta($sqlCompras);
	}
	
	if ($tipo == "modificar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
		$fechaVencimiento = filtro($db->GetFormatofecha($_GET['fechavencimiento'],'/'));
		if ($_GET['moneda'] == "Dolares") {
		    $_GET['importe'] =  round(($_GET['importe'] * $_GET['tipocambio']),4);	
		}
		
		if ($_GET['tipocuenta'] == "cuentabanco") {
			$cajacp = $_GET['cuenta'];
		} else {
			$cajacp = $_GET['cuentacaja']; 
		}
		
		$sql = "update cuentaporpagar set fecha='$fecha',tipodeudor='".filtro($_GET['receptor'])
		."',moneda='".filtro($_GET['moneda'])."',tipocambio='".
		filtro($_GET['tipocambio'])."',idsucursal='".filtro($_GET['sucursal'])
		."',monto='".filtro($_GET['importe'])
		."',glosa='".filtro($_GET['glosa'])."',fechavencimiento='$fechaVencimiento'
		,iddeudor='".filtro($_GET['idpersonarecibida'])."',
		 cuenta='".filtro($_GET['cuenta'])."', documento='".filtro($_GET['documento'])
		 ."',tipocuenta='".filtro($_GET['tipocuenta'])."',cuentacaja='".filtro($cajacp).
		 "',idusuario='$_SESSION[id_usuario]' where idporpagar='$_GET[idporpagar]';";
		$db->consulta($sql);
		
		$sql = "select porcobrarhablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);
		if ($dato['porcobrarhablibrodiario'] == "1") {	
		   $idlibro = modificarLibro($_GET['sucursal'],'Bolivianos',$fecha,$_GET['idporpagar']
		   ,$_GET['tipocambio'],$_SESSION['id_usuario'],$db,filtro($_GET['glosa']));
		   $detalleLibro = getDetalleLibro($db,$_GET['sucursal'],$_GET['idporpagar']
		   ,filtro($_GET['receptor']),filtro($_GET['nombrepersona']));		
		   $sql = "delete from detallelibrodiario where idlibro=$idlibro";
		   $db->consulta($sql);	
		   $factura = json_decode(stripcslashes($_GET['factura']),true);  
		   $caja = $_GET['cuentacaja']; 
		   if ($_GET['cuentacaja'] == "") {
			   $sql = "select *from tipoconfiguracion where tipo='Por Pagar Gasto' and descripcion in
			    (select descripcion from tipoconfiguracion where cuenta='$_GET[cuenta]' and tipo='Por Pagar')";
			   $cajas = $db->arrayConsulta($sql);
			   $caja = $cajas['cuenta'];
		   }	  
		   if ($factura['dia'] == "") {		
			   detalleSinFactura($db,$idlibro,$caja,$_GET['cuenta'],$detalleLibro,$_GET['importe']);  
		   } else {
			   detalleFacturado($db,$idlibro,$caja,$_GET['cuenta'],$detalleLibro,$_GET['importe'],$factura['numfactura']);  	  
		   }		
		}
		
		$sql = "delete from librocomprasiva where idtransaccion=$_GET[idporpagar]
		 and transaccion='Cuenta por pagar';";
		$db->consulta($sql);	  
		
		  if ($factura['dia'] != "") {
			insertarLibroCompras($factura,$fecha,$_GET['idporpagar'],$_GET['sucursal'],$db);	
		  }	  
		
		echo $_GET['idporpagar']."---";
		$sql = "select ctaxpagdireimprimir from impresion;";  
		$dato = $db->arrayConsulta($sql);
		echo $dato['ctaxpagdireimprimir']."---";
	}
	
	
	function getTotalGeneral($dato)
	{
		 $totalGeneral = 0;  
		 while($total = mysql_fetch_array($dato)) {
		     $totalGeneral = $totalGeneral + $total['total'];	
		 } 
		 return $totalGeneral;  
	  }
	
	
	 if ($tipo == "reporte") {
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		
		$sql = "SELECT  (SUM(dl.haber ) - SUM(dl.debe)
		) AS  'total'
		FROM detallelibrodiario dl, librodiario l, plandecuenta p 
		WHERE LEFT( dl.idcuenta, 1) =  '2'
		AND l.idlibrodiario = dl.idlibro
		AND l.fecha <=  '$fecha'
		AND dl.idcuenta = p.codigo
		AND l.estado=1  
		GROUP BY dl.idcuenta;";
		$dato = $db->consulta($sql);
		$totalGeneral = getTotalGeneral($dato);
			
		
		$sql = "SELECT p.codigo, p.nivel, p.cuenta
		, IF( p.moneda =  'Bolivianos',  'Bs',  '$us' ) AS  'moneda', SUM( dl.debe ) 
		AS  'debe', SUM( dl.haber ) AS  'haber', (SUM( dl.haber ) - SUM( dl.debe )
		) AS  'total', IF( p.nivel =6, (
		SELECT pc.cuenta
		FROM plandecuenta pc
		WHERE pc.codigo = LEFT( p.codigo, 13 ) ) ,  ''
		) AS  'padre'
		FROM detallelibrodiario dl, librodiario l, plandecuenta p 
		WHERE LEFT( dl.idcuenta, 1 ) =  '2'
		AND l.idlibrodiario = dl.idlibro
		AND l.fecha <=  '$fecha'
		AND dl.idcuenta = p.codigo
		AND l.estado=1 
		and p.estado=1 
		GROUP BY dl.idcuenta, padre;";  
		$data = $db->consulta($sql);
		$subTotal = "";
		$padre = "";
		$totalBs = 0;
		$totalDo = 0;
		$subPorcentajes = array();
		$subTabla = "";
		$monedaSub = "";
		  while($balance = mysql_fetch_array($data)) {
			  $totalBs = $totalBs + $balance['total'];
			  
			  if ($balance['total'] == 0)
				  continue;
			  
					if ($padre != "" && $padre == $balance['padre']) {
						$subTotal = $subTotal + $balance['total'];
						$subTabla = $subTabla ."<tr class='ocultar'>
						 <td class='ocultar'>$balance[nivel]</td>
						 <td class='fondoCelda2'>&nbsp;&nbsp;&nbsp;$balance[cuenta]</td>
						 <td class='fondoCelda2' align='center'>$balance[moneda]</td>
						 <td class='fondoCelda2' align='center'>".number_format($balance['total'],2)."</td>
						</tr>";
						array_push($subPorcentajes,$balance['total']);
						continue;
					} 
					if ($padre != "" && $padre != $balance['padre']) {
						echo "<tr>
						 <td class='ocultar'>5</td>
						 <td class='fondoCelda'>$padre</td>
						 <td class='fondoCelda' align='center'>$monedaSub</td>
						 <td class='fondoCelda' align='center'>".number_format($subTotal,2)."</td>
						</tr>";
						echo $subTabla;
						 $por = (($subTotal/$totalGeneral)*100);
						 $por = redondeado($por,2);
						 $totalSubPorcentaje = abs($por);
						 $auxPorcentaje = (abs($por)>100) ? 100 : abs($por);
						 $porcentajes = $porcentajes."<tr><td width='15%' class='fondoCeldaBarra'>$por%</td>
						 <td width='85%' height='22px'>
						 <div style='width:".abs($auxPorcentaje)."%;' class='barra'></div></td><td></td></tr>";
						
						for ($i = 0; $i < count($subPorcentajes); $i++) {
						   $por = (($subPorcentajes[$i]/$subTotal)*100);
						   $por = redondeado($por,2);
						   
						   $newPorcentaje = (abs($por)/100)*$totalSubPorcentaje;
						   $newPorcentaje = redondeado($newPorcentaje,2);
						   $auxPorcentaje = (abs($newPorcentaje)>100) ? 100 : abs($newPorcentaje);
						   $porcentajes = $porcentajes."<tr class='ocultar' >
						   <td width='15%' class='fondoCeldaBarra'>$newPorcentaje%</td>
						   <td width='85%' height='22px'><div style='width:".abs($auxPorcentaje)."%;' class='barraSecundaria'>
						   </div></td><td></td></tr>";
						}
						
						$subPorcentajes = array();
						$padre = "";
					}
					if ($padre == "" && $balance['nivel'] == 6) {
						$padre = $balance['padre'];
						$subTotal = $balance['total'];
						$monedaSub = $balance['moneda'];
						$subTabla = "<tr class='ocultar'>
						 <td class='ocultar'>$balance[nivel]</td>
						 <td class='fondoCelda2'>&nbsp;&nbsp;&nbsp;$balance[cuenta]</td>
						 <td align='center' class='fondoCelda2'>$balance[moneda]</td>
						 <td align='center' class='fondoCelda2'>".number_format($balance['total'],2)."</td>
						</tr>";
						array_push($subPorcentajes,$balance['total']);
						continue;
					}
				
				  echo "<tr>
				   <td class='ocultar' >$balance[nivel]</td>
				   <td class='fondoCelda'>$balance[cuenta]</td>
				   <td class='fondoCelda' align='center'>$balance[moneda]</td>
				   <td class='fondoCelda' align='center'>".number_format($balance['total'],2)."</td>
				  </tr>"; 
				  $por = (($balance['total'] / $totalGeneral) * 100);
				  $por = redondeado($por, 2);
				  $auxPorcentaje = (abs($por) > 100) ? 100 : abs($por);
				  $porcentajes = $porcentajes.
				  "<tr><td width='15%' class='fondoCeldaBarra'>$por%</td><td width='85%' height='22px'>
				  <div style='width:".abs($auxPorcentaje)."%;' class='barra'></div></td><td></td></tr>";			
			 
		  }
		  
		  
			if ($padre != "") {
				echo "<tr>
				 <td class='ocultar'>5</td>
				 <td class='fondoCelda'>$padre</td>
				 <td class='fondoCelda' align='center'>$monedaSub</td>
				 <td class='fondoCelda' align='center'>".number_format($subTotal,2)."</td>
				</tr>";
				echo $subTabla;
				 $por = (($subTotal / $totalGeneral) * 100);
				 $por = redondeado($por, 2);
				 $totalSubPorcentaje = abs($por);
				 $auxPorcentaje = (abs($por) > 100) ? 100 : abs($por);
				 $porcentajes = $porcentajes."<tr><td width='15%' class='fondoCeldaBarra'>$por%</td>
				 <td width='85%' height='22px'>
				 <div style='width:".abs($auxPorcentaje)."%;' class='barra'></div></td><td></td></tr>";
				
				for ($i = 0; $i < count($subPorcentajes); $i++) {
				   $por = (($subPorcentajes[$i] / $subTotal) * 100);
				   $por = redondeado($por, 2);				   
				   $newPorcentaje = (abs($por) / 100) * $totalSubPorcentaje;
				   $newPorcentaje = redondeado($newPorcentaje,2);
				   $auxPorcentaje = (abs($newPorcentaje)>100) ? 100 : abs($newPorcentaje);
				   $porcentajes = $porcentajes."<tr class='ocultar' >
				   <td width='15%' class='fondoCeldaBarra'>$newPorcentaje%</td>
				   <td width='85%' height='22px'><div style='width:".abs($auxPorcentaje)."%;' class='barraSecundaria'>
				   </div></td><td></td></tr>";
				}
				
				$subPorcentajes = array();
				$padre = "";
			}
		  
		  $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		  $tc = $db->getCampo('dolarcompra',$sql); 
		  $totalDo = $totalBs / $tc;
		  echo "<tr>
				   <td class='ocultar'></td>
				   <td></td>
				   <th align='center' class='cabeceraReporte'>Total Bs.</th>
				   <td align='center' class='cabeceraReporte'>".number_format($totalBs,2)."</td>
				  </tr>
				  <tr>
				   <td class='ocultar'></td>
				   <td></td>
				   <th align='center' class='cabeceraReporte'>Total Sus.</th>
				   <td align='center' class='cabeceraReporte'>".number_format($totalDo,2)."</td>
				  </tr>			  
				  "."---";
		  echo $porcentajes."---";	  
		
		  exit();
	  }
	
	
	  function redondeado($numero, $decimales) 
	  {
	      $factor = pow(10, $decimales);
	      return (round($numero * $factor) / $factor); 
	  }

?>