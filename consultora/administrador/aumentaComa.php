<?php

function dividendo($valor){
	return  (int)($valor/1000);
}


function aumentar($cadena,$pos){
	$convertir = "";
	for($i=0;$i<strlen($cadena);$i++){
		$convertir = $convertir.$cadena[$i];
		if ($i+1==$pos)
		$convertir = $convertir.",";

	}
	return $convertir;
}


function desconvertir($cadena){
	$convertir = "";     
	for($i=0;$i<strlen($cadena);$i++){		
		if ($cadena[$i]!=",")
		$convertir = $convertir.$cadena[$i];

	}
	return $convertir;
}

function convertir($valor){
	$total=$valor;
	$conversion = $valor."";
	while($total>=1000){		
	 $total = dividendo($total);
	 $pos =strlen($total."");
	 $conversion =aumentar($conversion,$pos);
	}
	return $conversion;
}

function mes($dato){
  $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");		 
  return $meses[$dato-1];
}



?>