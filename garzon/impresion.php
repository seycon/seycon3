<?php


/**
 * @author ZEGARRA CORNE, Sergio
 * @copyright 2009
 */


if(($handle = @fopen("COM1", "w")) === FALSE){
        die('No se puedo Imprimir, Verifique su conexion con el Terminal');
    }

$dato = "Marco Rodrigo Farell";  

fwrite($handle,chr(27). chr(64));//reinicio

//fwrite($handle, chr(27). chr(112). chr(48));//ABRIR EL CAJON
fwrite($handle, chr(27). chr(100). chr(0));//salto de linea VACIO
fwrite($handle, chr(27). chr(33). chr(8));//negrita
fwrite($handle, chr(27). chr(97). chr(1));//centrado
fwrite($handle,"=================================");
fwrite($handle, chr(27). chr(100). chr(1));//salto de linea
fwrite($handle, chr(27). chr(32). chr(3));//ESTACIO ENTRE LETRAS
fwrite($handle,"Titulo Principal ");
fwrite($handle, chr(27). chr(32). chr(3));//ESTACIO ENTRE LETRAS
fwrite($handle, chr(27). chr(100). chr(1));//salto de linea VACIO
fwrite($handle, chr(27). chr(100). chr(1));//salto de linea
fwrite($handle,"Nacimos de Nuevo para ser grandes");
fwrite($handle, chr(27). chr(100). chr(1));//salto de linea
fwrite($handle,"=================================");
fwrite($handle, chr(27). chr(100). chr(1));//salto de linea
fwrite($handle, chr(27). chr(100). chr(1));//salto de linea
fwrite($handle,$dato);


fclose($handle); // cierra el fichero PRN
$salida = shell_exec('lpr COM1'); //lpr->puerto impresora


?>


