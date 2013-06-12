// JavaScript Document

var total_bolivianos = 0;
var total_Dolares = 0;
var idUTransaccion = 0;
var transaccion = "insertar";
var servidor = "solicitud_pedidos/DSolicitud.php";
var irDireccion = "listar_solicitud.php";

 var $$ = function(id){
  return document.getElementById(id);	 
 }

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

function accion(){
	 $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
	 $$("dato").value = "";
	 $$("dato").focus();	 
}

var autocompletar = function(e,id){
	var consulta = "select idproducto,left(nombre,25)as 'nombre' from producto where estado=1 and "; 
	eventoTeclas(e,id,'resultados','producto','nombre','idproducto','eventoResultado','autocompletar/consultor.php',consulta,'','autoL1');
}

//teclas de atajo
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 115://F4
	  if ($$("cancelar") != null)
	   salir();
	break;
	case 113://F2
	  if($$('overlay').style.visibility != "visible")
	   enviarMaestro();
	break;
	case 112:
      enviarDireccion();
	break;
    case 27:
	  if ($$("modal").style.visibility == "visible")
         accion();
	break;
   }
 }

function consultar(parametros,funcion){
 var  pedido = ajax();	
 filtro ="transaccion=consulta&codigo="+parametros; 
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText.split("---");    
    	  funcion(resultado[0],resultado[1]);   
	   }	   
   }
   pedido.send(null);
}


var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}


var enviarMaestro = function(){
  if (esvalido()){	
  $$('overlay').style.visibility = "visible";
  $$('gif').style.visibility = "visible";
    almacen = $$('almacen').value; 
    proveedor = $$('proveedor').value;
    contacto = $$U('contacto');   
    moneda = $$U('moneda');  
    montototal = desconvertirFormatoNumber($$("subtotalBS").value);  
    filtro ="fecha="+$$("fecha").value+"&almacen="+almacen+"&proveedor="+proveedor+"&externo="+$$U("externo")+
     "&contacto="+contacto+"&moneda="+moneda+"&glosa="+$$U("glosa")+"&monto="+montototal+"&transaccion="+transaccion
    +"&idsolicitud="+$$("idSolicitud").value
    +"&tc="+$$("tipoCambioBs").value;  
    enviarDetalle(filtro);
  }
  else{
	mostrarMensajeError();  
  }
}

function mostrarMensajeError(){
  if ($$('almacen').value == ""){
   openMensaje("Advertencia",'Debe seleccionar un Almacén');
   return;
  }
    if ($$('proveedor').value == ""){
   openMensaje("Advertencia",'Debe seleccionar un Proveedor');
   return;
  }
    if ($$('detalleSolicitud').rows.length == 0){
   openMensaje("Advertencia",'Debe ingresar detalle en la Solicitud');
  } 	
}

var setProvedorExterno = function(valor){
  if (valor == "0"){
	$$("externo").style.display = "block";  
  }else{
	$$("externo").value = "";  
	$$("externo").style.display = "none"; 
  }	
}

function enviar(datos){
  var  pedido = ajax();	  
  pedido.open("GET",servidor+"?"+datos,true);
  pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){
		   datos = pedido.responseText.split("---"); 
		  if (datos[1] == "1"){
		    idUTransaccion = datos[0];	   
 		    $$('overlay').style.visibility = "visible";
            $$('modal_vendido').style.visibility = "visible";
			$$('gif').style.visibility = "hidden";      		   
		  }else{
		  	enviarDireccion(); 
		  }
	   }	   
   }
   pedido.send(null);   	
}

function enviarDireccion(){
  window.location.href = "nuevo_solicitud.php";	
  return;
}

function esvalido(){
  return ($$('proveedor').value != "" && $$('almacen').value != "" && $$('detalleSolicitud').rows.length > 0); 	
}

