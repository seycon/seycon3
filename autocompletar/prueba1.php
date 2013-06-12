
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
		 var anterior = anteriorCadena("dato2"); 
     	 document.getElementById("dato2").value = anterior + resultado + ", ";
	     document.getElementById("codR1").value = codigo;		
      }
	  
	  
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
		 var consulta = "(select idcliente as 'id',concat(nombre,' [',email,']')as 'email' from cliente where email!='' and estado=1 and email like '"+ filtracion +
		 "%') union (select idproveedor as 'id',concat(nombre,'  [',email,']') as 'email' from proveedor where email!='' and estado=1 and email like '"+ filtracion+"%');"; 
	     eventoTeclas(e,id,'resultados2','email','email','id','eventoResul','consultor.php',consulta,'<sinfiltro>');
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
'cliente','nombre','nombrenit','eventoResultado','consultor.php')"/><br>
     <div  id="resultados"  class="divresultado" ></div>
      
       <strong>Aqui se Guardara el Codigo (F1):</strong><br>
       <input name='prueba' id='codR' type='text' value=''>
    <br> 
    <br>
    <br>
    <strong>INGRESE PARTE DEL FILTRO 2</strong><br> 
    <?php
	$sql = "select idtrabajador,concat(nombre,apellido)as nombre from trabajador where ";
	?>
    
    
    <input type="text"  id="dato2" onKeyUp="ejecutar(event,this.id)" size="80"/>
        <div  id="resultados2"  class="divresultado" style="width:350px;">
        </div>
    
    <br>
    <strong>Aqui se Guardara el Codigo (F2):</strong> <br>        
    <input name='codR1' id='codR1' type='text' value='marco ,rodrigo '>
    <br>
    <br>
    <br>
    <h3><strong>Author : Rodrigo Farell</strong></h3>
    <p>&nbsp;</p>
    <p><input type="button" onClick="obtenerCadena(codR1)" value="mostrar"></p>
    

    </body>
</html>
