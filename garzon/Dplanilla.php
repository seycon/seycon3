<?php
    session_start(); 
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["tipo"];
	
	
	if ($tipo == "insertar") {
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		$sql = "insert into planillaapoyo(fecha,idtrabajador,monto,estado) 
		values('$fecha','$_GET[idtrabajador]',$_GET[total],1)";
		$db->consulta($sql);
		$idplanilla = $db->getMaxCampo("idplanilla", "planillaapoyo");
		
		$datos =  json_decode(stripcslashes($_GET['detalle']));
		for ($i = 0; $i < count($datos); $i++) {
		    $fila = $datos[$i];
			$fecha = $db->GetFormatofecha($fila[0], "/");
			$sql = "insert into detalleplanilla(idplanilla,idsucursal,fecha,venta,comision,botella,haber,faltante)
			values($idplanilla, $fila[1], '$fecha', $fila[2], $fila[3], $fila[4], $fila[5], $fila[6])";
		    $db->consulta($sql);
			$sql = "update atencion a,usuariorestaurante u set a.tipo='Planilla' 
			where a.idsucursal=$fila[1] and date(a.fecha)='$fecha' and 
            a.estado='cobrado' and a.idusuariorestaurante=u.idusuario and u.idtrabajador='$_GET[idtrabajador]'";
			$db->consulta($sql);
		}
		
	   $sql = "SELECT s.idsucursal, LEFT( s.nombrecomercial, 20 ) AS  'sucursal'
       FROM usuariorestaurante u, sucursal s WHERE u.idtrabajador=$_GET[idtrabajador] AND u.tipo =  'apoyo'
       AND u.estado=1 AND u.idsucursal = s.idsucursal;";	
	   $sucursal = $db->arrayConsulta($sql);
       insertarLibro($sucursal['idsucursal'], $idplanilla, $db, $_GET['caja'], $_GET['total']
	   , $_SESSION['id_usuario'], $sucursal['sucursal'], $_GET['anticipo']);		
	   exit();
	}
	
	
	function insertarDetalle($libro, $cuenta, $descripcion, $monto1, $monto2, $doc, $db)
    {
       $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values"
           ."($libro,'$cuenta','$descripcion',$monto1,$monto2,'$doc')";
       $db->consulta($sql);   
    }
   
    function insertarLibro($sucursal, $codigo, $db, $cuenta, $monto, $usuario, $nombresucursal, $anticipo)
    {
       $sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' from librodiario l where"
	       ." l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
	   $num = $db->arrayConsulta($sql); 
	   if (!isset($num['num'])) {
  	       $num['num'] = 1;
	       $num['sucursal'] = $sucursal;
	   }	
	
	   $sql = "select *from configuracioncontable;";
	   $contabilidad = $db->arrayConsulta($sql);	
	   $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	   $tc = $db->getCampo('dolarcompra',$sql); 
	   $sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa,idtransaccion,"
	       ."tipocambio,idusuario,estado,transaccion)" 
	       ."values('$num[num]','$num[sucursal]','Bolivianos','ingreso',now(),'Planilla de Apoyo.','$codigo'"
	       .",'$tc','$usuario',1,'Planilla de Apoyo Restaurante');"; 
	   $db->consulta($sql);   
	   $idlibro = $db->getMaxCampo("idlibrodiario","librodiario");
	   
	
	   $descripcionLibro = "Nº $codigo/Sucursal: $nombresucursal/Planilla de Apoyo";
	   $sql = "select exigibleapoyo as 'exigible',gastosapoyo as 'gastos' from configuracionrestaurante;"; 
	   $cuentaRestaurante = $db->arrayConsulta($sql);
	   
	   
	   insertarDetalle($idlibro, $cuentaRestaurante['gastos'], $descripcionLibro, ($monto + $anticipo), 0, 0, $db);
	   if ($anticipo > 0) {		  
	           insertarDetalle($idlibro, $cuentaRestaurante['exigible'], $descripcionLibro, 0, $anticipo, 0, $db);		
	   }	   
	   insertarDetalle($idlibro, $cuenta, $descripcionLibro, 0, $monto, 0, $db);
   }
	
	
	
	if ($tipo == "pendientes") {
		$idtrabajador = $_GET['idapoyo'];
		$sql = "select left(concat(nombre,' ',apellido),40) as 'nombre',left(cargo,20)as 'cargo',honorario from personalapoyo 
	     where estado=1 and idpersonalapoyo=$idtrabajador;";
	    $datoTrabajador = $db->arrayConsulta($sql);
		$sql = "select $datoTrabajador[cargo]$datoTrabajador[honorario] as 'haber' from configuracionrestaurante;";
     	$haber = $db->arrayConsulta($sql);
		$haber = $haber['haber'];
		
	    $sql = "select round(sum(ap.efectivo),2)as 'efectivo' ,date(fecha) as 'fecha',left(s.nombrecomercial,20) as 'sucursal',
		(
		 select round(sum(b.descuento*cantidad),2) as 'botella' 
		 from atencion a,detalleatencion d,usuariorestaurante u,
		 bonoproducto b,sucursal s  
		 where d.idatencion=a.idatencion and d.estado=1 and a.idusuariorestaurante=u.idusuario 
		 and u.idtrabajador=$idtrabajador and u.tipo='apoyo' and a.estado='cobrado' 
		 and s.idsucursal=a.idsucursal and a.idsucursal=ap.idsucursal and date(fecha)=date(ap.fecha)  
		 and d.idcombinacion=b.idcombinacion group by s.idsucursal,date(fecha)
		) as 'botella',
		(
		select round(sum(acumulado-monto),2)as 'faltante' 
		from entregadinero where estado=1 
		and idtrabajador=$idtrabajador and fecha=date(ap.fecha) and tipo='apoyo'
		) as 'faltante',s.idsucursal  
		 
		 from atencion ap,usuariorestaurante u,sucursal s  
		 where ap.idusuariorestaurante=u.idusuario and ap.estado='cobrado' and
		 ap.credito=0 and ap.socio=0 and u.tipo='apoyo' 
		 and u.idtrabajador=$idtrabajador and s.idsucursal=ap.idsucursal and ap.tipo!='Planilla'   
		  group by s.idsucursal,date(fecha) order by date(fecha);";	
	   $dato = $db->consulta($sql);
	   $sql = "select * from configuracionrestaurante;";
	   $configuracionR = $db->arrayConsulta($sql);	   
	   $ventaminima = $configuracionR['ventaminima'];
	   $pendientes = "";
	   
	   while ($data = mysql_fetch_array($dato)){
		  $fecha = $db->GetFormatofecha($data['fecha'], "-"); 
		  $botella = ($data['botella'] == "") ? 0 : $data['botella'];
		  $comision = 0;
		   if ($data['efectivo'] >= $ventaminima){
			  $comision = 0.04 * $data['efectivo']; 
		   }
		   
		   $totalH = 0;
		   if ($comision == 0){
			  $totalH = $haber; 
		   }		  
		  
		  $pendientes = $pendientes.
		   "<tr>
			  <td>$data[sucursal]</td>
			  <td align='center'>$fecha</td>
			  <td align='center'>".number_format($data['efectivo'], 2)."</td>
			  <td>".number_format($comision, 2)."</td>
			  <td>".number_format($botella, 2)."</td>
			  <td>".number_format($totalH, 2)."</td>
			  <td>".number_format($data['faltante'], 2)."</td>
			  <td align='center'><input type='checkbox' id='1' name='1' onclick='setTotalesOption(this,this.checked)' /></td>
			  <td align='center' style='display:none'>$data[idsucursal]</td>
			  <td align='center' style='display:none'>0</td>
			</tr>"; 
	   }
	   
		echo $pendientes;  	
		exit();
	}

?>