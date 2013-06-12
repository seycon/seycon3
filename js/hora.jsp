<script language="javascript" type="text/javascript">

var RelojID12 = null
var RelojEjecutandose12 = false

function DetenerReloj12 () {
	if(RelojEjecutandose12)
		clearTimeout(RelojID12)
	RelojEjecutandose12 = false
}


function obtenerHora(){

var date =new Date();
var hora = date.getHours();
var minuto = date.getMinutes();
var segundo = date.getSeconds();
var meridiano;
var ValorHora;

if (hora > 12) {
		hora -= 12
		meridiano = " P.M."
	} else {
		meridiano = " A.M."
    }
	
	if (hora < 10)
		ValorHora = "0" + hora
	else
		ValorHora = "" + hora

	//establece los minutos
	if (minuto < 10)
		ValorHora += ":0" + minuto
	else
		ValorHora += ":" + minuto
        	
	//establece los segundos
	if (segundo < 10)
		ValorHora += ":0" + segundo
	else
		ValorHora += ":" + segundo
        
	ValorHora += meridiano

 	document.getElementById("horainicio").value = ValorHora
	
	
}


function MostrarHora12 () {
	var ahora = new Date()
	var horas = ahora.getHours()
	var minutos = ahora.getMinutes()
	var segundos = ahora.getSeconds()
	var meridiano

	//ajusta las horas
	if (horas > 12) {
		horas -= 12
		meridiano = " P.M."
	} else {
		meridiano = " A.M."
    }
        	
   	//establece las horas
	if (horas < 10)
		ValorHora = "0" + horas
	else
		ValorHora = "" + horas

	//establece los minutos
	if (minutos < 10)
		ValorHora += ":0" + minutos
	else
		ValorHora += ":" + minutos
        	
	//establece los segundos
	if (segundos < 10)
		ValorHora += ":0" + segundos
	else
		ValorHora += ":" + segundos
        
	ValorHora += meridiano
 	document.getElementById("horacierre").value = ValorHora

	//si se desea tener el reloj en la barra de estado, reemplazar la anterior por esta
  	//window.status = ValorHora

   	RelojID12 = setTimeout("MostrarHora12()",1000)
  	RelojEjecutandose12 = true
}

function IniciarReloj12 () {
		obtenerHora()
   	DetenerReloj12()
  	MostrarHora12()

}

window.onload = IniciarReloj12;
if (document.captureEvents) {			//N4 requiere invocar la funcion captureEvents
	document.captureEvents(Event.LOAD)
}

</script>
