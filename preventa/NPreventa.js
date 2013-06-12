// JavaScript Document

  var total_bolivianos = 0;
  var total_Dolares = 0;
  var idUTransaccion = 0;
  var transaccion = "insertar";
  var servidor = "preventa/Dpreventa.php";
  var irDireccion = "listar_preventa.php";

 var $$ = function(id){
    return document.getElementById(id);	 
 }

 var ajax = function(){
	return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
 }

  function accion() { 
      $$("overlay").style.visibility = "hidden";
	  $$("modal").style.visibility = "hidden"; 
	  $$("msjsubnumero").style.visibility = "hidden";
	  $$("dato").value = "";
	  $$("dato").focus();	 
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

  var cambiarTipo = function(tipo) {	
	if (tipo == "efectivo") {
	  document.formdatos.tiempocredito.disabled = true;	
	} else {
	  document.formdatos.tiempocredito.disabled = false;		
	}
 }

  function consultar(filtro, funcion) {
   var  pedido = ajax();	
   pedido.open("GET",servidor+"?"+filtro, true);
	 pedido.onreadystatechange = function(){
		 if (pedido.readyState == 4){     	
			var resultado = pedido.responseText;   
			if (funcion != null) 
			 funcion(resultado);   
		 }	   
	 }
	 pedido.send(null);
  }

 var tipoBusqueda = function(e){
	var  sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
	eventoTeclas(e,"texto",'clienteResult','cliente','nombre','idcliente','eventoResultadoCliente'
	,'autocompletar/consultor.php',sql,'','autoL2');	  
 }
 
  var eventoResultadoCliente = function(resultado, codigo){
	   $$("texto").value= resultado;
	   $$("cliente").value = codigo;	
	   verificarRuta(codigo); 
   }


  function verificarRuta(codigo){
	if (codigo != "") {	
	    var filtro = "tipo=datosCliente&idcliente="+codigo;
	    consultar(filtro, resultadoRuta);	
	}
  }

  function resultadoRuta(resultados){
	  var datos = resultados.split("---");
	  $$("ruta").value = datos[0];
	  $$("idruta").value = datos[1];
	  $$("documento").value = datos[2];
	  $$("nombrenit").value = datos[3];
  }


	var $$U = function(id){
	  return encodeURIComponent($$(id).value);	
	}
	
	var enviarMaestro = function(){
	  if (esvalido()) {	
		$$('overlay').style.visibility = "visible";
		$$('gif').style.visibility = "visible";	
		
		 nfilas = $$('detalleTransaccion').rows.length;    	
		 json = new Array();
		 for(var i = 0;i < nfilas;i++) {
		   vector = new Array();
		   vector[0]=$$('detalleTransaccion').rows[i].cells[1].innerHTML;	//idproducto	
		   vector[1]=$$('detalleTransaccion').rows[i].cells[3].innerHTML;   //cantidad
		   vector[2]=$$('detalleTransaccion').rows[i].cells[4].innerHTML;	//um							
		   vector[3]=$$('detalleTransaccion').rows[i].cells[5].innerHTML;	//precio	
		   json[i] = vector;	 		
		 }
		 dato = JSON.stringify(json);
		
		 var filtro ="fecha="+$$("fecha").value+"&idalmacen="+ $$("idalmacen").value+"&fechaentrega="
		 +$$("fechaentrega").value+"&moneda="+$$("moneda").value+"&idcliente="+$$("cliente").value
		 +"&glosa="+$$U("glosa")+"&modalidad="+$$("formapago").value+"&tipo="+$$("transaccion").value
		 +"&idpreventa="+$$("idTransaccion").value + "&detalle=" + dato
		 +"&dias="+$$("tiempocredito").value+"&tipocambio="+$$("tipoCambioBs").value
		 +"&tipoprecio="+$$("tipoprecio").value;  
		 consultar(filtro, registroTransaccion);
	  }
	}
	
	var registroTransaccion = function(resultado) {
      idUTransaccion = resultado;  
	  $$('overlay').style.visibility = "visible";
      $$('modal_vendido').style.visibility = "visible";
	  $$('gif').style.visibility = "hidden";
	}


	function enviarDireccion(){
	  location.href = "nuevo_preventa.php";	
	}
	
	function esvalido(){
	  if ($$('idalmacen').value == "") {
	    openMensaje("Advertencia",'Debe seleccionar la Sucursal.');
	    return false;
	  }
	  if ($$('cliente').value == "") {
	    openMensaje("Advertencia",'Debe seleccionar un Cliente.');
	    return false;
	  }
  	  if ($$('fecha').value == "") {
	    openMensaje("Advertencia",'Debe ingresar fecha de realización.');
	    return false;
	  }
	  if ($$('fechaentrega').value == "") {
	    openMensaje("Advertencia",'Debe ingresar fecha de entrega.');
	    return false;
	  }	  
	  if (!validarFecha($$("fecha").value)) {
		openMensaje("Advertencia",'Debe ingresar una fecha valida.');
	    return false;
	  }
	  if (!validarFecha($$("fechaentrega").value)) {
		openMensaje("Advertencia",'Debe ingresar una fecha de entrega valida.');
	    return false;
	  }
	  if ($$('detalleTransaccion').rows.length == 0){
	    openMensaje("Advertencia",'Debe ingresar el detalle de la Transacción.');
	    return false;
	  } 	 	
	  return true;
	}
	
	function accionPostRegistro(){
	   window.open('preventa/imprimir_preventa.php?idpreventa='+idUTransaccion+'&logo='+$$("logo").checked,'target:_blank');
	   enviarDireccion();	
	}


  var eventoIngresoCantidad = function(evento){
	  var tecla = (document.all) ? evento.keyCode : evento.which;		
	  if (tecla == 13)
		validarEntradaVentana();
  }

  function validarEntradaVentana(){
	 if (validaSubVentana())
	  agrega_celda();
  }
 
  function cerrarPagina() {
   location.href = "nuevo_preventa.php";	
  }

 
  function validaSubVentana(){
	 var cant;
	 if (obtenerSeleccionRadio('formdatos','pselectorU') == "UM"){
		cant = ($$("cant").value == "") ? 0 : parseFloat($$("cant").value);  
		if (cant <= 0){
		 openMensaje("Advertencia","Debe ingresar una cantidad mayor a 0");	
		 return false;
		}
	 }
	 else{
	    cant = ($$("cantA").value == "") ? 0 : parseFloat($$("cantA").value); 
		if (cant <= 0){
		 openMensaje("Advertencia","Debe ingresar una cantidad mayor a 0");
		 return false;		 
		}
	 }
	 return true;
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
		if (Data.length > 2)
		  y.style.display = Data[2];
     }
 }

 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;	
	$$("cambio").value = 0;
	setSubTotal(-parseFloat(desconvertirFormatoNumber(tr.cells[8].innerHTML)));
    var table = tr.parentNode;
    table.removeChild(tr);
 }
 
 function subVentanaValida() {
 var cantidad = ($$("cant").value == "") ? 0 : $$("cant").value; 
   if (parseFloat(cantidad) <= 0){
  	  $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "Debe ingresar una cantidad mayor a 0";
	  return false;	
	}
	if (!isvalidoNumero("cant") || !isvalidoNumero("cantUM")){
	  $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "La cantidad ingresada es Incorrecta"; 
	  return false;		
	}
	$$("msjsubnumero").style.visibility = "hidden";
	return true;
 }
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }
 
 var agrega_celda =function(){	
     $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 	 
	 var cantidad;
	 var unidadM;
	 var precio;
	 if (obtenerSeleccionRadio('formdatos','pselectorU') == "UM") {
		cantidad = $$("cant").value;
		unidadM = $$("unidadmedida").value; 
		precio = parseFloat($$("precioProducto").value);
	 } else {
		cantidad = $$("cantA").value;
		unidadM = $$("unidadalternativa").value; 
		precio = parseFloat($$("precioProducto").value) / parseFloat($$("conversion").value);
	 }
	 
	 var datos = new Array();
	 var total = parseFloat(cantidad) * precio;
     datos[0] = new Array("<img src='css/images/borrar.gif' title='Eliminar' alt='borrar' onclick='eliminarFila(this)' />","center");
	 datos[1] = new Array($$("codidproducto").value,"center");
	 datos[2] = new Array($$("dato").value,"left");
	 datos[3] = new Array(cantidad,"center");
	 datos[4] = new Array(unidadM,"center");
 	 datos[5] = new Array(convertirFormatoNumber(precio.toFixed(4)),"center");
	 datos[6] = new Array(convertirFormatoNumber(total.toFixed(4)),"center");	 
	 insertarFila(datos,"detalleTransaccion");	 
  	 setTotal(total);	
	 $$("dato").value=""; 
     document.formdatos.dato.focus();   
}
 
 

 function setTotal(total){
	 total = (isNaN(total)) ? 0 : total ;
	 total_bolivianos = total_bolivianos + total;
	 total_Dolares = total_bolivianos / ($$("tipoCambioBs").value);
	 $$("subtotalBS").value = convertirFormatoNumber(total_bolivianos.toFixed(2));
	 $$("subtotalDL").value = convertirFormatoNumber(total_Dolares.toFixed(2));		
 }
 

 
 function salir(){ 
	 location.href = irDireccion;  
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
  
 
   
   var autocompletar = function(e,id) {
	var consulta = "select idproducto,left(nombre,25)as 'nombre' from producto where estado=1 and "; 
	eventoTeclas(e,id,'resultados','producto','nombre','idproducto'
	,'eventoResultado','autocompletar/consultor.php',consulta,'','autoL1');
  }
  
   var eventoResultado = function(resultado, codigo){	
       var filtro = "tipo=datosProdcuto&idproducto=" + codigo + "&precio=" + $$("tipoprecio").value;
       consultar(filtro, cargarCantidad); 
	   $$("dato").value =resultado;
	   $$("codidproducto").value = codigo; 	 
	   $$("overlay").style.visibility = "visible";
	   $$('gif').style.visibility = "visible"; 
	   $$("cant").value="";      		
   }
   
   
  var cargarCantidad = function(resultado){
	 var datos = resultado.split("---"); 
     $$("precioProducto").value = datos[0];
     $$("unidadmedida").value = datos[1];
	 $$("unidadalternativa").value = datos[2];
	 $$("conversion").value = datos[3];
	 $$("cantA").value = "0.00";
	 $$('gif').style.visibility = "hidden";
	 $$("modal").style.visibility = "visible"; 
     document.formdatos.cant.focus();
 }