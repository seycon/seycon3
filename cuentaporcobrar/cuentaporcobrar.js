// JavaScript Document
 var servidor = "cuentaporcobrar/Dcuentacobrar.php";
 var transaccion = "insertar";
 var idTransaccion = 0;

 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
  switch(tecla){
	case 113://F2
	if($$('overlay').style.visibility != "visible")
	  $$("enviar").click();
	break;
	case 115://F4
	  if ($$("cancelar") != null)
       $$("cancelar").click();
	break; 
   }   
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

 var cambiarTipoCuenta = function(tipo){
	
	if (tipo == "cuentaCaja"){
	  document.formdatos.cuentacaja.disabled = false;
	  document.formdatos.cuentaapertura.disabled = true;	
	}else{
	  document.formdatos.cuentacaja.disabled = true;
	  document.formdatos.cuentaapertura.disabled = false;		
	}
 }

 var getConsulta = function(){
	 filtro = "fecha="+$$("fecha").value+"&transaccion=reporte";
	 consultar(filtro,setResultadoConsulta);
 }
 
  var mostrarAuxiliares = function(){	
    var tipo = $$("auxiliares").checked;
	if (tipo == true){
	 tipo = "none";
	}else{
	 tipo = "table-row"; 
	}
	 
	cantidad = $$("detalleTransaccion").rows.length;
	for (var i=0;i<cantidad;i++){
	  if ($$("detalleTransaccion").rows[i].cells[0].innerHTML == "6"){
		  $$("detalleTransaccion").rows[i].style.display = tipo;
		  $$("detalleGrafico").rows[i].style.display = tipo;
	  }
	}	 
 }
 
 var setResultadoConsulta = function(resultado){
	 var datos = resultado.split("---");
	 $$("detalleTransaccion").innerHTML = datos[0];
	 $$("detalleGrafico").innerHTML = datos[1];
 }

 var $$ = function(id){
  return document.getElementById(id);	 
 }
 
 function limpiarDeudor(){
	$$("texto").value = ""; 
	$$("idpersonarecibida").value = ""; 
 }
 
 function formularioValido(){
	 var tipocuenta = obtenerSeleccionRadio("formdatos","tipocuenta");
	if ($$("sucursal").value == ""){
	  openMensaje("Advertencia","Debe seleccionar una Sucursal");
	  return false;
	}
	if ($$("cuenta").value == ""){
	 openMensaje("Advertencia","Debe seleccionar una Cuenta");
	  return false;
	}
	if ($$("idpersonarecibida").value == ""){
	  openMensaje("Advertencia","Debe seleccionar el nombre del Deudor");
	  return false;
	}
	if ($$("cuentacaja").value == "" && tipocuenta == "cuentaCaja"){
	  openMensaje("Advertencia","Debe seleccionar la cuenta Caja/Banco");
	  return false;
	}
	if ($$("cuentaapertura").value == "" && tipocuenta == "cuentaApertura"){
	  openMensaje("Advertencia","Debe seleccionar la cuenta contable.");
	  return false;
	}
	if ($$("importe").value == ""){
	  openMensaje("Advertencia","Debe ingresar el Importe");
	  return false;
	}
	if (!isvalidoNumero("importe")){
	  openMensaje("Advertencia","El importe ingresado es incorrecto");
	  return false;
	}
	if (parseFloat($$("importe").value) <= 0){
	  openMensaje("Advertencia","El importe ingresado es incorrecto");
	  return false;
	}
	
	return true; 
 }
 
  var obtenerSeleccionRadio = function(formulario,radio){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i< form.length;i++){
	if (form[i].checked==true)
  	  return form[i].value;   
	}	
}
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }
 
 var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}
 
 function registrarDatos(){
	var filtro;
	if (formularioValido()){
	$$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";
	var tipocuenta = obtenerSeleccionRadio("formdatos","tipocuenta");
	if (tipocuenta == "cuentaCaja"){
	 cuentaOrigen = $$("cuentacaja").value;
	}else{
	 cuentaOrigen = $$("cuentaapertura").value; 
	}
	filtro = "fecha="+$$("fecha").value+"&receptor="+$$("receptor").value+"&moneda="+$$("moneda").value
	+"&tipocambio="+$$("tipocambio").value
	+"&sucursal="+$$("sucursal").value+"&importe="+$$("importe").value+"&glosa="+$$U("glosa")
	+"&fechavencimiento="+$$("fechavencimiento").value+"&documento="+$$("documento").value
	+"&idpersonarecibida="+$$("idpersonarecibida").value+"&cuenta="+$$("cuenta").value+"&cuentacaja="+cuentaOrigen+"&idporcobrar="+
	$$("idporcobrar").value+"&transaccion="+transaccion+"&nombrepersona="+$$("texto").value+"&tipocuenta="+tipocuenta; 
	consultar(filtro,resultadoTransaccion);
	}
 }

 var seleccionarRadio = function(formulario,radio,opcion){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i<form.length;i++){
		if (form[i].value==opcion){		
		form[i].checked = true;
		break;
		}
	}
 }

 function resultadoTransaccion(resultado){
     datos = resultado.split("---"); 
	  if (datos[1] == "1"){
	    idTransaccion = datos[0];	
	    $$("overlay").style.visibility = "visible";
	    $$("modal_vendido").style.visibility = "visible"; 
		$$('gif').style.visibility = "hidden";
	  }else{
		cerrarPagina(); 
	  }
 }

 function accionPostRegistro(){
   window.open('cuentaporcobrar/imprimir_cuenta_cobrar.php?idcuenta='+idTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
   cerrarPagina();
 }

 var eventoResultadoEgreso = function(resultado,codigo){
  	   $$("texto").value= resultado;
	   $$("idpersonarecibida").value = codigo;
 }
 
 var resultadoCuenta = function(resultado){
	$$("cuenta").innerHTML = resultado; 
 }

 var tipoBusqueda = function(e){
	var sql;
	   tipocliente = $$('receptor').value;
	   if (tipocliente!="otros"){ 
	    idconsulta = "id"+tipocliente;   
		switch(tipocliente){
		  case 'trabajador':
		   sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t where estado=1 and  ";
		 break;	
		 case 'cliente':
		   sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
		 break;
		 case 'proveedor':
		   sql = "select idproveedor,left(nombre,20) as 'nombre' from proveedor where estado=1 and ";
		 break;
		}		
	 eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultadoEgreso','autocompletar/consultor.php',sql,'','autoL1');
	 }
  }
  
  function cerrarPagina(){
	location.href = "nuevo_cuentaporcobrar.php";  
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

  