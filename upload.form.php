<?php 

// filename: upload.form.php 

// first let's set some variables 

// make a note of the current working directory relative to root. 
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

// make a note of the location of the upload handler script 
$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload.processor.php'; 

// set a max file size for the html upload form 
$max_file_size = 1000000 // size in bytes 

// now echo the html page 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/molderhema.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Rhema en Acción - Jovenes viviendo la palabra</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.Estilo10 {color: #000000}
.Estilo3 {font-size: 20px}
.Estilo9 {color: #FF6600}
.Estilo13 {
	font-size: 14px;
	font-weight: bold;
}
.Estilo14 {font-size: 14px}
-->
</style>
<!-- InstanceEndEditable -->
<style type="text/css"> 
<!-- 
body  {
	font: 100% Verdana, Arial, Helvetica, sans-serif;
	background: #666666;
	margin: 0; /* es recomendable ajustar a cero el margen y el relleno del elemento body para lograr la compatibilidad con la configuración predeterminada de los diversos navegadores */
	padding: 0;
	text-align: center; /* esto centra el contenedor en los navegadores IE 5*. El texto se ajusta posteriormente con el valor predeterminado de alineación a la izquierda en el selector #container */
	color: #000000;
	background-color: #039;
	background-image: url(fondo.jpg);
}
.thrColLiqHdr #container {
	background: #FFFFFF;
	margin: 0 auto; /* los márgenes automáticos (conjuntamente con un ancho) centran la página */
	border: 0px solid #000000;
	text-align: left; /* esto anula text-align: center en el elemento body. */
	min-width: 1005px;
	width: 1010px;
	font-size: 16px;
} 
.thrColLiqHdr #header {
	background: #ffffff;
	padding: 2px;  /* este relleno coincide con la alineación izquierda de los elementos de los divs que aparecen bajo él. Si se utiliza una imagen en el #header en lugar de texto, es posible que le interese quitar el relleno. */
} 
.thrColLiqHdr #header h1 {
	margin: 0; /* el ajuste en cero del margen del último elemento del div de #header evita la contracción del margen (un espacio inexplicable entre divs). Si el div tiene un borde alrededor, esto no es necesario, ya que también evita la contracción del margen */
	padding: 10px 0; /* el uso de relleno en lugar de margen le permitirá mantener el elemento alejado de los bordes del div */
}

/* Sugerencias para barras laterales:
1. Dado que está trabajando en porcentajes, es conveniente no utilizar relleno lateral en las barras laterales. Se añadirá al ancho en el caso de navegadores que cumplen los estándares, creando un ancho real desconocido. 
2. El espacio entre el lado del div y los elementos que contiene puede crearse colocando un margen izquierdo y derecho en dichos elementos, como se observa en la regla ".thrColLiqHdr #sidebar1 p".
3. Dado que Explorer calcula los anchos después de mostrarse el elemento padre, puede que ocasionalmente encuentre errores inexplicables con columnas basadas en porcentajes. Si necesita resultados más predecibles, puede optar por cambiar a columnas con tamaño en píxeles.
*/
.thrColLiqHdr #sidebar1 {
	float: left; /* este elemento debe preceder en el orden de origen a cualquier elemento que desee situar junto a */
	width: 220px; /* dado que este elemento es flotante, debe asignarse un ancho */
	background: #ffffff; /* el color de fondo se mostrará a lo largo de todo el contenido de la columna, pero no más allá */
	padding: 3px 0; /* el relleno superior e inferior crea un espacio visual dentro de este div  */
}
.thrColLiqHdr #sidebar2 {
	float: right; /* este elemento debe preceder en el orden de origen a cualquier elemento que desee situar junto a */
	width: 23%; /* dado que este elemento es flotante, debe asignarse un ancho */
	background: #EBEBEB; /* el color de fondo se mostrará a lo largo de todo el contenido de la columna, pero no más allá */
	padding: 15px 0; /* el relleno superior e inferior crea un espacio visual dentro de este div */
}
.thrColLiqHdr #sidebar1 p, .thrColLiqHdr #sidebar1 h3, .thrColLiqHdr #sidebar2 p, .thrColLiqHdr #sidebar2 h3 {
	margin-left: 10px; /* deben asignarse los márgenes izquierdo y derecho de cada elemento que vaya a colocarse en las columnas laterales */
	margin-right: 10px;
}

