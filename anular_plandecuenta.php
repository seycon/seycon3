<?php
include_once('bdlocal.php');
if ((isset($_GET['idplandecuenta'])) && ($_GET['idplandecuenta'] != ""))
{

	$deleteSQL = "update plandecuenta set estado=0 where idplandecuenta=".$_GET['idplandecuenta'];
	$Result1 = mysql_query($deleteSQL) or die(mysql_error());
}
header("Location: listar_plandecuentas.php#".$_GET['has']);
?>