<?php
    session_start(); 
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["tipo"];
 
    if ($tipo == "mesas"){	 
	    $sql = "select max(nromesa) as 'nro' from atencion where idusuariorestaurante='$_SESSION[idusuariorestaurante]' "
			 ." and date(fecha)=CURRENT_DATE()";
		$dato = $db->arrayConsulta($sql);
		if ($dato['nro'] == "") {
			 $cantidad = 1;
		} else {
			 $cantidad = $dato['nro'] + 1; 	 
		}
		$sql = "insert into atencion(idatencion,fecha,estado,nromesa,tipo,idtrabajador,idusuariorestaurante"
			 . ",credito,socio,efectivo,cortesia,descuento,idsucursal) "
			 . "values(null,now(),'atencion',$cantidad,'',null,$_SESSION[idusuariorestaurante]"
			 . ",false,false,0,0,0,$_SESSION[IDsucursalrestaaurante])";
		$db->consulta($sql);
	   
		$sql = "select *from atencion where idusuariorestaurante='$_SESSION[idusuariorestaurante]' and estado='atencion'";
		$mesas = $db->consulta($sql);
		$i = 0;
		$cadena = "";
		while ($data = mysql_fetch_array($mesas)) {
			 $cadena = $cadena."<td width='16%'><div id='opcionMesa' onclick='setNroPedido($data[idatencion])'>
			 <div id='textoMesa'>Mesa #$data[nromesa]</div></div></td>";	
			 $i++;
			 if ($i == 6) {
				 $i = 0;
				 echo "<tr>$cadena<tr>";
				 $cadena = "";	 
			 }   
		}
	   
		if ($i < 6) {
			 for ($j = $i; $j <= 6; $j++) {
				 $cadena = $cadena."<td width='16%'></td>";
			 }
		}
	   echo $cadena;
	   exit();	 
	 }
   
   
    if ($tipo == "nropedido") {
	    $atencion = $_GET['atencion'];
	    $sql = "select max(nroatencion)as 'nro' from detalleatencion where idatencion=$atencion;"; 
	    $result = $db->arrayConsulta($sql);
	    $nro = ($result['nro'] == "") ? 1 : $result['nro'] + 1; 
	    echo $atencion."---";
	    echo $nro."---";
	    exit(); 
    }
 
	if ($tipo == "totalVendido") {
	    $idusuario = $_GET['idusuario'];
		$sql = "select sum(efectivo) as 'total' from atencion where date(fecha)=date(now()) and "
		    ."  idusuariorestaurante=$idusuario and estado='cobrado' group by idusuariorestaurante;"; 
		$monto = $db->arrayConsulta($sql);
		echo ($monto['total'] == "") ? 0 : (number_format($monto['total']));
	}   
	
	if ($tipo == "verificarEntrega") {
		  $sql = "select u.idtrabajador,u.tipo,round(a.efectivo,2)as 'efectivo',a.credito from atencion a,usuariorestaurante u where 
         a.idusuariorestaurante=u.idusuario and a.idatencion=$_GET[idatencion];";
		 $dataTrabajador = $db->arrayConsulta($sql);
		
		 if ($dataTrabajador['credito'] == "0") {
		 $sql = "select round(sum(a.efectivo),2)as 'debe' from atencion a,usuariorestaurante u 
            where a.idusuariorestaurante=u.idusuario and a.estado='cobrado' and u.tipo='$dataTrabajador[tipo]'
			 and u.idtrabajador=$dataTrabajador[idtrabajador]
             and credito=0 and socio=0 group by u.idtrabajador; ";
	       $debe = $db->arrayConsulta($sql);
		   $sql = "select round(sum(acumulado),2)as 'haber',round(sum(nulo),4)as 'nulo',round(sum(cortesia),4)as 'cortesia' 
			,round(sum(credito),4)as 'credito' from entregadinero where idtrabajador=$dataTrabajador[idtrabajador] 
				and tipo='$dataTrabajador[tipo]' and estado=1 group by idtrabajador;";
		   $haber = $db->arrayConsulta($sql);
		   
		   $montoVenta = $debe['debe'] - $dataTrabajador['efectivo'];
		   if ($montoVenta < $haber['haber']) {
		       echo 1;   
		   } else {
		       echo 0;   
		   }
		 } else {
		     echo "0"; 	 
		 }
		   
		
	}
	
 
?>