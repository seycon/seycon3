<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	class Daguinaldo {
	
	  public function Daguinaldo()
	  {
		  include("../conexion.php");
		  $this->db = new MySQL; 
	  }
	 
	  public function getConsulta()
	  {

		$anio = $_GET['anio'];
		$fecha = $_GET['anio']."/".$_GET['mes']."/".date("d");
		$sucursal = $_GET['sucursal'];  
		$sql = "select t.idtrabajador,t.nombre,t.fechaingreso,(ps.totalganado)as 'sept',
		(po.totalganado)as 'oct',(pn.totalganado)as 'nov' from
		 trabajador t,planilla ps,planilla po,planilla pn
		 where t.idtrabajador=ps.idtrabajador
		 and t.idtrabajador=po.idtrabajador
		 and t.idtrabajador=pn.idtrabajador
		 and month(ps.fecha)=09
		 and month(po.fecha)=10
		 and month(pn.fecha)=11  
		 and t.idsucursal=$sucursal
		 and year(ps.fecha)=$anio
		 and year(po.fecha)=$anio
		 and year(pn.fecha)=$anio;";
		 $consulta = $this->db->consulta($sql);
		 while ($dato = mysql_fetch_array($consulta)){
			$sql = "select idperiodo from periodotrabajo where idtrabajador=$dato[idtrabajador] and gestion=$anio";
			$periodo = $this->db->arrayConsulta($sql);
			if (!isset($periodo['idperiodo'])) {
			  $sql = "insert into periodotrabajo values(null,'$anio','$dato[idtrabajador]','3','0');";	
			  $this->db->consulta($sql);
			}
		 }

		 $sql = "select t.idtrabajador,concat(t.nombre,' ',t.apellido)as 'nombre',
		 t.sexo,c.cargo,date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso',
		 (ps.totalganado)as 'sept',
		 (po.totalganado)as 'oct',(pn.totalganado)as 'nov',pt.meses,pt.dias from
		 trabajador t,planilla ps,planilla po,planilla pn,cargo c,periodotrabajo pt
		 where t.idtrabajador=ps.idtrabajador
		 and pt.idtrabajador=t.idtrabajador 
		 and pt.gestion=$anio 
		 and t.idcargo=c.idcargo 
		 and t.idtrabajador=po.idtrabajador
		 and t.idtrabajador=pn.idtrabajador
		 and month(ps.fecha)=09
		 and month(po.fecha)=10
		 and month(pn.fecha)=11  
		 and t.idsucursal=$sucursal
		 and year(ps.fecha)=$anio
		 and year(po.fecha)=$anio
		 and year(pn.fecha)=$anio;";   
		
		 
		$resul = $this->db->consulta($sql);
		$i = 0;
		  while ($dato = mysql_fetch_array($resul)) {
			 $baseCalculo = ($dato['sept'] + $dato['oct'] + $dato['nov']) / 3;
			 $meses = $dato['meses'];
			 $dias = $dato['dias'];
			 $op1 = ($meses == 0) ? 0 : (($baseCalculo / 12) * ($meses));
			 $op2 = ($dias == 0) ? 0 : (($baseCalculo / 360) * ($dias));
			 $liquido = $op1 + $op2;
			 $i++; 
			echo "
			<tr bgcolor='#FFFFFF'>
			  <td><img src='bonos/edit.png' onclick='recuperarDatos($i,$dato[idtrabajador])'/></td>
			  <td class='letra'>$i</td>
			  <td class='letra'>$dato[nombre]</td>
			  <td class='letra'>$dato[fechaingreso]</td>
			  <td class='letra'>".number_format($baseCalculo,2)."</td>
			  <td class='letra'>$dato[meses]</td>
			  <td class='letra'>$dato[dias]</td>
			  <td class='letra'>".number_format($liquido,2)."</td>
				  
			</tr>
			";  
		  }
		  
		  
		  $sql = "select sum((((((ps.totalganado)+(po.totalganado)+(pn.totalganado))/3)/12)*pt.meses)
		  +	(((((ps.totalganado)+(po.totalganado)+(pn.totalganado))/3)/360)*pt.dias)) as 'liquido'
			  from
		 trabajador t,planilla ps,planilla po,planilla pn,cargo c,periodotrabajo pt
		 where t.idtrabajador=ps.idtrabajador
		 and pt.idtrabajador=t.idtrabajador 
		 and pt.gestion=$anio 
		 and t.idcargo=c.idcargo 
		 and t.idtrabajador=po.idtrabajador
		 and t.idtrabajador=pn.idtrabajador
		 and month(ps.fecha)=09
		 and month(po.fecha)=10
		 and month(pn.fecha)=11  
		 and t.idsucursal=$sucursal
		 and year(ps.fecha)=$anio
		 and year(po.fecha)=$anio
		 and year(pn.fecha)=$anio;";
		 $total = $this->db->arrayConsulta($sql);	 
		 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
		 $tc = $this->db->getCampo('dolarcompra',$sql); 		
		 $sql = "select *from configuracioncontable;";
		 $cuentaContable = $this->db->arrayConsulta($sql);
		 $detalleLibro = $this->getDetalleLibro($sucursal,$anio);
		 
		 $sql = "select idlibrodiario,idsucursal from librodiario where transaccion='Aguinaldo' and idtransaccion=$anio;"; 
		 $existe = $this->db->arrayConsulta($sql);
		 if ($existe['idlibrodiario'] != "") {
		   $libro = $this->modificarLibro($sucursal,"Bolivianos",$anio,$tc,$_SESSION['id_usuario'],$db,"Aguinaldo ".$anio);
		   $sql = "delete from detallelibrodiario where idlibro=$libro"; 
		   $this->db->consulta($sql);
		 } else {	 
		   $libro = $this->insertarLibro($sucursal,"Bolivianos",$anio,$tc,$_SESSION['id_usuario'],$db,"Aguinaldo ".$anio);
		 }
		 $this->insertarDetalle($libro,$cuentaContable['aguinaldo'],$detalleLibro,$total['liquido'],0,0);
		 $this->insertarDetalle($libro,$cuentaContable['aguinaldo'],$detalleLibro,0,$total['liquido'],0); 		  
		 exit();		  
	  }
	
  	  public function insertar()
 	  {
	     $sql = "update periodotrabajo set meses='$_GET[meses]',dias='$_GET[dias]' 
	     where idtrabajador='$_GET[idtrabajador]' and gestion='$_GET[gestion]';";	 
	     $this->db->consulta($sql);
	     echo "";	 
	     exit();	 
	  }
	 
	 
	function insertarDetalle($idlibro, $cuenta, $descripcion, $debe, $haber, $documento)
	{
	   $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
	   values($idlibro,'$cuenta','$descripcion','$debe','$haber','$documento')";
	   $this->db->consulta($sql);
	}
	
	function getDetalleLibro($sucursal, $year)
	{	
	  $sql = "select * from sucursal where idsucursal=$sucursal";
	  $datosSucursal = $this->db->arrayConsulta($sql);
	  $descripcionLibro = "Aguinaldo del Periodo $year/Sucursal: $datosSucursal[nombrecomercial]";
	  return $descripcionLibro;	
	}
	 
	function insertarLibro($sucursal, $moneda, $codigo, $tc, $usuario, $glosa)
	{
		$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' 
		from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
		$num = $this->db->arrayConsulta($sql); 
		if (!isset($num['num'])) {
		    $num['num'] = 1;
		    $num['sucursal'] = $sucursal;
		}		 	
		$sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha
		,glosa,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
		'$num[num]','$num[sucursal]','$moneda','egreso',now(),'$glosa','$codigo','$tc','$usuario',1,'Aguinaldo');"; 
		$this->db->consulta($sql);
		$libro = $this->db->getMaxCampo("idlibrodiario","librodiario"); 
		return $libro;
	}
	
	function modificarLibro($sucursal, $moneda, $codigo, $tc, $usuario, $db, $glosa)
	{
		$sql = "select idlibrodiario,idsucursal from librodiario 
		 where transaccion='Aguinaldo' and idtransaccion=$codigo;";  
		$libro = $this->db->arrayConsulta($sql); 
	 
		if ($libro['idsucursal'] != $sucursal) {
			$sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal'
			 from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";
			$num = $this->db->arrayConsulta($sql);  	
			if (!isset($num['num'])) {
			   $num['num'] = 1;
			   $num['sucursal'] = $sucursal;
			}
			  $update = "idsucursal='$num[sucursal]',numero=$num[num],";
		} else {
		  $update = "";	
		}	
		
		$sql = "update librodiario set $update moneda='$moneda',fecha=now()
		,tipocambio='$tc',idusuario='$usuario',glosa='$glosa'  
		where idlibrodiario=$libro[idlibrodiario];"; 
		$this->db->consulta($sql);
		return $libro['idlibrodiario'];
	}
	
	}
	
	$aguinaldo = new Daguinaldo();
	switch($_GET['transaccion']) {
		case "consulta":
			$aguinaldo->getConsulta();
		break;   
		case "insertar":
			$aguinaldo->insertar();
		break;
	}

?>