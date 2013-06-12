<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}

   class Dventa {
        
		public $db;
		public function Dventa()
		{
			include("../conexion.php");
			$this->db = new MySQL; 
		}
	
		function filtro($cadena)
		{
		  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
		}
	
	    public function desconvertir($cadena)
		{
			$convertir = "";     
			for($i = 0; $i < strlen($cadena); $i++) {		
				if ($cadena[$i]!=",")
					$convertir = $convertir.$cadena[$i];
		
			}
			return $convertir;
		}	
	
		public function getVendedor()
		{
			$cliente = $_GET['idcliente'];	
			$sql = "select r.idruta,left(r.nombre,15)as 'nombre',c.nit,c.nombrenit  
			 from cliente c,ruta r where c.ruta=r.idruta
			 and c.idcliente=$cliente;";	
			$resultado = $this->db->arrayConsulta($sql);
			echo $resultado['idruta']."---";
			echo $resultado['nombre']."---";
			echo $resultado['nit']."---";
			echo $resultado['nombrenit']."---";
			exit();
			
		}
		
		public function getAlmacen()
		{
		  $sql = "select * from almacen where sucursal=$_GET[idsucursal] and estado=1";
		  $dato = $this->db->consulta($sql);
			  while($lote = mysql_fetch_array($dato)) {
				echo "<option value='$lote[idalmacen]'>$lote[nombre]</option>";	
				$i++;
			  }
		  echo "---";
		  $this->getFactura();	  
		  exit();		
		}
	
		public function getFactura()
		{
			 $sql = "select case when fechalimitemision<=current_date 
				 then 'si' else 'no' end as 'limite',
				 case when numfacturaactual>numfactfinal
				 then 'no' else 'si' end as 'emite',numautorizacion,numfacturaactual
				  from sucursal where idsucursal = '$_GET[idsucursal]'";
			 $sucursal = $this->db->arrayConsulta($sql);
			 if ($sucursal['limite'] == "si" || $sucursal['emite'] == "no") {
				  if ($sucursal['limite'] == "si") {
					  echo "fecha"."---";
				  } else {
					  echo "numero"."---";
				  }
			  } else {
			   echo $sucursal['numfacturaactual']."---";
			 }
			 exit();
		}
	
		public function getdatoProducto()
		{
			$consulta = "select * from producto WHERE idproducto =".$_GET['codigo'];
			$respuesta = $this->db->arrayConsulta($consulta);
			echo $respuesta['unidaddemedida']."---";
			echo $respuesta['unidadalternativa']."---";
			echo $respuesta['conversiones']."---";
			echo $respuesta[$_GET['tipoprecio']]."---";
			$sql = "SELECT DATE_FORMAT( dp.fecha,  '%d/%m/%Y' ) AS  'fecha'
					, lote, cantidadactual as 'cantidad', unidadmedida,dp.iddetalleingreso 
					FROM detalleingresoproducto dp, ingresoproducto i
					WHERE dp.idingresoprod = i.idingresoprod
					AND i.idalmacen =$_GET[idalmacen]
					AND dp.cantidadactual >0
					AND dp.idproducto =$_GET[codigo] 
					and dp.estado=1 and i.estado=1 
					ORDER BY dp.lote;";
			$dato = $this->db->consulta($sql);
			$i = 0;
			while ($lote = mysql_fetch_array($dato)) {
				echo "<option value='$i'>$lote[lote]</option>";	
				$i++;
			}
			echo "---";
			$dato = $this->db->consulta($sql);
			while($lote = mysql_fetch_array($dato)) {
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
			exit();
		}
	
		public function insertar()
		{
			$fecha = $this->filtro($this->db->GetFormatofecha($_GET['fecha'],"/"));
			$diasCredito = ($_GET['diascredito']=="") ? 0 : $this->filtro($_GET['diascredito']);	
			$sql = "select DATE_ADD('$fecha',INTERVAL $diasCredito DAY)as 'limite'";
			$fechaC = $this->db->arrayConsulta($sql);
			$sql = "select max(numero)+1 as 'nro' from notaventa where tiponota='productos';";
			$nroVenta = $this->db->arrayConsulta($sql);
			$fechaEntrega = $this->db->GetFormatofecha($_GET['fechaentrega'], "/");
			
			if ($nroVenta['nro'] == "") {
				$nroVenta['nro'] = 1;	
			}
			
			$sql = "update cliente set nit='".$this->filtro($_GET['nit'])."',nombrenit='".$this->filtro($_GET['nombrenit'])
			."' where idcliente=".$this->filtro($_GET['cliente'])."";
			$this->db->consulta($sql);
			$sql = "insert into notaventa values(null,'$nroVenta[nro]','".$this->filtro($_GET['cliente'])
			."','".$this->filtro($_GET['factura'])."','$fecha','"
			.$this->filtro($_GET['descuento'])."','".
			$this->filtro($_GET['recargo'])."','".$this->filtro($_GET['moneda'])."','".$this->filtro($_GET['glosa'])
			."','$fechaC[limite]','".$this->filtro($_GET['caja'])."','$diasCredito','".
			$this->filtro($_GET['monto'])."','".$this->filtro($_GET['tipocambio'])."','".$this->filtro($_GET['precio'])
			."','productos','".$this->filtro($_GET['sucursal']).
			"','".$this->filtro($_GET['cambio'])."',0,'$fechaEntrega',$_SESSION[id_usuario],1)";
			$this->db->consulta($sql);			
			
			$sql = "select numfacturaactual,numautorizacion from sucursal 
			where idsucursal='$_GET[sucursal]' and estado=1";
			$datosFac = $this->db->arrayConsulta($sql);
			if ($datosFac['numfacturaactual'] == $_GET['factura']) {
			   $sql = "update sucursal set numfacturaactual=numfacturaactual+1
			    where idsucursal='$_GET[sucursal]'";
			   $this->db->consulta($sql);
			}
			
			$costoProductos = 0;
			$codigo = $this->db->getMaxCampo("idnotaventa", "notaventa");
			$datos =  json_decode(stripcslashes($_GET['detalle']));  			
			  for ($i = 0; $i < count($datos); $i++) {
				$fila = $datos[$i];                 
				$id = $this->filtro($fila[0]);
				$lote = $this->filtro($fila[1]);
				$fechav = $this->filtro($this->db->GetFormatofecha($fila[2],"/"));
				$cantidad = $this->filtro($fila[3]);
				$um= $this->filtro($fila[4]);		
				$precio = $this->filtro($this->desconvertir($fila[5]));
				$total = $this->filtro($this->desconvertir($fila[6]));
				if ($_GET['moneda'] == "Dolares") {
					$total = round(($total * $_GET['tipocambio']),4);
					$precio = round(($precio * $_GET['tipocambio']),4);
				}		
				
				$iddetalle = $this->filtro($fila[7]);		
				$sql = "select precio from detalleingresoproducto where iddetalleingreso=$iddetalle";
				$precioCompra = $this->db->arrayConsulta($sql);
				$costoProductos = $costoProductos + ($precioCompra['precio'] * $cantidad);
				
				$consulta = "insert into detallenotaventa values(null,$iddetalle,'$id','$codigo','$lote'
				,'$fechav','$cantidad','$um','$precio','$total');";
				$this->db->consulta($consulta);	
				$sql = "select dp.cantidadactual,dp.unidadmedida,p.unidaddemedida as 'UM'
				,p.unidadalternativa as 'UA',p.conversiones 
				from detalleingresoproducto dp,producto p where 
				dp.iddetalleingreso=$iddetalle and dp.idproducto=p.idproducto;";
				$saliente = 0;
				$datosingreso = $this->db->arrayConsulta($sql);
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
				di.cantidadactual=di.cantidadactual-$saliente where di.iddetalleingreso=$iddetalle;";
				$this->db->consulta($sql);			
			  }	
			  
		   $sql = "select nvimprimirfactura,nvlibrodiario from impresion;";  
		   $dato = $this->db->arrayConsulta($sql);
		   if ($dato['nvlibrodiario'] == "1") {	  
			  if ($_GET['moneda'] == "Dolares") {
					$_GET['subtotal'] = round(($_GET['subtotal'] * $_GET['tipocambio']),4);
			  }	
		   
			  $this->insertarLibro($this->filtro($_GET['sucursal']),$this->filtro($_GET['moneda']),$fecha,$codigo
			  ,$this->filtro($_GET['tipocambio']),$_SESSION['id_usuario']
			  ,$this->filtro($_GET['subtotal']),$db,$this->filtro($_GET['caja'])
			  ,$this->filtro($_GET['descuento']),$this->filtro($_GET['recargo'])
			  ,$this->filtro($_GET['glosa']),$this->filtro($_GET['cliente']),$this->filtro($_GET['factura'])
			  ,$this->filtro($_GET['cambio']),$costoProductos);
		   }
			  
		   if ($_GET['factura'] != "" && $_GET['factura'] >= 0){  
			 $this->insertarLibroVentas($_GET['sucursal'], $fecha, $_GET['nit'], $_GET['nombrenit']
			 , $_GET['factura'], $datosFac['numautorizacion']
			 , $_GET['monto'], $codigo, $db);	  
		   }		  
			  
			echo $codigo."---";
			echo $dato['nvimprimirfactura']."---";		
			exit();  
		}
	
	
		function insertarLibroVentas($sucursal, $fecha, $nit, $nombrenit, $factura
									  , $numautorizacion, $total, $codigo, $db)
		{
			$sql = "select porcentajedebitofiscal as 'iva' from configuracioncontable";
			$datosSistema = $this->db->arrayConsulta($sql);
			$iva = round((($datosSistema['iva']/100) * $total),2);
			
			$sql = "select idlibroventasiva from libroventasiva where idtransaccion=$codigo
			 and transaccion='Venta Productos' and estado=1";
			$datosLibro = $this->db->arrayConsulta($sql);
			if ($datosLibro['idlibroventasiva'] == "") {	
				$sql = "insert into libroventasiva(folio,fechadeemision,numcinitcliente,nomrazonsocicliente,numfactura
				,numautorizacion,codigodecontrol,
				totalfactura,totalice,importeexcento,importeneto,debitofiscal
				,idtransaccion,transaccion,tipo,estadofactura,idcuenta,tipocuenta,idusuario,estado)
				values ('$sucursal','$fecha','$nit','$nombrenit','$factura','$numautorizacion',
				'0','$total','0','0','$total','$iva',$codigo,'Venta Productos','VP',
				'V','','productos','$_SESSION[id_usuario]',1)";
			} else {
				$sql = "update libroventasiva set folio='$sucursal',fechadeemision='$fecha',
				numcinitcliente='$nit',nomrazonsocicliente='$nombrenit',numfactura='$factura'
				,numautorizacion='$numautorizacion',tipocuenta='productos',
				totalfactura='$total',importeneto='$total',debitofiscal='$iva',idusuario='$_SESSION[id_usuario]' 
				where idlibroventasiva=$datosLibro[idlibroventasiva];";
			}
			$this->db->consulta($sql);
		}
	
	
		function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario
								,$monto, $db, $cuentacaja, $descuento, $recargo
								  ,$glosa, $cliente, $factura, $cambio, $costoP)
		{
			$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' from librodiario l where 
			l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
			$num = $this->db->arrayConsulta($sql); 
			if (!isset($num['num'])){
			  $num['num'] = 1;
			  $num['sucursal'] = $sucursal;
			}	
				
			$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa
			,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
			'$num[num]','$num[sucursal]','$moneda','ingreso','$fecha','$glosa'
			,'$codigo','$tc','$usuario',1,'Nota Venta Productos');"; 
			$this->db->consulta($sql);
			
			$sql = "select * from sucursal where idsucursal=$sucursal";
			$datosSucursal = $this->db->arrayConsulta($sql);
			$sql = "select *from cliente where idcliente=$cliente;";
			$datoCliente = $this->db->arrayConsulta($sql);	
			$descripcionLibro = "Nº $codigo/Cliente: $datoCliente[nombre]/Sucursal:
			 $datosSucursal[nombrecomercial]";
			$libro = $this->db->getMaxCampo("idlibrodiario","librodiario");
			$this->setDetalleLibro($db,$monto,$cuentacaja,$factura,$descripcionLibro,$recargo
			,$descuento,$cambio,$cliente,$costoP,$libro);
		}
	
		function modificarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $monto, $db
						  , $cuentacaja, $descuento, $recargo, $glosa, $cliente, $factura, $cambio, $costoP) {
			$sql = "select idlibrodiario,idsucursal from librodiario where 
			transaccion='Nota Venta Productos' and idtransaccion=$codigo;";  
			$libro = $this->db->arrayConsulta($sql); 
		 
			if ($libro['idsucursal'] != $sucursal) {
				$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' 
				from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";
				$num = $this->db->arrayConsulta($sql);  	
				  if (!isset($num['num'])){
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
			$this->db->consulta($sql);
			$sql = "delete from detallelibrodiario where idlibro=$libro[idlibrodiario]";
			$this->db->consulta($sql);
			$sql = "select * from sucursal where idsucursal=$sucursal";
			$datosSucursal = $this->db->arrayConsulta($sql);
			$sql = "select *from cliente where idcliente=$cliente;";
			$datoCliente = $this->db->arrayConsulta($sql);	
			$descripcionLibro = "Nº $codigo/Cliente: $datoCliente[nombre]/Sucursal: $datosSucursal[nombrecomercial]";
			$this->setDetalleLibro($db,$monto,$cuentacaja,$factura,$descripcionLibro
			,$recargo,$descuento,$cambio,$cliente,$costoP,$libro['idlibrodiario']);	
		}
	
		function setFacturado($libro, $contabilidad, $monto, $descripcion, $factura, $db)
		{
		   $porcentaje = (100 - $contabilidad['porcentajedebitofiscal']) / 100;	
		   $montoVenta = $porcentaje * $monto;	
		   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values
		   ($libro,'$contabilidad[cuentalcvproductos]','$descripcion',0,$montoVenta,'$factura')";
		   $this->db->consulta($sql);
		   $porcentaje = $contabilidad['porcentajedebitofiscal'] / 100;
		   $montodebito = $porcentaje * $monto;
		   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values
		   ($libro,'$contabilidad[debitofiscal]','$descripcion',0,$montodebito,'$factura')";
		   $this->db->consulta($sql);
		}
	
		function setSinFactura($libro, $contabilidad, $monto, $descripcion, $db) 
		{
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			values($libro,'$contabilidad[cuentalcvproductos]','$descripcion',0,$monto,'')";
			$this->db->consulta($sql);
		}
		
		function setCuentasProductos($libro, $contabilidad, $monto, $descripcion, $db, $factura, $costoP)
		{
			$costo = $costoP;
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			values($libro,'$contabilidad[costoventa]','$descripcion',$costo,0,'$factura')";
			$this->db->consulta($sql);
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			values($libro,'$contabilidad[inventario]','$descripcion',0,$costo,'$factura')";
			$this->db->consulta($sql);	
		}
	
		function setImpuestosTransacciones($libro, $contabilidad, $monto, $descripcion, $db, $factura)
		{
		  if ($factura != "") {	
			$porcentaje = $contabilidad['porcentajeitgastos'] / 100;
			$montogastos = $porcentaje * $monto;
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			values($libro,'$contabilidad[itgastos]','$descripcion',$montogastos,0,'$factura')";
			$this->db->consulta($sql);
			$porcentaje = $contabilidad['porcentajeitpasivo'] / 100;
			$montopasivo = $porcentaje * $monto;
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			values($libro,'$contabilidad[itpasivo]','$descripcion',0,$montopasivo,'$factura')";
			$this->db->consulta($sql);
		  }
		}
		
		function setEfectivoCredito($db, $monto, $cambio, $cliente, $libro
									, $cuentacaja, $factura, $descripcion, $recargo, $descuento) 
		{
			  $monto = $monto + (($recargo / 100) * $monto) - (($descuento / 100) * $monto);
			  $efectivo = $monto - $cambio;
			  $credito = $monto - $efectivo;	
			  $sql = "select *from configuracioncontable";
			  $datocliente = $this->db->arrayConsulta($sql);	
			  if ($efectivo >  0) {
				  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
					values($libro,'$cuentacaja','$descripcion',$efectivo,0,'$factura')";
				  $this->db->consulta($sql);
			  }
			  if ($credito >  0) {
				  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
				  values($libro,'$datocliente[clientescobrar]','$descripcion',$credito,0,'$factura')";
				  $this->db->consulta($sql);
			  }
		}
	
		 function setRecargo($libro, $contabilidad, $monto, $recargo, $descripcion, $db, $factura)
		 {
			if ($recargo != "" && $recargo > 0) { 
			   $recargo = ($recargo/100) * $monto;
			   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			   values($libro,'$contabilidad[recargo]','$descripcion',0,$recargo,'$factura')";
			   $this->db->consulta($sql);
			}
		 }
	
		 function setDescuento($libro, $contabilidad, $monto, $descuento, $descripcion, $db, $factura)
		 {
			if ($descuento != "" && $descuento > 0){ 
			   $descuento = ($descuento/100) * $monto;
			   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values
			   ($libro,'$contabilidad[descuentoventa]','$descripcion',$descuento,0,'$factura')";
			   $this->db->consulta($sql);
			}
		 }
	
	
		function setDetalleLibro($db, $monto, $cuentacaja, $factura, $descripcion
								  , $recargo, $descuento, $cambio, $cliente, $costoP, $idlibro) {
			$libro = $idlibro; 
			$sql = "select *from configuracioncontable;";
			$contabilidad = $this->db->arrayConsulta($sql);	
			$total = $monto + (($recargo/100)*$monto) - (($descuento/100) * $monto);
			
			if ( $cambio != "" && $cambio > 0) {
			  $this->setEfectivoCredito($db,$monto,$cambio,$cliente,$libro,$cuentacaja,$factura,$descripcion,$recargo,$descuento);
			} else {
			  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			   values($libro,'$cuentacaja','$descripcion',$total,0,'$factura')";
			  $this->db->consulta($sql);
			}	
			
			$this->setDescuento($libro, $contabilidad, $monto, $descuento, $descripcion, $db, $factura);
			$this->setRecargo($libro,$contabilidad,$monto,$recargo,$descripcion,$db,$factura);
			if ($factura != "") {
			  $this->setFacturado($libro,$contabilidad,$monto,$descripcion,$factura,$db);
			} else {
			  $this->setSinFactura($libro,$contabilidad,$monto,$descripcion,$db);
			}
			$this->setCuentasProductos($libro,$contabilidad,$monto,$descripcion,$db,$factura,$costoP);
			$this->setImpuestosTransacciones($libro,$contabilidad,$total,$descripcion,$db,$factura);
		}
	
		public function modificar()
		{
			$nota = $this->filtro($_GET['idnota']);
			$fecha = $this->filtro($this->db->GetFormatofecha($_GET['fecha'],"/"));
			$diasCredito = ($_GET['diascredito']=="") ? 0 : $this->filtro($_GET['diascredito']);
			$sql = "select DATE_ADD('$fecha',INTERVAL $diasCredito DAY)as 'limite'";
			$fechaC = $this->db->arrayConsulta($sql);
			$sql = "update cliente set nit='".$this->filtro($_GET['nit'])."',nombrenit='".$this->filtro($_GET['nombrenit'])
			."' where idcliente=".$this->filtro($_GET['cliente'])."";
			$this->db->consulta($sql);
			$fechaEntrega = $this->db->GetFormatofecha($_GET['fechaentrega'], "/");
			$sql = "update notaventa set fecha='$fecha',moneda='".$this->filtro($_GET['moneda'])
			."',idsucursal='".$this->filtro($_GET['sucursal'])."',idcliente='".$this->filtro($_GET['cliente'])
			."',numfactura='".$this->filtro($_GET['factura'])."',glosa='".$this->filtro($_GET['glosa'])
			."',monto='".$this->filtro($this->desconvertir($_GET['monto']))
			."',idusuario=$_SESSION[id_usuario],descuento='".$this->filtro($_GET['descuento'])
			."',recargo='".$this->filtro($_GET['recargo'])."',fechacredito='$fechaC[limite]',diascredito='".$diasCredito
			."',tipocambio='".$this->filtro($_GET['tipocambio'])."',tipoprecio='".$this->filtro($_GET['precio'])
			."',caja='".$this->filtro($_GET['caja'])."',credito='".$this->filtro($_GET['cambio'])
			."',fechaentrega='$fechaEntrega' where idnotaventa=$nota;";  	
			$this->db->consulta($sql);
			$this->aumentarStock($db,$nota);
			$costoProductos = 0;
			$sql = "delete from detallenotaventa where idnotaventa=$nota";
			$this->db->consulta($sql);
			$datos =  json_decode(stripcslashes($_GET['detalle']));
					
			for ($i = 0; $i < count($datos);  $i++) {
				$fila = $datos[$i];                 
				$id = $this->filtro($fila[0]);
				$lote= $this->filtro($fila[1]);
				$fechav= $this->filtro($this->db->GetFormatofecha($fila[2],"/"));
				$cantidad= $this->filtro($fila[3]);
				$um= $this->filtro($fila[4]);
				$precio = $this->filtro($this->desconvertir($fila[5]));
				$total = $this->filtro($this->desconvertir($fila[6]));
				
				if ($_GET['moneda'] == "Dolares") {
					$total = round(($total * $_GET['tipocambio']),4);
					$precio = round(($precio * $_GET['tipocambio']),4);
				}		
				
				$iddetalle = $this->filtro($fila[7]);
				$sql = "select precio from detalleingresoproducto where iddetalleingreso=$iddetalle";
				$precioCompra = $this->db->arrayConsulta($sql);
				$costoProductos = $costoProductos + ($precioCompra['precio'] * $cantidad);
				
				$consulta = "insert into detallenotaventa values
				(null,$iddetalle,'$id','$nota','$lote','$fechav','$cantidad','$um','$precio','$total');";
				$this->db->consulta($consulta);
				$sql = "select dp.cantidadactual,dp.unidadmedida,p.unidaddemedida as 'UM'
				,p.unidadalternativa as 'UA',p.conversiones
				 from detalleingresoproducto dp,producto p where 
				dp.iddetalleingreso=$iddetalle and dp.idproducto=p.idproducto;";
				$saliente = 0;
				$datosingreso = $this->db->arrayConsulta($sql);
				if ($datosingreso['unidadmedida'] == $um) {
				  $saliente = $cantidad;
				} else {
				   if ($um == $datosingreso['UA']) {
					   $saliente = $cantidad / $datosingreso['conversiones'];
				   } else {
					   $saliente = $cantidad * $datosingreso['conversiones'];
				   }
				}
				$sql = "update detalleingresoproducto di set di.cantidadactual=di.cantidadactual-$saliente
				 where di.iddetalleingreso=$iddetalle;";
				$this->db->consulta($sql);						
			}
			  
			$sql = "select nvimprimirfactura,nvlibrodiario from impresion;";  
			$dato = $this->db->arrayConsulta($sql);
			if ($dato['nvlibrodiario'] == "1") {  
			  if ($_GET['moneda'] == "Dolares") {
				 $_GET['subtotal'] = round(($_GET['subtotal'] * $_GET['tipocambio']),4);
			  }	
			
			  $this->modificarLibro($this->filtro($_GET['sucursal']),$this->filtro($_GET['moneda']),$fecha
			  ,$nota,$this->filtro($_GET['tipocambio'])
			  ,$_SESSION['id_usuario'],$this->filtro($_GET['subtotal']),
			  $db,$this->filtro($_GET['caja']),$this->filtro($_GET['descuento'])
			  ,$this->filtro($_GET['recargo']),$this->filtro($_GET['glosa'])
			  ,$this->filtro($_GET['cliente']),$this->filtro($_GET['factura']),
			  $this->filtro($_GET['cambio']),$costoProductos);
			}
			
			$sql = "select numautorizacion from sucursal where idsucursal='$_GET[sucursal]' and estado=1";
			$datosFac = $this->db->arrayConsulta($sql);
			
			if ($_GET['factura'] != "" && $_GET['factura'] >= 0) {  
			  $this->insertarLibroVentas($_GET['sucursal'], $fecha, $_GET['nit'], $_GET['nombrenit']
			  , $_GET['factura'], $datosFac['numautorizacion'], $_GET['monto'], $nota, $db);	  
			}
			
			echo $nota."---";  
			echo $dato['nvimprimirfactura']."---";	
			exit();  
		}
	
	
	
		function aumentarStock($db, $idnota) {
			$sql = "select de.iddetalleingreso,de.cantidad as 'cantidadEgreso'
			,de.unidadmedida as 'unidadmedidaegreso',di.cantidadactual,di.unidadmedida
			,p.unidaddemedida as 'UM',p.unidadalternativa as 'UA',p.conversiones  
			from detallenotaventa de,detalleingresoproducto di,producto p 
			where de.iddetalleingreso=di.iddetalleingreso 
			and de.idproducto=p.idproducto and de.idnotaventa=$idnota;";	
			$dato = $this->db->consulta($sql);
			while($detalle = mysql_fetch_array($dato)) {
				if ($detalle['unidadmedidaegreso'] == $detalle['unidadmedida']) {
				  $saliente = $detalle['cantidadEgreso'];
				} else {
				   if ($detalle['unidadmedidaegreso'] == $detalle['UA']){
					$saliente = $detalle['cantidadEgreso'] / $detalle['conversiones'];
				   }else{
					$saliente = $detalle['cantidadEgreso'] * $detalle['conversiones'];
				   }
				}
				$sql = "update detalleingresoproducto di set di.cantidadactual=di.cantidadactual+$saliente
				 where di.iddetalleingreso=$detalle[iddetalleingreso];";
				$this->db->consulta($sql);
			}	
		}
	
	}


	$venta = new Dventa();
	switch($_GET['transaccion']) {
		case "consultarVendedor":
			$venta->getVendedor();
		break;   
		case "almacenes":
			$venta->getAlmacen();  
		break;
		case "factura":
			$venta->getFactura();
		break;
		case "insertar":
			$venta->insertar();
		break;
		case "modificar":
			$venta->modificar();
		break;
		case "consultarDato":
		    $venta->getdatoProducto();
		break;
	}
?>