// JavaScript Document

var totalTransaccion = 0;
var preciosFactory = Array();

var $$ = function(id){
    return document.getElementById(id);	 
}

var ajax = function(){
    return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

function consultar(servidor,filtro,funcion){
  var  pedido = ajax();	
  pedido.open("GET",servidor+"?"+filtro,true);
  pedido.onreadystatechange = function(){
	  if (pedido.readyState == 4){     	
		  var resultado = pedido.responseText; 
		  if (funcion != null)
			  funcion(resultado); 
	  }	   
  }
  pedido.send(null);
}



var ejecutarTransaccion = function(){
   var nfilas = $$('detallePedido').rows.length;    	
   var json = new Array();   
     for(i=0; i<nfilas; i++) {
	   vector = [
	    $$('detallePedido').rows[i].cells[5].innerHTML,	
	    $$('detallePedido').rows[i].cells[6].innerHTML];		   	
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);	
    var filtro = "estado="+$$("estado").value+"&glosa="+$$("glosa").value+"&detalle="+dato+"&idalmacen="+$$("almacen").value+
    "&transaccion="+$$("transaccion").value+"&idtransaccion="+$$("idtransaccion").value;	  
    consultar("Dsolicitud.php",filtro,resultadoTransaccion);  	
}

var resultadoTransaccion = function(){
  location.href = "nuevo_solicitud.php";	
}


var consultarProductos = function(idalmacen){
  var filtro;	
    $$("detallePedido").innerHTML = "";
	cargarTotales(-totalTransaccion);
	if (idalmacen != ""){
	filtro = "idalmacen="+idalmacen+"&transaccion=productos";
	consultar("Dsolicitud.php",filtro,resultadoListaProductos);  
	}
}

var resultadoListaProductos = function(resultado){
	$$("producto").innerHTML = resultado;
}


var consultarDatosProductos = function(idproducto){
  var filtro;	
	if (idproducto != ""){
	filtro = "idproducto="+idproducto+"&idalmacen="+$$("almacen").value+"&transaccion=datosproducto";
	consultar("Dsolicitud.php",filtro,resultadoDatosProductos);  
	}
}

var resultadoDatosProductos = function(resultado){
	var datos = resultado.split("---");
	$$("disponible").value = datos[0];
	$$("stock").value = datos[1];
	$$("pedido").focus();
}


var insertarNewItem = function(tabladestino){
  if (validarSubIngreso()){	
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("producto").selectedIndex;
	var nombre = $$("producto").options[indice].text;
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{data :nombre , type:"set"},
	{id :"disponible" , type:"get"},
	{id :"stock", type:"get"},
	{id :"pedido", type:"get"},
	{id :"producto", type:"get" }
    ];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0]);
  }
}


var validarSubIngreso = function(){
	if (!isvalidoNumero("pedido")){
	  $$("mensaje").innerHTML = "Invalido.";
	  $$("mensaje").style.visibility = "visible";
	  return false;
	}
	$$("mensaje").style.visibility = "hidden";
	return true; 
  }

  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
  }


var cargarTotales = function(total){
	totalTransaccion = parseFloat(totalTransaccion) + parseFloat(total);
	$$('total').value = convertirFormatoNumber(parseFloat(totalTransaccion).toFixed(2));
	$$("pedido").focus();	
}

var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center' , display: 'none'}		
	];
	return formato;	
}


var eliminarFila = function(t){
  var td = t.parentNode;
  var tr = td.parentNode;
  var table = tr.parentNode;	
  cargarTotales(-desconvertirFormatoNumber(tr.cells[5].innerHTML));	
  table.removeChild(tr);
  orderNumeroItem();
}

var orderNumeroItem = function(){
	var n =  $$("detallePedido").rows.length;
	for (var i=0;i<n ;i++){
		$$("detallePedido").rows[i].cells[1].innerHTML = i+1;
	}
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


function eventoText(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
   if (tecla == 13){
    insertarNewItem('detallePedido');
   }
 }