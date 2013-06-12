// JavaScript Document
var irDireccion = "listar_egreso.php#t2";
var transaccion = 'insertar';
var servidorT = "egresos/DEgreso.php";
var totalTransaccion = { bolivianos: 0,dolares: 0};
var codigoTransaccion = 0;
var dirLocal = "nuevo_egreso.php";
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

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
	cargarTotales(-desconvertirFormatoNumber(tr.cells[6].innerHTML),-desconvertirFormatoNumber(tr.cells[7].innerHTML));		
	var indice = tr.cells[1].innerHTML;
	if (indice != "-1")
	  $$("datosFacturas").rows[indice].cells[12].innerHTML = "-1";

    table.removeChild(tr);
	orderNumeroItem();
}


var orderNumeroItem = function(){
	var n =  $$("detalleTransaccion").rows.length;
	for (var i=0;i<n ;i++){
		$$("detalleTransaccion").rows[i].cells[2].innerHTML = i+1;
	}
}

var deleteFacturaInvalidas = function(){
	var n =  $$("datosFacturas").rows.length;
	for (var i=0;i<n ;i++){
		if ($$("datosFacturas").rows[i].cells[12].innerHTML == "-1"){
		 $$("datosFacturas").deleteRow(i);
		 n =  $$("datosFacturas").rows.length;
		 i--; 
		}
	}
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

function d(numero){
  resultado = Math.round(numero*100) / 100; 
  return resultado;
}

function calculaIva(){
   $$('ivaF').value = d($$('importetotalF').value*0.13);
   $$('codigocontrolF').focus();
}
  
function calculaNeto(){
   $$('netoF').value = $$('importetotalF').value - $$('iceF').value - $$('excentoF').value; 
   $$('ivaF').focus();
}


var eventoResultadoEgreso = function(resultado,codigo){
	  $$("texto").value= resultado;
	  $$("idpersonarecibida").value = codigo;	   
}
   
var cambiarDependencias = function(){
	  $$("texto").value = "";
	  $$("idpersonarecibida").value = "0";
}


var tipoBusqueda = function(e){
	   var sql;
	   tipocliente = $$('receptor').value;
	   if (tipocliente != "otros") { 
	    idconsulta = "id"+tipocliente;   
		switch(tipocliente){
		 case 'trabajador':
		   sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from "
		   + " trabajador t where estado=1 and  ";
		 break;	
		 case 'cliente':
		   sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
		 break;
		 case 'proveedor':
		   sql = "select idproveedor,left(nombre,20) as 'nombre' from proveedor where estado=1 and ";
		 break;
		}		
	  eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultadoEgreso'
	  ,'autocompletar/consultor.php',sql,'','autoL1');
	   }
}



var insertarNewItem = function(tabladestino){
   $$("bolivianosD").value = ($$("bolivianosD").value == "")? 0 : $$("bolivianosD").value;
   $$("dolaresD").value = ($$("dolaresD").value == "")? 0 : $$("dolaresD").value; 	
   if (validarSubIngreso()){	
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("cuentaD").selectedIndex; 
    var texto = $$("cuentaD").options[indice].text;
	var datosegreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :-1 , type:"set"},
	{data :n , type:"set"},
	{id :"cuentaD", type:"get" },
	{data :texto  , type:"set"},
	{id :"descripcionD", type:"get"},
	{id :"bolivianosD", type:"get"},
	{id :"dolaresD", type:"get"},
	{data:"<input type='button' style='width:60px;' class='botonseycon' value='Factura' onclick='mostrarVentanaFactura(this)'/>", type:"set"},
	{data:"-1", type:"set"},
	{data :"Egreso Dinero", type:"set" },
	{data :"0", type:"set" }	
	];
	var total = cargarDatos(formato,datosegreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0],total[1]);
   }
    $$("bolivianosD").value = "";
	$$("dolaresD").value = "";
}



var insertarNewItem = function(tabladestino){
   $$("bolivianosD").value = ($$("bolivianosD").value == "")? 0 : $$("bolivianosD").value;
   $$("dolaresD").value = ($$("dolaresD").value == "")? 0 : $$("dolaresD").value; 	
   if (validarSubIngreso()){	
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("cuentaD").selectedIndex; 
    var texto = $$("cuentaD").options[indice].text;
	var datosegreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :-1 , type:"set"},
	{data :n , type:"set"},
	{id :"cuentaD", type:"get" },
	{data :texto  , type:"set"},
	{id :"descripcionD", type:"get"},
	{id :"bolivianosD", type:"get"},
	{id :"dolaresD", type:"get"},
	{data:"<input type='button' style='width:60px;' class='botonNegro' value='Factura' onclick='mostrarVentanaFactura(this)'/>", type:"set"},
	{data:"-1", type:"set"},
	{data :"Egreso Dinero", type:"set" },
	{data :"0", type:"set" }	
	];
	var total = cargarDatos(formato,datosegreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0],total[1]);
   }
    $$("bolivianosD").value = "";
	$$("dolaresD").value = "";
}

 function cargarCuentaSeleccionada(){
  insertarNewItemPagados("detalleTransaccion");
  cerrarSubVentana2();
 }

