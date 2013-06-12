// JavaScript Document
  
  var servidor = 'proyecto/DProyecto.php';
  var irDireccion = "listar_proyecto.php#t6";
  var totalTransaccion = {horas :0,costo :0 };
  var transaccion = "insertar";
  var idTransaccion;


  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2
	  if($$('overlay').style.visibility != "visible")
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
		openMensaje("Advertencia","Debe Ingresar el Titulo"); 
		return false;
	 }
	if ($$('detalleTransaccion').rows.length < 1){
		openMensaje("Advertencia","Debe ingresar el detalle del Proyecto"); 
		return false;
	 }
	 return true;
  }

  var validarSubIngreso = function(){
	if (!isvalidoNumero("horas")){
	  openMensaje("Advertencia","Las Horas ingresadas son incorrectas.");
	  return false;
	}
	if (!isvalidoNumero("costo")){
	  openMensaje("Advertencia","El monto en bolivianos ingresado es incorrecto.");
	  return false;
	}

	return true; 
 }

 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }


  var $$ = function(id){
	return document.getElementById(id);  
  }
  
  
  function enterInput(e){
    tecla = (document.all) ? event.keyCode : e.which; 
	   if (tecla == 13){
          insertarNewItem("detalleTransaccion");
		  $$("descripcion").focus();
       }
  }

  
  function ejecutarTransaccion(){
	var n;  
	  if (validar()){
		$$('overlay').style.visibility = "visible";
        $$('gif').style.visibility = "visible";
  
      nfilas = $$('detalleTransaccion').rows.length;		 	    	
      json = new Array();
         for(var i=0; i<nfilas; i++) {
	        vector = new Array();
		    vector[0] = getData("detalleTransaccion",i,2);		
		    vector[1] = getData("detalleTransaccion",i,3);
			vector[2] = getData("detalleTransaccion",i,4);	
			vector[3] = getData("detalleTransaccion",i,5);				
		    json[i] = vector;	 		
         }
        dato = JSON.stringify(json);
		
	 filtro ="titulo="+$$U('titulo')+"&fechainicio="+$$('fechaincio').value+"&fechafin="+$$('fechafinalizacion').value+
     "&terminado="+$$('terminadoP').checked+"&privado="+$$('privadoP').checked+"&recursos="+$$U('recursos')
	 + "&glosa="+$$U('glosa')+"&avanceP="+$$('avanceP').value
	 + "&idproyecto="+$$('codProyecto').value+"&total="+$$('Tcosto').value+"&presupuesto="+$$U('presupuesto')
	 + "&presupuestoUtil="+$$U('presupuestoUtil')
	 + "&transaccion="+transaccion+"&detalle="+encodeURIComponent(dato);	   
	 enviar(filtro,resultadoEjecutarTransaccion);
	 }
  }
  
  var $$U = function(id) {
    return encodeURIComponent($$(id).value);	
  }  
  
  var resultadoEjecutarTransaccion = function(resultado){
	 datos = resultado.split("---"); 
	  if (datos[1] == "1"){
		idTransaccion = datos[0];	  
        $$('modal_vendido').style.visibility = 'visible';
        $$('overlay_vendido').style.visibility = 'visible';
		$$('overlay').style.visibility = "hidden";
        $$('gif').style.visibility = "hidden";
	  }else{
		cerrarPagina();  
	  }
  }
  
  var insertarNewItem = function(tabladestino){
	if (validarSubIngreso()){  
	  var formato = getFormatoColumna();
	  var n =  $$(tabladestino).rows.length + 1;
	  var datosIngreso =[
	  {data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	  {data :n , type:"set"},
	  {id :"descripcion", type:"get" },
	  {id :"fecha", type:"get"},
	  {id :"horas", type:"get"},
	  {id :"costo", type:"get"}
	  ];
	  var total = cargarDatos(formato,datosIngreso,tabladestino);
	  if (total.length > 0)
	  cargarTotales(total[0],total[1]);
	}
  }
  
  var cargarTotales = function(horas,costo){
	totalTransaccion.horas = parseFloat(totalTransaccion.horas) + parseFloat(horas);
	totalTransaccion.costo = parseFloat(totalTransaccion.costo) + parseFloat(costo);
	$$('Thoras').value = parseFloat(totalTransaccion.horas).toFixed(2);
	$$('Tcosto').value = parseFloat(totalTransaccion.costo).toFixed(2);
	$$("descripcion").focus();	
  }  
  
  var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center'}	
	];
	return formato;	
  }

  var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
	cargarTotales(-desconvertirFormatoNumber(tr.cells[4].innerHTML),-desconvertirFormatoNumber(tr.cells[5].innerHTML));	
    table.removeChild(tr);
	orderNumeroItem();
  }

  var orderNumeroItem = function(){
	var n =  $$("detalleTransaccion").rows.length;
	for (var i=0;i<n ;i++){
		$$("detalleTransaccion").rows[i].cells[1].innerHTML = i+1;
	}
  }  
  
  function accionPostRegistro(){
    window.open('proyecto/imprimir_proyecto.php?idproyecto='+idTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
	cerrarPagina();
  } 
 
  function cerrarPagina(){
   location.href = 'nuevo_proyecto.php';
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

  function soloNumeros(evt){
    var tecla = (document.all) ? evt.keyCode : evt.which;
    return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
  }
 