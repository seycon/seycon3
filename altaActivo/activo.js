// JavaScript Document
var servidorT = "altaActivo/Dactivo.php";
var transaccion = "insertar";
var irDireccion = "nuevo_activo.php";
var datosFactura ={dia :"", nit: "" ,razonsocial:"",numfactura:"", numeroautorizacion:"",importetotal:"",ice:"",excento:"",neto:"",iva:"",codigocontrol:"" };

	
document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115){ //F4
    if ($$("cancelar") != null)
	 $$("cancelar").click();
   }
	
   if(tecla == 113){ //F2
    if($$('overlay').style.visibility != "visible") 
	 $$("enviar").click();	  
	}
}

function enviar(filtro,funcion){
  var  pedido = ajax();	  
  pedido.open("GET",servidorT+"?"+filtro,true);
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


function calcularTotal(){
  var cantidad = ($$("cantidad").value == "") ? 0 : $$("cantidad").value;  
  var precio = ($$("precio").value == "") ? 0 : $$("precio").value;  
  total = cantidad * precio;
  $$("total").value = total.toFixed(2);
}


var cambiarTipoCuenta = function(tipo){
 if (tipo != ""){	
  if (tipo == "caja"){	
   $$("textoCuenta").innerHTML = "Caja/Banco<span class='rojo'>*</span>:";
  }else{
   $$("textoCuenta").innerHTML = "Cuenta Contable<span class='rojo'>*</span>:";
  }
  $$("cuenta").innerHTML = $$(tipo).innerHTML;  
 }
}

function $$(id){
  return document.getElementById(id);  
}

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

var insertarNewFactura = function(){
   if (datosCompletos()){       
    datosFactura.dia = $$("diaF").value;
    datosFactura.nit = $$("nitF").value;
    datosFactura.razonsocial = $$("razonsocialF").value;
    datosFactura.numfactura = $$("numerofactura").value;
    datosFactura.numeroautorizacion = $$("numeroautorizacionF").value;
    datosFactura.importetotal = $$("importetotalF").value;
    datosFactura.ice = $$("iceF").value;
    datosFactura.excento = $$("excentoF").value;
    datosFactura.neto = $$("netoF").value;
    datosFactura.iva = $$("ivaF").value;
    datosFactura.codigocontrol = $$("codigocontrolF").value;  	
	cerrarSubVentana();
  }
}


var cargarFactura = function(){
	if (datosFactura.dia == "") {
	   var fech = $$("fechacompra").value.split("/");	
	   datosFactura.dia = fech[0];
	}
	$$("diaF").value = datosFactura.dia;
    $$("nitF").value = datosFactura.nit;
    $$("razonsocialF").value = datosFactura.razonsocial;
    $$("numerofactura").value = datosFactura.numfactura;
    $$("numeroautorizacionF").value = datosFactura.numeroautorizacion;
    $$("importetotalF").value = datosFactura.importetotal;
    $$("iceF").value = datosFactura.ice;
    $$("excentoF").value = datosFactura.excento;
    $$("netoF").value = datosFactura.neto;
    $$("ivaF").value = datosFactura.iva;
    $$("codigocontrolF").value = datosFactura.codigocontrol;  
}


var datosValidos = function(){
  if ($$("nombre").value == ""){	
    openMensaje("Advertencia","Debe ingresar el nombre del activo.");
	return false;
  }
  
  if ($$("cuenta").value == "" ){
	openMensaje("Advertencia","Debe seleccionar una cuenta.");
	return false;  
  }
  
  if ($$("idsucursal").value == ""){
	openMensaje("Advertencia","Debe seleccionar la sucursal.");  
    return false;
  }
  
  if ($$("idtipoactivo").value == ""){
	openMensaje("Advertencia","Debe seleccionar el tipo activo.");  
    return false;
  }
    
  if (!isvalidoNumero("cantidad")){
	openMensaje("Advertencia","La cantidad ingresada es incorrecta.");  
    return false;  
  }
  
  if (!isvalidoNumero("precio")){
	openMensaje("Advertencia","El precio ingresado es incorrecto.");  
    return false;  
  }
  
  if ($$("cantidad").value == ""){
	openMensaje("Advertencia","Debe Ingresar la cantidad.");  
    return false;
  }
  if ($$("precio").value == ""){
	openMensaje("Advertencia","Debe ingresar el precio.");  
    return false;
  }
  
  return true;
}


 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }



 var datosCompletos = function(){
	 $$("numeroautorizacionF").value = ($$("numeroautorizacionF").value == "") ? "0" : $$("numeroautorizacionF").value;
	 $$("importetotalF").value = ($$("importetotalF").value == "") ? "0" : $$("importetotalF").value;
 	 $$("iceF").value = ($$("iceF").value == "") ? "0" : $$("iceF").value;
 	 $$("excentoF").value = ($$("excentoF").value == "") ? "0" : $$("excentoF").value;
	 $$("netoF").value = ($$("netoF").value == "") ? "0" : $$("netoF").value;
	 $$("ivaF").value = ($$("ivaF").value == "") ? "0" : $$("ivaF").value;
	 $$("codigocontrolF").value = ($$("codigocontrolF").value == "") ? "0" : $$("codigocontrolF").value;
	 
	if ($$('diaF').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Dia");
		return false;
	 }
	 if ($$('nitF').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Nit");
		return false;
	}
	if ($$('razonsocialF').value == ''){
		openMensaje("Advertencia","Debe ingresar el Nombre o Razon Social");
		return false;
	}	 
	if ($$('numerofactura').value == ''){
		openMensaje("Advertencia","Debe Ingresar el numero de factura");
		return false;
	}	
	if (parseFloat($$('diaF').value) > 31){
		openMensaje("Advertencia","El Dia, esta Fuera de rango");
		return false;
	}	
	return true; 
 }

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

