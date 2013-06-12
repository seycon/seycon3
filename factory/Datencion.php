<?php
 session_start(); 
 include("../conexion.php");
 $db = new MySQL();
 $tipo = $_GET["tipo"];
 
 if (!isset($_SESSION['idusuarioF'])){
  header("Location: index.php");	
 }
 
 function filtro($cadena){
  return htmlspecialchars(strip_tags($cadena));
 }
 
 
 if ($tipo == "mesas"){	 
   $sql = "select max(nromesa) as 'nro' from atencionF where idusuario='$_SESSION[idusuarioF]' and fecha=CURRENT_DATE()";
   $dato = $db->arrayConsulta($sql);
   if ($dato['nro'] == ""){
    $cantidad = 1;
   }else{
    $cantidad = $dato['nro'] + 1; 	 
   }
   $sql = "insert into atencionF(idatencion,fecha,estado,nromesa,idusuario)values(null,now(),'atencion',$cantidad,$_SESSION[idusuarioF])";
   $db->consulta($sql);
   
   $sql = "select *from atencionF where idusuario='$_SESSION[idusuarioF]' and estado='atencion'";
   $mesas = $db->consulta($sql);
   $i = 0;
   $cadena = "";
   while ($data = mysql_fetch_array($mesas)){
	$cadena = $cadena."<td width='14%'><div id='opcionMesa' onclick='setNroPedido($data[idatencion])'><div id='textoMesa'>Mesa #$data[nromesa]</div></div></td>";	
	$i++;
	  if ($i == 7){
		$i = 0;
		echo "<tr>$cadena<tr>";
		$cadena = "";	 
	  }   
   }
   
   if ($i<7){
	 for ($j=$i;$j<=7;$j++){
	  $cadena = $cadena."<td width='14%'></td>";
    }
   }
   echo $cadena;
   exit();	 
 }
   
 if ($tipo == "nropedido"){
	$atencion = filtro($_GET['atencion']);
	$sql = "select max(d.nroatencion)as 'nro' from notaventaF n,detallenotaF d where n.idatencion=$atencion and d.idnotaventa=n.idnotaventa;";
	$result = $db->arrayConsulta($sql);
	$nro = ($result['nro'] == "") ? 1 : $result['nro'] + 1; 
	echo $atencion."---";
	echo $nro."---";
	exit(); 
 }


    
 
?>