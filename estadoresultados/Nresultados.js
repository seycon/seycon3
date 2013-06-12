// JavaScript Document

var servidor = "estadoresultados/DResultados.php";

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
   window.open('estadoresultados/imprimir_estadoresultados.php?desde='+$$("desde").value+'&hasta='+$$("hasta").value
   +'&logo='+$$("logo").checked+'&auxiliar='+$$("auxiliares2").checked+'&moneda='+$$("moneda").value+'&sucursal='
   +$$("sucursal").value,'target:_blank');	
 }   
 
 
 var getConsulta = function(){
	 filtro = "tipo=consulta"+'&sucursal='+$$("sucursal2").value
	 + "&mes=" + $$("mes").value + "&anio=" + $$("anio").value;
	 consultar(filtro,setResultadoConsulta);
 }
 
 var setResultadoConsulta = function(resultado){
	 var datos = resultado.split("---");
	 $$("detalleDisponible").innerHTML = datos[0];
	 $$("detalleGrafico").innerHTML = datos[1];
	 $$("detalleDisponible2").innerHTML = datos[2];
	 $$("detalleGrafico2").innerHTML = datos[3];
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
	
	cantidad = $$("detalleDisponible2").rows.length;
	for (var i=0;i<cantidad;i++){
	  if ($$("detalleDisponible2").rows[i].cells[0].innerHTML == "6"){
		  $$("detalleDisponible2").rows[i].style.display = tipo;
		  $$("detalleGrafico2").rows[i].style.display = tipo;
	  }
	}		 
 }