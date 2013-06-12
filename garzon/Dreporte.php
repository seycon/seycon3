<?php
    session_start();
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["transaccion"];
	
	if ($tipo == "reporte1") {	
	  $general = array();
	  $i = 1;
	  $nroatencion = $_GET['nroatencion'];  
	  $idatencion = $_GET['idatencion'];
	  
	  
 	  $sql = "select date_format(a.fecha, '%d/%m/%Y') as 'fecha', left(s.nombrecomercial, 10)as 'sucursal' 
	  from atencion a,sucursal s where a.idsucursal=s.idsucursal and a.idatencion=$idatencion;";
	  $sucursal = $db->arrayConsulta($sql); 
	  
	  
	  $sql = "select left(c.nombre,15)as 'nombre',round(dc.precio,2)as 'precio',round(dc.cantidad,2) as 'cantidad'
	    from detalleatencion dc,
        combinacion c where dc.idcombinacion=c.idcombinacion 
        and dc.idatencion=$idatencion and nroatencion=$nroatencion and dc.estado=1;";
	  $producto = $db->consulta($sql);
	  $usuario = substr($_SESSION['nombretrestaurante'], 0, 15);
	  $general[0] = array($sucursal['sucursal'], $idatencion, $usuario, $nroatencion, $sucursal['fecha']);	
	   while ($data = mysql_fetch_array($producto)) {
		   $datos = array();
		   $totalU = $data['cantidad'] * $data['precio'];
		   $datos = array($data['nombre'], $data['cantidad'], $data['precio'], $totalU);
		   $general[$i] = $datos;
		   $i++;
	   }
		 
	   echo json_encode($general);	
	}	
	
	
	if ($tipo == "reporte2") {	
	  $general = array();
	  $i = 1;
	  $idatencion = $_GET['idatencion'];
	  
	  
 	  $sql = "select date_format(a.fecha, '%d/%m/%Y') as 'fecha', left(s.nombrecomercial, 10)as 'sucursal',
	  a.descuento,a.efectivo,a.cortesia,a.credito,a.idtrabajador,u.idtrabajador as 'usuario',u.tipo          
	  from atencion a,sucursal s,usuariorestaurante u 
	  where a.idsucursal=s.idsucursal and a.idatencion=$idatencion and u.idusuario=a.idusuariorestaurante;";
	  $sucursal = $db->arrayConsulta($sql); 
	  
	  if ($sucursal['tipo'] == "apoyo") {
	      $sql = "select ci,left(concat_WS(' ',nombre,apellido),15)as 'usuario' from personalapoyo where  
		   idpersonalapoyo=$sucursal[usuario]";		 
	  } else {
		  $sql = "select carnetidentidad as 'ci',left(concat_WS(' ',t.nombre,t.apellido),15) as 'usuario'
		   from trabajador t where t.idtrabajador=$sucursal[usuario]";  
	  }
	  $usuario = $db->arrayConsulta($sql);
	  
	  
	  $cliente = "Varios";	  
	  if ($sucursal['credito'] == 0 && $sucursal['idtrabajador'] != "0") {
		  $sql = "select ci,left(concat_WS(' ',nombre,apellido),15)as 'nombre' from personalapoyo where  
		 idpersonalapoyo=$sucursal[idtrabajador]"; 
		 $datoTrabajador = $db->arrayConsulta($sql);
		 $cliente =  $datoTrabajador['nombre']; 
	  }
	  
	  if ($sucursal['credito'] == 1) {
		$tipoVenta = "Credito";  
 		$sql = "select carnetidentidad as 'ci',left(concat_WS(' ',t.nombre,t.apellido),15) as 'nombre'
		  from trabajador t where t.idtrabajador=$sucursal[idtrabajador]";   
		$datoTrabajador = $db->arrayConsulta($sql);
		$cliente =  $datoTrabajador['nombre']; 
	  } else {
		if ($sucursal['cortesia'] > 0) {  
		    $tipoVenta = "Cortesia";  
		} else {
			$tipoVenta = "Contado";  
		}
	  }
	  
	  
	  $sql = "select left(c.nombre,15)as 'nombre',round(dc.precio,2)as 'precio',round(dc.cantidad,2)as 'cantidad' 
	   from detalleatencion dc,
       combinacion c where dc.idcombinacion=c.idcombinacion and dc.idatencion=$idatencion and dc.estado=1;";
	  $producto = $db->consulta($sql);
	  $general[0] = array($sucursal['sucursal'], $idatencion, $usuario['usuario'], $sucursal['fecha'], $tipoVenta
	  , $sucursal['descuento'], $sucursal['efectivo'], $sucursal['cortesia'], $cliente);	
	   while ($data = mysql_fetch_array($producto)) {
		   $datos = array();
		   $totalU = $data['cantidad'] * $data['precio'];
		   $datos = array($data['nombre'], $data['cantidad'], $data['precio'], $totalU);
		   $general[$i] = $datos;
		   $i++;
	   }
		 
	   echo json_encode($general);	
	}	
	
	
	
	if ($tipo == "reporte3") {	
	  $general = array();
	  
	  $identrega = $_GET['identrega'];
	  
	  $sql = "select e.fecha,DAYOFWEEK(e.fecha)as 'dia',e.tipo,e.idtrabajador,round(e.monto,2)as 'monto'
	  ,round(e.acumulado,2)as 'acumulado',left(concat_WS(' ',t.nombre,t.apellido),15) as 'usuario',
	  left(s.nombrecomercial, 15) as 'sucursal',e.idsucursal     
	   from entregadinero e,usuario u,trabajador t,sucursal s where identrega=$identrega 
	  and e.idusuariosistema=u.idusuario and u.idtrabajador=t.idtrabajador and e.idsucursal=s.idsucursal";
	  $entregaD = $db->arrayConsulta($sql);
	  
	  if ($entregaD['tipo'] == "fijo") {
		 $sql = "select carnetidentidad as 'ci',left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre'
		  from trabajador t where t.idtrabajador=$entregaD[idtrabajador]";  
	  } else {
		 $sql = "select ci,left(concat_WS(' ',nombre,apellido),20)as 'nombre' from personalapoyo where  
		 idpersonalapoyo=$entregaD[idtrabajador]"; 
	  }
	  $trabajador = $db->arrayConsulta($sql);
	  
 
	  
	  $general[0] = array($entregaD['sucursal'], $entregaD['fecha'], $trabajador['nombre'], $entregaD['dia']
	  , $entregaD['usuario'], $trabajador['ci'], $entregaD['monto'], $entregaD['acumulado']);
	  	
	  $sql = "select u.idtrabajador,a.fecha,d.cantidad,d.nroatencion,d.idatencion,left(c.nombre,15)as 'nombre'  
      from atencion a,detalleatencion d,combinacion c,usuariorestaurante u where 
      a.idatencion=d.idatencion and c.idcombinacion=d.idcombinacion and a.estado='cobrado'  and 
	  a.idsucursal=$entregaD[idsucursal] and 
      a.socio=1 and d.estado=1 and a.idusuariorestaurante=u.idusuario and u.idtrabajador=$entregaD[idtrabajador] and
      date(a.fecha)='$entregaD[fecha]';";
	  $producto = $db->consulta($sql);
	  
	  $i = 0;
	  $subgeneral = array();
	   while ($data = mysql_fetch_array($producto)) {
		   $datos = array();
		   $datos = array($data['nombre'], $data['cantidad'], $data['idatencion'], $data['nroatencion']);
		   $subgeneral[$i] = $datos;
		   $i++;
	   }
	   $general[1] = $subgeneral;
	   
	   
	   $sql = "select left(c.nombre,15)as 'nombre',dc.nroatencion,dc.idatencion,dc.cantidad from detalleatencion dc,atencion a,
       combinacion c,usuariorestaurante u where dc.idcombinacion=c.idcombinacion and a.estado='cobrado'  and 
	   a.idsucursal=$entregaD[idsucursal]   
	   and dc.idatencion=a.idatencion and a.idusuariorestaurante=u.idusuario and u.idtrabajador=$entregaD[idtrabajador] and  
	   dc.estado=0 and date(a.fecha)='$entregaD[fecha]';";
	   $producto = $db->consulta($sql);
	  
	  $i = 0;
	  $subgeneral = array();
	   while ($data = mysql_fetch_array($producto)) {
		   $datos = array();
		   $datos = array($data['nombre'], $data['cantidad'], $data['idatencion'], $data['nroatencion']);
		   $subgeneral[$i] = $datos;
		   $i++;
	   }
	   $general[2] = $subgeneral;
	   
	   $sql = "select u.idtrabajador,a.fecha,d.cantidad,left(c.nombre,15)as 'producto' 
	   ,left(concat_WS(' ',t.nombre,t.apellido),15) as 'nombre'  
       from atencion a,detalleatencion d,combinacion c,usuariorestaurante u 
		,trabajador t where  a.idsucursal=$entregaD[idsucursal] and 
		a.idatencion=d.idatencion and c.idcombinacion=d.idcombinacion and a.estado='cobrado' and 
		a.credito=1 and a.socio=0 and d.estado=1 and a.idusuariorestaurante=u.idusuario and u.idtrabajador=$entregaD[idtrabajador] 
		and a.idtrabajador=t.idtrabajador and  date(a.fecha)='$entregaD[fecha]';";
	   $producto = $db->consulta($sql);
	  
	  $i = 0;
	  $subgeneral = array();
	   while ($data = mysql_fetch_array($producto)) {
		   $datos = array();
		   $datos = array($data['producto'], $data['cantidad'], $data['nombre']);
		   $subgeneral[$i] = $datos;
		   $i++;
	   }
	   $general[3] = $subgeneral;
	   
	   $sql = "select left(c.nombre,15)as 'nombre',dc.precio,sum(dc.cantidad)as 'cantidad' from detalleatencion dc,atencion a,
       combinacion c,usuariorestaurante u where dc.idcombinacion=c.idcombinacion and dc.idatencion=a.idatencion 
       and a.idusuariorestaurante=u.idusuario and u.idtrabajador=$entregaD[idtrabajador] and 
	   a.idsucursal=$entregaD[idsucursal] and 
        a.credito=0 and dc.estado=1 and a.estado='cobrado' and a.cortesia=0 
	    and date(a.fecha)='$entregaD[fecha]' group by c.idcombinacion;";
	   $producto = $db->consulta($sql);
	   $i = 0;
	  $subgeneral = array();
	   while ($data = mysql_fetch_array($producto)) {
		   $datos = array();
		   $datos = array($data['nombre'], $data['cantidad'], $data['precio']);
		   $subgeneral[$i] = $datos;
		   $i++;
	   }
	   $general[4] = $subgeneral;
		 
	   $sql = "select a.idatencion,left(c.nombre,15)as 'nombre',dc.nroatencion,dc.cantidad from detalleatencion dc,atencion a,
       combinacion c,usuariorestaurante u where dc.idcombinacion=c.idcombinacion and dc.idatencion=a.idatencion 
       and a.idusuariorestaurante=u.idusuario and u.idtrabajador=$entregaD[idtrabajador] and 
	   a.idsucursal=$entregaD[idsucursal] and 
       a.credito=0 and dc.estado=1 and a.estado='cobrado' 
	   and date(a.fecha)='$entregaD[fecha]' and a.cortesia>0 ;";
	   $producto = $db->consulta($sql);
	   $i = 0;
	  $subgeneral = array();
	   while ($data = mysql_fetch_array($producto)) {
		   $datos = array();
		   $datos = array($data['nombre'], $data['cantidad'], $data['idatencion'], $data['nroatencion']);
		   $subgeneral[$i] = $datos;
		   $i++;
	   }
	   $general[5] = $subgeneral; 
		 
	   echo json_encode($general);	
	}	
	
?>	