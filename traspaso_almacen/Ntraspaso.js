// JavaScript Document

var total_bolivianos = 0;
var total_Dolares = 0;
var idUTransaccion = 0;
var transaccion = "insertar";
var servidor = "traspaso_almacen/DTraspaso.php";
var irDireccion = "listar_traspaso.php#t5";
var dirLocal = "nuevo_traspaso.php#t5";

 var $$ = function(id){
  return document.getElementById(id);	 
 }

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
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
 
 
   var tipoBusqueda = function(e,id){
	   var sql;
	   tipocliente = $$('receptor').value;
	   if (tipocliente != "otros") { 
		switch(tipocliente){	
		 case 'cliente':
		   sql = "select idcliente,left(nombre,25) as 'nombre' from cliente where estado=1 and  ";
		 break;
		}		
	    eventoTeclas(e,id,'cliente',tipocliente,'nombre',"idcliente",'eventoResultadoCliente'
		,'autocompletar/consultor.php',sql,'','autoL2');
	   }
   }
   
   var eventoResultadoCliente = function(resultado,codigo){
	 $$("solicitado").value= resultado;
	 $$("idcliente").value = codigo;	   
 }
  
 
 
 var consultorAutocompletar = function(evento,id,value){
	var idalmacen = $$("almacenorigen").value;  	
	var cadena = new String(value);
	cadena = cadena.trim();

	if (cadena != ""){
	if (idalmacen != ""){
 	var consulta = "select distinct p.idproducto,p.nombre from ingresoproducto " 
	+ " i,almacen a,producto p,detalleingresoproducto di where "   
	+ "i.idingresoprod=di.idingresoprod and i.estado=1 and di.cantidadactual>0 "
	+ "and di.estado=1 and di.idproducto=p.idproducto and i.idalmacen="+idalmacen
	+" and p.nombre like '"+value+"%' limit 9;";  
	eventoTeclas(evento,id,'resultados','producto','nombre','idproducto','eventoResultado','autocompletar/consultor.php'
	,consulta,'<sinfiltro>','autoL1');  
	} else {
	  openMensaje("Advertencia",'Debe seleccionar un Almacén');	
	  return;
	}
	} else {
	  $$("resultados").style.visibility = "hidden";	
	}
  }
 

function consultar(parametros,funcion){
 var  pedido = ajax();	
 filtro ="transaccion=consulta&codigo="+parametros+"&idalmacen="+$$("almacenorigen").value; 
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText.split("---");    
    	  funcion(resultado[0],resultado[1],resultado[2],resultado[3],resultado[4],resultado[5],resultado[6]);   
	   }	   
   }
   pedido.send(null);
}


var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}


var enviarMaestro = function(){
  if (esvalido()) {	
    $$('overlay').style.visibility = "visible";
	$$('gif').style.visibility = "visible";
    montototal = desconvertirFormatoNumber($$("subtotalBS").value);  
    filtro ="fecha="+$$("fecha").value+"&almacenorigen="+$$('almacenorigen').value+"&almacendestino="+$$('almacendestino').value+
    "&moneda="+$$('moneda').value+"&glosa="+$$U("glosa")+"&monto="+montototal+"&transaccion="+transaccion+"&idregistro="
	+$$("idRegistro").value+"&tc="+$$('tipoCambioBs').value+"&solicitado="+$$U("solicitado")
	+"&receptor="+$$('receptor').value+"&idcliente="+$$("idcliente").value;  
    enviarDetalle(filtro);
  } else {
	mostrarMensajeError();  
  }
}

var limpiarSolicitante = function()
{
    $$("solicitado").value = "";
    $$("idcliente").value = "";	
}

