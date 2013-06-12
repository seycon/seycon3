// JavaScript Document

 var servidor = "anticipo/DAnticipo.php";


 var $$ = function(id){
   return document.getElementById(id);	 
 }

  var ajax = function(){
	return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"
	); 	
  }
  
  function consultar(parametros, funcion){
   var  pedido = ajax();	
   filtro = parametros; 
   pedido.open("GET",servidor+"?"+filtro,true);
	 pedido.onreadystatechange = function(){
		 if (pedido.readyState == 4){     	
			var resultado = pedido.responseText; 
			funcion(resultado);   
		 }	   
	 }
	 pedido.send(null);
  }
  
  function consultarTrabajadores(){
	var parametros = "";
	 if ($$("idsucursal").value != "" ){
		parametros = "transaccion=trabajadores&idsucursal=" + $$("idsucursal").value; 
		consultar(parametros,cargarTrabajadores);
	 }
  }
  
  function cargarTrabajadores(resultado){
	  $$("idtrabajador").innerHTML = resultado;
  }
  
  function cargarSueldo(resultado){
	  var datos = resultado.split("---");
	  $$("sueldobasico").value = datos[0];
	  $$("anticiposanteriores").value = datos[1];
  }
  
  function consultarSueldo(){
	var parametros = "";
	  if (validarSubConsulta()){
		 parametros = "transaccion=sueldo&idtrabajador=" + $$("idtrabajador").value
		 + "&fecha=" + $$("fecha").value; 	
		 consultar(parametros,cargarSueldo);
	  }
  }
  
  var consultarAnticipos = function(){	  
	  if (validarFecha($$("fecha").value) && $$("idtrabajador").value != "") {
	     var filtro = "transaccion=anticipos&idtrabajador=" + $$("idtrabajador").value
		 + "&fecha=" + $$("fecha").value;
	     consultar(filtro, cargarAnticipo);	
	  } 
  }
  
  
  var validarSubConsulta = function() {
	 if (!validarFecha($$("fecha").value)) {
		openMensaje("Advertencia","Fecha Invalida, ingrese en formato dd/mm/yyyy."); 
		return false; 
	 }
	 if ($$("idtrabajador").value == ""){
		openMensaje("Advertencia","Debe Seleccionar un trabajador."); 
		return false;  
	 }
	  return true;
  }
  
  
   function validarFecha(value){  
    var Fecha= new String(value);   
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length));  
    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")));
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")));  
  
    if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
        return false;  
    }  
 
    if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
        return false;  
    }  

    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
        return false  ;
    }  
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
        if (Mes==2 && Dia > 28 || Dia>30) {  
            return false;  
        }  
    }      

    return true;   
   } 
  
  var cargarAnticipo = function(resultado) {
	 $$("anticiposanteriores").value = resultado; 
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
  
  var $$U = function(id){
	return encodeURIComponent($$(id).value);	
  }


 function ejecutarTransaccion(){	
    if (datosValidos()) {
	    $$('overlay').style.visibility = "visible";
        $$('gif').style.visibility = "visible";		
		var filtro = "transaccion="+$$("transaccion").value+"&idsucursal="+$$("idsucursal").value 
		+"&fecha="+$$("fecha").value
		+"&egreso="+$$("egreso").value+"&documento="+$$("documento").value+"&glosa="+$$U("glosa")
		+"&idtrabajador="+$$("idtrabajador").value + "&sueldobasico="+$$("sueldobasico").value
		+"&idanticipo="+$$("idanticipo").value + "&anticipo="+$$("anticipo").value
		+"&tipoCambioBs="+$$("tipoCambioBs").value ;
	  	consultar(filtro, resultadoTransaccion);
	}
 }
 
  var resultadoTransaccion = function(resultado) 
 {
	location.href = "nuevo_anticipo.php";
 }
 
 
  var datosValidos = function(){	  
  if (!validarFecha($$("fecha").value)) {
	openMensaje("Advertencia","Fecha Invalida, ingrese en formato dd/mm/yyyy."); 
	return false; 
  }
  	  
  if ($$("idsucursal").value == ""){	
    openMensaje("Advertencia","Debe Seleccionar una sucursal.");
	return false;
  }
  
  if ($$("egreso").value == ""){
	openMensaje("Advertencia","Debe seleccionar la cuenta Caja/Banco");
	return false;  
  }
  
  if ($$("idtrabajador").value == ""){
	openMensaje("Advertencia","Debe Seleccionar un trabajador");  
    return false;
  }
  
  if ($$("anticipo").value == "" || parseFloat($$("anticipo").value) <= 0){
	openMensaje("Advertencia","Debe ingresar el monto del anticipo");  
    return false;
  }
  
  if (!isvalidoNumero("anticipo")){
	openMensaje("Advertencia","Debe ingresar un monto valido del anticipo.");  
    return false;	  
  }
  
  var sueldo = parseFloat(desconvertirFormatoNumber($$("sueldobasico").value));
  var adelantos = parseFloat(desconvertirFormatoNumber($$("anticiposanteriores").value));
  var anticipo = parseFloat($$("anticipo").value);
  if ((anticipo + adelantos) > sueldo) {
	openMensaje("Advertencia","No es posible dar anticipos que excedan al sueldo basico.");  
    return false;  
  }
  
  return true;
 }
 
 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }
 
 
 var desconvertirFormatoNumber = function(cadena){
	convertir = "";     
	for(i = 0;i < cadena.length;i++){		
		if (cadena[i] != ",")
		convertir = convertir + cadena[i];
	}
	return convertir;
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