// JavaScript Document
 
  var activado = 0;
  var anterior = 1;
  var id_amigo = 0; 
  setInterval("cargarMsjs('ajax.php', 'POST', 'conec', 'cont_chat')", 5000);
 
function $(id){
	return document.getElementById(id);
}

function $e(id){
	return document.getElementById(id).style;
}

function $v(id){
	return document.getElementById(id).value;
}
  
function Cerrar(div){
  $e(div+'c').display = 'none';
  $e(div).visibility = 'hidden';
}


function crearElement(padreId, idElement, tipoElement, clase){
  divPadre = $(padreId);	
  divChat = document.createElement(tipoElement);	
  divChat.id = idElement;
  divChat.className = clase;
  idElement1 = idElement.toString();
  if (idElement1.slice(idElement1.length-2) == 'ci'){
	  id = idElement1.substring(0,idElement1.length-2);
      divChat.innerHTML='<input type="text" id="'+id+'t" onkeypress="enviarPriv('+id+', '+id+', this.value)" class="input_priv" />';
  }
  divPadre.appendChild(divChat);
  
}


function crearHijoDePuta(padreId, idElement, tipoElement, clase){
  divPadre = $(padreId);	
  divChat = document.createElement(tipoElement);	
  divChat.id = idElement;
  divChat.className = clase;
  idE = idElement;
  try {
  idE = idE.slice(0, idE.length-1);
  nombre_user = $(idE.toString()+'lin').innerHTML; 
  divChat.innerHTML = '<a href="#" onclick="mostrarDiv('+idE+')">'+nombre_user+'</a><img src="iconos/cerrar.jpg" onclick="Cerrar('+idE+')" class="cerrar"  />'; 
  } catch(e){
	 alert('aki de nuevo');  
  }
  divPadre.appendChild(divChat);
}


function mostrarDiv(id_amigo_div){
    $e(id_amigo_div+'c').backgroundColor = '#00FF66';
	var div_chat = $(id_amigo_div);
	l =  $(id_amigo_div+'c').offsetLeft;
	t =  $(id_amigo_div+'c').offsetTop;
	if (t > 0){
	  div_chat.style.top = 58;
	  $e('contpriv').height = 58; 
	} else {
	  div_chat.style.top = 27;
	}
	 if (anterior == id_amigo_div ){
			   if (div_chat.style.visibility == 'visible'){
				  div_chat.style.visibility = 'hidden';
				}  
				else {
				  div_chat.style.visibility = 'visible';  
				  $(id_amigo_div+'t').focus();
				  div_chat.style.left = l-103;
				}
	 } else {
		 div_chat.style.visibility = 'visible';
		 $(id_amigo_div+'t').focus();
		  try {
		  $e(anterior).visibility = 'hidden';
		  } catch(e) {
		  }
		  div_chat.style.left = l-103;
		//  div_chat.style.top = 30;
	 }	
	 
   anterior = id_amigo_div;	
}


function mostrarDivU(div_u){
  if (!$(div_u)){
	  crearElement('contpriv', div_u, 'div', 'div_msj');
	  crearElement(div_u, div_u+'msj_priv', 'div', 'msj_priv');
	  crearElement(div_u, div_u+'ci', 'div', 'div_inp_chat');
	  crearHijoDePuta('contpriv', div_u+'c', 'div', 'div_u');
  }
  $e(div_u+'c').display = 'block';
  mostrarDiv(div_u);
}


function crear(msj, e){
  if (window.event) { 
    e = window.event; 
  }
   if (e.keyCode == 13 || e.keyCode == 121) {
     alert('hola');
   }

}


  // *************** AJAX() ************************
  
  
  function Send(){
	if ($('sala_send').value != ''){
		   ajaxSincrono('GET', 'salas.php?msj='+$('sala_send').value+'&insertar=si');
		   $('sala_send').value = '';
		   $('sala_send').focus();
	}  
	  
  }

  function ksend(div_sala){
	    if ($('sala_send').value != ''){
			if (window.event) { 
               e = window.event; 
            }
              if (e.keyCode == 13) {
			     ajaxSincrono('GET', 'salas.php?msj='+$('sala_send').value+'&insertar=si');
			     $('sala_send').value = '';
				 return false;
			}
        }   
  }
  
  
  
    function enviarPriv(id_user, id_input, inp_value){
		
	    if ( inp_value != ''){
			if (event.keyCode == 13){
			  ajaxSincrono('GET', 'insertPriv.php?id_user='+id_user+'&msj='+inp_value+'&privORsala=priv');
			  $(id_user+'msj_priv').innerHTML += inp_value+'<br>';
			  $(id_input+'t').value = '';
			}
        }   
  }
  
  
//  function enviarSala(sala, msj)
  
  

