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
      var filtro = "tipo=validarUsuario&usuario="+ $$("usuario").value +"&idtransaccion=" + $$("identificador").value 
	  + "&idtrabajador="+ $$("idpersonarecibida").value + "&tipoT=" + $$("tipo").value +
	  "&idsucursal="+ $$("sucursal").value;
      enviar("Dusuario.php",filtro,resultadoValidacion);
  } else {
	  validarForm();  
  }
 } 
 
 function EvalSound(soundobj) {
  var thissound=document.getElementById(soundobj);
  thissound.play();
}
 
 var resultadoValidacion = function(resultado){ 
	var resultado = resultado.split("---"); 	
	if (parseFloat(resultado[1]) > 0) {
	    $$("botonUsuario").type = "button";	
        $$("Vtrabajador").style.display = "block"; 
		$$("Vtrabajador").innerHTML = "El trabajador seleccionado ya tiene una cuenta de usuario.";
		return;	
	}
	 
	if (parseFloat(resultado[2]) > 0) {
	    $$("botonUsuario").type = "button";	
        $$("Vsucursal").style.display = "block"; 
		$$("Vsucursal").innerHTML = "No puede cambiar de sucursal, aun tiene mesas sin cobrar.";
		return;	
	}
	
	if (parseFloat(resultado[3]) > 0) {
	    $$("botonUsuario").type = "button";	
        $$("Vsucursal").style.display = "block"; 
		$$("Vsucursal").innerHTML = "No puede cambiar de sucursal, aun no entrego dinero.";
		EvalSound('audio1');
		return;	
	}
	 
	if (parseFloat(resultado[0]) > 0) { 
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
	  var filtro = "tipo=listaUsuarios&idsucursal=" + $$("sucursal").value; 
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
	 
 
  var tipoBusquedaTrabajador = function(e) {
	 var sql;
	 tipocliente = $$("tipo").value;
     switch(tipocliente){
		case 'fijo':
		   sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t,"
		   + "usuariorestaurante a  where a.estado=1 and a.idtrabajador=t.idtrabajador and a.tipo='fijo' and t.nombre "
		   + " like '"+ $$("trabajador").value + "%' group by t.idtrabajador limit 9;";
		   idconsulta = "idtrabajador"; 
		 break;	
		 case 'apoyo':
		   sql = "select p.idpersonalapoyo,left(concat_WS(' ',p.nombre,p.apellido),20)as 'nombre' from personalapoyo p "
		   + ",usuariorestaurante a where a.estado=1 and a.idtrabajador=p.idpersonalapoyo and a.tipo='apoyo' and p.nombre "
		   + " like '"+$$("trabajador").value+"%'  group by p.idpersonalapoyo limit 9;";
		   idconsulta = "idpersonalapoyo";
		 break;		 
	  }		
	  eventoTeclas(e,"trabajador",'resultados',tipocliente,'nombre',idconsulta
	  ,'eventoResultadoTrabajador','../autocompletar/consultor.php'
	  ,sql,'<sinfiltro>','autoL1');
 }
 
  var eventoResultadoTrabajador = function(resultado,codigo){
	  $$("trabajador").value= resultado;
	  $$("idtrabajador").value = codigo;
	  var filtro = "transaccion=consultaDeuda&idtrabajador="+codigo+"&tipo="+$$("tipo").value;
      enviar("Dentrega.php",filtro,deudaTrabajador);	   
 } 	 
 
 var deudaTrabajador = function(resultado){
	 var total = resultado.split("---");
     $$("totalventa").value = convertirFormatoNumber(parseFloat(total[0]).toFixed(2));
     $$("nuloventa").value = total[1];
     $$("cortesiaventa").value = total[2];
     $$("creditoventa").value = total[3];
 }

 var limpiarTrabajador = function() {
	  $$("trabajador").value= "";
	  $$("idtrabajador").value = "";
	  $$("totalventa").value = "0";
	  $$("entregado").value = "0";
	  $$("faltante").value = "0";
 }

  var calcularFaltante = function(id){
	var total = ($$("totalventa").value == "") ? 0 :  parseFloat(desconvertirFormatoNumber($$("totalventa").value));
	var entregado = ($$(id).value == "") ? 0 : parseFloat($$(id).value);
	$$("faltante").value = convertirFormatoNumber(parseFloat(total - entregado).toFixed(2));
  }	 
	 
	 
  var desconvertirFormatoNumber = function(cadena){
	convertir = "";     
	for(i = 0;i < cadena.length; i++){		
		if (cadena[i] != ",")
		convertir = convertir + cadena[i];
	}
	return convertir;
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
  
  var setEntrega = function(){ 	 
	if (validoFormulario()) { 
	$$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";
	 var filtro = "transaccion="+$$("transaccion").value +"&idtrabajador="+ $$("idtrabajador").value +"&idtransaccion=" 
	 + $$("idtransaccion").value+"&fecha=" + $$("fecha").value + "&acumulado="+desconvertirFormatoNumber($$("totalventa").value) 
	 + "&tipo="+ $$("tipo").value + "&monto="+ $$("entregado").value+"&caja="+ $$("caja").value+"&nulo="+$$("nuloventa").value
	 + "&cortesia="+$$("cortesiaventa").value +"&credito="+$$("creditoventa").value ;
	 enviar("Dentrega.php", filtro, resultadoEntrega);
	} 
 }
 
 var validoFormulario = function() {
	var flag = true;
	
	if (!validarFecha($$("fecha").value)) {
		$$("msjfecha").innerHTML = "Fecha Invalida.";
	    $$("msjfecha").style.display = "block";
		flag = false;
	}
	
	if ($$("idtrabajador").value == "") {
		$$("msjtrabajador").innerHTML = "Trabajador no registrado.";
	    $$("msjtrabajador").style.display = "block";
		flag = false;	
	}
	
	if ($$("trabajador").value == "") {
		$$("msjtrabajador").innerHTML = "Este campo es requerido.";
	    $$("msjtrabajador").style.display = "block";
		flag = false;	
	}
	
	if ($$("entregado").value == "") {
		$$("msjentregado").innerHTML = "Este campo es requerido.";
	    $$("msjentregado").style.display = "block";
		flag = false;	
	}
	
	if ($$("caja").value == "") {
		$$("msjcaja").innerHTML = "Este campo es requerido.";
	    $$("msjcaja").style.display = "block";
		flag = false;	
	}
	
	
	
	if (!isvalidoNumero("entregado")) {
		$$("msjentregado").innerHTML = "Numero Invalido.";
	    $$("msjentregado").style.display = "block";
		flag = false;	
	}	
		
	return flag; 
 }
 
 
 var resultadoEntrega = function(){
    jumpNuevo();
 }
 
 var jumpNuevo = function(){	 
	location.href = "nuevo_entrega.php"; 
 }
 
 function soloNumeros(evt){
     var tecla = (document.all) ? evt.keyCode : evt.which;
     return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
 }
 
 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }

function validarFecha(value){  
    var Fecha= new String(value);   
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length));  
    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")));
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")));  
  
    if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
        return false;  
    }  
 
    if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
        return false;  
    }  

    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
        return false  ;
    }  
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
        if (Mes==2 && Dia > 28 || Dia>30) {  
            return false;  
        }  
    }      

  return true;   
}