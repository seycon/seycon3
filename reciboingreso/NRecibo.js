  var servidorPrincipal = 'reciboingreso/DRecibo.php';
  var irDireccion = "listar_reciboingreso.php#t1";
  var totalTransaccion = 0;
  var idcliente = 0;
  var idreciboIngreso = 0;
  var transaccionS = "";
  
  document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 113://F2
	  if($$('overlay').style.visibility != "visible")
	   ejecutarTransaccion();
	break;
	case 115://F4
	   if ($v("cancelar") != null)
	     salir();
	break;
   }
 }
      
 var salir = function(){
	location.href = irDireccion;
}	  
  
  window.onload = function() {
	  $v('texto').focus();
  }
  
  var cambiarDependencias = function(){
	  $v("texto").value = "";
	  $v("idpersonarecibida").value = "0";
  } 
  
  function $v(id){
	  return document.getElementById(id);
  }

	function enterInput(e){
		tecla = (document.all) ? event.keyCode : e.which; 
	      if(tecla==13){
              $v('aceptar_modal').click();
          }
	}

 
  function ajaxx() {
	 return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
  }
    
   var eventoResultado = function(resultado,codigo){
	   	   $v("texto").value= resultado;
		   $v("idpersonarecibida").value = codigo;
   }
  
   var tipoBusqueda = function(e){
	   tipocliente = $$('receptor').options[$$('receptor').selectedIndex].value;
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
	    eventoTeclas(e,"texto",'cliente',tipocliente,'nombre',idconsulta,'eventoResultado','autocompletar/consultor.php',sql,'','autoL1');
	   }
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
  
  function ejecutarTransaccion(){
	  var cadena;
		if (validar()){	  
		 $$('overlay').style.visibility = "visible";
         $$('gif').style.visibility = "visible";
		 nfilas = $v('a').rows.length;		 	    	
         json = new Array();
          for(i=0;i<nfilas;i++) {
	        vector = new Array();
		    vector[0]=$v('a').rows[i].cells[3].innerHTML;		
		    vector[1]=$v('a').rows[i].cells[2].innerHTML;			
		    json[i] = vector;	 		
          }
          dato = JSON.stringify(json); 		    
          cadena = 'nuevo=nuevo&idReciboI='+$v('idReciboI').value+'&fecha='+$v('fecha').value+'&idrecibido='
		  +$v('idpersonarecibida').value+
               '&totalingreso='+totalTransaccion+'&nombrerecibido='+$$U('texto')+"&pagado="
			   +$v('pagado').checked+"&firmaDigital="+$v('firmadigital').checked
			   +"&codigo="+$v("codigo").value+"&cargo="+$$('receptor').value+"&transaccion="+transaccionS+
			   "&detalle="+encodeURIComponent(dato);
           enviar(cadena,resultadoEjecutarTransaccion);
     }
  }
  
  
var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}
  
  
  var resultadoEjecutarTransaccion = function(resultado){
	   datos = resultado.split("---"); 
		  if (datos[1] == "1"){
		    idreciboIngreso = datos[0];
            $v('modal_vendido').style.visibility = 'visible';
			$$('gif').style.visibility = "hidden";
		  }else{
			cerrarPagina();  
		  }
  }
  

  function validar(){
    tipocargo = $$('receptor').options[$$('receptor').selectedIndex].value; 
	 if ($v('texto').value==''){
		 openMensaje("Advertencia",'Debe ingrese el cliente');
		 return false;
	 }
	 
	 if (($v('idpersonarecibida').value=='' || $v('idpersonarecibida').value=='0') && tipocargo!="otros" ){
		openMensaje("Advertencia"," "+tipocargo+" no es valido");
		return false; 
	 }

	 if ($v('a').rows.length < 1){
		 openMensaje("Advertencia",'No hay items para registrar');
		 return false;
	 }
	 
	 return true;	 
  }
  
  var validarSubIngreso = function(){
	if (!isvalidoNumero("importe")){
	  openMensaje("Advertencia","El importe ingresado es incorrecto.");
	  return false;
	}
	return true; 
  }

  var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
  }
  
  
var insertarNewItem = function(tabladestino){
  if (validarSubIngreso()){	
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var datosIngreso =[
	{data:"<img src='css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data :n , type:"set"},
	{id :"descripcion", type:"get" },
	{id :"importe", type:"get"}
    ];
	var total = cargarDatos(formato,datosIngreso,tabladestino);
	if (total.length > 0)
	cargarTotales(total[0]);
  }
}

var cargarTotales = function(total){
	totalTransaccion = parseFloat(totalTransaccion) + parseFloat(total);
	$v('total_ingreso').value = parseFloat(totalTransaccion).toFixed(2);
	$$("descripcion").focus();	
}

var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'contable', numerico : 'si', aling : 'center'}	
	];
	return formato;	
}

var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
	cargarTotales(-desconvertirFormatoNumber(tr.cells[3].innerHTML));	
    table.removeChild(tr);
	orderNumeroItem();
}

var orderNumeroItem = function(){
	var n =  $$("a").rows.length;
	for (var i=0;i<n ;i++){
		$$("a").rows[i].cells[1].innerHTML = i+1;
	}
}

 function enter(input,e){ 
  var tecla = (document.all) ? event.keyCode : e.which; 
	  if (tecla==13){
		if (input == 'input'){
		 insertarNewItem('a');
		}
		$v('descripcion').focus();
    }
 }

 function accionPostRegistro(){
   window.open('reciboingreso/imprimir_reciboIngreso.php?idrecibo='+idreciboIngreso,'target:_blank');
   cerrarPagina();	
 }  

 function cerrarPagina(){
   location.href = 'nuevo_reciboingreso.php';
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
  
  function soloNumeros(evt){
    var tecla = (document.all) ? evt.keyCode : evt.which;
    return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
  }

