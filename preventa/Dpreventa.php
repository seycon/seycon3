<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();    
	if (!isset($_SESSION['softLogeoadmin'])) {
		 header("Location: ../index.php");	
	}
	
	
	class Dpreventa
    {   
	
		public $db;   
				  
		public function Dpreventa()
		{   
			include("../conexion.php");
			$this->db = new MySQL();	
		}
		
		public function insertarPreventa()
		{
		
			$glosa = $this->filtro($_GET['glosa']);
			$fecha = $this->db->GetFormatofecha($_GET['fecha'], "/");
			$fechaentrega = $this->db->GetFormatofecha($_GET['fechaentrega'], "/");
			$sql = "insert into preventa(fecha,modalidad,idalmacen,moneda
			,diascredito,glosa,idcliente,idusuario,estado,tipocambio,fechaentrega)
			 values('$fecha', '$_GET[modalidad]', '$_GET[idalmacen]'
			 , '$_GET[moneda]', '$_GET[dias]','$glosa','$_GET[idcliente]'
			 ,$_SESSION[id_usuario], 1, $_GET[tipocambio],
			 '$fechaentrega')";
			$this->db->consulta($sql); 
			$codigo = $this->db->getMaxCampo("idpreventa","preventa");
			$datos =  json_decode(stripcslashes($_GET['detalle']));  			
			for ($i = 0; $i < count($datos); $i++) {
				$fila = $datos[$i];
				$idproducto = $fila[0];
				$precio = $fila[3];
				if ($_GET['moneda'] == "Dolares") {
				   $precio = round(($precio * $_GET['tipocambio']), 4);	
				}
				$cantidad = $fila[1];
				$um = $fila[2];
				$sql = "insert into detallepreventa(idpreventa,idproducto
				,cantidad,unidadmedida,precio)
				values ('$codigo','$idproducto','$cantidad','$um','$precio');";				
				$this->db->consulta($sql);
			}
			echo $codigo;
			
		}
		
		public function modificarPreventa()
		{
			$glosa = $this->filtro($_GET['glosa']);
			$fecha = $this->db->GetFormatofecha($_GET['fecha'], "/");
			$fechaentrega = $this->db->GetFormatofecha($_GET['fechaentrega'], "/");
			$codigo = $_GET['idpreventa'];
			$sql = "update preventa set fecha='$fecha',modalidad='$_GET[modalidad]'
			,idalmacen='$_GET[idalmacen]',moneda='$_GET[moneda]'
			,diascredito='$_GET[dias]',glosa='$glosa',idcliente='$_GET[idcliente]'
			,idusuario=$_SESSION[id_usuario],tipocambio=$_GET[tipocambio],fechaentrega='$fechaentrega' 
			where idpreventa=$codigo;";
			$this->db->consulta($sql); 
			$sql = "delete from detallepreventa where idpreventa=$codigo";
			$this->db->consulta($sql);
			$datos =  json_decode(stripcslashes($_GET['detalle']));  			
			for ($i = 0; $i < count($datos); $i++) {
				$fila = $datos[$i];
				$idproducto = $fila[0];
				$precio = $fila[3];
				if ($_GET['moneda'] == "Dolares") {
				   $precio = round(($precio * $_GET['tipocambio']), 4);	
				}
				$cantidad = $fila[1];
				$um = $fila[2];
				$sql = "insert into detallepreventa(idpreventa,idproducto
				,cantidad,unidadmedida,precio)
				values ('$codigo','$idproducto','$cantidad','$um','$precio');";				
				$this->db->consulta($sql);
			}
			echo $codigo;
		}
		
		public function getDatosClientes()
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
		}
		
		public function cargarDatosProductos()
		{
		  	$consulta = "select * from producto WHERE idproducto =".$_GET['idproducto'];
			$respuesta = $this->db->arrayConsulta($consulta);
			echo $respuesta[$_GET['precio']]."---";
			echo $respuesta['unidaddemedida']."---";
			echo $respuesta['unidadalternativa']."---";
			echo $respuesta['conversiones']."---";
		}
		
		public function filtro($cadena)
		{
            return htmlspecialchars(strip_tags($cadena));
        }
		
	

		
	}


	$db = new Dpreventa(); 
    switch ($_GET['tipo']) {
	  case "datosProdcuto":
	      $db->cargarDatosProductos();
	  break;
	  case "insertar":
	      $db->insertarPreventa();
	  break;
	  case "modificar":
	      $db->modificarPreventa();
	  break;
	  case "datosCliente":
	      $db->getDatosClientes();
	  break;
    }


	
?>