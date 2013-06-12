<?php
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["transaccion"];
 
    
    if ($tipo == "insertar") {	
	    $fecha = $db->GetFormatofecha($_GET['fecha'],"/");
	    $sql = "insert into entregadinero(identrega,fecha,monto,acumulado,idtrabajador,tipo,idusuariorestaurante,estado,caja,
		  nulo,cortesia,credito,idusuariosistema) value(null,'$fecha','$_GET[monto]','$_GET[acumulado]','$_GET[idtrabajador]'
		  ,'$_GET[tipo]','$_SESSION[id_usuario]',1,'$_GET[caja]','$_GET[nulo]','$_GET[cortesia]','$_GET[credito]',
		  '$_SESSION[id_usuario]');";
	    $db->consulta($sql);
		
		$codigo = $db->getMaxCampo("identrega", "entregadinero");
		
		$sql = "select u.idsucursal,s.nombrecomercial from  usuariorestaurante u,sucursal s where u.idsucursal=s.idsucursal
		 and u.tipo='$_GET[tipo]' and u.idtrabajador=$_GET[idtrabajador];";
		$sucursal = $db->arrayConsulta($sql);
		insertarLibro($sucursal['idsucursal'], $codigo, $db, $_GET['caja'], $_GET['monto'], $_GET['acumulado']
		, $_SESSION['id_usuario'], $sucursal['nombrecomercial'], $_GET['tipo']);
    }   
	
	if ($tipo == "modificar") {
	    $fecha = $db->GetFormatofecha($_GET['fecha'],"/");
	    $sql = "update entregadinero set fecha='$fecha',monto='$_GET[monto]',acumulado='$_GET[acumulado]'
		    , idtrabajador='$_GET[idtrabajador]',tipo='$_GET[tipo]' ,idusuariorestaurante='$_SESSION[id_usuario]'
	        ,caja='$_GET[caja]',nulo='$_GET[nulo]',cortesia='$_GET[cortesia]',credito='$_GET[credito]'
			,idusuariosistema='$_SESSION[id_usuario]' where identrega='$_GET[idtransaccion]';";
	    $db->consulta($sql);	
		$sql = "select u.idsucursal,s.nombrecomercial from  usuariorestaurante u,sucursal s where u.idsucursal=s.idsucursal
		 and u.tipo='$_GET[tipo]' and u.idtrabajador=$_GET[idtrabajador];";
		$sucursal = $db->arrayConsulta($sql);
		insertarLibro($sucursal['idsucursal'], $_GET['idtransaccion'], $db, $_GET['caja'], $_GET['monto'], $_GET['acumulado']
		, $_SESSION['id_usuario'], $sucursal['nombrecomercial'], $_GET['tipo']);
	}
	
	function insertarDetalle($libro, $cuenta, $descripcion, $monto1, $monto2, $doc, $db)
    {
       $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values"
           ."($libro,'$cuenta','$descripcion',$monto1,$monto2,'$doc')";
       $db->consulta($sql);   
    }
   
    function insertarLibro($sucursal, $codigo, $db, $cuenta, $monto, $total, $usuario, $nombresucursal, $tipo)
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
	
	   $sql = "select idlibrodiario,idsucursal from librodiario where transaccion='Entrega Dinero Restaurante'"
	       ." and idtransaccion=$codigo;";  
	   $libro = $db->arrayConsulta($sql); 
	   $idlibro = $libro['idlibrodiario'];
	   if ($libro['idlibrodiario'] == "") {		
	       $sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa,idtransaccion,"
	           ."tipocambio,idusuario,estado,transaccion)" 
	           ."values('$num[num]','$num[sucursal]','Bolivianos','ingreso',now(),'venta discoteca.','$codigo'"
	           .",'$tc','$usuario',1,'Entrega Dinero Restaurante');"; 
	       $db->consulta($sql);   
	       $idlibro = $db->getMaxCampo("idlibrodiario","librodiario");
	   }
	
	   $sql = "delete from detallelibrodiario where idlibro=$idlibro";
	   $db->consulta($sql);
	   $descripcionLibro = "Nº $codigo/Sucursal: $nombresucursal/Venta Discoteca";
	   $sql = "select cuenta from configuracionsucursal where idsucursal=$sucursal";
  	   $cuentaSucursal = $db->arrayConsulta($sql);
	   $sql = "select anticiposueldo as 'cuenta' from configuracioncontable;"; 
	   $cuentaAnticipo = $db->arrayConsulta($sql);
	   $sql = "select exigibleapoyo as 'cuenta' from configuracionrestaurante;"; 
	   $cuentaRestaurante = $db->arrayConsulta($sql);
	   
	   
	   insertarDetalle($idlibro, $cuenta, $descripcionLibro, $monto, 0, 0, $db);
	   if (($total - $monto) > 0) {
		   if ($tipo == "fijo") { 
	           insertarDetalle($idlibro, $cuentaAnticipo['cuenta'], $descripcionLibro, ($total - $monto), 0, 0, $db);   
		   } else {
			   insertarDetalle($idlibro, $cuentaRestaurante['cuenta'], $descripcionLibro, ($total - $monto), 0, 0, $db);   
		   }
	   }	   
	   insertarDetalle($idlibro, $cuentaSucursal['cuenta'], $descripcionLibro, 0, $total, 0, $db);
   }
	
	
   
    if ($tipo == "consultaDeuda") {
		$sql = "select round(sum(a.efectivo),4)as 'debe' from atencion a,usuariorestaurante u 
            where a.idusuariorestaurante=u.idusuario and a.estado='cobrado' and u.tipo='$_GET[tipo]'
			 and u.idtrabajador=$_GET[idtrabajador]
             and credito=0 and socio=0 group by u.idtrabajador; ";
	    $debe = $db->arrayConsulta($sql);
		$sql = "select round(sum(d.precio*d.cantidad),4)as 'nuloD' from detalleatencion d,atencion a,usuariorestaurante u 
         where d.idatencion=a.idatencion and a.idusuariorestaurante=u.idusuario and a.estado='cobrado' 
         and u.tipo='$_GET[tipo]' and u.idtrabajador=$_GET[idtrabajador] and d.estado=0 group by u.idtrabajador;";
		$nuloD = $db->arrayConsulta($sql);
		$sql = "select round(sum(a.cortesia),4)as 'cortesiaD' from atencion a,usuariorestaurante u 
            where a.idusuariorestaurante=u.idusuario and a.estado='cobrado' and u.tipo='$_GET[tipo]'
			 and u.idtrabajador=$_GET[idtrabajador]
             and credito=0 and socio=0 group by u.idtrabajador;";
		$cortesiaD = $db->arrayConsulta($sql);
		$sql = "select round(sum(a.efectivo),4)as 'creditoD' from atencion a,usuariorestaurante u 
            where a.idusuariorestaurante=u.idusuario and a.estado='cobrado' and u.tipo='$_GET[tipo]'
			 and u.idtrabajador=$_GET[idtrabajador]
             and credito=1 and socio=0 group by u.idtrabajador;";
		$creditoD = $db->arrayConsulta($sql);
		 
		$sql = "select round(sum(acumulado),4)as 'haber',round(sum(nulo),4)as 'nulo',round(sum(cortesia),4)as 'cortesia' 
		,round(sum(credito),4)as 'credito' from entregadinero where idtrabajador=$_GET[idtrabajador] 
		    and tipo='$_GET[tipo]' and estado=1 group by idtrabajador;";
		$haber = $db->arrayConsulta($sql);		
		$total = $debe['debe'] - $haber['haber'];
		echo round($total, 2)."---";
		echo round(($nuloD['nuloD'] - $haber['nulo']) ,2)."---";
		echo round(($cortesiaD['cortesiaD'] - $haber['cortesia']), 2)."---";
		echo round(($creditoD['creditoD'] - $haber['credito']), 2)."---";
	}
   
 
?>