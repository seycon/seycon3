// JavaScript Document

var servidorPrincipal= "configuracion/DConfiguracion.php";

var $$ = function(id){
 return document.getElementById(id);	
}

function soloNumeros(evt){
var tecla = (document.all) ? evt.keyCode : evt.which;
return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
}

var insertarNewItem = function(tabladestino,descripcion){
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var id;
	var id2;
	switch(descripcion){
	 case "descripcionS1":
	   id = "DS1_" + n;
	 break;	
	 case "descripcionS2":
	   id = "DS2_" + n;
	 break;
	 case "descripcionS3":
	   id = "DS3_" + n;
	 break;			
	 case "descripcionS4":
	   id = "DS4_" + n;
	   id2 = "DS8_" + n;
	   formato = getFormatoColumnaPorPagar();
	 break;			
	 case "descripcionS5":
	   id = "DS5_" + n;
	 break;		
	  case "descripcionPS5":
	   id = "DPS5_" + n;
	 break;		
	 case "descripcionS6":
	   id = "DS6_" + n;
	 break;			
	 case "descripcionS7":
	   id = "DS7_" + n;
	 break;	
	}
	
    if ( descripcion == "descripcionS4"){
	  var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data:id, type:"set"},
	{id :descripcion, type:"get"},
	{data :"<select id='"+id+"' style='width:190px;'></select>" , type:"set"},
	{data :"<select id='"+id2+"' style='width:190px;'></select>" , type:"set"}
    ];
    cargarDatos(formato,datosIngreso,tabladestino);
    $$(id).innerHTML = $$("modeloplan").innerHTML;	
	$$(id2).innerHTML = $$("modeloplan").innerHTML;	
	}else{
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data:id, type:"set"},
	{id :descripcion, type:"get"},
	{data :"<select id='"+id+"' style='width:190px;'></select>" , type:"set"}
    ];
    cargarDatos(formato,datosIngreso,tabladestino);
    $$(id).innerHTML = $$("modeloplan").innerHTML;
	}
}

var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'}
	];
	return formato;	
}

var getFormatoColumnaPorPagar = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'}
	];
	return formato;	
}

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
    table.removeChild(tr);
}

function ajaxx() {
	 return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
}

var enviar = function(filtro,funcion){
	 peticion = ajaxx(); 
	 peticion.open('GET', servidorPrincipal+"?"+filtro, true);	 	 
	 peticion.onreadystatechange = function() { 	
	   if (peticion.readyState == 4) {
		  resultado = peticion.responseText;
		  if (funcion != null)
		  funcion(resultado);		  
	   } 
	}
	peticion.send(null); 
  }

var resultadoEjecutarTransaccion = function(resultado){
 $$('overlay').style.visibility = "hidden";
 $$('gif').style.visibility = "hidden";	
 $$("mensaje").innerHTML = "Sus Datos Fueron Guardados Correctamente.";	
}

var insertarCuenta = function(evt,id){
var tecla = (document.all) ? evt.keyCode : evt.which;	
 if (tecla == 13){
  	$$(id).click();
  }
}


 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }

function validarVentanaInventario(){
  $$("msjp4").style.visibility = "hidden";
  var float = true;
   if (!isvalidoNumero("porcentajeCF")){
	  $$("msjp4").style.visibility = "visible";
	  $$("msjp4").innerHTML = "Invalido"; 
	  float = false;		
	}
  if (float){	
  	$$("msjp4").style.visibility = "hidden";	
  }
	return float;	
}

function validarVentas(){
    $$("msjp1").style.visibility = "hidden";
	$$("msjp2").style.visibility = "hidden";
	$$("msjp3").style.visibility = "hidden";
	$$("msjp5").style.visibility = "hidden";
	var float = true;
    if (!isvalidoNumero("porcentajeITG")){
	  $$("msjp1").style.visibility = "visible";
	  $$("msjp1").innerHTML = "Invalido"; 
	  float = false;		
	}
	if (!isvalidoNumero("porcentajeITP")){
	  $$("msjp2").style.visibility = "visible";
	  $$("msjp2").innerHTML = "Invalido"; 
	  float = false;		
	}
	if (!isvalidoNumero("porcentajeDF")){
	  $$("msjp3").style.visibility = "visible";
	  $$("msjp3").innerHTML = "Invalido"; 
	  float = false;		
	}		
	if (!isvalidoNumero("porcreditoporpagar")){
	  $$("msjp5").style.visibility = "visible";
	  $$("msjp5").innerHTML = "Invalido"; 
	  float = false;		
	}
	if (float){	
	$$("msjp1").style.visibility = "hidden";
	$$("msjp2").style.visibility = "hidden";
	$$("msjp3").style.visibility = "hidden";
	$$("msjp5").style.visibility = "hidden";
	}
	return float;
}