function ajaxx() {
	if (window.XMLHttpRequest) { // si es firefox
		return new XMLHttpRequest(); // objeto q se ocupa de la conexion
	} else if (window.ActiveXObject) { // si es internet explorer
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
}

function cargarmensaje(getORpost, div_chat, pagina_insert) {
  peticion = ajaxx();   
  peticion.open(getORpost, pagina+'?div_chat='+div_chat.innerHTML, true); 
  peticion.onreadystatechange = function() { 	
     if (peticion.readyState == 4) { 
		   if (peticion.responseText != ''){
		      div_chat.innerHTML = div_chat.innerHTML + peticion.responseText; 	
		   }
     } 
  } 
  peticion.send(null); 
}


function ajaxSincrono(getORpost, pagina){
	 // Creamos la variable parametro
  peticion_sin = ajaxx();
 // Preparamos la petición con parametros
 peticion_sin.open(getORpost, pagina, false);
  //Realizamos la petición
 peticion_sin.send(null);
 // Devolvemos el resultado
 //return oXML.responseText;

}

function insertar(getORpost, pagina, id_boton, texto){
    peticion_insert = ajaxx();
	var caption = $(id_boton).value;
	peticion_insert.open(getORpost, pagina+'?texto='+texto, true);
	peticion_insert.onreadystatechange = function() {
	  if (peticion_insert.readyState == 4){
		  $(id_boton).value = caption;
		  $(id_boton).disabled = 'enabled';
	  }
	  else {
		  $(id_boton).value = caption;
		  $(id_boton).disabled  = 'disabled';
	  }
	}
	peticion_insert.send(null);
}

function informacion(getORpost, id_div, pagina, id_user) {
  div = $(id_div);
  chat = $e('cont_chat');
  chat.display = 'none';
  peticion = ajaxx();   
  peticion.open(getORpost, pagina+'?id_user='+id_user, true); 
  peticion.onreadystatechange = function() { 	
     if (peticion.readyState == 4) { 
		   if (peticion.responseText != ''){
		      div.innerHTML += peticion.responseText; 	
		   }
     } 
  } 
  peticion.send(null); 
}

/*
function cargarmensaje(getORpost, div_chat, pagina) {
  peticion = ajaxx();   
  peticion.open(getORpost, pagina+'?div_chat='+div_chat.innerHTML, true); 
  peticion.onreadystatechange = function() { 	
     if (peticion.readyState == 4) { 
		   if (peticion.responseText != ''){
		      div_chat.innerHTML = div_chat.innerHTML + peticion.responseText; 	
		   }
     } 
  } 
  peticion.send(null); 
}



function cargarmensaje_priv(getORpost, pagina) {	
  peticion = ajaxx();  
  peticion.open(getORpost, pagina, true);
  peticion.onreadystatechange = function() { 	
     if (peticion.readyState == 4) { 
		   if (peticion.responseText != ''){
			    msj = peticion.responseText;
				msj = msj.split('-');
				var i = 0;
				while (i<msj.length-1){
					 if (!document.getElementById(msj[i])){
						crearElement('contpriv', msj[i], 'div', 'div_msj');
						crearElement(msj[i], msj[i]+'ci', 'div', 'div_inp_chat');
						alert('noooo');
						alert(peticion.responseText);
						crearHijoDePuta('contpriv', msj[i]+'c', 'div', 'div_u'); 
					 }
					 document.getElementById(msj[i]).innerHTML += msj[i+1]+'<br>';
					 document.getElementById(msj[i]+'c').style.display = 'block';
					 document.getElementById(msj[i]+'c').style.backgroundColor = 'red';
					 i = i + 2;
			   }
		   }
     } 
  } 
  peticion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  peticion.send("div_privado="+encodeURI(document.getElementById('contpriv').innerHTML)); 
}
*/


function cargarPriv(msj){
	msj = msj.split('c1o1n1e1c4');
	var i = 0;
	while (i<msj.length-1){
		 if (!$(msj[i])){
			crearElement('contpriv', msj[i], 'div', 'div_msj');
			crearElement(msj[i], msj[i]+'msj_priv', 'div', 'msj_priv');
			crearElement(msj[i], msj[i]+'ci', 'div', 'div_inp_chat');
			crearHijoDePuta('contpriv', msj[i]+'c', 'div', 'div_u'); 
		 }
		 $(msj[i]+'msj_priv').innerHTML += msj[i+1]+'<br>';
		 $e(msj[i]+'c').display = 'block';
		 $e(msj[i]+'c').backgroundColor = 'red';
		 i = i + 2;
   }
}



function cargarMsjs(pagina, getORpost, div_conec, div_sala){
  peticion = ajaxx(); 
  div_conec = $(div_conec);
  div_sala = $(div_sala);
  //div_priv = document.getElementById(div_priv);
  peticion.open(getORpost, pagina+'?div_chat='+div_sala.innerHTML, true);
  peticion.onreadystatechange = function() { 	
     if (peticion.readyState == 4) { 
		   if (peticion.responseText != ''){	
		       HTML = peticion.responseText.split('d1a1n1i1l1o');
			     for(var i = 0; i < HTML.length; i++) {
				   switch (HTML[i].substring(0, 10)){
					 case "c1o1n1e1c1":
							div_conec.innerHTML = HTML[i].replace('c1o1n1e1c1', ""); 
					 break;
					 case "c1o1n1e1c2":
							div_sala.innerHTML += HTML[i].replace('c1o1n1e1c2', "");
							div_sala.scrollTop += 1000;
					 break;
					 case "c1o1n1e1c3":
							cargarPriv(HTML[i].replace('c1o1n1e1c3', ""));
					 break;
				   }
				 }
		   }
	 }
 }
  peticion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  peticion.send("div_privado="+encodeURI($('contpriv').innerHTML));		   
}


