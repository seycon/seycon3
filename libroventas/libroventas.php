<?php
     session_start();
	 include('../conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])) {
         header("Location: ../index.php");	
     }
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
	 if (!$db->tieneAccesoFile($estructura['Contabilidad'],'Libro de Ventas','libroventas/libroventas.php')) {
	     header("Location: ../cerrar.php");	
	 }
	 $sql_aviso = "select numautorizacion,numfactfinal,fechalimitemision from sucursal 
	 where idsucursal = '$_SESSION[idsucursal]'";
	 
	 function calculaespacio($nivel)
	 {
       $espacio="&nbsp;";
	   for ($i = 0; $i < $nivel-2; $i++)
	       $espacio.=$espacio;	
	   return $espacio;
	 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Libro de Ventas</title>
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

<style type="text/css">
body{
scrollbar-arrow-color: #CCC;
scrollbar-base-color: #333;
scrollbar-dark-shadow-color: #666;
scrollbar-track-color: #999;
scrollbar-face-color: #666;
scrollbar-shadow-color: #333;
scrollbar-highlight-color: #CCCCCC;

}

</style>

<link rel="stylesheet" type="text/css" href="style.css" />
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="NVentas.js"></script>
<script src="../autocompletar/FuncionesUtiles.js"></script>
<script src="../lib/Jtable.js"></script>
<script>
$(document).ready(function(){	 
	  alm = '<? echo $_SESSION['idsucursal'];?>';
	  seleccionarCombo('sucursal',alm);
});	 
</script>


</head>

<body>


<div id="overlay" class="overlays"></div>
 <div id="gif" class="gifLoader"></div>

 <div id="modal_mensajes" class="contenedorMsgBox">
  <div class="modal_interiorMsgBox"></div>
  <div class="modalContenidoMsgBox">
      <div class="cabeceraMsgBox">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBox">ADVERTENCIA</div>
        <div class="modal_cerrarMsgBox">
         <img src="../iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
      </div>
      <div class="contenidoMsgBox">
        <div class="modal_ico1MsgBox"><img src="../iconos/alerta.png" width="28" height="28"></div>
        <div class="modal_datosMsgBox" id="modal_contenido">Debe Seleccionar un Almacén de Origen.</div>
        <div class="modal_boton1MsgBox"><input type="button" value="Aceptar" class="botonNegro" onclick="closeMensaje()"/></div>
      </div>
  </div>
 </div>
 



<form id="formulario" name="formulario"> 
<div id="factura" class="cen">
<div class="subcaption"></div>
<div id="caption" class="caption">
  <table width="100%" border="0">
  <tr>
    <td height="10"></td>
    <td align="center"></td>
    <td></td>
  </tr>         
  <tr>
    <td width="9%"></td>
    <td width="82%" align="center">
           <strong style="font-size:24px;color:#FFF;font-weight:bold;text-shadow: 0.1em 0.1em #000;font-family:'capture_itregular';">
             <?php echo "LIBRO DE VENTAS";?>
            <input type="hidden" id="tc" value="<? echo $tc?>" />
           </strong></td>
    <td width="9%"><img src="../iconos/borrar2.gif" class="cerrar" width="16" height="16" onclick="window.close(); "></td>
  </tr>
</table>

</div>
         
   <div id="producto" class="producto">

           <table width="100%" border="0">
  <tr>
    <td width="10%" align="right"><div class="subtitle">Sucursal:</div> </td>
    <td width="16%" align="left"><select name="sucursal" id="sucursal" onchange="realizarConsulta();" style="width:160px;">
      <option value="" selected="selected" style="border-bottom:2px solid #CCC;background-color:#E2E2E2" >--Seleccione--</option>
      <?php
        $almacen = "select idsucursal, left(nombrecomercial,23)as 'nombrecomercial' from sucursal where estado=1";
		$almacen = $db->consulta($almacen);
		$i = 1;
		while ($data = mysql_fetch_array($almacen)){
		 if ($i%2 == 0){
		  $color = "E2E2E2";
		 }else{
		  $color = "FFF";  	
		 }
		  echo "<option value='$data[idsucursal]' style='border-bottom:2px solid #CCC;background-color:#$color'>".ucfirst($data['nombrecomercial'])."</option>";
		  $i++;
		}		
	
      ?>
    </select></td>
    <td width="9%" align="right"><div class="subtitle">Periodo:</div></td>
    <td width="21%">
    <input type="hidden" value="<? echo $_GET['fac'];?>" id="otrapagina" />
    <select name="mes" id="mes" onchange="realizarConsulta();">
      <option value=""> Mes:</option>
      <option value="01">Enero</option>
      <option value="02">Febrero</option>
      <option value="03">Marzo</option>
      <option value="04">Abril</option>
      <option value="05">Mayo</option>
      <option value="06">Junio</option>
      <option value="07">Julio</option>
      <option value="08">Agosto</option>
      <option value="09">Septiembre</option>
      <option value="10">Octubre</option>
      <option value="11">Noviembre</option>
      <option value="12">Diciembre</option>
    </select>
      <select name="anio" id="anio" onchange="realizarConsulta();">
      <option value="2010">2010</option>
      <option value="2011">2011</option>
      <option value="2012">2012</option>
      <option value="2013">2013</option>
      <option value="2014">2014</option>
      <option value="2015">2015</option>
    </select></td>
    <td width="8%" class="subtitle">Servicios<input type="radio" name="radio" id="tipocuenta" checked="checked" value="servicios" /></td>
    <td width="14%" class="subtitle">Productos<input type="radio" name="radio" id="tipocuenta" value="productos" /></td>
    <td width="9%"><span style="margin-bottom:7px;margin-top:5px">
      <input type="button" value="Imprimir" onclick="imprimir('imprimir_Libro_Ventas.php')" class="botonNegro" />
    </span></td>
    <td width="13%"><span style="margin-bottom:7px;margin-top:5px">
      <input type="button" value="Da Vinci" onclick="imprimir('ventasDavinchi.php')" class="botonNegro" />
    </span></td>
    </tr>
</table>


</div>
<div style="text-align:right;background:#FFF;height:21px;margin-top:2px;"></div>

       <div class="cuerpo_insert">
               <div class="subtitle" align="center">Ingrese los Datos del L.V. </div>
                <hr />
         <table width="100%" border="0">
           
           <tr>
             <td colspan="2" height="25">
             <div id="mensajeLibro" class="msjFacturas"></div> 
             </td>
           </tr>
           <tr>
            <td><div class="subtitle">DIA</div>                      
             <input name="fecha" type="text" id="fecha" size="10" 
             onkeypress="return cambiarFoco(event,this.value,'nit')" autocomplete="off"/>
             <input type="hidden" id="id" name="id" value="-1"  />
             <input type="hidden" id="fila" name="fila" value="-1"  /></td>
            <td valign="bottom" align="center">
            <input type="button" name="Guardar2" id="Guardar2" value="Sin Movimiento"  
            class="botonNegro" onclick="generarSinMovimiento();" style="width:100px;"/></td>
           </tr>
           
           <tr>
             <td width="39%" align="left"><div class="subtitle">NIT</div>
             <input name="nit" type="text" id="nit" size="10" onchange="recuperaNombre(this.value,'DVentas.php')"
              onkeyup="atajoAnulado(this.value)" onkeypress="return soloNumeros(event)" onfocus="ocultarMensaje()" autocomplete="off"/></td>
             <td width="61%" align="left"><div class="subtitle">ESTADO</div>
               <select name="estado" id="estado" onchange="cambioEstado(this.value)">
              <option value="V" selected="selected">Valido</option>
              <option value="A" >Anulado</option>
              <option value="E">Extraviado</option>
             </select>
             
             </td>
           </tr>           
           <tr>
              <td colspan="2"><div class="subtitle">Nombre o Razón Social</div>
               <input name="razonsocial" type="text" id="razonsocial" style="width:90%;" autocomplete="off"/></td>
           </tr>           
           <tr>
             <td ><div class="subtitle">N° Factura</div>
               <input type="text" id="numerofactura" name="numerofactura" size="10" value="" 
               onkeypress="return soloNumeros(event)" disabled="disabled" />
             </td>
             <td align="left"><div class="subtitle">N° Autorización</div>
              <input name="numeroautorizacion" type="text" id="numeroautorizacion" disabled="disabled" value="" style="width:90%"/>
             </td>
           </tr>
            <tr>
              <td align="right" style="font-size:12px;"><div class="subtitle">Imp. Total (a)</div></td>
              <td>
                <input name="importetotal" onkeypress="return soloNumeros(event)" type="text" 
                id="importetotal" style="width:90%" autocomplete="off"/>
              </td>
            </tr>
            <tr>
              <td align="right"  style="font-size:12px;"><div class="subtitle">Imp. ICE (b)</div></td>
              <td>
              <input name="ice" onkeypress="return soloNumeros(event)" type="text" id="ice" style="width:90%" autocomplete="off"/>
              </td>
            </tr>
            <tr>
              <td  align="right"style="font-size:12px;"><div class="subtitle">Imp. Exento(c)</div></td>
              <td>
             <input name="excento" onkeypress="return soloNumeros(event)" type="text" id="excento" 
             style="width:90%" autocomplete="off"/>
             </td>
               
            </tr>
            <tr>
              <td align="right" bgcolor="#999999"style="font-size:12px;"> I. Neto (a-b-c)</td>
              <td >
                <input name="neto" onkeypress="return false" type="text" id="neto" style="width:90%" onfocus="calculaNeto();" /></td>
            </tr>
            <tr>
              <td align="right" bgcolor="#999999"style="font-size:12px;">I. IVA (13%)</td>
              <td>
                <input name="iva" onkeypress="return false" type="text" id="iva" style="width:90%" onfocus="calculaIva();" /></td>
            </tr>
            
          <tr>
            <td colspan="2" align="center"><div class="subtitle">Código de Control</div>
            <input name="codigocontrol" type="text" id="codigocontrol" size="20" onkeyup="eventoText(event)" autocomplete="off"/>
            </td>
           </tr>
            <tr>
              <td colspan="2" align="center" >
              <input type="button" name="Guardar" id="Guardar" 
              value="Guardar" class="botonseycon" onclick="ejecutarTransaccion();"/>
              </td>
           </tr>
       </table>

</div> 

    <div id="cuerpo" class="cuerpo">
    <div id="cubrir" class="overlay"></div>
         <div id="tr_div" style="position:relative; height:418px;overflow:auto;">
          <table width="100%" border="0" id="tabla" >
            
            <tr style="background-image:url(../iconos/fondofactura.jpg); line-height:14px; font-size:11px; font-weight:bold">
              <td width="1%"  >&nbsp;</td>
              <td width="1%"  >&nbsp;</td>
              <td width="2%"  >N°</td>
              <td width="5%" ><div align="center">Fecha Emision</div></td>
              <td width="6%" style="display:none" ><div align="center">nit</div></td>
              <td width="30%" ><div align="center">Nombre o Razón Social</div></td>
              <td width="6%" ><div align="center">N° Factura</div></td>
              <td width="10%" ><div align="center">N° Autorización</div></td>
              <td width="14%" ><div align="center">Cod. Control</div></td>
              <td width="6%" ><div align="center">Total Factura</div></td>
              <td width="5%" ><div align="center">Total ICE</div></td>
              <td width="7%" ><div align="center">Importe Exento</div></td>
              <td width="7%" ><div align="center">Importe Neto</div></td>
              <td width="6%" ><div align="center">Crédito Fiscal</div></td>
              <td align="center">Tipo</td>
              <td style="display:none;"><div align="center">Cuenta</div></td>
              <td style="display:none;">id</td> 
              <td style="display:none;">estado</td>
              <td style="display:none;">Razon Social</td>
            </tr>
            
            <tbody id="a">
               
            
            </tbody>
           
          </table> 
         </div> 
           <table style="bottom:0px; position:absolute; width:100%;" class="ta"  border="0">
            <tr>
              <td width="10%" align="right" class="titulosTotales">Total Factura:</td>
              <td width="10%" align="right"><div id="totalf" align="left"> </div></td>
              <td width="8%" align="right" class="titulosTotales">Total ICE:</td>
              <td width="12%" align="right"><div id="totali" align="left"> </div></td>
              <td width="8%" align="right" class="titulosTotales">Importe Exento:</td>
              <td width="10%" align="right"><div id="totale" align="left"> </div></td>
              <td width="11%" align="right" class="titulosTotales">Importe Neto:</td>
              <td width="11%" align="right"><div id="totaln" align="left"> </div></td>
              <td width="8%" align="right" class="titulosTotales">Crédito Fiscal:</td>
              <td width="12%"><div id="totald" align="left"> </div></td>
            </tr>
          </table> 

    </div>
</div>
</form>
     
<script>
  seleccionarCombo('anio','<?php echo date("Y")?>'); 
</script>
     
</body>
</html>
