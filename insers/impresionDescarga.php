<?php
$nombre = 'precios.txt'; 
$contenido =
str_pad("",5," ",STR_PAD_LEFT).
str_pad('Karaoke disco - Buffalo',29," ",STR_PAD_RIGHT).PHP_EOL.

str_pad("",33,"-",STR_PAD_RIGHT).PHP_EOL.PHP_EOL.
str_pad("Nota Venta: 1",25," ",STR_PAD_RIGHT).PHP_EOL.
str_pad("Cliente: Varios",25," ",STR_PAD_RIGHT).PHP_EOL.
str_pad("Fecha: 15/11/2012",25," ",STR_PAD_RIGHT).PHP_EOL.PHP_EOL.


str_pad("",33,"-",STR_PAD_RIGHT).PHP_EOL. 
str_pad("Cant.",6," ",STR_PAD_RIGHT).
str_pad("Producto",11," ",STR_PAD_RIGHT).
str_pad("P/U",7," ",STR_PAD_RIGHT).
str_pad("P/Total",7," ",STR_PAD_RIGHT).PHP_EOL.
str_pad("",33,"-",STR_PAD_RIGHT).PHP_EOL.


str_pad("2",6," ",STR_PAD_RIGHT).
str_pad("Licuadora",11," ",STR_PAD_RIGHT).
str_pad("5",7," ",STR_PAD_RIGHT).
str_pad("10",7," ",STR_PAD_RIGHT).PHP_EOL.
str_pad("2",7," ",STR_PAD_RIGHT).
str_pad("Batidora",11," ",STR_PAD_RIGHT).
str_pad("5",7," ",STR_PAD_RIGHT).
str_pad("15",7," ",STR_PAD_RIGHT).PHP_EOL.PHP_EOL.

str_pad("Total: 25",30," ",STR_PAD_LEFT).PHP_EOL.PHP_EOL.
str_pad("",1," ",STR_PAD_LEFT).
str_pad("",18,"-",STR_PAD_LEFT).PHP_EOL.
str_pad("",3," ",STR_PAD_LEFT).
str_pad("Marco rodrigo",20," ",STR_PAD_RIGHT).PHP_EOL.PHP_EOL.

str_pad("Pedido: 25",30," ",STR_PAD_LEFT).PHP_EOL.
str_pad("Hora: 25",30," ",STR_PAD_LEFT).PHP_EOL;



header( "Content-Type: application/octet-stream");
header( "Content-Disposition: attachment; filename=".$nombre."");
print($contenido);
?>