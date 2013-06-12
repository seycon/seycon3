// JavaScript Document

  var total_bolivianos = 0;
  var puntero = null;
  var idUTransaccion = 0;
  var transaccion = "insertar";
  var servidor = "rutavisita/Druta.php";
  var irDireccion = "listar_rutavisita.php";
  
  var $$ = function(id) {
	  return document.getElementById(id);	 
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
	  var sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t where estado=1 and ";
	  eventoTeclas(e,id,'resultados','trabajador','nombre','idtrabajador','eventoResultado'
	  ,'autocompletar/consultor.php',sql,'','autoL1');
  }
  
  
  function consultar(filtro, funcion) {
   var  pedido = ajax();	
   pedido.open("GET", servidor+"?"+filtro, true);
	 pedido.onreadystatechange = function(){
		 if (pedido.readyState == 4){     	
			var resultado = pedido.responseText;    
			funcion(resultado);   
		 }	   
	 }
	 pedido.send(null);
  }


 var eventoResultado = function(resultado, codigo) {	  
     var filtro = "transaccion=listaRuta&idtrabajador="+codigo;
     consultar(filtro,cargarListaRuta); 
     $$("dato").value =resultado;
	 $$("codigotrabajador").value = codigo; 	 
	 $$("overlay").style.visibility = "visible";
     $$('gif').style.visibility = "visible"; 	
 }


 var cargarListaRuta = function(resultado) {
	 $$("ruta").innerHTML = resultado;
	 $$("overlay").style.visibility = "hidden";
	 $$('gif').style.visibility = "hidden";

 }
 
 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
	puntero = tr;
	if (tr.cells[8].innerHTML != 0) {
		var filtro = "transaccion=rutavisitada&iddetalleruta="+tr.cells[8].innerHTML;
		consultar(filtro,asignarPrivilegio);
		$$("overlay").style.visibility = "visible";
		$$('gif').style.visibility = "visible"; 
	} else {
		var table = puntero.parentNode;	
        table.removeChild(puntero);	
	}
 } 
 
 var asignarPrivilegio = function(resultado) {
	 if (parseFloat(resultado) > 0) {
  	     $$('gif').style.visibility = "hidden"; 
   	     openMensaje("Advertencia","No se puede eliminar, ya se registró un estado de la visita.");
	 } else {
	     var table = puntero.parentNode;	
         table.removeChild(puntero);	 
 	     $$("overlay").style.visibility = "hidden";
	     $$('gif').style.visibility = "hidden"; 
	 }
 }
 
 
 var insertarNewItem = function(tabladestino){
   if (validarSubIngreso()) {
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice= $$("ruta").selectedIndex; 
    var nombreRuta = $$("ruta").options[indice].text;
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{data : $$("dato").value, type:"set" },
	{data : nombreRuta  , type:"set"},
	{data : $$("fechaInicio").value, type:"set" },
	{data : $$("fechaFinal").value, type:"set" },
	{data :$$("codigotrabajador").value, type:"set"},
	{data :$$("ruta").value, type:"set"},
	{data :0, type:"set"},
	];
	cargarDatos(formato,datosIngreso,tabladestino);
    $$("codigotrabajador").value = "";
	$$("dato").value = "";
   }
 }

 var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},	
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left' , display:'none' },
    {type : 'normal', numerico : 'no', aling : 'left' , display:'none' },	
	{type : 'normal', numerico : 'no', aling : 'left' , display:'none' }	
	];
	return formato;	
 }
 
  var validarSubIngreso = function(){
	if ($$("codigotrabajador").value == ""){
	  openMensaje("Advertencia","Debe seleccionar un trabajador.");
	  return false;
	}
	if ($$("ruta").value == ""){
	  openMensaje("Advertencia","Debe seleccionar una ruta.");
	  return false;
	}

	return true; 
 }
 
 
  var enviarTransaccion = function() {
	if (esvalido()) {	
	  $$('overlay').style.visibility = "visible";
	  $$('gif').style.visibility = "visible";
	 
	  nfilas = $$('detalleT').rows.length;    	
	   json = new Array();
	   for(i = 0;i < nfilas;i++) {
		 vector = new Array();
		 vector[0]=$$('detalleT').rows[i].cells[4].innerHTML;  //fecha Inicio		
		 vector[1]=$$('detalleT').rows[i].cells[5].innerHTML;  //fecha Final
		 vector[2]=$$('detalleT').rows[i].cells[6].innerHTML;  //idtrabajador							
		 vector[3]=$$('detalleT').rows[i].cells[7].innerHTML;  //idruta		
		 vector[4]=$$('detalleT').rows[i].cells[8].innerHTML;  //iddetalleruta	
		 json[i] = vector;	 		
	   }
	   dato = JSON.stringify(json);    
	 
	  filtro ="transaccion="+$$("transaccion").value+"&fecha="+$$("fecha").value
	  +"&detalle="+dato+"&idtransaccion=" + $$("idTransaccion").value;  
	  consultar(filtro, resultadoRegistro);
	}
  }
  
  var resultadoRegistro = function(resultado){
	  cerrarPagina();
  }


  function esvalido(){
	if ($$('fecha').value == ""){   
	 openMensaje("Advertencia",'Debe ingresar la fecha de realización.');
	 return false;
	}
	if ($$('detalleT').rows.length == 0){   
	  openMensaje("Advertencia",'Debe asignar rutas a los trabajadores.');
	  return false;
	} 	
	return true;
  }

function enviarDireccion(){
  location.href = irDireccion;	
}

function cerrarPagina(){
  location.href = "nuevo_rutavisita.php";	
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
 

 
  
  
 function limpiarDetalle(){
	  $$("detalleT").innerHTML = ""; 
	  setSubTotal(-total_bolivianos);  
 }
 
 function salir() {
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
