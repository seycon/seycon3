// JavaScript Document
var totalTransaccion = 0 ;
var itemPedido = null;

 var $$ = function(id){
   return document.getElementById(id);
 }
 
 var openVentanaClave = function(){
   	$$("cortesia").value = "0";
	$$("efectivobs").value = "0";
    $$("cambio").value = "0";
	$$("modal1").style.visibility = "visible";
	$$("modalInterior1").style.visibility = "visible";  
	$$("totalgeneral").value = convertirFormatoNumber(parseFloat(totalTransaccion).toFixed(2));
	$$("dato").value = "Varios";
	$$("dato").focus();
 }
 
 var limpiarIngreso = function(){
   	$$("cortesia").value = "0";
	$$("efectivobs").value = "0";
	$$("cambio").value = "0";  
 }
 
 
 var tipoBusqueda = function(e){
	 var sql;
     sql = "select t.idtrabajador,left(concat_WS(nombre,' ',apellido),20)as nombre from trabajador t where ";
	 eventoTeclas(e,"clienteasignado",'cliente','trabajador','nombre','idtrabajador','eventoResultadoEgreso','../autocompletar/consultor.php',sql,'');
 }
 
  var eventoResultadoEgreso = function(resultado,codigo){
	  $$("clienteasignado").value= resultado;
	  $$("idpersonarecibida").value = codigo;	  	   
 } 
 
 
 var getCambio = function(){
	var total = ($$("totalgeneral").value == "") ? 0 : parseFloat(desconvertirFormatoNumber($$("totalgeneral").value)); 
	var cortesia = ($$("cortesia").value == "") ? 0 : parseFloat($$("cortesia").value);	
	var efectivo = ($$("efectivobs").value == "") ? 0 : parseFloat($$("efectivobs").value);
	var descuento = ($$("descuento").value == "") ? 0 : parseFloat(desconvertirFormatoNumber($$("descuento").value)); 	
	var cambio = parseFloat(efectivo) - parseFloat(total - descuento - cortesia) ;
	$$("cambio").value = cambio.toFixed(2);
 }
 
  var autocompletar = function(e,id){  
	 var sql;
     sql = "select t.idtrabajador,left(concat_WS(nombre,' ',apellido),20)as nombre from trabajador t where ";
	 eventoTeclas(e,id,'resultados','trabajador','nombre','idtrabajador','eventoResultado','../autocompletar/consultor.php',sql,'');
  }
 
  var eventoResultado = function(resultado,codigo){	     
	 var filtro = "idtrabajador="+codigo+"&tipo=trabajador&idatencion="+$$("idatencion").value;
	 enviar("Dpedido.php",filtro,resultadoTrabajador); 
     $$("dato").value =resultado;
	 $$("codidproducto").value = codigo; 	 
 }
 
 var resultadoTrabajador = function(resultado){
	 var dato = resultado.split("---");	 
	 if (dato[0] == "1"){
		$$("cksocio").checked = true; 
	 }
	 $$("descuento").value =  convertirFormatoNumber(parseFloat(dato[1]).toFixed(2));
 	 $$("efectivobs").value = 0;
	 $$("cortesia").value = 0;
 }
 
 
 var closeVentanaClave = function(){
	$$("modal1").style.visibility = "hidden";
	$$("modalInterior1").style.visibility = "hidden";  
 }
 
 var closeVentanaPedido = function(){
	$$("modal2").style.visibility = "hidden";
	$$("modalInterior2").style.visibility = "hidden";  
	$$("msj_Subventana").style.visibility = "hidden";	 
 }
 
 var openVentanaPedido = function(nombre,precio,idproducto){
	$$("nombreproducto").value = nombre;
	$$("precioproducto").value = precio;
	$$("idcombinacion").value = idproducto;
	var filtro = "tipo=combinacion&idcombinacion="+idproducto;
	enviar("Dpedido.php",filtro,resultadoCantCombinacion); 
 }
 
 
 var resultadoCantCombinacion = function(resultado){
	$$("cdisponible").value = resultado; 	
	$$("modal2").style.visibility = "visible";
	$$("modalInterior2").style.visibility = "visible"; 
	$$("msj_Subventana").style.visibility = "hidden"; 
	$$("cantidadproducto").value = "";
	$$("cantidadproducto").focus();  
 }
 
 var insertarCobranza = function(){
	var filtro = "tipo=actualizarAtencion&idatencion="+$$("idatencion").value+"&trabajador="+$$("codidproducto").value+
	"&credito="+$$("credito").checked+"&socio="+$$("cksocio").checked+"&efectivo="+$$("efectivobs").value+"&cortesia="+$$("cortesia").value
	+"&descuento="+$$("descuento").value;
	enviar("Dpedido.php",filtro,resultadoCobranza); 	 
 }
 
 var resultadoCobranza = function(resultado){
   window.open('reporte2.php?idatencion='+$$("idatencion").value,'target:_blank');	 
   jumpIngreso();
 }
 
 var jumpIngreso = function(){	 
	location.href = "nuevo_atencion.php"; 
 }
 
 var consultarProductos = function(cadena){	
   if(cadena != ""){ 
	var filtro = "tipo=busqueda&texto="+cadena;
	enviar("Dpedido.php",filtro,resultadoProductos); 
   }
 }
 
 var resultadoProductos = function(resultado){
	 $$("productos").innerHTML = resultado;
 }
 
 function ajax() {
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
 
 
 var insertarPedido = function(){
	 if (validaSubVentana()){
	var filtro = "tipo=insertarPedido&cantidad="+$$("cantidadproducto").value+"&precio="+$$("precioproducto").value+
	"&idcombinacion="+$$("idcombinacion").value+"&idatencion="+$$("idatencion").value+"&nroatencion="+$$("idnroatencion").value;
	enviar("Dpedido.php",filtro,resultadoPedido); 	 
	 }
 }
 
 var validaSubVentana = function(){
	if (!isvalidoNumero("cantidadproducto")){
	   $$("msj_Subventana").innerHTML = "Numero Inválido.";
	   $$("msj_Subventana").style.visibility = "visible";
	   return false;		
	} 
	 
	if (parseFloat($$("cantidadproducto").value) > parseFloat($$("cdisponible").value)){
		$$("msj_Subventana").innerHTML = "Combinación Insuficiente.";
		$$("msj_Subventana").style.visibility = "visible";		
		return false;
	}
	
	if ($$("cantidadproducto").value <= 0){
		$$("msj_Subventana").innerHTML = "Cantidad Invalida.";
		$$("msj_Subventana").style.visibility = "visible";		
		return false;
	}
	return true;
 }
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
  }
 
 var resultadoPedido = function(resultado){
   insertarNewItem('detallepedidoProductos',resultado);
 }
 
 
 var insertarNewItem = function(tabladestino,iddetalle){
	var formato = getFormatoColumna();
	var nelementos =  $$(tabladestino).rows.length + 1;
	var cantidad = $$("cantidadproducto").value;
	var precio = $$("precioproducto").value;
	var total = precio * cantidad;
	n = $$("idnroatencion").value;
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{id :"nombreproducto", type:"get" },
	{id :"cantidadproducto", type:"get"},
	{id :"precioproducto", type:"get"},
	{data :total , type:"set"},
	{data :iddetalle , type:"set"}];
	var total = cargarDatos(formato,datosIngreso,tabladestino,'celdacuaderno',nelementos-1);
	if (total.length > 0)
	cargarTotales(total[0]);
	closeVentanaPedido();
 }
 
 
 var eliminarFila = function(t){
	itemPedido = t;
    $$("modal_tituloCabecera").innerHTML = 'Advertencia';
    $$("modal_contenido").innerHTML = '¿Desea anular este Pedido?';
    $$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";
 }
 
 var setPedidoAnulado = function() {
	var td = itemPedido.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;
	cargarTotales(-desconvertirFormatoNumber(tr.cells[5].innerHTML));	
    table.removeChild(tr);	
    var filtro="tipo=eliminarPedido&iddetalle="+tr.cells[6].innerHTML;
    enviar("Dpedido.php",filtro,null); 	 
	closeMensaje();
 }
 

  var cargarTotales = function(total){
   	totalTransaccion = parseFloat(totalTransaccion) + parseFloat(total);
	$$("totalPedido").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion).toFixed(2));
	$$("producto").focus();	
 }
 
  var getFormatoColumna = function(){
	var col1;
	var col2;  
	if ($$("idpantallapu").value == 0)  
	  col1 = {type : 'normal', numerico : 'si', aling : 'center' , display : 'none'};
	else
	  col1 = {type : 'normal', numerico : 'si', aling : 'center'};
	if ($$("idpantallapt").value == 0)  
	  col2 = {type : 'normal', numerico : 'si', aling : 'center' , display : 'none'};
	else
	  col2 = {type : 'normal', numerico : 'si', aling : 'center'};  
	  
	  
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no'},
	col1,col2,	
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center' , display : 'none'}	
	];
	return formato;	
 }

 var getReporte1 = function(){
	window.open('reporte1.php?idatencion='+$$("idatencion").value+'&nroatencion='+$$("idnroatencion").value,'target:_blank');	
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
 
var closeMensaje = function(){
	$$("modal_mensajes").style.visibility = "hidden";
	$$("overlay").style.visibility = "hidden";    
} 