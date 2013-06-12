// JavaScript Document


 var $$ = function(id){
   return document.getElementById(id);
 }
 

 var efectoClick = function(id){
	var ides = ['cgarzon','cventasucursal','cdinero']; 
	$$(id).style.display = "block"; 
	for (var j=0;j<ides.length;j++){
		if (ides[j] != id)
		$$(ides[j]).style.display = "none"; 
	}
 }
 
 
  function ajax() {
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
 }
 
 function enviar(servidor,filtro,funcion){ 
  var  pedido = ajax();	
  pedido.open("GET",servidor+"?"+filtro,true);
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
 
 
 var getPersonal = function(value,funcion){
	var filtro = "tipo=usuarios&personal="+value;
	enviar("Dusuario.php",filtro,funcion);	 
 }
 
 var setPersonal = function(resultado){
   $$("trabajador").innerHTML = resultado;	 
 }
 
 var setPersonal2 = function(resultado){
   $$("trabajador2").innerHTML = resultado;	 	 
 }
 
  var getReporte3 = function(){
	window.open('reporte3.php?idusuario='+$$("trabajador").value+'&fecha='+$$("fecha").value,'target:_blank');	
 }
 
 var autocompletar = function(e,id){  
 
    if ($$("tipo2").value == "apoyo"){
    var consulta = "select u.idusuario,p.nombre from usuariorestaurante u,personalapoyo p  "
     + " where  p.idpersonalapoyo=u.idtrabajador and u.tipo='apoyo' and u.estado=1 "
     + " and p.nombre like '"+$$(id).value+"%' limit 5; "; 
	}else{
	var consulta = "select u.idusuario,p.nombre from usuariorestaurante u,trabajador p "
     + " where p.idtrabajador=u.idtrabajador and u.tipo='fijo' and u.estado=1 "
     + " and p.nombre like '"+$$(id).value+"%';";	
	}
	 
    eventoTeclas(e,id,'resultados','producto','nombre','idusuario','eventoResultado','../autocompletar/consultor.php',consulta,'<sinfiltro>');
  }

 var eventoResultado = function(resultado,codigo){	     
	 var filtro = "idusuario="+codigo+"&tipo=totalVendido";
	 enviar("Datencion.php",filtro,resultadoVentas); 
     $$("trabajadorr3").value =resultado;
	 $$("idusuarior3").value = codigo; 	 
 }
 
 var resultadoVentas = function(resultado){
	 $$("totalventar3").value = resultado;
 }
 
 
 var calcularFaltante = function(id){
	var total = ($$("totalventar3").value == "") ? 0 :  parseFloat(desconvertirFormatoNumber($$("totalventar3").value));
	var entregado = ($$(id).value == "") ? 0 : parseFloat($$(id).value);
	$$("faltanter3").value = convertirFormatoNumber(parseFloat(total - entregado).toFixed(2));
 }
 
 
 var desconvertirFormatoNumber = function(cadena){
	convertir = "";     
	for(i = 0;i < cadena.length;i++){		
		if (cadena[i] != ",")
		convertir = convertir + cadena[i];
	}
	return convertir;
}

 var convertirFormatoNumber = function(valor){	
	var total = valor;
	var conversion = valor + "";
	var convertir = "";
	while(total >= 1000){		
	 total = dividendo(total);
     var convertir = ""+total;      
	 var pos = convertir.length; 	
	 conversion = aumentar(conversion,pos);	 
	}
	return conversion;
}


var dividendo = function(valor){
	return  parseInt(valor/1000);
}


var aumentar = function(cadena,pos){
	convertir = "";
	for(i=0;i<cadena.length;i++){
		convertir = convertir + cadena[i];
		if (i+1 == pos)
		convertir = convertir + ",";

	}
	return convertir;
}

 var getReporte3 = function(){
	var entregado = desconvertirFormatoNumber($$("entregador3").value); 
	var faltante =  desconvertirFormatoNumber($$("altanter3").value); 
	 
	window.open('reporte3.php?idusuario='+$$("idusuarior3").value+'&fecha='+$$("fechar3").value+
	"&entregado="+entregado+"&faltante="+faltante,'target:_blank');	
 }