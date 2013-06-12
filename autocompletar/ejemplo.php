<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <script src="funciones.js"></script>
        <link href="estilo1.css" rel="stylesheet" type="text/css" />
     
      <script>
	  
	  var eventoResultado = function(resultado,codigo){
     	 document.getElementById("dato").value =resultado;
	     document.getElementById("codR").value = codigo;		
      }
	  
	  var eventoResul = function(resultado,codigo){
     	 document.getElementById("dato2").value =resultado;
	     document.getElementById("codR1").value = codigo;		
      }
	  
	  var ejecutar = function(e,id){
	var consulta = "select t.idtrabajador,nombre from trabajador t where "; 
   eventoTeclas(e,id,'resultados2','trabajador','nombre','idtrabajador','eventoResul','consultor.php',consulta,'','autoLoading');
  } 
	  
	  </script>


    </head>

    <body>

    <h2><strong>Ejemplo de uso de Completer 1.1</strong></h2>

    <strong>INGRESE PARTE DEL FILTRO 1</strong><br>
    <!-- Evento teclas Parametros
    (event) : es por defecto
    (this.id):es por defecto
    (resultados): Es el id del div donde se mostrara el resultado 
    (trabajador): Nombre de la tabla de BD que se consultara
    (nombre): es el campo 2 que se obtendra de la Tabla de la BD
    (nombrenit):es el campo 2 que se obtendra de la Tabla de la BD 
    (eventoResultado): es el metodo que realizara una ves el servidor
    le devuelva el resultado de su consulta
    (consultor.php):Direccion del consultor que esta dentro de la carpeta autocompletar
    -->
    
    <input type="text"  id="dato" onKeyUp="eventoTeclas(event,this.id,'resultados',
'cliente','nombre','nombrenit','eventoResultado','consultor.php')"/><div id="autoLoading" class="autoLoading"></div><br>
     <div  id="resultados"  class="divresultado" ></div>
      
       <strong>Aqui se Guardara el Codigo (F1):</strong><br>
       <input name='prueba' id='codR' type='text' value=''>
    <br> 
    <br>
    <br>
    <strong>INGRESE PARTE DEL FILTRO 2</strong><br> 
    <?php
	$sql = "select idtrabajador,nombre from trabajador where";
	?>
    
    
    <input type="text"  id="dato2" onKeyUp="ejecutar(event,this.id)"/>
        <div  id="resultados2"  class="divresultado" >
        </div>
    
    <br>
    <strong>Aqui se Guardara el Codigo (F2):</strong> <br>        
    <input name='prueba' id='codR1' type='text' value=''>
    <br>
    <br>
    <br>
    <h3><strong>Author : Rodrigo Farell</strong></h3>
    

    </body>
</html>
