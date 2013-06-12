<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>



<script>
var $$ = function(id){
  return document.getElementById(id);	
}

function abrir(puerto) {
   $$("cmms").CommPort = puerto;
   $$("cmms").PortOpen = true;
}
function cerrar() { $$("cmms").PortOpen = false; }
function mandar(cadena) {
    $$("cmms").Output = cadena;
}
function recibir() {
    var cadena = $$("cmms").Input;
 
    forma.respuesta.value += cadena + "n";
 
    var r = forma.respuesta.createTextRange();
    r.scrollIntoView(false);
 
    return false;       
}
</script>
<script LANGUAGE=javascript FOR=cmms EVENT=OnComm>recibir()</script>

</head>

<body onload="abrir(1);" onunload="cerrar();" topmargin=0>

 

 
<br>
<OBJECT id="cmms" name="cmms" style="LEFT: 0px; TOP: 0px" classid="clsid:648A5600-2C6E-101B-82B6-000000000014" VIEWASTEXT>
    <PARAM NAME="_ExtentX" VALUE="1005">
    <PARAM NAME="_ExtentY" VALUE="1005">
    <PARAM NAME="_Version" VALUE="393216">
    <PARAM NAME="CommPort" VALUE="1">
    <PARAM NAME="DTREnable" VALUE="-1">
    <PARAM NAME="Handshaking" VALUE="0">
    <PARAM NAME="InBufferSize" VALUE="1024">
    <PARAM NAME="InputLen" VALUE="0">
    <PARAM NAME="NullDiscard" VALUE="0">
    <PARAM NAME="OutBufferSize" VALUE="512">
    <PARAM NAME="ParityReplace" VALUE="63">
    <PARAM NAME="RThreshold" VALUE="1">
    <PARAM NAME="RTSEnable" VALUE="0">
    <PARAM NAME="BaudRate" VALUE="9600">
    <PARAM NAME="ParitySetting" VALUE="2">
    <PARAM NAME="DataBits" VALUE="7">
    <PARAM NAME="StopBits" VALUE="0">
    <PARAM NAME="SThreshold" VALUE="0">
    <PARAM NAME="EOFEnable" VALUE="0">
    <PARAM NAME="InputMode" VALUE="1">
</OBJECT>
 
<form name="forma" onsubmit="return false;">
<table border=1>
    <tr>
        <td>Texto</td>
        <td><input type="parte" name="parte" value="VA6L2H FNLALT"></td>
        <td><input type="button" name="btnImprimir1" value="Imprimir1" onclick="mandar(parte.value + sufijo.value + cantidad.value)"></td>
    </tr>
    <tr>
        <td>Sufijo</td>
        <td><input type="text" name="sufijo" value="AA"></td>
    </tr>
    <tr>
        <td>Cantidad</td>
        <td><input type="text" name="cantidad" value="000026"></td>
    </tr>
    <tr>
        <td colspan=4><textarea cols=50 rows=8 name="respuesta" value=""></textarea></td>
    </tr>
</table>
 


</body>
</html>