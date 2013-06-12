// JavaScript Document

var total_bolivianos = 0;
var total_Dolares = 0;
var idUTransaccion = 0;
var transaccion = "insertar";
var servidor = "cotizaciones/DCotizacion.php";
var irDireccion = "listar_cotizacion.php#t1";

 var $$ = function(id){
  return document.getElementById(id);	 
 }

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

function accion(){
	 $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
	 $$("msjsubnumero").style.visibility = "hidden";
	 $$("dato").value = "";
	 $$("dato").focus();	 
}

var autocompletar = function(e,id){
	if ($$("tipocotizacion").value == "productos") {
	    var consulta = "select idproducto,left(nombre,25)as 'nombre' from producto where estado=1 and "; 
	} else {
	    var consulta = "select idservicio as 'idproducto',left(nombre,25)as 'nombre' from servicio where estado=1 and ";
	}
	eventoTeclas(e,id,'resultados','producto','nombre','idproducto','eventoResultado'
	,'autocompletar/consultor.php',consulta,'','autoL1');
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

   }
 }

function consultar(parametros,funcion){
 var  pedido = ajax();	
 filtro ="transaccion=consulta&codigo="+parametros; 
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText.split("---");    
    	  funcion(resultado[0]);   
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
    contacto = $$U('contacto');   
    moneda = $$('moneda').value;  
    montototal = desconvertirFormatoNumber($$("subtotalBS").value);  
    filtro ="fecha="+$$("fecha").value+"&almacen="+almacen+"&contacto="+contacto
	+"&moneda="+moneda+"&cliente="+$$("cliente").value+
	"&glosa="+$$U("glosa")+"&monto="+montototal+"&transaccion="+transaccion
	+"&idcotizacion="+$$("idTransaccion").value+"&validez="+$$("validez").value
	+"&tiempoentrega="+$$("tiempoentrega").value+"&tiempocredito="+$$("tiempocredito").value
	+"&formadepago="+$$("formapago").value+"&precio="+$$("tipoprecio").value+"&descuento="+$$("pdescuento").value
	+"&recargo="+$$("precargo").value+"&tipocambio="+$$("tipoCambioBs").value
	+"&carta="+$$("carta").value+"&tipocotizacion="+$$("tipocotizacion").value;  	
    enviarDetalle(filtro);
  }
  else{
	mostrarMensajeError();  
  }
}

var enviarDetalle = function(filtro) {		
     nfilas = $$('detalleT').rows.length;    	
     json = new Array();
     for(i = 0;i < nfilas;i++) {
	   vector = new Array();
	   vector[0]=$$('detalleT').rows[i].cells[1].innerHTML;		
	   vector[1]=$$('detalleT').rows[i].cells[3].innerHTML;
	   vector[2]=$$('detalleT').rows[i].cells[4].innerHTML;								
	   vector[3]=$$('detalleT').rows[i].cells[5].innerHTML;		
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);	 
     filtro = filtro + '&detalle=' + dato; 	 
     enviar(filtro);
  }

function mostrarMensajeError(){
  if ($$('almacen').value == ""){   
   openMensaje("Advertencia",'Debe seleccionar un Almacén');
   return;
  }
  if ($$('cliente').value == ""){
   openMensaje("Advertencia",'Debe seleccionar un Cliente');
   return;	  
  }
  if ($$('detalleT').rows.length == 0){   
    openMensaje("Advertencia",'Debe ingresar detalle en la Cotizacion');
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
			cerrarPagina();  
		  }
	   }	   
   }
   pedido.send(null);   	
}

function enviarDireccion(){
  location.href = irDireccion;	
}

 var tipoBusqueda = function(e) {
	var  sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
	eventoTeclas(e,"texto",'clienteResult','cliente','nombre','idcliente','eventoResultadoCliente','autocompletar/consultor.php',sql,'','autoL2');	  
 }

