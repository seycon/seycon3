<?php 
  session_start();
  include("conexion.php");
  if (!isset($_SESSION['softLogeoadmin'])) {
	header("Location: index.php");	
  }
  
  
  function descontarEgreso($idegreso, $db) {
	 $sql = "select de.iddetalleingreso,de.cantidad as 'cantidadEgreso',de.unidadmedida as    
	 'unidadmedidaegreso',di.cantidadactual,di.unidadmedida,p.unidaddemedida as 'UM',p.unidadalternativa as 'UA',p.conversiones 
	  from detalleegresoproducto de,detalleingresoproducto di,producto p where de.iddetalleingreso=di.iddetalleingreso 
	  and de.idproducto=p.idproducto and de.idegresoprod=$idegreso;";	
     $dato = $db->consulta($sql);
	while($detalle = mysql_fetch_array($dato)){
		if ($detalle['unidadmedidaegreso'] == $detalle['unidadmedida']) {
		  $saliente = $detalle['cantidadEgreso'];
		} else {
		   if ($detalle['unidadmedidaegreso'] == $detalle['UA']) {
			$saliente = $detalle['cantidadEgreso'] / $detalle['conversiones'];
		   } else {
			$saliente = $detalle['cantidadEgreso'] * $detalle['conversiones'];
		   }
		}
		$sql = "update detalleingresoproducto di set di.cantidadactual=di.cantidadactual+$saliente
		 where di.iddetalleingreso=$detalle[iddetalleingreso];";
		$db->consulta($sql);
	}	 
  }
  
  function descontarTraspaso($idtraspaso, $db) { 
    $sql = "select de.iddetalleingreso,de.cantidad as 'cantidadEgreso',de.unidadmedida as   
	'unidadmedidaegreso',di.cantidadactual,di.unidadmedida,p.unidaddemedida as 'UM',p.unidadalternativa as 'UA',p.conversiones  
	from detalletraspaso de,detalleingresoproducto di,producto p where de.iddetalleingreso=di.iddetalleingreso 
	and de.idproducto=p.idproducto and de.idtraspaso=$idtraspaso;";	
    $dato = $db->consulta($sql);
	while($detalle = mysql_fetch_array($dato)) {
		if ($detalle['unidadmedidaegreso'] == $detalle['unidadmedida']) {
		  $saliente = $detalle['cantidadEgreso'];
		} else {
		   if ($detalle['unidadmedidaegreso'] == $detalle['UA']) {
			$saliente = $detalle['cantidadEgreso'] / $detalle['conversiones'];
		   } else {
			$saliente = $detalle['cantidadEgreso'] * $detalle['conversiones'];
		   }
		}
		$sql = "update detalleingresoproducto di set di.cantidadactual=di.cantidadactual+$saliente
		 where di.iddetalleingreso=$detalle[iddetalleingreso];";
		$db->consulta($sql);
	}	
	$sql = "select idingresoprod from ingresoproducto where tipo='TI' and idtransaccion=$idtraspaso";
    $codIngreso = $db->arrayConsulta($sql);
	$sql = "update ingresoproducto set estado=0 where idingresoprod=$codIngreso[idingresoprod]";
	$db->consulta($sql);
    $sql = "update detalleingresoproducto set estado=0 where idingresoprod=$codIngreso[idingresoprod]";
    $db->consulta($sql);
  }
  
  function descontarVenta($idnota, $db) {	  
  $sql = "update libroventasiva set estado=0 where idtransaccion=$idnota and transaccion='Venta Productos'";	  
  $db->consulta($sql);
	  
  $sql = "select de.iddetalleingreso,de.cantidad as 'cantidadEgreso',de.unidadmedida as 
  'unidadmedidaegreso',di.cantidadactual,di.unidadmedida,p.unidaddemedida as 'UM'
  ,p.unidadalternativa as 'UA',p.conversiones  from detallenotaventa 
  de,detalleingresoproducto di,producto p where de.iddetalleingreso=di.iddetalleingreso
   and de.idproducto=p.idproducto and de.idnotaventa=$idnota;";	
  $dato = $db->consulta($sql);
	while($detalle = mysql_fetch_array($dato)) {
		if ($detalle['unidadmedidaegreso'] == $detalle['unidadmedida']) {
		  $saliente = $detalle['cantidadEgreso'];
		} else {
		   if ($detalle['unidadmedidaegreso'] == $detalle['UA']) {
			$saliente = $detalle['cantidadEgreso'] / $detalle['conversiones'];
		   } else {
			$saliente = $detalle['cantidadEgreso'] * $detalle['conversiones'];
		   }
		}
		$sql = "update detalleingresoproducto di set di.cantidadactual=di.cantidadactual+$saliente where 
		di.iddetalleingreso=$detalle[iddetalleingreso];";
		$db->consulta($sql);
	}	
  }

  function descontarIngresoDinero($ingreso, $db) {
	$sql = "select *from detalleingreso where idingreso=$ingreso";
	$dConsulta = $db->consulta($sql);
	while ($data = mysql_fetch_array($dConsulta)){
		if ($data['transaccion'] == "Nota Venta Producto") {
		  $sql = "update notaventa set montoactualcredito=montoactualcredito-"
		  .($data['montobolivianos']+($data['montodolares']*$tc)).
		    " where idnotaventa=".$data['idtransaccion'].";";
		  $db->consulta($sql);
		}
		
		if ($data['transaccion'] == "Nota Venta Servicios") {
		  $sql = "update notaventa set montoactualcredito=montoactualcredito-"
		  .($data['montobolivianos']+($data['montodolares']*$tc)).
		    " where idnotaventa=".$data['idtransaccion'].";";
		  $db->consulta($sql);
		}
		
		if ($data['transaccion'] == "Cuenta Por Cobrar") {
		  $sql = "update cuentaporcobrar set montoactualcobrado=montoactualcobrado-
		  ".($data['montobolivianos']+($data['montodolares']*$tc)).
		  " where idporcobrar=".$data['idtransaccion'].";";
		  $db->consulta($sql);
		}				
	}	  
  }

  function descontarEgresoDinero($egreso, $db) {
	$sql = "select *from detalleegreso where idegreso=$egreso";
	$dConsulta = $db->consulta($sql);
	while ($data = mysql_fetch_array($dConsulta)) {
		if ($data['transaccion'] == "Cuenta Por Pagar") {
		  $sql = "update cuentaporpagar set montoactualpagado=montoactualpagado-"
		  .($data['montobolivianos']+($data['montodolares']*$tc)).
		    " where idporpagar=".$data['idtransaccion'].";";
		  $db->consulta($sql);
		}
		
		if ($data['transaccion'] == "Ingreso Producto") {
		  $sql = "update ingresoproducto set montoactualcredito=montoactualcredito-"
		  .($data['montobolivianos']+($data['montodolares']*$tc)).
		  " where idingresoprod=".$data['idtransaccion'].";";
		  $db->consulta($sql);
		}
	}  
  }
  
  function descontarVentaServicio($idnota, $db) {	  
      $sql = "update libroventasiva set estado=0 where idtransaccion=$idnota and transaccion='Venta Servicios'";	  
      $db->consulta($sql);
  }
  
  function anularBonos($idbono, $db) {
	  $sql = "select month(b.fecha)as 'mes',year(b.fecha)as 'anio',t.idsucursal from bono b,trabajador t 
	  where b.idbono=$idbono and t.idtrabajador=b.idtrabajador; ";
	  $datoBono = $db->arrayConsulta($sql);		  
      $sql = "update bono b,trabajador t set b.estado=0 where month(b.fecha)=$datoBono[mes]
	   and year(b.fecha)=$datoBono[anio] and t.idtrabajador=b.idtrabajador and t.idsucursal=$datoBono[idsucursal]";	  
      $db->consulta($sql);
  }
  
  function anularSueldos($idsueldos, $db) {
	  $sql = "select month(b.fecha)as 'mes',year(b.fecha)as 'anio',t.idsucursal from planilla b,trabajador t 
	  where b.idplanilla=$idsueldos and t.idtrabajador=b.idtrabajador; ";
	  $datoBono = $db->arrayConsulta($sql);		  
      $sql = "update planilla b,trabajador t set b.estado=0 where month(b.fecha)=$datoBono[mes]
	   and year(b.fecha)=$datoBono[anio] and t.idtrabajador=b.idtrabajador and t.idsucursal=$datoBono[idsucursal]";	  
      $db->consulta($sql);	  
	  $sql = "update librodiario set estado=0 where 
	  transaccion='Planilla Sueldos' and month(fecha)=$datoBono[mes] and year(fecha)=$datoBono[anio]  
	  and idsucursal=$datoBono[idsucursal] and estado=1;";
	  $db->consulta($sql);	  
  }
  
  
  function actualizarPlanilla($idanticipo, $db) {
	  $sql = "select * from anticipo where idanticipo=$idanticipo";
	  $datoAnticipo = $db->arrayConsulta($sql);
	  $fechaBase = explode("-", $datoAnticipo['fecha']);
	  $sql = "select idplanilla  
	   from planilla   where month(fecha)=$fechaBase[1] and year(fecha)=$fechaBase[0] 
	   and idtrabajador=$datoAnticipo[idtrabajador];";
	  $datoPlanilla = $db->arrayConsulta($sql);
	  if ($datoPlanilla['idplanilla'] != "") {
		  $sql = "select sum(anticipo) as 'anticipo' from anticipo where month(fecha)=$fechaBase[1] and 
				year(fecha)=$fechaBase[0] and idtrabajador=$datoAnticipo[idtrabajador] 
				and idanticipo!=$idanticipo and estado=1 GROUP BY idtrabajador;";		 
		  $anticipoTotal = $db->arrayConsulta($sql);
		  $anticipoCalculado = ($anticipoTotal['anticipo'] == "") ? 0 : $anticipoTotal['anticipo'];
		  $sql = "update planilla set anticipo=$anticipoCalculado where idplanilla=$datoPlanilla[idplanilla];";  
		  $db->consulta($sql);
		  $sql = "update planilla set totaldescuento=afp+anticipo where idplanilla=$datoPlanilla[idplanilla];";
		  $db->consulta($sql);
	  }	  
  }


  $db = new MySQL();
  $tabla = $_GET['tabla'];
  $id = $_GET['id'.$tabla];
  $nro = $_GET['menu'];
  
  switch($_GET['libro']) {
	case "Egreso Almacen":
	 descontarEgreso($id,$db);
	break;  
	case "Traspaso Almacen":
	 descontarTraspaso($id,$db);
	break;
	case "Nota Venta Productos":
	 descontarVenta($id,$db);
	break;	
	case "Nota Venta Servicios":
	 descontarVentaServicio($id,$db);
	break;
	case "Ingreso dinero":
	 descontarIngresoDinero($id,$db);
	break;
	case "Egreso dinero":
	 descontarEgresoDinero($id,$db);
	break;
	case "Anticipo Sueldo":
	 actualizarPlanilla($id, $db);
	break;
	case "Bono":
	anularBonos($id, $db);
	break;
	case "Sueldos":
	anularSueldos($id, $db);
	break;
  }
  
  
  if (isset($_GET['libro'])) {
	$sql = "update librodiario set estado=0 where transaccion='$_GET[libro]' 
	and tipotransaccion='$_GET[tipolibro]' and idtransaccion='$id';";
	$db->consulta($sql);	
  }

  if (isset($_GET['reg'])) {
    $sql = "update $_GET[reg] set estado=0 where id$tabla=".$id;
	$tabla = $_GET['reg'];
  } else {
    $sql = "update $tabla set estado=0 where id$tabla=".$id;
  }
  $db->consulta($sql);
  if (isset($_GET['salto']))
    header("Location: listar_$_GET[salto].php#t$nro");
  else
    header("Location: listar_$tabla.php#t$nro");
?>