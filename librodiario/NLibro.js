// JavaScript Document
var irDireccion = "listar_librodiario.php#t3";
var transaccion = 'insertar';
var servidorT = "librodiario/DLibro.php";
var totalTransaccion = { bolivianos: 0,dolares: 0};
var codigoTransaccion = 0;
var dirLocal = "nuevo_librodiario.php";



var $$ = function(id){
	return document.getElementById(id);
}

document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2	  
     if($$('overlay').style.visibility != "visible")
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

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
	cargarTotales(-desconvertirFormatoNumber(tr.cells[5].innerHTML),-desconvertirFormatoNumber(tr.cells[6].innerHTML));	
    table.removeChild(tr);
	orderNumeroItem();
}


var orderNumeroItem = function(){
	var n =  $$("detalleTransaccion").rows.length;
	for (var i=0;i<n ;i++){
		$$("detalleTransaccion").rows[i].cells[1].innerHTML = i+1;
	}
}

var eventoResultadoEgreso = function(resultado,codigo){
	  $$("texto").value= resultado;
	  $$("idpersonarecibida").value = codigo;	  	   
}
   
var cambiarDependencias = function(){
	  $$("texto").value = "";
	  $$("idpersonarecibida").value = "";
}



var insertarNewItem = function(tabladestino){
  $$("debeD").value = ($$("debeD").value == "") ? 0 : $$("debeD").value;
  $$("haberD").value = ($$("haberD").value == "") ? 0 : $$("haberD").value;
  $$("documentoD").value = ($$("documentoD").value == "") ? 0 : $$("documentoD").value;	
  if(validarSubIngreso()){	
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("cuentaD").selectedIndex; 
    var texto = $$("cuentaD").options[indice].text;
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{id :"cuentaD", type:"get" },
	{data :texto  , type:"set"},
	{id :"descripcionD", type:"get"},
	{id :"debeD", type:"get"},
	{id :"haberD", type:"get"},
	{id :"documentoD", type:"get"}];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
 	 cargarTotales(total[0],total[1]);
  }
}

var cargarTotales = function(bolivianos,dolares){
   	totalTransaccion.bolivianos = parseFloat(totalTransaccion.bolivianos) + parseFloat(bolivianos);
	totalTransaccion.dolares = parseFloat(totalTransaccion.dolares) + parseFloat(dolares);
	$$("totaldebe").value = convertirFormatoNumber(parseFloat(totalTransaccion.bolivianos).toFixed(2));
	$$("totalhaber").value = convertirFormatoNumber(parseFloat(totalTransaccion.dolares).toFixed(2));
	$$("descripcionD").focus();	
}


var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left', display:'none' },
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'}		
	];
	return formato;	
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
    
  if ($$("sucursal").value == ""){
    openMensaje("Advertencia","Debe Seleccionar la Sucursal");  
    return false;
  }
  
  if ($$("detalleTransaccion").rows.length < 1){
    openMensaje("Advertencia","Debe Ingresar Detalle del libro Diario");  
    return false;
  }
  
  if (totalTransaccion.bolivianos  != totalTransaccion.dolares){
	openMensaje("Advertencia","Los montos del Debe y  Haber deben ser iguales.");  
    return false; 
  }
  
  
  return true;
}


var validarSubIngreso = function(){
	if (!isvalidoNumero("debeD")){
	  openMensaje("Advertencia","El monto en el debe ingresado es incorrecto.");
	  return false;
	}
	if (!isvalidoNumero("haberD")){
	  openMensaje("Advertencia","El monto en el haber ingresado es incorrecto.");
	  return false;
	}

	return true; 
}

 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }


function ejecutarTransaccion(){	
    nfilas = $$('detalleTransaccion').rows.length;    	
    json = new Array();
	if (datosValidos()){
	  $$('overlay').style.visibility = "visible";
      $$('gif').style.visibility = "visible";	 
	   for(i=0; i<nfilas; i++) {
		 vector = [
		  $$('detalleTransaccion').rows[i].cells[2].innerHTML,								
		  $$('detalleTransaccion').rows[i].cells[4].innerHTML,	
		  $$('detalleTransaccion').rows[i].cells[5].innerHTML,	
		  $$('detalleTransaccion').rows[i].cells[6].innerHTML,
		  $$('detalleTransaccion').rows[i].cells[7].innerHTML];		   	
		 json[i] = vector;	 		
	}
    dato = JSON.stringify(json); 
  	var filtro = "transaccion="+transaccion+"&moneda="+$$("moneda").value+"&tipotransaccion="+$$("tipo").value+
	"&glosa="+$$U("glosa")+"&sucursal="+$$("sucursal").value
	+"&fecha="+$$("fecha").value+'&detalle='+encodeURIComponent(dato)+"&idlibro="+$$("idTransaccion").value+
	"&ingresodebe="+totalTransaccion.bolivianos+"&ingresoHaber="+totalTransaccion.dolares+
	"&tipocambio="+$$("tipocambio").value;
	enviar(filtro,respuestaEjecutarT);
	 }
}

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

function respuestaEjecutarT(respuesta){
	codigoTransaccion = respuesta;
    $$('overlay').style.visibility = "visible";
    $$('modal_vendido').style.visibility = "visible";
	$$('gif').style.visibility = "hidden";      	
}

 function cerrarPagina(){
	window.location = dirLocal;	 
 }

function accionPostRegistro(){
   window.open('librodiario/imprimir_libro.php?idlibrodiario='+codigoTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
   cerrarPagina();
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

