// JavaScript Document

  var servidor = "activo/DBajaactivo.php";

  function calcularTotal()
  {
	var cantidad = ($$("cantidad").value == "") ? 0 : $$("cantidad").value;  
    var precio = ($$("cantidadbaja").value == "") ? 0 : $$("cantidadbaja").value;  
	total = cantidad - precio;
	$$("total").value = total.toFixed(2);
  }
  
  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115){ //F4
    if ($$("cancelar") != null)
	  document.getElementById("cancelar").click();
   }
	
   if(tecla == 113){ //F2
	 document.getElementById("enviar").click();
	  
	}
  }


 var $$ = function(id){
  return document.getElementById(id);	 
 }

  var ajax = function(){
	return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
  }
  
  function consultar(parametros,funcion){
   var  pedido = ajax();	
   filtro = parametros; 
   pedido.open("GET",servidor+"?"+filtro,true);
	 pedido.onreadystatechange = function(){
		 if (pedido.readyState == 4){     	
			var resultado = pedido.responseText.split("---"); 
			funcion(resultado[0],resultado[1],resultado[2]);   
		 }	   
	 }
	 pedido.send(null);
  }
  
  function consultarActivos(){
	var parametros = "";
	 if ($$("idsucursal").value != "" ){
		parametros = "transaccion=activos&idsucursal=" + $$("idsucursal").value; 
		consultar(parametros,cargarDatos);
	 }
  }
  
  function cargarDatos(resultado,res1,res2){
	  $$("idactivo").innerHTML = resultado;
  }
  
  
  function consultarDatosActivos(){
	var parametros = "";
	 if ($$("idactivo").value != "" ){
		parametros = "transaccion=datosactivos&idactivo=" + $$("idactivo").value; 
		consultar(parametros,cargarDatosActivo);
	 } else {
		$$("responsable").value = "";
		$$("ubicacion").value = "";
		$$("cantidad").value = "0";   
		calcularTotal();
	 }
  }
  
  function cargarDatosActivo(responsable,ubicacion,cantidad){
	  $$("responsable").value = responsable;
	  $$("ubicacion").value = ubicacion;
	  $$("cantidad").value = cantidad;
  }
  
   var seleccionarCombo = function(combo,opcion){	 
	   var cb = document.getElementById(combo);
	   for (var i=0;i<cb.length;i++){
		  if (cb[i].value==opcion){
		  cb[i].selected = true;
		  break;
		  }
	   }	 
   }
   
  function soloNumeros(evt){
    var tecla = (document.all) ? evt.keyCode : evt.which;
    return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
  }