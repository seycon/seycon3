<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: ../../index.php");	
	}

    class Dventaservicio {
	  
	 public $db;
	 public function Dventaservicio() 
	 {
		include("../../conexion.php");
		$this->db = new MySQL; 
	 }

	 public function getConsulta()
	 {
	     $consulta = "select * from servicio WHERE idservicio =".$_GET['codigo'];
		 $respuesta = $this->db->arrayConsulta($consulta);
		 echo $respuesta[$_GET['tipoprecio']]."---";
		 echo $respuesta['precio2']."---";			 
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
	  }
	  
	  public function getFacturasDisponibles()
	  {
		   $sql = "select case when fechalimitemision<=current_date 
				 then 'si' else 'no' end as 'limite',
				 case when numfacturaactual>numfactfinal
				 then 'no' else 'si' end as 'emite',numautorizacion
				 ,(numfactfinal - numfacturaactual)as 'disponible' 
				 from sucursal where idsucursal = '$_GET[sucursal]'";
		   $sucursal = $this->db->arrayConsulta($sql);
		   if ($sucursal['limite'] == "si" || $sucursal['emite'] == "no") {
				if ($sucursal['limite'] == "si") {
					return "fecha";
				} else {
					return 0;
				}
			} else {
			 return $sucursal['disponible'];
		   }  
	  }
	
	
	  public function getNextFactura()
	  {
		  $sql = "select numfacturaactual from sucursal where idsucursal = '$_GET[sucursal]'";
		  $sucursal = $this->db->arrayConsulta($sql);
		  return $sucursal['numfacturaactual'];
	  }
	
	  public function getDatoVendedor()
	  {
		  $cliente = $_GET['idcliente'];	
		  $sql = "select r.idruta,left(r.nombre,15)as 'nombre',c.nit,c.nombrenit  from cliente c,ruta r 
		  where c.ruta=r.idruta  and c.idcliente=$cliente;";
		  $resultado = $this->db->arrayConsulta($sql);
		  echo $resultado['idruta']."---";
		  echo $resultado['nombre']."---";
		  echo $resultado['nit']."---";
		  echo $resultado['nombrenit']."---";
	  }
	
	  public function filtro($cadena)
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
	
	  public function facturasDisponibles()
	  {
		  $dia = date("d",(mktime(0,0,0,$_GET['mes']+1,1,$_GET['anio'])-1));
		  $fecha = $_GET['anio']."/".$_GET['mes']."/".$dia;
		  $sql = "select c.idcliente,c.recargo,c.registro,cc.idservicio,cc.precio 		  
		   from contratocliente cc,cliente c 
		   where cc.fechainicio<'$fecha' 
		   and (cc.fechafinal>'$fecha' or (month(cc.fechafinal)=month('$fecha') 
		   and year(cc.fechafinal)=year('$fecha'))) 
		   and cc.idcliente=c.idcliente and c.idsucursal=$_GET[sucursal]  
		   and c.modalidad='Definido' order by cc.idcontratocliente;";	  
		  $requeridas = $this->db->getnumRow($sql);
		  $disponible = $this->getFacturasDisponibles();
		  if ($disponible == "fecha") {
			 echo "Fecha";
			 return;  
		  }
		  if ($disponible == 0 || $requeridas > $disponible) {
			 echo "Insuficientes";
			 return;  
		  }
		  echo "si";
	  }
	
	
	  public function ventaMasiva() 
	  {
		  $dia = date("d",(mktime(0,0,0,$_GET['mes']+1,1,$_GET['anio'])-1));
		  $fecha = $_GET['anio']."/".$_GET['mes']."/".$dia;
		  $fechaIniFinal = $_GET['anio']."/".$_GET['mes']."/1";		
		  $sql = "select c.idcliente,c.recargo,c.registro from contratocliente cc,cliente c 
			 where cc.fechainicio<'$fecha' 
			 and (cc.fechafinal>'$fecha' or (month(cc.fechafinal)=month('$fecha') 
			 and year(cc.fechafinal)=year('$fecha'))) 
			 and cc.idcliente=c.idcliente and c.idsucursal=$_GET[sucursal]  
			 and c.modalidad='Definido' group by c.idcliente;";
		  $dato = $this->db->consulta($sql);
		  $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		  $tc = $this->db->getCampo('dolarcompra',$sql);
		  while ($data = mysql_fetch_array($dato)) {
			  if ($data['registro'] == "Agrupar") {
				  $this->ventaAgrupar($data['idcliente'], $data['recargo'], $tc, $fecha, $fechaIniFinal);  	
			  } else {
				  $this->ventaIndividual($data['idcliente'], $data['recargo'], $tc, $fecha, $fechaIniFinal);
			  }
		  }
	  }
	  
	  public function ventaIndividual($idcliente, $recargo, $tc, $fecha, $fechaIniFinal)
	  {
		  
		  $sql = "select c.idcliente,c.recargo,c.registro,cc.idservicio,cc.precio 
		  ,DATEDIFF('$fecha', cc.fechainicio) as 'diasinicio',cc.cantidad,
		  IF (month(cc.fechainicio)=month('$fecha') and year(cc.fechainicio)=year('$fecha')
		  , 'si', 'no')as 'fechainicial',
		  IF (month(cc.fechafinal)=month('$fecha') and year(cc.fechafinal)=year('$fecha')
		  , 'si', 'no')as 'fechafinal',
		  IF (month(cc.fechafinal)=month(cc.fechainicio) and year(cc.fechafinal)=year(cc.fechainicio)
		  , DATEDIFF(cc.fechafinal,
		   cc.fechainicio), 0)as 'diferencia',
		  DATEDIFF(cc.fechafinal,'$fechaIniFinal') as 'diasfinal',
		  day('$fecha')as 'dias'		
		   from contratocliente cc,cliente c 
		   where cc.fechainicio<'$fecha' 
		   and (cc.fechafinal>'$fecha' or (month(cc.fechafinal)=month('$fecha')
		    and year(cc.fechafinal)=year('$fecha'))) 
		   and cc.idcliente=c.idcliente and c.idsucursal=$_GET[sucursal]  
		   and c.modalidad='Definido' and c.idcliente=$idcliente order by cc.idcontratocliente;";
		   $dato = $this->db->consulta($sql);
  		  $sql = "select numautorizacion from sucursal where idsucursal='$_GET[sucursal]'";
		  $datosFac = $this->db->arrayConsulta($sql);
		  $sql = "select * from cliente where idcliente=$idcliente";
		  $datoCliente = $this->db->arrayConsulta($sql); 
		  while ($data = mysql_fetch_array($dato)) {
			 $nroFactura = "";
			 if ($_GET['factura'] == "si") {
				  $nroFactura = $this->getNextFactura();
				  $sql = "update sucursal set numfacturaactual=numfacturaactual+1
				   where idsucursal='$_GET[sucursal]'";
				  $this->db->consulta($sql);
			 }	
			 $idnota = $this->setVenta($idcliente, $recargo, $tc, $nroFactura);	
			 $monto = $this->getMonto($data['precio'], $data['fechainicial'], $data['fechafinal'], 
			 $data['diasinicio'], $data['dias'], $data['diferencia'], $data['diasfinal']);	
			 $this->insertarDetalle($data['idservicio'], $idnota, $monto, $data['cantidad'], $tc);
			 $general = round((($recargo / 100) * $monto ) + $monto,2); 
			 $sql = "update notaventa set credito='$general',monto='$general' where idnotaventa=$idnota";
			 $this->db->consulta($sql);
			 $this->insertarLibro($_GET['sucursal'],$_GET['moneda'], $fecha, $idnota, $tc
			,$_SESSION['id_usuario'], $monto, '', 0
			, $recargo, 'Ventas Automaticas', $idcliente, $nroFactura, $general);
  		     $this->insertarLibroVentas($_GET['sucursal'], $fecha, $datoCliente['nit'],$datoCliente['nombrenit']
		     , $nroFactura, $datosFac['numautorizacion'], $general, $idnota);			 
		  }		
	  }	
	  
	  public function ventaAgrupar($idcliente, $recargo, $tc, $fecha, $fechaIniFinal)
	  {
		  $nroFactura = "";
		  if ($_GET['factura'] == "si") {
			  $nroFactura = $this->getNextFactura();
			  $sql = "update sucursal set numfacturaactual=numfacturaactual+1
			   where idsucursal='$_GET[sucursal]'";
			  $this->db->consulta($sql);
		  }
		  $idnota = $this->setVenta($idcliente, $recargo, $tc, $nroFactura);
		  $sql = "select c.idcliente,c.recargo,c.registro,cc.idservicio
		  ,cc.precio,cc.cantidad 
		  ,DATEDIFF('$fecha', cc.fechainicio) as 'diasinicio',
		  IF (month(cc.fechainicio)=month('$fecha') and year(cc.fechainicio)=year('$fecha')
		  , 'si', 'no')as 'fechainicial',
		  IF (month(cc.fechafinal)=month('$fecha') and year(cc.fechafinal)=year('$fecha')
		  , 'si', 'no')as 'fechafinal',
		  IF (month(cc.fechafinal)=month(cc.fechainicio) and year(cc.fechafinal)=year(cc.fechainicio)
		  , DATEDIFF(cc.fechafinal, 
		  cc.fechainicio), 0)as 'diferencia',
		  DATEDIFF(cc.fechafinal,'$fechaIniFinal') as 'diasfinal'
		  ,day('$fecha')as 'dias'		
		   from contratocliente cc,cliente c 
		   where cc.fechainicio<'$fecha' 
		   and (cc.fechafinal>'$fecha' or (month(cc.fechafinal)=month('$fecha') 
		   and year(cc.fechafinal)=year('$fecha'))) 
		   and cc.idcliente=c.idcliente and c.idsucursal=$_GET[sucursal]  
		   and c.modalidad='Definido' and c.idcliente=$idcliente order by cc.idcontratocliente;";		   
		  $dato = $this->db->consulta($sql);
		  $totalGeneral = 0;  
		  while ($data = mysql_fetch_array($dato)) {
		      $monto = $this->getMonto($data['precio'], $data['fechainicial'], $data['fechafinal'], 
			  $data['diasinicio'], $data['dias'], $data['diferencia'], $data['diasfinal']);	
			  $totalGeneral = $totalGeneral + $monto;
			  $this->insertarDetalle($data['idservicio'], $idnota, $monto, $data['cantidad'], $tc);
		  }		  
		  $general = round((($recargo / 100) * $totalGeneral ) + $totalGeneral,2); 
		  $sql = "update notaventa set credito='$general',monto='$general' where idnotaventa=$idnota";
		  $this->db->consulta($sql);
  		  $sql = "select numautorizacion from sucursal where idsucursal='$_GET[sucursal]'";
		  $datosFac = $this->db->arrayConsulta($sql);
		  $sql = "select * from cliente where idcliente=$idcliente";
		  $datoCliente = $this->db->arrayConsulta($sql);
		  $this->insertarLibro($_GET['sucursal'],$_GET['moneda'], $fecha, $idnota, $tc
			,$_SESSION['id_usuario'], $totalGeneral, '', 0
			, $recargo, 'Ventas Automaticas', $idcliente, $nroFactura, $general);
		  $this->insertarLibroVentas($_GET['sucursal'], $fecha, $datoCliente['nit'],$datoCliente['nombrenit']
		  , $nroFactura, $datosFac['numautorizacion'], $general, $idnota);	
		  
	  }
	  
	  
	  public function getMonto($precio, $fechainicial, $fechafinal, $diasinicio
								  , $dias, $diferencia, $diasfinal)
	  {
		  $preciodia = $precio / 30;
		  
		  if ($fechainicial == "no" && $fechafinal == "no") {
			 return round(($precio), 4);	
		  }
		  if ($fechainicial == "si" && $fechafinal == "no") {
			 return round(($preciodia * $diasinicio), 4);	
		  }
		  if ($fechainicial == "no" && $fechafinal == "si") {
			 return round(($preciodia * $diasfinal), 4);	
		  }
		  if ($fechainicial == "si" && $fechafinal == "si") {
			 return round(($preciodia * $diferencia), 4);	
		  }		
	  }
	  
	  
	  public function insertarDetalle($idservicio, $idnotaventa, $precio, $cantidad,$tc)
	  {
		  $consulta = "insert into detallenotaventaserv(idservicio,idnotaventa,precio,cantidad)
		  values('$idservicio','$idnotaventa','$precio','$cantidad');";
		  $this->db->consulta($consulta);		
	  }
	  
	  
	  public function setVenta($idcliente, $recargo, $tc, $nroFactura)
	  {
		  $sql = "select max(numero)+1 as 'nro' from notaventa where tiponota='servicios';";
		  $nroVenta = $this->db->arrayConsulta($sql);
		  if ($nroVenta['nro'] == "") {
			  $nroVenta['nro'] = 1;	
		  }
		  $dia = date("d",(mktime(0,0,0,$_GET['mes']+1,1,$_GET['anio'])-1));
		  $fecha = $_GET['anio']."/".$_GET['mes']."/".$dia;
		  $sql = "select DATE_ADD('$fecha',INTERVAL 1 DAY)as 'limite'";
		  $fechaC = $this->db->arrayConsulta($sql);
		  
		  $sql = "insert into notaventa(
		   numero,idcliente,numfactura,fecha,descuento,recargo,
		   moneda,glosa,fechacredito,caja,diascredito,monto,
		   tipocambio,tipoprecio,tiponota,idsucursal,credito,
		   montoactualcredito,fechaentrega,idusuario,estado)
		   values('$nroVenta[nro]','$idcliente','$nroFactura','$fecha',0,'$recargo',
		   '$_GET[moneda]','Ventas Automaticas','$fechaC[limite]','','1','0',
		   '$tc','precio1','servicios','$_GET[sucursal]','0',
		   '0','$fecha',$_SESSION[id_usuario],1);";
		  $this->db->consulta($sql);
		  $codigo = $this->db->getMaxCampo("idnotaventa", "notaventa where tiponota='servicios'");
		  return $codigo;
	  }
	  
	
	  public function insertar()
	  {
		  $fecha = $this->filtro($this->db->GetFormatofecha($_GET['fecha'],"/"));
		  $diasCredito = ($_GET['diascredito']=="") ? 0 : $this->filtro($_GET['diascredito']);
		  $sql = "select DATE_ADD('$fecha',INTERVAL $diasCredito DAY)as 'limite'";
		  $fechaC = $this->db->arrayConsulta($sql);
		  $sql = "update cliente set nit='".$this->filtro($_GET['nit'])."',nombrenit='".$this->filtro($_GET['nombrenit'])
		  ."' where idcliente=".$this->filtro($_GET['cliente'])."";
		  $this->db->consulta($sql);
		  $sql = "select max(numero)+1 as 'nro' from notaventa where tiponota='servicios';";
		  $nroVenta = $this->db->arrayConsulta($sql);
		  if ($nroVenta['nro'] == "") {
			  $nroVenta['nro'] = 1;	
		  }
		  $sql = "insert into notaventa values(null,'$nroVenta[nro]','".$this->filtro($_GET['cliente'])."','"
		  .$this->filtro($_GET['factura'])."','$fecha','"
		  .$this->filtro($_GET['descuento'])
		  ."','".$this->filtro($_GET['recargo'])."','".$this->filtro($_GET['moneda'])
		  ."','".$this->filtro($_GET['glosa'])."','"
		  .$this->filtro($fechaC['limite'])."','"
		  .$this->filtro($_GET['caja'])."','$diasCredito','"
		  .$this->filtro($_GET['monto'])."','".$this->filtro($_GET['tipocambio'])."','"
		  .$this->filtro($_GET['precio'])."','servicios','".$this->filtro($_GET['sucursal'])."','"
		  .$this->filtro($_GET['cambio'])."',0,now(),$_SESSION[id_usuario],1)";
		  $this->db->consulta($sql);
		  
		  $sql = "select numfacturaactual,numautorizacion from sucursal where idsucursal='$_GET[sucursal]'";
		  $datosFac = $this->db->arrayConsulta($sql);
		  if ($datosFac['numfacturaactual'] == $_GET['factura']) {
			  $sql = "update sucursal set numfacturaactual=numfacturaactual+1 where idsucursal='$_GET[sucursal]'";
			  $this->db->consulta($sql);
		  }	
		  
		  $codigo = $this->db->getMaxCampo("idnotaventa", "notaventa");
		  $datos =  json_decode(stripcslashes($_GET['detalle']));  			
		  for ($i = 0; $i < count($datos); $i++) {
			  $fila = $datos[$i];                 
			  $id = $this->filtro($fila[0]);
			  $precio = $this->filtro($this->desconvertir($fila[1]));	
			  $cantidad = $this->filtro($this->desconvertir($fila[2]));		  
			  if ($_GET['moneda'] == "Dolares") {
				  $precio = round(($precio * $_GET['tipocambio']),4);
			  }			  		  
			  $consulta = "insert into detallenotaventaserv 
			  values(null,'$id','$codigo','$precio','$cantidad');";
			  $this->db->consulta($consulta);				
		  }	
			
		  $sql = "select nvsimprimirfactura,nvslibrodiario from impresion;";  
		  $dato = $this->db->arrayConsulta($sql);  
		  if ($dato['nvslibrodiario'] == "1") {
			if ($_GET['moneda'] == "Dolares") {
				  $_GET['subtotal'] = round(($_GET['subtotal'] * $_GET['tipocambio']),4);
			}				  
			$this->insertarLibro($this->filtro($_GET['sucursal']),$this->filtro($_GET['moneda'])
			,$fecha,$codigo,$this->filtro($_GET['tipocambio'])
			,$_SESSION['id_usuario'],$this->filtro($_GET['subtotal']),
			$this->filtro($_GET['caja']),$this->filtro($_GET['descuento'])
			,$this->filtro($_GET['recargo']),$this->filtro($_GET['glosa'])
			,$this->filtro($_GET['cliente']),$this->filtro($_GET['factura']),$_GET['cambio']);
		  }		 
		   $this->insertarLibroVentas($_GET['sucursal'], $fecha, $_GET['nit'], $_GET['nombrenit'], $_GET['factura']
		   , $datosFac['numautorizacion'], $_GET['monto'], $codigo);		 	
			  
		  echo $codigo."---";  
		  echo $dato['nvsimprimirfactura']."---";
	  }
  
  
	  function insertarLibroVentas($sucursal, $fecha, $nit, $nombrenit, $factura
									 , $numautorizacion, $total, $codigo)
	  {
		  if ($factura != "" && $factura >= 0) {  
			  $sql = "select porcentajedebitofiscal as 'iva' from configuracioncontable";
			  $datosSistema = $this->db->arrayConsulta($sql);
			  $iva = round((($datosSistema['iva'] / 100) * $total),2);
			  
			  $sql = "select idlibroventasiva from libroventasiva where idtransaccion=$codigo
			   and transaccion='Venta Servicios' and estado=1";
			  $datosLibro = $this->db->arrayConsulta($sql);
			  if ($datosLibro['idlibroventasiva'] == "") {	
				  $sql = "insert into libroventasiva(folio,fechadeemision
				  ,numcinitcliente,nomrazonsocicliente,numfactura
				  ,numautorizacion,codigodecontrol,
				  totalfactura,totalice,importeexcento,importeneto,debitofiscal
				  ,idtransaccion,transaccion,tipo,estadofactura,idcuenta,tipocuenta,idusuario,estado)
				  values ('$sucursal','$fecha','$nit','$nombrenit','$factura','$numautorizacion',
				  '0','$total','0','0','$total','$iva',$codigo,'Venta Servicios','VS',
				  'V','','servicios','$_SESSION[id_usuario]',1)";
			  } else {
				  $sql = "update libroventasiva set folio='$sucursal',fechadeemision='$fecha',
				  numcinitcliente='$nit',nomrazonsocicliente='$nombrenit',numfactura='$factura'
				  ,numautorizacion='$numautorizacion',tipocuenta='servicios',
				  totalfactura='$total',importeneto='$total',debitofiscal='$iva',idusuario='$_SESSION[id_usuario]' 
				  where idlibroventasiva=$datosLibro[idlibroventasiva];";
			  }
			  $this->db->consulta($sql);
		  }
	  }
  
  
	  function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario
							 , $monto, $cuentacaja, $descuento, $recargo, $glosa, $cliente, $factura, $cambio)
	  {
		  $sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' from librodiario l 
		  where l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
		  $num = $this->db->arrayConsulta($sql); 
		  if (!isset($num['num'])) {
			  $num['num'] = 1;
			  $num['sucursal'] = $sucursal;
		  }	
			  
		  $sql = "insert into librodiario
		  (numero,idsucursal,moneda,tipotransaccion,fecha,glosa
		  ,idtransaccion,tipocambio,idusuario,estado,transaccion) 
		  values(
		  '$num[num]','$num[sucursal]','$moneda','ingreso','$fecha','$glosa'
		  ,'$codigo','$tc','$usuario',1,'Nota Venta Servicios');"; 
		  $this->db->consulta($sql);
		  
		  $sql = "select * from sucursal where idsucursal=$sucursal";
		  $datosSucursal = $this->db->arrayConsulta($sql);
		  $sql = "select *from cliente where idcliente=$cliente;";
		  $datoCliente = $this->db->arrayConsulta($sql);	
		  $descripcionLibro = "Nº $codigo/Cliente: $datoCliente[nombre]/Sucursal: $datosSucursal[nombrecomercial]";
		  $libro = $this->db->getMaxCampo("idlibrodiario","librodiario");
		  $this->setDetalleLibro($monto, $cuentacaja, $factura, $descripcionLibro
							, $recargo, $descuento, $cambio, $cliente, $libro);
	   }
  
  
	  function modificarLibro($sucursal, $moneda, $fecha, $codigo, $tc
							   , $usuario, $monto, $cuentacaja, $descuento
								 , $recargo, $glosa, $cliente, $factura, $cambio)
	  {
		  $sql = "select idlibrodiario,idsucursal from librodiario where 
		  transaccion='Nota Venta Servicios' and idtransaccion=$codigo;";  
		  $libro = $this->db->arrayConsulta($sql); 
	   
		  if ($libro['idsucursal'] != $sucursal) {
			  $sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' from librodiario l 
			  where l.idsucursal=$sucursal GROUP BY l.idsucursal;";
			  $num = $this->db->arrayConsulta($sql);  	
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
		  $this->db->consulta($sql);
		  $sql = "delete from detallelibrodiario where idlibro=$libro[idlibrodiario]";
		  $this->db->consulta($sql);
		  $sql = "select * from sucursal where idsucursal=$sucursal";
		  $datosSucursal = $this->db->arrayConsulta($sql);
		  $sql = "select *from cliente where idcliente=$cliente;";
		  $datoCliente = $this->db->arrayConsulta($sql);	
		  $descripcionLibro = "Nº $codigo/Cliente: $datoCliente[nombre]/Sucursal: $datosSucursal[nombrecomercial]";
		  $this->setDetalleLibro($monto, $cuentacaja, $factura, $descripcionLibro
						  , $recargo, $descuento, $cambio, $cliente, $libro['idlibrodiario']);	
	  }
  
  
	  function setFacturado($libro, $contabilidad, $monto, $descripcion, $factura)
	  {
		 $porcentaje = (100 - $contabilidad['porcentajedebitofiscal'])/100;	
		 $montoVenta = $porcentaje * $monto;	
		 $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values
		 ($libro,'$contabilidad[cuentalcv]','$descripcion',0,$montoVenta,'$factura')";
		 $this->db->consulta($sql);
		 $porcentaje = $contabilidad['porcentajedebitofiscal']/100;
		 $montodebito = $porcentaje * $monto;
		 $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values
		 ($libro,'$contabilidad[debitofiscal]','$descripcion',0,$montodebito,'$factura')";
		 $this->db->consulta($sql);
	  }
  
	  function setSinFactura($libro, $contabilidad, $monto, $descripcion)
	  {
		  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		  values($libro,'$contabilidad[cuentalcvproductos]','$descripcion',0,$monto,'')";
		  $this->db->consulta($sql);
	  }
  
	  function setImpuestosTransacciones($libro, $contabilidad, $monto, $descripcion, $factura)
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
  
	  function setEfectivoCredito($monto, $cambio, $cliente, $libro, $cuentacaja
								   , $factura, $descripcion, $recargo, $descuento)
	  {
		  $monto = $monto + (($recargo / 100) * $monto) - (($descuento / 100) * $monto);	 
		  $efectivo = $monto - $cambio;
		  $credito = $monto - $efectivo;	
		  $sql = "select *from configuracioncontable";
		  $datocliente = $this->db->arrayConsulta($sql);
		  if ($efectivo > 0) {	
			  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			   values($libro,'$cuentacaja','$descripcion',$efectivo,0,'$factura')";
			  $this->db->consulta($sql);
		  }
		  if ($credito > 0) {
			  $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
			  values($libro,'$datocliente[clientescobrar]','$descripcion',$credito,0,'$factura')";
			  $this->db->consulta($sql);
		  }
	  }
  
	  function setRecargo($libro, $contabilidad, $monto, $recargo, $descripcion, $factura)
	  {
		if ($recargo != "" && $recargo > 0) { 
		   $recargo = ($recargo/100) * $monto;
		   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
		   values($libro,'$contabilidad[recargo]','$descripcion',0,$recargo,'$factura')";
		   $this->db->consulta($sql);
		}
	  }
  
	  function setDescuento($libro, $contabilidad, $monto, $descuento, $descripcion, $factura)
	  {
		if ($descuento != "" && $descuento > 0) { 
		   $descuento = ($descuento / 100) * $monto;
		   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values
		   ($libro,'$contabilidad[descuentoventa]','$descripcion',$descuento,0,'$factura')";
		   $this->db->consulta($sql);
		}
	  }
  
	   function setDetalleLibro($monto, $cuentacaja, $factura, $descripcion
								 , $recargo, $descuento, $cambio, $cliente, $idlibro)
	   {
		  $libro = $idlibro; 
		  $sql = "select *from configuracioncontable;";
		  $contabilidad = $this->db->arrayConsulta($sql);	
		  $total = $monto + (($recargo / 100) * $monto) - (($descuento / 100) * $monto);
		  
		  if ( $cambio != "" && $cambio > 0) {
			$this->setEfectivoCredito($monto, $cambio, $cliente, $libro
				, $cuentacaja, $factura, $descripcion, $recargo, $descuento);
		  } else {
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
			 values($libro,'$cuentacaja','$descripcion',$total,0,'$factura')";
			$this->db->consulta($sql);
		  }	
		  
		  $this->setDescuento($libro, $contabilidad, $monto, $descuento, $descripcion, $factura);
		  $this->setRecargo($libro, $contabilidad, $monto, $recargo, $descripcion, $factura);
		  if ($factura != "") {
			  $this->setFacturado($libro, $contabilidad, $monto, $descripcion, $factura);
		  } else {
			  $this->setSinFactura($libro, $contabilidad, $monto, $descripcion);
		  }
		  $this->setImpuestosTransacciones($libro, $contabilidad, $total, $descripcion, $factura);
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
		  $sql = "update notaventa set fecha='$fecha',moneda='".$this->filtro($_GET['moneda'])."',idsucursal='"
		  .$this->filtro($_GET['sucursal'])."',idcliente='".$this->filtro($_GET['cliente'])
		  ."',numfactura='".$this->filtro($_GET['factura'])."',glosa='"
		  .$this->filtro($_GET['glosa'])."',monto='".$this->filtro($this->desconvertir($_GET['monto']))
		  ."',idusuario=$_SESSION[id_usuario],descuento='"
		  .$this->filtro($_GET['descuento'])."',recargo='".$this->filtro($_GET['recargo'])
		  ."',fechacredito='$fechaC[limite]',diascredito='".$this->filtro($_GET['diascredito'])."'
		  ,tipocambio='".$this->filtro($_GET['tipocambio'])."',tipoprecio='"
		  .$this->filtro($_GET['precio'])."',caja='".$this->filtro($_GET['caja']).
		  "',credito='".$this->filtro($_GET['cambio'])."',fechaentrega=now() where idnotaventa=$nota;";  	
		  $this->db->consulta($sql);
		  $sql = "delete from detallenotaventaserv where idnotaventa=$nota";
		  $this->db->consulta($sql);
		  $datos =  json_decode(stripcslashes($_GET['detalle']));
				  
		  for ($i = 0; $i < count($datos); $i++) {
			  $fila = $datos[$i];                 
			  $id = $this->filtro($fila[0]);
			  $precio = $this->filtro($this->desconvertir($fila[1]));
			  $cantidad = $this->filtro($this->desconvertir($fila[2]));
			  if ($_GET['moneda'] == "Dolares") {
				  $precio = round(($precio * $_GET['tipocambio']),4);
			  }			  
			  $consulta = "insert into detallenotaventaserv
			   values(null,'$id','$nota','$precio','$cantidad');";
			  $this->db->consulta($consulta);			
		  }
		  
		  $sql = "select nvsimprimirfactura,nvslibrodiario from impresion;";  
		  $dato = $this->db->arrayConsulta($sql);
		  if ($dato['nvslibrodiario'] == "1") {  
		    if ($_GET['moneda'] == "Dolares") {
			    $_GET['subtotal'] = round(($_GET['subtotal'] * $_GET['tipocambio']),4);
			}	
		  
		  $this->modificarLibro($this->filtro($_GET['sucursal']),$this->filtro($_GET['moneda'])
		  ,$fecha,$nota,$this->filtro($_GET['tipocambio'])
		  ,$_SESSION['id_usuario'],$this->filtro($_GET['subtotal']),
			$this->filtro($_GET['caja']),$this->filtro($_GET['descuento'])
			,$this->filtro($_GET['recargo']),$this->filtro($_GET['glosa'])
			,$this->filtro($_GET['cliente']),$this->filtro($_GET['factura']), $this->filtro($_GET['cambio']));  
		  }
		  
		  $sql = "select numautorizacion from sucursal 
				  where idsucursal='$_GET[sucursal]' and estado=1";
		  $datosFac = $this->db->arrayConsulta($sql);
		 
		  $this->insertarLibroVentas($_GET['sucursal'], $fecha, $_GET['nit'], $_GET['nombrenit'], $_GET['factura']
			  , $datosFac['numautorizacion'], $_GET['monto'], $nota);	  		 	
		  
		  echo $nota."---";  
		  echo $dato['nvsimprimirfactura']."---";   
	  }
	
	}
	
	$venta = new Dventaservicio();
	switch($_GET['transaccion']) {
		case "consulta":
			$venta->getConsulta();
		break;   
		case "factura":
			$venta->getFactura();  
		break;
		case "consultarVendedor":
			$venta->getDatoVendedor();
		break;
		case "insertar":
			$venta->insertar();
		break;
		case "modificar":
			$venta->modificar();
		break;
		case "ventaMasiva":
		    $venta->ventaMasiva();
		break;
		case "disponibles":
		    $venta->facturasDisponibles();
		break;
	}

?>