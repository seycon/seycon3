// JavaScript Document

 var $$ = function(id){
   return document.getElementById(id);
 } 

 function ajax() {
    return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
 }
 
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2
	  if ($$('tabs-1').style.display == "block") {
		 asignarCliente();
	  }
	  if ($$('tabs-2').style.display == "block") {
		 registrarAsistencia();
	  }
	  if ($$('tabs-3').style.display == "block") {
		 registrarAntecedente();
	  }
	  if ($$('tabs-4').style.display == "block") {
		 registrarFalta();
	  }
	break;
	case 27://Esc
	  if ($$('ventana_credencial').style.display == "block") { 
	      closeCredencial();
	  }
	  if ($$("modal_horario").style.display == "block") {
		  closeHorario();
	  }
	break;
   }
 }
 
 function enviar(servidor, filtro, funcion){ 
  var  pedido = ajax();	
  pedido.open("GET",servidor+"?"+filtro,true);
  pedido.onreadystatechange = function() {
	   if (pedido.readyState == 4){
     	  respuesta = pedido.responseText;
		  if (funcion != null) {		  
		     funcion(respuesta);
		  }
	   }	   
   }
   pedido.send(null);   	
 } 
 
 
 var validoBusqueda = function(tipo)
 {
	if (tipo == "trabajador" && $$("idtipotrabajador").value == "") {
		$$("overlay2").style.display = "block";
		openMensaje("Advertencia","Debe seleccionar un trabajador.","no","300px","210px");
	    return false; 
	}
	return true;
 }
 
 var restaurarCampos = function(tipo)
 {
	switch(tipo) {
	    case "cliente":
		    $$("tipocliente").focus();
		break;
		case "trabajador":
		    $$("tipotrabajador").focus();
		break;
		case "parametro":
		    $$("parametro").focus();
		break;	
	}
	 
	$$("idtipotrabajador").value = ""; 
	$$("idtipocliente").value = ""; 
    $$("tipocliente").value = ""; 
	$$("tipotrabajador").value = ""; 
 }
 
 var setOption = function(tipo){
	 if (tipo == "monitoreo") {
		$$("opcion_monitoreo").style.display = "block"; 
		$$("opcion_asistencia").style.display = "none";
		$$("panel").style.display = "block"; 
		$$("panel_asistencia").style.display = "none";
		$$("abuscar").disabled = "";		
	 }
	 if (tipo == "asistencia") {
		$$("opcion_monitoreo").style.display = "none"; 
		$$("opcion_asistencia").style.display = "block"; 
		$$("panel").style.display = "none"; 
		$$("panel_asistencia").style.display = "block";	
		$$("abuscar").disabled = "disabled";
		$$("strabajador").innerHTML = "";			 
	 }
 }
 
 
  var validoHorario = function()
 {
	if ($$("idtrabajadorhorario").value == "") {
		$$("overlay2").style.display = "block";
		openMensaje("Advertencia","Debe seleccionar un trabajador.","no","300px","210px");
	    return false; 
	}
	return true;
 }
 
  var getHorario = function(){	
	  if (validoHorario()) {
		 $$("overlay").style.display = "block"; 
	     $$("gif").style.display = "block";  
		 var filtro = "transaccion=listaHorario&idtrabajador="+ $$("idtrabajadorhorario").value 
		 + "&mes=" + $$("mes").value + "&anio=" + $$("anio").value;
		 enviar("monitor/Dmonitor.php",filtro,setHorario); 
	  }
 }
 
  var setHorario = function(resultado){
 	  $$("overlay").style.display = "none"; 
	  $$("gif").style.display = "none";  
	  if (resultado != "") {
		$$("cuerpo_asistencia").innerHTML = resultado;  
	  } else {
		$$("overlay2").style.display = "block"; 
		openMensaje("Advertencia","Sin resultados de busqueda.","no","300px","210px");  
	  }	  
  }
  
  var openHorario = function(t)
  {
	  var td = t.parentNode;
      var tr = td.parentNode;
	  var entrada = tr.cells[5].innerHTML.split(":");
	  var salida = tr.cells[6].innerHTML.split(":");
	  cargaHorario(entrada, "i");
      cargaHorario(salida, "s");
      $$("overlay2").style.display = "block";
	  $$("modal_horario").style.display = "block";  	  
  }
  
  var cargaHorario = function(horario,tipo)
  {
	seleccionarCombo("hora_"+tipo, horario[0]);  
	seleccionarCombo("minuto_"+tipo, horario[1]); 
	seleccionarCombo("segundo_"+tipo, horario[2]); 
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
  
  var closeHorario = function()
  {
	  $$("overlay2").style.display = "none";
	  $$("modal_horario").style.display = "none"; 
  }
 
 var getBusqueda = function(cadena){	
      var tipo = obtenerSeleccionRadio("formBusqueda","busqueda");
	  if (validoBusqueda(tipo)) {
		 $$("overlay").style.display = "block"; 
	     $$("gif").style.display = "block";  
		 var filtro = "transaccion="+ tipo +"&idcliente="+ $$("idtipocliente").value
		 + "&idtrabajador="+ $$("idtipotrabajador").value + "&parametros="+ $$("parametro").value ;
		 enviar("monitor/Dmonitor.php",filtro,setBusqueda); 
	  }
 }
 
 var setBusqueda = function(resultado){
	 var parametros = JSON.parse(resultado);
	 if (parametros.length > 0) {
	    cargarDatos(parametros); 
	 } else {
		$$("panel").innerHTML = ""; 
		$$("overlay").style.display = "none"; 
	    $$("gif").style.display = "none";  
		$$("overlay2").style.display = "block"; 
		openMensaje("Advertencia","Sin resultados de busqueda.","no","300px","210px");
	 }	 
 }  
 
 var consultarPersonal = function(cadena){	
   if(cadena != "") { 
       var filtro = "transaccion=busqueda&texto="+cadena+"&tipo=" + $$("tipo").value;
	   enviar("monitor/Dmonitor.php",filtro,resultadoPersonal); 
   }
 }
 
 var resultadoPersonal = function(resultado){
	 $$("strabajador").innerHTML = resultado;
 } 
 
 var validoAsignacion = function()
 {
	if ($$("idcliente").value == "") {
		openMensaje("Advertencia","Debe seleccionar un cliente.","si","715px","180px");
	    return false; 
	}
	if ($$("motivo").value == "") {
		openMensaje("Advertencia","El campo motivo es requerido.","si","715px","180px");
	    return false; 
	}
	return true;
 }
 
 
 var asignarCliente = function(){	
   if(validoAsignacion()) { 
      closeCredencial();
      $$("overlay").style.display = "block"; 
	  $$("gif").style.display = "block";  
       var filtro = "transaccion=asignarcliente&idtrabajador="+ $$("idtrabajador").value 
	   + "&idcliente=" + $$("idcliente").value;
	   enviar("monitor/Dmonitor.php",filtro,resultadoAsignacion); 
   }
 }
 
 var resultadoAsignacion = function(resultado){
	 if (resultado == "invalido") {
		 $$("overlay2").style.display = "block"; 
		 openMensaje("Advertencia","El cliente seleccionado ya está asignado actualmente.","no","300px","210px");
	 } else {
 		 var filtro = "transaccion=trabajador&idtrabajador="+ resultado;
		 enviar("monitor/Dmonitor.php", filtro, setBusqueda); 
	 }
 } 
 
 var obtenerSeleccionRadio = function(formulario,radio){
	var form = eval("document."+formulario+"."+radio);	
	for (var i=0;i< form.length;i++){
	if (form[i].checked==true)
  	  return form[i].value;   
	}	
 }
 
 var registrarAsistencia = function(){	
      var tipo = obtenerSeleccionRadio("formdatos","asistencia");
       var filtro = "transaccion=asistencia&idtrabajador="+ $$("idtrabajador").value 
	   + "&tipo=" + tipo;
	   enviar("monitor/Dmonitor.php",filtro,resultadoAsistencia); 
 }
 
 
 
 var resultadoAsistencia = function(resultado){
	 if (resultado == "invalido" || resultado == "cliente") {
	     if (resultado == "invalido") {
		   openMensaje("Advertencia","El tipo de asistencia ya fue registrado anteriormente.","si","715px","180px");
		 }
		 if (resultado == "cliente") {
		   openMensaje("Advertencia","El trabajador debe estar asignado a un cliente para "
		   + "registrar la asistencia.","si","715px","180px");
		 }
	 } else {
	    closeCredencial();
	 }
 } 
 
 var validoAntecendente = function()
 {
	if ($$("detalle_ant").value == "") {
		openMensaje("Advertencia","Ingrese el detalle del antecedente.","si","715px","180px");
	    return false; 
	}
	return true;
 }
 
 var registrarAntecedente = function()
 {
	 if(validoAntecendente()) { 
		 var filtro = "transaccion=antecedente&idtrabajador="+ $$("idtrabajador").value 
		   + "&titulo=" + $$("titulo_ant").value + "&descripcion=" + $$("detalle_ant").value;
		 enviar("monitor/Dmonitor.php",filtro,resultadoAntecedente);   
	 }
 }
 
  var resultadoAntecedente = function(resultado){
	    closeCredencial();
 }
 
  var validoFalta = function()
 {
	if ($$("detalle_falta").value == "") {
		openMensaje("Advertencia","Ingrese el detalle de la falta.","si","715px","180px");
	    return false; 
	}
	return true;
 }
 
 var registrarFalta = function()
 {
	 if(validoFalta()) { 
		 var filtro = "transaccion=falta&idtrabajador="+ $$("idtrabajador").value 
		   + "&titulo=" + $$("titulo_falta").value + "&descripcion=" + $$("detalle_falta").value;
		 enviar("monitor/Dmonitor.php",filtro,resultadoFalta);   
	 }
 }
 
  var resultadoFalta = function(resultado){
	    closeCredencial();
		location.href = "nuevo_monitor.php";
 }
 
 var getDatosPersonal = function(codigo)
 {
	 $$("overlay").style.display = "block"; 
	 $$("gif").style.display = "block"; 
	 var filtro = "transaccion=datospersonales&idtrabajador=" + codigo;
	 enviar("monitor/Dmonitor.php",filtro,setDatosPersonal); 
 }
 
 var limpiarCampos = function()
 {
	 $$("motivo").value = ""; 
	 $$("detalle_ant").value = "";
	 $$("detalle_falta").value = "";
	 $$("tabs1").click();
 }
 
 var setDatosPersonal = function(resultado)
 {
	limpiarCampos(); 
	$$("gif").style.display = "none";  
	var datos = Array();
	datos = JSON.parse(resultado);
	$$("idtrabajador").value = datos[0];
	$$("cp_nrotrabajador").innerHTML = "Nº. " + datos[0];
	$$("cp_nombre").innerHTML = "&nbsp;" + datos[1]; 
 	$$("cp_direccion").innerHTML = "&nbsp;" + datos[2]; 
	$$("cp_telefono").innerHTML = "&nbsp;" + datos[3]; 
	$$("cp_celular").innerHTML = "&nbsp;" + datos[4]; 
	$$("cp_conyugue").innerHTML = "&nbsp;" + datos[5];
	if (datos[7] == "") {
		$$("cliente").value = "-- Sin Asignar --"; 
		$$("idcliente").value = ""; 
	} else {
		$$("cliente").value = datos[6]; 
		$$("idcliente").value = datos[7]; 		
	}
	$$("imagen_credencial").src = datos[8];
	$$("listaHistorial").innerHTML = datos[9];
	openCredencial();
 }
 
  var viewMenu = function(id){
	var menu = ['tabs-1','tabs-2','tabs-3', 'tabs-4', 'tabs-5'];
	var menu2 = ['tabs1','tabs2','tabs3','tabs4','tabs5'];
		for (var j=0;j<menu.length;j++){
	  if (menu[j] == id){
		$$(menu[j]).style.display = "block"; 
		$$(menu2[j]).style.background = "#000"; 
	  }else{
		$$(menu[j]).style.display = "none";
		$$(menu2[j]).style.background = ""; 
	  }
	}	 
 }
 
 var closeCredencial = function(){
     $$("ventana_credencial").style.display = "none";
	 $$("overlay").style.display = "none"; 
	 $$("modal_mensajes").style.display = "none";
 }
 
 var openCredencial = function(){
     $$("ventana_credencial").style.display = "block";
	 $$("overlay").style.display = "block";
	 $$("modal_mensajes").style.display = "none"; 
	 $$("overlay2").style.display = "none";
 }
 
 var tipoClienteBusqueda = function(e){
	  var  sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
	  eventoTeclas(e,"tipocliente",'tipoclienteResult','cliente','nombre','idcliente','eventoTipoCliente'
	  ,'autocompletar/consultor.php',sql,'','autoL4');	  
 }

 var eventoTipoCliente = function(resultado, codigo){
	 $$("tipocliente").value= resultado;
	 $$("idtipocliente").value = codigo;	
 }
 
  var tipoTrabajadorBusqueda = function(e){
	  var sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) "
	  + " as 'nombre' from trabajador t where t.estado=1 and t.control='Monitorizado' and  ";
	  eventoTeclas(e,"tipotrabajador",'tipotrabajadorResult','trabajador','nombre','idtrabajador'
	  ,'eventoTipoTrabajador','autocompletar/consultor.php',sql,'','autoL3');	  
 }

 var eventoTipoTrabajador = function(resultado, codigo){
	 $$("tipotrabajador").value= resultado;
	 $$("idtipotrabajador").value = codigo;	
 }
 
 var trabajadorHorarioBusqueda = function(e){
	  var sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) "
	  + " as 'nombre' from trabajador t where t.estado=1 and t.control='Monitorizado' and  ";
	  eventoTeclas(e,"trabajadorhorario",'trabajadorhorarioResult','trabajador','nombre','idtrabajador'
	  ,'eventoTrabajadorHorario','autocompletar/consultor.php',sql,'','autoL6');	  
 }

 var eventoTrabajadorHorario = function(resultado, codigo){
	 $$("trabajadorhorario").value= resultado;
	 $$("idtrabajadorhorario").value = codigo;	
 } 
 
 var tipoBusqueda = function(e){
	  var  sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
	  eventoTeclas(e,"cliente",'clienteResult','cliente','nombre','idcliente','eventoResultadoCliente'
	  ,'autocompletar/consultor.php',sql,'','autoL2');	  
 }

 var eventoResultadoCliente = function(resultado, codigo){
	 $$("cliente").value= resultado;
	 $$("idcliente").value = codigo;	
 }
 
 var openMensaje = function(titulo,contenido,union, left, top){
    $$("u1_msg").style.display = "none";
	$$("u2_msg").style.display = "none";
	if (union == "si") {
		$$("u1_msg").style.display = "block";
		$$("u2_msg").style.display = "block";
	}
	$$("modal_tituloCabecera").innerHTML = titulo;
	$$("modal_contenido").innerHTML = contenido;
	$$("modal_mensajes").style.left = left;
	$$("modal_mensajes").style.top = top;
	$$("modal_mensajes").style.display = "block";	
 }
  
 var closeMensaje = function(){
	$$("modal_mensajes").style.display = "none";
	$$("overlay2").style.display = "none";
 }
 