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
	    $$('detallePedido').rows[i].cells[3].innerHTML,								
	    $$('detallePedido').rows[i].cells[4].innerHTML,	
	    $$('detallePedido').rows[i].cells[5].innerHTML,	
	    $$('detallePedido').rows[i].cells[7].innerHTML];		   	
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);		
	
  var filtro = "nombre="+$$("nombre").value+"&dia="+$$("dia").value+"&hora="+$$("hora").value+"&telefono="+$$("telefono").value
  +"&total="+$$("total").value+"&acuenta="+$$("acuenta").value+"&estado="+$$("estado").value+"&idtransaccion="+$$("idtransaccion").value
  +"&transaccion="+$$("transaccion").value+"&pirotines="+$$("pirotines").value+"&masa="+$$("masa").value+"&crema="+$$("crema").value+
  "&relleno="+$$("relleno").value+"&glosa="+$$("detalle").value+"&detalle="+dato;	  
  consultar("Dpedido.php",filtro,resultadoTransaccion);  	
}

var resultadoTransaccion = function(){
  location.href = "nuevo_pedidoespecial.php";	
}

var getPrecio = function(){
  var cantidad = $$("cantidad").value;
  var nro = $$("producto").value;
  var tipo = $$("tipo").value;
  if (cantidad<=39)	{
	return preciosFactory[nro]['<39'][tipo];  
  }else{
	return preciosFactory[nro]['>39'][tipo];  
  }  
}


var insertarNewItem = function(tabladestino){
  if (validarSubIngreso()){	
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("producto").selectedIndex;
	var nombre = $$("producto").options[indice].text;
	var tipo = $$("tipo").value;
	var precio = getPrecio();
	var total = precio * $$("cantidad").value;
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{data :nombre , type:"set"},
	{data :tipo , type:"set"},
	{id :"cantidad", type:"get"},
	{data : precio, type:"set"},
	{data : total, type:"set"},
	{id :"producto", type:"get" }
    ];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0]);
  }
}


var validarSubIngreso = function(){
	if (!isvalidoNumero("cantidad")){
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
	$$("cantidad").focus();	
	setSaldo();
}

var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'si', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center' , display: 'none'}		
	];
	return formato;	
}


var eliminarFila = function(t){
  var td = t.parentNode;
  var tr = td.parentNode;
  var table = tr.parentNode;	
  cargarTotales(-desconvertirFormatoNumber(tr.cells[6].innerHTML));	
  table.removeChild(tr);
  orderNumeroItem();
}

var orderNumeroItem = function(){
	var n =  $$("detallePedido").rows.length;
	for (var i=0;i<n ;i++){
		$$("detallePedido").rows[i].cells[1].innerHTML = i+1;
	}
}

var setSaldo = function(){
  var acuenta;	
  var total;
  if (!isvalidoNumero("acuenta") || $$("acuenta").value == ""){
    acuenta = 0;
  }else{
    acuenta = $$("acuenta").value;	
  }
  total = ($$("total").value == "") ? 0 : desconvertirFormatoNumber($$("total").value);	
  var saldo = parseFloat(total) - parseFloat(acuenta);
  $$("saldo").value = convertirFormatoNumber(parseFloat(saldo).toFixed(2));
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