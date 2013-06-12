// JavaScript Document


var getData = function(nombre, fila, columna){
  return $$(nombre).rows[fila].cells[columna].innerHTML;	
}

var getDatosTabla = function(origen,tabladestino){
      datos = new Array();
	       for(var i=0; i<origen.length; i++) {
				  switch(origen[i].type){
					case "set":
  					  datos.push(origen[i].data);
					break;
					case "get":
					  datos.push($$(origen[i].id).value);
					break;					  
				  }			 
            }
	  return datos;		
}


var cargarDatos = function(formato,datosIngreso,tabladestino){
   var datos = new Array();   
   var total = new Array();
   if (SubIngreso(datosIngreso,'validar')){
     datos = getDatosTabla(datosIngreso,tabladestino);
	 if (arguments.length >= 4)
      total = insertarFila(formato,datos,tabladestino,arguments[3],arguments[4]);
	 else
	  total = insertarFila(formato,datos,tabladestino);
	 SubIngreso(datosIngreso,'limpiar');
   }
   return total;
}


var insertarFila = function(formato,datos,tabla){
  var pos;	
	if (arguments.length >= 4){
	 pos = arguments[4];
	}else{
	 pos = $$(tabla).rows.length;
	}
   var x = $$(tabla).insertRow(pos);
   var total = new Array();
   var iten = 0;
   
     for (var i = 0;i < datos.length;i++){
        var y = x.insertCell(i);
		var formatoTabla = formato[i];
		var dato = datos[i];
		
        if (arguments.length >= 3)
		 y.className = arguments[3];
		 
		
		if (formatoTabla.type == "contable"){
			total[iten] = parseFloat(dato).toFixed(2);
			iten++;
		}
		
		if (formatoTabla.numerico == "si"){
		  dato = convertirFormatoNumber(parseFloat(dato).toFixed(2));
		}
		
		if (formatoTabla.display != null){
		  y.style.display = formatoTabla.display;  
		}
		
		y.align = formatoTabla.aling;			
        y.innerHTML = dato;
     }
   return total;	 
}

var SubIngreso = function(datos,tipo){
  for(var i=0; i<datos.length; i++) {
	   if(datos[i].type == "get" && $$(datos[i].id).value == "" && tipo == "validar"){
		  return false;
	   }
	   if (tipo == "limpiar"){
		  if (datos[i].type == "get") 
		   $$(datos[i].id).value = "";
	   }
	}
	return true;		
}