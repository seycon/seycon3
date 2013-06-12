// JavaScript Document

 $(document).ready(function()
 {	
  $("#formValidado").validate({});
 });

 document.onkeydown = function(e){
     tecla = (window.event) ? event.keyCode : e.which;   
      if (tecla == 115){ //F4
       if ($$("cancelar") != null)
	    location.href = 'listar_grupousuario.php#t8';	   
	  }
	
      if(tecla == 113){ //F2
	   $$("enviar").click();	  
	}
 }
 
 var $$ = function(id){
	return document.getElementById(id); 
 }
 
 var viewMenu = function(id){
	var menu = ['tabs-1','tabs-2','tabs-3','tabs-4','tabs-5','tabs-6','tabs-7'];
	var menu2 = ['tabs1','tabs2','tabs3','tabs4','tabs5','tabs6','tabs7'];
		for (var j=0;j<menu.length;j++){
	  if (menu[j] == id){
		$$(menu[j]).style.display = "block"; 
		$$(menu2[j]).style.background = "#8E8E8E"; 
		$$(menu2[j]).style.color = "#FFF"; 
	  }else{
		$$(menu[j]).style.display = "none";
		$$(menu2[j]).style.background = "#F6F6F6"; 
		$$(menu2[j]).style.color = "#666";  
	  }
	}	 
 }


var $$ = function(id){
 return document.getElementById(id);	 
}

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}


function enviar(servidor,filtro,funcion){
  var  pedido = ajax();	  
  pedido.open("GET",servidor+"?"+filtro,true);
  pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){
		   resultado = pedido.responseText;
		   if (funcion != null)
		   funcion(resultado);
	   }	   
   }
   pedido.send(null);   	
}

function validar(){  	
  if ($$("nombre").value == ""){
	$$("requerido").style.visibility = "visible";  
	return false;
  }
  return true;
}


var transaccionGrupo = function(){
  $$("requerido").style.visibility = "hidden";
  if (validar()){
   $$('overlay').style.visibility = "visible";
   $$('gif').style.visibility = "visible";	  	
   var filtro = "transaccion="+$$('transaccion').value+"&nombre="+$$U("nombre")+"&privilegios="+getDetalle()+
   "&idgrupo="+$$("idgrupousuario").value;
   enviar("grupousuario/Dgrupousuario.php",filtro,resultadoTransaccion);
  }
}

var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}

var getDetalle = function() {		
    var num = ['numadm', 'numinv', 'numrec', 'numact', 'numvent', 'numcont', 'numage'];
	var ids = ['adm', 'inv', 'rec', 'act', 'ven', 'con', 'age'];
	var k = 0;
    json = new Array(); 
	for (var j=0;j<num.length;j++){ 
     var cantidad = $$(num[j]).value;     
     for(i = 1;i <= cantidad-1;i++) {
	   var id = ids[j]+i;
	   if ($$(id).checked){
		 json[k] = $$(id).value;     
		 k++;
	   }	   	 		
     }
	}
     dato = JSON.stringify(json);	 
    return dato;
}


var setMarcado = function(modulo, name, identificador, idmensaje){
    var cantidad = $$(modulo).value; 
	var atributo = "";
	var mensaje = "Marcar Todos";
	if ($$(identificador).checked){
		atributo = "checked";
		mensaje = "Desmarcar Todos";
	}	
    for(i = 1; i < cantidad; i++) {
		var id = name+i;
		$$(id).checked = atributo;
	}
	$$(idmensaje).innerHTML = mensaje;
}

var resultadoTransaccion = function(resultado){	
	window.location.href = "nuevo_grupousuario.php";	
}