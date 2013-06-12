// JavaScript Document

  var servidor = 'nota/DNota.php';
  var irDireccion = "listar_nota.php#t7";
  var transaccion = "insertar";
  var idTransaccion;


  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2
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

  function ajaxx() {
	 return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  var enviar = function(filtro,funcion){
	 peticion = ajaxx(); 
	 peticion.open('GET', servidor+"?"+filtro, true);	 	 
	 peticion.onreadystatechange = function() { 	
	   if (peticion.readyState == 4) {
		  resultado = peticion.responseText;
		  if (funcion != null)
		  funcion(resultado);		  
	   } 
	}
	peticion.send(null); 
  }

  function validar(){
	 if ($$('titulo').value == ""){
		openMensaje("Advertencia","Debe Ingresar el Titulo de la Nota"); 
		return false;
	 }
	 
	 return true;
  }

  var $$ = function(id){
	return document.getElementById(id);  
  }  
 
  function ejecutarTransaccion(){
	var n;  
	  if (validar()){
		 filtro ="titulo="+$$U('titulo')+"&fecha="+$$('fecha').value+"&privado="+$$('privado').value+
        "&contenido="+$$U('contenido')+"&idnota="+$$('idnota').value+"&transaccion="+transaccion;	   
	    enviar(filtro,resultadoEjecutarTransaccion);
	  }
  }
  
  var $$U = function(id){
  return encodeURIComponent($$(id).value);	
  }
  
  var resultadoEjecutarTransaccion = function(resultado){
	  location.href = "nuevo_nota.php";
  }
  
  function setCompartido(estado){
	var n = $$("detalleCompartir").rows.length; 
	var objeto;
	var estado = (estado == true)? 'check' : '';
	 for (var i=1;i<=n;i++){
		$$('uc'+i).checked = estado; 
	 }	
   }
  
   function ventanaCompartido(estado){
	 if (estado == "abrir"){ 
	   $$("subventana").style.visibility = "visible"; 
	   $$("overlay_vendido").style.visibility = "visible";   
	 }else{
	   $$("subventana").style.visibility = "hidden"; 
	   $$("overlay_vendido").style.visibility = "hidden"; 
	 }	 
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

