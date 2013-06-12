// JavaScript Document
var servidor = "cuentaporpagar/Dcuentapagar.php";
var datosFactura ={dia :"", nit: "" ,razonsocial:"",numfactura:"", numeroautorizacion:""
,importetotal:"",ice:"",excento:"",neto:"",iva:"",codigocontrol:"" };
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
	for (var i=0; i<cantidad; i++) {
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
	 
    if ($$("sucursal").value == ""){
	  openMensaje("Advertencia","Debe seleccionar una sucursal.");
	  return false;
	}
	if ($$("cuenta").value == ""){
	  openMensaje("Advertencia","Debe seleccionar una cuenta.");
	  return false;
	}
	if ($$("idpersonarecibida").value == ""){
	  openMensaje("Advertencia","Debe seleccionar el nombre del deudor.");
	  return false;
	}
	if ($$("tipocuenta").value == "cuentacaja" && $$("cuentasaliente").value == ""){
	  openMensaje("Advertencia","Debe seleccionar una cuenta caja.");
	  return false;
	}
	if ($$("importe").value == ""){
	  openMensaje("Advertencia","Debe ingresar el importe.");
	  return false;
	}
    if (!isvalidoNumero("importe")){
	  openMensaje("Advertencia","El importe ingresado es incorrecto.");
	  return false;
	}
	if (parseFloat($$("importe").value) <= 0){
	  openMensaje("Advertencia","El importe ingresado es incorrecto.");
	  return false;
	}
		
	return true; 
 }
 
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }

 var $$U = function(id){
  return encodeURIComponent($$(id).value);	
 }
 
 function registrarDatos(){
	var filtro;
	if (formularioValido()) {
	   var tipocuenta = obtenerSeleccionRadio("formdatos","tipocuenta");
	   if (tipocuenta == "cuentabanco") {	
	      $$('overlay').style.visibility = "visible";
       	  $$('gif').style.visibility = "visible";		  
		  var filtro = "transaccion=validar&cuenta="+$$("cuenta").value;
		  consultar(filtro,resultadoValidacion); 
	   } else {
		  enviarDatos(); 
	   }	   
	}
 }
 
 var resultadoValidacion = function(resultado){ 	 
	 if (resultado == "") {
		 $$('overlay').style.visibility = "hidden";
  	     $$('gif').style.visibility = "hidden";	
		 openMensaje("Advertencia","La cuenta seleccionada no tiene contra cuenta.");
	 } else {
		 enviarDatos();
	 }
 }
 
 
 var enviarDatos = function() {
	$$('overlay').style.visibility = "visible";
	$$('gif').style.visibility = "visible";	
	var tipocuenta = obtenerSeleccionRadio("formdatos","tipocuenta");
	factura = JSON.stringify(datosFactura);		
	filtro = "fecha="+$$("fecha").value+"&receptor="+$$("receptor").value+"&moneda="+$$("moneda").value+"&tipocambio="
	+$$("tipocambio").value+"&sucursal="+$$("sucursal").value+"&importe="+$$("importe").value+"&glosa="+$$U("glosa")
	+"&fechavencimiento="+$$("fechavencimiento").value
	+"&idpersonarecibida="+$$("idpersonarecibida").value+"&cuenta="+$$("cuenta").value+"&cuentacaja="
	+$$("cuentasaliente").value+"&idporpagar="+
	$$("idporpagar").value+"&transaccion="+transaccion+"&factura="+factura+"&tipocuenta="+tipocuenta
	+"&nombrepersona="+$$("texto").value+"&documento="+ $$("documento").value;	  
	consultar(filtro,resultadoTransaccion); 
 }
 
 
 
 
 var obtenerSeleccionRadio = function(formulario,radio){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i< form.length;i++){
	if (form[i].checked==true)
  	  return form[i].value;   
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
   window.open('cuentaporpagar/imprimir_cuenta_pagar.php?idcuenta='+idTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
   cerrarPagina();
 }

 var eventoResultadoEgreso = function(resultado,codigo){
  	   $$("texto").value= resultado;
	   $$("idpersonarecibida").value = codigo;
 }
 
 var cambiarTipoCuenta = function(tipo){
 if (tipo != ""){	
  if (tipo == "cuentacaja"){	
    document.formdatos.cuentasaliente.disabled = false;
  }else{
    document.formdatos.cuentasaliente.disabled = true;
	seleccionarCombo("cuentasaliente","");
  }
 }
}
 
 function cerrarPagina(){
	location.href = "nuevo_cuentaporpagar.php"; 
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
  
  
  var cambiarFoco = function(evt){
   var tecla = (document.all) ? evt.keyCode : evt.which;
   var n = $$("diaF").value.length;
    if (tecla == 8){
     return true;  
	}    
    if (n == 1){
     $$("nitF").focus();
     return true;
    }
    if (n >= 2){
      $$("nitF").focus();
      return false;
    }
    return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
}

function recuperaNombre(nit, pagina){
   peticion = ajax();   
   peticion.open('GET', pagina+'?nit='+nit+'&transaccion=nit', true); 
   peticion.onreadystatechange = function() { 	
      if (peticion.readyState == 4) { 
        if (peticion.responseText.length == '3') {$$('razonsocialF').focus(); return}
	      m = peticion.responseText.split('---');
	      $$('razonsocialF').value = m[0];
		  $$('numeroautorizacionF').value = m[1];
		  $$('numerofactura').focus();
	    } 
	  } 
  peticion.send(null);
}


function d(numero){
  resultado = Math.round(numero*100) / 100; 
  return resultado;
}

function calculaNeto(){
   $$('netoF').value = $$('importetotalF').value - $$('iceF').value - $$('excentoF').value; 
   $$('ivaF').focus();
}

function calculaIva(){
   $$('ivaF').value = d($$('importetotalF').value*0.13);
   $$('codigocontrolF').focus();
}
  
var openSubVentana = function(){	
    cargarFactura();
	$$("subventana").style.visibility = "visible";
	$$("overlays").style.visibility = "visible";
	$$('diaF').focus();
}

var cerrarSubVentana = function(){
   $$("subventana").style.visibility = "hidden";
   $$("overlays").style.visibility = "hidden";
}


 var datosCompletos = function(){
	 $$("numeroautorizacionF").value = ($$("numeroautorizacionF").value == "") ? "0" : $$("numeroautorizacionF").value;
	 $$("importetotalF").value = ($$("importetotalF").value == "") ? "0" : $$("importetotalF").value;
 	 $$("iceF").value = ($$("iceF").value == "") ? "0" : $$("iceF").value;
 	 $$("excentoF").value = ($$("excentoF").value == "") ? "0" : $$("excentoF").value;
	 $$("netoF").value = ($$("netoF").value == "") ? "0" : $$("netoF").value;
	 $$("ivaF").value = ($$("ivaF").value == "") ? "0" : $$("ivaF").value;
	 $$("codigocontrolF").value = ($$("codigocontrolF").value == "") ? "0" : $$("codigocontrolF").value;
	 
	if ($$('diaF').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Dia");
		return false;
	 }
	 if ($$('nitF').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Nit");
		return false;
	}
	if ($$('razonsocialF').value == ''){
		openMensaje("Advertencia","Debe ingresar el Nombre o Razon Social");
		return false;
	}	 
	if ($$('numerofactura').value == ''){
		openMensaje("Advertencia","Debe Ingresar el numero de factura");
		return false;
	}	
	if (parseFloat($$('diaF').value) > 31){
		openMensaje("Advertencia","El Dia, esta Fuera de rango");
		return false;
	}	
	return true; 
 }


var insertarNewFactura = function(){
   if (datosCompletos()){       
    datosFactura.dia = $$("diaF").value;
    datosFactura.nit = $$("nitF").value;
    datosFactura.razonsocial = $$("razonsocialF").value;
    datosFactura.numfactura = $$("numerofactura").value;
    datosFactura.numeroautorizacion = $$("numeroautorizacionF").value;
    datosFactura.importetotal = $$("importetotalF").value;
    datosFactura.ice = $$("iceF").value;
    datosFactura.excento = $$("excentoF").value;
    datosFactura.neto = $$("netoF").value;
    datosFactura.iva = $$("ivaF").value;
    datosFactura.codigocontrol = $$("codigocontrolF").value;  	
	cerrarSubVentana();
  }
}

var cargarFactura = function(){
	if (datosFactura.dia == "") {
	   var fech = $$("fecha").value.split("/");	
	   datosFactura.dia = fech[0];
	}
	
    $$("diaF").value = datosFactura.dia;
    $$("nitF").value = datosFactura.nit;
    $$("razonsocialF").value = datosFactura.razonsocial;
    $$("numerofactura").value = datosFactura.numfactura;
    $$("numeroautorizacionF").value = datosFactura.numeroautorizacion;
    $$("importetotalF").value = datosFactura.importetotal;
    $$("iceF").value = datosFactura.ice;
    $$("excentoF").value = datosFactura.excento;
    $$("netoF").value = datosFactura.neto;
    $$("ivaF").value = datosFactura.iva;
    $$("codigocontrolF").value = datosFactura.codigocontrol;  
}

function soloNumeros(evt){
var tecla = (document.all) ? evt.keyCode : evt.which;
return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
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
 
 var seleccionarRadio = function(formulario,radio,opcion){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i<form.length;i++){
		if (form[i].value==opcion){		
		form[i].checked = true;
		break;
		}
	}
}
