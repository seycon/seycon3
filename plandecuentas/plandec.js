// JavaScript Document
var servidorT = "plandecuentas/DPlandecuentas.php";
var hass = ''
var idimg = '';

var $$ = function(id){
  return document.getElementById(id);	
}

function has(){
	$$('formulario').action = 'listar_plandecuentas.php'+location.hash;
	$$('formulario').submit();	
}

function closeVentana(){
  window.close();	
}

function on(){
	hass = location.hash;
	$$('haspost').value = hass.replace('#','');
	if (hass != ''){
	 a = hass.replace('#','');
     toggle($$(a), (a*1+900));
	}
}

function nuevo_evento(elemento, evento, funcion) {
      if (elemento.addEventListener) {
            elemento.addEventListener(evento, funcion, false);
      } else {
            elemento.attachEvent("on"+evento, funcion);
      }
}


function scrol(){
	window.scrollTo(0,9999999);
}

function ocultar(){
	 $$('overlay').style.visibility = 'hidden';
	 $$('modal').style.visibility = 'hidden';
}


function mostarInsert(fila,tipo){
	if (tipo == "insertar"){
	 vec = fila.cells[3].innerHTML.split('.');
	 vec[vec.length-2] = vec[vec.length-2]*1 +1;
	 nivel = vec.length;
	 fila_aux = ultimoHijo(fila);

	 if (!fila_aux){
		 vec = fila.cells[3].innerHTML.split('.');
		 vec = fila.cells[3].innerHTML+'01.';
	 } else{
	 
	   vec = fila_aux.cells[3].innerHTML.split('.');	   
	   vec[vec.length-2] = vec[vec.length-2]*1 +1;
	   
		  if (vec[vec.length-2]<10){		   
  	        vec[vec.length-2] = '0'+vec[vec.length-2];
		  }else{
		    vec[vec.length-2] = vec[vec.length-2];
		  }
	  
	   vec = vec.join('.'); 
	 }
	 
     $$('codigo').value = vec;
	 $$('idplan').value = 0;
	 $$('nivel').value = nivel;	
	 $$("tituloTransaccion").innerHTML = "Nueva Cuenta";
   	}else{
	var idplancuenta = fila.cells[2].innerHTML;
	$$('idplan').value = idplancuenta;	
	$$('codigo').value = fila.cells[3].innerHTML;	
	var cuenta = fila.cells[4].innerHTML;
	cuenta = eliminarCadena(cuenta,"&nbsp;");	 
	$$('cuenta').value = cuenta;
	moneda = fila.cells[7].innerHTML;	
	seleccionarCombo('moneda',moneda);
    $$("tituloTransaccion").innerHTML = "Modificar Cuenta";
	}
	  $$('overlay').style.visibility = 'visible';
	 $$('modal').style.visibility = 'visible';
}

function eliminarCadena(cadenacompleta,subcadena){
  while (cadenacompleta.indexOf(subcadena) != -1)
	cadenacompleta = cadenacompleta.replace(subcadena, "");
  return cadenacompleta;
}

 function eventoText(evt){
  var tecla = (document.all) ? evt.keyCode : evt.which;
   if (tecla == 13){
    has();
   }
 }


function toggle(fila, idimagen){	
	var escrol = document.documentElement.scrollTop;
    vec_img = $$(idimagen).src.split('/');
	nombre = $$(idimagen).src;
	if (vec_img[vec_img.length-1] == 'arrowarriba.png'){
		$$(idimagen).src = nombre.replace( 'arrowarriba.png', 'arrowsabajo.png');		
	}else{ 
	    $$(idimagen).src = nombre.replace( 'arrowsabajo.png', 'arrowarriba.png');
	}
	fila_ = recorrer(fila,idimagen);  
	contraerDescontraer();
}



function quitarMas(fila){
		aux_fila = fila.nextSibling;
		  while (aux_fila){
			      len = aux_fila.cells[3].innerHTML.split('.').length;
			      if (len == 7){
				    aux_fila.cells[1].innerHTML = '';
					aux_fila.style.display = 'none';
					aux_fila.cells[0].innerHTML = '';
				  } else if (len > 2 ){
					  aux_fila.style.display = 'none';
					  aux_fila.cells[0].innerHTML = '';
				  }
			  aux_fila = aux_fila.nextSibling;		
		  }
}



function contraerDescontraer(){
 var cantidad = $$("objetotabla").rows.length;
 
 if ($$("estado").checked){
  tipo = "table-row";	 
 }else{
  tipo = "none";	 
 }
 var float = false;
   for(var i=0;i<cantidad;i++){
	  if ($$("objetotabla").rows[i].cells[0].innerHTML != "" && $$("objetotabla").rows[i].cells[0].innerHTML != "&nbsp;"){ 
	  id = 1000+i; 
	  vec_img = $$(id).src.split('/'); 
	  if(vec_img[vec_img.length-1] == 'arrowsabajo.png'){ 
	    float = true;
	  }
	  if (vec_img[vec_img.length-1] == 'arrowarriba.png'){ 
	    float = false;		  
	  }
	  }	  
	  
	  if ( float && $$("objetotabla").rows[i].cells[3].innerHTML.length > 13)
	   $$("objetotabla").rows[i].style.display = tipo;
	   
   }
	
}

