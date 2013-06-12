<?php
# FileName='Connection_php_mysql.htm'
# Type='MYSQL'
# HTTP='true'
session_start();
$hostname_bdlocal = 'localhost';
$database_bdlocal = 'bdpages';
//$username_bdlocal = 'abo_jorge';
//$password_bdlocal = 'softbaseseycon2012';
$username_bdlocal = 'root';
$password_bdlocal = '';
$bdlocal = mysql_pconnect($hostname_bdlocal, $username_bdlocal, $password_bdlocal) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_bdlocal);
mysql_query("SET NAMES 'utf8'");

?>
