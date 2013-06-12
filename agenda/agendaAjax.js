// JavaScript Document

function $e(id){
	return document.getElementById(id);
}


function ajaxx() {
	if (window.XMLHttpRequest) { // si es firefox
		return new XMLHttpRequest(); // objeto q se ocupa de la conexion
	} else if (window.ActiveXObject) { // si es internet explorer
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
}


function verEventoDia(pagina, fecha, iddiv) {
  peticion = ajaxx();
  peticion.open('GET', pagina+'?fecha='+fecha, true); 
  peticion.onreadystatechange = function() { 	
     if (peticion.readyState == 4) { 
		 $e(iddiv).innerHTML = peticion.responseText; 	
	 }
  } 
  peticion.send(null); 
}



function verModal(){
	$e('overlay').style.visibility = 'visible';
	$e('modal').style.visibility = 'visible';
}




//muestra La Fecha
var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var dias= new Array("Domingo ","Lunes ","Martes ","Miercoles ","Jueves ","Viernes ","Sabado ");
var f=new Date();

function cargarFechaActual(){
$e("numFecha").innerHTML=f.getDate();
$e("textDia").innerHTML=dias[f.getDay()];
$e("mesA").innerHTML=meses[f.getMonth()] + " de " + f.getFullYear();
}

var prueba=new Date(90,2,19);
function cargarFechaEspecif(varDate){
$e("numFecha").innerHTML=varDate.getDate();
$e("textDia").innerHTML=dias[varDate.getDay()];
$e("mesA").innerHTML=meses[varDate.getMonth()] + " de " + varDate.getFullYear();
}
//Muestra La Hora

function mueveReloj(){ 
   	momentoActual = new Date() ;
   	hora = momentoActual.getHours() ;
   	minuto = momentoActual.getMinutes() ;
   	segundo = momentoActual.getSeconds() ;

   	horaImprimible = hora + " : " + minuto + " : " + segundo ;

   	$e('reloj').value = horaImprimible ;

   	setTimeout("mueveReloj()",1000) ;
} 


function eliminarEvento(id, iddiv){
  if (!confirm('Desea eliminar este registro?')) return;
  peticion = ajaxx();
  peticion.open('GET', 'ver_evento.php?id='+id, true); 
  peticion.onreadystatechange = function() { 	
     if (peticion.readyState == 4) { 
		 $e(iddiv).innerHTML = peticion.responseText; 	
	 }
  } 
  peticion.send(null); 

	
}





