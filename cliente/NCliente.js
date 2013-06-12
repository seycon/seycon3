// JavaScript Document

  var irDireccion = "listar_cliente.php";
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
 
 var tipoBusqueda = function(e){
	 var sql;	
	  idconsulta = "idservicio";   	  
	  sql = "select idservicio,left(nombre,35)as nombre from servicio where estado=1 and ";		
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
  
  function consultarServidor(filtro,funcion){
   var  pedido = ajax();
   var servidor = "egresos/DEgreso.php";	
   pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText;    
    	  funcion(resultado);   
	   }	   
   }
   pedido.send(null);
  } 
   
  function verificarVendedor(codigo){
    if (codigo != "") {	
       var filtro = "transaccion=consultarPrecios&idservicio="+codigo;
       consultarServidor(filtro,resultadoVendedor);	
    }
  }
  
  var resultadoVendedor = function(resultado) {
	$$("precioservicio").innerHTML = resultado;  
  } 
 
 
  var salir = function() {
	location.href = irDireccion;
  }	 

  var bloquear = function(dato) {
	 if (dato != "Definido") {
		$$("nombreservicio").value = "";
		$$("cantidadservicio").value = "";
		$$("nombreservicio").disabled = "disabled"; 
		document.formValidado.precioservicio.disabled = "disabled"; 
		$$("fechainicio").disabled = "disabled"; 
		$$("fechafinal").disabled = "disabled"; 
		$$("agregar").disabled = "disabled";
		$$("cantidadservicio").disabled = "disabled";
		$$("detalleContrato").innerHTML = "";
	 } else {
		$$("nombreservicio").disabled = ""; 	
		$$("fechainicio").disabled = ""; 
		$$("fechafinal").disabled = ""; 
		$$("agregar").disabled = ""; 
		$$("cantidadservicio").disabled = "";
		document.formValidado.precioservicio.disabled = "";
	 }
  }   
  
  function validar() {	  
	 if (!validarCajas()) {       
		return false;
	 }	 
	 if (!validarFechas()) {       
		return false;
	 }
	 if (!validarNumber()) {       
		return false;
	 }	 
	 if (!isvalidoNumero("recargo")) {
	      openMensaje("Advertencia","El campo recargo es invalido.");   
	      return false;
	 }
	 if (parseFloat($$("recargo").value) < 0) {
	      openMensaje("Advertencia","El campo recargo debe ser mayor a 0.");   
	      return false;
	 }
	 if (parseFloat($$("recargo").value) > 100) {
	      openMensaje("Advertencia","El campo recargo debe ser menor a 100.");   
	      return false;
	 } 	 	 
	 return true;
  }
  
  var validarNumber = function() {
	var ids = ['nit','nroguardias']; 
	var msg = ['nit','Nº de guardias'];
	for (var j = 0; j < ids.length; j++){
	   if (!isvalidoNumero(ids[j])) {
	      openMensaje("Advertencia",'El campo '+msg[j] + " es invalido.");   
	      return false;
	   }	 
	}
	return true;
  } 
  
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
  }
  
  var validarCajas = function() {
	var ids = ['nombre','nit','ruta','idsucursal']; 
	var msg = ['nombre','nit','ruta','sucursal'];
	for (var j = 0; j < ids.length; j++){
	  if ($$(ids[j]).value == "")	{
	      openMensaje("Advertencia",'El campo '+msg[j] + " es requerido.");   
	      return false;
	  }	 
	}
	return true;
  } 
  
  var validarFechas = function() {
	var ids = ['fechacontacto','fechapropietario','fechaaniversario'];
	var msg = ['fecha de contacto','fecha de propietario','fecha de aniversario'];
	for (var j = 0; j < ids.length; j++){
	  if ($$(ids[j]).value != "" && !validarFecha($$(ids[j]).value))	{
	      openMensaje("Advertencia",'Ingrese una '+msg[j] + " valida.");   
	      return false;
	  }	 
	}
	return true;  
  }
  
  function validarFecha(value){  
    var Fecha= new String(value);   
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length));  
    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")));
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")));  
  
    if (isNaN(Ano) || Ano.length<4 || Ano.length>4 || parseFloat(Ano)<1900){  
        return false;  
    }  
 
    if (isNaN(Mes) || parseFloat(Mes)<1 ||  parseFloat(Mes)>12 || Mes.length>2){  
        return false;  
    }  

    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31 || Mes.length>2){  
        return false  ;
    }  
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
        if (Mes==2 && Dia > 28 || Dia>30) {  
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
	       var id = "detalleContrato";
	       var nfilas = $$(id).rows.length;    	
		   json = new Array();
		   for(i = 0; i < nfilas; i++) {
			 vector = [$$(id).rows[i].cells[4].innerHTML, //fecha inicio		
			           $$(id).rows[i].cells[5].innerHTML, //fecha fina 
			           $$(id).rows[i].cells[6].innerHTML, //Servicio								
			           $$(id).rows[i].cells[7].innerHTML, //Precio	
					   $$(id).rows[i].cells[3].innerHTML  //Cantidad					  
					   ]; 	
			 json[i] = vector;	 		
		   }
		   dato = JSON.stringify(json); 

	       $$("datosContrato").value = dato;	  	
           $$("formValidado").submit();
	  }
  }
  
   
   function getfecha(fecha) {
	hoy = new Date()
	var edad = 0;
	if (fecha != "00/00/0000") {	
	  var array_fecha = fecha.split("/")
	  var ano
	  ano = parseInt(array_fecha[2], 10);
	  if (isNaN(ano))
		  return;
	  var mes
	  mes = parseInt(array_fecha[1], 10);
	  if (isNaN(mes))
		  return;
	  var dia
	  dia = parseInt(array_fecha[0], 10);
	  if (isNaN(dia))
		  return;
	  edad = hoy.getFullYear() - ano - 1;
	  
  
	  if (hoy.getMonth() + 1 - mes < 0) {
		   return edad;
	  }
	  if (hoy.getMonth() + 1 - mes > 0) {
		  edad = edad+1;
		  return edad;
	  }
	  if (hoy.getUTCDate() - dia >= 0) {
		  edad = edad + 1
		  return edad;
	  }
	}
     return edad;
  }
  
  var insertarNewItem = function(tabladestino) {
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
    var indice = $$("precioservicio").selectedIndex; 
    var texto = $$("precioservicio").options[indice].text;
    if (validarSubIngreso()){
	  var datosIngreso =[
	  {data:"<img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	  {data:$$("nombreservicio").value, type:"set"},
	  {data:$$("precioservicio").value, type:"set"},
	  {data:$$("cantidadservicio").value, type:"set"},
	  {data:$$("fechainicio").value , type:"set"},
	  {data:$$("fechafinal").value , type:"set"},
	  {data:$$("idservicio").value , type:"set"},
	  {data:$$("precioservicio").value , type:"set"}
	  ];
      cargarDatos(formato, datosIngreso, tabladestino);
	  limpiarContrato();
	}
  }
  
  var getFormatoColumna = function() {
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'si', aling : 'left'},
	{type : 'normal', numerico : 'si', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display: 'none'},
	{type : 'normal', numerico : 'no', aling : 'center', display: 'none'}
	];
	return formato;	
 }  
  
  var limpiarContrato = function() {
	$$("nombreservicio").value = "";
	$$("idservicio").value = "";  
	$$("cantidadservicio").value = "";
	$$("precioservicio").innerHTML = "<option value=''>--Seleccione--</option>";
	$$("nombreservicio").focus();
  }
  
  var validarSubIngreso = function() {
	if ($$("idservicio").value == "") {
		openMensaje("Advertencia",'Debe seleccionar el tipo de servicio. ');   
	   return false;
	}  
	if ($$("precioservicio").value == "") {
	   openMensaje("Advertencia",'Debe seleccionar el precio del servicio. ');   
	   return false;	
	}	
	if ($$("fechainicio").value == "") {
	   openMensaje("Advertencia",'Debe ingresar la fecha de inicio. ');   
	   return false;	
	}	
	if ($$("fechafinal").value == "") {
		openMensaje("Advertencia",'Debe ingresar la fecha final.');   
	   return false;
	}	
	if ($$("fechainicio").value != "" && !validarFecha($$("fechainicio").value)) {		
	   openMensaje("Advertencia",'Ingrese una fecha de inicio valida.');   
	   return false;	
	}
	if ($$("fechafinal").value != "" && !validarFecha($$("fechafinal").value)) {		
	   openMensaje("Advertencia",'Ingrese una fecha final valida.');   
	   return false;	
	}
	return true;
  }
   
  var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
    table.removeChild(tr);
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
  
  var desconvertirFormatoNumber = function(cadena){
	  convertir = "";     
	  for(i = 0;i < cadena.length;i++){		
		  if (cadena[i] != ",")
		  convertir = convertir + cadena[i];
	  }
	  return convertir;
  }