var insertarNewItemPagados = function(tabladestino){
   $$("bolivianosPago").value = ($$("bolivianosPago").value == "")? 0 : $$("bolivianosPago").value;
   $$("dolaresPago").value = ($$("dolaresPago").value == "")? 0 : $$("dolaresPago").value;  	
   var dolaresD = parseFloat($$("dolaresPago").value).toFixed(4); 	
	
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("cuentaD").selectedIndex; 
    var texto = $$("cuentaD").options[indice].text;
	var datosegreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :-1 , type:"set"},
	{data :n , type:"set"},
	{data :pagosPendientes.codigoCuenta, type:"set" },
	{data :pagosPendientes.cuenta  , type:"set"},
	{id :"descripcionPago", type:"get"},
	{id :"bolivianosPago", type:"get"},
	{data :dolaresD, type:"set"},
	{data:"<input type='button' style='width:60px;' class='botonNegro' value='Factura' onclick='mostrarVentanaFactura(this)'/>", type:"set"},
	{data:"-1", type:"set"},
	{data :pagosPendientes.transaccion, type:"set" },
	{data :pagosPendientes.idTransaccion, type:"set"}	
	];
	var total = cargarDatos(formato,datosegreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0],total[1]);
  
    $$("bolivianosD").value = "";
	$$("dolaresD").value = "";
}

var cargarTotales = function(bolivianos,dolares){
   	totalTransaccion.bolivianos = parseFloat(totalTransaccion.bolivianos) + parseFloat(bolivianos);
	totalTransaccion.dolares = parseFloat(totalTransaccion.dolares) + parseFloat(dolares);
	$$("totalbs").value = convertirFormatoNumber(parseFloat(totalTransaccion.bolivianos).toFixed(2));
	$$("totaldolares").value = convertirFormatoNumber(parseFloat(totalTransaccion.dolares).toFixed(2));
	$$("descripcionD").focus();	
}

var mostrarVentanaFactura = function(t){
	limpiarDatosFactura();
	var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;		
	var posFactura = tr.cells[1].innerHTML;
	var fech = $$("fecha").value.split("/");
    $$("diaF").value = fech[0];
	if (posFactura != "-1"){
	  cargarDatosFactura(posFactura);	
	  $$("ItemF").value = posFactura;
	}else{
	  $$("ItemTabla").value = tr.cells[2].innerHTML - 1;
	  $$("ItemF").value = "-1";
	}


	$$("subventana").style.visibility = "visible";
	$$("overlay").style.visibility = "visible";
	$$("diaF").focus();
}


var limpiarDatosFactura = function(){
  	 $$("diaF").value = "";
	 $$("ItemF").value = "";
	 $$("nitF").value = "";
	 $$("razonsocialF").value = "";
	 $$("numerofactura").value = "";
	 $$("numeroautorizacionF").value = "";
	 $$("importetotalF").value = "";
	 $$("iceF").value = "";
	 $$("excentoF").value = "";
	 $$("netoF").value = "";
	 $$("ivaF").value = "";
	 $$("codigocontrolF").value = "";	
}

var cargarDatosFactura = function(posicion){
	$$("diaF").value = $$("datosFacturas").rows[posicion].cells[0].innerHTML;
	$$("ItemF").value = $$("datosFacturas").rows[posicion].cells[1].innerHTML;
	$$("nitF").value = $$("datosFacturas").rows[posicion].cells[2].innerHTML;
	$$("razonsocialF").value = $$("datosFacturas").rows[posicion].cells[3].innerHTML;
	$$("numerofactura").value = $$("datosFacturas").rows[posicion].cells[4].innerHTML;
	$$("numeroautorizacionF").value = $$("datosFacturas").rows[posicion].cells[5].innerHTML;
	$$("importetotalF").value = $$("datosFacturas").rows[posicion].cells[6].innerHTML;
	$$("iceF").value = $$("datosFacturas").rows[posicion].cells[7].innerHTML;
	$$("excentoF").value = $$("datosFacturas").rows[posicion].cells[8].innerHTML;
	$$("netoF").value = $$("datosFacturas").rows[posicion].cells[9].innerHTML;
	$$("ivaF").value = $$("datosFacturas").rows[posicion].cells[10].innerHTML;
	$$("codigocontrolF").value = $$("datosFacturas").rows[posicion].cells[11].innerHTML;
	$$("ItemTabla").value = $$("datosFacturas").rows[posicion].cells[12].innerHTML;
}

