// JavaScript Document

var $$ = function(id){
 return document.getElementById(id);	
}

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

function enviar(servidor,filtro,funcion){
  var  pedido = ajax();	  
  pedido.open("GET",servidor+"?"+filtro,true);
  pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){
     	  respuesta = pedido.responseText;
		  if (funcion != null){		  
		   funcion(respuesta);
		  }
	   }	   
   }
   pedido.send(null);   	
}


var consultar = function(){
	var filtro = "transaccion=consulta&sucursal="+$$("sucursal").value+"&mes="+$$("meses").value+"&anio="+$$("anio").value;
	enviar("planillas/DPlanilla.php",filtro,resultConsulta);	
}


var resultConsulta= function(resultado){
  if (resultado == "si")
  	openMensaje();
  else
    enviarFormulario();	
}

