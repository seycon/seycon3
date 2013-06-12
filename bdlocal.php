<?php
  session_start();
# FileName='Connection_php_mysql.htm'
# Type='MYSQL'
# HTTP='true'

  $hostname_bdlocal = 'localhost';
  $database_bdlocal = $_SESSION['BDname'];
  // $username_bdlocal = 'jorge_bdseycon';
  // $password_bdlocal = 'softbaseseycon2012';
  $username_bdlocal = 'root';
  $password_bdlocal = '';
  $bdlocal = mysql_pconnect($hostname_bdlocal, $username_bdlocal, $password_bdlocal) or trigger_error(mysql_error(),E_USER_ERROR);
  mysql_select_db($database_bdlocal);
  mysql_query("SET NAMES 'utf8'");

  function tieneacceso($tabla) {
	  session_start();
	  $tablars= mysql_query("select $tabla from acceso where idusuario = '".$_SESSION['id_usuario']."';");
	  $acceso = mysql_fetch_row($tablars);
  
	  if ($acceso[0] == 0) {		
		header("Location: index.php" );
	  }
  }


?>
