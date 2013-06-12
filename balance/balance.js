// JavaScript Document


var servidor = "balance/DBalance.php";

 var $$ = function(id){
  return document.getElementById(id);	 
 }

 var ajax = function(){
   return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
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
 
 function verReporte(){
   window.open('balance/imprimir_balance.php?desde='+$$("desde").value+'&hasta='+$$("hasta").value+'&logo='+$$("logo").checked+'&auxiliar='+$$("auxiliares2").checked+
   '&moneda='+$$("moneda").value,'target:_blank');	
 }  
 
 
 var getConsulta = function(){
	 filtro = "fecha="+$$("fecha").value+"&tipo=consulta";
	 consultar(filtro,setResultadoConsulta);
 }
 
 var setResultadoConsulta = function(resultado){
	 var datos = resultado.split("---");
	 $$("detalleDisponible").innerHTML = datos[0];
	 $$("detalleGrafico").innerHTML = datos[1];
 }
 
 
 var mostrarAuxiliares = function(){	
    var tipo = $$("auxiliares").checked;
	if (tipo == true){
	 tipo = "none";
	}else{
	 tipo = "table-row"; 
	}
	 
	cantidad = $$("detalleDisponible").rows.length;
	for (var i=0;i<cantidad;i++){
	  if ($$("detalleDisponible").rows[i].cells[0].innerHTML == "6"){
		  $$("detalleDisponible").rows[i].style.display = tipo;
		  $$("detalleGrafico").rows[i].style.display = tipo;
	  }
	}	
		 
 }