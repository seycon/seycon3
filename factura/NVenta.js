// JavaScript Document

  var total_bolivianos = 0;
  var total_Dolares = 0;
  var idUTransaccion = 0;
  var transaccion = "insertar";
  var servidor = "factura/DVenta.php";
  var irDireccion = "listar_notaventa.php#t2";

  var $$ = function(id){
    return document.getElementById(id);	 
  }

  var ajax = function(){
	return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"
	); 	
  }

  function accion()
  {
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
	case 119:
	  if ($$('overlay').style.visibility != "visible")
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
  
  function consultarGeneral(filtro,funcion){
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

  function consultarAlmacenes()
  {
	if ($$("sucursal").value != "") {	
		var filtro = "transaccion=almacenes&idsucursal="+$$("sucursal").value;
		consultarGeneral(filtro,cargarAlmacenes);	
	} else {
		$$("idalmacen").innerHTML = "";
		$$("facturas").value = "";
		document.form1.facturado.checked = "";
	}
  }
  
  function cargarAlmacenes(resultado) 
  {
	  var datos = resultado.split("---");	
	  $$("idalmacen").innerHTML = datos[0];	
	  if (datos[1] == "fecha" || datos[1] == "numero") {
			if (datos[1] == "fecha") {
			  openMensaje("Advertencia","Fecha limite de emisión de factura.");  
			} else {
			  openMensaje("Advertencia","Facturas agotadas.");    
			}		  
			document.form1.facturado.checked = "";	 
			$$("facturas").value = "";  
			$$("facturas").disabled = "disabled";
		} else {
			$$("facturas").value = datos[1];
			document.form1.facturado.checked = "check";
			$$("facturas").disabled = "";
		}  
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
	if ($$("sucursal").value == "") {
	  openMensaje("Advertencia","Debe seleccionar una sucursal para obtener el número de factura.");  
	  document.form1.facturado.checked = "";
	  return;
	}
	
	if (facturar == true){
	  filtro = "transaccion=factura&idsucursal="+$$("sucursal").value;  
	  consultarGeneral(filtro,resultadoNumFactura);	  
	}
	else{
	  $$("facturas").value = "";
	  $$("facturas").disabled = "disabled"; 
	}
  }
  
  function resultadoNumFactura(resultado){
	  var result = resultado.split("---");
	  if (result[0] == "fecha" || result[0] == "numero") {
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


function verificarRuta(codigo){
  if (codigo != ""){	
   var filtro = "transaccion=consultarVendedor&idcliente="+codigo;
   consultarVendedor(filtro,resultadoRuta);	
  }
}

function resultadoRuta(idruta,nombre,nit,nombrenit){
	$$("ruta").value = nombre;
	$$("idruta").value = idruta;
	$$("documento").value = nit;
	$$("nombrenit").value = nombrenit;
}

function consultarServidor(parametros,funcion){
 var  pedido = ajax();	
 filtro ="transaccion=consultarDato&codigo="+parametros+"&idalmacen="+$$("idalmacen").value+"&tipoprecio="+$$("tipoprecio").value; 
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText.split("---");    
    	  funcion(resultado[0],resultado[1],resultado[2],resultado[3],resultado[4],resultado[5]);   
	   }	   
   }
   pedido.send(null);
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

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

var enviarMaestro = function(){
  if (esvalido()) {	
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";
    sucursal = $$('sucursal').value; 
    moneda = $$('moneda').value;  
    montototal = desconvertirFormatoNumber($$("subtotalBS").value);  
	subtotal = desconvertirFormatoNumber($$("subtotal").value);
    filtro ="fecha="+$$("fecha").value+"&sucursal="+sucursal+"&factura="+$$("facturas").value+"&moneda="+moneda+"&cliente="
	+$$("cliente").value+
	"&glosa="+$$U("glosa")+"&monto="+montototal+"&transaccion="+transaccion+"&idnota="+$$("idTransaccion").value
	+"&diascredito="+$$("diascredito").value+"&tipocambio="+$$("tipoCambioBs").value
	+"&ruta="+$$("idruta").value+"&precio="+$$("tipoprecio").value+"&descuento="+$$("pdescuento").value
	+"&recargo="+$$("precargo").value+"&caja="+$$("caja").value+"&nombrenit="+$$U("nombrenit")+"&nit="+$$U("documento")
	+"&subtotal="+subtotal+"&cambio="+$$("cambio").value+"&fechaentrega=" + $$("fechaentrega").value;  	
    enviarDetalle(filtro);
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
  	   vector[4]=$$('detalleT').rows[i].cells[6].innerHTML;		 
	   vector[5]=$$('detalleT').rows[i].cells[7].innerHTML;		
	   vector[6]=$$('detalleT').rows[i].cells[8].innerHTML;	
	   vector[7]=$$('detalleT').rows[i].cells[9].innerHTML;	   	   
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
			enviarDireccion();  
		  }
	   }	   
   }
   pedido.send(null);   	
}

function enviarDireccion(){
  location.href = "nuevo_notaventa.php";	
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
   window.open('factura/imprimir_notaventa.php?idnotaventa='+idUTransaccion+'&logo='+$$("logo").checked,'target:_blank');
   enviarDireccion();	
}

var eventoIngresoCantidad = function(evento){
	var tecla = (document.all) ? evento.keyCode : evento.which;		
	if (tecla == 13)
	agrega_celda();	
}

 var eventoResultado = function(resultado,codigo){	     
     consultarServidor(codigo,cargarCantidad); 
     $$("dato").value =resultado;
	 $$("codidproducto").value = codigo; 	 
	 $$("overlay").style.visibility = "visible";
	 $$('gif').style.visibility = "visible";
	 $$("msjsubnumero").style.visibility = "hidden"; 
	 $$("cant").value="";  	
 }
 
 
 var cargarCantidad = function(unidadM,unidadA,conversiones,precio,lotes,itemProductos){
	 $$("precioProducto").value = precio;
	 $$("cantUM").value ="0.00";
	 $$("cant").value = "";
	 $$("ptotal").value ="0.00";
	 $$("punidadmedida").value = unidadM;
	 $$("punidadalternativa").value = unidadA;
	 $$("pconversiones").value = conversiones;
	 
	 if ($$("moneda").value == "Dolares"){
		precio = parseFloat(precio / $$("tipoCambioBs").value).toFixed(4); 		 
	 } 
	 
	 $$("ppreciounitario").value = precio;
	 var ppreciound = precio / conversiones;
	 $$("ppreciounitarioalternativa").value = ppreciound.toFixed(4); 
	 $$("idLotes").innerHTML = lotes;
	 $$("detalleProdDisponible").innerHTML = itemProductos;
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
 
 function subVentanaValida(){
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
 
 var agrega_celda = function(){	
   if (subVentanaValida()){
     var pCantidad = 0;
     var pprecio = 0;
	 var pUndMedida = "";
	 var selector = obtenerSeleccionRadio("form1","pselectorU");
	 var fila = parseInt($$("idLotes").value);	 
	 $$("cambio").value = 0;
	 if (selector == "UP"){
		pCantidad = $$("cant").value;
		pUndMedida = $$("punidadmedida").value;
		pprecio = parseFloat($$("ppreciounitario").value);
	 }else{
		pCantidad = $$("cantUM").value;
		pUndMedida = $$("punidadalternativa").value; 
		pprecio = parseFloat($$("ppreciounitarioalternativa").value);
	 }
	 	 
	 var datos = new Array();
	 precio = pprecio;
     datos[0] = new Array("<img src='css/images/borrar.gif' title='Eliminar' alt='borrar' onclick='eliminarFila(this)' />","center");
	 datos[1] = new Array($$("codidproducto").value,"center");
	 datos[2] = new Array($$("dato").value,"left");
	 datos[3] = new Array($$("detalleProdDisponible").rows[fila].cells[1].innerHTML,"left","none");
 	 datos[4] = new Array($$("detalleProdDisponible").rows[fila].cells[0].innerHTML,"center");
	 datos[5] = new Array(pCantidad,"center");
	 datos[6] = new Array(pUndMedida,"center");
	 datos[7] = new Array(convertirFormatoNumber(precio.toFixed(4)),"center");
	 total = parseFloat($$("ptotal").value);
     datos[8] = new Array(convertirFormatoNumber(total.toFixed(4)),"center");
	 datos[9] = [$$("detalleProdDisponible").rows[fila].cells[5].innerHTML,"center","none"];
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
	 $$("subtotal").value = total_bolivianos.toFixed(4); 
	 calcularDescuento();
     calcularRecargo();
	 calcularTotales($$("moneda").value);
	 calcularCambio();
 }
 
 function calcularDescuento(){	 
	var descuento = ($$("pdescuento").value == "") ? 0 : $$("pdescuento").value; 
	descuento = parseFloat(descuento / 100) * total_bolivianos;
	$$("descuento").value = descuento.toFixed(2);
	calcularTotales($$("moneda").value);
	calcularCambio();
 }
  
 function calcularRecargo(){
   var recargo = ($$("precargo").value == "") ? 0 : $$("precargo").value; 
	recargo = parseFloat(recargo / 100) * total_bolivianos;
	$$("recargo").value = recargo.toFixed(2);	
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
   var disponibleA = $$("detalleProdDisponible").rows[fila].cells[4].innerHTML;
   if ($$("detalleProdDisponible").rows[fila].cells[3].innerHTML == $$("punidadmedida").value){
	 cant = ($$("cant").value == "") ? 0 : parseFloat($$("cant").value);  
	 if (cant <= disponibleA){
	  cantidad = parseFloat($$("detalleProdDisponible").rows[fila].cells[4].innerHTML) - cant;	
	  $$("detalleProdDisponible").rows[fila].cells[2].innerHTML = cantidad.toFixed(2);  
	  $$("msjsubnumero").style.visibility = "hidden";  
	 }else{
		setAlmacenInsuficiente(); 
	 }	 
   }else{
	 cant = ($$("cantUM").value == "") ? 0 :  parseFloat($$("cantUM").value); 
	 if (cant <= disponibleA){
      cantidad = parseFloat($$("detalleProdDisponible").rows[fila].cells[4].innerHTML) - cant;	
      $$("detalleProdDisponible").rows[fila].cells[2].innerHTML = cantidad.toFixed(2);   
	 }else{
	   $$("msjsubnumero").style.visibility = "hidden"; 	 
	   setAlmacenInsuficiente();	 
	 }
   }	
}
 
 var setAlmacenInsuficiente = function(){
  $$("ptotal").value = 0;	
  $$("cant").value = 0;
  $$("cantUM").value = 0;  
  $$("msjsubnumero").style.visibility = "visible";
  $$("msjsubnumero").innerHTML = "Almacén Insuficiente."; 	
}

 
 function calcularUnidadMedida(cantidad,tipoUnidad){	     
	  if (tipoUnidad == "precioUA"){
		  $$("ppreciounitario").value = parseFloat($$("pconversiones").value * cantidad).toFixed(4);
		  cantidad = $$("cant").value;	
	  }	  
	  if (tipoUnidad == "precioU"){
		  $$("ppreciounitario").value = cantidad;
		  var ppreciound = cantidad / $$("pconversiones").value;
		  cantidad = $$("cant").value;		   
		   $$("ppreciounitarioalternativa").value = ppreciound.toFixed(4); 
	  }	  
	  if (tipoUnidad == "UM" || tipoUnidad == "precioU" || tipoUnidad == "precioUA"){
		 var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value; 
		 pprod = pprod * cantidad;
		 $$("ptotal").value =  pprod.toFixed(4);
		 cantUm = parseFloat(cantidad * $$("pconversiones").value);
		 $$("cantUM").value = cantUm.toFixed(2);
	  }
	  if (tipoUnidad == "UA"){
		var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value;  
		pprod = pprod * (cantidad / $$("pconversiones").value);
		$$("ptotal").value =  pprod.toFixed(4);  
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
  
    var consultorAutocompletar = function(evento,id,value){
	var idalmacen = $$("idalmacen").value;  
	if (idalmacen != ""){
 	var consulta = "select distinct p.idproducto,p.nombre from ingresoproducto i,almacen a,producto p,detalleingresoproducto di where i.idingresoprod=di.idingresoprod and i.estado=1 and di.cantidadactual>0 and di.estado=1 and di.idproducto=p.idproducto and i.idalmacen="
	+idalmacen+" and p.nombre like '"+value+"%' limit 9;";  
	eventoTeclas(evento,id,'resultados','producto','nombre','idproducto','eventoResultado','autocompletar/consultor.php'
	,consulta,'<sinfiltro>','autoL1');  
	}else{
	  openMensaje("Advertencia",'Debe seleccionar un Almacén');	
	}
  }