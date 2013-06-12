<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script language="javascript">

  function imprSelec(nombre)

  {
  
  var ficha = document.getElementById(nombre);

  var ventimp = window.open(' ', 'popimpr');

  ventimp.document.write( ficha.innerHTML );

  ventimp.document.close();

  ventimp.print( );

  ventimp.close();

  } 

</script> 
<link rel="stylesheet" href="../calendario_multiple/jquery.datepick.css" type="text/css"/>
<script src="../calendario_multiple/jquery.min.js"></script>
<script src="../calendario_multiple/jquery.datepick.js"></script>



<script type="text/jscript">
$(document).ready(function()
{
	

$('#fecha').datepick({ multiSelect: 999, monthsToShow: 2, showTrigger: '#calImg',dateFormat: 'mm-dd-yyyy',
onSelect: function(dates) { 
 document.getElementById("valores").value = "";
 for (var i=0;i<dates.length;i++) {
   document.getElementById("valores").value = document.getElementById("valores").value + $.datepick.formatDate(dates[i]) + ",";
 }
}

});
});


var $$ = function(id) {
 return document.getElementById(id);	
}

function obtenerFechas() {
   var datos = $$("valores").value.split(",");
   for (var i=0;i<datos.length-1;i++) {
	 var fecha = (datos[i].split("/")); 
	 var formato = fecha[1]+"/"+fecha[0]+ "/" +fecha[2];
	alert(formato);   
   }
}
	
	 
</script>
</head>

<body>

<div id="fecha">

</div>

<input type="text" id="valores" value="" size="40"/>
   <input type="button" value="Buscar" onclick="obtenerFechas()"/>
</body>
</html>