// JavaScript Document
var irDireccion = "listar_grupo.php#t9";
var transaccion = 'insertar';
var Servidor = "finiquitos/Dfiniquitos.php";

var $$ = function(id){
	return document.getElementById(id);
}

var insertarFila = function(datos,tabla){
   var x = $$(tabla).insertRow($$(tabla).rows.length);
     for (var i = 0;i < datos.length;i++){
        var y = x.insertCell(i);
		if (i <= 1 || i == 3)
		 y.align = "center";		
		
		if (i == 3){
		  datos[i] = convertirFormatoNumber(parseFloat(datos[i]).toFixed(2)); 	
		}
			
        y.innerHTML = datos[i];
     }
}

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;
    table.removeChild(tr);
}

//teclas de atajo
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if(tecla == 115){//F5
    if ($$("cancelar") != null)
	 $$("cancelar").click();
   }
	
   if(tecla == 113)//F2
	 enviarDetalle();
 }

function eventoText(evt){
var tecla = (document.all) ? evt.keyCode : evt.which;
  if (tecla == 13){
    cargarDatos('datosorigen','detallegrupo');
	$$("descripcionD").focus();
  }
}

var getDatosTabla = function(tablaorigen,tabladestino){
     var datos = new Array();
     var n =  $$(tabladestino).rows.length + 1;
     datos[0] = "<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />";
     datos[1] = n;
	  var parrafos = $$(tablaorigen).getElementsByTagName("input");
	       for(var i=0; i<parrafos.length; i++) {
			   if(parrafos[i].getAttribute('type')=='text'){
				  var cant = datos.length; 
				  datos.splice(cant,0,parrafos[i].value);
			   }
            }
	  return datos;		
}



var cargarDatos = function(tablaorigen,tabladestino){
   var datos = new Array();
   if (validaSubIngreso()){
     if (SubIngreso(tablaorigen,'validar')){
       datos = getDatosTabla(tablaorigen,tabladestino);
       insertarFila(datos,tabladestino);
	   SubIngreso(tablaorigen,'limpiar');
     }
   }
}


var SubIngreso = function(tabla,tipo){
	 var parrafos = $$(tabla).getElementsByTagName("input");
	       for(var i=0; i<parrafos.length; i++) {
			   if(parrafos[i].getAttribute('type') == 'text' && parrafos[i].value == "" && tipo == "validar"){
				  return false;
			   }
			   if (parrafos[i].getAttribute('type') == 'text' && tipo == "limpiar"){
				   parrafos[i].value = "";
			   }
            }
	return true;		
}


var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

function datosValidos(){
	if($$("trabajador").value == ""){
	  openMensaje("Advertencia",'Debe seleccionar el Trabajador');
	  return false;	
	}
	return true;
}


function datosValidos(){
	if($$("trabajador").value == ""){
	  openMensaje("Advertencia",'Debe seleccionar el Trabajador');
	  return false;	
	}
	return true;
}

var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }
 
var validaSubIngreso = function(){
	if(!isvalidoNumero("totalD")){
	  openMensaje("Advertencia",'Debe ingresar un numero valido');
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

function soloNumeros(evt){
 var tecla = (document.all) ? evt.keyCode : evt.which;
 return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
}

var enviarDetalle = function() {
	if (datosValidos()){	
	$$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";
     nfilas = $$('detallegrupo').rows.length;    	
     json = new Array();
     for(i=0;i<nfilas;i++) {
	   vector = new Array();
			vector[0] = encodeURIComponent($$('detallegrupo').rows[i].cells[2].innerHTML);		
			vector[1] = $$('detallegrupo').rows[i].cells[3].innerHTML;
			json[i] = vector;	 		
     }
     dato = JSON.stringify(json); 	 
	 mesesV = ($$("mesesvacaciones").value == "") ? 0 : $$("mesesvacaciones").value;
	 diasV = ($$("diasvacaciones").value == "") ? 0 : $$("diasvacaciones").value;
	 mesesP = ($$("mesesprima").value == "") ? 0 : $$("mesesprima").value;
	 diasP = ($$("diasprima").value == "") ? 0 : $$("diasprima").value;
     datos = 'detalle='+dato+'&trabajador='+$$("trabajador").value+'&transaccion='+transaccion+'&motivo='+$$U("motivo")
	 +"&mesesvacaciones="+mesesV+"&diasvacaciones="+diasV+"&mesesprima="+mesesP
	 +"&diasprima="+diasP+"&descripcionotros="+$$U("descripcionotros")+"&totalotros="+$$("montootros").value+
	 "&idfiniquito="+$$("idfiniquitos").value+"&fecha="+$$("fecha").value; 	 	
     enviar(datos);
	 $$('detallegrupo').innerHTML = "";
	}
}

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

function enviar(datos){
  var  pedido = ajax();	  
  pedido.open("GET",Servidor+"?"+datos,true);
  pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     
	     location.href = "nuevo_finiquito.php";
	   }	   
   }
   pedido.send(null);
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
