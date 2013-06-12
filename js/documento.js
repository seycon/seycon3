// JavaScript Document



var id = 1;
var cont = 1;
var Conexion = false;
var Palabra = "";
var usuarios = 0;

  function Conectar()
  {
	  if(window.XMLHttpRequest){
		  Conexion=new XMLHttpRequest(); //mozilla
	  }
	  else if(window.ActiveXObject){
		  Conexion=new ActiveXObject("Microsoft.XMLHTTP"); //microsoft
	  }
  }
  

function selectUser(idc,nombre){
	cadena = $e('compartira').value;
	fic = cadena.split(', ');
	ini = fic[fic.length-1].length;
    cadena = (cadena).substring(0, (cadena.length-ini));
	$e('compartira').value = cadena;
	$e('compartira').value += nombre+', ';
	$e('cliente').innerHTML = '';
	$e('compartira').focus();
	usuarios++;
	$e('cantidadusuarios').value = usuarios;
	$e('idusuarios').value += idc+'-';  
	

}


////////////////////////////  DEVUELVE EL NOMBRE DEL INPUT FILE ////////////////////////////////////

function nombreArchivo(id) {

  fic = $e(id).value	
  fic = fic.split('\\');
  //$e('idadjuntos').innerHTML += "<br><a href='#' >"+fic[fic.length-1]+"</a>";
  return fic;
}

function r(id){
	$e('t'+id).style.display = 'none';
}

//////////////////////////////////////////////////////////////////////////////////////////////////////







////////////////////////////////  AÑADE EL NOMBRE DEL ARCHIVO ////////////////////////////////////

function incluir(files){
	$e('idadjuntos').innerHTML += "<a href='#' >"+nombreArchivo(files)+"</a><br>";
}


function crearfile(){

	$e('idadjunto'+id).innerHTML = "<br><input type='file' id =t"+id+" name=t"+id+" onchange='incluir(this.id);' />";
	$e('t'+id).click();
	nombre = $e('t'+id).value;
	//setTimeout("nombreArchivo(id)", 5000);
	$e('cantidadarchivos').value = id;
	id++;
    
}

//////////////////////////////////////////////////////////////////////////////////////////////





function $e(id){
	return document.getElementById(id);
}




function Contenido(idContenido){
	  if(Conexion.readyState!=4) return;
	     if(Conexion.status==200) {
		   if(Conexion.responseText){
			  $e(idContenido).style.display="block";
			  $e(idContenido).innerHTML = Conexion.responseText;
			  donde = $e('cliente');
			  cont = 0;
			  donde.getElementsByTagName('a')[cont].style.backgroundColor = '#CF0';
		  }else
			  $e(idContenido).style.display="none";
	  }else{
		  $e(idContenido).innerHTML=Conexion.status+"-"+Conexion.statusText;
	  }
	  $e("reloj").style.visibility="hidden";
	  Conexion=false;
  }
  
  function Solicitud(idContenido,Cadena){
	  if(Cadena && Cadena!=Palabra){
		 //if(Conexion) return; // Previene uso repetido del boton.
		   Conectar();
		   if(Conexion){
			  $e("reloj").style.visibility="visible";
			  Palabra=Cadena;
			  Conexion.open("POST",'php/usuarios.php',true);
			  Conexion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			  Conexion.onreadystatechange=function(){
		      Contenido(idContenido);
			  }
			  Conexion.send("idContenido="+idContenido+"&word="+Cadena);
		  }else
			  $e(idContenido).innerHTML="No disponible";
	  }
  }
  
  
  function autocompletar(idContenido,Cadena,e){
	  fic = Cadena.split(', ');
	  Cadena = fic[fic.length-1];
	  if (subeybaja(e,idContenido) == 1){
	      return false;
		  return;
	  } 
	  if(Cadena.length>=2){
		 if(Conexion!=false){
			$e("reloj").style.visibility="hidden";
			Conexion.abort();
			Conexion=false;
		  }
		  Solicitud(idContenido,Cadena);
	  }else
		  $e(idContenido).style.display="none";
  }
  
  
  
  
  function subeybaja(e, div){
	  tecla = (document.all) ? event.keyCode : e.which; 
      donde = $e(div);
      maxi = donde.getElementsByTagName('a').length -1;
      

	   
      if (cont<0) 
	      cont=maxi;
      if(cont>maxi) 
	     cont=0;

      if(tecla==40 & cont < maxi){
		  donde.getElementsByTagName('a')[cont].style.backgroundColor = '#EEE';
          cont++;
          donde.getElementsByTagName('a')[cont].style.backgroundColor = '#CF0';   
		  return 1;
      }  //   abajo
	  
      if(tecla==38 & cont > 0){
          donde.getElementsByTagName('a')[cont].style.backgroundColor = '#EEE';
          cont--;
          donde.getElementsByTagName('a')[cont].style.backgroundColor = '#CF0';
          return 1;
      }  //   abajo

      }
  
  