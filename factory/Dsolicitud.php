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
 
 
 if ($tipo == "productos"){
	$sql = "SELECT DISTINCT p.idproducto,left(p.nombre,25)as 'nombre' FROM ingresoproducto i, producto p, detalleingresoproducto d
    WHERE p.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod AND i.idalmacen =".filtro($_GET['idalmacen']).";"; 
	$consulta = $db->consulta($sql);
	echo "<option value=''>-- Seleccione --</option>";
	$db->imprimirCombo($sql);
	exit();
 }
 
 
 if ($tipo == "datosproducto"){
	$sql = "select p.idproducto,p.nombre,sum(d.cantidadactual) as 'cantidad',p.stockminimo  
    from ingresoproducto i,producto p,detalleingresoproducto d where p.idproducto=d.idproducto and d.idingresoprod=i.idingresoprod and i.idalmacen='"
	.filtro($_GET['idalmacen'])."' and p.idproducto='".$_GET['idproducto']."' group by p.idproducto;"; 
	$datos = $db->arrayConsulta($sql);
	echo $datos['cantidad']."---";
	echo $datos['stockminimo']."---";
	exit();
 }
  
 if ($tipo == "insertar"){
     $sql = "INSERT INTO solicitudF(idsolicitud,fecha,idalmacen,idusuario,detalle,estado,estadoatencion) VALUES (NULL,now(),'"
	 .filtro($_GET['idalmacen'])."','".filtro($_SESSION['idusuarioF'])."','".filtro($_GET['glosa'])."','1','Espera');";    
	 $db->consulta($sql);
	 $codigo = $db->getMaxCampo("idsolicitud","solicitudF");	 
	 $datos =  json_decode(stripcslashes($_GET['detalle']));  			
	 for ($i = 0;$i < count($datos);$i++){
	  $fila = $datos[$i];                 
	  $pedido = filtro(desconvertir($fila[0]));
	  $idproducto = filtro($fila[1]);
	  $consulta = "insert into detallesolicitudF(iddetallesolicitud,idsolicitud,idproducto,pedido) 
	  values(null,'".filtro($codigo)."','".filtro($idproducto)."','".filtro($pedido)."');";
	  $db->consulta($consulta);			
	 }		 
 }
 
 
 if ($tipo == "modificar"){
	$sql = "UPDATE solicitudF SET fecha=now(),idalmacen='".filtro($_GET['idalmacen'])."',idusuario='".filtro($_SESSION['idusuarioF'])
	."',detalle='".filtro($_GET['glosa'])."'  WHERE idsolicitud='".$_GET['idtransaccion']."';";	
	$db->consulta($sql);    
	$codigo = $_GET['idtransaccion'];
	$sql = "delete from detallesolicitudF where idsolicitud=$codigo";
	$db->consulta($sql);	  
	$datos =  json_decode(stripcslashes($_GET['detalle']));  			
	 for ($i = 0;$i < count($datos);$i++){
	  $fila = $datos[$i];                 
	  $pedido = filtro(desconvertir($fila[0]));
	  $idproducto = filtro($fila[1]);
	  $consulta = "insert into detallesolicitudF(iddetallesolicitud,idsolicitud,idproducto,pedido) 
	  values(null,'".filtro($codigo)."','".filtro($idproducto)."','".filtro($pedido)."');";
	  $db->consulta($consulta);			
	 }	  
 }

?>