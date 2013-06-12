<?php
  session_start();
  include("../conexion.php");
  $db = new MySQL();
  $tipo = $_GET['tipo'];
  $sucursal = $_GET['sucursal'];
  
   if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: ../index.php");	
  }
  
  function getTotalGeneral($dato)
  {
	 $totalGeneral = 0;  
	 while($total = mysql_fetch_array($dato)) {
	     $totalGeneral = $totalGeneral + $total['total'];	
	 } 
	 return $totalGeneral;  
  }
  
   
  if ($tipo == "consulta") {
	$mes = $_GET['mes'];
	$anio = $_GET['anio'];
	for ($item = 1; $item <= 2; $item++) {
	  if ($item == 1) {
		  $cantidad = 3;
		  $codigo = "4.1";
		  
		  $sql = "SELECT  ( SUM(   dl.haber ) - SUM(   dl.debe ) 
		  ) AS  'total'
		  FROM detallelibrodiario dl, librodiario l, plandecuenta p 
		  WHERE LEFT( dl.idcuenta, $cantidad) =  '$codigo'
		  AND l.idlibrodiario = dl.idlibro
		  AND month(l.fecha) = $mes 
		  AND year(l.fecha) = $anio  
		  AND l.idsucursal= $sucursal
		  AND dl.idcuenta = p.codigo
		  AND l.estado=1 
		  GROUP BY dl.idcuenta;";
		  $dato = $db->consulta($sql);
		  $totalGeneral = getTotalGeneral($dato);
		  
	  
		  $sql = "SELECT p.codigo, p.nivel, left(p.cuenta,28)as 'cuenta', 
		  IF( p.moneda =  'Bolivianos',  'Bs',  '$us' ) AS  'moneda'
		  , SUM( dl.debe ) AS  'debe', SUM( dl.haber ) AS  'haber', (
		   SUM(   dl.haber ) -	SUM(  dl.debe ) ) AS  'total', IF( p.nivel =6, (
		  SELECT pc.cuenta
		  FROM plandecuenta pc
		  WHERE pc.codigo = LEFT( p.codigo, 13 ) and pc.estado=1) ,  ''
		  ) AS  'padre'
		  FROM detallelibrodiario dl, librodiario l, plandecuenta p 
		  WHERE LEFT( dl.idcuenta, $cantidad) =  '$codigo'
		  AND l.idlibrodiario = dl.idlibro
		  AND month(l.fecha) = $mes 
		  AND year(l.fecha) = $anio  
		  AND l.idsucursal= $sucursal
		  AND l.estado=1 
		  AND p.estado=1 
		  AND dl.idcuenta = p.codigo
		  GROUP BY dl.idcuenta, padre;";  
		  $data = $db->consulta($sql);
		  
		  
	  } else {
		  $cantidad = 1;
		  $codigo = "6";
		  
		  $sql = "SELECT  (SUM(  dl.debe ) - SUM(   dl.haber )
		  ) AS  'total'
		  FROM detallelibrodiario dl, librodiario l, plandecuenta p 
		  WHERE LEFT( dl.idcuenta, $cantidad) =  '$codigo'
		  AND l.idlibrodiario = dl.idlibro
		  AND month(l.fecha) = $mes 
		  AND year(l.fecha) = $anio
		  AND l.idsucursal= $sucursal
		  AND dl.idcuenta = p.codigo 
		  AND l.estado=1 
		  GROUP BY dl.idcuenta;";
		  $dato = $db->consulta($sql);
		  $totalGeneral = getTotalGeneral($dato);
	  
  
		  $sql = "SELECT p.codigo, p.nivel, left(p.cuenta,28)as 'cuenta',
		   IF( p.moneda =  'Bolivianos',  'Bs',  '$us' ) AS  'moneda'
		  , SUM( dl.debe ) AS  'debe', SUM( dl.haber ) AS  'haber', (
		  SUM(  dl.debe ) - SUM(   dl.haber )
		  ) AS  'total', IF( p.nivel =6, (
		  SELECT pc.cuenta
		  FROM plandecuenta pc
		  WHERE pc.codigo = LEFT( p.codigo, 13 ) ) ,  ''
		  ) AS  'padre'
		  FROM detallelibrodiario dl, librodiario l, plandecuenta p 
		  WHERE LEFT( dl.idcuenta, $cantidad) =  '$codigo'
		  AND l.idlibrodiario = dl.idlibro
		  AND month(l.fecha) = $mes 
		  AND year(l.fecha) = $anio
		  AND l.idsucursal= $sucursal
		  AND dl.idcuenta = p.codigo 
		  AND p.estado=1 
		  AND l.estado=1 
		  GROUP BY dl.idcuenta, padre;";  
		  $data = $db->consulta($sql);
		  
	  }
	
	
	$subTotal = "";
	$padre = "";
	$totalBs = 0;
	$totalDo = 0;
	$subPorcentajes = array();
	$subTabla = "";
	$monedaSub = "";
	$porcentajes = "";
	  while($balance = mysql_fetch_array($data)) {
		  $totalBs = $totalBs + $balance['total'];
		  if ($balance['total'] == 0)
		      continue;
		  
	    		if ($padre != "" && $padre == $balance['padre']) {
					$subTotal = $subTotal + $balance['total'];
					$subTabla = $subTabla ."<tr class='ocultar'>
					 <td class='ocultar'>$balance[nivel]</td>
		             <td class='fondoCelda2'>&nbsp;&nbsp;&nbsp;$balance[cuenta]</td>
		             <td class='fondoCelda2' align='center'>$balance[moneda]</td>
			         <td class='fondoCelda2' align='center'>".number_format($balance['total'],2)."</td>
		            </tr>";
					array_push($subPorcentajes,$balance['total']);
					continue;
				} 
				if ($padre != "" && $padre != $balance['padre']) {
					echo "<tr>
					 <td class='ocultar'>5</td>
		             <td class='fondoCelda'>$padre</td>
		             <td class='fondoCelda' align='center'>$monedaSub</td>
			         <td class='fondoCelda' align='center'>".number_format($subTotal,2)."</td>
		            </tr>";
					echo $subTabla;
					 $por = (($subTotal/$totalGeneral)*100);
			         $por = redondeado($por,2);
					 $totalSubPorcentaje = abs($por);
					 $auxPorcentaje = (abs($por)>100) ? 100 : abs($por);
		             $porcentajes = $porcentajes."<tr><td width='15%' class='fondoCeldaBarra'>$por%</td>
					 <td width='85%' height='22px'>
					 <div style='width:".abs($auxPorcentaje)."%;' class='barra'></div></td><td></td></tr>";
					
					for($i = 0; $i < count($subPorcentajes); $i++) {
					   $por = (($subPorcentajes[$i]/$subTotal)*100);
			           $por = redondeado($por, 2);
					   
					   $newPorcentaje = (abs($por)/100)*$totalSubPorcentaje;
					   $newPorcentaje = redondeado($newPorcentaje,2);
					   $auxPorcentaje = (abs($newPorcentaje)>100) ? 100 : abs($newPorcentaje);
		               $porcentajes = $porcentajes."<tr class='ocultar' >
					   <td width='15%' class='fondoCeldaBarra'>$newPorcentaje%</td>
					   <td width='85%' height='22px'><div style='width:".abs($auxPorcentaje)."%;' class='barraSecundaria'>
					   </div></td><td></td></tr>";
					}
					
					$subPorcentajes = array();
					$padre = "";
				}
				if ($padre == "" && $balance['nivel'] == 6) {
					$padre = $balance['padre'];
					$subTotal = $balance['total'];
					$monedaSub = $balance['moneda'];
					$subTabla = "<tr class='ocultar'>
					 <td class='ocultar'>$balance[nivel]</td>
		             <td class='fondoCelda2'>&nbsp;&nbsp;&nbsp;$balance[cuenta]</td>
		             <td align='center' class='fondoCelda2'>$balance[moneda]</td>
			         <td align='center' class='fondoCelda2'>".number_format($balance['total'],2)."</td>
		            </tr>";
					array_push($subPorcentajes,$balance['total']);
					continue;
				}
			
			  echo "<tr>
			   <td class='ocultar' >$balance[nivel]</td>
		       <td class='fondoCelda'>$balance[cuenta]</td>
		       <td class='fondoCelda' align='center'>$balance[moneda]</td>
			   <td class='fondoCelda' align='center'>".number_format($balance['total'],2)."</td>
		      </tr>"; 
			  $por = (($balance['total'] / $totalGeneral) * 100);
			  $por = redondeado($por,2);
			  $auxPorcentaje = (abs($por) > 100) ? 100 : abs($por);
		      $porcentajes = $porcentajes."<tr><td width='15%' class='fondoCeldaBarra'>$por%</td>
			  <td width='85%' height='22px'>
			  <div style='width:".abs($auxPorcentaje)."%;' class='barra'></div></td><td></td></tr>";
		
		 
   	  }
	  
	  $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	  $tc = $db->getCampo('dolarcompra',$sql); 
	  $totalDo = $totalBs / $tc;
	  
	  echo "<tr>
			   <td class='ocultar'></td>
			   <td></td>
		       <th align='center' class='cabeceraReporte2'>Total Bs.</th>
			   <td align='center' class='cabeceraReporte2'>".number_format($totalBs,2)."</td>
		      </tr>
			  <tr>
		       <td class='ocultar'></td>
			   <td></td>
		       <th align='center' class='cabeceraReporte2'>Total Sus.</th>
			   <td align='center' class='cabeceraReporte2'>".number_format($totalDo,2)."</td>
		      </tr>			  
			  "."---";
	  echo $porcentajes."---";
	}
	  
	  exit();
  }

  function redondeado ($numero, $decimales)
  {
      $factor = pow(10, $decimales);
      return (round($numero * $factor) / $factor); 
  }

?>