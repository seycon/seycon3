// JavaScript Document

  var servidor = 'trabajador/DTrabajador.php';
  var irDireccion = "listar_trabajador.php";
  var transaccion = "insertar";
  var idTransaccion;


	function active_v(checks,opcion,contenido,valor){
		if (checks.checked){
		   document.getElementById(opcion).style.display = '';
		   document.getElementById(contenido).style.display = '';
		   checks.value = valor;
		}
		else{
		   document.getElementById(opcion).style.display = 'none';
		   document.getElementById(contenido).style.display = 'none';
		   checks.value = '';
		   document.getElementById("t").click();
		}
		  
	}
	
	$(document).ready(function()
	{
	document.getElementById('cortinaInicio').style.visibility = "hidden";
    document.getElementById('gif').style.visibility = "hidden";
		
	$("input[type=file]").filestyle({ 
     image: "images/file.png",
     imageheight : 21,
     imagewidth : 80,
     width : 130
   });	
	
	$("#fechanacimiento").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechaingreso").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechafinalizacion").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechaconyugue").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechamoto").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechaauto").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechafamiliar").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechahijo").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechaseguro").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	$("#fechaafp").datepicker({
	showOn: "button",
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'dd/mm/yy'
	});
	});


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
 
  var salir = function() {
	location.href = irDireccion;
  }	 

  var bloquear = function(dato){
	 if (dato == "Efectivo") {
		$$("numerocuenta").value = "";
		$$("nombrebanco").value = "";
		$$("numerocuenta").disabled = "disabled"; 
		$$("nombrebanco").disabled = "disabled"; 
	 } else {
    	$$("numerocuenta").value = "";
		$$("nombrebanco").value = "";
		$$("numerocuenta").disabled = ""; 
		$$("nombrebanco").disabled = "";  
	 }
  }
  
  var setModalidad = function(dato) {
	  var data = "";
  	 /* setCompartido(false);  
  	  $$("LabelSueldo").innerHTML = "Sueldo Básico:";  		 
	  var option = ['bonoproduccion',"transporte","puntualidad","asistencia","seguromedico"];
	  for (var i=0;i<option.length;i++){
		 $$(option[i]).disabled = data; 
		 $$(option[i]).value = ""; 
	  }*/
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
 
  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
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
	 if ($$('moto').checked && $$("fechamoto").value == "") {
		openMensaje("Advertencia","El campo fecha de vencimiento de la licencia es requerido.");   
	    return false; 
	 }	
	 if ($$('moto').checked && $$("fechamoto").value != "" && !validarFecha($$("fechamoto").value)) {
		openMensaje("Advertencia","Ingrese una fecha de vencimiento valida.");   
	    return false; 
	 }
	 if ($$('auto').checked && $$("fechaauto").value == ""){
		openMensaje("Advertencia","El campo fecha de vencimiento de la licencia es requerido.");   
	    return false; 
	 } 
	 if ($$('auto').checked && $$("fechaauto").value != "" && !validarFecha($$("fechaauto").value)) {
		openMensaje("Advertencia","Ingrese una fecha de vencimiento valida.");   
	    return false; 
	 }
	 if ($$('cajero_check').checked) {
	  if (!validarCajero())
	   	 return false;
	 }
	 if ($$('vende_active_check').checked && !isvalidoNumero("comisionventas")) {
		openMensaje("Advertencia","El campo comisión de ventas es invalido.");   
	    return false;  
	 }
	 if ($$('vende_active_check').checked && !isvalidoNumero("comisioncobros")) {
		openMensaje("Advertencia","El campo comisión de cobros es invalido.");   
	    return false;  
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
  
  
   var validarNumber = function() {
	var ids = ['ingresogarante','ingresoconyugue','sueldobasico','bonoproduccion','transporte'
	,'puntualidad','asistencia','numerocuenta','seguromedico']; 
	var msg = ['ingreso del garante','ingreso del conyugue','sueldo basico','bono producción','bono transporte'
	,'bono puntualidad','bono asistencia','Nº de cuenta bancaria','Nº de seguro medico'];
	for (var j = 0; j < ids.length; j++){
	   if (!isvalidoNumero(ids[j])) {
	      openMensaje("Advertencia",'El campo '+msg[j] + " es invalido.");   
	      return false;
	   }	 
	}
	return true;
  } 
  
  
   var validarCajas = function() {
	var ids = ['nombre','apellido','carnetidentidad','fechanacimiento','direccion'
	,'ciudad','nombregarante','apellidogarante','direcciongarante','ingresogarante','idcargo','idsucursal'
	,'departamento']; 
	var msg = ['nombre','apellido','C.I.','fecha de nacimiento','domicilio','ciudad de origen'
	,'nombre del garante','apellido del garante','dirección del garante','ingreso mensual del garante'
	,'cargo','sucursal','departamento'];
	for (var j = 0; j < ids.length; j++){
	  if ($$(ids[j]).value == "")	{
	      openMensaje("Advertencia",'El campo '+msg[j] + " es requerido.");   
	      return false;
	  }	 
	}
	return true;
  } 
  
  var validarFechas = function() {
	var ids = ['fechanacimiento','fechaingreso','fechafinalizacion','fechaseguro','fechaafp']; 
	var msg = ['fecha de nacimiento','fecha de ingreso','fecha de finalización'
	, 'fecha de seguro', 'fecha de afp'];
	for (var j = 0; j < ids.length; j++){
	  if ($$(ids[j]).value != "" && !validarFecha($$(ids[j]).value))	{
	      openMensaje("Advertencia",'Ingrese una '+msg[j] + " valida.");   
	      return false;
	  }	 
	}
	return true;  
  }

  var validarCajero = function() {
	var option = ['textocaja1','textocaja2','textobanco1','textobanco2','textobanco3']; 
	var selector = ['cuentacaja1','cuentacaja2','cuentabanco1','cuentabanco2','cuentabanco3']; 
	var msg = ['Caja 1','Caja 2','Banco 1','Banco 2','Banco 3'];
	for (var j=0; j<option.length; j++) {
	  if ($$(option[j]).value == "" && $$(selector[j]).value != "")	{
	      openMensaje("Advertencia",'Debe Ingresar el Titulo '+msg[j]);   
	      return false;
	  }
	  if ($$(option[j]).value != "" && $$(selector[j]).value == "")	{
	      openMensaje("Advertencia",'Debe Seleccionar la Cuenta '+msg[j]);   
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
	       var id = "detalleFamilia";
	       var nfilas = $$(id).rows.length;    	
		   json = new Array();
		   for(i = 0; i < nfilas; i++) {
			 vector = [$$(id).rows[i].cells[1].innerHTML, //parentesco		
			           $$(id).rows[i].cells[2].innerHTML, //nombre 
			           $$(id).rows[i].cells[3].innerHTML, //fecha nacimiento								
			           $$(id).rows[i].cells[4].innerHTML, //direccion	
			           $$(id).rows[i].cells[5].innerHTML];//telefono 	
			 json[i] = vector;	 		
		   }
		   dato = JSON.stringify(json); 
	       $$("datosFamilia").value = dato;
		   var id = "detallehijos";
	       var nfilas = $$(id).rows.length;    	
		   json = new Array();
		   for(i = 0; i < nfilas; i++) {
			 vector = [$$(id).rows[i].cells[1].innerHTML, //tipodependencia		
			           $$(id).rows[i].cells[2].innerHTML, //nombre 
			           $$(id).rows[i].cells[3].innerHTML, //genero								
			           $$(id).rows[i].cells[4].innerHTML];//fecha nacimiento 	
			 json[i] = vector;	 		
		   }
		   dato = JSON.stringify(json); 
	       $$("datosHijos").value = dato;
		   var id = "detalleCompartir";
		   var json = new Array();
		   var n = $$(id).rows.length;	 
		   var j = 0;
			for (var i = 1; i <= n; i++) {
				 if ($$("ts"+i).checked) {		
					 json[j] = getData(id,(i-1),3);	
					 j++;
				 }
			}		
		   $$("sucursalAsignada").value = json;
		   json = new Array();
		   n = $$("detalleRutas").rows.length;	 
		   var j = 0;
		   for (var i = 1; i <= n; i++) {
			   if ($$("rt" + i).checked) {		
				   json[j] = getData("detalleRutas",(i-1),3);	
				   j++;
			   }
		   }		
		   $$("rutasAsignada").value = json;
	  	
         $$("formValidado").submit();
	  }
  }
  
  function setCompartido(estado){
	var n = $$("detalleCompartir").rows.length; 
	var objeto;
	var estado = (estado == true)? 'check' : '';
	 for (var i=1;i<=n;i++){
		$$('ts'+i).checked = estado; 
	 }	
   }
   
   function setRuta(estado){
	var n = $$("detalleRutas").rows.length; 
	var objeto;
	var estado = (estado == true)? 'check' : '';
	 for (var i=1; i<=n; i++) {
		$$('rt'+i).checked = estado; 
	 }	
   }
  
   function ventanaCompartido(idventana, estado) {
	 if (estado == "abrir") { 
	   $$(idventana).style.visibility = "visible"; 
	   $$("overlay_vendido").style.visibility = "visible";   
	 } else {
	   $$(idventana).style.visibility = "hidden"; 
	   $$("overlay_vendido").style.visibility = "hidden"; 
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
  
  var setEdad = function(value)  {
	 var edad = getfecha(value); 
	 $$("edadhijo").value = edad;
  }
  
  var bloquearVivencia = function(dato) {
	  if (dato != "Otros"){
		  $$("descripcionvivienda").style.display = "none"; 
		  $$("descripcionvivienda").value = "";
	  } else {
     	  $$("descripcionvivienda").style.display = "block";
	  }	  
  }
  
  var bloquearMoto = function(estado, id) {
	  setValorCheck(id);
	  if (estado == false) {
	      $$("fechamoto").disabled = "disabled";  
	  } else {
		  $$("fechamoto").disabled = "";  
	  }
  }
  
  var bloquearAuto = function(estado, id) {
	  setValorCheck(id);
	  if (estado == false) {
		  $$("fechaauto").disabled = "disabled";  
		  $$("categoriaauto").disabled = "disabled";  
		  $$("categoriaauto").value = "";
	  } else {
		  $$("fechaauto").disabled = ""; 
		  $$("categoriaauto").disabled = "";  
	  }
  }
  
  var bloquearMedicamento = function(valor) {
      if (valor == 1) {
		 $$("msgmedicamento").style.display = "block"; 		 
	  } else {
		 $$("msgmedicamento").style.display = "none"; 
		 $$("cualmedicamento").value = "";
	  }
  }  
  
  var bloquearFarmaco = function(valor) {
      if (valor == 1) {
		 $$("msgdroga").style.display = "block"; 
	  } else {
		 $$("msgdroga").style.display = "none"; 
 		 $$("cualdroga").value = ""; 
	  }
  }
  
  var bloquearCampo = function(valor, id, texto) {
	  if (valor == 1) {
		 $$(id).style.display = "block";  
	  } else {
		 $$(id).style.display = "none"; 
		 $$(texto).value = ""; 
	  }
  }
  
  var bloquearEstadoCivil = function(valor) {
	  if (valor != "Casado") {
	      $$("nombreconyugue").disabled = "disabled"; 
		  $$("nacimientoconyugue").disabled = "disabled";
		  $$("empresatrabajo").disabled = "disabled";
		  $$("celularconyugue").disabled = "disabled"; 
		  $$("fechaconyugue").disabled = "disabled"; 
		  $$("direccionconyugue").disabled = "disabled"; 
		  $$("situacioncivil").disabled = "disabled"; 
		  $$("nombreconyugue").value = "";
		  $$("empresatrabajo").value = "";
		  $$("celularconyugue").value = "";
		  $$("direccionconyugue").value = "";
		  $$("nacimientoconyugue").value = "";
	  } else {
		  $$("nombreconyugue").disabled = ""; 
		  $$("nacimientoconyugue").disabled = "";
		  $$("empresatrabajo").disabled = "";
		  $$("celularconyugue").disabled = ""; 
		  $$("fechaconyugue").disabled = ""; 
		  $$("direccionconyugue").disabled = ""; 
		  $$("situacioncivil").disabled = "";  
	  }
  }
  
  var bloquearHijos = function(valor) {
	  if (valor == "0") {
	      $$("tipohijo").disabled = "disabled"; 
		  $$("nombrehijo").disabled = "disabled";
		  $$("generohijo").disabled = "disabled";
		  $$("fechahijo").disabled = "disabled"; 
		  $$("agregarhijo").disabled = "disabled"; 
		  $$("detallehijos").innerHTML = "";
		  $$("nombrehijo").value = "";
		  $$("edadhijo").value = "0";
		  var f = new Date();
  	      $$("fechahijo").value = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
	  } else {
	      $$("tipohijo").disabled = ""; 
		  $$("nombrehijo").disabled = "";
		  $$("generohijo").disabled = "";
		  $$("fechahijo").disabled = ""; 
		  $$("agregarhijo").disabled = "";  
	  }
  }
  
  var bloquearContrato = function(valor) {
	  switch(valor) {
	      case "Administracion":
		      $$("am1").disabled = ""; 
			  $$("am1_minutos").disabled = "";
			  $$("am2").disabled = "";
			  $$("am2_minutos").disabled = "";
			  $$("pm1").disabled = "";
			  $$("pm1_minutos").disabled = "";
			  $$("pm2").disabled = ""; 
			  $$("pm2_minutos").disabled = ""; 
			  $$("ingreso1").disabled = "disabled"; 
			  $$("sale1").disabled = "disabled"; 
			  $$("dias1").disabled = "disabled"; 
			  $$("dias2").disabled = "disabled"; 
		  break;
		  case "Ciudad":
		      $$("am1").disabled = "disabled"; 
			  $$("am2").disabled = "disabled";
			  $$("pm1").disabled = "disabled";
			  $$("pm2").disabled = "disabled"; 
			  $$("am1_minutos").disabled = "disabled"; 
			  $$("am2_minutos").disabled = "disabled";
			  $$("pm1_minutos").disabled = "disabled";
			  $$("pm2_minutos").disabled = "disabled"; 
			  $$("ingreso1").disabled = ""; 
			  $$("sale1").disabled = ""; 
			  $$("dias1").disabled = "disabled"; 
			  $$("dias2").disabled = "disabled"; 		  
		  break;	
		  case "Campo":
		      $$("am1").disabled = "disabled"; 
			  $$("am2").disabled = "disabled";
			  $$("pm1").disabled = "disabled";
			  $$("pm2").disabled = "disabled"; 
  			  $$("am1_minutos").disabled = "disabled"; 
			  $$("am2_minutos").disabled = "disabled";
			  $$("pm1_minutos").disabled = "disabled";
			  $$("pm2_minutos").disabled = "disabled"; 
			  $$("ingreso1").disabled = "disabled"; 
			  $$("sale1").disabled = "disabled"; 
			  $$("dias1").disabled = ""; 
			  $$("dias2").disabled = ""; 		  
		  break;  		  
	  }	  
  }
  
  var insertarNewItem = function(tabladestino) {
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var edad = getfecha($$("fechahijo").value);
    if (validarSubIngresoHijo()){
	  var datosIngreso =[
	  {data:"<img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	  {data:$$("tipohijo").value, type:"set"},
	  {data:$$("nombrehijo").value, type:"set"},
	  {data:$$("generohijo").value , type:"set"},
	  {data:$$("fechahijo").value , type:"set"},
	  {data:edad , type:"set"}
	  ];
      cargarDatos(formato, datosIngreso, tabladestino);
	  limpiarIngresoHijo();
	}
  }
  
  
  var limpiarIngresoHijo = function() {
	$$("nombrehijo").value = "";
	$$("edadhijo").value = "0";  
	var f = new Date();
	$$("fechahijo").value = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
	$$("nombrehijo").focus();
  }
  
  var validarSubIngresoHijo = function() {
	if ($$("nombrehijo").value == "") {
	   openMensaje("Advertencia",'Ingrese el nombre completo del ' + $$("tipohijo").value + ".");   
	   return false;	
	}	
	if ($$("fechahijo").value == "") {
		openMensaje("Advertencia",'Ingrese la fecha de nacimiento. ');   
	   return false;
	}	
	if (!validarFecha($$("fechahijo").value)) {		
	   openMensaje("Advertencia",'Ingrese una fecha de nacimiento valida.');   
	   return false;	
	}
	return true;
  }
  
  
  var getFormatoColumna = function() {
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'}
	];
	return formato;	
 }
 
 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
    table.removeChild(tr);
 }
 
 var insertarNewItemFamilia = function(tabladestino) {
	var formato = getFormatoColumnaFamilia();
	var n =  $$(tabladestino).rows.length + 1;
    if (validarSubIngresoFamilia()){
	  var datosIngreso =[
	  {data:"<img src='css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	  {data:$$("parentesco").value, type:"set"},
	  {data:$$("nombrefamiliar").value, type:"set"},
	  {data:$$("fechafamiliar").value , type:"set"},
	  {data:$$("direccionfamiliar").value , type:"set"},
	  {data:$$("telefonofamiliar").value , type:"set"}
	  ];
      cargarDatos(formato, datosIngreso, tabladestino);
	  limpiarIngresoFamilia();
	}
  }
  
  
  var limpiarIngresoFamilia = function() {
	$$("nombrefamiliar").value = "";
	$$("direccionfamiliar").value = "";
	$$("telefonofamiliar").value = "";
	var f = new Date();
	$$("fechafamiliar").value = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
	$$("nombrefamiliar").focus();
  }
  
  var setValorCheck = function(id) {
	if ($$(id).checked) {
	  $$(id).value = "1";	
	} else {
	  $$(id).value = "0";	
	}
  }
  
  var validarSubIngresoFamilia = function() {
	if ($$("nombrefamiliar").value == "") {
	   openMensaje("Advertencia",'Ingrese el nombre completo del familiar.');   
	   return false;	
	}	
	if ($$("direccionfamiliar").value == "") {
		openMensaje("Advertencia",'Ingrese la dirección del familiar. ');   
	   return false;
	}	
	if ($$("fechafamiliar").value == "") {
		openMensaje("Advertencia",'Ingrese la fecha de nacimiento. ');   
	   return false;
	}
	if (!validarFecha($$("fechafamiliar").value)) {		
	   openMensaje("Advertencia",'Ingrese una fecha de nacimiento valida.');   
	   return false;	
	}
	return true;
  }
  
  
  var getFormatoColumnaFamilia = function() {
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'}
	];
	return formato;	
 }
 
  function checkclick(id){ 
   if (document.getElementById(id).checked) 
     document.getElementById(id).value=1; 
   else 
     document.getElementById(id).value=0;
  }
  
  
  function recuperaAuxiliares(flag,tipo){
	  if (flag != '') {
		if (tipo == "vendedor")  
	     document.getElementById('vende_active_check').click();
	    if (tipo == "cajero") 
		 document.getElementById('cajero_check').click();
	  }
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

  
  
  