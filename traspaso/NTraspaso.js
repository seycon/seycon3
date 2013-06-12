// JavaScript Document
var irDireccion = "listar_traspasoDinero.php#t8";
var transaccion = 'insertar';
var servidorT = "traspaso/DTraspaso.php";
var totalTransaccion = { bolivianos: 0,dolares: 0};
var codigoTransaccion = 0;
var dirLocal = "nuevo_traspaso_dinero.php";



var $$ = function(id){
	return document.getElementById(id);
}

document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2
	if($$('overlay').style.visibility != "visible")
	   ejecutarTransaccion();
	break;
	case 115://F4
	  if ($$("cancelar") != null)
	   salir();
	break;
   }
 }


var salir = function(){
	location.href = irDireccion;
}

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
	cargarTotales(-desconvertirFormatoNumber(tr.cells[5].innerHTML),-desconvertirFormatoNumber(tr.cells[6].innerHTML));	
    table.removeChild(tr);
	orderNumeroItem();
}


var orderNumeroItem = function(){
	var n =  $$("detalleTransaccion").rows.length;
	for (var i=0;i<n ;i++){
		$$("detalleTransaccion").rows[i].cells[1].innerHTML = i+1;
	}
}

var eventoResultadoEgreso = function(resultado,codigo){
	  $$("texto").value= resultado;
	  $$("idpersonarecibida").value = codigo;	  	   
}
   
var cambiarDependencias = function(){
	  $$("texto").value = "";
	  $$("idpersonarecibida").value = "";
}

var cambiarTipoCuenta = function(texto){
  $$("textoTipoCuenta").innerHTML = texto+":";
  consultarCuenta(texto); 	
   if (texto == "Caja"){
 	$$("cheque").style.visibility = "hidden"; 
	$$("textoCheque").innerHTML = "";  
   }
   if (texto == "Banco"){
	$$("cheque").style.visibility = "visible";  
	$$("textoCheque").innerHTML = "Cheque:"; 
   }
  
}

var tipoBusqueda = function(e){
	   var sql;
	   tipocliente = $$('receptor').value;
	   if (tipocliente!="otros"){ 
	    idconsulta = "id"+tipocliente;   
		switch(tipocliente){
		  case 'trabajador':
		   sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t where estado=1 and  ";
		 break;	
		 case 'cliente':
		   sql = "select idcliente,nombre as 'nombre' from cliente where estado=1 and  ";
		 break;
		 case 'proveedor':
		   sql = "select idproveedor,nombre as 'nombre' from proveedor where estado=1 and ";
		 break;
		}		
	eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultadoEgreso','autocompletar/consultor.php',sql,'','autoL1');
	   }
}


var insertarNewItem = function(tabladestino){	
  if(validarSubIngreso()){	
    $$("bolivianosD").value = ($$("bolivianosD").value == "")? 0 : $$("bolivianosD").value;
    $$("dolaresD").value = ($$("dolaresD").value == "")? 0 : $$("dolaresD").value;  
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var nombre = $$("nombreTrabajadorD").value;
	var indice = $$("cuentaD").selectedIndex; 
	var texto = $$("cuentaD").options[indice].text;
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{id :"cuentaD", type:"get" },
	{data :texto, type:"set" },
	{id :"nombreTrabajadorD", type:"get"},
	{id :"bolivianosD", type:"get"},
	{id :"dolaresD", type:"get"},
	{id :"trabajadorD" , type:"get"}];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0],total[1]);
	$$("bolivianosD").focus();
	$$("nombreTrabajadorD").value = nombre;
  }
}

var cargarTotales = function(bolivianos,dolares){
   	totalTransaccion.bolivianos = parseFloat(totalTransaccion.bolivianos) + parseFloat(bolivianos);
	totalTransaccion.dolares = parseFloat(totalTransaccion.dolares) + parseFloat(dolares);
	$$("totalbs").value = convertirFormatoNumber(parseFloat(totalTransaccion.bolivianos).toFixed(2));
	$$("totaldolares").value = convertirFormatoNumber(parseFloat(totalTransaccion.dolares).toFixed(2));
}


var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center' , display:'none'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center' , display:'none'}	
	];
	return formato;	
}


