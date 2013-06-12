// JavaScript Document

var total_bolivianos = 0;
var total_Dolares = 0;
var idUTransaccion = 0;
var transaccion = "insertar";
var servidor = "factura/servicio/DVenta.php";
var irDireccion = "listar_notaventaservicios.php#t3";

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
	 $$("msjsubcantidad").style.visibility = "hidden";
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
	case 119:
	  seleccionarFacturar();
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

  function consultarFactura()
  {
	if ($$("sucursal").value != "") {	
		var filtro = "transaccion=factura&idsucursal="+$$("sucursal").value;
		consultarGeneral(filtro,cargarFacturas);	
	} else {
		$$("facturas").value = "";
		document.form1.facturado.checked = "";
	}
  }

  function cargarFacturas(resultado) 
  {
	  var datos = resultado.split("---");	
	  if (datos[0] == "fecha" || datos[0] == "numero") {
			if (datos[0] == "fecha") {
			  openMensaje("Advertencia","Fecha limite de emisión de factura.");  
			} else {
			  openMensaje("Advertencia","Facturas agotadas.");    
			}		  
			document.form1.facturado.checked = "";	 
			$$("facturas").value = "";  
			$$("facturas").disabled = "disabled";
		} else {
			$$("facturas").value = datos[0];
			document.form1.facturado.checked = "check";
			$$("facturas").disabled = "";
		}  
  }

 var tipoBusquedaCliente = function(e){
	var  sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
	eventoTeclas(e,"texto",'clienteResult','cliente','nombre','idcliente','eventoResultadoCliente'
	,'autocompletar/consultor.php',sql,'','autoL2');	  
 }

  var eventoResultadoCliente = function(resultado, codigo){
	   $$("texto").value= resultado;
	   $$("cliente").value = codigo;	
	   verificarVendedor(codigo);   
   }


function consultarGeneral(filtro,funcion){
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

function seleccionarFacturar(){
	var facturar = document.form1.facturado.checked;
	var e = (facturar == false) ? 'check' : '' ;
    document.form1.facturado.checked = e;
	solicitarNumFactura();
}

function solicitarNumFactura(){
  var facturar = document.form1.facturado.checked;
  var filtro;
  if ($$("sucursal").value == ""){
	openMensaje("Advertencia","Debe seleccionar un sucursal para obtener el numero de factura");  
	document.form1.facturado.checked = "";
	return;
  }
  
  if (facturar == true) {
	filtro = "transaccion=factura&idsucursal="+$$("sucursal").value;  
	consultarGeneral(filtro,resultadoNumFactura);
  } else {
    $$("facturas").value = "";
	$$("facturas").disabled = "disabled"; 
  }
}

function resultadoNumFactura(resultado){
	var result = resultado.split("---");
	if (result[0] == "fecha" || result[0] == "numero"){
		  if (result[0] == "fecha"){
			openMensaje("Advertencia","Fecha limite de emisión de factura.");    
		  } else {
			openMensaje("Advertencia","Facturas agotadas.");			  
		  }		  
          $$("facturas").value = "";	
		  $$("facturas").disabled = "disabled";   
	  } else {
		  $$("facturas").value = result[0];
		  $$("facturas").disabled = "";
	  }
}


function calcularCambio(){
 var efectivo;
 var efectivoDolares;
 var total;	
 efectivo = ($$("cambio").value == "") ? 0 : parseFloat($$("cambio").value);
 total = ($$("subtotalBS").value == "") ? 0 : parseFloat(desconvertirFormatoNumber($$("subtotalBS").value));
 $$("efectivo").value = parseFloat(total - efectivo).toFixed(2); 
 if (parseFloat($$("efectivo").value) > 0) {
	$$("caja").disabled = ""; 
 }
 
 if (parseFloat($$("efectivo").value) <= 0 || $$("efectivo").value == "") {
	$$("caja").disabled = "disabled"; 
 }
 
 if (parseFloat($$("cambio").value) > 0) {
	$$("diascredito").disabled = ""; 
 }
 
 if (parseFloat($$("cambio").value) <= 0 || $$("cambio").value == "") {
	$$("diascredito").disabled = "disabled"; 
 }
}

function calcularCambio2(){
 var efectivo;
 var efectivoDolares;
 var total;	
 efectivo = ($$("efectivo").value == "") ? 0 : parseFloat($$("efectivo").value);
 total = ($$("subtotalBS").value == "") ? 0 : parseFloat(desconvertirFormatoNumber($$("subtotalBS").value));
 $$("cambio").value = parseFloat(total - efectivo).toFixed(2); 
 
 if (parseFloat($$("efectivo").value) > 0) {
	$$("caja").disabled = ""; 
 }
 
 if (parseFloat($$("efectivo").value) <= 0 || $$("efectivo").value == "") {
	$$("caja").disabled = "disabled"; 
 }
 
 if (parseFloat($$("cambio").value) > 0) {
	$$("diascredito").disabled = ""; 
 }
 
 if (parseFloat($$("cambio").value) <= 0 || $$("cambio").value == "") {
	$$("diascredito").disabled = "disabled"; 
 }
 
}


function consultarServidor(parametros,funcion){
 var  pedido = ajax();	
 filtro ="transaccion=consultarDato&codigo="+parametros; 
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText.split("---");    
    	  funcion(resultado[0],resultado[1],resultado[2],resultado[3],resultado[4],resultado[5]);   
	   }	   
   }
   pedido.send(null);
}



