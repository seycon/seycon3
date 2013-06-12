// JavaScript Document

var servidor = "aguinaldo/Daguinaldo.php";

 var $$ = function(id){
  return document.getElementById(id);	 
 }

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"
  ); 	
}


function consultar(parametros,funcion){
 var  pedido = ajax();	
 filtro = parametros; 
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText; 
    	  funcion(resultado);   
	   }	   
   }
   pedido.send(null);
}


 var subIngresoValidos = function(){
  if ($$("idtrabajador").value == ""){	
	openMensaje("Advertencia","Debe seleccionar un ítem de la lista para poder modificar.");
	return false;
  }  
  var ids = ["meses","dias"];
  var texto = ["meses", "dias"];  
  for (var i = 0; i < ids.length; i++) {
	 if (!isvalidoNumero(ids[i])) {
	   openMensaje("Advertencia","El dato ingresado en " + texto[i]+" es incorrecto.");
	   return false
	 }
  }
  return true;
 }


function registrarAguinaldo(){
  if (subIngresoValidos()){
	$$("meses").value = ($$("meses").value == "") ? 0 : $$("meses").value;  
	$$("dias").value = ($$("dias").value == "") ? 0 : $$("dias").value; 
    var parametros = "transaccion=insertar&idtrabajador="+$$("idtrabajador").value+"&gestion="+$$("anio").value
    +"&meses="+$$("meses").value+"&dias="+$$("dias").value;	
    consultar(parametros,actualizarTabla);
  }
}

 function realizarConsulta(){	
	if (datosValidos()){
	 $$('overlay').style.visibility = "visible";
     $$('gif').style.visibility = "visible"; 
	 var parametros = "transaccion=consulta&anio="+$$("anio").value+"&sucursal="+$$("sucursal").value;	
	 consultar(parametros,cargarItemTabla);
	}
 }

 var datosValidos = function(){
  if ($$("sucursal").value == "0"){
	openMensaje("Advertencia","Debe seleccionar la sucursal.");
	return false;  
  }
  
  return true;
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


function actualizarTabla(resultado){
   var numero = $$("nro").value;	
   $$("detalleBonos").rows[numero-1].cells[5].innerHTML = $$("meses").value;
   $$("detalleBonos").rows[numero-1].cells[6].innerHTML = $$("dias").value;
   var base =desconvertirFormatoNumber($$("detalleBonos").rows[numero-1].cells[4].innerHTML);
   var opt1 = ($$("meses").value == 0) ? 0 : ((base /12) *($$("meses").value));
   var opt2 = ($$("dias").value == 0) ? 0 : ((base / 360) * ($$("dias").value));
   var liquido = opt1 + opt2;
   $$("detalleBonos").rows[numero-1].cells[7].innerHTML = liquido.toFixed(2);
   $$("nro").value = "";
   $$("meses").value = "";
   $$("dias").value = "";  
}

function cargarItemTabla(lista){
    $$('overlay').style.visibility = "hidden";
    $$('gif').style.visibility = "hidden";
	$$("detalleBonos").innerHTML = lista;
}

function recuperarDatos(numero,idtrabajador){
   $$("nro").value = numero;	
   $$("idtrabajador").value = idtrabajador;
   $$("meses").value = $$("detalleBonos").rows[numero-1].cells[5].innerHTML;
   $$("dias").value = $$("detalleBonos").rows[numero-1].cells[6].innerHTML;   
}