<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
  session_start();
  if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: ../index.php");	 
  }

  class Druta {
	  
	 public $db;
	 public function Druta() 
	 {
		include("../conexion.php");
		$this->db = new MySQL; 
	 }
	 
	 public function getListaRuta()
	 {
		$sql = "select r.idruta,left(r.nombre,16)as 'ruta' 
		from rutatrabajo rt,ruta r where rt.idruta=r.idruta and rt.idtrabajador=$_GET[idtrabajador];";
		$datoLista =  $this->db->consulta($sql); 
		echo "<option value=''>-- Seleccione --</option>";
		while ($data = mysql_fetch_array($datoLista)) {
			echo "<option value='$data[idruta]'>$data[ruta]</option>";
		}
		 
	 } 
	 
	 
	 public function getEstadoVisita() 
	 {
		$sql = "select * from visitacliente where iddetallerutavisita=$_GET[iddetalleruta]"; 
		echo $this->db->getnumRow($sql);
	 }
	 
	 public function insertar()
	 {
		$fecha = $this->db->GetFormatofecha($_GET['fecha'], "/"); 
		$sql = "insert into rutavisita(fecha,idusuario,estado) values 
		('$fecha',$_SESSION[id_usuario],1)";
		$this->db->consulta($sql);
		$codigo = $this->db->getMaxCampo("idrutavisita","rutavisita"); 
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0;$i < count($datos);$i++) {
		    $fila = $datos[$i];                 
		    $fechainicio = $this->db->GetFormatofecha($fila[0], "/");
		    $fechafinal = $this->db->GetFormatofecha($fila[1], "/"); 
			$idtrabajador = $fila[2];
			$idruta = $fila[3];
			$sql = "insert into detallerutavisita(idrutavisita,idruta,idtrabajador,fechainicio,fechafinal,estado)
			values ($codigo,$idruta,$idtrabajador,'$fechainicio','$fechafinal',1);";
			$this->db->consulta($sql);
		}
	 }
	 
	 public function modificar()
	 {
		$fecha = $this->db->GetFormatofecha($_GET['fecha'], "/"); 
		$sql = "update rutavisita set 
		 fecha='$fecha',idusuario=$_SESSION[id_usuario] where idrutavisita=$_GET[idtransaccion]";
		$this->db->consulta($sql);
		$codigo = $_GET['idtransaccion']; 
		$sql = "update detallerutavisita set estado=0 where idrutavisita=$codigo";
        $this->db->consulta($sql);
		
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0;$i < count($datos);$i++) {
		    $fila = $datos[$i];              
			$iddetalleruta = $fila[4];
			if ($iddetalleruta == 0) {   
				$fechainicio = $this->db->GetFormatofecha($fila[0], "/");
				$fechafinal = $this->db->GetFormatofecha($fila[1], "/"); 
				$idtrabajador = $fila[2];
				$idruta = $fila[3];
				$sql = "insert into detallerutavisita(idrutavisita,idruta,idtrabajador,fechainicio,fechafinal,estado)
				values ($codigo,$idruta,$idtrabajador,'$fechainicio','$fechafinal','1');";
				$this->db->consulta($sql);
			} else {
				$sql = "update detallerutavisita set estado=1 where iddetallerutavisita=$iddetalleruta;";
				$this->db->consulta($sql);	
			}
		}
	 }
	 
	  
  }



  $ruta = new Druta();
  switch($_GET['transaccion']) {
      case "listaRuta":
	      $ruta->getListaRuta();
	  break;   
	  case "insertar":
	      $ruta->insertar();  
	  break;
	  case "modificar":
	      $ruta->modificar();
	  break;
	  case "rutavisitada":
	      $ruta->getEstadoVisita();
	  break;
  }

?>