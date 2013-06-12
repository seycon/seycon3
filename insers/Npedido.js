// JavaScript Document
var totalTransaccion = 0 ;
var itemPedido = null;
var trGeneral = null;
 var $$ = function(id){
   return document.getElementById(id);
 }
 
 var openVentanaClave = function(){
   	$$("cortesia").value = "0";
	$$("efectivobs").value = "0";
	$$("descuento").value = "0";
	$$("efectivobs").disabled = "";
	$$("cortesia").disabled = "";
	$$("cksocio").checked = false; 
	$$("credito").checked = false; 
    $$("cambio").value = "0";
	$$("modal1").style.visibility = "visible";
	$$("modalInterior1").style.visibility = "visible";  
	$$("totalgeneral").value = convertirFormatoNumber(parseFloat(totalTransaccion).toFixed(2));
	$$("dato").value = "Varios";
	$$("dato").disabled = "disabled";
	calcularTotalBs();
	$$("cortesia").focus();
 }
 
 var calcularTotalBs = function() {
	 $$("efectivobs").value = desconvertirFormatoNumber($$("totalgeneral").value) - 
	 desconvertirFormatoNumber($$("descuento").value) - desconvertirFormatoNumber($$("cortesia").value);	 
 }
 
 
 var limpiarIngreso = function(){
   	$$("cortesia").value = "0";
	$$("efectivobs").value = "0";
	$$("cambio").value = "0";  
	$$("dato").value = "";
	$$("codidproducto").value = "";
	$$("tipotrabajador").value = "";
	$$("descuento").value = 0;
	if ($$("credito").checked == true) {
	    $$("efectivobs").disabled = "disabled";
		$$("cortesia").disabled = "disabled";	
		$$("dato").disabled = "";
		$$("dato").focus();
	} else {
		$$("efectivobs").disabled = "";
		$$("cortesia").disabled = "";	
		$$("dato").value = "Varios";
		$$("dato").disabled = "disabled";
		$$("cortesia").focus();
	}
	
 }
 
 
 var tipoBusqueda = function(e){
	 var sql;
	 sql = "select t.idtrabajador,left(concat_WS(' ',nombre,apellido),20)as nombre from trabajador t where t.estado=1 and ";	 
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
	 if ($$("credito").checked) {	 
     sql = "(select t.idtrabajador,left(concat_WS(' ','F -',nombre,apellido),20)as nombre from trabajador t where t.estado=1 and "
	 + "nombre like '"+$$("dato").value+"%' ) union all "
	 + "( select idpersonalapoyo,left(concat_WS(' ','A -',nombre,apellido),20)as nombre from "
     + " personalapoyo where estado=1 and nombre like '"+$$("dato").value+"%' )  order by nombre limit 9";
	  
	 eventoTeclas(e,id,'resultados','trabajador','nombre','idtrabajador','eventoResultado','../autocompletar/consultor.php',sql
	 ,'<sinfiltro>', 'autoL1');
	 }
  }
 
 
  var eventoResultado = function(resultado,codigo){	
     var resultado = resultado.split("-");
     if (resultado[0] == "F ") {
		$$("tipotrabajador").value = "fijo"; 
	 } else {
		$$("tipotrabajador").value = "apoyo";
	 }
	   
	 var filtro = "idtrabajador="+codigo+"&tipo=trabajador&idatencion="+$$("idatencion").value
	 + "&tipotrabajador="+ $$("tipotrabajador").value;
	 enviar("Dpedido.php",filtro,resultadoTrabajador); 
     $$("dato").value =resultado[1];
	 $$("codidproducto").value = codigo; 	 
 }
 
 var resultadoTrabajador = function(resultado){
	 var dato = resultado.split("---");	 
	 if (dato[0] == "1"){
		$$("cksocio").checked = true; 
		$$("efectivobs").disabled = "disabled";
		$$("cortesia").disabled = "disabled";
		$$("descuento").value = 0;
		$$("efectivobs").value = 0;
	 } else {
		$$("cksocio").checked = false; 
		if (!$$("credito").checked) {
		    $$("efectivobs").disabled = "";
		    $$("cortesia").disabled = "";
		}
		$$("descuento").value =  convertirFormatoNumber(parseFloat(dato[1]).toFixed(2));
	    var efectivo = (parseFloat($$("totalgeneral").value).toFixed(2)) - (parseFloat(dato[1]).toFixed(2));
	   	$$("efectivobs").value = efectivo.toFixed(2); 
	 } 
 	
	 $$("cortesia").value = 0;
	 $$("cambio").value = 0;
 }
 
 
 var closeVentanaClave = function(){
	$$("modal1").style.visibility = "hidden";
	$$("modalInterior1").style.visibility = "hidden"; 
	$$("mensajeE1").style.display = "none";
	$$("mensajeE2").style.display = "none";
	$$("mensajeE3").style.display = "none"; 
	$$("mensajeE4").style.display = "none";
	$$("msjfecha").style.display = "none";
 }
 
 var closeVentanaPedido = function(){
	$$("modal2").style.visibility = "hidden";
	$$("modalInterior2").style.visibility = "hidden";  
	$$("msj_Subventana").style.visibility = "hidden";	 
 }
 
 var openVentanaPedido = function(nombre,precio,idproducto, tipocombinacion){
	$$("nombreproducto").value = nombre;
	$$("precioproducto").value = precio;
	$$("idcombinacion").value = idproducto;
	$$("tipocombinacion").value = tipocombinacion;
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";
	  	 
	var filtro = "tipo=combinacion&idcombinacion="+idproducto;
	enviar("Dpedido.php",filtro,resultadoCantCombinacion); 
 }
 
 
 var resultadoCantCombinacion = function(resultado){
	$$("cdisponible").value = resultado; 	
	$$("modal2").style.visibility = "visible";
	$$("modalInterior2").style.visibility = "visible"; 
	$$("msj_Subventana").style.visibility = "hidden"; 
    $$('overlay').style.visibility = "hidden";
    $$('gif').style.visibility = "hidden";
	$$("cantidadproducto").value = "";
	$$("cantidadproducto").focus();  
 }
 
 
 
 
 var validarCobranza = function() {
	var valido = true; 
	if (!isvalidoNumero("efectivobs")){
	   $$("mensajeE1").innerHTML = "Numero Inválido.";
	   $$("mensajeE1").style.display = "block";
	   valido = false;		
	} 
	
	if (!validarFecha($$("fechaatencion").value)) {
		$$("msjfecha").innerHTML = "Fecha Invalida.";
	    $$("msjfecha").style.display = "block";
		valido = false;
	}
	
	if (!isvalidoNumero("cortesia")){
	   $$("mensajeE2").innerHTML = "Numero Inválido.";
	   $$("mensajeE2").style.display = "block";
	   valido = false;		
	}
	if ( ($$("codidproducto").value == "" && $$("credito").checked) || ($$("dato").value == "" && $$("credito").checked) ){
	   $$("mensajeE4").innerHTML = "Trabajador Inválido.";
	   $$("mensajeE4").style.display = "block";
	   $$("codidproducto").value = "";
	   valido = false;
	}
	var cortesia = parseFloat(desconvertirFormatoNumber($$("cortesia").value));
	if (( cortesia > 0) && (cortesia < parseFloat(desconvertirFormatoNumber($$("totalgeneral").value)))) {
	   $$("efectivobs").value = 0;
	   $$("mensajeE3").innerHTML = "Monto Insuficiente.";
	   $$("mensajeE3").style.display = "block";
	   valido = false;  	
	}
	
	
	if ($$("cksocio").checked == false && $$("credito").checked == false) {
	 var subTotal = parseFloat(desconvertirFormatoNumber($$("descuento").value)) 
	 	 + parseFloat(desconvertirFormatoNumber($$("efectivobs").value)) 
		 + parseFloat(desconvertirFormatoNumber($$("cortesia").value));	
		
	   if ( subTotal < parseFloat(desconvertirFormatoNumber($$("totalgeneral").value))) {
		$$("mensajeE3").innerHTML = "Monto Insuficiente.";
	    $$("mensajeE3").style.display = "block";
	    valido = false;  
	   }
	}
	
	return valido;
 }
 
 
 function validarFecha(value){  
    var Fecha= new String(value);   
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length));  
    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")));
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")));  
  
    if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
        return false;  
    }  
 
    if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
        return false;  
    }  

    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
        return false  ;
    }  
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
        if (Mes==2 && Dia > 28 || Dia>30) {  
            return false;  
        }  
    }      

  return true;   
}
 
 
 var desconvertirFormatoNumber = function(cadena){
	convertir = "";     
	for(i = 0;i < cadena.length;i++){		
		if (cadena[i] != ",")
		convertir = convertir + cadena[i];
	}
	return convertir;
 } 
 
 var insertarCobranza = function(){
    if (validarCobranza()) { 
	    if ($$("tipotrabajador").value == "apoyo") {
			$$("credito").checked = false;
			$$("codidproducto").value = "";
		}
	
		var filtro = "tipo=actualizarAtencion&idatencion="+$$("idatencion").value+"&trabajador="+$$("codidproducto").value+
		"&credito="+$$("credito").checked+"&socio="+$$("cksocio").checked+"&efectivo="+$$("efectivobs").value+"&cortesia="
		+desconvertirFormatoNumber($$("cortesia").value)
		+"&descuento="+desconvertirFormatoNumber($$("descuento").value)+"&fecha="+$$("fechaatencion").value;
		enviar("Dpedido.php",filtro,resultadoCobranza); 	 
	}
 }
 
 var resultadoCobranza = function(resultado){
   window.open('reporte2.php?idatencion='+$$("idatencion").value,'target:_blank');	 
   jumpIngreso();
 }
 
 var jumpIngreso = function(){	 
	location.href = "nuevo_atencion.php"; 
 }
 
 var consultarProductos = function(cadena){	
   if(cadena != "") { 
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
 
 

 
 var eventoIngresoCantidad = function(evento){
	var tecla = (document.all) ? evento.keyCode : evento.which;		
	if (tecla == 13) {
	    insertarPedido();	
	}
}
 
 var validaSubVentana = function(){
	if (!isvalidoNumero("cantidadproducto")){
	   $$("msj_Subventana").innerHTML = "Numero Inválido.";
	   $$("msj_Subventana").style.visibility = "visible";
	   return false;		
	} 
	 
	if (parseFloat($$("cantidadproducto").value) > parseFloat($$("cdisponible").value) && 
	$$("tipocombinacion").value != "Sin Descuento en Producto"){
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
 
 function soloNumeros(evt){ 
     var tecla = (document.all) ? evt.keyCode : evt.which;
     return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
 }
 
 var insertarPedido = function(){
	 if (validaSubVentana()){
	  closeVentanaPedido();
	  $$('overlay').style.visibility = "visible";
      $$('gif').style.visibility = "visible";	  	 
	  var filtro = "tipo=insertarPedido&cantidad="+$$("cantidadproducto").value+"&precio="+$$("precioproducto").value+
	  "&idcombinacion="+$$("idcombinacion").value+"&idatencion="+$$("idatencion").value+"&nroatencion="+$$("idnroatencion").value;
	  enviar("Dpedido.php",filtro,resultadoPedido); 	 
	 }
 } 
 
 var resultadoPedido = function(resultado){
	var codigo = parseFloat(resultado); 
   insertarNewItem('detallepedidoProductos',resultado);
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
	{type : 'normal', numerico : 'no', aling : 'center' , display : 'none'}	
	];
	return formato;	
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
	$$('overlay').style.visibility = "hidden";
    $$('gif').style.visibility = "hidden";
 }
 
 
 var eliminarFila = function(t){
	itemPedido = t;
    $$("modal_tituloCabecera").innerHTML = 'Advertencia';
    $$("modal_contenido").innerHTML = '¿Desea anular este Pedido?';
    $$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";
 }
 
 var setPedidoAnulado = function() {
   $$("modal_mensajes").style.visibility = "hidden";
   $$('gif').style.visibility = "visible";
	var td = itemPedido.parentNode;
    var tr = td.parentNode;
	trGeneral = tr;    
    var filtro="tipo=eliminarPedido&iddetalle="+desconvertirFormatoNumber(tr.cells[6].innerHTML);
    enviar("Dpedido.php", filtro, resultadoAnulacion); 	 	
 }
 
 var resultadoAnulacion = function() {
	var table = trGeneral.parentNode;
	cargarTotales(-desconvertirFormatoNumber(trGeneral.cells[5].innerHTML));	
    table.removeChild(trGeneral);	 
	closeMensaje();
	$$('gif').style.visibility = "hidden";
 }


  var cargarTotales = function(total){
   	totalTransaccion = parseFloat(totalTransaccion) + parseFloat(total);
	$$("totalPedido").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion).toFixed(2));
	$$("producto").focus();	
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