function consultarVendedor(filtro,funcion){
 var  pedido = ajax();	
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText.split("---");    
    	  funcion(resultado[0],resultado[1],resultado[2],resultado[3]);   
	   }	   
   }
   pedido.send(null);	
}


function verificarVendedor(codigo){
  if (codigo != ""){	
   var filtro = "transaccion=consultarVendedor&idcliente="+codigo;
   consultarVendedor(filtro,resultadoVendedor);	
  }
}

function resultadoVendedor(idvendedor,nombre,nit,nombrenit){
	$$("vendedor").value = nombre;
	$$("idvendedor").value = idvendedor;
	$$("documento").value = nit;
	$$("nombrenit").value = nombrenit;
}

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

var enviarMaestro = function(){
  if (esvalido()){	
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";
    sucursal = $$('sucursal').value; 
    moneda = $$('moneda').value;  
    montototal = desconvertirFormatoNumber($$("subtotalBS").value);
	subtotal = desconvertirFormatoNumber($$("subtotal").value);  
    filtro ="fecha="+$$("fecha").value+"&sucursal="+sucursal+"&factura="+$$("facturas").value
	+"&moneda="+moneda+"&cliente="+$$("cliente").value+
	"&glosa="+$$U("glosa")+"&monto="+montototal+"&transaccion="+transaccion+"&idnota="+$$("idTransaccion").value
	+"&diascredito="+$$("diascredito").value+"&tipocambio="+$$("tipoCambioBs").value+"&nombrenit="+$$U("nombrenit")
	+"&vendedor="+$$("idvendedor").value+"&precio="+$$("tipoprecio").value+"&descuento="
	+$$("pdescuento").value+"&nit="+$$("documento").value
	+"&recargo="+$$("precargo").value+"&caja="+$$("caja").value+"&subtotal="+subtotal+"&cambio="+$$("cambio").value;
    enviarDetalle(filtro);
  }

}

