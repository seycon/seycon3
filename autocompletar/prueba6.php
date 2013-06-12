<?php
  session_start();
  include('../conexion.php'); 
  $db = new MySQL();
   
  for ($i = 582; $i <= 600; $i++) {
	 $sql = "insert into libroventasiva values(null, 3, '2013-04-01', '0', 'Anulado', $i
	 , '7001002607131', '0', 0, 0, 0, 0, 0, NULL, 'Libro Ventas', 'LV', '', 'servicios', 'A', 3, 1);";  
     $db->consulta($sql);;   
  }

?>