function mostrarMensajeError(){
  if (!validarFecha($$("fecha").value)){
	openMensaje("Advertencia",'Ingrese una fecha valida.');
    return false;  
  } 	
  if ($$('almacenorigen').value == "0"){
    openMensaje("Advertencia","Debe seleccionar un almacén de origen.");
    return;
  }
  if ($$('almacendestino').value == "0"){
    openMensaje("Advertencia",'Debe seleccionar un almacén de destino.');
    return;
  }
  if ($$('detalleSolicitud').rows.length == 0){
    openMensaje("Advertencia",'Debe ingresar detalle del Traspaso.');
  } 	
  if ($$("receptor").value == "cliente" && $$("idcliente").value == "") {
	  openMensaje("Advertencia",'Debe seleccionar un cliente.');  
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

function accionPostRegistro(){
   window.open('traspaso_almacen/imprimir_traspaso.php?idtraspaso='+idUTransaccion+'&logo='+$$("logo").checked
   +'&cprecios='+$$("Mprecios").checked,'target:_blank');	
   cerrarPagina();
}

function accion(){
	 $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
	 $$("msjsubnumero").style.visibility = "hidden";
	 $$("dato").value = "";
	 $$("dato").focus();	 
}

function esvalido(){
  return ($$('almacenorigen').value != "0" && $$('almacendestino').value != "0"
   && $$('detalleSolicitud').rows.length > 0 && validarFecha($$("fecha").value) 
   && (($$("receptor").value == "cliente" && $$("idcliente").value != "") || $$("receptor").value == "otros")); 	
}

var eventoIngresoCantidad = function(evento){
	var tecla = (document.all) ? evento.keyCode : evento.which;		
	if (tecla == 13)
	agrega_celda();	
}

var enviarDetalle = function(filtro) {		
     nfilas = $$('detalleSolicitud').rows.length;    	
     json = new Array();
     for(i=0;i<nfilas;i++) {
	   vector = new Array();
	   vector[0]=$$('detalleSolicitud').rows[i].cells[1].innerHTML;		
	   vector[1]=$$('detalleSolicitud').rows[i].cells[3].innerHTML;
	   vector[2]=$$('detalleSolicitud').rows[i].cells[4].innerHTML;								
	   vector[3]=$$('detalleSolicitud').rows[i].cells[5].innerHTML;	
	   vector[4]=$$('detalleSolicitud').rows[i].cells[6].innerHTML;	
	   vector[5]=$$('detalleSolicitud').rows[i].cells[7].innerHTML;	
	   vector[6]=$$('detalleSolicitud').rows[i].cells[8].innerHTML;	
	   vector[7]=$$('detalleSolicitud').rows[i].cells[9].innerHTML;		
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);	 
     filtro = filtro + '&detalle=' + dato; 
     enviar(filtro);
  }


 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }

 var eventoResultado = function(resultado,codigo){	     
     consultar(codigo,cargarCantidad); 
     $$("dato").value =resultado;
	 $$("codidproducto").value = codigo; 
	 $$('gif').style.visibility = "visible";	 
	 $$("overlay").style.visibility = "visible";
	 $$("msjsubnumero").style.visibility = "hidden";
	 $$("cant").value="";      		
 }
 
 
  function calcularUnidadMedida(cantidad,tipoUnidad){	     
	  if (tipoUnidad == "precioUA"){
		  $$("ppreciounitario").value = parseFloat($$("pconversiones").value * cantidad).toFixed(2);
		  cantidad = $$("cant").value;	
	  }	  
	  if (tipoUnidad == "precioU"){
		  var ppreciound = cantidad / $$("pconversiones").value;
		  cantidad = $$("cant").value;		   
		   $$("ppreciounitarioalternativa").value = ppreciound.toFixed(4); 
	  }	  
	  if (tipoUnidad == "UM" || tipoUnidad == "precioU" || tipoUnidad == "precioUA"){
		 var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value; 
		 pprod = pprod * cantidad;
		 $$("ptotal").value =  pprod.toFixed(4);
		 cantUm = parseFloat(cantidad * $$("pconversiones").value);
		 $$("cantUM").value = cantUm.toFixed(4);
	  }
	  if (tipoUnidad == "UA"){
		var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value;  
		pprod = pprod * (cantidad / $$("pconversiones").value);
		$$("ptotal").value =  pprod.toFixed(4);  
		 cantUm = parseFloat(cantidad / $$("pconversiones").value);
		 $$("cant").value = cantUm.toFixed(4);
	  }
	  realizarDescuento(); 
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
	 $$("msjsubnumero").style.visibility = "hidden"; 
	  }else{
		setAlamacenInsuficiente();  
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
 
  var cargarCantidad = function(unidadM,unidadA,conversiones,precio,lotes,itemProductos,itemprecios){
	 $$("precioProducto").value = precio;
	 $$("cantUM").value ="0.00";
	 $$("cant").value ="";
	 $$("ptotal").value ="0.00";
	 $$("punidadmedida").value = unidadM;
	 $$("punidadalternativa").value = unidadA;
	 $$("pconversiones").value = conversiones;
	 $$("ppreciounitario").innerHTML = itemprecios;
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
	setTotal(-parseFloat(desconvertirFormatoNumber(tr.cells[8].innerHTML)),$$("moneda").value);
    var table = tr.parentNode;
    table.removeChild(tr);
 }
 
 function validarSubVentana(){	
	if (!isvalidoNumero("cant") || !isvalidoNumero("cantUM")){
	  $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "La cantidad ingresada es Incorrecta"; 
	  return false;		
	}		
	
	if(parseFloat($$("ptotal").value) <= 0){
	  $$("msjsubnumero").style.visibility = "visible";
  	  $$("msjsubnumero").innerHTML = "Debe ingresar una cantidad mayor a cero"; 
	  return false;	
	}
	$$("msjsubnumero").style.visibility = "hidden";
	return true;
 } 
 
 var agrega_celda = function(){	
  if (validarSubVentana()){
     var pCantidad = 0;
     var pprecio = 0;
	 var pUndMedida = "";
	 var selector = obtenerSeleccionRadio("form1","pselectorU");
	 var fila = parseInt($$("idLotes").value);
	 
	 
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
     datos[0] = ["<img src='css/images/borrar.gif' title='Eliminar' alt='borrar' onclick='eliminarFila(this)' />","center"];
	 datos[1] = [$$("codidproducto").value,"center"];
	 datos[2] = new Array($$("dato").value,"left");
	 datos[3] = new Array($$("detalleProdDisponible").rows[fila].cells[5].innerHTML,"left","none");
 	 datos[4] = new Array($$("detalleProdDisponible").rows[fila].cells[0].innerHTML,"center");
	 datos[5] = new Array(pCantidad,"center");
	 datos[6] = new Array(pUndMedida,"center");
	 datos[7] = new Array(convertirFormatoNumber(precio.toFixed(4)),"center");
	 total = parseFloat($$("ptotal").value);
     datos[8] = new Array(convertirFormatoNumber(total.toFixed(4)),"center");
	 datos[9] = new Array($$("detalleProdDisponible").rows[fila].cells[1].innerHTML,"left","none");
	 insertarFila(datos,'detalleSolicitud');
	 setTotal(total,$$("moneda").value);
     $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
     $$("dato").value=""; 
     document.form1.dato.focus();   
  }
}
 
 function setTotal(total,moneda){	 
	 if (moneda == "Bolivianos"){
	   total_bolivianos = total_bolivianos + total;
	 }
	 
	 if (moneda == "Dolares"){
		total = total * ($$("tipoCambioBs").value);
		total_bolivianos = total_bolivianos + total;
	 }
	 
     total_Dolares = total_bolivianos / ($$("tipoCambioBs").value);
	 $$("subtotalBS").value = convertirFormatoNumber(total_bolivianos.toFixed(2));
	 $$("subtotalDL").value = convertirFormatoNumber(total_Dolares.toFixed(2));
 }
 
  
 function limpiarDetalle(){
	  $$("detalleSolicitud").innerHTML = "";   
	  setTotal(-total_bolivianos,"Bolivianos");  
 }
 
 
 function salir(){
   if ($$('detalleSolicitud').rows.length>0){
	if (confirm("Cuenta con detalles de Traspaso desea Salir ?"))
	 location.href = irDireccion;  
   }
   else{
	 location.href = irDireccion;  
   }
}

var quitarCombo = function(combo,opcion){	
   if (opcion == "0")
    return; 
	 var cb = document.getElementById(combo);   
	 for (var i=0;i<cb.length;i++){
		if (cb[i].value == opcion){
		cb[i].style.display = "none";
		}
		else{
		  if (cb[i].value != "0")
		   cb[i].style.display = "block";		 
		}
	 }	 
 }
 
 function cerrarPagina(){
	location.href = "nuevo_traspaso.php";	 
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