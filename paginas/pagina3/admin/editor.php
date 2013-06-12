<?php
 include("conexion.php");
    $db = new MySQL();
    session_start();
   if (isset($_POST['pies1']))
    {
    $sql = "insert into contem values(null,'$_POST[pies1]')";
	$db->consulta($sql);
	header("Location: editor.php");
   }
   
   $sql = "select *from contem where idcontem=2";
   $data = $db->arrayConsulta($sql);
   
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>

<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>

</head>

<body>
<form id="formulario" name="formulario" method="post" action="editor.php"> 
<textarea id="pies1" name="pies1" cols="40"><?php echo $data['descripcion'];?></textarea>
<input type="submit" id="enviar" name="enviar"/>
</form>

</body>
</html>