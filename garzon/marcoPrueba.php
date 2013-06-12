<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>

<script>

 var $$ = function(id){
   return document.getElementById(id);
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

 
   var setEspacios = function(numero) {
	   var cadena = "";
       for (var  i = 1; i <= numero; i++) {
		   cadena = cadena + " ";
	   }
	   return cadena;
   }

   var setTitulo = function(titulo) {
	   return setEspacios(8) + str_pad(titulo,25," ", "STR_PAD_BOTH") + "\n ";
   }
   
   var setContenido = function(producto, cantidad, precio, total) {
	 var cadena =  setEspacios(1) + str_pad(cantidad,3," ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(producto, 17, " ", "STR_PAD_RIGHT") + 
	 setEspacios(1) + str_pad(convertirFormatoNumber(parseFloat(precio).toFixed(1)), 6, " ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(convertirFormatoNumber(parseFloat(total).toFixed(1)), 7, " ", "STR_PAD_RIGHT") + " \n ";
     return cadena;
   }
   
        
   
   
   var setnotaVentaR2 = function(nro, tipo) {
	   return setEspacios(2) + "Num. Venta: "+ str_pad(nro, 7, " ", "STR_PAD_RIGHT") + setEspacios(4) + "Tipo: "+ tipo + " \n ";
   }
   
    
     
   var setTitulo3 = function(){
	   return setEspacios(12) + "ENTREGA DE DINERO \n "; 
   }  
   
   
  
  
   var setGarzon = function(usuario) {
	   return setEspacios(2) + "Garzon: " + usuario + " \n \n ";  
   }
   
   var setFechaPrincipalR3 = function(fecha, dia) {
	  var date = fecha.split("-");
	  return " Venta del " + getDia(dia) + " " + date[2] + " de " + getMes(date[1]) + " de " + date[0] + " \n \n ";
   }
   
   var setSession = function(nombre) {
	   return setEspacios(1) + nombre + " \n "; 
   }
   
   var setCabeceraR3 = function(sucursal, fecha, usuario, dia) {
	   var cabecera = "";
	   cabecera = setTitulo("SUCURSAL - "+ sucursal) + setTitulo3()  +  setFechaPrincipalR3(fecha, dia) + setGarzon(usuario) 
	   + setSession("CORTESIA"); 
       return cabecera;
   }
   
   
   var setDivision = function(num) {
	   var cadena = "" + setEspacios(1);	   
	       for (var i = 0; i <= num; i++){
	           cadena = cadena + "_"; 	   
	       }
	   cadena = cadena + " \n ";	   
       return cadena;
   }
   
   var setDivisionR3 = function(num) {
	   var cadena = "" + setEspacios(1);	   
	       for (var i = 0; i <= num; i++){
	           cadena = cadena + "="; 	   
	       }
	   cadena = cadena + " \n ";	   
       return cadena;
   }
   
   var setSubTituloR3_1 = function() {       
	   var cadena = setDivisionR3(37) + " Cant     Producto     N.V.     Pedido \n " + setDivisionR3(37);   
	   return cadena;
   }
   
   var setContenidoR3_1 = function(producto, cantidad, nv, pedido) {
	 var cadena =  setEspacios(1) + str_pad(cantidad,3," ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(producto, 17, " ", "STR_PAD_RIGHT") + 
	 setEspacios(1) + str_pad(nv, 6, " ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(pedido, 7, " ", "STR_PAD_RIGHT") + " \n ";
     return cadena;
   }
   
   var setSubTituloR3_2 = function() {       
	   var cadena = setDivisionR3(37) + " Cant     Producto     Trabajador \n " + setDivisionR3(37);   
	   return cadena;
   }
   
   var setContenidoR3_2 = function(producto, cantidad, trabajador) {
	 var cadena =  setEspacios(1) + str_pad(cantidad,3," ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(producto, 17, " ", "STR_PAD_RIGHT") + 
	 setEspacios(1) + str_pad(trabajador, 12, " ", "STR_PAD_RIGHT") + " \n ";
     return cadena;
   }
   
   var setSubTituloR3_3 = function() {       
	 var cadena = setDivisionR3(37) + " Cant     Producto     P/U     P/Total \n " + setDivisionR3(37);   
	 return cadena;
   }
   
   var setContenidoR3_3 = function(producto, cantidad, precio, total) {
	 var cadena =  setEspacios(1) + str_pad(cantidad,3," ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(producto, 17, " ", "STR_PAD_RIGHT") + 
	 setEspacios(1) + str_pad(convertirFormatoNumber(parseFloat(precio).toFixed(1)), 6, " ", "STR_PAD_RIGHT") +
	 setEspacios(1) + str_pad(convertirFormatoNumber(parseFloat(total).toFixed(1)), 7, " ", "STR_PAD_RIGHT") + " \n ";
     return cadena;
   }
   
   var setTotalR3 = function(total, entrega, faltante) {
	 var cadena = "";
	 return setEspacios(28) + setDivision(7) + setEspacios(11) + "Venta al Contado: " 
	 + str_pad( convertirFormatoNumber(parseFloat(total).toFixed(2)), 7, " ", "STR_PAD_RIGHT") + " \n "
	 + setEspacios(13)+ "Entrega Dinero: " 
	 + str_pad(convertirFormatoNumber(parseFloat(entrega).toFixed(2)), 7, " ", "STR_PAD_RIGHT")
	 + " \n "
	 + setEspacios(12)+ "Faltante Dinero: " 
	 + str_pad(convertirFormatoNumber(parseFloat(faltante).toFixed(2)), 7, " ", "STR_PAD_RIGHT") 
	 + " \n \n ";
   }
   
   var setFirmaR3 = function(garzon, usuario, ci) {	 
	 return   setEspacios(1) + "_______________      _______________\n " 
	 + str_pad("C.I. "+ci, 15, " ", "STR_PAD_BOTH")  + "        " + str_pad(usuario, 15, " ", "STR_PAD_BOTH") + " \n " 
	 + str_pad(garzon, 15, " ", "STR_PAD_BOTH") + " \n \n " ;
   }
   
   
   var setFechaR3 = function() {
       var fecha = new Date(); 
	   return setEspacios(2) + "Fecha: " + fecha.getDate() + "/" + (fecha.getMonth() +1) + "/" + fecha.getFullYear() 
	   + setEspacios(6) + "Hora: " + fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds()+ "  \n ";  
   }
   
   
   var getDatosVentaR3 = function() {
	  var filtro = "transaccion=reporte3&identrega=11"; 
	  enviar("Dreporte.php", filtro, setReporteR3); 
   }
   
   
   
     
   
   var setReporteR3 = function(resultado) {
      var datos = eval(resultado);
      var cadena = setCabeceraR3(datos[0][0], datos[0][1], datos[0][2], datos[0][3]);
      cadena = cadena + setSubTituloR3_1(); 
	 // cadena = cadena + setContenidoR3_1("Ducal", 5, 2, 3);
	  	  
	  var subdatos = datos[1];
	  for (var i=0; i<subdatos.length; i++) {
		  cadena = cadena + setContenidoR3_1( subdatos[i][0], subdatos[i][1], subdatos[i][2], subdatos[i][3]);
	  }
	  
	  
	  cadena = cadena + " \n ";
	  cadena = cadena + setSession("PRODUCTOS ELIMINADOS");
	  cadena = cadena + setSubTituloR3_1(); 
	  var subdatos = datos[2];
	  for (var i=0; i<subdatos.length; i++) {
		  cadena = cadena + setContenidoR3_1(subdatos[i][0], subdatos[i][1], subdatos[i][2], subdatos[i][3]);
	  }
	  
	  
	  cadena = cadena + " \n ";
	  cadena = cadena + setSession("CREDITO TRABAJADORES");
	  cadena = cadena + setSubTituloR3_2(); 
	  var subdatos = datos[3];
	  for (var i=0; i<subdatos.length; i++) {
		  cadena = cadena + setContenidoR3_2(subdatos[i][0], subdatos[i][1], subdatos[i][2]);
	  }
	   
	   
	  cadena = cadena + " \n ";
	  cadena = cadena + setSession("VENTA AL CONTADO");
	  cadena = cadena + setSubTituloR3_3(); 
	  var subdatos = datos[4];
	  var totalGeneral = 0;
	  for (var i=0; i<subdatos.length; i++) {
		  var subtotal = subdatos[i][1] * subdatos[i][2];
		  totalGeneral = totalGeneral + subtotal;
		  cadena = cadena + setContenidoR3_3(subdatos[i][0], subdatos[i][1], subdatos[i][2], subtotal);
	  }
	  
	  
	  var faltante = datos[0][7] - datos[0][6];
	  cadena = cadena + setTotalR3(totalGeneral, datos[0][6], faltante);
	  cadena = cadena + setFirmaR3(datos[0][2], datos[0][4], datos[0][5]); 
	  cadena = cadena + setFechaR3(); 
	 // alert(cadena);
	  print(cadena);	  
   }
   
   
   var getDia = function(option) {
	  var dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
	  return dias[option - 1];  
   }
   
   var getMes = function(option) {
      var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto'
	  , 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];    
	  return meses[option - 1];
   }
   
   
   function str_pad (input, pad_length, pad_string, pad_type) {
	var half = '',
	  pad_to_go;
  
	var str_pad_repeater = function (s, len) {
	  var collect = '',
		i;
  
	  while (collect.length < len) {
		collect += s;
	  }
	  collect = collect.substr(0, len);
  
	  return collect;
	};
  
	input += '';
	pad_string = pad_string !== undefined ? pad_string : ' ';
  
	if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {
	  pad_type = 'STR_PAD_RIGHT';
	}
	if ((pad_to_go = pad_length - input.length) > 0) {
	  if (pad_type == 'STR_PAD_LEFT') {
		input = str_pad_repeater(pad_string, pad_to_go) + input;
	  } else if (pad_type == 'STR_PAD_RIGHT') {
		input = input + str_pad_repeater(pad_string, pad_to_go);
	  } else if (pad_type == 'STR_PAD_BOTH') {
		half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
		input = half + input + half;
		input = input.substr(0, pad_length);
	  }
	}
     return input;
   }

   
   
   
    function print(datos) {
         var applet = document.jzebra;
		 
		  if (applet != null) {
			   applet.findPrinter();			   
			   applet.append (datos);			  
			   for (var i=0; i<=10; i++){
				applet.append ("  \n ");   
			   }
				
			   applet.print();
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

</script>


<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="js/html2canvas.js"></script>
<script type="text/javascript" src="js/jquery.plugin.html2canvas.js"></script>
</head>

<body>
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="./jzebra.jar" width="0px" height="0px">
      <param name="printer" value="zebra">
   </applet><br />
   
   <input type="button" onClick="getDatosVentaR3()" value="Print" />

</body>
</html>