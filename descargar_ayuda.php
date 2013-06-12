<?php
$enlace = "Manual_de_Usuario.pdf";
header ("Content-Disposition: attachment; filename=Manual_de_Usuario.pdf ");
header ("Content-Type: application/octet-stream");
header ("Content-Length: ".filesize($enlace));
readfile($enlace);
?> 