// JavaScript Document


	  var posicionComa = function(id){
		var cadena = $$(id).value; 		  
	    return cadena.lastIndexOf(",");	  
	  }
	  
	  var anteriorCadena = function(id){
		var cadena = $$(id).value;	  
	    var pos = posicionComa(id);	  
	    return cadena.substring(0,pos+1);	   
	  }
	  
	  var obtenerCadena = function(id){
	    var cadena = $$(id).value;	  
	    var pos = posicionComa(id);	  
	    cadena = cadena.substring(pos+1,cadena.length);	
		 var cad = new String (cadena);  
		 return cad.trim();
	  }
	  
	  String.prototype.trim = function(){ 
        return this.replace(/^\s+|\s+$/g,'') 
      }
	  
	  var ejecutar = function(e,id){
   	     var filtracion = obtenerCadena(id);
		 var consulta = "(select idcliente as 'id' "
		 + " ,concat_WS('',left(nombre,15),' [',emailcorporativo,']')as 'email' "
		 + " from cliente where emailcorporativo!='' and estado=1 and emailcorporativo like '"+ filtracion +"%')"
		 + " union (select idproveedor as 'id',concat_WS('',left(nombre,15),'  [',email,']') as 'email' "
		 + " from proveedor where email!='' and estado=1 and email like '"
		 +  filtracion+"%') union "
		 + "(select idtrabajador as 'id',concat_WS('',left(nombre,15),' [',emailcorporativo,']')as 'email'"
		 + " from trabajador where emailcorporativo!='' and emailcorporativo like '"+filtracion+"%' and estado=1) limit 9;"; 
	     eventoTeclas(e,id,'resultados2','email','email','id','eventoResultado','autocompletar/consultor.php',consulta,'<sinfiltro>');
      }
	  
	  var eventoResultado = function(resultado,codigo){
		 var anterior = anteriorCadena("para"); 
     	 document.getElementById("para").value = anterior + resultado + ", "; 
      }
	  
	 var numero = 1;

var addCampo = function () { 
   nDiv = document.createElement('div');
   nDiv.id = 'file' + (numero++);
   nCampo = document.createElement('input');
   nCampo.name = 'archivos[]';
   nCampo.type = 'file';
   nCampo.id = "IF"+ numero;
   nCampo.className = 'desplazar';
   a = document.createElement('a');
   a.name = nDiv.id;
   a.id = "IL"+ numero;
   a.href = '#';
   a.onclick = elimCamp;
   a.innerHTML = '';
   nCampo.onchange = cambio;
   nDiv.appendChild(nCampo);
   nDiv.appendChild(a);
   $$("adjuntos").appendChild(nDiv);
   $$(nCampo.id).click();
}

function cambio(value){
  if(this.value != "")
  $$("IL"+numero).innerHTML = "<img src='iconos/eraser.png' title='Eliminar' />"+this.value+"(Eliminar)";	
}

var evento = function (evt) { 
   return (!evt) ? event : evt;
}

var elimCamp = function (evt){
   evt = evento(evt);
   nCampo = rObj(evt);
   div = document.getElementById(nCampo.name);
   div.parentNode.removeChild(div);
}

var rObj = function (evt) { 
  return evt.srcElement ?  evt.srcElement : evt.target;
}

var $$ = function(id){
  return document.getElementById(id);	
}

var enviarDatos = function(){
  if (esValido()){	
    $$('overlay').style.visibility = "visible";
	$$('gif').style.visibility = "visible";
	$$("pasarDatos").type = "submit";
	$$("pasarDatos").click();
  }
}


function estadoCheck(id){
	if($$(id).checked){
	 $$("para").value = $$("para").value + $$(id).value;
	}else{
	eliminarLista($$(id).value);	
	}
}

function eliminarLista(dato){
	var lista="";
	var destinos = $$("para").value.split(",");
	for(var j=0;j<destinos.length-1;j++){
	  var cadena = new String (destinos[j]);	
		if (cadena.trim()+"," != dato)
		lista = lista + destinos[j]+",";
	}
	$$("para").value = lista;	
}

function openLista(){
   $$("modal").style.visibility = "visible";		
   $$("modalInterno").style.visibility = "visible";	
}

function accion(){
	 $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
	 $$("modalInterno").style.visibility = "hidden"; 
}

var esValido = function(){
	if ($$("para").value == ""){
		openMensaje("Advertencia","Debe ingresar el destino del mensaje");
		return false;
	}
	if ($$("asunto").value == ""){
		openMensaje("Advertencia","Debe ingresar el asunto del mensaje");
		return false;
	}
	if ($$("remitente").value == ""){
		openMensaje("Advertencia","Debe registrar su correo personal.");
		return false;
	}
	return true;
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