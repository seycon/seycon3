 var cargado; 
 var cantidadElementos = 0;
 var items;
 var textEndrada;
 var divResultado;
 var selec = 0;


//objeto Ajax
 var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
 }


 var $$ = function(id){
	return document.getElementById(id); 
 }
 

 //Evento del mouse sobre la lista
 var mouseSobreLista = function(idDiv,contenedor){
   var contenedores = $$(contenedor).getElementsByTagName("div");
   for(x = 0; x < contenedores.length; x++)
    {
     contenedores[x].style.background ="#333333";
    }
   $$(idDiv).style.background ="#202020";
 }



 //seleccion de un items
 var selecciono = function(idDiv,textResultado,divResult,texValor,funcion){
   var nom = idDiv+"HD"; 
   var idresultado = $$(nom).value;   
   eval(funcion+"('"+texValor+"','"+idresultado+"')");   
   $$(divResult).innerHTML = "";
   $$(divResult).style.visibility="hidden";
   cantidadElementos = 0; 
 }

 //se mueve la seleccion de los items de acuerdo a la seleccion
 var moverSeleccion = function(direccion){
    if (direccion=="abajo"){
        if (items+1<=cantidadElementos){
         items = items + 1;
         if (items-1>0)
            $$(divResultado+"AC"+(items-1)).style.background ="#333333";
         $$(divResultado+"AC"+(items)).style.background ="#202020";
        }
    }
    else{
        
       if (items-1>0){
        items = items - 1;
        if (items+1<=cantidadElementos)
            $$(divResultado+"AC"+(items+1)).style.background ="#333333";
         $$(divResultado+"AC"+(items)).style.background ="#202020";
       }       
    }
 }


var evt = null;


 var eventoTeclas = function(evento,idtext,iddivResultado,tablaBD,campoBD,idtabla,funcion,direccion){
	if (!evt){
		HTMLElement.prototype.click=function(){
		evt = this.ownerDocument.createEvent('MouseEvents');
		evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0,false, false, false, false, 0, null);
	   this.dispatchEvent(evt);
	   }
    }
	
	

	

 //realiza la peticion al servidor
 function peticion(tablaBD,campoBD,idtabla,funcion,direccion,sql,filtroExtra){
  var pedido = ajax();
  var loader = arguments[7]; 
  if ($$(textEndrada).value!=""){
  fil = (filtroExtra == "") ? $$(textEndrada).value : filtroExtra;  	
  filtro = "filtro="+fil
  +"&tabla="+tablaBD+"&campo="+campoBD+"&divResultado="+divResultado
  +"&textEntrada="+textEndrada+"&HDresult="+funcion+"&idtabla="+idtabla+"&sql="+encodeURIComponent(sql);  

   pedido.open("GET",direccion+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	if (pedido.readyState == 4){ 
	    if (loader != ""){    	
	     $$(loader).style.visibility = "hidden";
		}
        var resultado = pedido.responseText.split("---");
		cantidadElementos =resultado[0];
        items = 0;		
	    $$(divResultado).innerHTML = "";
		$$(divResultado).style.visibility="hidden";
        if (cantidadElementos>0){
         $$(divResultado).style.visibility="visible";
         $$(divResultado).innerHTML = resultado[1];
        }else{
		 // openMensaje("Advertencia",'No se encontro resultado en la busqueda.');	
		}
        
	}	
	//Carga el .gif del loading 
	 if (pedido.readyState==1 || pedido.readyState==2 || pedido.readyState==3){	    
	   if (loader != "")
        $$(loader).style.visibility = "visible";
     }   
   }
   pedido.send(null);
 }
 else{
   $$(divResultado).innerHTML = "";
   $$(divResultado).style.visibility="hidden";
   cantidadElementos = 0;
   if (loader != ""){    	
	     $$(loader).style.visibility = "hidden";
   }
 }
 }
 
 
 

 var tecla = (document.all) ? evento.keyCode : evento.which;
    textEndrada = idtext;
    divResultado = iddivResultado;

    switch (tecla) {
        case 38:
            moverSeleccion("arriba");
          break;
        case 40:
            moverSeleccion("abajo");
          break;
        case 13:
		var bt =$$(divResultado+"AC"+items);
		if (bt){
		   bt.click();
		   return 1;
		}
        break;
        default:
		  if (arguments.length >= 9){				  
		    var arg = (arguments.length == 11) ? arguments[10] : "";    
            peticion(tablaBD,campoBD,idtabla,funcion,direccion,arguments[8],arguments[9],arg);
		  }
		  else{
			if (arguments.length == 8)  
		    peticion(tablaBD,campoBD,idtabla,funcion,direccion,'','',arguments[8]);	
			else
			peticion(tablaBD,campoBD,idtabla,funcion,direccion,'','','');	
		  }
        break;
    }
 }