var cerrarSubVentana = function(){
	$$("subventana").style.visibility = "hidden";
	$$("overlay").style.visibility = "hidden";
}

function cerrarSubVentana2(){
	$$("subventana2").style.visibility = "hidden";  
	$$("overlay").style.visibility = "hidden";
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
	$$("subventana2").style.visibility = "visible";  
	$$("overlay").style.visibility = "visible";
  }else{
	openMensaje("Advertencia","No Tiene Cuentas Pendientes.");   
  }
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

var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display: 'none'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left', display: 'none'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display: 'none'},
	{type : 'normal', numerico : 'no', aling : 'center', display: 'none'},
	{type : 'normal', numerico : 'no', aling : 'center', display: 'none'}	
	];
	return formato;	
}

var getFormatoColumnaFactura = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
    {type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'}
	];
	return formato;	
}

var actualizarDatosFactura = function(posicion){
	 $$("datosFacturas").rows[posicion].cells[0].innerHTML = $$("diaF").value;
	 $$("datosFacturas").rows[posicion].cells[1].innerHTML = $$("ItemF").value;
	 $$("datosFacturas").rows[posicion].cells[2].innerHTML = $$("nitF").value;
	 $$("datosFacturas").rows[posicion].cells[3].innerHTML = $$("razonsocialF").value;
	 $$("datosFacturas").rows[posicion].cells[4].innerHTML = $$("numerofactura").value;
	 $$("datosFacturas").rows[posicion].cells[5].innerHTML = $$("numeroautorizacionF").value;
	 $$("datosFacturas").rows[posicion].cells[6].innerHTML = $$("importetotalF").value;
	 $$("datosFacturas").rows[posicion].cells[7].innerHTML = $$("iceF").value;
	 $$("datosFacturas").rows[posicion].cells[8].innerHTML = $$("excentoF").value;
	 $$("datosFacturas").rows[posicion].cells[9].innerHTML = $$("netoF").value;
	 $$("datosFacturas").rows[posicion].cells[10].innerHTML= $$("ivaF").value;
	 $$("datosFacturas").rows[posicion].cells[11].innerHTML = $$("codigocontrolF").value;
}


  var datosCompletos = function(){
	 $$("numeroautorizacionF").value = ($$("numeroautorizacionF").value == "") ? "0" : $$("numeroautorizacionF").value;
	 $$("importetotalF").value = ($$("importetotalF").value == "") ? "0" : $$("importetotalF").value;
 	 $$("iceF").value = ($$("iceF").value == "") ? "0" : $$("iceF").value;
 	 $$("excentoF").value = ($$("excentoF").value == "") ? "0" : $$("excentoF").value;
	 $$("netoF").value = ($$("netoF").value == "") ? "0" : $$("netoF").value;
	 $$("ivaF").value = ($$("ivaF").value == "") ? "0" : $$("ivaF").value;
	 $$("codigocontrolF").value = ($$("codigocontrolF").value == "") ? "0" : $$("codigocontrolF").value;
	 
	if ($$('numerofactura').value == ''){
		openMensaje("Advertencia","Debe Ingresar el numero de factura");
		return false;
	}	
	if ($$('diaF').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Dia");
		return false;
	}
	if (parseFloat($$('diaF').value) > 31){
		openMensaje("Advertencia","El Dia, esta Fuera de rango");
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
	return true; 
 }



var insertarNewFactura = function(){
   if (datosCompletos()){	
	var posicionTabla = $$("ItemF").value;
	if (posicionTabla != "-1"){
		actualizarDatosFactura(posicionTabla);
	}
	else{
	var n = $$("datosFacturas").rows.length ;
	var iTabla = $$("ItemTabla").value;
	var formato = getFormatoColumnaFactura();
	var datosfactura =[
	{id :"diaF", type:"get" },
	{data :n, type:"set" },
	{id :"nitF", type:"get"},
	{id :"razonsocialF", type:"get"},
	{id :"numerofactura", type:"get"},
	{id :"numeroautorizacionF", type:"get"},
	{id :"importetotalF", type:"get"},
	{id :"iceF", type:"get"},
	{id :"excentoF", type:"get"},
	{id :"netoF", type:"get"},
	{id :"ivaF", type:"get"},
	{id :"codigocontrolF", type:"get"},
	{data :iTabla, type:"set"}
	];
	cargarDatos(formato,datosfactura,'datosFacturas');
	$$("detalleTransaccion").rows[iTabla].cells[1].innerHTML = n;
	}
	cerrarSubVentana();
  }
}


var setFacturasDetalle = function(){
 var n = $$("datosFacturas").rows.length;	
   for (var i=0; i<n; i++){
	  var iddetalle = $$("datosFacturas").rows[i].cells[12].innerHTML;
	  if (iddetalle != "") {
	    setDetalleFactura(iddetalle,i);
	  }
   }
}

var setDetalleFactura = function(identificador,pos){
  var n = $$("detalleTransaccion").rows.length;	
   for (var i=0; i<n; i++){
	   var iddetalle = $$("detalleTransaccion").rows[i].cells[9].innerHTML;
	   if (iddetalle == identificador){
		 $$("detalleTransaccion").rows[i].cells[1].innerHTML = pos; 
	   }	   
   }	
}


var posicionIndiceFactura = function(indice){
 var n = $$("datosFacturas").rows.length;	
   for (var i=0; i<n; i++){
	   if($$("datosFacturas").rows[i].cells[1].innerHTML == indice && $$("datosFacturas").rows[i].cells[0].innerHTML != "")
	   return i;
   }
   return -1;
}


var reposicionarIndice = function(){
  var n = $$("detalleTransaccion").rows.length;	
  var pos;
   for (var i=0; i<n; i++){
	   var indicefactura = $$("detalleTransaccion").rows[i].cells[1].innerHTML;
	   if (indicefactura != "-1"){
		 pos = posicionIndiceFactura(indicefactura); 
		 $$("detalleTransaccion").rows[i].cells[1].innerHTML = pos; 
	   }	   
   }	
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


function consultarCuenta(tipo){
  var filtro = "transaccion=cuenta&tipo="+tipo;
  enviar(filtro,setDatosCuenta);  	
}


function setDatosCuenta(result){
  $$("cuenta").innerHTML = result;	
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


function ejecutarTransaccion(){
    var nFactura;
	if (datosValidos()){
	 $$('overlay').style.visibility = "visible";
     $$('gif').style.visibility = "visible";	
	 deleteFacturaInvalidas();	
	 reposicionarIndice();	
	 nfilas = $$('detalleTransaccion').rows.length;    	
     json = new Array();
     for(i=0; i<nfilas; i++) {
	   vector = [
	    $$('detalleTransaccion').rows[i].cells[3].innerHTML,								
	    $$('detalleTransaccion').rows[i].cells[5].innerHTML,	
	    $$('detalleTransaccion').rows[i].cells[6].innerHTML,	
	    $$('detalleTransaccion').rows[i].cells[7].innerHTML,
		$$('detalleTransaccion').rows[i].cells[1].innerHTML,
		$$('detalleTransaccion').rows[i].cells[10].innerHTML,
		$$('detalleTransaccion').rows[i].cells[11].innerHTML,
		];		   	
	    json[i] = vector;	 		
     }
     dato = JSON.stringify(json);     	
  	 json = new Array();
	 nFactura = $$("datosFacturas").rows.length;
	 for(i=0; i<nFactura; i++) {		 
	  if ($$('datosFacturas').rows[i].cells[0].innerHTML != ""){ 
		vector = [
	    $$('datosFacturas').rows[i].cells[0].innerHTML,								
	    $$('datosFacturas').rows[i].cells[2].innerHTML,	
	    $$('datosFacturas').rows[i].cells[3].innerHTML,	
	    $$('datosFacturas').rows[i].cells[4].innerHTML,
		$$('datosFacturas').rows[i].cells[5].innerHTML,
		$$('datosFacturas').rows[i].cells[6].innerHTML,
		$$('datosFacturas').rows[i].cells[7].innerHTML,
		$$('datosFacturas').rows[i].cells[8].innerHTML,
		$$('datosFacturas').rows[i].cells[9].innerHTML,
		$$('datosFacturas').rows[i].cells[10].innerHTML,
		$$('datosFacturas').rows[i].cells[11].innerHTML
		];	
		json[i] = vector;
	  }
	 }
	factura = JSON.stringify(json);	
		
  	var filtro = "transaccion="+transaccion+"&tipopersona="+$$("receptor").value+"&idpersona="+$$("idpersonarecibida").value
	+"&cuenta="+$$("cuenta").value+"&recibo="+$$U("recibo")+"&glosa="+$$U("glosa")+"&sucursal="+$$("sucursal").value
	+"&fecha="+$$("fecha").value+'&detalle='+encodeURIComponent(dato)+"&idegreso="+$$("idTransaccion").value+
	"&egresobs="+totalTransaccion.bolivianos+"&egresoDolares="+totalTransaccion.dolares
	+"&cheque="+$$U("cheque")+"&factura="+factura
	+"&nombrepersona="+$$("texto").value+"&tipocambio="+$$("tipoCambioBs").value;
	enviar(filtro,respuestaEjecutarT);
	 }
}

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
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
   window.open('egresos/imprimir_egreso.php?idegreso='+codigoTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
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

