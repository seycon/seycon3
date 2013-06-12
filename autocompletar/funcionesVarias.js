// JavaScript Document


var dividendo = function(valor){
	return  parseInt(valor/1000);
}


var aumentar = function(cadena,pos){
	convertir = "";
	for(i=0;i<cadena.length;i++){
		convertir = convertir + cadena[i];
		if (i+1 == pos)
		convertir = convertir + ",";

	}
	return convertir;
}

var desconvertir = function(cadena){
	convertir = "";     
	for(i=0;i<cadena.length;i++){		
		if (cadena[i]!=",")
		convertir = convertir + cadena[i];
	}
	alert(convertir);
}

var convertirDecimales = function(valor){
	
	var total=valor;
	var conversion = valor + "";
	var convertir = "";
	while(total >= 1000){		
	 total = dividendo(total);
     var convertir = ""+total;      
	var pos = convertir.length; 	
	 conversion =aumentar(conversion,pos);	 
	}
	return conversion;

}

