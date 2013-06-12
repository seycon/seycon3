// JavaScript Document

 var $$ = function(id){
   return document.getElementById(id);
 }
 
 var openVentanaClave = function(id){
    $$("errorClave").innerHTML = ""; 
	$$("idtrabajador").value = id;
	$$("modal").style.visibility = "visible";
	$$("modalInterior").style.visibility = "visible";  
	$$("clave").value = "";
	$$("clave").focus();
 }
 
 var closeVentanaClave = function(){
	$$("modal").style.visibility = "hidden";
	$$("modalInterior").style.visibility = "hidden";  
 }
 
 var jumpAtencionTrabajador = function(id){
	location.href = "autentificar.php?usuario="+id;
 }
 
 var jumpIngreso = function(){	 
	location.href = "nuevo_atencion.php"; 
 } 

 var jumpPedido = function(nroatencion,nropedido){	 
	location.href = "nuevo_pedido.php?atencion="+nroatencion+"&pedido="+nropedido; 
 }   

 function eventoText(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
   if (tecla == 13){
    validarClave();
   }
 } 
 
 var setNroPedido = function(nro){
   var filtro = "tipo=nropedido&atencion="+nro;
   enviar("Datencion.php",filtro,resultadoNroPedido);
 } 
 
 var resultadoNroPedido = function(resultado){	 
   resultado = resultado.split("---");
   jumpPedido(resultado[0],resultado[1]);
 } 
 
 var setNuevaAtencion = function(){
   var filtro = "tipo=mesas";
   enviar("Datencion.php",filtro,resultadoAtencion);
 } 
 
 var resultadoAtencion = function(resultado){
   $$("mesasAtencion").innerHTML = resultado; 
 }
 
 function ajax() {
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
 }
 
 function enviar(servidor,filtro,funcion){ 
  var  pedido = ajax();	
  pedido.open("GET",servidor+"?"+filtro,true);
  pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){
     	  respuesta = pedido.responseText;
		  if (funcion != null){		  
		   funcion(respuesta);
		  }
	   }	   
   }
   pedido.send(null);   	
 }
 
 
 var getValidacionUsuario = function(){ 	 
  if ($$("usuario").value != ""){ 
   var filtro = "tipo=validarUsuario&usuario="+ $$("usuario").value +"&idtransaccion=" + $$("identificador").value;
   enviar("Dusuario.php",filtro,resultadoValidacion);
  } else {
	validarForm();  
  }
 } 
 
 var resultadoValidacion = function(resultado){ 
	 
	if (parseFloat(resultado) > 0) { 
	    $$("botonUsuario").type = "button";	
        $$("msjError").style.display = "block"; 
		$$("msjError").innerHTML = "El usuario ingresado ya existe, debe ingresar otro nombre de usuario.";
	} else {
		if (validarForm()) {
            document.formulario.submit();
		}
	}
 }
 
 
 var validarForm = function(){
     var flag = true;
	 $$("Vsucursal").style.display = "none"; 
	 $$("Vtrabajador").style.display = "none"; 
	 $$("Vclave").style.display = "none"; 
	 $$("msjError").style.display = "none"; 
	 
	 if ($$("sucursal").value == "") {
		$$("Vsucursal").style.display = "block"; 
		flag = false;
	 }
	 if ($$("idpersonarecibida").value == "") {
		$$("Vtrabajador").style.display = "block"; 
		flag = false;
	 }
	 if ($$("clave").value == "") {
		$$("Vclave").style.display = "block"; 
		flag = false;
	 }
	 if ($$("usuario").value == "") {
		$$("msjError").style.display = "block"; 
		$$("msjError").innerHTML = "Este campo es requerido.";
		flag = false;
	 }
	 
	 return flag; 	 
 }
 
 var getListaUsuarios = function(){
	if ($$("sucursal").value != ""){ 
	  var filtro = "tipo=listaUsuarios&idsucursal="+$$("sucursal").value; 
	  enviar("Dusuario.php",filtro,setListaUsuarios);
	}
 }
 
 var setListaUsuarios = function(respuesta){
	 $$("listaUser").innerHTML = respuesta;	 
 } 
 
 var getPersonal = function(value){
	$$("texto").value = "";
	$$("idpersonarecibida").value = "";
 }
 
 var setPersonal = function(resultado){
   $$("trabajador").innerHTML = resultado;	 
 }
 
 var getTrabajador = function(sucursal){
	if ($$("tipo").value == "fijo"){ 
	 var filtro = "tipo=trabajador&sucursal="+sucursal; 
	 enviar("Dusuario.php",filtro,setTrabajadores);	
	}
 }
 
 var setTrabajadores = function(value){
	$$("trabajador").innerHTML = value; 
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
 
 var tipoBusqueda = function(e) {
	 var sql;
	 tipocliente = $$("tipo").value;
     switch(tipocliente){
		case 'fijo':
		   sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t where estado=1 and  ";
		   idconsulta = "idtrabajador"; 
		 break;	
		 case 'apoyo':
		   sql = "select idpersonalapoyo,left(concat_WS(' ',nombre,apellido),20)as 'nombre' from personalapoyo where estado=1 and ";
		   idconsulta = "idpersonalapoyo";
		 break;		 
	  }		
	  eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultadoEgreso','../autocompletar/consultor.php'
	  ,sql,'','autoL1');
 }
 
  var eventoResultadoEgreso = function(resultado,codigo){
	  $$("texto").value= resultado;
	  $$("idpersonarecibida").value = codigo;	  	   
 }
	 