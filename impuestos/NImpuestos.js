// JavaScript Document
var irDireccion = "listar_impuestos.php#t10";
var transaccion = 'insertar';
var servidorT = "impuestos/DImpuestos.php";
var codigoTransaccion = 0;
var dirLocal = "nuevo_impuestos.php";



var $$ = function(id){
	return document.getElementById(id);
}

document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2
	   ejecutarTransaccion();
	break;
	case 115://F4
	  if ($$("cancelar") != null)
	   salir();
	break;
   }
 }


var salir = function(){
	location.href = irDireccion;
}


var eventoResultadoEgreso = function(resultado,codigo){
	  $$("texto").value= resultado;
	  $$("idpersonarecibida").value = codigo;	  	   
}
   


function eventoText(evt){
var tecla = (document.all) ? evt.keyCode : evt.which;
  if (tecla == 13){
    insertarNewItem('detalleTransaccion');
  }
}


function enviar(filtro,funcion){
  var  pedido = ajax();	  
  pedido.open("GET",servidorT+"?"+filtro,true);
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


function cerrarSubVentana(){
	$$("subventana").style.visibility = "hidden";  
	$$("overlay").style.visibility = "hidden";
}


var datosValidos = function(){    
  if ($$("mes").value == ""){
	openMensaje("Advertencia","Debe Seleccionar el Mes del Impuesto");  
    return false;
  }  
  return true;
}

function ejecutarTransaccion(){	
 var filtro;
	 if (datosValidos()){	
  	   filtro = "transaccion="+transaccion+"&mes="+$$("mes").value+"&fecha="+$$("fecha").value+
	   "&tipocambio="+$$("tipocambio").value+"&idimpuesto="+$$("idTransaccion").value;
	   enviar(filtro,respuestaEjecutarT);
	 }
}

function respuestaEjecutarT(respuesta){
  cerrarPagina();     	
}

 function cerrarPagina(){
	window.location = dirLocal;	 
 }

function accionPostRegistro(){
   window.open('librodiario/imprimir_libro.php?idlibrodiario='+codigoTransaccion,'target:_blank');	
}

var openMensaje = function(titulo,contenido){
	$$("modal_tituloCabecera").innerHTML = titulo;
	$$("modal_contenido").innerHTML = contenido;
	$$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";  
  }
  
  var closeMensaje = function(){
	$$("modal_mensajes").style.visibility = "hidden";
    $$("overlay").style.visibility = "hidden";    
  }