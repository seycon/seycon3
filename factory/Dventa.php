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
 
 
 
   if ($tipo == "busqueda"){
	  $sql = "select idcombinacion,left(nombre,17) as 'nombre1',total as 'precio',nombre from combinacion where nombre like '$_GET[texto]%' and estado=1;";
	  $producto = $db->consulta($sql);	  
	  while($dato = mysql_fetch_array($producto)){
    	 echo "<li onclick='openVentanaPedido(&quot;$dato[nombre]&quot;,&quot;$dato[precio]&quot;,&quot;$dato[idcombinacion]&quot;)'>$dato[nombre1]</li>";		  
	  }
	  echo "";
	  exit();
   }
   
   if ($tipo == "eliminarDetalle"){
	  $sql = "update detallenotaF set estado=0 where iddetallenotaf='$_GET[iddetalle]';";  
	  $db->consulta($sql); 
	  exit();
   }
      
   if ($tipo == "insertarVenta"){
	   $nronota = "";
	   $sql = "select idnotaventa from notaventaF where idatencion=".filtro($_GET['idatencion']);
	   $nota = $db->arrayConsulta($sql);
	   $nronota = $nota['idnotaventa'];
	   $actualizar = "si";
	   if ($nronota == ""){
		 $sql = "insert into notaventaF(idnotaventa,fecha,idatencion,idpersona,tipopersona,nombrepersona,tiponota,acuenta) values(null,now(),'"
		 .filtro($_GET['idatencion'])."','".filtro($_GET['idpersona'])."','".filtro($_GET['tipopersona'])."','".filtro($_GET['nombrepersona'])."','"
		 .filtro($_GET['tiponota'])."',0)";  
		 $db->consulta($sql); 
		 $sql = "select idnotaventa from notaventaF where idatencion=".filtro($_GET['idatencion']);
	     $nota = $db->arrayConsulta($sql);
	     $nronota = $nota['idnotaventa'];
		 $actualizar = "no";
	   }
	  if ($actualizar == "si"){	   
	   $sql = "update notaventaF set idpersona='".filtro($_GET['idpersona'])."',nombrepersona='".filtro($_GET['nombrepersona'])."',tiponota='".
	   filtro($_GET['tiponota'])."',tipopersona='".filtro($_GET['tipopersona'])."' where idnotaventa=$nronota";  
	  $db->consulta($sql);
	  }
	   
	 $sql = "insert into detallenotaF(iddetallenotaf,idnotaventa,idcombinacion,precio,cantidad,estado,nroatencion) 
	 values(null,'$nronota','".filtro($_GET['idcombinacion'])."','".filtro($_GET['precio'])."','".filtro($_GET['cantidad'])."','1','".filtro($_GET['nroatencion'])."');";   
	 $db->consulta($sql);
	 echo $db->getMaxCampo('iddetallenotaf',"detallenotaF");
	 exit();
   }
   
   if ($tipo == "actualizarAtencion"){
	$sql = "update atencionF set estado='".filtro($_GET['estado'])."' where idatencion='".filtro($_GET['idatencion'])."'";
	$db->consulta($sql);   
	exit();
   }
   
   if ($tipo == "actualizarCobroMesa"){
	$sql = "update atencionF set estado='".filtro($_GET['estado'])."' where idatencion='".filtro($_GET['idatencion'])."'";
	$db->consulta($sql);   
	$sql = "update notaventaF n,atencionF a set n.acuenta=".filtro($_GET['acuenta'])." where a.idatencion=n.idatencion and a.idatencion='".filtro($_GET['idatencion'])."'";
	$db->consulta($sql);
	exit();
   }
   
   if ($tipo == "totalMesa"){
	$sql = "select sum(d.precio*d.cantidad) as 'total',n.tiponota,n.nombrepersona from detallenotaF d,notaventaF n where d.idnotaventa=n.idnotaventa and d.idnotaventa="
	.filtro($_GET['idnotaventa'])." group by d.idnotaventa";   
	$total = $db->arrayConsulta($sql);
	echo $total['total']."---";
	echo $total['tiponota']."---";
	echo $total['nombrepersona']."---";
	exit();
   }
 
 
 
?>