function validarSubVentana(){	
	$$("msjp1").style.visibility = "hidden";
	$$("msjp2").style.visibility = "hidden";
	$$("msjp3").style.visibility = "hidden";
	$$("msjp4").style.visibility = "hidden";
	$$("msjp5").style.visibility = "hidden";
	var float = true;

	if (!isvalidoNumero("porcentajeITG")){
	  $$("msjp1").style.visibility = "visible";
	  $$("msjp1").innerHTML = "Invalido"; 
	  float = false;		
	}
	if (!isvalidoNumero("porcentajeITP")){
	  $$("msjp2").style.visibility = "visible";
	  $$("msjp2").innerHTML = "Invalido"; 
	  float = false;		
	}
	if (!isvalidoNumero("porcentajeDF")){
	  $$("msjp3").style.visibility = "visible";
	  $$("msjp3").innerHTML = "Invalido"; 
	  float = false;		
	}	
	if (!isvalidoNumero("porcentajeCF")){
	  $$("msjp4").style.visibility = "visible";
	  $$("msjp4").innerHTML = "Invalido"; 
	  float = false;		
	}
	if (!isvalidoNumero("porcreditoporpagar")){
	  $$("msjp5").style.visibility = "visible";
	  $$("msjp5").innerHTML = "Invalido"; 
	  float = false;		
	}	
	
	if (float){	
	   $$("msjp1").style.visibility = "hidden";
	   $$("msjp2").style.visibility = "hidden";
	   $$("msjp3").style.visibility = "hidden";
	   $$("msjp4").style.visibility = "hidden";
	   $$("msjp5").style.visibility = "hidden";
	}
	return float;
 }


function ejecutarTransaccioninventario(){
	  var cadena;
	  var iddetalle = ['detalleS1','detalleS2'];
	  var datosG = new Array();
	  if (validarVentanaInventario()){
	   $$('overlay').style.visibility = "visible";
       $$('gif').style.visibility = "visible";
	  for (var j=0;j<iddetalle.length;j++){
		  nfilas = $$(iddetalle[j]).rows.length;		 	    	
         json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0]=$$(iddetalle[j]).rows[i].cells[2].innerHTML;		
		    var id = $$(iddetalle[j]).rows[i].cells[1].innerHTML;
			vector[1]= $$(id).value;			
		    json[i] = vector;	 		
          }
          dato = JSON.stringify(json); 	
		  datosG[j] = dato;
	  }	
    	       cadena = "tipo=inventario&anticipoproveedor="+$$('anticipoproveedor').value
               +"&proveedorpagar="+$$('proveedorpagar').value+'&creditofiscal='+$$("creditofiscal").value
			   +"&porcentajeCF="+$$('porcentajeCF').value
			   +"&costooperativo="+$$("costooperativo").value+"&detalleingreso="+datosG[0]+"&detalleegreso="+datosG[1];			   
               enviar(cadena,resultadoEjecutarTransaccion);   	
	  }
}


function ejecutarTransaccioncontable(){
	 $$('overlay').style.visibility = "visible";
     $$('gif').style.visibility = "visible";
	 var cadena;
	  var iddetalle = ['detalleS6','detalleS7'];
	  var datosG = new Array();
	  for (var j=0;j<iddetalle.length;j++){
		  nfilas = $$(iddetalle[j]).rows.length;		 	    	
         json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0]=$$(iddetalle[j]).rows[i].cells[2].innerHTML;		
		    var id = $$(iddetalle[j]).rows[i].cells[1].innerHTML;
			vector[1]= $$(id).value;			
		    json[i] = vector;	 		
          }
          dato = JSON.stringify(json); 	
		  datosG[j] = dato;
	  }	
	  cadena = 'tipo=contabilidad'+"&detalleIngresoDinero="+datosG[0]+"&detalleEgresoDinero="+datosG[1];
	  enviar(cadena,resultadoEjecutarTransaccion);   
}




