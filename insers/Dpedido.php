<?php
    session_start(); 
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["tipo"];
 
    if ($tipo == "busqueda") {
	    $sql = "select c.idcombinacion,left(c.nombre,22) as 'nombre1',c.total as 'precio',c.nombre, t.nombre as 'tipo' "
		    ." from combinacion c,tipocombinacion t where c.idtipocombinacion=t.idtipocombinacion 
			and c.nombre like '$_GET[texto]%' and c.estado=1 limit 9;";
		$producto = $db->consulta($sql);	  
		while ($dato = mysql_fetch_array($producto)) {
		   echo "<li onclick='openVentanaPedido(&quot;$dato[nombre]&quot;
		   ,&quot;$dato[precio]&quot;,&quot;$dato[idcombinacion]&quot;,&quot;$dato[tipo]&quot;)'>".
		   ucfirst($dato['nombre1'])."</li>";		  
		}
		echo "";
		exit();
	}
   
    if ($tipo == "trabajador") {	
	    if ($_GET['tipotrabajador'] == "fijo") {	
	        $sql = "select idsocio from socio where idtrabajador=$_GET[idtrabajador]";
	        $socio = $db->arrayConsulta($sql);
	        $socio = ($socio['idsocio'] == "") ? 0 : 1;   
		} else {
		    $socio = "0";	
		}
	    $sql = "select sum((d.precio*(e.porcentaje/100))*cantidad) as 'total' from detalleatencion d,descuento e "
            ." where d.idcombinacion=e.idcombinacion and d.idatencion=$_GET[idatencion] and d.estado=1"
			." group by d.idatencion; ";  
	    $descuento = $db->arrayConsulta($sql);
	    $descuento = ($descuento['total'] == "") ? 0 : $descuento['total'];
	    echo $socio."---".$descuento."---";  
    }
   
   
    if ($tipo == "combinacion") {
	    $disponible = -1;
	    $sql = "select p.idproducto,p.nombre,d.cantidad,d.unidadmedida "
            ." from detallecombinacion d,producto p "
            ." where p.idproducto=d.idproducto and "
            ." d.idcombinacion=$_GET[idcombinacion]";
	   $producto = $db->consulta($sql);
	   while ($data = mysql_fetch_array($producto)) {
	       $cantidadP = $data['cantidad'];
		   $unidadM = $data['unidadmedida'];  
		   $sql = "select p.idproducto,p.nombre,sum(if (p.unidaddemedida=d.unidadmedida,d.cantidadactual"
               .",(d.cantidadactual/p.conversiones)))as 'cantidad',p.unidaddemedida,p.conversiones from"
               ." detalleingresoproducto d,ingresoproducto i,almacen a,producto p" 
               ." where p.idproducto=d.idproducto "
		       ." and i.idalmacen=a.idalmacen"  
		       ." and a.sucursal=$_SESSION[IDsucursalrestaaurante]"   
               ." and i.idingresoprod=d.idingresoprod" 
		       ." and d.cantidadactual>0"
               ." and i.estado=1 "
               ." and p.idproducto=$data[idproducto] group by p.idproducto";   
		   $dataP = $db->arrayConsulta($sql);
		 
		   if ($unidadM == $dataP['unidaddemedida']) {
		       $cantidad = $dataP['cantidad'];	 
		   } else {
		       $cantidad = $dataP['cantidad'] * $dataP['conversiones'];	 
		   }		 
		   $dis = (int)($cantidad / $cantidadP);		 
		   if ($dis < $disponible || $disponible == -1) {
		       $disponible = $dis; 
		   }		 
	   }
	   
	   $d = ($disponible <= 0) ? 0 : $disponible;
       echo $d;
   }
   
   if ($tipo == "eliminarPedido") {
       $sql = "update detalleatencion set estado=0 where iddetalleatencion='$_GET[iddetalle]';";  
	   $db->consulta($sql); 	  
	   $sql = "select * from detallerequerimiento where iddetalleatencion='$_GET[iddetalle]';";
	   $consulta = $db->consulta($sql);
	   while ($data = mysql_fetch_array($consulta)) {
		  $sql = "update detalleingresoproducto set cantidadactual=cantidadactual+$data[cantidad] "
		      ." where iddetalleingreso=$data[iddetalleingreso]";
		  $db->consulta($sql);
	   } 	  
	   $sql = "select idatencion from detalleatencion where iddetalleatencion='$_GET[iddetalle]';";
	   $atencion = $db->arrayConsulta($sql); 	  
	   $sql = "select cuenta from configuracionsucursal where idsucursal=$_SESSION[IDsucursalrestaaurante]";
  	   $cuentacaja = $db->arrayConsulta($sql);	  
	   insertarLibro($_SESSION['IDsucursalrestaaurante'], $atencion['idatencion'], $db, $cuentacaja['cuenta'],'false', 0, 0);
	   exit();
   }
   
   function insertarLibro($sucursal, $codigo, $db, $cuenta1, $credito, $descuento, $cortesia)
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
	
	   $sql = "select idlibrodiario,idsucursal from librodiario where transaccion='Venta Productos Restaurante'"
	       ." and idtransaccion=$codigo;";  
	   $libro = $db->arrayConsulta($sql); 
	   $idlibro = $libro['idlibrodiario'];
	   if ($libro['idlibrodiario'] == "") {		
	       $sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa,idtransaccion,"
	           ."tipocambio,idusuario,estado,transaccion)" 
	           ."values('$num[num]','$num[sucursal]','Bolivianos','ingreso',now(),'venta discoteca.','$codigo'"
	           .",'$tc','1',1,'Venta Productos Restaurante');"; 
	       $db->consulta($sql);   
	       $idlibro = $db->getMaxCampo("idlibrodiario","librodiario");
	   }
	
	   $sql = "delete from detallelibrodiario where idlibro=$idlibro";
	   $db->consulta($sql);
	   $descripcionLibro = "Nº $codigo/Sucursal: $_SESSION[sucursalrestaaurante]/Venta Discoteca";
	
	   $sql = "select sum(cantidad*precio)as 'monto' from detalleatencion where idatencion=$codigo and estado=1 group by idatencion;";
	   $monto = $db->arrayConsulta($sql);
	   $monto = ($monto['monto'] == "") ? 0 : $monto['monto'];
	   $costo = getCostoProducto($codigo,$db);
	   
	   $subMonto = $monto - $descuento - $cortesia;
	   $sql = "select * from configuracionrestaurante;";
	   $cuentaR = $db->arrayConsulta($sql);
	   
	   
	   if ($cortesia > 0) {
	       insertarDetalle($idlibro,$cuentaR['cuentacortesia'],$descripcionLibro,$cortesia,0,0,$db);    
	   } else {
	   	   insertarDetalle($idlibro,$cuenta1,$descripcionLibro,$subMonto,0,0,$db);
		   if ($descuento > 0) {
			   insertarDetalle($idlibro,$cuentaR['cuentadescuento'],$descripcionLibro,$descuento,0,0,$db);    
		   }	   	   
	   }
	   
	   insertarDetalle($idlibro,$contabilidad['cuentalcvproductos'],$descripcionLibro,0,$monto,0,$db);
	   insertarDetalle($idlibro,$contabilidad['costoventa'],$descripcionLibro,$costo,0,0,$db);
	   insertarDetalle($idlibro,$contabilidad['inventario'],$descripcionLibro,0,$costo,0,$db);
   }
   
   function getCostoProducto($idatencion, $db)
   {
       $sql = "select sum(d.cantidad*di.precio)as 'total' from detallerequerimiento d,detalleatencion da,detalleingresoproducto di" 
           ." where da.iddetalleatencion=d.iddetalleatencion and di.iddetalleingreso=d.iddetalleingreso"
           ." and da.idatencion=$idatencion and da.estado=1 group by da.idatencion;";	
	   $costo = $db->arrayConsulta($sql);
	   return ($costo['total'] == "") ? 0 : $costo['total'];   
   }
   
   
   function insertarDetalle($libro, $cuenta, $descripcion, $monto1, $monto2, $doc, $db)
   {
       $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)values"
           ."($libro,'$cuenta','$descripcion',$monto1,$monto2,'$doc')";
       $db->consulta($sql);   
   }
   
      
   if ($tipo == "insertarPedido") {
       $sql = "insert into detalleatencion(iddetalleatencion,idatencion,nroatencion,idcombinacion,precio,cantidad,estado)" 
	       ."values(null,'$_GET[idatencion]','$_GET[nroatencion]','$_GET[idcombinacion]','$_GET[precio]','$_GET[cantidad]','1');";   
	   $db->consulta($sql);
	   $codigoPedido = $db->getMaxCampo("iddetalleatencion","detalleatencion");
  	
	   $iddetalleatencion = $db->getMaxCampo("iddetalleatencion","detalleatencion");	 
	   $cantidadPedida = $_GET['cantidad'];
	 
	   $sql = "select p.idproducto,p.nombre,d.cantidad,d.unidadmedida "
	       ."from detallecombinacion d,producto p "
		   ." where p.idproducto=d.idproducto and "
		   ." d.idcombinacion=$_GET[idcombinacion];";
	   $consulta = $db->consulta($sql);
	   while ($data = mysql_fetch_array($consulta)){
		  $UMCombinacion = $data['unidadmedida'];
		  $cantidadSalida = $data['cantidad'] * $cantidadPedida;
		  $sql = "select p.nombre,p.unidaddemedida,p.conversiones,d.unidadmedida as 'UMingreso',d.cantidadactual,d.iddetalleingreso"
              ." from detalleingresoproducto d,producto p,ingresoproducto i,almacen a where "
              ." p.idproducto=d.idproducto "
		      ." and i.idingresoprod=d.idingresoprod "
		      ." and i.estado=1 "
		      ." and i.idalmacen=a.idalmacen "
		      ." and a.sucursal=$_SESSION[IDsucursalrestaaurante] "
              ." and d.cantidadactual>0 "
              ." and p.idproducto=$data[idproducto];";
		  $producto = $db->consulta($sql);
		  while($dato = mysql_fetch_array($producto)) {
		      if ($UMCombinacion != $dato['UMingreso']) {
			      if ($UMCombinacion == $dato['unidaddemedida']) {
				      $cantidadSalida = $cantidadSalida * $dato['conversiones'];						
				  } else { 
					  $cantidadSalida = $cantidadSalida / $dato['conversiones'];
				  }
				  $UMCombinacion = $dato['UMingreso'];
			  }
				
			  if ($cantidadSalida > $dato['cantidadactual']) {
			      $cantidadDescuento = $dato['cantidadactual'];
			  } else {
				  $cantidadDescuento = $cantidadSalida;	
			  }
				
			  $sql = "insert into detallerequerimiento(idrequerimiento,iddetalleatencion,iddetalleingreso,unidadmedida,cantidad)
				values (null,'$iddetalleatencion','$dato[iddetalleingreso]','$dato[UMingreso]','$cantidadDescuento');";
			  $db->consulta($sql);
				
			  $sql = "update detalleingresoproducto set cantidadactual=cantidadactual-$cantidadDescuento 
				where iddetalleingreso=$dato[iddetalleingreso]";
			  $db->consulta($sql);
				
			  $cantidadSalida = $cantidadSalida - $cantidadDescuento;
			  if ($cantidadSalida <= 0) {
			      break;
			  }
			}		  
	   }
	  
	   $sql = "select cuenta from configuracionsucursal where idsucursal=$_SESSION[IDsucursalrestaaurante]";
  	   $cuentacaja = $db->arrayConsulta($sql);
	   insertarLibro($_SESSION['IDsucursalrestaaurante'],$_GET['idatencion'],$db,$cuentacaja['cuenta'],'false', 0, 0);	  
	   echo $codigoPedido;	 
       exit();
   }
   
   if ($tipo == "actualizarAtencion") {
	   $fecha = $db->GetFormatofecha($_GET['fecha'],"/");
	   $sql = "update atencion set estado='cobrado',idtrabajador='$_GET[trabajador]',credito=$_GET[credito],"
	       ."socio=$_GET[socio],efectivo='$_GET[efectivo]',fecha='$fecha',cortesia='$_GET[cortesia]',descuento='$_GET[descuento]' "
		   ." where idatencion='$_GET[idatencion]'";	
       $cuenta = getCuenta($_GET['socio'],$_GET['credito'],$db);	
       insertarLibro($_SESSION['IDsucursalrestaaurante'], $_GET['idatencion'], $db, $cuenta, $_GET['credito'], $_GET['descuento'], 
	   $_GET['cortesia']);
	   $db->consulta($sql);   	
	   exit();
   }
 
   function getCuenta($socio, $credito, $db)
   {	   
       
	   if ($socio == "false" && $credito == "false") {
		   $sql = "select cuenta from configuracionsucursal where idsucursal=$_SESSION[IDsucursalrestaaurante]";
  	       $cuenta = $db->arrayConsulta($sql);   
		   return $cuenta['cuenta'];
	   }
	   
	   if ($socio == "true") {
	       $sql = "select cuentasocio as 'cuenta' from configuracionrestaurante;"; 
		   $cuenta = $db->arrayConsulta($sql);
		   return $cuenta['cuenta'];
	   }
	   
	   if ($credito == "true") {
	       $sql = "select anticiposueldo as 'cuenta' from configuracioncontable;"; 
		   $cuenta = $db->arrayConsulta($sql);
		   return $cuenta['cuenta'];
	   }
	   
	   return "";
   }
 
 
?>