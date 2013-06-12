<?php
    session_start();
    include("../conexion.php");
    $db = new MySQL();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script>

 var imprimirPedido = function() {
	if (datosValidos()) {		 
  		$$('overlay').style.visibility = "visible";
        $$('gif').style.visibility = "visible"; 
	    var filtro = "transaccion=reporte1&nroatencion="+ $$("pedido").value +"&idatencion=" + $$("venta").value; 
	    enviar("Dreporte.php", filtro, setImpresionPedido); 
	}
 }
 


 var datosValidos = function() {
	var bandera = true; 
	$$("msjventa").style.display = "none";
	$$("msjpedido").style.display = "none";	
	if ($$("venta").value == "") {
		bandera = false;
		$$("msjventa").style.display = "block"; 
		$$("msjventa").innerHTML = "Este campo es requerido.";
	}
	
	if ($$("pedido").value == "") {
		bandera = false;
		$$("msjpedido").style.display = "block"; 
		$$("msjpedido").innerHTML = "Este campo es requerido.";
	}
	
	if (!isvalidoNumero("venta")) {
		bandera = false;
		$$("msjventa").style.display = "block"; 
		$$("msjventa").innerHTML = "Numero Invalido.";
	}
	
	if (!isvalidoNumero("pedido")) {
		bandera = false;
		$$("pedido").style.display = "block"; 
		$$("pedido").innerHTML = "Numero Invalido.";
	}
	
	return bandera;
 }


    var jumpImpresion = function(){
	 location.href = "nuevo_impresion.php";	
	}

 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
 }

 var setImpresionPedido = function(resultado) {

      var datos = eval(resultado);
	  
	  if (datos.length > 1) {
		var cadena = setCabecera(datos[0][0], datos[0][1], datos[0][4]);  
		var total = 0;
		 for (var i=1; i<datos.length; i++) {
			total = total + datos[i][3]; 
			cadena = cadena + setContenido( datos[i][0], datos[i][1], datos[i][2], datos[i][3]);
	     }
		cadena = cadena + setTotal(total);
		cadena = cadena + setEspacios(4) + " C O P I A   I M P R E S I O N \n \n";
		cadena = cadena + setFirma(datos[0][2], datos[0][3]) + " \n ";
		$$('overlay').style.visibility = "hidden";
        $$('gif').style.visibility = "hidden"; 
		printR1(cadena, jumpImpresion);	
	 } else {
		$$('gif').style.visibility = "hidden";  
		$$('modal_mensajes').style.visibility = "visible";
		$$("modal_contenido").innerHTML = "No existen datos para imprimir, verifique su Nº Venta y el Nº Pedido.";   
		$$("btAccion").style.visibility = "hidden";
	 }
	  
 }
   
</script>
<script src="Npedido.js"></script>
<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="js/html2canvas.js"></script>
<script type="text/javascript" src="js/jquery.plugin.html2canvas.js"></script>

</head>

<body>
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="./jzebra.jar" width="0px" height="0px">
      <param name="printer" value="zebra">
</applet><br />


<div id="overlay" class="overlays"></div>
<div id="gif" class="gifLoader"></div>
<div id="modal_mensajes" class="modal_mensajes">
  <div class="modal_cabecera">
     <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
     <div class="modal_cerrar">
     <img src="../iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
  </div>
  <div class="modal_icono_modal"><img src="../iconos/alerta.png" width="24" height="24"></div>
  <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
  <div id="btAccion" class="modal_boton2">
  <input type="button" value="Aceptar" class="boton_modal" onclick="setPedidoAnulado()"/></div>
  <div class="modal_boton1"><input type="button" value="Cancelar" class="boton_modal" onclick="closeMensaje()"/></div>
</div>




 <div class="contendedor">  
   <div class="tela_izq"></div>
   <div class="tela_cierreizq"></div>
   <div class="tela_der"></div>
   <div class="tela_cierreder"></div> 
   <div class="derechosReservados">Copyright © Consultora Guez – Diseñado y Desarrollado
   </div>
   <div class="header"><div class="gradient7"><h1><span></span>Scav</h1></div>  </div>
   <div class="subTitulo">Software Contable de Administración y Ventas.</div>
   
   <table width="90%" border="0" align="center">
  <tr>
    <td width="21%">&nbsp;</td>
    <td width="79%"></td>
  </tr>
  <tr>
    <td width="21%">
    <div class="menu1">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="36%">&nbsp;</td>
    <td width="64%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><div class="tituloMenu"><< <?php echo ucfirst($_SESSION['sucursalrestaaurante']); ?> >></div></td>
    </tr>
  <tr>
    <td height="336" colspan="2">
    <div class="contenedorMenu">
     <div id="opcion1" onclick="location.href = 'nuevo_atencion.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href = 'nuevo_impresion.php'"><div class="sombraButon">
     </div><div id="textoOpcion">Imprimir</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href = 'nuevo_cambiarclave.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Cambiar Contraseña</div></div>
    </div>
    </td>
    </tr>
  <tr>
    <td height="21" align="center" class="letra" colspan="2"></td>
  </tr>
</table>
    <div class="contenedorUser"><div class="imgUser"></div><div class="nombreUser"><?php echo $_SESSION['nombretrestaurante'];?></div></div>
    
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion">
         <div class="textoTituloTransaccion">Imprimir Comanda</div></div>
          <div class="separador"></div>
            </br>
            
       <form id="formulario" name="formulario" method="post" action="">     
       <table width="92%" border="0" align="center">
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td >Los campos con <span class="rojo">(*)</span> son requeridos.</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td class="letra">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right" class="letra">Nº Venta:<span class="rojo">*</span></td>
    <td><input type="text" name="venta" id="venta" class="required" minlength="6"/>
    <div id="msjventa" class="msjError">Este campo es requerido.</div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right" class="letra">Pedido:<span class="rojo">*</span></td>
    <td><input type="text" name="pedido" id="pedido" class="required" minlength="6"/>
    <div id="msjpedido" class="msjError">Este campo es requerido.</div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td width="2%"></td>
    <td width="2%"></td>
    <td width="31%" align="right"></td>
    <td width="37%">&nbsp;</td>
    <td width="15%"><input type="button" value="Imprimir" id="botonrestaurante" onclick="imprimirPedido()"/></td>
    <td width="13%"><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
  </tr>
</table>

</form>
 <div class="contenedorCerrar"><div class="imagenCerrar"></div><div id="textoCerrar" onclick="location.href='cerrar.php'">Cerrar</div></div>
      </div>
    </td>
  </tr>
</table>

   
 </div>
</body>
</html>