<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
  session_start();
  if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: ../index.php");	 
  }


  class Drutaenetrega {
	  
	 public $db;
	 public function Drutaenetrega() 
	 {
		include("../conexion.php");
		$this->db = new MySQL; 
	 }
	 
	 public function getListaNotasVenta()
	 {
		$condicion = "";  
		if ($_GET['idsucursal'] != "") {
		    $condicion = " and nv.idsucursal=$_GET[idsucursal] ";
		}
		$sql = "select left(concat(nombre,' ',apellido),20)as 'trabajador',idtrabajador  
		 from trabajador where idtrabajador=$_GET[idtrabajador]";
		$datoTrabajador = $this->db->arrayConsulta($sql); 
		$fecha = $this->db->GetFormatofecha($_GET['fecha'], "/"); 
		$sql = "select nv.idnotaventa,nv.numero,nv.fechaentrega,left(c.nombre,15)as 'cliente' 
		   , left(r.nombre,15)as 'ruta',left(s.nombrecomercial,20) as 'sucursal',r.idruta    
		  from notaventa nv,cliente c,ruta r,sucursal s   
		  where nv.idcliente=c.idcliente 
		  and nv.idsucursal=s.idsucursal 
		  and r.idruta=c.ruta  
		  and nv.tiponota='productos' 
		  $condicion 
		  and nv.estado=1 
		  and nv.fechaentrega='$fecha' 
		  and nv.idnotaventa not in ( 
		  select dr.idnotaventa  from rutaentrega re,detallerutaentrega dr 
		  where dr.idrutaentrega=re.idrutaentrega 
		  and dr.iddetallerutaentrega not in (
		    select ec.iddetallerutaentrega from entregacliente ec 
            where ec.descripcion='Cerrado' or ec.descripcion='No Entregado' 
		  )
		  and re.estado=1);		   
		  ";
		$datoLista =  $this->db->consulta($sql); 
		$j = -1;
		while ($data = mysql_fetch_array($datoLista)) {
			$j++;
			$fecha = $this->db->GetFormatofecha($data['fechaentrega'], "-");
			$idcheck = "codentrega$j";
			echo "
			<tr>
			  <td ><img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' /></td>
              <td align='center'>NV-$data[numero]</td>
			  <td >$data[sucursal]</td>
              <td >$datoTrabajador[trabajador]</td> 
              <td >$data[cliente]</td>
              <td align='center'>$data[ruta]</td>
              <td align='center'>$fecha</td>              
			  <td align='center'><input type='checkbox' id='$idcheck' name='$idcheck'/></td>
			  <td style='display:none'>$datoTrabajador[idtrabajador]</td>
			  <td style='display:none'>$data[idnotaventa]</td>
			  <td style='display:none'>$data[idruta]</td>
			</tr>
			";
		}
		 
	 } 
	 
	 public function insertar()
	 {
		$fecha = $this->db->GetFormatofecha($_GET['fecha'], "/"); 
		$sql = "insert into rutaentrega(fecha,idusuario,estado) values 
		('$fecha',$_SESSION[id_usuario],1)";
		$this->db->consulta($sql);
		$codigo = $this->db->getMaxCampo("idrutaentrega","rutaentrega"); 
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0;$i < count($datos);$i++) {
		    $fila = $datos[$i];                 
			$idtrabajador = $fila[0];
			$idnota = $fila[1];
			$idruta = $fila[2];
			$sql = "insert into detallerutaentrega(idrutaentrega,idtrabajador,idnotaventa,idruta)
			values ($codigo,$idtrabajador,'$idnota','$idruta');";
			$this->db->consulta($sql);
		}
	 }
	 
	 public function modificar()
	 {
		$fecha = $this->db->GetFormatofecha($_GET['fecha'], "/"); 
		$sql = "update rutaentrega set 
		 fecha='$fecha',idusuario=$_SESSION[id_usuario] where idrutaentrega=$_GET[idtransaccion]";
		$this->db->consulta($sql);
		$codigo = $_GET['idtransaccion']; 
		$sql = "delete from detallerutaentrega where idrutaentrega=$codigo";
        $this->db->consulta($sql);
		
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0;$i < count($datos);$i++) {
		    $fila = $datos[$i];                 
			$idtrabajador = $fila[0];
			$idnota = $fila[1];
			$idruta = $fila[2];
			$sql = "insert into detallerutaentrega(idrutaentrega,idtrabajador,idnotaventa,idruta)
			values ($codigo,$idtrabajador,'$idnota','$idruta');";
			$this->db->consulta($sql);
		}
	 }
	 
	  
  }



  $ruta = new Drutaenetrega();
  switch($_GET['transaccion']) {
      case "listaNotas":
	      $ruta->getListaNotasVenta();
	  break;   
	  case "insertar":
	      $ruta->insertar();  
	  break;
	  case "modificar":
	      $ruta->modificar();
	  break;
  }

?>