function ejecutarTransaccion(){
  if (datosValidos()){
	$$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible"; 
	factura = JSON.stringify(datosFactura);	
	var tipocuenta = obtenerSeleccionRadio("formValidado","tipocuenta");
 	var filtro = "transaccion="+transaccion+"&nombre="+$$U("nombre")+"&idtipoactivo="+$$("idtipoactivo").value
	+"&idtrabajador="+$$("idtrabajador").value+"&idsucursal="+$$("idsucursal").value+"&detalle="+$$U("detalle")
	+"&cuenta="+$$("cuenta").value
	+"&fechacompra="+$$("fechacompra").value+'&cantidad='+$$("cantidad").value+"&precio="+$$("precio").value+
	"&ubicacion="+$$U("ubicacion")+"&tipocuenta="+tipocuenta+"&idactivo="+$$("idactivo").value
	+"&factura="+factura;		
	enviar(filtro,respuestaEjecutarT);
  }
}

var obtenerSeleccionRadio = function(formulario,radio){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i< form.length;i++){
	if (form[i].checked==true)
  	  return form[i].value;   
	}	
}

var respuestaEjecutarT = function(){
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


var cambiarFoco = function(evt){
   var tecla = (document.all) ? evt.keyCode : evt.which;
   var n = $$("diaF").value.length;
    if (tecla == 8){
     return true;  
	}    
    if (n == 1){
     $$("nitF").focus();
     return true;
    }
    if (n >= 2){
      $$("nitF").focus();
      return false;
    }
    return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
}

function recuperaNombre(nit, pagina){
   peticion = ajax();   
   peticion.open('GET', pagina+'?nit='+nit+'&transaccion=nit', true); 
   peticion.onreadystatechange = function() { 	
      if (peticion.readyState == 4) { 
        if (peticion.responseText.length == '3') {$$('razonsocialF').focus(); return}
	      m = peticion.responseText.split('---');
	      $$('razonsocialF').value = m[0];
		  $$('numeroautorizacionF').value = m[1];
		  $$('numerofactura').focus();
	    } 
	  } 
  peticion.send(null);
}

function d(numero){
  resultado = Math.round(numero*100) / 100; 
  return resultado;
}

function calculaNeto(){
   $$('netoF').value = $$('importetotalF').value - $$('iceF').value - $$('excentoF').value; 
   $$('ivaF').focus();
}

function calculaIva(){
   $$('ivaF').value = d($$('importetotalF').value*0.13);
   $$('codigocontrolF').focus();
}

var openSubVentana = function(){	
    cargarFactura();
	$$("subventana").style.visibility = "visible";
	$$("overlays").style.visibility = "visible";
	$$('diaF').focus();
}


var cerrarSubVentana = function(){
   $$("subventana").style.visibility = "hidden";
   $$("overlays").style.visibility = "hidden";
}