<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();

	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: ../index.php");	
	}

  class Dcotizacion {
	  
	 public $db;
	 public function Dcotizacion() 
	 {
		include("../conexion.php");
		$this->db = new MySQL; 
	 }


     public function filtro($cadena) 
	 {
	    return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	 }
	 
	 public function desconvertir($cadena)
	 {
		$convertir = "";     
		for($i = 0; $i < strlen($cadena); $i++) {		
			if ($cadena[$i] != ",")
			    $convertir = $convertir.$cadena[$i];
	
		}
		return $convertir;
	 }

     public function getdatos() 
	 {
		if ($_GET['tipocotizacion'] == "productos") { 
		    $consulta = "select * from producto WHERE idproducto =".$_GET['codigo'];
		} else {
			$consulta = "select *from servicio where idservicio=".$_GET['codigo'];
		}
		$respuesta = $this->db->arrayConsulta($consulta);
		echo $respuesta[$_GET['tipoprecio']]."---";
	 }

     public function datosCliente()
	 {
		$sql = "select c.nombrecontacto   
		 from cliente c where c.idcliente=$_GET[idcliente];";	
		$resultado = $this->db->arrayConsulta($sql);
		echo $resultado['nombrecontacto']; 
	 }	 
	 
     public function insertar() 
	 {
		$fecha = $this->filtro($this->db->GetFormatofecha($_GET['fecha'],"/"));
		$tiempoentrega = $_GET['tiempoentrega'];
		$tiempocredito = $this->filtro($_GET['tiempocredito']);
		$sql = "insert into cotizacion values(null,'".$this->filtro($_GET['cliente'])
		."','$fecha','".$this->filtro($_GET['descuento'])."','".$this->filtro($_GET['recargo'])."','".
		$this->filtro($_GET['moneda'])."','".$this->filtro($_GET['glosa'])."','"
		.$this->filtro($_GET['formadepago'])."','$tiempoentrega','$tiempocredito','"
		.$this->filtro($_GET['validez'])."','".$this->filtro($_GET['contacto'])."','".$this->filtro($_GET['monto'])
		."','".$this->filtro($_GET['almacen'])."','".$this->filtro($_GET['carta'])
		."','".$this->filtro($_GET['tipocotizacion'])."','".$this->filtro($_GET['tipocambio'])
		."',$_SESSION[id_usuario],1)";
		$this->db->consulta($sql);
		$codigo = $this->db->getMaxCampo("idcotizacion","cotizacion");
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		  for ($i  =0; $i<count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = $this->filtro($fila[0]);
			$cantidad = $this->filtro($fila[1]);
			$precio = $this->filtro($this->desconvertir($fila[2]));
			$total = $this->filtro($this->desconvertir($fila[3]));
			$consulta = "insert into detallecotizacion values(null,'$id','$codigo','$cantidad','$precio','$total');";
			$this->db->consulta($consulta);	
		  }	
		echo $codigo."---";  
		$sql = "select cotimprimir from impresion;";  
		$dato = $this->db->arrayConsulta($sql);
		echo $dato['cotimprimir']."---";	
	 }

     public function modificar() 
	 {
		$cotizacion = $_GET['idcotizacion'];
		$fecha = $this->db->GetFormatofecha($_GET['fecha'],"/");
		$tiempoentrega = $_GET['tiempoentrega'];
		$tiempocredito = $_GET['tiempocredito'];
		$sql = "update cotizacion set fecha='$fecha',moneda='".$this->filtro($_GET['moneda'])
		."',idalmacen='".$this->filtro($_GET['almacen'])."',idcliente='"
		.$this->filtro($_GET['cliente'])."',contacto='".$this->filtro($_GET['contacto'])
		."',glosa='".$this->filtro($_GET['glosa'])."',monto='".$this->filtro($this->desconvertir($_GET['monto']))
		."',idusuario=".$_SESSION['id_usuario'].",descuento='".$this->filtro($_GET['descuento'])
		."',idcarta='".$this->filtro($_GET['carta'])."',tipo='".$this->filtro($_GET['tipocotizacion'])
		."',recargo='".$this->filtro($_GET['recargo'])."'
		,formapago='".$this->filtro($_GET['formadepago'])."',tiempoentrega='".$tiempoentrega
		."',tiempocredito='$tiempocredito',validez='".
		$this->filtro($_GET['validez'])."',tipocambio='".$this->filtro($_GET['tipocambio'])
		."' where idcotizacion=$cotizacion;";  	
		$this->db->consulta($sql);
		$sql = "delete from detallecotizacion where idcotizacion=$cotizacion";
		$this->db->consulta($sql);
		$datos =  json_decode(stripcslashes($_GET['detalle']));				  
		for ($i = 0; $i < count($datos); $i++) {
		  $fila = $datos[$i];                 
		  $id = $this->filtro($fila[0]);
		  $cantidad = $this->filtro($fila[1]);
		  $precio = $this->filtro($this->desconvertir($fila[2]));
		  $total = $this->filtro($this->desconvertir($fila[3]));
		  $consulta = "insert into detallecotizacion values(null,'$id',$cotizacion,'$cantidad','$precio','$total');";
		  $this->db->consulta($consulta);				
		}
		echo $cotizacion."---";  
		$sql = "select cotimprimir from impresion;";  
		$dato = $this->db->arrayConsulta($sql);
		echo $dato['cotimprimir']."---";		
	 }

  }

  
  $cotizacion = new Dcotizacion();
  switch($_GET['transaccion']) {
      case "consulta":
	      $cotizacion->getdatos();
	  break;   
	  case "insertar":
	      $cotizacion->insertar();  
	  break;
	  case "modificar":
	      $cotizacion->modificar();
	  break;
	  case "consultarCliente":
	      $cotizacion->datosCliente();
	  break;
  }

?>

