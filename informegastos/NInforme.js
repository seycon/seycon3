  var servidor = 'informegastos/DInforme.php';
  var irDireccion = "listar_informegasto.php#t3";
  var totalTransaccion = 0;
  var transaccion = "insertar";
  var idTransaccion;

 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2
	  if ($$('overlay').style.visibility != "visible")
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
	 if ($$('rendicion').value==''){
		 openMensaje("Advertencia",'Ingrese el monto de rendicion');
		 return false;
	 }	 

     if (!isvalidoNumero('rendicion')){
		openMensaje("Advertencia",'Ingrese un numero valido en monto de rendicion');
		 return false; 
	 }
	 if ($$('detalleTransaccion').rows.length < 1){
		 openMensaje("Advertencia",'Debe Ingresar Detalle del Informe');
		 return false;
	 }	 
	 return true;	 
  }

  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
  }

  var validarSubIngreso = function(){
	if (!isvalidoNumero("montobs")){
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
		var dato = JSON.stringify(json);
		filtro = 'idinforme='+$$('idinforme').value+'&fecha='+$$('fecha').value+'&nrodocumentos='+$$U('nrodocumentos')
		 +'&montorendicion='+
		 $$('rendicion').value+'&privado='+$$('privado').checked+'&comentario='+$$U('comentario')+"&transaccion="+transaccion
		 +"&detalle="+encodeURIComponent(dato)+"&firma="+$$('firmadigital').checked;
		enviar(filtro,resultadoEjecutarTransaccion);	   
	  }
  }
  
  var $$U = function(id){
       return encodeURIComponent($$(id).value);	
  }
  
  var resultadoEjecutarTransaccion = function(resultado){
	  idTransaccion = resultado;
      $$('modal_vendido').style.visibility = 'visible';
      $$('gif').style.visibility = "hidden";
  }
  
  var insertarNewItem = function(tabladestino){
    if (validarSubIngreso()){	  
	  var formato = getFormatoColumna();
	  var n =  $$(tabladestino).rows.length + 1;
	  var datosIngreso =[
	  {data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	  {data :n , type:"set"},
	  {id :"fechadetalle", type:"get" },
	  {id :"descripcion", type:"get"},
	  {id :"documento", type:"get"},
	  {id :"montobs", type:"get"}
	  ];
	  var total = cargarDatos(formato,datosIngreso,tabladestino);
	  if (total.length > 0)
	  cargarTotales(total[0]);
	}
}
  
var cargarTotales = function(total){
	totalTransaccion = parseFloat(totalTransaccion) + parseFloat(total);
	$$('total_ingreso').value = parseFloat(totalTransaccion).toFixed(2);
	$$("descripcion").focus();	
}  
  
var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center'}	
	];
	return formato;	
}

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
	cargarTotales(-desconvertirFormatoNumber(tr.cells[5].innerHTML));	
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
	 window.open('informegastos/imprimir_informeGastos.php?idinforme='+idTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
	 cerrarPagina();
 } 
 
 function cerrarPagina(){
   location.href = 'nuevo_informegastos.php';
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

  var seleccionarCheck =  function(formulario,check,estado){
	  var form = eval("document."+formulario+"."+check);
	  var e = (estado == true) ? 'check' : '' ;
	  form.checked = e;
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