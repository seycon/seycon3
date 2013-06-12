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
	
	if ($tipo == "cuenta") {
		$tipocuenta = $_GET['tipo'];
		$sql = "select c.* from usuario u,cajero c where c.idtrabajador=u.idtrabajador 
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

	if ($tipo == "cuentaTrabajador") {
		$idtrabajador = $_GET['idtrabajador'];
		$sql = "select left(concat(t.nombre,' ',t.apellido),40)as 'nombre',c.* from trabajador t,
		cajero c where c.idtrabajador=t.idtrabajador and t.idtrabajador=$idtrabajador;";
		$cuenta = $db->arrayConsulta($sql);
		if (isset($cuenta['nombre'])) {
			echo $cuenta['nombre']."---";
			echo "<option value='' selected='selected'>-- Seleccione --</option>";
			if ($cuenta['cuentacaja1']!= "" && $cuenta['textocaja1'] != "")  
			  echo "<option value='$cuenta[cuentacaja1]'>$cuenta[textocaja1]</option>";  
			if ($cuenta['cuentacaja2']!= "" && $cuenta['textocaja2'] != "")  
			  echo "<option value='$cuenta[cuentacaja2]'>$cuenta[textocaja2]</option>"; 
			if ($cuenta['cuentacaja3']!= "" && $cuenta['textocaja3'] != "")  
			  echo "<option value='$cuenta[cuentacaja3]'>$cuenta[textocaja3]</option>"; 
			if ($cuenta['cuentacaja4']!= "" && $cuenta['textocaja4'] != "")  
			  echo "<option value='$cuenta[cuentacaja4]'>$cuenta[textocaja4]</option>"; 
			if ($cuenta['cuentacaja5']!= "" && $cuenta['textocaja5'] != "")  
			  echo "<option value='$cuenta[cuentacaja5]'>$cuenta[textocaja5]</option>"; 
			if ($cuenta['cuentacaja6']!= "" && $cuenta['textocaja6'] != "")  
			  echo "<option value='$cuenta[cuentacaja6]'>$cuenta[textocaja6]</option>";    
			if ($cuenta['cuentabanco1']!= "" && $cuenta['textobanco1'] != "")    
			  echo "<option value='$cuenta[cuentabanco1]'>$cuenta[textobanco1]</option>"; 
			if ($cuenta['cuentabanco2']!= "" && $cuenta['textobanco2'] != "")    
			  echo "<option value='$cuenta[cuentabanco2]'>$cuenta[textobanco2]</option>"; 
			if ($cuenta['cuentabanco3']!= "" && $cuenta['textobanco3'] != "")    
			  echo "<option value='$cuenta[cuentabanco3]'>$cuenta[textobanco3]</option>";
			if ($cuenta['cuentabanco4']!= "" && $cuenta['textobanco4'] != "")    
			  echo "<option value='$cuenta[cuentabanco4]'>$cuenta[textobanco4]</option>"; 
			if ($cuenta['cuentabanco5']!= "" && $cuenta['textobanco5'] != "")    
			  echo "<option value='$cuenta[cuentabanco5]'>$cuenta[textobanco5]</option>"; 
			if ($cuenta['cuentabanco6']!= "" && $cuenta['textobanco6'] != "")    
			  echo "<option value='$cuenta[cuentabanco6]'>$cuenta[textobanco6]</option>";       
						
		} else {
			echo "---";
			echo "<option value='' selected='selected'>- Seleccione --</option>";	
		}
		
	}


	if ($tipo == "pendientes") {
	   $iddeudor = $_GET['iddeudor'];
	   $tipodeudor = $_GET['tipodeudor'];
	   $sql = "select date_format(c.fecha,'%d/%m/%Y')as 'fecha',left(p.cuenta,20)as 'cuenta',
	   p.codigo,left(c.glosa,20)as 'detalle',c.glosa,round(c.monto,2)as 'monto' 
	   from cuentaporcobrar c,plandecuenta p where 
	   p.codigo=c.cuenta and tipodeudor='$tipodeudor' and iddeudor=$iddeudor and c.estado=1;";	
	   $cuenta = $db->consulta($sql);
	   $i = 1;
	   while ($dato = mysql_fetch_array($cuenta)) {
		   echo "
				   <tr bgColor='#E6E6E6'>
					<td align='center'>$i</td>
					<td align='center'>$dato[fecha]</td>
					<td align='center'>$dato[cuenta]</td>
					<td style='display:none'>$dato[codigo]</td>
					<td style='display:none'>$dato[glosa]</td>
					<td>$dato[detalle]</td>
					<td>".convertir($dato['monto'])."</td>
					<td><input type='radio' id='selectorCuenta' name='selectorCuenta' value='$i' /></td>
				   </tr>
				   ";
			$i++;	   
	   }
	   exit();
	}

	if ($tipo == "insertar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));	
		$numero = $db->getNextID("numero","ingreso where idsucursal='$_GET[sucursal]'");	
		$tDolares = 0;
		if ($_GET['ingresoDolares'] > 0) {
			$tDolares = round(($_GET['ingresoDolares'] * $_GET['tipocambio']),4);	
		}	
		$sql = "insert into traspasodinero values(null,'$numero','$fecha','"
		.filtro($_GET['cuenta'])."','".filtro($_GET['cheque'])
		."','".filtro($_GET['idpersona'])."','".filtro($_GET['tipopersona'])."',
		'".filtro($_GET['nombrepersona'])."','".filtro($_GET['recibo'])."','".filtro($_GET['sucursal'])
		."','".filtro($_GET['ingresobs'])."','".filtro($tDolares)
		."','".filtro($_GET['glosa'])."','".filtro($_GET['tipocambio'])."','$_SESSION[id_usuario]',1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idtraspaso","traspasodinero");
		
		 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		 $tc = $db->getCampo('dolarcompra',$sql); 
		
		$sql = "select trapasodineroimprimir,trapdinerohablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);
		if ($dato['trapdinerohablibrodiario'] == "1") {  
			$idlibro = insertarLibro(filtro($_GET['sucursal']),'Bolivianos',$fecha,$codigo,$tc
			,filtro($_SESSION['id_usuario']),$db,filtro($_GET['glosa']));
			$detalleLibro = getDetalleLibro($db,filtro($_GET['sucursal']),$codigo
			,filtro($_GET['tipopersona']),filtro($_GET['nombrepersona']));	
		}
		$tipoC = $_GET['tipocambio'];
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$cuenta = filtro($fila[0]);
			$totalbs = filtro(desconvertir($fila[1]));
			$totaldolares = filtro(desconvertir($fila[2]));
			$trabajador = filtro($fila[3]);
			$totalD = round(($totaldolares * $tipoC),4);
			$consulta = "insert into detalletraspasodinero values(null,'$codigo'
			,'$cuenta','$trabajador','$totalbs','$totalD');";
			$db->consulta($consulta);	
			if ($dato['trapdinerohablibrodiario'] == "1") {
				insertarDetalle($db,$idlibro,$cuenta,$detalleLibro,($totalbs+$totalD),0,$_GET['recibo']);				
			}
		}	
		if ($dato['trapdinerohablibrodiario'] == "1") {
			insertarDetalle($db,$idlibro,$_GET['cuenta'],$detalleLibro,0
			,($_GET['ingresobs']+$tDolares),$_GET['recibo']);
		}
		echo $codigo."---";
		echo $dato['trapasodineroimprimir']."---";  
		exit();  
		
	}


	function getDetalleLibro($db, $sucursal, $codigo, $tipopersona, $nombrepersona)
	{	
		$sql = "select * from sucursal where idsucursal=$sucursal";
		$datosSucursal = $db->arrayConsulta($sql);
		$descripcionLibro = "Traspaso Dinero Nº $codigo/$tipopersona: 
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
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha
		,glosa,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','traspaso','$fecha'
		,'$glosa','$codigo','$tc','$usuario',1,'Traspaso dinero');"; 
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
		where transaccion='Traspaso dinero' and idtransaccion=$codigo;";  
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
		$ingreso = filtro($_GET['idtransaccion']);	
		$tDolares = 0;
		if ($_GET['ingresoDolares'] > 0) {
			$tDolares = round(($_GET['ingresoDolares'] * $_GET['tipocambio']),4);	
		}
		$sql = "update traspasodinero set fecha='$fecha',cuenta='"
		.filtro($_GET['cuenta'])."',cheque='".filtro($_GET['cheque'])."',nombrepersona='"
		.filtro($_GET['nombrepersona'])."',idpersona='".filtro($_GET['idpersona'])
		."',tipopersona='".filtro($_GET['tipopersona'])."',
		recibo='".filtro($_GET['recibo'])."',idsucursal='"
		.filtro($_GET['sucursal'])."',ingresoBolivianos='".filtro($_GET['ingresobs'])
		."',ingresoDolares='".filtro($tDolares)."',glosa='"
		.filtro($_GET['glosa'])."',idusuario='$_SESSION[id_usuario]'  where idtraspaso=$ingreso";
		$db->consulta($sql);
		
		$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		$tc = $db->getCampo('dolarcompra',$sql); 
		
		$sql = "select trapasodineroimprimir,trapdinerohablibrodiario from impresion;";  
		$dato = $db->arrayConsulta($sql);
		if ($dato['trapdinerohablibrodiario'] == "1") {
		   $idlibro = modificarLibro(filtro($_GET['sucursal']),'Bolivianos',$fecha,$ingreso
		   ,$tc,$_SESSION['id_usuario'],$db,filtro($_GET['glosa']));
		   $detalleLibro = getDetalleLibro($db,filtro($_GET['sucursal']),$codigo
		   ,filtro($_GET['tipopersona']),filtro($_GET['nombrepersona']));
		   $sql = "delete from detallelibrodiario where idlibro=$idlibro";
		   $db->consulta($sql);	
		}
			
		$sql = "delete from detalletraspasodinero where idtraspaso=$ingreso";
		$db->consulta($sql);
		$tipoC = $_GET['tipocambio'];
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$cuenta = filtro($fila[0]);
			$totalbs = filtro(desconvertir($fila[1]));
			$totaldolares = filtro(desconvertir($fila[2]));
			$totalD = round(($totaldolares * $tipoC),4);
			$trabajador = filtro($fila[3]);
			$consulta = "insert into detalletraspasodinero values(null,'$ingreso'
			,'$cuenta','$trabajador','$totalbs','$totalD');";
			$db->consulta($consulta);
			if ($dato['trapdinerohablibrodiario'] == "1") {
			   insertarDetalle($db,$idlibro,$cuenta,$detalleLibro,$totalbs,0,filtro($_GET['recibo']));				
			}
		  }	
		  if ($dato['trapdinerohablibrodiario'] == "1") {
			insertarDetalle($db,$idlibro,filtro($_GET['cuenta']),$detalleLibro,0
			,(filtro($_GET['ingresobs'])+$tDolares),filtro($_GET['recibo']));  
		}
		echo $ingreso."---";
		echo $dato['trapasodineroimprimir']."---";  
		exit();  
	}

?>