function eventoText(evt){
var tecla = (document.all) ? evt.keyCode : evt.which;
  if (tecla == 13){
    insertarNewItem('detalleTransaccion');
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


function consultarPendientes(){
  if ($$("idpersonarecibida").value != "" && $$("receptor").value!="otros"){	
   var filtro = "transaccion=pendientes&iddeudor="+$$("idpersonarecibida").value+"&tipodeudor="+$$("receptor").value;
   enviar(filtro,setCuentasPendientes);	
  }
  else{
    	openMensaje("Advertencia","Debe Seleccionar una persona para realizar la Busqueda");  
  }
}

function setCuentasPendientes(result){
  var n;	
  $$("detallePendientes").innerHTML = result;	
  n = $$("detallePendientes").rows.length;
  if (n > 0){
	$$("subventana").style.visibility = "visible";  
	$$("overlay").style.visibility = "visible";
  }
}

function consultarCuenta(tipo){
  var filtro = "transaccion=cuenta&tipo="+tipo;
  enviar(filtro,setDatosCuenta);  	
}


function setDatosCuenta(result){
  $$("cuenta").innerHTML = result;	
}


function consultarCuentasTrabajador(){
   var filtro = "transaccion=cuentaTrabajador&idtrabajador="+$$("trabajadorD").value;
   enviar(filtro,setCuentasTrabajador);  	
}

function setCuentasTrabajador(result){
  var datos = result.split("---");
  $$("nombreTrabajadorD").value = datos[0];
  $$("cuentaD").innerHTML = datos[1];
 }


function cerrarSubVentana(){
	$$("subventana").style.visibility = "hidden";  
	$$("overlay").style.visibility = "hidden";
}

function cargarCuentaSeleccionada(){
 var pos = obtenerSeleccionRadio("formVentana","selectorCuenta");
 var codigo;
 var descripcion;
 if (!isNaN(pos)){
   codigo = $$("detallePendientes").rows[pos-1].cells[3].innerHTML;
   descripcion = $$("detallePendientes").rows[pos-1].cells[4].innerHTML;
   $$("descripcionD").value = descripcion;
   seleccionarCombo('cuentaD',codigo);
 }
 cerrarSubVentana();
}

var datosValidos = function(){
  if ($$("cuenta").value == ""){	
    openMensaje("Advertencia","Debe Seleccionar la cuenta Caja/Banco");
	return false;
  }
  
  if ($$("idpersonarecibida").value == "" && $$("receptor").value != "otros"){
	openMensaje("Advertencia","Debe Ingresar la persona a la cual se le asigna el Egreso");
	return false;  
  }
  
  if ($$("sucursal").value == ""){
	openMensaje("Advertencia","Debe Seleccionar la Sucursal");  
    return false;
  }
  
  if ($$("detalleTransaccion").rows.length < 1){
	openMensaje("Advertencia","Debe Ingresar Detalle del Egreso");  
    return false;
  }
  
  return true;
}

function ejecutarTransaccion(){	
	 nfilas = $$('detalleTransaccion').rows.length;    	
     json = new Array();
	 if (datosValidos()){
   	   $$('overlay').style.visibility = "visible";
       $$('gif').style.visibility = "visible";
     for(i=0; i<nfilas; i++) {
	   vector = [
	    $$('detalleTransaccion').rows[i].cells[2].innerHTML,								
	    $$('detalleTransaccion').rows[i].cells[5].innerHTML,	
	    $$('detalleTransaccion').rows[i].cells[6].innerHTML,	
	    $$('detalleTransaccion').rows[i].cells[7].innerHTML];		   	
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);     	
	
  	var filtro = "transaccion="+transaccion+"&tipopersona="+$$("receptor").value+"&idpersona="+$$("idpersonarecibida").value
	+"&cuenta="+$$("cuenta").value+"&recibo="+$$U("recibo")+"&glosa="+$$U("glosa")+"&sucursal="+$$("sucursal").value
	+"&fecha="+$$("fecha").value+'&detalle='+dato+"&idtransaccion="+$$("idTransaccion").value+
	"&ingresobs="+totalTransaccion.bolivianos+"&ingresoDolares="+totalTransaccion.dolares+"&cheque="+$$U("cheque")
	+"&nombrepersona="+$$("texto").value+"&tipocambio="+$$("tipoCambioBs").value;
	enviar(filtro,respuestaEjecutarT);
	 }
}


var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

var validarSubIngreso = function(){
	if (!isvalidoNumero("bolivianosD")){
	  openMensaje("Advertencia","El monto en bolivianos ingresado es incorrecto.");
	  return false;
	}
	if (!isvalidoNumero("dolaresD")){
	  openMensaje("Advertencia","El monto en dolares ingresado es incorrecto.");
	  return false;
	}

	return true; 
}

 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }


function respuestaEjecutarT(respuesta){
	datos = respuesta.split("---"); 
	if (datos[1] == "1"){
	  codigoTransaccion = datos[0];	
      $$('overlay').style.visibility = "visible";
      $$('modal_vendido').style.visibility = "visible";
	  $$('gif').style.visibility = "hidden"; 
	}else{
	  cerrarPagina();	
	}
}

 function cerrarPagina(){
	window.location = dirLocal;	 
 }

function accionPostRegistro(){
   window.open('traspaso/imprimir_traspaso.php?idtraspaso='+codigoTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
   cerrarPagina();
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