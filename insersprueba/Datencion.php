<?php
    session_start(); 
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["tipo"];
 
    if ($tipo == "mesas"){	 
	    $sql = "select max(nromesa) as 'nro' from atencion where idusuariorestaurante='$_SESSION[idusuariorestaurante]' "
			 ." and fecha=CURRENT_DATE()";
		$dato = $db->arrayConsulta($sql);
		if ($dato['nro'] == "") {
			 $cantidad = 1;
		} else {
			 $cantidad = $dato['nro'] + 1; 	 
		}
		$sql = "insert into atencion(idatencion,fecha,estado,nromesa,tipo,idtrabajador,idusuariorestaurante"
			 .",credito,socio,efectivo,cortesia,descuento) "
			 ."values(null,now(),'atencion',$cantidad,'',null,$_SESSION[idusuariorestaurante],false,false,0,0,0)";
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
 
?>