function ejecutarTransaccionventa(){
      var iddetalle = ['detalleS5','detallePS5'];
	  var datosG = new Array();
	  if (validarVentas()){
  	   $$('overlay').style.visibility = "visible";
       $$('gif').style.visibility = "visible";	  
	  for (var j=0;j<iddetalle.length;j++){
		  nfilas = $$(iddetalle[j]).rows.length;		 	    	
         json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0]=$$(iddetalle[j]).rows[i].cells[2].innerHTML;		
		    var id = $$(iddetalle[j]).rows[i].cells[1].innerHTML;
			vector[1]= $$(id).value;			
		    json[i] = vector;	 		
          }
          dato = JSON.stringify(json); 	
		  datosG[j] = dato;
	  }	
	  
	   nfilas = $$('detalleS4').rows.length;		 	    	
          json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0] = $$('detalleS4').rows[i].cells[2].innerHTML;		
		    var id = $$('detalleS4').rows[i].cells[1].innerHTML;
			vector[1]= $$(id).value;			
			var cadenaId = id.split("_");
			id = "DS8_"+cadenaId[1];
			vector[2] = $$(id).value;
		    json[i] = vector;	 		
          }
          datoPorPagar = JSON.stringify(json);		 

	
   var cadena = 'tipo=ventas&itgastos='+$$('itgastos').value+"&itpasivo="+$$('itpasivo').value+"&costoventa="+$$('costoventa').value
			   +"&inventario="+$$("inventario").value+"&porcentajeDF="+$$('porcentajeDF').value+
			   "&porcentajeITG="+$$('porcentajeITG').value+"&porcentajeITP="+$$('porcentajeITP').value+"&libroCV="+$$('libroCV').value
			   +"&libroCVproducto="+$$('libroCVproducto').value+'&descuentoventa='+$$('descuentoventa').value
			   +"&recargo="+$$('recargo').value+'&debitofiscal='+$$('debitofiscal').value+"&cajalibroCV="+$$("cajalibroCV").value
			   +"&clientescobrar="+$$('clientescobrar').value+"&anticipocliente="+$$('anticipocliente').value
			   +"&devolucion="+$$('devolucion').value+'&detallePorCobrar='+datosG[0]+'&detallePasivoPorCobrar='+datosG[1]
			   +"&detallePorPagar="+datoPorPagar+
			   "&porcreditoporpagar="+$$("porcreditoporpagar").value+"&creditofiscalporpagar="+$$("creditofiscalporpagar").value; 
	enviar(cadena,resultadoEjecutarTransaccion);   
	  }
}

function ejecutarTransaccionrecursos(){
	$$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";	
	var cadena = "tipo=recursos&anticiposueldo="+$$('anticiposueldo').value
			   +"&recargoinformar="+$$('recargoinformar').value+"&sueldossalarios="+$$('sueldossalarios').value
			   +"&bonoantiguedad="+$$('bonoantiguedad').value
			   +"&bonoproduccion="+$$('bonoproduccion').value+"&horasextras="+$$('horasextras').value
			   +"&otrosbonos="+$$('otrosbonos').value
			   +"&salariospagar="+$$('salariospagar').value+"&aporteretenciones="+$$('aporteretenciones').value
			   +"&seguromedico="+$$("seguromedico").value+
			   "&aportepatronal="+$$("aportepatronal").value+"&aportelaboral="+$$("aportelaboral").value
			   +"&aguinaldoporpagar="+$$("aguinaldoporpagar").value+"&aguinaldo="+$$('aguinaldo').value;
			   
   enviar(cadena,resultadoEjecutarTransaccion);			   
}

function ejecutarTransaccionactivo(){
  	  $$('overlay').style.visibility = "visible";
      $$('gif').style.visibility = "visible";	
	  var iddetalle = ['detalleS3'];
	  var datosG = new Array();
	  for (var j=0;j<iddetalle.length;j++){
		  nfilas = $$(iddetalle[j]).rows.length;		 	    	
         json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0]=$$(iddetalle[j]).rows[i].cells[2].innerHTML;		
		    var id = $$(iddetalle[j]).rows[i].cells[1].innerHTML;
			vector[1]= $$(id).value;			
		    json[i] = vector;	 		
          }
          dato = JSON.stringify(json); 	
		  datosG[j] = dato;
	  }		
	  var dato = "tipo=activo&detalle="+datosG[0];
      enviar(dato,resultadoEjecutarTransaccion);
}


