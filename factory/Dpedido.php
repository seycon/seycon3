<?php
session_start(); 
 include("../conexion.php");
 include("../aumentaComa.php");
 $db = new MySQL();
 $tipo = $_GET["transaccion"];
 
 if (!isset($_SESSION['idusuarioF'])){
  header("Location: index.php");	
 }
 
 function filtro($cadena){
  return htmlspecialchars(strip_tags($cadena));
 }
  
 if ($tipo == "insertar"){
     $sql = "INSERT INTO pedidoespecialF(idpedido,nombre,dia,hora,telefono,total,acuenta,estado,pirotines,masa,crema,relleno,glosa) VALUES (NULL,'"
	 .filtro($_GET['nombre'])."','".filtro($_GET['dia'])."','".filtro($_GET['hora'])."','".filtro($_GET['telefono'])."','".filtro($_GET['total'])
	 ."','".filtro($_GET['acuenta'])."','".filtro($_GET['estado'])."','".filtro($_GET['pirotines'])."','".filtro($_GET['masa'])."','"
	 .filtro($_GET['crema'])."','".filtro($_GET['relleno'])."','".filtro($_GET['glosa'])."');";    
	 $db->consulta($sql);
	 $codigo = $db->getMaxCampo("idpedido","pedidoespecialF");	 
	 $datos =  json_decode(stripcslashes($_GET['detalle']));  			
	 for ($i = 0;$i < count($datos);$i++){
	  $fila = $datos[$i];                 
	  $tipo = filtro($fila[0]);
	  $cantidad = filtro(desconvertir($fila[1]));
	  $precio = filtro(desconvertir($fila[2]));
	  $idcupcakes = filtro($fila[3]);
	  $consulta = "insert into detallepedidoF(iddetallepedido,idpedido,idcupcakes,cantidad,precio,tipo) 
	  values(null,'".filtro($codigo)."','".filtro($idcupcakes)."','".filtro($cantidad)."','".filtro($precio)."','".filtro($tipo)."');";
	  $db->consulta($consulta);			
	 }		 
 }
 
 
 if ($tipo == "modificar"){
	$sql = "UPDATE pedidoespecialF SET nombre='".filtro($_GET['nombre'])."',dia='".filtro($_GET['dia'])."',hora='".filtro($_GET['hora'])
	  ."',telefono='".filtro($_GET['telefono'])."',total='".filtro($_GET['total'])."',acuenta='".filtro($_GET['acuenta'])."',estado='"
	  .filtro($_GET['estado'])."',pirotines='".filtro($_GET['pirotines'])."',masa='".filtro($_GET['masa'])
	  ."',crema='".filtro($_GET['crema'])."',relleno='".filtro($_GET['relleno'])."',glosa='".filtro($_GET['glosa'])
	  ."'  WHERE idpedido= '".$_GET['idtransaccion']."';";	
	$db->consulta($sql);    
	$codigo = $_GET['idtransaccion'];
	$sql = "delete from detallepedidoF where idpedido=$codigo";
	$db->consulta($sql);	  
	$datos =  json_decode(stripcslashes($_GET['detalle']));  			
	for ($i = 0;$i < count($datos);$i++){
	  $fila = $datos[$i];                 
	  $tipo = filtro($fila[0]);
	  $cantidad = filtro(desconvertir($fila[1]));
	  $precio = filtro(desconvertir($fila[2]));
	  $idcupcakes = filtro($fila[3]);
	  $consulta = "insert into detallepedidoF(iddetallepedido,idpedido,idcupcakes,cantidad,precio,tipo) 
	  values(null,'".filtro($codigo)."','".filtro($idcupcakes)."','".filtro($cantidad)."','".filtro($precio)."','".filtro($tipo)."');";
	  $db->consulta($consulta);			
	}		  
 }

?>