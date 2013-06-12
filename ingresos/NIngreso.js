// JavaScript Document
 var irDireccion = "listar_ingreso.php#t1";
 var transaccion = 'insertar';
 var servidorT = "ingresos/DIngreso.php";
 var totalTransaccion = { bolivianos: 0,dolares: 0};
 var codigoTransaccion = 0;
 var dirLocal = "nuevo_ingreso.php";
 var pagosPendientes = { transaccion: '',codigoCuenta:'',cuenta:'',idTransaccion:'' };


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
 
 var selectorPago = function(t){
	var td = t.parentNode;
    var tr = td.parentNode;
	$$("descripcionPago").value = tr.cells[5].innerHTML;
	$$("bolivianosPago").value = desconvertirFormatoNumber(tr.cells[6].innerHTML);
	$$("dolaresPago").value = 0;
	pagosPendientes.codigoCuenta =  tr.cells[4].innerHTML;
	pagosPendientes.cuenta =  tr.cells[2].innerHTML;
	pagosPendientes.idTransaccion = tr.cells[8].innerHTML;
	pagosPendientes.transaccion = tr.cells[3].innerHTML;
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
		   sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
		 break;
		 case 'proveedor':
		   sql = "select idproveedor,left(nombre,20) as 'nombre' from proveedor where estado=1 and ";
		 break;
	  }		
	  eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultadoEgreso','autocompletar/consultor.php',sql,'','autoL1');
	 }
 }


 var insertarNewItem = function(tabladestino){
   $$("bolivianosD").value = ($$("bolivianosD").value == "")? 0 : $$("bolivianosD").value;
   $$("dolaresD").value = ($$("dolaresD").value == "")? 0 : $$("dolaresD").value; 
   
   var dolaresD = parseFloat($$("dolaresD").value).toFixed(4);
   if (validarSubIngreso()) {
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("cuentaD").selectedIndex; 
    var texto = $$("cuentaD").options[indice].text;
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{id :"cuentaD", type:"get" },
	{data :texto  , type:"set"},
	{id :"descripcionD", type:"get"},
	{id :"bolivianosD", type:"get"},
	{data :dolaresD, type:"set"},
	{data :"Ingreso Dinero", type:"set" },
	{data :"0", type:"set" }
	];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0],total[1]);
   }
    $$("bolivianosD").value = "";
	$$("dolaresD").value = "";
 }

 var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left' , display:'none' },
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left' , display:'none' },
	{type : 'normal', numerico : 'no', aling : 'left' , display:'none' }	
	];
	return formato;	
 }

 var cargarTotales = function(bolivianos,dolares){
   	totalTransaccion.bolivianos = parseFloat(totalTransaccion.bolivianos) + parseFloat(bolivianos);
	totalTransaccion.dolares = parseFloat(totalTransaccion.dolares) + parseFloat(dolares);
	$$("totalbs").value = convertirFormatoNumber(parseFloat(totalTransaccion.bolivianos).toFixed(2));
	$$("totaldolares").value = convertirFormatoNumber(parseFloat(totalTransaccion.dolares).toFixed(2));
	$$("descripcionD").focus();	
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
      var filtro = "transaccion=pendientes&iddeudor="+$$("idpersonarecibida").value+"&receptor="+$$("receptor").value;
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
	$$("descripcionPago").value = "";  
	$$("bolivianosPago").value = ""; 
	$$("dolaresPago").value = ""; 
	$$("subventana").style.visibility = "visible";  
	$$("overlay").style.visibility = "visible";
  }else{
	openMensaje("Advertencia","No Tiene Cuentas Pendientes.");   
  }
 }

 function consultarCuenta(tipo){
  var filtro = "transaccion=cuenta&tipo="+tipo;
  enviar(filtro,setDatosCuenta);  	
 }


 function setDatosCuenta(result){
  $$("cuenta").innerHTML = result;	
 }

 function cerrarSubVentana(){
	$$("subventana").style.visibility = "hidden";  
	$$("overlay").style.visibility = "hidden";
 }

 function cargarCuentaSeleccionada(){
  insertarNewItemPagados("detalleTransaccion");
  cerrarSubVentana();
 }


var insertarNewItemPagados = function(tabladestino){
   $$("bolivianosPago").value = ($$("bolivianosPago").value == "")? 0 : $$("bolivianosPago").value;
   $$("dolaresPago").value = ($$("dolaresPago").value == "")? 0 : $$("dolaresPago").value; 
   
   var dolaresD = parseFloat($$("dolaresPago").value).toFixed(4);

	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("cuentaD").selectedIndex; 
    var texto = $$("cuentaD").options[indice].text;
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{data :pagosPendientes.codigoCuenta, type:"set" },
	{data :pagosPendientes.cuenta  , type:"set"},
	{id :"descripcionPago", type:"get"},
	{id :"bolivianosPago", type:"get"},
	{data :dolaresD, type:"set"},
	{data :pagosPendientes.transaccion, type:"set" },
	{data :pagosPendientes.idTransaccion, type:"set" }
	];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0],total[1]);

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
	openMensaje("Advertencia","Debe Ingresar Detalle del Igreso");  
    return false;
  }
  
  return true;
 }

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
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
		  $$('detalleTransaccion').rows[i].cells[4].innerHTML,	
		  $$('detalleTransaccion').rows[i].cells[5].innerHTML,	
		  $$('detalleTransaccion').rows[i].cells[6].innerHTML,
		  $$('detalleTransaccion').rows[i].cells[7].innerHTML,
		  $$('detalleTransaccion').rows[i].cells[8].innerHTML		  
		  ];		   	
		 json[i] = vector;	 		
     }
     dato = JSON.stringify(json);     	
	
  	var filtro = "transaccion="+transaccion+"&tipopersona="+$$U("receptor")+"&idpersona="+$$("idpersonarecibida").value
	+"&cuenta="+$$("cuenta").value+"&recibo="+$$("recibo").value+"&glosa="+$$U("glosa")+"&sucursal="+$$("sucursal").value
	+"&fecha="+$$("fecha").value+'&detalle='+encodeURIComponent(dato)+"&idingreso="+$$("idTransaccion").value+
	"&ingresobs="+totalTransaccion.bolivianos+"&ingresoDolares="+totalTransaccion.dolares+"&cheque="+$$("cheque").value
	+"&nombrepersona="+$$("texto").value+"&tipocambio="+$$("tipoCambioBs").value;
	enviar(filtro,respuestaEjecutarT);
	 }
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
   window.open('ingresos/imprimir_ingreso.php?idingreso='+codigoTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
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
  
