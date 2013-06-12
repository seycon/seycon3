// JavaScript Document

var transaccion = "insertar";
var servidor =   "inventario/DInventario.php";
var dirDestino = "listar_inventario.php";
var dirLocal = "nuevo_inventario.php";
var idUTransaccion = 0;

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
	   ejecutarTransaccion();
	break;	   
   }
 }


var salir = function(){
	location.href = dirDestino;
}

 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }


function consultar(filtro,funcion){
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

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

 function ejecutarTransaccion(){	
	 nfilas = $$('detalleTransaccion').rows.length;    	
     json = new Array();
	 if (datosValidos()){
	 $$('overlay').style.visibility = "visible";
	 $$('gif').style.visibility = "visible";
     for(var i=0; i<nfilas; i++) {
	  var cadena = $$('detalleTransaccion').rows[i].cells[3].innerHTML;	 
	  var cantidadUM = desconvertirFormatoNumber(cadena);	
	  var cadena = $$('detalleTransaccion').rows[i].cells[5].innerHTML;  
	  var cantidadUA = desconvertirFormatoNumber(cadena); 	 
	   vector = [
	    $$('detalleTransaccion').rows[i].cells[1].innerHTML,								
	    cantidadUM,	
	    $$('detalleTransaccion').rows[i].cells[4].innerHTML,	
	    cantidadUA,
		$$('detalleTransaccion').rows[i].cells[6].innerHTML	
		];		   	
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);     	
	
  	var filtro = "transaccion="+transaccion+"&fecha="+$$("fecha").value+"&fechafinal="+$$("fechafinal").value
	+"&idalmacen="+$$("almacen").value+"&supervisor="+$$U("supervisor")+"&glosa="+$$U("glosa")
	+"&administrador="+$$U("administrador")+'&detalle='+dato+"&idtransaccion="+$$("idTransaccion").value;	
	consultar(filtro,respuestaEjecutarT);
	}
 }
 
 var respuestaEjecutarT = function(resultado){
	 idUTransaccion = resultado;
	 $$('overlay').style.visibility = "visible";
     $$('modal_vendido').style.visibility = "visible";  
	 $$('gif').style.visibility = "hidden";
 }
 
 
 function accionPostRegistro(){
	window.open('inventario/imprimir_inventario.php?idinventario='+idUTransaccion+'&logo='
	+$$("logo").checked+"&grupo="+$$("grupoR").value,'target:_blank');	
	cerrarPagina();
 }
 
 function cerrarPagina(){
	location.href = "nuevo_inventario.php";	 
 }
 
  var datosValidos = function(){
  if ($$("almacen").value == ""){	
   openMensaje("Advertencia","Debe seleccionar el almacén.");
	return false;
  }
  
  if ($$("detalleTransaccion").rows.length < 1){
	openMensaje("Advertencia","Debe Ingresar el detalle de la transacción");  
    return false;
  }
  
  return true;
 }
 
 
 function soloNumeros(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
  return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
 }
 
 var autocompletar = function(e,id){
  if ($$("almacen").value != "" ){	
    var consulta = "select idproducto,left(nombre,25)as 'nombre' from producto where estado=1 and "; 
    eventoTeclas(e,id,'resultados','producto','nombre','idproducto','eventoResultado','autocompletar/consultor.php',consulta,'','autoL1');	
  }else{
	 openMensaje("Advertencia","Debe seleccionar el almacen."); 
	 $$(id).value = "";
	 $$(id).focus();
  }
 }

 var eventoResultado = function(resultado,codigo){
	 var filtro ="transaccion=consulta&codigo="+codigo; 	     
     consultar(filtro,cargarCantidad); 
     $$("dato").value =resultado;
	 $$("codidproducto").value = codigo; 
	 $$("cunidadI").value = "";
	 $$("cunidadII").value = "";
	 $$("msjsubnumero").style.visibility = "hidden";
	 $$("overlay").style.visibility = "visible";
	 $$('gif').style.visibility = "visible";
 }
 
 var cargarCantidad = function(resultado){
	var unidades = resultado.split("---"); 
	$$("unidadI").innerHTML = unidades[0];
    $$("unidadII").innerHTML = unidades[1];
    $$("modal").style.visibility = "visible"; 
	$$('gif').style.visibility = "hidden";
    $$("cunidadI").focus();
 }
 
 function accion(){
	 $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
	 $$("msjsubnumero").style.visibility = "hidden"; 
	 $$("dato").value = "";
	 $$("dato").focus();	 
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
  
   var insertarNewItem = function(tabladestino){  
   if (validarSubIngreso()){
	var cantidadI = ( $$("cunidadI").value == "") ? 0 : $$("cunidadI").value;
	var cantidadII = ( $$("cunidadII").value == "") ? 0 : $$("cunidadII").value;
	
	var formato = getFormatoColumna();
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :$$("codidproducto").value , type:"set"},
	{data :$$("dato").value, type:"set" },
	{data :cantidadI  , type:"set"},
	{data :$$("unidadI").innerHTML, type:"set"},
	{data :cantidadII , type:"set"},
	{data :$$("unidadII").innerHTML, type:"set"}
	];
	cargarDatos(formato,datosIngreso,tabladestino);
	accion();
   }else{
	$$("cunidadI").focus();   
   }

 }
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }

 
 var validarSubIngreso = function(){
	$$("cunidadI").value = ($$("cunidadI").value == "") ? 0 : $$("cunidadI").value;
	$$("cunidadII").value = ($$("cunidadII").value == "") ? 0 : $$("cunidadII").value;
	if (!isvalidoNumero("cunidadI") || !isvalidoNumero("cunidadII")){
	  $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "Cantidad Incorrecta."; 
	  return false;		
	}
	
	if (parseFloat($$("cunidadI").value) <= 0 && parseFloat($$("cunidadII").value) <= 0){
	  $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "Ingrese una cantidad mayor a cero."; 
	  return false;		
	}
			
	$$("msjsubnumero").style.visibility = "hidden";
	return true;
 }

 var eventoSubVentana = function(evento){
	var tecla = (document.all) ? evento.keyCode : evento.which;
	if (tecla == 13)
	 insertarNewItem("detalleTransaccion");
 }
 
 
  var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'si', aling : 'center'  },
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'si', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'}	
	];
	return formato;	
 }
 
  var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;
    table.removeChild(tr);
 }
 
  function limpiarDetalle(){
	  $$("detalleTransaccion").innerHTML = ""; 
 }