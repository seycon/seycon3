// JavaScript Document

  var irDireccion = "listar_vacaciones.php";
  var transaccion = "insertar";


  document.onkeydown = function(e){
     tecla = (window.event) ? event.keyCode : e.which;
	 switch(tecla){
	  case 113://F2
		 ejecutarTransaccion();
	  break;
	  case 115://F4
		 salir();
	  break;
	 }
  }
  
 
  var autocompletar = function(e,id){
	  var sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t where estado=1 and ";
	  eventoTeclas(e,id,'resultados','trabajador','nombre','idtrabajador','eventoResultado'
	  ,'autocompletar/consultor.php',sql,'','autoL1');
  } 
  

  var eventoResultado = function(resultado, codigo){
     $$("nombre").value =resultado;
	 $$("idtrabajador").value = codigo; 
	 getdatosTrabajador(codigo);   
  }
  
  function getdatosTrabajador(codigo){
    if (codigo != "") {	
       var filtro = "transaccion=consultarTrabajador&idtrabajador="+codigo;
       consultarServidor(filtro,resultadoTrabajador);	
    }
  }
  
  var resultadoTrabajador = function(resultado) {
	var datos = resultado.split("---");  
	$$("fechainicio").value = datos[0];
	$$("cargo").value = datos[1];
	$$("derecho").value = datos[2];
  } 
  
   
  var ajax = function(){
    return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
  }
  
  function consultarServidor(filtro,funcion){
   var  pedido = ajax();
   var servidor = "vacaciones/Dvacaciones.php";	
   pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText;    
    	  funcion(resultado);   
	   }	   
   }
   pedido.send(null);
  } 
   
  var marcar = function() {
	 $$('eventofecha').click() 
  }
 
  var salir = function() {
	location.href = irDireccion;
  }	 

  function validar() {	  
	 if (!validarCajas()) {       
		return false;
	 }	 	 
	 return true;
  }
 
  var validarCajas = function() {
	var ids = ['idtrabajador','valores']; 
	var msg = ['nombre','días de vacaciones'];
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
 
  function ejecutarTransaccion() {
	  if (validar()) {
		   $$('overlay').style.visibility = "visible";
           $$('gif').style.visibility = "visible";	
	       var filtro = "transaccion=" + $$("transaccion").value+ "&idtrabajador=" + $$("idtrabajador").value 
		   + "&motivo=" + $$U("motivo")+ "&idtransaccion=" + $$("idvacaciones").value 
		   + "&fecha=" + $$("valores").value + "&diashabilitado=" + $$("derecho").value;
	       consultarServidor(filtro, resultadoTransaccion);
	  }
  }
  
  
 var $$U = function(id){
  return encodeURIComponent($$(id).value);	
 }
  
  
  var resultadoTransaccion = function(resultado) {
	  if (resultado == "") {
	    location.href = "nuevo_vacaciones.php";
	  } else {
		openMensaje("Advertencia","Problema al procesar su registro.");  
	  }
  }
  
   
 

 