function ejecutarTransaccion(){
	if (validarSubVentana()){
 	  $$('overlay').style.visibility = "visible";
      $$('gif').style.visibility = "visible";	
	  var cadena;
	  var iddetalle = ['detalleS1','detalleS2','detalleS3','detalleS5','detalleS6','detalleS7'];
	  var datosG = new Array();
	  for (var j=0;j<iddetalle.length;j++){
		  nfilas = $$(iddetalle[j]).rows.length;		 	    	
         json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0]=$$(iddetalle[j]).rows[i].cells[2].innerHTML;		
		    var id = $$(iddetalle[j]).rows[i].cells[1].innerHTML;
			vector[1]= $$(id).value;			
		    json[i] = vector;	 		
          }
          dato = JSON.stringify(json); 	
		  datosG[j] = dato;
	  }	
	 
		  nfilas = $$('detalleS4').rows.length;		 	    	
          json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0] = $$('detalleS4').rows[i].cells[2].innerHTML;		
		    var id = $$('detalleS4').rows[i].cells[1].innerHTML;
			vector[1]= $$(id).value;			
			var cadenaId = id.split("_");
			id = "DS8_"+cadenaId[1];
			vector[2] = $$(id).value;
		    json[i] = vector;	 		
          }
          datoPorPagar = JSON.stringify(json);		 
	  
		    
	       cadena = 'nuevo=nuevo&descuentoventa='+$$('descuentoventa').value+'&recargo='+$$('recargo').value
		   +'&debitofiscal='+$$('debitofiscal').value+
               '&creditofiscal='+$$("creditofiscal").value+'&itgastos='+$$('itgastos').value+"&itpasivo="
			   +$$('itpasivo').value+"&costoventa="+$$('costoventa').value
			   +"&inventario="+$$("inventario").value+"&porcentajeDF="+$$('porcentajeDF').value+"&porcentajeCF="+$$('porcentajeCF').value
			   +"&porcentajeITG="+$$('porcentajeITG').value+"&porcentajeITP="+$$('porcentajeITP').value+"&libroCV="+$$('libroCV').value+
			   "&libroCVproducto="+$$('libroCVproducto').value+"&anticipoproveedor="+$$('anticipoproveedor').value
			   +"&proveedorpagar="+$$('proveedorpagar').value+"&anticiposueldo="+$$('anticiposueldo').value
			   +"&recargoinformar="+$$('recargoinformar').value+"&sueldossalarios="+$$('sueldossalarios').value
			   +"&salariospagar="+$$('salariospagar').value
			   +"&clientescobrar="+$$('clientescobrar').value+"&anticipocliente="+$$('anticipocliente').value
			   +"&devolucion="+$$('devolucion').value+"&aguinaldo="+$$('aguinaldo').value+"&bonoantiguedad="+$$('bonoantiguedad').value
			   +"&bonoproduccion="+$$('bonoproduccion').value+"&horasextras="+$$('horasextras').value+"&otrosbonos="+$$('otrosbonos').value
			   +"&detalle="+datosG[0]+"&detalleEgreso="+datosG[1]+"&detalleBajaActivo="+datosG[2]+"&detallePorPagar="+datoPorPagar
			   +"&detallePorCobrar="+datosG[3]+"&detalleIngresoDinero="+datosG[4]+"&detalleEgresoDinero="+datosG[5]
			   +"&aporteretenciones="+$$('aporteretenciones').value+"&seguromedico="+$$("seguromedico").value+
			   "&aportepatronal="+$$("aportepatronal").value+"&aportelaboral="+$$("aportelaboral").value
			   +"&aguinaldoporpagar="+$$("aguinaldoporpagar").value+"&costooperativo="+$$("costooperativo").value
			   +"&cajalibroCV="+$$("cajalibroCV").value+"&creditofiscalporpagar="+$$("creditofiscalporpagar").value
			   +"&porcreditoporpagar="+$$("porcreditoporpagar").value;	   			  
           enviar(cadena,resultadoEjecutarTransaccion);   
	}
  }