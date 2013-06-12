<?php

if (isset($_POST['nam'])) {
 echo "ENTRO";

//echo "Destino: ".$_FILES['logo']['name'];
copy($_FILES['logo']['tmp_name'],"img/".$_FILES['logo']['name']);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.filestyle.js" type="text/javascript"></script>
<script>
$(document).ready(function()
{	
	
	$("input[type=file]").filestyle({ 
     image: "img/file.png",
     imageheight : 21,
     imagewidth : 80,
     width : 130
   });
   
   
  });

</script>

</head>

<body>
<form id="formulario" name="formulario" method="post" action="nombrar.php" enctype='multipart/form-data'>
<input type="file" name="logo" id="logo" />
<input type="hidden" id="nam" name="nam"  value="1"/>

<br />

<input type="submit" name="Enviar" value="Enviar" />
</form>
</body>
</html>