/* Sugerencias para mainContent:
1. El espacio entre el mainContent y las barras laterales se crea con los márgenes izquierdo y derecho del div mainContent.
2. para evitar la caída de un elemento flotante con una resolución mínima admitida de 800 x 600, los elementos situados dentro del div mainContent deben tener un tamaño de 300px o inferior (incluidas las imágenes).
3. en el siguiente comentario condicional de Internet Explorer, la propiedad zoom se utiliza para asignar a mainContent "hasLayout." Esto evita diversos problemas específicos de IE.
*/
.thrColLiqHdr #mainContent { 
	margin: 0 24% 0 23.5%; /* los márgenes derecho e izquierdo de este elemento div crean las dos columnas externas de los lados de la página. Con independencia de la cantidad de contenido que incluyan los divs de las barras laterales, permanecerá el espacio de la columna. Puede quitar este margen si desea que el texto del div #mainContent llene el espacio de las barras laterales cuando termine el contenido de cada una de ellas. */
}

.thrColLiqHdr #footer {
	padding: 0 10px; /* este relleno coincide con la alineación izquierda de los elementos de los divs que aparecen por encima de él. */
	background:#DDDDDD;
	font-size: 50%;
} 
.thrColLiqHdr #footer p {
	margin: 0; /* el ajuste en cero de los márgenes del primer elemento del pie evitará que puedan contraerse los márgenes (un espacio entre divs) */
	padding: 10px 0; /* el relleno de este elemento creará espacio, de la misma forma que lo haría el margen, sin el problema de la contracción de márgenes */
	font-size: 150%;
}

/* Varias clases diversas para su reutilización */
.fltrt { /* esta clase puede utilizarse para que un elemento flote en la parte derecha de la página. El elemento flotante debe preceder al elemento junto al que debe aparecer en la página. */
	float: right;
	margin-left: 8px;
}
.fltlft { /* esta clase puede utilizarse para que un elemento flote en la parte izquierda de la página. El elemento flotante debe preceder al elemento junto al que debe aparecer en la página. */
	float: left;
	margin-right: 8px;
}
.clearfloat { /* esta clase debe colocarse en un elemento div o break y debe ser el último elemento antes del cierre de un contenedor que deba incluir completamente a sus elementos flotantes hijos */
	clear:both;
	height:0;
	font-size: 24px;
	line-height: 0px;
}
--> 
</style><!--[if IE]>
<style type="text/css"> 
/* coloque las reparaciones de css para todas las versiones de IE en este comentario condicional */
.thrColLiqHdr #sidebar2, .thrColLiqHdr #sidebar1 { padding-top: 30px; }
.thrColLiqHdr #mainContent { zoom: 1; padding-top: 15px; }
/* la propiedad zoom propia que se indica más arriba proporciona a IE el hasLayout que necesita para evitar diversos errores */
</style>
<![endif]-->
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
</head>

<body class="thrColLiqHdr">

