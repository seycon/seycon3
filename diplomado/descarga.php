<?php
header("Content-type: image/jpeg");
header('Content-Disposition: attachment; filename="autocinema_coyote_indicaciones.jpg"');
//nombre_imagen.gif es el nombre de la imagen tras la descarga 
readfile('files/autocinema_coyote_indicaciones.jpg'); 
//leemos la imagen.
//nombre_imagen.gif debe ser la ruta para llegar a la imagen.
?>