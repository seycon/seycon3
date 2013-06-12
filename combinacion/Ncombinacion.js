// JavaScript Document

var total_bolivianos = 0;
var total_Dolares = 0;
var codigo_solicitud = 0;
var transaccion = 'insertar';
var servidor = "combinacion/Dcombinacion.php?";
var irDireccion = "listar_combinacion.php#t11";

  var $$ = function(id){
    return document.getElementById(id);	 
  }

  var ajax = function(){
	return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
  }

//teclas de atajo
  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   
   if (tecla == 115){ //F4
    if ($$("cancelar") != null)
	 $$("cancelar").onclick();
   }
	
	if(tecla == 113){ //F2
	 if($$('overlay').style.visibility != "visible")
	  enviarDetalle();	  
	}
  }


  function consultar(parametros,funcion){
   var  pedido = ajax();	
   filtro ="transaccion=consulta&codigo="+parametros; 
   pedido.open("GET",servidor+filtro,true);
	 pedido.onreadystatechange = function(){
		 if (pedido.readyState == 4){     	
			var resultado = pedido.responseText.split("---"); 
			funcion(resultado[0],resultado[1],resultado[2],resultado[3]);   
		 }	   
	 }
	 pedido.send(null);
  }

  var limpiarFormulario = function(){
	  $$("form1").reset(); 
	  $$("detalleSolicitud").innerHTML=""; 
	  $$("subtotalBS").innerHTML=""; 
	  $$("subtotalDL").innerHTML="";
	  total_bolivianos = 0;
	  total_Dolares = 0; 
	  document.location.href="nuevo_solicitud.php"; 
  }

  
  var eventoIngresoCantidad = function(evento){
	  var tecla = (document.all) ? evento.keyCode : evento.which;		
	  if (tecla == 13)
		validarEntradaVentana();
  }

  function datosValidos(){
	  if($$("nombre").value == ""){
		openMensaje("Advertencia","Debe ingresar el nombre de la combinación");
		return false;	
	  }
	  if($$("tipocombinacion").value == ""){
		openMensaje("Advertencia","Debe seleccionar el tipo de combinación");
		return false;	
	  }
	  if($$('detalleSolicitud').rows.length == 0 
	  && $$("tipocombinacion").options[$$("tipocombinacion").selectedIndex].text != "Sin Descuento en Producto"){
		openMensaje("Advertencia","Debe ingresar el detalle de la combinación");
		return false;	
	  }
	  return true;
  }

  var $$U = function(id){
	return encodeURIComponent($$(id).value);	
  }


  var enviarDetalle = function() {	
	 if(datosValidos()){
	   $$('overlay').style.visibility = "visible"; 
	   $$('gif').style.visibility = "visible"; 
	   nfilas = $$('detalleSolicitud').rows.length;   	
	   json = new Array();
	   for(i=0;i<nfilas;i++) {
		 vector = new Array();
			  vector[0]=$$('detalleSolicitud').rows[i].cells[1].innerHTML;		
			  vector[1]=$$('detalleSolicitud').rows[i].cells[3].innerHTML;							
			  vector[2]=$$('detalleSolicitud').rows[i].cells[4].innerHTML;  
			  vector[3]=$$('detalleSolicitud').rows[i].cells[5].innerHTML; 
			  vector[4]=$$('detalleSolicitud').rows[i].cells[6].innerHTML;  			
			  json[i] = vector;	 		
	   }
	   dato = JSON.stringify(json); 		 
	   datos = 'detalle='+dato+'&nombre='+$$U("nombre")+'&tipocombinacion='+$$("tipocombinacion").value
	   +'&glosa='+$$U('glosa')+'&total='+$$('subtotalBS').value+'&transaccion='+transaccion
	   +'&idcombinacion='+$$("idcombinacion").value; 	 	
	   enviar(datos);
	 }
  }


  function enviar(datos){
	 var  pedido = ajax();
	 pedido.open("GET",servidor + datos,true);
	 pedido.onreadystatechange = function(){
		 if ( pedido.readyState == 4 ){     
		   location.href = "nuevo_combinacion.php";		      
		 }	   
	 }
	 pedido.send(null);
  }


  var eventoResultado = function(resultado, codigo){	
	   consultar(codigo,cargarCantidad); 
	   $$("dato").value =resultado;
	   $$("codidproducto").value = codigo; 	 
	   $$("overlay").style.visibility = "visible";
	   $$('gif').style.visibility = "visible"; 
	   $$("cant").value="";      		
  }
 
 function accion(){
	 $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
	 $$("dato").value = "";
	 $$("dato").focus();	 
 }
 
 var cargarCantidad = function(precio,unidadM,unidadA,conversion){
     $$("precioProducto").value = precio;
     $$("unidadmedida").value = unidadM;
	 $$("unidadalternativa").value = unidadA;
	 $$("conversion").value = conversion;
	 $$("cantA").value = "0.00";
	 $$('gif').style.visibility = "hidden";
	 $$("modal").style.visibility = "visible"; 
     document.form1.cant.focus();
 }
 
 
 var insertarFila = function(datos,tabla){
   var Data = new Array();	 
   var x = $$(tabla).insertRow($$(tabla).rows.length);
   x.bgColor= "#F6F6F6" ;
     for (var i = 0;i < datos.length;i++){
        var y = x.insertCell(i);
		Data = datos[i];
		y.align = Data[1];			
        y.innerHTML = Data[0];
		if (Data.length > 2)
		y.style.display = Data[2];
     }
}

 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
	setTotal(-parseFloat(desconvertirFormatoNumber(tr.cells[6].innerHTML)));
    var table = tr.parentNode;
    table.removeChild(tr);
 }
  
 function validaSubVentana(){
	 var cant;
	 if (obtenerSeleccionRadio('form1','pselectorU') == "UM"){
		cant = ($$("cant").value == "") ? 0 : parseFloat($$("cant").value);  
		if (cant <= 0){
		 openMensaje("Advertencia","Debe ingresar una cantidad mayor a 0");	
		 return false;
		}
	 }
	 else{
	    cant = ($$("cantA").value == "") ? 0 : parseFloat($$("cantA").value); 
		if (cant <= 0){
		 openMensaje("Advertencia","Debe ingresar una cantidad mayor a 0");
		 return false;		 
		}
	 }
	 return true;
 }
  
 function validarEntradaVentana(){
	 if (validaSubVentana())
	  agrega_celda();
 }
 
 //selecciona una opcion del combo
 var seleccionarCombo = function(combo,opcion){	 
	 var cb = document.getElementById(combo);
	 for (var i=0;i<cb.length;i++){
		if (cb[i].value==opcion){
		cb[i].selected = true;
		break;
		}
	 }	 
 }
 
 var agrega_celda =function(){	
     $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 	 
	 var cantidad;
	 var unidadM;
	 var precio;
	 if (obtenerSeleccionRadio('form1','pselectorU') == "UM"){
		cantidad = $$("cant").value;
		unidadM = $$("unidadmedida").value; 
		precio = parseFloat($$("precioProducto").value);
	 }
	 else{
		cantidad = $$("cantA").value;
		unidadM = $$("unidadalternativa").value; 
		precio = parseFloat($$("precioProducto").value) / parseFloat($$("conversion").value);
	 }
	 
	 var datos = new Array();
	 var total = parseFloat(cantidad) * precio;
     datos[0] = new Array("<img src='css/images/borrar.gif' title='Eliminar' alt='borrar' onclick='eliminarFila(this)' />","center");
	 datos[1] = new Array($$("codidproducto").value,"center");
	 datos[2] = new Array($$("dato").value,"left");
	 datos[3] = new Array(cantidad,"center");
	 datos[4] = new Array(unidadM,"center");
 	 datos[5] = new Array(convertirFormatoNumber(precio.toFixed(4)),"center");
	 datos[6] = new Array(convertirFormatoNumber(total.toFixed(4)),"center");	 
	 insertarFila(datos,"detalleSolicitud");	 
  	 setTotal(total);	
	 $$("dato").value=""; 
     document.form1.dato.focus();   
}
 
 
 function setTotal(total){
	 total = (isNaN(total)) ? 0 : total ;
	 total_bolivianos = total_bolivianos + total;
	 total_Dolares = total_bolivianos / ($$("tipoCambioBs").value);
	 $$("subtotalBS").value = convertirFormatoNumber(total_bolivianos.toFixed(4));
	 $$("subtotalDL").value = convertirFormatoNumber(total_Dolares.toFixed(4));		
 }
 
 var calcularTotalDolares = function(){
	 var total_D = $$("subtotalBS").value / ($$("tipoCambioBs").value);
	 $$("subtotalDL").value = convertirFormatoNumber(parseFloat(total_D).toFixed(4));	
 }
 
  var calcularTotalBs = function(){
	 var total_D = $$("subtotalDL").value * ($$("tipoCambioBs").value);
	 $$("subtotalBS").value = convertirFormatoNumber(parseFloat(total_D).toFixed(4));	
 }
 
  function salir(){
   if ($$('detalleSolicitud').rows.length > 0){
	if (confirm("Cuenta con detalles de Combinacion desea Salir ?"))
	 location.href = irDireccion;  
   }
   else{
	 location.href = irDireccion;  
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
 
 function soloNumeros(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
  return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
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

//obtiene la opcion seleccionada del radio
var obtenerSeleccionRadio = function(formulario,radio){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i< form.length;i++){
	if (form[i].checked==true)
  	  return form[i].value;   
	}	
}

var autocompletar = function(e,id){
	var consulta = "select idproducto,left(nombre,25)as 'nombre' from producto where estado=1 and "; 
	eventoTeclas(e,id,'resultados','producto','nombre','idproducto','eventoResultado','autocompletar/consultor.php',consulta,'','autoL1');
}


 var obtenerSeleccionCombo = function(nombreCombo){	
     var combo  = document.getElementById(nombreCombo);  
      return combo.options[combo.selectedIndex].text;	 
 }
