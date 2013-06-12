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
	if ($$("transaccion").value == "insertar"){
	var filtro = "transaccion=consulta&login="+$$("login").value;
	enviar("usuario/DUsuario.php",filtro,resultConsulta);	
	}else{
	 resultConsulta("no");	
	}
}

