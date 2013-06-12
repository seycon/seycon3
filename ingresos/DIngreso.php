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

	if ($tipo == "cuenta") {
		$tipocuenta = $_GET['tipo'];
		$sql = "select c.* 
		from usuario u,cajero c where c.idtrabajador=u.idtrabajador
		 and u.idusuario=$_SESSION[id_usuario];";
		$cuenta = $db->arrayConsulta($sql);
		echo "<option value='' selected='selected'>- Seleccione --</option>";
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
	   $sql1 = ""; 
	   $sql2 = "";
		if ($receptor == "cliente") {
		  $sql1 = "select n.idnotaventa as 'id',n.numero as 'cod'
		  ,date_format(n.fecha,'%d/%m/%Y')as 'fecha',n.caja as 'codigo',p.cuenta,
		  left(n.glosa,30)as 'detalle',(n.credito-n.montoactualcredito)as 'monto'
		  ,'Nota Venta Producto' as 'transaccion' 
		  from notaventa n,plandecuenta p,configuracioncontable cc 
		  where cc.clientescobrar=p.codigo
		   and (credito-montoactualcredito)>0 and idcliente=$iddeudor 
		  and n.tiponota='productos' and n.estado=1 and p.estado=1;";  
		  $sql2 = "select n.idnotaventa as 'id',n.numero as 'cod'
		  ,date_format(n.fecha,'%d/%m/%Y')as 'fecha',n.caja as 'codigo',p.cuenta,
		  left(n.glosa,30)as 'detalle',(n.credito-n.montoactualcredito)as 'monto'
		  ,'Nota Venta Servicios' as 'transaccion' 
		  from notaventa n,plandecuenta p,configuracioncontable cc where cc.clientescobrar=p.codigo
		   and (credito-montoactualcredito)>0 and idcliente=$iddeudor 
		  and n.tiponota='servicios' and n.estado=1 and p.estado=1;"; 
		}     
		$sql3 = "(select n.idporcobrar as 'id',n.idporcobrar as 'cod'
		,date_format(n.fecha,'%d/%m/%Y')as 'fecha'
		  ,n.cuenta as 'codigo',p.cuenta,
		  left(n.glosa,30)as 'detalle',(n.monto-n.montoactualcobrado)as 'monto'
		  ,'Cuenta Por Cobrar' as 'transaccion' 
		  from cuentaporcobrar n,plandecuenta p where n.cuenta=p.codigo and (monto-montoactualcobrado)>0 and 
		  iddeudor=$iddeudor and n.tipodeudor='".$receptor."' and n.estado=1 
		  and tipocuenta='cuentaCaja' and p.estado=1) union (
		  select n.idporcobrar as 'id',n.idporcobrar as 'cod',date_format(n.fecha,'%d/%m/%Y')as 'fecha'
		  ,n.cuenta as 'codigo',p.cuenta,
		  left(n.glosa,30)as 'detalle',(n.monto-n.montoactualcobrado)as 'monto'
		  ,'Cuenta Por Cobrar' as 'transaccion' 
		  from cuentaporcobrar n,plandecuenta p where n.cuenta=p.codigo
		   and (monto-montoactualcobrado)>0 and 
		  iddeudor=$iddeudor and n.tipodeudor='".$receptor."'
		   and n.estado=1 and tipocuenta='cuentaApertura'
		  and p.estado=1 );";
		$sql4 = "";  
		
		
		$Vconsulta = array();
		$Vconsulta[0] = $sql1;  
		$Vconsulta[1] = $sql2; 
		$Vconsulta[2] = $sql3;
		$i = 1;
	  for ($j = 0; $j < count($Vconsulta); $j++) {
		 if ($Vconsulta[$j] != "") {
			$cuenta = $db->consulta($Vconsulta[$j]);   
			while ($dato = mysql_fetch_array($cuenta)) {
			   echo "  <tr bgColor='#E6E6E6'>
						<td align='center'>$dato[cod]</td>
						<td align='center'>$dato[fecha]</td>
						<td align='center'>$dato[cuenta]</td>
						<td align='center'>$dato[transaccion]</td>
						<td style='display:none'>$dato[codigo]</td>
						<td>$dato[detalle]</td>
						<td>".number_format($dato['monto'],2)."</td>
						<td><input type='radio' id='selectorCuenta' name='selectorCuenta' 
						value='$i' onclick='selectorPago(this);' /></td>
						<td style='display:none'>$dato[id]</td>
					   </tr>
					   ";
				$i++;	   
			 }
		 }
	  }
	   exit();
	}

	if ($tipo == "insertar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));	
		$numero = $db->getNextID("numero","ingreso where idsucursal='$_GET[sucursal]'");	
		$tDolares = 0;
		if ($_GET['ingresoDolares'] > 0){
		  $tDolares = round(($_GET['ingresoDolares'] * $_GET['tipocambio']),4);	
		}
		
		$sql = "insert into ingreso values(null,'".$numero."','$fecha','"
		.filtro($_GET['cuenta'])."','".filtro($_GET['cheque']).
		"','".filtro($_GET['idpersona'])."','".
		filtro($_GET['tipopersona'])."','".filtro($_GET['nombrepersona'])."','".filtro($_GET['recibo'])
		."','".filtro($_GET['sucursal'])."','".filtro($_GET['ingresobs'])
		."','".filtro($tDolares)."','".filtro($_GET['glosa'])."','".filtro($_GET['tipocambio'])
		."','".$_SESSION['id_usuario']."',1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idingreso","ingreso");
		
		$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		$tc = $db->getCampo('dolarcompra',$sql); 
		
		$sql = "select ingdirecimprimir,inghablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);
		
		 if ($dato['inghablibrodiario'] == "1") {
		  $idlibro = insertarLibro(filtro($_GET['sucursal']),'Bolivianos'
		  ,$fecha,$codigo,$tc,$_SESSION['id_usuario'],$db,filtro($_GET['glosa']));
		  $detalleLibro = getDetalleLibro($db,filtro($_GET['sucursal'])
		  ,$codigo,filtro($_GET['tipopersona']),filtro($_GET['nombrepersona']));	
		  insertarDetalle($db,$idlibro,filtro($_GET['cuenta']),$detalleLibro
		  ,(filtro($_GET['ingresobs'])+$tDolares),0,filtro($_GET['recibo']));
		 }
		
		$tipoC = $_GET['tipocambio'];
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		  for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$cuenta = filtro2($fila[0]);
			$descripcion = filtro2($fila[1]);
			$totalbs = filtro2(desconvertir($fila[2]));
			$totaldolares = filtro2(desconvertir($fila[3]));
			$totalD = round(($totaldolares * $tipoC),4);
			$transaccionPago= $fila[4];
			$idtransaccionPago = $fila[5];
			
			if ($transaccionPago == "Nota Venta Producto") {
			  $sql = "update notaventa set montoactualcredito=montoactualcredito+".($totalbs+$totalD).
			  " where idnotaventa=".$idtransaccionPago." and tiponota='productos';";
			  $db->consulta($sql);
			}
			
			if ($transaccionPago == "Nota Venta Servicios") {
			  $sql = "update notaventa set montoactualcredito=montoactualcredito+".($totalbs+$totalD).
			  " where idnotaventa=".$idtransaccionPago." and tiponota='servicios';";
			  $db->consulta($sql);
			}
			
			if ($transaccionPago == "Cuenta Por Cobrar") {
			  $sql = "update cuentaporcobrar set montoactualcobrado=montoactualcobrado+".($totalbs+$totalD).
			  " where idporcobrar=".$idtransaccionPago.";";
			  $db->consulta($sql);
			}
					
			$consulta = "insert into detalleingreso values(null,'$codigo','$cuenta','$descripcion','$totalbs'
			,'$totalD','$transaccionPago','$idtransaccionPago');";
			$db->consulta($consulta);	
			if ($dato['inghablibrodiario'] == "1"){
			  insertarDetalle($db,$idlibro,$cuenta,$detalleLibro,0,($totalbs+$totalD),$_GET['recibo']);			
			}
		  }	
		echo $codigo."---";  
		echo $dato['ingdirecimprimir']."---";
		exit();  
		
	}

	function getDetalleLibro($db, $sucursal, $codigo, $tipopersona, $nombrepersona) 
	{	
		$sql = "select * from sucursal where idsucursal=$sucursal";
		$datosSucursal = $db->arrayConsulta($sql);
		$descripcionLibro = "Ingreso Dinero Nº $codigo/$tipopersona: $nombrepersona
		/Sucursal: $datosSucursal[nombrecomercial]";
		return $descripcionLibro;	
	}
	
	
	function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $db, $glosa)
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
		'$num[num]','$num[sucursal]','$moneda','ingreso','$fecha','$glosa'
		,'$codigo','$tc','$usuario',1,'Ingreso dinero');"; 
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
		$sql = "select idlibrodiario,idsucursal from librodiario 
		where transaccion='Ingreso dinero' and idtransaccion=$codigo;";  
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
		return $libro['idlibrodiario'];
	}

	if ($tipo == "modificar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
		$ingreso = filtro($_GET['idingreso']);	
		$tDolares = 0;
		if ($_GET['ingresoDolares'] > 0){
		    $tDolares = round(($_GET['ingresoDolares'] * $_GET['tipocambio']), 4);	
		}
		$sql = "update ingreso set fecha='$fecha',cuenta='".filtro($_GET['cuenta'])
		."',cheque='".filtro($_GET['cheque'])."',nombrepersona='".filtro($_GET['nombrepersona'])."',
		idpersona='".filtro($_GET['idpersona'])."',tipopersona='".filtro($_GET['tipopersona'])."',
		recibo='".filtro($_GET['recibo'])."',idsucursal='".filtro($_GET['sucursal'])
		."',ingresoBolivianos='".filtro($_GET['ingresobs'])."',ingresoDolares='".
		filtro($tDolares)."',glosa='".filtro($_GET['glosa'])."',idusuario='$_SESSION[id_usuario]'
		  where idingreso=$ingreso";
		$db->consulta($sql);	
	   
		$tc = $_GET['tipocambio']; 
		
		$sql = "select ingdirecimprimir,inghablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);
		if ($dato['inghablibrodiario'] == "1") {	
		   $idlibro = modificarLibro(filtro($_GET['sucursal']),'Bolivianos'
		   ,$fecha,filtro($ingreso),$tc,$_SESSION['id_usuario'],$db,filtro($_GET['glosa']));
		   $detalleLibro = getDetalleLibro($db,filtro($_GET['sucursal']),$codigo
		   ,filtro($_GET['tipopersona']),filtro($_GET['nombrepersona']));		
		   $sql = "delete from detallelibrodiario where idlibro=$idlibro";
		   $db->consulta($sql);	
		   insertarDetalle($db,$idlibro,filtro($_GET['cuenta']),$detalleLibro
		   ,(filtro($_GET['ingresobs'])+$tDolares),0,filtro($_GET['recibo']));
		}
		
		$sql = "select *from detalleingreso where idingreso=$ingreso";
		$dConsulta = $db->consulta($sql);
		while ($data = mysql_fetch_array($dConsulta)) {
			if ($data['transaccion'] == "Nota Venta Producto") {
				$sql = "update notaventa set montoactualcredito=montoactualcredito-"
				.($data['montobolivianos']+($data['montodolares']*$tc)).
				  " where idnotaventa=".$data['idtransaccion'].";";
				$db->consulta($sql);
			}
			
			if ($data['transaccion'] == "Nota Venta Servicios") {
				$sql = "update notaventa set montoactualcredito=montoactualcredito-"
				.($data['montobolivianos']+($data['montodolares']*$tc)).
				  " where idnotaventa=".$data['idtransaccion'].";";
				$db->consulta($sql);
			}
			
			if ($data['transaccion'] == "Cuenta Por Cobrar") {
				$sql = "update cuentaporcobrar set montoactualcobrado=montoactualcobrado-"
				.($data['montobolivianos']+($data['montodolares']*$tc)).
				" where idporcobrar=".$data['idtransaccion'].";";
				$db->consulta($sql);
			}		
			
		}	
		
		$sql = "delete from detalleingreso where idingreso=$ingreso";
		$db->consulta($sql);
		$tipoC = $_GET['tipocambio'];
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$cuenta = filtro2($fila[0]);
			$descripcion = filtro2($fila[1]);
			$totalbs = filtro2(desconvertir($fila[2]));
			$totaldolares = filtro2(desconvertir($fila[3]));
			$totalD = round(($totaldolares * $tipoC),4);
			$transaccionPago = $fila[4];
			$idtransaccionPago = $fila[5];
			
			if ($transaccionPago == "Nota Venta Producto") {
			  $sql = "update notaventa set montoactualcredito=montoactualcredito+".($totalbs+$totalD).
			  " where idnotaventa=".$idtransaccionPago." and tiponota='productos';";
			  $db->consulta($sql);
			}
			
			if ($transaccionPago == "Nota Venta Servicios") {
			  $sql = "update notaventa set montoactualcredito=montoactualcredito+".($totalbs+$totalD).
			  " where idnotaventa=".$idtransaccionPago." and tiponota='servicios';";
			  $db->consulta($sql);
			}
			
			if ($transaccionPago == "Cuenta Por Cobrar") {
			  $sql = "update cuentaporcobrar set montoactualcobrado=montoactualcobrado+".($totalbs+$totalD).
			  " where idporcobrar=".$idtransaccionPago.";";
			  $db->consulta($sql);
			}
							
			$consulta = "insert into detalleingreso values(null,'$ingreso','$cuenta','$descripcion','$totalbs'
			,'$totalD','$transaccionPago','$idtransaccionPago');";
			$db->consulta($consulta);	
			if ($dato['inghablibrodiario'] == "1") {		
			   insertarDetalle($db,$idlibro,$cuenta,$detalleLibro,0,($totalbs+$totalD),filtro($_GET['recibo']));			
			}
		}	
		echo $ingreso."---";  
		echo $dato['ingdirecimprimir']."---";
		exit();  
	}


?>