<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
  session_start();
  if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: ../index.php");	 
  }


  class Dvacaciones {
	  
	 public $db;
	 public function Dvacaciones() 
	 {
		include("../conexion.php");
		$this->db = new MySQL; 
	 }
	 
	 public function getDatosTrabajador()
	 {
	     $sql = "select t.fechaingreso,c.cargo,
		 (YEAR(CURDATE())-YEAR(t.fechaingreso))- (RIGHT(CURDATE(),5)<RIGHT(t.fechaingreso,5))as 'anios' from trabajador t,cargo c 
		 where t.idcargo=c.idcargo and t.idtrabajador=$_GET[idtrabajador]";
		 $datoTrabajador = $this->db->arrayConsulta($sql); 
		 echo $this->db->GetFormatofecha($datoTrabajador['fechaingreso'], "-")."---";
		 echo $datoTrabajador['cargo']."---";
		 $sql = "select count(dv.idvacaciones)as 'total' from detallevacaciones dv,vacaciones v 
		  where v.idvacaciones=dv.idvacaciones and v.idtrabajador=$_GET[idtrabajador] and v.estado=1;";
		 $tomados = $this->db->arrayConsulta($sql);  
		 $dias = $this->getCorespondiente($datoTrabajador['anios']) - $tomados['total'];
		 echo $dias."---";
	 } 
	 
	 public function getCorespondiente($permanencia)
	 {
		 $sql = "select * from datosvacaciones;";
		 $diasCorresponden = $this->db->arrayConsulta($sql);
		 if ($permanencia >= 1 && $permanencia <= 5) {
		   return $diasCorresponden['rango1'] * $permanencia;	 
		 }
		 if ($permanencia >= 6 && $permanencia <= 10) {
		   return $diasCorresponden['rango2'] * $permanencia;	 
		 }
		 if ($permanencia >= 11) {
		   return  $diasCorresponden['rango3'] * $permanencia;	 
		 } 
		 return 0;
	 }
	 
	 public function insertar()
	 {
		$motivo = $this->filtro($_GET['motivo']); 
		$sql = "insert into vacaciones(fecha,idtrabajador,motivo,idusuario,estado,diashabilitado) values 
		(now(),$_GET[idtrabajador],'$motivo',$_SESSION[id_usuario],1,'$_GET[diashabilitado]')";
		$this->db->consulta($sql);
		$codigo = $this->db->getMaxCampo("idvacaciones","vacaciones"); 
		$datos =  explode(",", $_GET['fecha']);
		for ($i = 0; $i < (count($datos) - 1); $i++) {
		    $fila = explode("/",$datos[$i]);                 
			$fecha = $fila[2]."/".$fila[0]."/".$fila[1];
			$sql = "insert into detallevacaciones(idvacaciones,fecha)
			values ($codigo,'$fecha');";
			$this->db->consulta($sql);
		}
	 }
	 
	 public function modificar()
	 {
		$motivo = $this->filtro($_GET['motivo']);  
		$sql = "update vacaciones set 
		 fecha=now(),idtrabajador='$_GET[idtrabajador]',motivo='$motivo',
		 diashabilitado='$_GET[diashabilitado]'
		 ,idusuario=$_SESSION[id_usuario] where idvacaciones=$_GET[idtransaccion]";
		$this->db->consulta($sql);
		$codigo = $_GET['idtransaccion']; 
		$sql = "delete from detallevacaciones where idvacaciones=$codigo";
        $this->db->consulta($sql);
		$datos =  explode(",", $_GET['fecha']);
		for ($i = 0; $i < (count($datos) - 1); $i++) {
		    $fila = explode("/",$datos[$i]);                 
			$fecha = $fila[2]."/".$fila[0]."/".$fila[1];
			$sql = "insert into detallevacaciones(idvacaciones,fecha)
			values ($codigo,'$fecha');";
			$this->db->consulta($sql);
		}
	 }
	 
	 public function filtro($cadena)
	 {
		return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	 }	 
	  
  }

  $vacaciones = new Dvacaciones();
  switch($_GET['transaccion']) {
      case "consultarTrabajador":
	      $vacaciones->getDatosTrabajador();
	  break;   
	  case "insertar":
	      $vacaciones->insertar();  
	  break;
	  case "modificar":
	      $vacaciones->modificar();
	  break;
  }

?>