var is = 0;

function recorrer(fila,idimagen){
		
	if (!tieneHijo(fila)){
	   	return false;
	} else {
		json = ultimo(fila.cells[3].innerHTML) ;
		ultimo_valor = json.val;
		dim = json.dim;
		vec = fila.cells[3].innerHTML.split('.') ;
		aux_fila = fila.nextSibling;
		if (aux_fila.style.display=='none')
			dis = '';
		else
			dis = 'none';

		  while ((ultimo_valor == getPosArray(vec, dim))){
			  try{
			    vec = aux_fila.cells[3].innerHTML.split('.');
			   } catch (e){
				  return;
			  }
			  aux_json = ultimo(aux_fila.cells[3].innerHTML);
			  if (ultimo_valor == getPosArray(vec, dim) && (aux_fila.style.display != dis ) ){
				     if (aux_json.dim > 4){
					     if (is == 1)  
						     aux_fila.style.display=dis;
						 else
					        aux_fila.style.display = 'none';
					 }else
					    aux_fila.style.display=dis;
			  }
			    aux_fila = aux_fila.nextSibling;	

		  }
	}
	   	hass = location.hash;
		idimg = idimagen;
       setTimeout("hass = location.hash; $$('haspost').value=hass",100);
}


function ultimoHijo(fila){
	
	if (!tieneHijo(fila)){
	   	return;
	} else {
		json = ultimo(fila.cells[3].innerHTML) ;
				
		ultimo_valor = json.val;
		
		dim = json.dim;
		vec = fila.cells[3].innerHTML.split('.') ;
		aux_fila = fila.nextSibling;
		
		  while ((ultimo_valor == getPosArray(vec, dim))){
			  vec = aux_fila.cells[3].innerHTML.split('.');
			  aux_json = ultimo(aux_fila.cells[3].innerHTML);
			  if (dim+1 == aux_json.dim)
			      ultimo_hijo = aux_fila;
			  vec = aux_fila.cells[3].innerHTML.split('.');
			  aux_fila = aux_fila.nextSibling;		
		  }		  		  
		return ultimo_hijo;
	}
}

function getPosArray(array, pos){
	return array[pos];
}

function ultimo(valor){
	aux = valor.split('.');
	return {val:aux[aux.length-2], dim:(aux.length-2)};
}


function tieneHijo(fila){
	vec = ultimo(fila.cells[3].innerHTML);
	nextFila = fila.nextSibling;
    next_valor = nextFila.cells[3].innerHTML.split('.');
    return (vec.val == next_valor[vec.dim]);
}

function contraer(fila){
	if (!tieneHijo(fila)){
       return;		
	} else {		
		for (var i=1;i<=cantHijos(fila);i++){
			hijo = obtenerHijo(fila,i);
		    hijo.style.display='none';
			contraer(hijo);  		
		}		
	}
}

function eliminarTransaccion(){
  location.href = "anular_plandecuenta.php?idplandecuenta="+$$("idDelete").value+"&has="+$$("idHas").value;
}

var openMensaje = function(codigo,has){
  var filtro ="codigo="+codigo+"&transaccion=consulta";
  consultar("plandecuentas/Dplancuentas.php",filtro,resultadoServer,codigo,has);
}

var resultadoServer = function(resultado,codigo,has){
	if (resultado == "no"){
	  openMsjEliminacion(codigo,has);
	}else{ 
	  openMsjAdvertencia(); 
	}
}

var openMsjAdvertencia = function(){
	$$("modal_tituloCabecera").innerHTML = 'Advertencia';
	$$("mb2").style.visibility = "hidden";
	$$("modal_contenido").innerHTML = 'No es posible eliminar la cuenta debido a que ya se realizaron movimientos con la misma.';
	$$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";
}

var openMsjEliminacion = function(codigo,has){
	$$("idDelete").value = codigo; 
	$$("idHas").value = has;
	$$("modal_tituloCabecera").innerHTML = 'Advertencia';
	$$("modal_contenido").innerHTML = '¿Desea anular este Plan de Cuentas?';
	$$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";  
}

var closeMensaje = function(){
	$$("modal_mensajes").style.visibility = "hidden";
    $$("overlay").style.visibility = "hidden";    
  }

var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
}

function consultar(servidor,filtro,funcion,codigo,has){
 var  pedido = ajax();	
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText; 
    	  funcion(resultado,codigo,has);
	   }	   
   }
   pedido.send(null);
 }


var openVentanaImprimir = function(){
    $$('overlay').style.visibility = "visible";
    $$('modal_vendido').style.visibility = "visible"; 
}

function accionPostRegistro(){
   window.open('plandecuentas/imprimir_plandecuentas.php?logo='+$$("logo").checked,'target:_blank');	
}

function cerrarPagina(){
    $$('overlay').style.visibility = "hidden";
    $$('modal_vendido').style.visibility = "hidden"; 
}
