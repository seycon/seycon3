<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
  session_start();
  if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: ../index.php");	 
  }


  class Dpersonal {
	  
	 public $db;
	 public function Dpersonal() 
	 {
		include("../conexion.php");
		$this->db = new MySQL; 
	 }
	 
	 public function listarTrabajador()
	 {
		if ($_GET['tipo'] == "sinasignar") { 
		$sql = "select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),30)as 'nombre' 
					  from trabajador t where t.idtrabajador not in(
					  select h.idtrabajador
					   from historialpersonal h 
					  where h.tipo='cliente' and h.titulo='asignar' 
					   and h.estado=1  
					  ) 
					  and t.idtrabajador not in(					  
						select idtrabajador from historialpersonal where 
						tipo='falta' and fecha=current_date and estado=1
					  )	
					   and t.control='Monitorizado' and t.estado=1 
					  and t.nombre like '$_GET[texto]%' order by t.nombre limit 14;";	
		}
		if ($_GET['tipo'] == "notrabajo") {
		    $sql = "select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),30)as 'nombre'
			             from historialpersonal h,trabajador t where 
						 h.tipo='falta' and h.fecha=current_date and h.estado=1 
					     and h.idtrabajador=t.idtrabajador and t.nombre like '$_GET[texto]%' order by t.nombre limit 14;";
		}
		
		$producto = $this->db->consulta($sql);	  
		while ($dato = mysql_fetch_array($producto)) {
			   $codigo = $dato['idtrabajador'];
			   $item = "<li class='listatrabajador' onclick='getDatosPersonal($codigo);'
				>".ucfirst(strtolower($dato['nombre']))."</li>";		  
			   echo $item;		  
		}
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
	 
	 
	 public function getPersonal()
	 {
		$condicion = " and h.descripcion=$_GET[idcliente] "; 
		if ($_GET['idcliente'] == "") {
		   $condicion = "";	
		}		 
		$sql = "
		 select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),15)as 'nombre',
		 t.fotoprincipal as 'foto',left(c.nombre,40)as 'cliente',c.idcliente,c.nroguardias    
		 from historialpersonal h,trabajador t,cliente c  
		 where h.tipo='cliente' and h.titulo='asignar' 
		 $condicion 
		 and c.idcliente=h.descripcion 
		 and h.estado=1 and h.idtrabajador=t.idtrabajador 
		 order by c.idcliente,t.nombre;  ";
		 $dato = $this->db->consulta($sql);
		 $parametros = array();
		 while ($data = mysql_fetch_array($dato)) {
			$src = $data['foto']; 
			if ($data ['foto'] == "") {
		        $src = "files/modelo_sombraSeycon.png";	
		    }
			$cliente = strtoupper($data['cliente']);
			$nombre = ucfirst(strtolower($data['nombre'])); 
			$cardex = array($data['idtrabajador'], $nombre, $src, $cliente
			, $data['idcliente'], $data['nroguardias']);
			array_push($parametros, $cardex); 
		 }
		 echo json_encode($parametros);
	 }
	 
	 public function listaHorario()
	 {
		$sql = "
		  select  a.idtrabajador,left(concat(t.nombre,' ',t.apellido),28)as 'trabajador', 
 		   a.fechaingreso,a.hora as 'inicio',left(c.nombre,28)as 'cliente',
		  (select b.hora from asistencia b where b.idcliente=a.idcliente
		  and b.idtrabajador=a.idtrabajador and b.tipo='salida' and 
		  b.estado=1 and b.fecha=a.fecha)as 'salida',a.idcliente 
		   from asistencia a,cliente c,trabajador t where  
		  a.idtrabajador=$_GET[idtrabajador] and a.idcliente=c.idcliente 
		  and t.idtrabajador=a.idtrabajador and month(a.fecha)='$_GET[mes]' 
		  and year(a.fecha)='$_GET[anio]' and a.tipo='ingreso' and a.estado=1 
		  order by a.fecha";
		$consulta = $this->db->consulta($sql);
		$lista = "";
		$num = 0;
		while ($data = mysql_fetch_array($consulta)) {
			$fecha = $this->db->GetFormatofecha($data['fecha'], "-");
			$cliente = ucfirst(strtolower($data['cliente']));
			$trabajador = ucfirst(strtolower($data['trabajador']));
			$salida = "0:00:00";
			$estilo = "";
			if ($data['salida'] != "") {
			    $salida = "$data[salida]";	
			}
			$num++;
			if ($num % 2 == 0) {
				$estilo = "#EAEAEA";
			}
			$lista .= "<tr bgcolor='$estilo'>
                <td class='fila6_1' align='center'>
				<img src='css/images/edit.gif' title='Modificar'
				 alt='editar' border='0' onclick='openHorario(this)' class='cursor'/></td>
                <td class='fila6_1' align='center'>$num</td>
                <td class='fila6_1'>&nbsp;$trabajador</td>
                <td class='fila6_1' align='center'>$fecha</td>
                <td class='fila6_1'>&nbsp;$cliente</td>
                <td class='fila6_1' align='center'>$data[inicio]</td>
                <td class='fila6_1' align='center'>$salida</td>
				<td style='display:none'>$data[idtrabajador]</td>
				<td style='display:none'>$data[idcliente]</td>
              </tr>";
		} 
		echo $lista;
	 }
	 
	 
	 public function getParametros()
	 {
		 
		$sql = "select h.descripcion as 'idcliente' from historialpersonal h 
		   where h.tipo='cliente' and h.titulo='asignar' 	
		   and h.estado=1 group by h.descripcion 
		   having count(h.descripcion) $_GET[parametros] (select nroguardias 
		   from cliente where idcliente=h.descripcion);"; 
        $clientes = $this->db->consulta($sql);	
		$parametros = array();	 
        while ($cliente = mysql_fetch_array($clientes)) {					 
			$sql = "
			 select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),15)as 'nombre',
			 t.fotoprincipal as 'foto',left(c.nombre,40)as 'cliente',c.idcliente,c.nroguardias    
			 from historialpersonal h,trabajador t,cliente c  
			 where h.tipo='cliente' and h.titulo='asignar' 
			 and h.descripcion=$cliente[idcliente] 
			 and c.idcliente=h.descripcion 
			 and h.estado=1 and h.idtrabajador=t.idtrabajador 
			 order by c.idcliente,t.nombre;  ";
			 $dato = $this->db->consulta($sql);			 
			 while ($data = mysql_fetch_array($dato)) {
				$src = $data['foto']; 
				if ($data ['foto'] == "") {
					$src = "files/modelo_sombraSeycon.png";	
				}
				$cliente = strtoupper($data['cliente']);
				$nombre = ucfirst(strtolower($data['nombre'])); 
				$cardex = array($data['idtrabajador'], $nombre, $src, $cliente
				, $data['idcliente'], $data['nroguardias']);
				array_push($parametros, $cardex); 
			 }
		}
		 echo json_encode($parametros);
	 }
	 
	 
	 public function getPersonalTrabajador()
	 {
    	 $sql = "select descripcion from historialpersonal where tipo='cliente' and titulo='asignar' 
		 and idtrabajador=$_GET[idtrabajador] and estado=1";
		 $historia = $this->db->arrayConsulta($sql); 
		 $parametros = array();
		 if ($historia['descripcion'] != "") {		  
		  $sql = "
		   select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),15)as 'nombre',
		   t.fotoprincipal as 'foto',left(c.nombre,40)as 'cliente',c.idcliente  
		   from historialpersonal h,trabajador t,cliente c  
		   where h.tipo='cliente' and h.titulo='asignar' 
		   and h.descripcion=$historia[descripcion] 
		   and c.idcliente=h.descripcion 
		   and h.estado=1 and h.idtrabajador=t.idtrabajador 
		   order by c.idcliente,t.nombre;  ";
		   $dato = $this->db->consulta($sql);
		   while ($data = mysql_fetch_array($dato)) {
			  $src = $data['foto']; 
			  if ($data ['foto'] == "") {
				  $src = "files/modelo_sombraSeycon.png";	
			  }
			  $cliente = strtoupper($data['cliente']);
			  $nombre = ucfirst(strtolower($data['nombre'])); 
			  $cardex = array($data['idtrabajador'], $nombre, $src, $cliente, $data['idcliente']);
			  array_push($parametros, $cardex); 
		   }
		  }
		 echo json_encode($parametros);
	 }
	 
	 
	 public function asiganarCliente()
	 {
		 $sql = "select descripcion from historialpersonal where tipo='cliente' and titulo='asignar' 
		 and idtrabajador=$_GET[idtrabajador] and estado=1";
		 $historia = $this->db->arrayConsulta($sql);
		 if (!isset($historia['descripcion']) || $historia['descripcion'] == "" 
		       || $historia['descripcion'] != $_GET['idcliente']) {	 
			$sql = "update historialpersonal set estado=0 where tipo='cliente' 
			and titulo='asignar' and idtrabajador=$_GET[idtrabajador]";   
			$this->db->consulta($sql);
			$sql = "update historialpersonal set estado=0 where tipo='falta'  
		      and idtrabajador=$_GET[idtrabajador] and estado=1";
			$this->db->consulta($sql);
		    $this->insertarHistorial("cliente", "asignar", $_GET['idcliente'], $_GET['idtrabajador']);
			echo $_GET['idtrabajador'];
		 } else {
			echo "invalido"; 
		 }
	 }
	 
	 public function insertarRegistro($tipo)
	 {
		if ($tipo == "falta") {
			 $sql = "update historialpersonal set estado=0 where tipo='cliente' and titulo='asignar' 
		      and idtrabajador=$_GET[idtrabajador] and estado=1";
			$this->db->consulta($sql);
		}
		$this->insertarHistorial($tipo, $_GET['titulo'], $_GET['descripcion'], $_GET['idtrabajador']); 
	 }
	 
	 
	 public function registrarAsistencia()
	 {
		$sql = "select descripcion from historialpersonal where tipo='cliente' and titulo='asignar' 
		 and idtrabajador=$_GET[idtrabajador] and estado=1";
		$historia = $this->db->arrayConsulta($sql); 
		if ($historia['descripcion'] != "") {
			$sql = "select idasistencia,fechaingreso,fechasalida from asistencia 
			where idtrabajador='$_GET[idtrabajador]' order by idasistencia desc limit 1";
			$asistencia = $this->db->arrayConsulta($sql);
			if (($asistencia['idasistencia'] == "" && $_GET['tipo'] == "ingreso") || ($_GET['tipo'] == "ingreso" && 
			   $asistencia['fechaingreso'] != "0000-00-00" && $asistencia['fechasalida'] != "0000-00-00") || 
			   ($_GET['tipo'] == "salida" && $asistencia['fechasalida'] == "0000-00-00")) {
				$this->insertarAsistencia($_GET['tipo'], $asistencia['idasistencia'],
				$_GET['idtrabajador'], $historia['descripcion']);	
			} else {
				echo "invalido";
			}
		} else {
		    echo "cliente";	
		}
	 }	 
	 
	 public function insertarAsistencia($tipo, $idasistencia, $idtrabajador, $idcliente)
	 {
		if ($tipo == "ingreso") { 
			$sql = "insert into asistencia(fechaingreso,horaingreso,fechasalida,horasalida
			,idcliente,idtrabajador,idusuario,estado) 
			values(CURDATE(),CURTIME(),'','','$idcliente','$idtrabajador',$_SESSION[id_usuario],1)"; 
		}
		if ($tipo == "salida") {
		    $sql = "update asistencia set fechasalida=CURDATE(),horasalida=CURTIME() where 
			idasistencia=$idasistencia;";	
		}
		$this->db->consulta($sql);
	 }
	 
	 public function insertarHistorial($tipo, $titulo, $descripcion, $idtrabajador)
	 {
		$sql = "insert into historialpersonal(fecha,tipo,titulo,descripcion,idtrabajador,idusuario,estado) values 
		(now(),'$tipo','$titulo','$descripcion','$idtrabajador',$_SESSION[id_usuario],1)";
		$this->db->consulta($sql);		
	 }
	 
	 public function getDatosPersonales()
	 {
		$sql = "select idtrabajador,left(concat(nombre,' ',apellido),25)as 'nombre',
		left(direccion,25)as 'direccion',left(telefono,25)as 'telefono'
		,left(celular,25)as 'celular',fotoprincipal     
		from trabajador where idtrabajador=$_GET[idtrabajador]";
		$trabajador = $this->db->arrayConsulta($sql);
		$sql = "select left(celular,25)as 'celular' from conyugue where idtrabajador=$_GET[idtrabajador]";
		$conyugue = $this->db->arrayConsulta($sql);
		$sql = "select descripcion as 'cliente' from historialpersonal where tipo='cliente' and titulo='asignar' 
		 and idtrabajador=$_GET[idtrabajador] and estado=1";
		$historia = $this->db->arrayConsulta($sql);
		if (!isset($historia['cliente'])|| $historia['cliente'] == "") {
		    $idcliente = "";
			$cliente = "";	
		} else {
		    $sql = "select idcliente,left(nombre,25)as 'nombre'
			 from cliente where idcliente=$historia[cliente]";
			$datoCliente = $this->db->arrayConsulta($sql);
			$idcliente = $datoCliente['idcliente'];
			$cliente = $datoCliente['nombre'];				
		}
		$src = $trabajador['fotoprincipal'];
		if ($trabajador['fotoprincipal'] == "") {
		    $src = "files/modelo_sombraSeycon.png";	
		}
		
		if ($conyugue['celular'] == "") {
		   $telefono = "";	
		}
		
		$sql = "
		  select a.fecha,a.hora as 'inicio',left(c.nombre,20)as 'cliente',
		  (select b.hora from asistencia b where b.idcliente=a.idcliente
		  and b.idtrabajador=a.idtrabajador and b.tipo='salida' and 
		  b.estado=1 and b.fecha=a.fecha)as 'salida'
		   from asistencia a,cliente c where 
		  a.idtrabajador=$_GET[idtrabajador] and a.idcliente=c.idcliente 
		  and a.tipo='ingreso' and a.estado=1 
		  order by a.fecha desc limit 10; ";
		$consulta = $this->db->consulta($sql);
		$lista = "";
		$num = 0;
		while ($data = mysql_fetch_array($consulta)) {
			$fecha = $this->db->GetFormatofecha($data['fecha'], "-");
			$cliente = ucfirst(strtolower($data['cliente']));
			$salida = "";
			if ($data['salida'] != "") {
			    $salida = "$data[salida]";	
			}
			$num++;
			$lista .= "<tr>
			   <td align='center' class='celdah_3'>$num</td>
               <td align='center' class='celdah_3'>$fecha</td>
               <td class='celdah_3' >$cliente</td>
               <td class='celdah_3' align='center'>$data[inicio]</td>     
               <td class='celdah_3' align='center'>$salida</td></tr> ";
		}
		
		$parametros = array();
		$parametros[0] = $trabajador['idtrabajador'];
		$parametros[1] = $trabajador['nombre'];
		$parametros[2] = $trabajador['direccion'];
		$parametros[3] = $trabajador['telefono'];
        $parametros[4] = $trabajador['celular'];
		$parametros[5] = $telefono;
		$parametros[6] = $cliente;
		$parametros[7] = $idcliente;
  		$parametros[8] = $src;
		$parametros[9] = $lista;
		echo json_encode($parametros);
		
	 }
	 
	 public function filtro($cadena)
	 {
		return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	 }
	 
	  
  }



  $personal = new Dpersonal();
  switch($_GET['transaccion']) {
      case "busqueda":
	      $personal->listarTrabajador();
	  break;   
	  case "asignarcliente":
	      $personal->asiganarCliente();  
	  break;
	  case "datospersonales":
	      $personal->getDatosPersonales();
	  break;
	  case "antecedente":
	      $personal->insertarRegistro("antecedente");
	  break;
	  case "falta":
	      $personal->insertarRegistro("falta");
	  break;
	  case "asistencia":
	      $personal->registrarAsistencia();
	  break;
	  case "cliente":
	      $personal->getPersonal();
	  break;
	  case "trabajador":
	      $personal->getPersonalTrabajador();
	  break;
	  case "parametro":
	      $personal->getParametros();
	  break;
	  case "listaHorario":
	      $personal->listaHorario();
	  break;
  }

?>