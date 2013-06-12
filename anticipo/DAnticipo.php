<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: ../index.php");	
	}


    class Danticipo {
	   
	   public $db; 	
		
	   public function Danticipo() 
	   {
	       include("../conexion.php");
	       $this->db = new MySQL();  
	   }
	   
	   public function listarTrabajadores()
	   {
		   $idsucursal = $_GET['idsucursal']; 
		   $sql = "select idtrabajador,left(concat(nombre,' ',apellido),20)as 'nombre' from trabajador 
		   where estado=1 and idsucursal=$idsucursal;";
		   echo "<option value=''> -- Seleccione -- </option>";
		   $this->db->imprimirCombo($sql);  
	   }
	   
	   public function datosTrabajador() 
	   {
		   $idtrabajador = $_GET['idtrabajador'];
		   $fecha = explode("/", $_GET['fecha']);
		   $sql = "select * from trabajador where idtrabajador=$idtrabajador";
		   $dato = $this->db->arrayConsulta($sql);
		   echo number_format($dato['sueldobasico'], 2)."---";  
		   $sql = "select sum(anticipo)as 'anticipo' from anticipo where month(fecha)=$fecha[1]  
		   and year(fecha)=$fecha[2] and idtrabajador=$idtrabajador and estado=1;";
		   $datoAnticipo = $this->db->arrayConsulta($sql);
		   echo number_format($datoAnticipo['anticipo'], 2)."---";
	   }
	   
	   public function getAnticipos()
	   {
		   $idtrabajador = $_GET['idtrabajador'];
		   $fecha = explode("/", $_GET['fecha']);
		   $sql = "select sum(anticipo)as 'anticipo' from anticipo where month(fecha)=$fecha[1]  
			and year(fecha)=$fecha[2] and idtrabajador=$idtrabajador and estado=1;";
		   $datoAnticipo = $this->db->arrayConsulta($sql);
		   echo number_format($datoAnticipo['anticipo'], 2);	  
	   }
	   
	   public function insertar()
	   {
		   $fecha = $this->db->GetFormatofecha($_GET['fecha'],"/");
		   $sueldobasico = $_GET['sueldobasico'];
		   $numero = $this->db->getNextID("numero","anticipo where idsucursal='$_GET[idsucursal]'"); 	  
		   $sql = "INSERT INTO anticipo VALUES (NULL,'$numero','".$this->filtro($fecha)."','"
		   .$this->filtro($_GET['egreso'])."','".$this->filtro($_GET['documento']).
		   "','".$this->filtro($_GET['idtrabajador'])."','".$this->filtro($_GET['idsucursal'])
		   ."','".$this->filtro($_GET['glosa'])."','$sueldobasico','".$this->filtro($_GET['anticipo'])
		   ."',$_SESSION[id_usuario],'1');";   
		   $this->db->consulta($sql);
		   $codigo = $this->db->getMaxCampo('idanticipo','anticipo');
		   $this->insertarLibro($this->filtro($_GET['idsucursal']), 'Bolivianos', $fecha, $codigo
		   , $this->filtro($_GET['tipoCambioBs']), $_SESSION['id_usuario']
		   , $this->filtro($_GET['anticipo']), $this->filtro($_GET['egreso'])
		   , $_GET['glosa'], $_GET['idtrabajador'], $_GET['documento']);		
		   $this->actualizarPlanilla();   
	   }
	   	   
	   public function actualizarPlanilla()
	   {
		   $fechaBase = explode("/", $_GET['fecha']);
		   $sql = "select idplanilla  
			 from planilla   where month(fecha)=$fechaBase[1] 
			 and year(fecha)=$fechaBase[2] and idtrabajador=$_GET[idtrabajador];";
		   $datoPlanilla = $this->db->arrayConsulta($sql);
		   if ($datoPlanilla['idplanilla'] != "") {
		       $sql = "select sum(anticipo) as 'anticipo' from anticipo 
			           where month(fecha)=$fechaBase[1] and 
					  year(fecha)=$fechaBase[2] and idtrabajador=$_GET[idtrabajador]
					   and estado=1 GROUP BY idtrabajador;";				 			   
			   $anticipoTotal = $this->db->arrayConsulta($sql);
			   $anticipoCalculado = ($anticipoTotal['anticipo'] == "") ? 0 : $anticipoTotal['anticipo'];
			   $sql = "update planilla set anticipo=$anticipoCalculado where idplanilla=$datoPlanilla[idplanilla];";  
			   $this->db->consulta($sql);
			   $sql = "update planilla set totaldescuento=afp+anticipo where idplanilla=$datoPlanilla[idplanilla];";
			   $this->db->consulta($sql);
          }   		   
	   }
	   	   
	   public function modificar()
	   {
		   $fecha = $this->db->GetFormatofecha($_GET['fecha'],"/");
		   $sueldobasico = $_GET['sueldobasico'];	  
		   $sql = "UPDATE anticipo SET fecha='".$this->filtro($fecha)
		   ."', egreso='".$this->filtro($_GET['egreso'])
		   ."', documento='".$this->filtro($_GET['documento']).
			"', idtrabajador='".$this->filtro($_GET['idtrabajador'])
			."', idsucursal='".$this->filtro($_GET['idsucursal'])
			."', glosa='".$this->filtro($_GET['glosa']).
			"', sueldobasico='$sueldobasico', anticipo='".$this->filtro($_GET['anticipo'])
			."',idusuario=$_SESSION[id_usuario] WHERE idanticipo= '".$_GET['idanticipo']."';"; 
			$this->db->consulta($sql); 
			$this->modificarLibro($this->filtro($_GET['idsucursal']), 'Bolivianos', $fecha
			, $this->filtro($_GET['idanticipo']), $this->filtro($_GET['tipoCambioBs']),
			$_SESSION['id_usuario'], $this->filtro($_GET['anticipo']), $this->filtro($_GET['egreso'])
			, $this->filtro($_GET['glosa']), $this->filtro($_GET['idtrabajador']), $_GET['documento']);	
			$this->actualizarPlanilla(); 	   
	   }
	   	   
	   public function filtro($cadena)
	   {
	       return htmlspecialchars(strip_tags($cadena), ENT_QUOTES);	
	   }
	   

       public function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc
	                                 , $usuario, $monto, $cuenta, $glosa, $trabajador, $documento)
	   {
			$sql = "select max(l.numero)+1 as 'num',$sucursal as 'sucursal' from librodiario l 
			where l.idsucursal=$sucursal group by l.idsucursal";  
			$num = $this->db->arrayConsulta($sql);  	
			if (!isset($num['num'])) {
			    $num['num'] = '1';
			    $num['sucursal'] = $sucursal;
			}	
			$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha
			,glosa,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
			'$num[num]','$num[sucursal]','$moneda','egreso','$fecha','$glosa','$codigo'
			,'$tc','$usuario',1,'Anticipo Sueldo');"; 
			$this->db->consulta($sql);
			$libro = $this->db->getMaxCampo("idlibrodiario","librodiario"); 
			$datoSucursal = $this->db->arrayConsulta("select nombrecomercial from sucursal where idsucursal=$sucursal");
			$datoTrabajador = $this->db->arrayConsulta("select *from trabajador where idtrabajador=$trabajador");
			$descripcionLibro = "Anticipo Sueldo Nº $codigo/Trabajador:$datoTrabajador[nombre] 
			$datoTrabajador[apellido]/Sucursal:$datoSucursal[nombrecomercial]";
			$sql = "select *from configuracioncontable;";
			$inventario = $this->db->arrayConsulta($sql);
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
			values($libro,'$inventario[anticiposueldo]','$descripcionLibro ',$monto,0,'$documento')";
			$this->db->consulta($sql);
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
			values($libro,'$cuenta','$descripcionLibro ',0,$monto,'$documento')";
			$this->db->consulta($sql);
	   }


       function modificarLibro($sucursal, $moneda, $fecha, $codigo, $tc
	                            , $usuario, $monto, $cuenta, $glosa, $trabajador, $documento) {
			$sql = "select idlibrodiario,idsucursal from librodiario where 
			transaccion='Anticipo Sueldo' and idtransaccion=$codigo;";  
			$libro = $this->db->arrayConsulta($sql);
			
			$num = $this->db->arrayConsulta($sql);  
			if ($libro['idsucursal'] != $sucursal) {
				$sql = "select max(l.numero)+1 as 'num',$sucursal as 'sucursal' from librodiario l 
				where l.idsucursal=$sucursal group by l.idsucursal";  
				$num = $this->db->arrayConsulta($sql);  	
				  if (!isset($num['num'])) {
				     $num['num'] = '1';
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
			$datoSucursal = $this->db->arrayConsulta("select nombrecomercial from sucursal where idsucursal=$sucursal");
			$datoTrabajador = $this->db->arrayConsulta("select *from trabajador where idtrabajador=$trabajador");
			$descripcionLibro = "Anticipo Sueldo Nº $codigo/Trabajador:$datoTrabajador[nombre] 
			$datoTrabajador[apellido]/Sucursal:$datoSucursal[nombrecomercial]";
			$sql = "select *from configuracioncontable;";
			$inventario = $this->db->arrayConsulta($sql);	
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
			values($libro[idlibrodiario],'$inventario[anticiposueldo]','$descripcionLibro',$monto,0,'$documento')";
			$this->db->consulta($sql);
			$sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento) 
			values($libro[idlibrodiario],'$cuenta','$descripcionLibro',0,$monto,'$documento')";
			$this->db->consulta($sql);
		}
	   
	   
	}
	
	
	$anticipo = new Danticipo();
	
	switch($_GET['transaccion']) {
	  case "trabajadores":
	      $anticipo->listarTrabajadores();
	  break;
	  case "sueldo":
	      $anticipo->datosTrabajador();
	  break;	
	  case "anticipos":
	      $anticipo->getAnticipos(); 
	  break;
	  case "insertar":
	      $anticipo->insertar(); 
	  break;
	  case "modificar":
	      $anticipo->modificar();
	  break;	  
	}
	

?>