<div id="container">
 <div id="header">
    <h1 align="center">
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1000" height="200" id="FlashID" title="encabezado">
        <param name="movie" value="header.swf" />
        <param name="quality" value="high" />
        <param name="wmode" value="opaque" />
        <param name="swfversion" value="6.0.65.0" />
        <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
        <param name="expressinstall" value="../Scripts/expressInstall.swf" />
        <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="../header.swf" width="1000" height="200">
          <!--<![endif]-->
          <param name="quality" value="high" />
          <param name="wmode" value="opaque" />
          <param name="swfversion" value="6.0.65.0" />
          <param name="expressinstall" value="../Scripts/expressInstall.swf" />
          <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
          <div>
            <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
            <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
          </div>
          <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
    </h1>
  <!-- end #header --></div>
 <!-- Este elemento de eliminación siempre debe ir inmediatamente después del div #mainContent para forzar al div #container a que contenga todos los elementos flotantes hijos -->
  <!-- InstanceBeginEditable name="mainedit" -->
  <div align="left">
    <table width="1005" border="0" cellpadding="4" cellspacing="2">
      <tr>
        <td width="181" height="550" valign="top"><img src="menu.jpg" width="181" height="466" border="0" usemap="#Map" /></td>
        <td width="611" valign="top">
        
        

    <form id="Upload" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post"> 
    
    
          <div align="center">
            <table width="80%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FF6600">
              <tr>
                <td><div align="center"><strong>Cargar imagenes al Rhema</strong></div></td>
              </tr>
            </table>
            <div align="right"><br />
            </div>
          </div>
          <div align="center">
            <table width="94%" height="338" border="0">
              <tr>
                <td width="31%" height="31" bgcolor="#CCCCCC" class="Estilo3"><div align="right" class="Estilo13">
                  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>" />
                  Cargar imagen:</div></td>
                <td width="69%" valign="top" bgcolor="#CCCCCC"><div align="left">
                   <input id="file" type="file" name="file">
                     800 x 600 o menor</div></td>
              </tr>
              <tr>
                <td height="88" class="Estilo3"><div align="right" class="Estilo13">Comentario: </div></td>
                <td valign="top"><div align="left">
                    <p>
                      <textarea name="comentario" cols="70" rows="5" id="comentario" onKeyUp="filtarMP()"></textarea>
                    </p>
                </div></td>
              </tr>
              <tr>
                <td height="27" bgcolor="#CCCCCC" class="Estilo3"><div align="right" class="Estilo13">Fecha:<br />
                </div></td>
                <td valign="top" bgcolor="#CCCCCC"><div align="left">
                    <input name="fecha" type="text" id="fecha" size="40" />
                    <strong><span class="Estilo9">(Opcional)</span></strong></div></td>
              </tr>
              <tr>
                <td height="26" class="Estilo3"><div align="right" class="Estilo13">Lugar<span class="Estilo10">:</span></div></td>
                <td valign="top"><label>
                  <div align="left">
                    <input type="text" name="lugar" id="lugar" />
                  </div>
                </label>
                    <div align="left"></div></td>
              </tr>
              <tr>
                <td height="29" bgcolor="#CCCCCC" class="Estilo3"><div align="right" class="Estilo13">Nombre:</div></td>
                <td valign="top" bgcolor="#CCCCCC"><div align="left">
                    <input type="text" name="nombre" id="nombre" />
                </div></td>
              </tr>
              <tr>
                <td height="26" class="Estilo3"><div align="right"></div></td>
                <td valign="top"><div align="left">
                    <label></label>
                </div></td>
              </tr>
              <tr class="Estilo3">
                <td colspan="2" class="Estilo13"></label>
                  <label>
                  
                    <div align="center">
                      <input id="submit" type="submit" name="submit" value="Cargar Archivo"> 
                      </div>
                  </label>
                  <div align="center"></div></td>
              </tr>
            </table>
          </div>
        </form>        <p>&nbsp;</p>
        </td>
        <td width="181" align="center" valign="top"><object id="FlashID2" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="181" height="466">
          <param name="movie" value="flashmenu.swf" />
          <param name="quality" value="high" />
          <param name="wmode" value="opaque" />
          <param name="swfversion" value="6.0.65.0" />
          <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
          <param name="expressinstall" value="Scripts/expressInstall.swf" />
          <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
          <!--[if !IE]>-->
          <object type="application/x-shockwave-flash" data="flashmenu.swf" width="181" height="466">
            <!--<![endif]-->
            <param name="quality" value="high" />
            <param name="wmode" value="opaque" />
            <param name="swfversion" value="6.0.65.0" />
            <param name="expressinstall" value="Scripts/expressInstall.swf" />
            <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
            <div>
              <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
              <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
            </div>
            <!--[if !IE]>-->
            </object>
          <!--<![endif]-->
        </object></td>
</tr>
      <tr> </tr>
    </table>
 
  </div>

  <!-- InstanceEndEditable --><br class="clearfloat" />
  <div id="footer">
    <p align="center">Copyright © 2009 Proyecto Rhema<br />
Todos los derechos reservados<br />
    </p>
  <!-- end #footer --></div>
<!-- end #container --></div>
<map name="Map" id="Map">
  <area shape="rect" coords="75,50,173,78" href="index.html" />
  <area shape="rect" coords="50,85,172,110" href="mensajes.html" />
  <area shape="rect" coords="28,116,172,147" href="coberturas.html" />
  <area shape="rect" coords="34,151,170,179" href="canciones.html" />
  <area shape="rect" coords="10,182,169,213" href="comentarios.html" />
  <area shape="rect" coords="15,251,165,286" href="registrar2.php" />
</map>
<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID2");
//-->
</script>
</body>
<!-- InstanceEnd --></html>
