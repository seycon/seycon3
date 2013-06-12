// JavaScript Document

  var total_bolivianos = 0;
  var total_Dolares = 0;
  var idUTransaccion = 0;
  var transaccion = "insertar";
  var servidor = "rutaentrega/Dentrega.php";
  var irDireccion = "listar_rutaentrega.php";
  
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


  var ajax = function() {
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
		 if (pedido.readyState == 4) {     	
			var resultado = pedido.responseText;    
			funcion(resultado);   
		 }	   
	 }
	 pedido.send(null);
 }

 var eventoResultado = function(resultado, codigo) {	  
     $$("dato").value =resultado;
	 $$("codigotrabajador").value = codigo; 	 
 }

 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
    table.removeChild(tr);
 } 
 
 
 
 var insertarNewItem = function(){
   if (validarSubIngreso()) {
	  var filtro = "transaccion=listaNotas&idtrabajador=" + $$("codigotrabajador").value 
	  + "&fecha=" + $$("fechaEntrega").value + "&idsucursal=" + $$("sucursal").value;
	  consultar(filtro, resultadoBusqueda); 
   }    
 }

 var resultadoBusqueda = function(resultado) {
	if (resultado == "") {
	   openMensaje("Advertencia","Sin resultado de busqueda.");
	} 
    $$("detalleT").innerHTML = resultado; 	
	
 }
 
  var validarSubIngreso = function(){
	if ($$("codigotrabajador").value == ""){
	  openMensaje("Advertencia","Debe seleccionar un trabajador.");
	  return false;
	}
	if ($$("fechaEntrega").value == ""){
	  openMensaje("Advertencia","Debe ingresar la fecha de Entrega.");
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
	  var j = 0;
	  for(i = 0;i < nfilas;i++) {
		 if ($$("codentrega" + i).checked) {  
			 vector = new Array();
			 vector[0]=$$('detalleT').rows[i].cells[8].innerHTML;  //trabajador 		
			 vector[1]=$$('detalleT').rows[i].cells[9].innerHTML;  //nota de venta
			 vector[2]=$$('detalleT').rows[i].cells[10].innerHTML; //ruta
			 json[j] = vector;	 	
			 j++;
		 }
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


  var existeDetalle = function(){
	  var  nfilas = $$('detalleT').rows.length;    		  
	  for(var i = 0; i < nfilas; i++) {
		if ($$("codentrega" + i).checked) {
		    return  true;	
		}
	  }
	  return false;
  }

  function esvalido(){
	if ($$('fecha').value == ""){   
	 openMensaje("Advertencia",'Debe ingresar la fecha de realización.');
	 return false;
	}
	if (!existeDetalle()){   
	  openMensaje("Advertencia",'Debe asignar entregas a los trabajadores.');
	  return false;
	} 	
	return true;
  }

function enviarDireccion(){
  location.href = irDireccion;	
}

function cerrarPagina(){
  location.href = "nuevo_rutaentrega.php";	
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
