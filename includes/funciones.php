<?php
/**
 * Conecta con la base de datos y decuelve el identificador
 * de la conección.
 */

function consulta( $sql ){
  $res = mysql_query($sql) or die (mysql_error());
  mysql_query("SET NAMES 'utf8'");
  return $res;	
}

function FormatoFecha($fecha){
  $date = explode("-",$fecha);
  return $date[2]."/".$date[1]."/".$date[0];
}

?>