<?php
/**
 * Imprime una cantidad especificada de tabulaciones.
 */ 
function tabulacion( $cant ){
	for( $i = 1 ; $i <= $cant ; $i++ )
	{
		echo("\t");
	}
}
?>