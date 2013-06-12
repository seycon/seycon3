// JavaScript Document

	
	var abrirSession = function(cliente, guardias)
	{
		return "<div class='conttipocliente_5'>"           
				 +  "<div class='tipocliente_5'>Cliente: <span style='color:#CCC'>" + cliente + "</span></div> "
				 +  "<div class='nrotrabajadores_5'>Guardias: "+ guardias +"</div>";	
	}
	
	var cerrar = function()
	{
		return "</div>";	
	}
	
	var abrirFila = function()
	{
		return "<div class='contenedorPersonal'>";
	}
	
	var cardex = function(codigo, src, nombre)
	{
		return " <div class='cardex' onclick='getDatosPersonal("+codigo+");'> " 
				+ "  <div class='franjacardex'>"
				+ "     <div class='nropersonal'>Nº "+ codigo +"</div>"
				+ "  </div>"
				+ "  <div class='fotopersonal'>"
				+ "    <img src='"+src+"' width='90' height='80' />"
				+ "  </div>"
				+ "  <div class='textopersonal'>N.</div>"
				+ "  <div class='namepersonal'>"+nombre+"</div>"
				+ "</div> ";	
	}
	
	
	var cargarDatos = function(datos)
	{
	  var cadena = "";
	  var nroCliente = "";  
	  var cantPersonal = 0;	
	  for (var i = 0; i < datos.length; i++){
		  if (nroCliente != datos[i][4]) {
			 if (i > 0) {
				cadena = cadena + cerrar() + cerrar();  
			 }
			 cadena = cadena + abrirSession(datos[i][3], datos[i][5]) + abrirFila();
			 nroCliente = datos[i][4];
			 cantPersonal = 0;
		  }
		  if (cantPersonal == 7 && nroCliente == datos[i][4]) {
			 cadena = cadena + cerrar() + abrirFila(); 
			 cantPersonal = 0;
		  }
		  
		  cadena = cadena + cardex(datos[i][0],datos[i][2],datos[i][1]);
		  cantPersonal++;	  
	  }
	  cadena = cadena + cerrar() + cerrar();
	  $$("panel").innerHTML = cadena;	
	  $$("overlay").style.display = "none"; 
	  $$("gif").style.display = "none"; 
	}
