// JavaScript Document
var totalTransaccion = 0;

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


 var closeVentanaPedido = function(){
	$$("modal2").style.visibility = "hidden";
	$$("modalInterior2").style.visibility = "hidden";  
	$$("mensaje").style.visibility = "hidden";	 
 }
 
 var openVentanaPedido = function(nombre,precio,idproducto){
    $$("modal2").style.visibility = "visible";
	$$("modalInterior2").style.visibility = "visible";  
	$$("cantidadproducto").value = "";
	$$("cantidadproducto").focus(); 
	$$("nombreproducto").value = nombre;
	$$("precioproducto").value = precio;
	$$("idcombinacion").value = idproducto;
 }
 
 var consultarProductos = function(cadena){	
	var filtro = "tipo=busqueda&texto="+cadena;
	consultar("Dventa.php",filtro,resultadoProductos);
 }
 
 var resultadoProductos = function(resultado){
	 $$("productos").innerHTML = resultado;
 }
 
 var validarSubVentana = function(){
	if (!isvalidoNumero("cantidadproducto")){
		$$("mensaje").innerHTML = "Numero Incorrecto";
		$$("mensaje").style.visibility = "visible";
		return false;
	}
	if ($$("cantidadproducto").value == ""){
		$$("mensaje").innerHTML = "Ingrese Numero";
		$$("mensaje").style.visibility = "visible";
	    return false;	
	}
	
	return true;
 }
 
 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }
 
 var insertarPedido = function(){
	if (validarSubVentana()){	 
	var filtro = "tipo=insertarVenta&cantidad="+$$("cantidadproducto").value+"&precio="+$$("precioproducto").value+
	"&idcombinacion="+$$("idcombinacion").value+"&idatencion="+$$("idatencion").value+"&nroatencion="+$$("idpedido").value+
	"&idpersona="+$$("idpersonarecibida").value+"&nombrepersona="+$$("texto").value+"&tipopersona="+$$("receptor").value+"&tiponota="+$$("tiponota").value;
	consultar("Dventa.php",filtro,resultadoPedido); 	
	}
 }
 
 var resultadoPedido = function(resultado){
   insertarNewItem('detallepedidoProductos',resultado);
 }
 
  var cambiarDependencias = function(){
	  $$("texto").value = "";
	  $$("idpersonarecibida").value = "0";
  } 
  
  var tipoBusqueda = function(e){
	   tipocliente = $$('receptor').options[$$('receptor').selectedIndex].value;
	   if (tipocliente!="otros"){ 
		idconsulta = "id"+tipocliente;   
		switch(tipocliente){
		   case 'trabajador':
		    sql = "select t.idtrabajador,left(concat(nombre,' ',apellido),20)as nombre from trabajador t where estado=1 and ";
		   break;	
		   case 'cliente':
		    sql = "select idcliente,nombre from cliente where estado=1 and ";
		   break;
		   case 'proveedor':
		    sql = "select idproveedor,nombre from proveedor where estado=1 and ";
		   break;
		}		
	    eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultado','../autocompletar/consultor.php',sql,'');
	   }
   }
   
   var eventoResultado = function(resultado,codigo){
	  $$("texto").value= resultado;
	  $$("idpersonarecibida").value = codigo;
   }
 
 
 var insertarNewItem = function(tabladestino,iddetalle){
	var formato = getFormatoColumna();
	var nelementos =  $$(tabladestino).rows.length + 1;
	var cantidad = $$("cantidadproducto").value;
	var precio = $$("precioproducto").value;
	var total = precio * cantidad;
	var n = $$("idpedido").value;
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n, type:"set"},
	{id :"nombreproducto", type:"get" },
	{id :"precioproducto", type:"get"},
	{id :"cantidadproducto", type:"get"},
	{data :total , type:"set"},
	{data :iddetalle , type:"set"}];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0]);
	closeVentanaPedido();
 }
 
 
 var getReporte1 = function(){
	window.open('reporte1.php?idatencion='+$$("idatencion").value+'&nroatencion='+$$("idpedido").value,'target:_blank');	
 }
 
 
 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;
	cargarTotales(-desconvertirFormatoNumber(tr.cells[5].innerHTML));	
    table.removeChild(tr);	
    var filtro="tipo=eliminarDetalle&iddetalle="+tr.cells[6].innerHTML;
    consultar("Dventa.php",filtro,null); 
	orderNumeroItem();
 }
 
  var getCambio = function(){
	var total = ($$("totalgeneral").value == "") ? 0 : parseFloat($$("totalgeneral").value); 
	var cortesia = ($$("cortesia").value == "") ? 0 : parseFloat($$("cortesia").value);	
	var bolivianos = ($$("efectivobs").value == "") ? 0 : parseFloat($$("efectivobs").value);
	var dolares = ($$("efectivods").value == "") ? 0 : parseFloat($$("efectivods").value); 	
	dolares = parseFloat(dolares) * parseFloat($$("tipocambio").value);
	var cambio = parseFloat(cortesia + bolivianos + dolares) - parseFloat(totalTransaccion);
	$$("cambio").value = cambio.toFixed(2);
 }
 
 var insertarCobranza = function(){
	var filtro = "tipo=actualizarAtencion&estado=cerrado&idatencion="+$$("idatencion").value;
	consultar("Dventa.php",filtro,resultadoCobranza); 	 
 }
 
 var resultadoCobranza = function(){
	 window.open('reporte2.php?idatencion='+$$("idatencion").value,'target:_blank'); 
	location.href = 'nuevo_ventas.php'; 
 }
 
 
 var orderNumeroItem = function(){
	var n =  $$("detallepedidoProductos").rows.length;
	for (var i=0;i<n ;i++){
		$$("detallepedidoProductos").rows[i].cells[1].innerHTML = i+1;
	}
 }
 

  var cargarTotales = function(total){
   	totalTransaccion = parseFloat(totalTransaccion) + parseFloat(total);
	$$("totalNota").value = convertirFormatoNumber(parseFloat(totalTransaccion).toFixed(2));
	$$("producto").focus();	
 }
 
  var getFormatoColumna = function(){
	var col1;
	var col2;  	
	col1 = {type : 'normal', numerico : 'si', aling : 'center'};
	col2 = {type : 'normal', numerico : 'si', aling : 'center'};  	  
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no'},col1,col2,	
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'normal', numerico : 'no', aling : 'center' , display : 'none'}
	];
	return formato;	
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


function event_ventaproducto(evento){
    var tecla = (document.all) ? evento.keyCode : evento.which;		
    if (tecla == 13)
       insertarPedido();
}
 