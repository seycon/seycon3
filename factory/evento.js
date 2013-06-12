var $$ = function(id){
    return document.getElementById(id);	 
}

var ajax = function(){
    return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

 function consultar(servidor,filtro,funcion){
    var  pedido = ajax();	
    pedido.open("GET",servidor+"?"+filtro,true);
    pedido.onreadystatechange = function(){
        if (pedido.readyState == 4){     	
            var resultado = pedido.responseText; 
            if (funcion != null)
                funcion(resultado); 
        }	   
    }
    pedido.send(null);
 }

 var jumpPedido = function(nroatencion,nropedido){	 
	location.href = "nuevo_detalleVenta.php?atencion="+nroatencion+"&pedido="+nropedido; 
 }  

 var setNroPedido = function(nro){
   var filtro = "tipo=nropedido&atencion="+nro;
   consultar("Datencion.php",filtro,resultadoNroPedido);
 } 
 
 var resultadoNroPedido = function(resultado){	 
   resultado = resultado.split("---");
   jumpPedido(resultado[0],resultado[1]);
 }


function entrar(){    
    filtro ="usuario="+$$('usuario').value+"&clave="+$$('dato').value;  
    consultar('autentificar.php',filtro,resultados);
}

var setNuevaAtencion = function(){
   var filtro = "tipo=mesas";
   consultar("Datencion.php",filtro,resultadoAtencion);
} 

var resultadoAtencion = function(resultado){
   $$("mesasAtencion").innerHTML = resultado; 
}


var resultados =function(resultado){ 
    if(resultado == "correcto"){
        location.href='inicio_restaurante.php';
    }else
    {
        $$('aviso').innerHTML = '<label>Usuario o Clave Incorrecta</label>';
    }
}
function enter_entrar(evento){
    var tecla = (document.all) ? evento.keyCode : evento.which;		
    if (tecla == 13)
        entrar();
}


var getTotalMesa = function(idnotaventa,idatencion){	
	var filtro = "tipo=totalMesa&idnotaventa="+idnotaventa;
	$$("idnotaventa").value = idnotaventa;
	$$("idatencion").value = idatencion;
    consultar("Dventa.php",filtro,resultadoTotalMesa);	
}

var resultadoTotalMesa = function(resultado){
 	var datos = resultado.split("---");
   	openVentanaClave(datos[0],datos[1],datos[2]);	
}


var insertarCobranza = function(){
	var filtro = "tipo=actualizarCobroMesa&estado=cobrado&idatencion="+$$("idatencion").value+"&acuenta="+$$("acuenta").value;
	consultar("Dventa.php",filtro,resultadoCobranza); 	 
 }
 
 var resultadoCobranza = function(){
	location.href = 'nuevo_cobrarMesa.php'; 
 }


var openVentanaClave = function(total,tiponota,cliente){
	var nro = $$("idnotaventa").value;
   	$$("cortesia").value = "0";
	$$("cliente").value = cliente;
	seleccionarCombo('tiponota',tiponota);
	if (tiponota == "Contado"){
	  $$("acuenta").disabled = "disabled";
	}	
	$$("efectivobs").value = "0";
	$$("efectivods").value = "0"; 
	$$("modal1").style.visibility = "visible";
	$$("modalInterior1").style.visibility = "visible";  
	$$("tituloCobranza").innerHTML = "Cobranza de Mesa - Nota venta #"+nro;
	$$("totalgeneral").value = convertirFormatoNumber(parseFloat(total).toFixed(2));
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
 
var closeVentanaClave = function(){
	$$("modal1").style.visibility = "hidden";
	$$("modalInterior1").style.visibility = "hidden";  
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


function soloNumeros(evt){
var tecla = (document.all) ? evt.keyCode : evt.which;
return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
}

