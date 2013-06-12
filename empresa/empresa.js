// JavaScript Document

 $(document).ready(function()
 {
   $("#formValidado").validate({});
 });

 var Meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio"
 ,"Julio","Agosto","Septiembre","Obtubre","Noviembre","Diciembre"];

 var leerURL = function(){
	 var param =  location.search;
	 if (param.length > 0) {
	 	 $$("mensajeRespuesta").innerHTML = "Sus Datos Fueron Guardados Correctamente"; 
	 }
 }
  
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;   
    if(tecla == 113){ //F2
	  document.formValidado.submit();	  
	}
 } 
 
 var $$ = function(id){
   return document.getElementById(id);	 
 }
 
 var asignarFecha = function(cadena){	
  if (cadena != ''){
   var ultdigito = parseInt((cadena[cadena.length-1]))+13;	
   var fecha = new Date();
   var mes = obtenerMes(fecha.getMonth()+1);
   $$("fechavencimientopago").value = ultdigito + " de " + mes ;  	
  }else{
   $$("fechavencimientopago").value = "";
  }
}

var viewMenu = function(id){
	var menu = ['tabs-1','tabs-2'];
	var menu2 = ['tabs1','tabs2'];
		for (var j=0;j<menu.length;j++){
	  if (menu[j] == id){
		$$(menu[j]).style.display = "block"; 
		$$(menu2[j]).style.background = "#8E8E8E"; 
		$$(menu2[j]).style.color = "#FFF"; 
	  }else{
		$$(menu[j]).style.display = "none";
		$$(menu2[j]).style.background = "#F6F6F6"; 
		$$(menu2[j]).style.color = "#666";  
	  }
	}	 
 }


var obtenerMes = function(mes){
	return Meses[mes-1];		
} 