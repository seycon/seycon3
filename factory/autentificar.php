<?php
 session_start(); 
 include("../conexion.php");
// $_SESSION['BDname'] = "jorge_factory";
 $_SESSION['BDname'] = "bdkiwis";
 
 function filtro($cadena){
  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
 }
 
 $db = new MySQL();
 $login = filtro($_GET['usuario']);
 $clave = filtro($_GET['clave']); 
 $sql=sprintf("select u.idusuario,t.idtrabajador,left(concat(t.nombre,' ',t.apellido),20)as 'nombre'  from usuarioF u,trabajador t where t.idtrabajador=u.idtrabajador and login=%s and clave=%s and u.estado=1 limit 1;",filtroSeguridad($login, "text"), filtroSeguridad($clave, "text"));    
 $cantidad = $db->getnumRow($sql);
 $data = $db->arrayConsulta($sql);
  if ($cantidad>0){
   $_SESSION['nombreusuarioF'] = $data['nombre'];
   $_SESSION['idusuarioF'] = $data['idusuario'];
   
   $sql = "select p.nombre,p.url from privilegiosF p,detalleprivilegioF d where d.idprivilegios=p.idprivilegios 
   and d.idusuario=$data[idusuario] order by p.idprivilegios;";
   $menuFactory = array();
   $i = 0;
   $consulta = $db->consulta($sql);
   while ($data = mysql_fetch_array($consulta)){
    $menu = array();	
    $menu['titulo'] = $data['nombre'];
    $menu['url'] = $data['url'];	
    $menuFactory[$i] = $menu;
    $i++;
   }
   $_SESSION['menuFactory'] = $menuFactory;   
   echo "correcto";
   exit();
  }  
  echo "incorrecto";
  
  
  
 function filtroSeguridad($valor, $tipo){
  if (PHP_VERSION < 6) {
    $valor = get_magic_quotes_gpc() ? stripslashes($valor) : $valor;
  }
  $valor = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($valor) : mysql_escape_string($valor);
  switch ($tipo) {
    case "text":
      $valor = ($valor != "") ? "'" . $valor . "'" : "NULL";
      break;        
  }
  return $valor;
 }
  
?>