function accionPostRegistro(){
   window.open('solicitud_pedidos/imprimir_SolitudProductos.php?idsolicitud='+idUTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
   enviarDireccion();
}

var eventoIngresoCantidad = function(evento){
	var tecla = (document.all) ? evento.keyCode : evento.which;		
	if (tecla == 13)
	agrega_celda();	
}

var enviarDetalle = function(filtro) {		
     nfilas = $$('detalleSolicitud').rows.length;    	
     json = new Array();
     for(i = 0;i < nfilas;i++) {
	   vector = new Array();
	   vector[0]=$$('detalleSolicitud').rows[i].cells[1].innerHTML;		
	   vector[1]=$$('detalleSolicitud').rows[i].cells[3].innerHTML;
	   vector[2]=$$('detalleSolicitud').rows[i].cells[4].innerHTML;								
	   vector[3]=$$('detalleSolicitud').rows[i].cells[5].innerHTML;		
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);	 
     filtro = filtro + '&detalle=' + dato; 	 	
     enviar(filtro);
  }

 var eventoResultado = function(resultado,codigo){	     
     consultar(codigo,cargarCantidad); 
     $$("dato").value =resultado;
	 $$("codidproducto").value = codigo; 	 
     $$("msjsubnumero").style.display = "none";
	 $$("overlay").style.visibility = "visible";
	 $$('gif').style.visibility = "visible";
	 $$("cant").value="";      		
 }
 
 
 var cargarCantidad = function(cantidad,precio){
	 var aux = 1;
	 if ($$("moneda").value == "Dolares"){
		aux = $$("tipoCambioBs").value; 
	 }	 
	 $$("precioProducto").value = precio/aux;
	 $$('gif').style.visibility = "hidden";
	 $$("modal").style.visibility = "visible";
	 document.form1.cant.focus(); 
 }
  
 
 var insertarFila = function(datos,tabla){
   var Data = new Array();	 
   var x = $$(tabla).insertRow($$(tabla).rows.length);
   x.bgColor= "#F6F6F6" ;
     for (var i = 0;i < datos.length;i++){
        var y = x.insertCell(i);
		Data = datos[i];
		y.align = Data[1];			
        y.innerHTML = Data[0];
     }
}

 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
	setTotal(-parseFloat(desconvertirFormatoNumber(tr.cells[5].innerHTML)),$$("moneda").value);
    var table = tr.parentNode;
    table.removeChild(tr);
 }
 
 function subVentanaValida(){
	var cantidad = ($$("cant").value == "") ? 0 : $$("cant").value; 
   if (parseFloat(cantidad) <= 0){
	  $$("msjsubnumero").innerHMTL = "Incorrecto"; 
	  $$("msjsubnumero").style.display = "block";
	  return false;	
	}
	if (!isvalidoNumero("cant")){
	  $$("msjsubnumero").innerHMTL = "Incorrecto"; 
	  $$("msjsubnumero").style.display = "block";
	  return false;		
	}
	
	return true;
 }
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
  }
 
 
 var agrega_celda = function(){	
   if(subVentanaValida()){
     var datos = new Array();
	 precio = parseFloat($$("precioProducto").value);
     datos[0] = new Array("<img src='css/images/borrar.gif' title='Eliminar' alt='borrar' onclick='eliminarFila(this)' />","center");
	 datos[1] = new Array($$("codidproducto").value,"center");
	 datos[2] = new Array($$("dato").value,"left");
	 datos[3] = new Array($$("cant").value,"center");
	 datos[4] = new Array(convertirFormatoNumber(precio.toFixed(2)),"center");
	 total = $$("cant").value * precio.toFixed(2);
     datos[5] = new Array(convertirFormatoNumber(total.toFixed(2)),"center");
	 insertarFila(datos,'detalleSolicitud');
	 setTotal(total,$$("moneda").value);
     $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
     $$("dato").value=""; 
     document.form1.dato.focus();
   }
}
 
 function setTotal(total,moneda){	 
 
	 if (moneda == "Bolivianos"){
	   total_bolivianos = total_bolivianos + total;
	 }
	 
	 if (moneda == "Dolares"){
		total = total * ($$("tipoCambioBs").value);
		total_bolivianos = total_bolivianos + total;
	 }
	 
     total_Dolares = total_bolivianos / ($$("tipoCambioBs").value);
	 $$("subtotalBS").value = convertirFormatoNumber(total_bolivianos.toFixed(2));
	 $$("subtotalDL").value = convertirFormatoNumber(total_Dolares.toFixed(2));
 }
 
  
 function limpiarDetalle(){
	  $$("detalleSolicitud").innerHTML = ""; 
	  setTotal(-total_bolivianos,"Bolivianos");  
 }
 
 function salir(){
   if ($$('detalleSolicitud').rows.length > 0){
	if (confirm("Cuenta con detalles de solicitud desea Salir ?"))
	location.href = irDireccion;  
   }
   else{
	 location.href = irDireccion;  
   }
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