// JavaScript Document

var servidor = "bonos/DBonos.php";

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
  if ($$("idbono").value == ""){	
	openMensaje("Advertencia","Debe seleccionar un ítem de la lista para poder modificar.");
	return false;
  }  
  var ids = ["bonoproduccion","horasextras","transporte","puntualidad","comision","asistencia"];
  var texto = ["bono de producción", "horas extras", "transporte", "puntualidad", "comisión", "asistencia"];  
  for (var i = 0; i < ids.length; i++) {
	 if (!isvalidoNumero(ids[i])) {
	   openMensaje("Advertencia","El dato ingresado en " + texto[i]+" es incorrecto.");
	   return false
	 }
  }
  return true;
 }


 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }

function registrarBono(){
  if (subIngresoValidos()) {	  
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";    
	$$("bonoproduccion").value = ($$("bonoproduccion").value == "") ? 0 : $$("bonoproduccion").value;  
	$$("horasextras").value = ($$("horasextras").value == "") ? 0 : $$("horasextras").value;  	
	$$("transporte").value = ($$("transporte").value == "") ? 0 : $$("transporte").value;  
	$$("puntualidad").value = ($$("puntualidad").value == "") ? 0 : $$("puntualidad").value; 
	$$("comision").value = ($$("comision").value == "") ? 0 : $$("comision").value; 
	$$("asistencia").value = ($$("asistencia").value == "") ? 0 : $$("asistencia").value; 
    var parametros = "transaccion=insertar&idbono="+$$("idbono").value+"&bonoproduccion="+$$("bonoproduccion").value
    +"&horasextras="+$$("horasextras").value+"&transporte="+$$("transporte").value+"&puntualidad="+$$("puntualidad").value
    +"&comision="+$$("comision").value+"&asistencia="+$$("asistencia").value;	
    consultar(parametros,actualizarTabla);
  }
}

function realizarConsulta(){
  if (datosValidos()){
	  $$('overlay').style.visibility = "visible";
      $$('gif').style.visibility = "visible";  
      var parametros = "transaccion=consulta&anio="+$$("anio").value+"&mes="
	  +$$("mes").value+"&sucursal="+$$("sucursal").value;	  
      consultar(parametros,cargarItemTabla);
  }
}

 var datosValidos = function(){
  if ($$("mes").value == "0"){	
   openMensaje("Advertencia","Debe seleccionar el mes.");
	return false;
  }
  
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


function realizarConsulta2(){
  var parametros = "transaccion=consulta2&anio="+$$("anio").value+"&mes="+$$("mes").value+"&sucursal="+$$("sucursal").value;
  if ($$("mes").value != "0" && $$("sucursal").value != "0"){
      $$('overlay').style.visibility = "visible";
      $$('gif').style.visibility = "visible";   	  
      consultar(parametros,cargarItemTabla);
  }
}

function actualizarTabla(resultado){
   $$('overlay').style.visibility = "hidden";
   $$('gif').style.visibility = "hidden"; 	
   var numero = $$("nro").value;	
   $$("detalleBonos").rows[numero-1].cells[5].innerHTML = convertirFormatoNumber(parseFloat($$("bonoproduccion").value).toFixed(2));
   $$("detalleBonos").rows[numero-1].cells[6].innerHTML = $$("horasextras").value;
   $$("detalleBonos").rows[numero-1].cells[7].innerHTML = convertirFormatoNumber(parseFloat($$("transporte").value).toFixed(2));
   $$("detalleBonos").rows[numero-1].cells[8].innerHTML = convertirFormatoNumber(parseFloat($$("puntualidad").value).toFixed(2));
   $$("detalleBonos").rows[numero-1].cells[9].innerHTML = convertirFormatoNumber(parseFloat($$("comision").value).toFixed(2));
   $$("detalleBonos").rows[numero-1].cells[10].innerHTML= convertirFormatoNumber(parseFloat($$("asistencia").value).toFixed(2));
   limpiarDatos();
}

function limpiarDatos()
{
   $$("nro").value = "";
   $$("bonoproduccion").value = "";
   $$("horasextras").value = "";
   $$("transporte").value = "";
   $$("puntualidad").value = "";
   $$("comision").value = "";
   $$("asistencia").value = "";	
   $$("idbono").value = "";
}

function cargarItemTabla(lista){
    $$('overlay').style.visibility = "hidden";
    $$('gif').style.visibility = "hidden"; 
	$$("detalleBonos").innerHTML = lista;
	limpiarDatos();
}

function recuperarDatos(numero,idcargo){
   $$("nro").value = numero;	
   $$("idbono").value = idcargo;
   $$("bonoproduccion").value = desconvertirFormatoNumber($$("detalleBonos").rows[numero-1].cells[5].innerHTML);
   $$("horasextras").value = $$("detalleBonos").rows[numero-1].cells[6].innerHTML;
   $$("transporte").value = desconvertirFormatoNumber($$("detalleBonos").rows[numero-1].cells[7].innerHTML);
   $$("puntualidad").value = desconvertirFormatoNumber($$("detalleBonos").rows[numero-1].cells[8].innerHTML);
   $$("comision").value = desconvertirFormatoNumber($$("detalleBonos").rows[numero-1].cells[9].innerHTML);
   $$("asistencia").value = desconvertirFormatoNumber($$("detalleBonos").rows[numero-1].cells[10].innerHTML);
}