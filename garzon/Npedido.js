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
		openVentanaClave();
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
	$$("producto").value = "";
	$$("producto").focus();  
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
	
	if ($$("impresionFV").value == 1 && !validarFecha($$("fechaatencion").value)) {
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
	    $$('overlay').style.visibility = "visible";
        $$('gif').style.visibility = "visible"; 
		
	    if ($$("tipotrabajador").value == "apoyo") {
			$$("credito").checked = false;		
		}
		
		var fecha = "";
		if ($$("impresionFV").value == 1) {
			fecha = "&fecha="+$$("fechaatencion").value;
		}
	
		var filtro = "tipo=actualizarAtencion&idatencion="+$$("idatencion").value+"&trabajador="+$$("codidproducto").value+
		"&credito="+$$("credito").checked+"&socio="+$$("cksocio").checked+"&efectivo="+$$("efectivobs").value+"&cortesia="
		+desconvertirFormatoNumber($$("cortesia").value)
		+"&descuento="+desconvertirFormatoNumber($$("descuento").value)+ fecha;
		enviar("Dpedido.php",filtro,resultadoCobranza); 	 
	}
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
 
 function enviar(servidor, filtro, funcion){ 
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
	$$("producto").value = "";
	
 }
 
 
 var eliminarFila = function(t){
	itemPedido = t;
    $$("modal_tituloCabecera").innerHTML = 'Advertencia';
    $$("modal_contenido").innerHTML = '¿Desea anular este Pedido?';
    $$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";
	$$("btAccion").style.visibility = "visible";
 }
 
 var setPedidoAnulado = function() {
   $$("modal_mensajes").style.visibility = "hidden";
   $$('gif').style.visibility = "visible";
	var td = itemPedido.parentNode;
    var tr = td.parentNode;
	trGeneral = tr;  
	var numFila = 6;
	if ($$("idpantallapu").value == "0"){
	    numFila--;	
	}
	if ($$("idpantallapt").value == "0"){
	    numFila--;	
	}
	  
    var filtro="tipo=eliminarPedido&iddetalle="+desconvertirFormatoNumber(tr.cells[numFila].innerHTML);
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
	$$("btAccion").style.visibility = "hidden";   
} 


 var getReporte1 = function(){
	window.open('reporte1.php?idatencion='+$$("idatencion").value+'&nroatencion='+$$("idnroatencion").value,'target:_blank');	
 }

  var getDatosVenta = function() {
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible"; 
	var filtro = "transaccion=reporte1&nroatencion="+ $$("idnroatencion").value +"&idatencion=" +$$("idatencion").value; 
	enviar("Dreporte.php", filtro, setReporte); 
  }
 
   var setEspacios = function(numero) {
	   var cadena = "";
       for (var  i = 1; i <= numero; i++) {
		   cadena = cadena + " ";
	   }
	   return cadena;
   }

   var setTitulo = function(titulo) {
	   return setEspacios(5)+  str_pad(titulo, 30, " ", "STR_PAD_BOTH") +"\n ";
   }
   
   var setnotaVenta = function(nro) {
	   return setEspacios(26) + "N.V.: "+ nro + " \n ";
   }
   
   var setCliente = function(cliente) {
	   return setEspacios(2) + "Cliente: " + cliente + " \n ";  
   }
   
   var setFecha = function(fecha) {
	   return setEspacios(2) + "Fecha: " + fecha + " \n \n ";  
   }
   
   var setDivision = function(num) {
	   var cadena = "" + setEspacios(1);	   
	       for (var i = 0; i <= num; i++){
	           cadena = cadena + "="; 	   
	       }
	   cadena = cadena + " \n ";	   
       return cadena;
   }
   
   var setSubTitulo = function() {       
	   var cadena = setDivision(37) + " Cant     Producto     P/U     P/Total \n " + setDivision(37);   
	   return cadena;
   }
   
   var setTitulo1 = function(){
	   return setEspacios(10) + "ATENCION DE MESA \n "; 
   }
   
   var setCabecera = function(sucursal, nroNota, fecha) {
	   var cabecera = "";
	   cabecera = setTitulo("DISCOTECA - "+ sucursal) + setTitulo1() + setnotaVenta(nroNota) 
	   + setCliente("Varios") + setFecha(fecha) + setSubTitulo();
       return cabecera;
   }
   
   var setTotal = function(total) {
	   var cadena = "";
	   return setEspacios(28) + setDivision(9) + setEspacios(29) 
	   + str_pad(convertirFormatoNumber(parseFloat(total).toFixed(2)), 7, " ", "STR_PAD_BOTH") + " \n \n \n ";
   }
   
   var setContenido = function(producto, cantidad, precio, total) {
	 var cadena =  setEspacios(1) + str_pad(cantidad,3," ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(producto, 17, " ", "STR_PAD_RIGHT") + 
	 setEspacios(1) + str_pad(convertirFormatoNumber(parseFloat(precio).toFixed(1)), 6, " ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(convertirFormatoNumber(parseFloat(total).toFixed(1)), 7, " ", "STR_PAD_RIGHT") + " \n ";
     return cadena;
   }
   
   var setFirma = function(usuario, pedido) {
	 var fecha = new Date();  
	 return   setEspacios(1) + "___________________ \n " 
	 + str_pad(usuario, 19, " ", "STR_PAD_BOTH") + " \n " 
	 + setEspacios(24) + "Pedido: " + pedido + " \n " 
	 + setEspacios(24) + "Hora: " + fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds();
   }
   
  
   var setReporte = function(resultado) {
      var datos = eval(resultado);

      if (datos.length > 1) {
        var cadena = setCabecera(datos[0][0], datos[0][1], datos[0][4]);

	    var total = 0;
	    for (var i=1; i<datos.length; i++) {
		  total = total + datos[i][3]; 
		  cadena = cadena + setContenido( datos[i][0], datos[i][1], datos[i][2], datos[i][3]);
	    }
	    cadena = cadena + setTotal(total);
	    cadena = cadena + setFirma(datos[0][2], datos[0][3]);
		$$('overlay').style.visibility = "hidden";
        $$('gif').style.visibility = "hidden"; 
	    printR1(cadena, jumpIngreso);			
	  } else {
		$$('gif').style.visibility = "hidden";  
		$$('modal_mensajes').style.visibility = "visible";
		$$("modal_contenido").innerHTML = "No puede Imprimir debido a que no realizo Ventas.";   
		$$("btAccion").style.visibility = "hidden";
	  }
   }

   function printR1(datos, funcion) {
         var applet = document.jzebra;
		 
		  if (applet != null) {
			   applet.findPrinter();  
	   
			   applet.append (datos);			  
			   for (var i=0; i<=10; i++){
				applet.append ("  \n ");   
			   }
				
			   applet.print();
		  } else {
		      alert("Error: Debe Instalar el plugin de Java.");  
		  }
		  funcion();
    }   

 var getDatosVentaSalir = function() {
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible"; 
	var filtro = "transaccion=reporte1&nroatencion="+ $$("idnroatencion").value +"&idatencion=" +$$("idatencion").value; 
	enviar("Dreporte.php", filtro, setReporteSalir); 
 }
   
 var setReporteSalir = function(resultado) {      
      var datos = eval(resultado);
      var cadena = setCabecera(datos[0][0], datos[0][1], datos[0][4]);

	  var total = 0;
	   for (var i=1; i<datos.length; i++) {
		  total = total + datos[i][3]; 
		  cadena = cadena + setContenido( datos[i][0], datos[i][1], datos[i][2], datos[i][3]);
	 }
	  cadena = cadena + setTotal(total);
	  cadena = cadena + setFirma(datos[0][2], datos[0][3]);
	  
	  printR1(cadena, jumpSalir);	
	  $$('overlay').style.visibility = "hidden";
      $$('gif').style.visibility = "hidden";   
   }


 var jumpSalir = function(){	 
	location.href = "cerrar.php"; 
 }

 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	  case 119://F8	  
		getDatosVentaSalir();
	  break;
   }
 }
   
   
   function str_pad (input, pad_length, pad_string, pad_type) {
	var half = '',
	  pad_to_go;
  
	var str_pad_repeater = function (s, len) {
	  var collect = '',
		i;
  
	  while (collect.length < len) {
		collect += s;
	  }
	  collect = collect.substr(0, len);
  
	  return collect;
	};
  
	input += '';
	pad_string = pad_string !== undefined ? pad_string : ' ';
  
	if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {
	  pad_type = 'STR_PAD_RIGHT';
	}
	if ((pad_to_go = pad_length - input.length) > 0) {
	  if (pad_type == 'STR_PAD_LEFT') {
		input = str_pad_repeater(pad_string, pad_to_go) + input;
	  } else if (pad_type == 'STR_PAD_RIGHT') {
		input = input + str_pad_repeater(pad_string, pad_to_go);
	  } else if (pad_type == 'STR_PAD_BOTH') {
		half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
		input = half + input + half;
		input = input.substr(0, pad_length);
	  }
	}
     return input;
   }
   
   
   
  var resultadoCobranza = function(){  
        var filtro = "transaccion=reporte2&idatencion=" + $$("idatencion").value; 
        enviar("Dreporte.php", filtro, setReporteR2);  		 
  }
   
   
   // Reporte Cierre Mesa

   var setnotaVentaR2 = function(nro, tipo) {
	   return setEspacios(2) + "N.V.: "+ str_pad(nro, 7, " ", "STR_PAD_RIGHT") + setEspacios(10) + "Tipo: "+ tipo + " \n ";
   }
   
   
   var setFechaR2 = function(fechaG) {
       var fecha = new Date(); 
	   return setEspacios(2) + "Fecha: " + fechaG + setEspacios(6) 
	   + "Hora: " + fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds()+ "  \n ";  
   }
   
     
   var setTitulo2 = function(){
	   return setEspacios(12) + "CIERRE DE MESA \n \n "; 
   }
   
   var setCabeceraR2 = function(sucursal, nroNota, fecha, tipo, cliente) {
	   var cabecera = "";
	   cabecera = setTitulo("DISCOTECA - "+ sucursal) + setTitulo2() + setCliente(cliente) 
	   + setDivision(37) + setFechaR2(fecha)  + setnotaVentaR2(nroNota, tipo)  
	   + setSubTitulo();
       return cabecera;
   }
   
   var setTotalR2 = function(total, descuento, efectivo, cortesia, tipo) {
	 var titulo = "Efectivo: ";  
	 if (tipo == "Credito") {
		 titulo = "Credito: "; 
	 }
	   
	 var cadena = "";
	 return setEspacios(28) + setDivision(9) + setEspacios(11) + "Total a Entregar: " 
	 + str_pad( convertirFormatoNumber(parseFloat(total).toFixed(2)), 7, " ", "STR_PAD_RIGHT") + " \n "
	 + setEspacios(18)+ "Descuento: " + str_pad(convertirFormatoNumber(parseFloat(descuento).toFixed(2)), 7, " ", "STR_PAD_RIGHT")
	 + " \n "
	 + setEspacios(19)+ titulo + str_pad(convertirFormatoNumber(parseFloat(efectivo).toFixed(2)), 7, " ", "STR_PAD_RIGHT") 
	 + " \n "
	 + setEspacios(19)+ "Cortesia: " + str_pad(convertirFormatoNumber(parseFloat(cortesia).toFixed(2)), 7, " ", "STR_PAD_RIGHT") 
	 + " \n \n \n ";
   }
   
   var setFirmaR2 = function(usuario, cliente, tipo) {	
     if (tipo == "Credito" || tipo == "Contado") { 
	   if (cliente == "Varios") {
		   return   setEspacios(1) + "___________________ \n " 
		   + str_pad(usuario, 19, " ", "STR_PAD_BOTH") + " \n " ;
	   } else {
		   return   setEspacios(1) + "________________   _______________\n " 
		   + str_pad(usuario, 18, " ", "STR_PAD_BOTH") + "   " + str_pad(cliente, 15, " ", "STR_PAD_BOTH") + " \n " ; 
	   }
	 } else {
		return   setEspacios(1) + "________________   _______________\n " 
		   + str_pad(usuario, 18, " ", "STR_PAD_BOTH") + " " + str_pad("Autorizo", 15, " ", "STR_PAD_BOTH") + " \n " ; 
	 }
   }
   
   
   var setReporteR2 = function(resultado) {
      var datos = eval(resultado);
	  if ($$("imprimircm").value == "1" || datos[0][4] == "Cortesia" || datos[0][4] == "Credito") {
		var cadena = setCabeceraR2(datos[0][0], datos[0][1], datos[0][3], datos[0][4], datos[0][8]);
  
		var total = 0;
		for (var i = 1; i < datos.length; i++) {
			total = total + datos[i][3]; 
			cadena = cadena + setContenido( datos[i][0], datos[i][1], datos[i][2], datos[i][3]);
	    }
		cadena = cadena + setTotalR2(total, datos[0][5], datos[0][6], datos[0][7], datos[0][4]);
		cadena = cadena + setFirmaR2(datos[0][2], datos[0][8], datos[0][4]);
		print(cadena);	  
	  }
      $$('overlay').style.visibility = "hidden";
      $$('gif').style.visibility = "hidden"; 
	  jumpIngreso();
   }



   
   
   
    function print(datos) {
         var applet = document.jzebra;
		 
		  if (applet != null) {
			   applet.findPrinter();  
	   
			   applet.append (datos);			  
			   for (var i=0; i<=10; i++){
				applet.append ("  \n ");   
			   }
				
			   applet.print();
		  } else {
		      alert("Error: Debe Instalar el plugin de Java.");  
		  }
    } 


   
  
  