// JavaScript Document

  var irDireccion = "nuevo_ventamasiva.php";
  var transaccion = "insertar";


  document.onkeydown = function(e){
     tecla = (window.event) ? event.keyCode : e.which;
	 switch(tecla){
	  case 113://F2
		 ejecutarTransaccion();
	  break;
	 }
  }
 
 var tipoBusqueda = function(e){
	 var sql;	
	  idconsulta = "idservicio";   	  
	  sql = "select idservicio,left(nombre,30)as nombre from servicio where estado=1 and ";		
	  eventoTeclas(e,"nombreservicio",'resultados','servicio','nombre',idconsulta,'eventoResultado'
	  ,'autocompletar/consultor.php',sql,'','autoL1');		
 }

  var eventoResultado = function(resultado, codigo){
	  $$("nombreservicio").value= resultado;
	  $$("idservicio").value = codigo;	
	  verificarVendedor(codigo);   
  }
   
  var ajax = function(){
    return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
  }
  
  function consultarServidor(filtro, funcion) {
	 var  pedido = ajax();
	 var servidor = "factura/servicio/DVenta.php";	
	 pedido.open("GET",servidor+"?"+filtro,true);
	 pedido.onreadystatechange = function(){
		 if (pedido.readyState == 4) {     	
			var resultado = pedido.responseText;    
			funcion(resultado);   
		 }	   
	 }
	 pedido.send(null);
  } 
   
  function realizarTransaccion(){
    if (validarCajas()) {		   
	   if ($$("facturado").checked) {
	       verificarDisponibles();     
	   } else {
		  enviarTransaccion(); 
	   }      
    }
  }
  
  function enviarTransaccion(){
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";  
	var factura = "no";
	   if ($$("facturado").checked) {
	       factura = "si";     
	   }
       var filtro = "transaccion=ventaMasiva&mes="+$$("mes").value + 
	   "&anio=" + $$("anio").value + "&sucursal=" + $$("sucursal").value +
	   "&moneda=" + $$("moneda").value + "&factura=" + factura;
       consultarServidor(filtro,resultadoTransaccion);	  
  }
  
  var resultadoTransaccion = function(resultado) {
      if (resultado == "") {
		salir();  
	  } else {
		openMensaje("Advertencia", "Problemas al procesar su transacción."); 
	  }
  } 
 
  var verificarDisponibles = function()
  {
	   var filtro = "transaccion=disponibles&mes="+$$("mes").value + 
	   "&anio=" + $$("anio").value + "&sucursal=" + $$("sucursal").value +
	   "&moneda=" + $$("moneda").value;
       consultarServidor(filtro,resultadoDisponible);	
  }
 
  var resultadoDisponible = function(resultado)
  {
	  if (resultado == "si") {
		enviarTransaccion();  
	  } 
	  if (resultado == "Fecha") {
		openMensaje("Advertencia", "Limite de emisión de las facturas."); 
	  } 
	  if (resultado == "Insuficientes") {
		openMensaje("Advertencia", "Facturas insuficientes."); 
	  } 
  }
 
  var salir = function() {
	location.href = irDireccion;
  }	 

  var validarCajas = function() {
	var ids = ['sucursal']; 
	var msg = ['sucursal'];
	for (var j = 0; j < ids.length; j++){
	  if ($$(ids[j]).value == "")	{
	      openMensaje("Advertencia",'El campo '+msg[j] + " es requerido.");   
	      return false;
	  }	 
	}
	return true;
  } 
  
  var openMensaje = function(titulo, contenido) {
	$$("modal_tituloCabecera").innerHTML = titulo;
	$$("modal_contenido").innerHTML = contenido;
	$$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";  
  }
  
  var closeMensaje = function() {
	$$("modal_mensajes").style.visibility = "hidden";
    $$("overlay").style.visibility = "hidden";    
  }


  var $$ = function(id){
	return document.getElementById(id);  
  }  
 