var eventoResultadoCliente = function(resultado, codigo){
	 $$("texto").value= resultado;
	 $$("cliente").value = codigo;	
	 getCliente(codigo);   
}

  function consultarVendedor(filtro,funcion){
   var  pedido = ajax();	
   pedido.open("GET",servidor+"?"+filtro,true);
	 pedido.onreadystatechange = function(){
		 if (pedido.readyState == 4){     	
			var resultado = pedido.responseText;    
			funcion(resultado);   
		 }	   
	 }
	 pedido.send(null);	
  }	
  
  function getCliente(codigo){
	if (codigo != ""){	
	 var filtro = "transaccion=consultarCliente&idcliente="+codigo;
	 consultarVendedor(filtro, resultadoCliente);	
	}
  }
  
  function resultadoCliente(resultado) {
	  $$("contacto").value = resultado;
  }
  
  var bloquearForma = function(valor) {
	 if (valor == "credito") {		
		$$("tiempocredito").disabled = false; 
	 } else {
		seleccionarCombo("tiempocredito", 0);  
		$$("tiempocredito").disabled = true;  
	 }
  }


  function cerrarPagina(){
	location.href = "nuevo_cotizacion.php";	
  }
  
  function esvalido(){
	return ($$('almacen').value != "" && $$('cliente').value != "" && $$('detalleT').rows.length > 0); 	
  }
  
  function accionPostRegistro(){
	 window.open('cotizaciones/imprimir_cotizacion.php?idcotizacion='+idUTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
	 cerrarPagina();
  }
  
  var eventoIngresoCantidad = function(evento){
	  var tecla = (document.all) ? evento.keyCode : evento.which;		
	  if (tecla == 13)
	  agrega_celda();	
  }



  var eventoResultado = function(resultado,codigo){	  
	   var filtro = codigo+"&tipoprecio="+$$("tipoprecio").value + "&tipocotizacion=" + $$("tipocotizacion").value; 
	   consultar(filtro, cargarCantidad); 
	   $$("dato").value =resultado;
	   $$("codidproducto").value = codigo; 	 
	   $$("overlay").style.visibility = "visible";
	   $$('gif').style.visibility = "visible";
	   $$("msjsubnumero").style.visibility = "hidden";
	   $$("cant").value="";      	
   }
   
   
   var cargarCantidad = function(precio) {
	   $$("precioProducto").value = precio;
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
	  setSubTotal(-parseFloat(desconvertirFormatoNumber(tr.cells[5].innerHTML)));
	  var table = tr.parentNode;
	  table.removeChild(tr);
   }
   
   function subVentanaValida(){
	  var cantidad = ($$("cant").value == "") ? 0 : $$("cant").value; 
	 if (parseFloat(cantidad) <= 0){
		$$("msjsubnumero").style.visibility = "visible";
		$$("msjsubnumero").innerHTML = "Incorrecto"; 
		return false;	
	  }
	  if (!isvalidoNumero("cant")){
		$$("msjsubnumero").style.visibility = "visible";
		$$("msjsubnumero").innerHTML = "Incorrecto"; 
		return false;		
	  }		
	  $$("msjsubnumero").style.visibility = "hidden";
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
	   total = $$("cant").value * $$("precioProducto").value;
	   datos[5] = new Array(convertirFormatoNumber(total.toFixed(2)),"center");
	   insertarFila(datos,'detalleT');
	   setSubTotal(total);
	   $$("overlay").style.visibility = "hidden";
	   $$("modal").style.visibility = "hidden"; 
	   $$("dato").value=""; 
	   document.form1.dato.focus();
	 }
  }
   
   
   function setSubTotal(total){
	   total_bolivianos = total_bolivianos + total;	 
	   $$("subtotal").value = total_bolivianos; 
	   calcularDescuento();
	   calcularRecargo();
	   calcularTotales();
   }
   
   function calcularDescuento(){	 
	  var descuento = ($$("pdescuento").value == "") ? 0 : $$("pdescuento").value; 
	  descuento = parseFloat(descuento / 100) * total_bolivianos;
	  $$("descuento").value = descuento.toFixed(2);
	  calcularTotales();
   }
	
   function calcularRecargo(){
	 var recargo = ($$("precargo").value == "") ? 0 : $$("precargo").value; 
	  recargo = parseFloat(recargo / 100) * total_bolivianos;
	  $$("recargo").value = recargo.toFixed(2);	
	  calcularTotales(); 
   }
   
   
   function calcularTotales(){	 
	 var descuento = $$("descuento").value;
	 var recargo = $$("recargo").value;
	 var totalParcial = ((parseFloat(total_bolivianos) - parseFloat(descuento)) + parseFloat(recargo)) ;   
	   total_Dolares = totalParcial / ($$("tipoCambioBs").value);
	   $$("subtotalBS").value = convertirFormatoNumber(totalParcial.toFixed(2));
	   $$("subtotalDL").value = convertirFormatoNumber(total_Dolares.toFixed(2));
   }
   
	
   function limpiarDetalle(){
		$$("detalleT").innerHTML = ""; 
		setSubTotal(-total_bolivianos);  
   }
   
   function cambiarTipo(dato)
   {
	   if (dato == "productos") {
		   $$("textotipo").innerHTML = "Producto:";	 
	   } else {
		   $$("textotipo").innerHTML = "Servicio:";
	   }
	   limpiarDetalle();
   }
   
   function salir(){
	 if ($$('detalleT').rows.length > 0){
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
