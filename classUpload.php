<?php
class subirArchivo{
	
	function subirArchivo($direccion, $archivo, $nombre){
		if ($archivo != "") {

			$destino =  $direccion."".$nombre;
			if (copy($archivo,$destino)) {
				$status = "Archivo subido: <b>".$archivo."</b>";
			} else {
				$status = "Error al subir el archivo";
			}
		} else {
			$status = "Error al subir archivo";
		}
	}
}

?>