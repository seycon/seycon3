// JavaScript Document
var comisionT = 0;
var botellaT = 0;
var haberT = 0;
var faltanteT = 0;
var tApoyo = -1;

 var $$ = function(id){
   return document.getElementById(id);
 }
 
 var setBlanco = function() {
	comisionT = 0;
    botellaT = 0;
    haberT = 0;
    faltanteT = 0; 
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
 
 var consultarPendientes = function() {
     if (tApoyo != $$("trabajadorapoyo").value) {
		 tApoyo = $$("trabajadorapoyo").value;		 
	     var filtro = "tipo=pendientes&idapoyo="+$$("trabajadorapoyo").value;
	     enviar("Dplanilla.php", filtro, resultadoPendiente);
	 } else {
		 $$("modal1").style.visibility = "visible";
	     $$("modalInterior1").style.visibility = "visible"; 
	 }
 }
 
 
 var getDetalle = function() {
	var nfilas = $$('deudasPendiente').rows.length;    	
     json = new Array();
	 var j = 0;
     for(var i=0; i<nfilas; i++) {
		if ($$('deudasPendiente').rows[i].cells[9].innerHTML == "1") { 
		  var fecha = desconvertirFormatoNumber($$('deudasPendiente').rows[i].cells[1].innerHTML);
		  var idsucursal = desconvertirFormatoNumber($$('deudasPendiente').rows[i].cells[8].innerHTML);
		  var venta = desconvertirFormatoNumber($$('deudasPendiente').rows[i].cells[2].innerHTML); 		 
	      var comision = desconvertirFormatoNumber($$('deudasPendiente').rows[i].cells[3].innerHTML);
		  var botella = desconvertirFormatoNumber($$('deudasPendiente').rows[i].cells[4].innerHTML);
		  var haber = desconvertirFormatoNumber($$('deudasPendiente').rows[i].cells[5].innerHTML);
		  var faltante = desconvertirFormatoNumber($$('deudasPendiente').rows[i].cells[6].innerHTML);
		  
		  vector = [fecha, idsucursal, venta, comision, botella, haber, faltante]; 												   	
		  json[j] = vector;
		  j++;
		}	    	 		
     }
     dato = JSON.stringify(json); 
	 return dato;
 }
 
 
 var guardarDatos = function() {
	if (validoFormulario()) { 
	    var total = desconvertirFormatoNumber($$("monto").value); 
	    var filtro = "tipo=insertar&total="+total+"&idtrabajador="+$$("trabajadorapoyo").value 
	    + "&fecha="+ $$("fecha").value + "&detalle=" +getDetalle() + "&anticipo=" + faltanteT 
		+ "&caja="+ $$("caja").value; 
	    enviar("Dplanilla.php", filtro, resultadoGuardar);
	}
 }
 
 var resultadoGuardar = function(resultado) {
	window.location = "nuevo_planilla.php"; 
 } 
 
 
 var validoFormulario = function() {
	var flag = true;
	$$("msjfecha").style.display = "none";
	$$("msjtrabajador").style.display = "none";
	$$("msjmonto").style.display = "none";
	
	if (!validarFecha($$("fecha").value)) {
		$$("msjfecha").innerHTML = "Fecha Invalida.";
	    $$("msjfecha").style.display = "block";
		flag = false;
	}
	
	if ($$("trabajadorapoyo").value == "") {
		$$("msjtrabajador").innerHTML = "Este campo es requerido.";
	    $$("msjtrabajador").style.display = "block";
		flag = false;	
	}
	
	if ($$("monto").value == "") {
		$$("msjmonto").innerHTML = "Este campo es requerido.";
	    $$("msjmonto").style.display = "block";
		flag = false;	
	}
	
	if ($$("caja").value == "") {
		$$("msjcaja").innerHTML = "Este campo es requerido.";
	    $$("msjcaja").style.display = "block";
		flag = false;	
	}
		
	return flag; 
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
 
 var tipoBusquedaTrabajador = function(e) {
	 var sql;
     sql = "select p.idpersonalapoyo,left(concat_WS(' ',p.nombre,p.apellido),20)as 'nombre' from personalapoyo p "
	   + ",usuariorestaurante a where a.estado=1 and a.idtrabajador=p.idpersonalapoyo and a.tipo='apoyo' and p.nombre "
	   + " like '"+$$("trabajador").value+"%'  group by p.idpersonalapoyo limit 9;";
	   	
	 eventoTeclas(e,"trabajador",'resultados',"apoyo",'nombre',"idpersonalapoyo"
	  ,'eventoResultadoTrabajador','../autocompletar/consultor.php',sql,'<sinfiltro>','autoL1');
 }
 
 var eventoResultadoTrabajador = function(resultado,codigo){
	  $$("trabajador").value= resultado;
	  $$("trabajadorapoyo").value = codigo;
 } 	 
 
 
 var resultadoPendiente = function(resultado) {
	 $$("deudasPendiente").innerHTML = resultado;
	 $$("totalDeuda").value = "0.00";
	 setBlanco();
     $$("modal1").style.visibility = "visible";
	 $$("modalInterior1").style.visibility = "visible";	 
 }
 
 var closeVentanaClave = function() {
    $$("modal1").style.visibility = "hidden";
	$$("modalInterior1").style.visibility = "hidden";
	$$("monto").value = $$("totalDeuda").value;
 }
 
 var setTotalesOption = function(t, seleccion) {
	var aux = 1; 
	if (seleccion == false){
	    aux = -1; 	
	}
    var td = t.parentNode;
    var tr = td.parentNode;
	comisionT = comisionT + (desconvertirFormatoNumber(tr.cells[3].innerHTML) * aux);
	botellaT = botellaT + (desconvertirFormatoNumber(tr.cells[4].innerHTML) * aux);
	haberT = haberT + (desconvertirFormatoNumber(tr.cells[5].innerHTML) * aux);
	faltanteT = faltanteT + (desconvertirFormatoNumber(tr.cells[6].innerHTML) * aux);	
	var totalG = (comisionT + botellaT + haberT) - faltanteT;
	$$("totalDeuda").value = convertirFormatoNumber(parseFloat(totalG).toFixed(2));
	
	if (aux == 1) {
	    tr.cells[9].innerHTML = "1";	
	} else {
		tr.cells[9].innerHTML = "0";
	}
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
 