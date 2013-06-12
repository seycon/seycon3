/*
Author : Marco Farell
*/

//selecciona una opcion del combo
 var seleccionarCombo = function(combo,opcion){	 
	 var cb = document.getElementById(combo);
	 for (var i=0;i<cb.length;i++){
		if (cb[i].value==opcion){
		cb[i].selected = true;
		break;
		}
	 }	 
 }
 
 //obtiene la opcion seleccionada del combo
 var obtenerSeleccionCombo = function(nombreCombo){	
     var combo  = document.getElementById(nombreCombo);  
      return combo.options[combo.selectedIndex].text;	 
 }

//selecciona una opcion del radio
var seleccionarRadio = function(formulario,radio,opcion){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i<form.length;i++){
		if (form[i].value==opcion){		
		form[i].checked = true;
		break;
		}
	}
}

//obtiene la opcion seleccionada del radio
var obtenerSeleccionRadio = function(formulario,radio){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i< form.length;i++){
	if (form[i].checked==true)
  	  return form[i].value;   
	}	
}

//selecciona o quita la seleccion de un check 
var seleccionarCheck =  function(formulario,check,estado){
	var form = eval("document."+formulario+"."+check);
	var e = (estado == true) ? 'check' : '' ;
	form.checked = e;
}

var convertirFormatoNumber = function(valor){	
	var total = valor;
	var conversion = valor + "";
	var convertir = "";
	while(total >= 1000){		
	 total = dividendo(total);
     var convertir = ""+total;      
	 var pos = convertir.length; 	
	 conversion = aumentar(conversion,pos);	 
	}
	return conversion;
}

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

var desconvertirFormatoNumber = function(cadena){
	convertir = "";     
	for(i = 0;i < cadena.length;i++){		
		if (cadena[i] != ",")
		convertir = convertir + cadena[i];
	}
	return convertir;
}

//solo solo numeros y el punto
function soloNumeros(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
  return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
}

function soloEnteros(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
  return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0);
}

function obtenerOpcionCombo(){
 return $$('formadepago').options[$$('formadepago').selectedIndex].value;
}

function validarFecha(value){  
    var Fecha= new String(value);   
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length));  
    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")));
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")));  
  
    if (isNaN(Ano) || Ano.length<4 || Ano.length>4 || parseFloat(Ano)<1900){  
        return false;  
    }  
 
    if (isNaN(Mes) || parseFloat(Mes)<1 ||  parseFloat(Mes)>12 || Mes.length>2){  
        return false;  
    }  

    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31 || Mes.length>2){  
        return false  ;
    }  
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
        if (Mes==2 && Dia > 28 || Dia>30) {  
            return false;  
        }  
    }      

  return true;   
}