var enviarDetalle = function(filtro) {		
     nfilas = $$('detalleT').rows.length;    	
     json = new Array();
     for(i = 0;i < nfilas;i++) {
	   vector = new Array();
	   vector[0] = $$('detalleT').rows[i].cells[1].innerHTML;	//codigo		
	   vector[1] = $$('detalleT').rows[i].cells[3].innerHTML;   //precio
   	   vector[2] = $$('detalleT').rows[i].cells[4].innerHTML;   //cantidad
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);	 
     filtro = filtro + '&detalle=' + dato; 	 
     enviar(filtro);
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

function cerrarPagina(){
  location.href = "nuevo_notaventa_servicio.php";	
}

function esvalido(){
   if ($$('sucursal').value == ""){
   openMensaje("Advertencia",'Debe seleccionar la sucursal.');
   return false;
  }
  if ($$('cliente').value == ""){
   openMensaje("Advertencia",'Debe seleccionar un cliente.');
   return false;
  }
  if ($$('caja').value == "" && parseFloat($$('efectivo').value) > 0){
   openMensaje("Advertencia",'Debe seleccionar la cuenta Caja/Banco.');
   return false;
  }  
  if (parseFloat($$('cambio').value) > 0 && $$('diascredito').value == "0"){
   openMensaje("Advertencia",'Los días de crédito deben de ser mayor a 0.');
   return false;
  }
  if ($$('detalleT').rows.length == 0){
   openMensaje("Advertencia",'Debe ingresar detalle en la nota.');
   return false;
  } 	 	
  return true;	
}

function accionPostRegistro(){
   window.open('factura/servicio/imprimir_notaventa_servicios.php?idnotaventa='+idUTransaccion+'&logo='+$$("logo").checked,'target:_blank');
   cerrarPagina();	
}

var eventoIngresoCantidad = function(evento){
	var tecla = (document.all) ? evento.keyCode : evento.which;		
	if (tecla == 13)
	agrega_celda();	
}

var eventoResultado = function(resultado,codigo){	    
    var filtro = codigo+ "&tipoprecio="+$$("tipoprecio").value; 
     consultar(filtro,cargarCantidad); 
     $$("dato").value =resultado;
	 $$("codidservicio").value = codigo; 	 
	 $$("overlay").style.visibility = "visible";
	 $$('gif').style.visibility = "visible";
	 $$("msjsubnumero").style.visibility = "hidden";    		
 }
 
 
 var cargarCantidad = function(precio,cantidad){
	 if ($$("moneda").value == "Dolares"){
		precio = precio / $$("tipoCambioBs").value; 		 
	 } 
	 
	 $$("Sprecio").value = parseFloat(precio).toFixed(4); 
	 $$("Scantidad").value = 1;
	 $$('gif').style.visibility = "hidden";
	 $$("modal").style.visibility = "visible"; 
	 document.form1.Sprecio.focus(); 
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
	setSubTotal(-parseFloat(desconvertirFormatoNumber(tr.cells[5].innerHTML)));
    var table = tr.parentNode;
    table.removeChild(tr);
 }
 
 
 var tipoBusqueda = function(e){
	 var sql;	
	  idconsulta = "idservicio";   	  
	  sql = "select idservicio,left(nombre,30)as nombre from servicio where estado=1 and ";		
	  eventoTeclas(e,"dato",'resultados','servicio','nombre',idconsulta,'eventoResultado'
	  ,'autocompletar/consultor.php',sql,'','autoL1');		
 }
 
 
 function subVentanaValida(){
	var cantidad = ($$("Sprecio").value == "") ? 0 : $$("Sprecio").value; 
    if (parseFloat(cantidad) <= 0){
      $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "Incorrecto";
	  return false;	
	}
	if (!isvalidoNumero("Sprecio") ){
	  $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "Incorrecto"; 
	  return false;		
	}
	if (parseFloat($$("Scantidad").value) <= 0 || $$("Scantidad").value == ""){
      $$("msjsubcantidad").style.visibility = "visible";
	  $$("msjsubcantidad").innerHTML = "Incorrecto";
	  return false;	
	}
	if (!isvalidoNumero("Scantidad")){
	  $$("msjsubcantidad").style.visibility = "visible";
	  $$("msjsubcantidad").innerHTML = "Incorrecto"; 
	  return false;		
	}		
	$$("msjsubnumero").style.visibility = "hidden";
	$$("msjsubcantidad").style.visibility = "hidden";
	return true;
 }
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }
 
 var agrega_celda = function(){	
   if (subVentanaValida()) {
     $$("cambio").value = 0;  
     var datos = new Array();
	 var precio = parseFloat($$("Sprecio").value);
	 var cantidad = parseFloat($$("Scantidad").value);
	 var total = precio * cantidad;
     datos[0] = ["<img src='css/images/borrar.gif' title='Eliminar' alt='borrar' onclick='eliminarFila(this)' />","center"];
	 datos[1] = [$$("codidservicio").value, "center"];
	 datos[2] = [$$("dato").value, "left"];
	 datos[3] = [convertirFormatoNumber(precio.toFixed(4)), "center"];
	 datos[4] = [convertirFormatoNumber(cantidad.toFixed(4)), "center"];
	 datos[5] = [convertirFormatoNumber(total.toFixed(4)), "center"];
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
	 var totalP = total_bolivianos;	 
	 $$("subtotal").value = totalP.toFixed(4); 
	 calcularDescuento();
     calcularRecargo();
	 calcularTotales($$("moneda").value);
	 calcularCambio();
 }
 
 function calcularDescuento(){	 
	var descuento = ($$("pdescuento").value == "") ? 0 : $$("pdescuento").value; 
	descuento = parseFloat(descuento / 100) * total_bolivianos;
	$$("descuento").value = descuento.toFixed(4);
	calcularTotales($$("moneda").value);	
	calcularCambio();
 }
  
 function calcularRecargo(){
   var recargo = ($$("precargo").value == "") ? 0 : $$("precargo").value; 
	recargo = parseFloat(recargo / 100) * total_bolivianos;
	$$("recargo").value = recargo.toFixed(4);	
	calcularTotales($$("moneda").value); 	
	calcularCambio();
 }
 
 
 function calcularTotales(moneda){	 
   var descuento = $$("descuento").value;
   var recargo = $$("recargo").value;
   var totalParcial = ((parseFloat(total_bolivianos) - parseFloat(descuento)) + parseFloat(recargo)) ;   
     total_Dolares = totalParcial / ($$("tipoCambioBs").value);
	 if (moneda == "Bolivianos"){
	  $$("subtotalBS").value = convertirFormatoNumber(totalParcial.toFixed(2));
	  $$("subtotalDL").value = convertirFormatoNumber(total_Dolares.toFixed(2));
	 }else{	
	  var totalC = totalParcial * ($$("tipoCambioBs").value);	   	 
	  $$("subtotalDL").value = convertirFormatoNumber(totalParcial.toFixed(2));
	  $$("subtotalBS").value = convertirFormatoNumber(totalC.toFixed(2));	 
	 }
	
 }
 
 function realizarDescuento(){
   var fila = parseInt($$("idLotes").value);
   var cantidad;	
   if ($$("detalleProdDisponible").rows[fila].cells[3].innerHTML == $$("punidadmedida").value){
	 cant = ($$("cant").value == "") ? 0 : parseFloat($$("cant").value);  
	 cantidad = parseFloat($$("detalleProdDisponible").rows[fila].cells[4].innerHTML) - cant;	
	 $$("detalleProdDisponible").rows[fila].cells[2].innerHTML = cantidad.toFixed(2);   
   }else{
	 cant = ($$("cantUM").value == "") ? 0 :  parseFloat($$("cantUM").value); 
     cantidad = parseFloat($$("detalleProdDisponible").rows[fila].cells[4].innerHTML) - cant;	
     $$("detalleProdDisponible").rows[fila].cells[2].innerHTML = cantidad.toFixed(2);   
   }	
}
 
 function calcularUnidadMedida(cantidad,tipoUnidad){	     
	  if (tipoUnidad == "precioUA"){
		  $$("ppreciounitario").value = parseFloat($$("pconversiones").value * cantidad).toFixed(2);
		  cantidad = $$("cant").value;	
	  }	  
	  if (tipoUnidad == "precioU"){
		  $$("ppreciounitario").value = cantidad;
		  var ppreciound = cantidad / $$("pconversiones").value;
		  cantidad = $$("cant").value;		   
		   $$("ppreciounitarioalternativa").value = ppreciound.toFixed(2); 
	  }	  
	  if (tipoUnidad == "UM" || tipoUnidad == "precioU" || tipoUnidad == "precioUA"){
		 var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value; 
		 pprod = pprod * cantidad;
		 $$("ptotal").value =  pprod.toFixed(2);
		 cantUm = parseFloat(cantidad * $$("pconversiones").value);
		 $$("cantUM").value = cantUm.toFixed(2);
	  }
	  if (tipoUnidad == "UA"){
		var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value;  
		pprod = pprod * (cantidad / $$("pconversiones").value);
		$$("ptotal").value =  pprod.toFixed(2);  
		 cantUm = parseFloat(cantidad / $$("pconversiones").value);
		 $$("cant").value = cantUm.toFixed(2);
	  }
	  realizarDescuento(); 
  }
  
 function limpiarDetalle(){
	  $$("detalleT").innerHTML = ""; 
	  setSubTotal(-total_bolivianos);  
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

