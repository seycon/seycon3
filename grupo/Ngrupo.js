// JavaScript Document
var irDireccion = "listar_grupo.php#t9";
var transaccion = 'insertar';

var $$ = function(id){
	return document.getElementById(id);
}

var insertarFila = function(datos,tabla){
   var x = $$(tabla).insertRow($$(tabla).rows.length);
     for (var i = 0;i < datos.length;i++){
        var y = x.insertCell(i);
		if (i <= 1 )
		y.align = "center";			
        y.innerHTML = datos[i];
		if (i==4)
		 y.style.display = "none";
     }
}

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;
    table.removeChild(tr);
}

//teclas de atajo
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if(tecla == 115){
    if ($$("cancelar") != null)
	 salir();
   }
   if(tecla == 113){//F2
    if($$('overlay').style.visibility != "visible")
	 enviarDetalle();
   }
 }

var getDatosTabla = function(tablaorigen,tabladestino){
     var datos = new Array();
     var n =  $$(tabladestino).rows.length + 1;
     datos[0] = "<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />";
     datos[1] = n;
	  var parrafos = $$(tablaorigen).getElementsByTagName("input");
	       for(var i=0; i<parrafos.length; i++) {
			   if(parrafos[i].getAttribute('type')=='text'){
				  var cant = datos.length; 
				  datos.splice(cant,0,parrafos[i].value);
			   }
            }
	datos[4] = 0;		
	  return datos;		
}

var SubIngreso = function(tabla,tipo){
	 var parrafos = $$(tabla).getElementsByTagName("input");
	       for(var i=0; i<parrafos.length; i++) {
			   if(parrafos[i].getAttribute('type') == 'text' && parrafos[i].value == "" && tipo == "validar"){
				  return false;
			   }
			   if (parrafos[i].getAttribute('type') == 'text' && tipo == "limpiar"){
				   parrafos[i].value = "";
			   }
            }
	return true;		
}

var cargarDatos = function(tablaorigen,tabladestino){
   var datos = new Array();
   if (SubIngreso(tablaorigen,'validar')){
     datos = getDatosTabla(tablaorigen,tabladestino);
     insertarFila(datos,tabladestino);
	 SubIngreso(tablaorigen,'limpiar');
   }
}

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

function datosValidos(){
	if($$("nombregrupo").value == ""){
	  openMensaje("Advertencia","Debe ingresar el nombre del grupo");
	  return false;	
	}
	if($$('detallegrupo').rows.length == 0){
	  openMensaje("Advertencia","Debe ingresar el detalle del SubGrupo");
	  return false;	
	}
	return true;
}

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}


var enviarDetalle = function() {
	if (datosValidos()){	
	$$('overlay').style.visibility = "visible";
	$$('gif').style.visibility = "visible";
     nfilas = $$('detallegrupo').rows.length;    	
     json = new Array();
     for(i=0; i<nfilas; i++) {
	   vector = new Array();
			vector[0] = $$('detallegrupo').rows[i].cells[2].innerHTML;		
			vector[1] = $$('detallegrupo').rows[i].cells[3].innerHTML;
			vector[2] = $$('detallegrupo').rows[i].cells[4].innerHTML;
			json[i] = vector;	 		
     }
     dato = JSON.stringify(json); 	 
     datos = 'detalle='+encodeURIComponent(dato)+'&nombre='+$$U("nombregrupo")
	 +'&transaccion='+transaccion+'&idgrupo='+$$U("idgrupo"); 	 	
     enviar(datos);
	 $$('detallegrupo').innerHTML = "";
	 $$('nombregrupo').value = '';
	}
}


function enviar(datos){
  var  pedido = ajax();	  
  destino = "grupo/Dgrupo.php?";
  pedido.open("GET",destino+datos,true);
  pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     
	     location.href = "nuevo_grupo.php";
	   }	   
   }
   pedido.send(null);
}


function eventoText(evt){
var tecla = (document.all) ? evt.keyCode : evt.which;
  if (tecla == 13){
    cargarDatos('datosorigen','detallegrupo');
	$$('nombreD').focus();
  }
}

function salir(){
   if ($$('detallegrupo').rows.length > 0){
	if (confirm("Cuenta con detalles de Sub Grupo desea Salir ?"))
	 location.href = irDireccion;  
   }
   else{
	 location.href = irDireccion;  
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
