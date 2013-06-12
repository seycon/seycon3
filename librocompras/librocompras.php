<?php
     session_start();
	 include('../conexion.php');
	 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
     }
	 $db = new MySQL();
	 $estructura = $_SESSION['estructura'];
	 if (!$db->tieneAccesoFile($estructura['Contabilidad'],'Libro de Compras','librocompras/librocompras.php')){
	  header("Location: ../cerrar.php");	
	 }
	 $sql_aviso = "select numautorizacion,numfactfinal,fechalimitemision from sucursal 
	 where idsucursal = '$_SESSION[idsucursal]'";
	 
	     function calculaespacio($nivel){
       $espacio="&nbsp;";
	   for ($i=0;$i<$nivel-2;$i++)
	           $espacio.=$espacio;	
		return $espacio;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Libro de Compras</title>
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
<script src="NCompras.js"></script>
<script src="../autocompletar/FuncionesUtiles.js"></script>
<script type="text/javascript" src="json2.js"></script>
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

<div id="modal_mensajes" class="modal_mensajes">
  <div class="modal_cabecera">
     <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
     <div class="modal_cerrar"><img src="../iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
  </div>
  <div class="modal_icono_modal"><img src="../iconos/alerta.png" width="24" height="24"></div>
  <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
  <div class="modal_boton1"><input type="button" value="Aceptar" class="boton_modal" onclick="closeMensaje()"/></div>
</div>


<form id="formulario"> 
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
    <td width="87%" align="center">
          <div style=" font-size:24px;color:#FFF;font-weight:bold;text-shadow: 0.1em 0.1em #000;font-family:'capture_itregular';">
             <? echo "LIBRO DE COMPRAS";?>
            <input type="hidden" id="tc" value="<? echo $tc?>" />
           </div></td>
    <td width="4%"><img src="../iconos/borrar2.gif" class="cerrar" width="16" height="16" title="Cerrar" onclick="window.close(); "></td>
  </tr>
</table>

</div>
         
        
       <div id="producto" class="producto">

  <table width="100%" border="0">
  <tr>
    <td width="14%" align="right"><div class="subtitle">Sucursal:</div> </td>
    <td width="15%" align="left">
      <select name="sucursal" id="sucursal" onchange="realizarConsulta();" style="width:160px;">
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
    <td width="7%" align="right">

  
     <div class="subtitle">Periodo:</div></td>
    <td width="46%">
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
    <td width="8%"><span style="margin-bottom:7px;margin-top:5px">
      <input type="button" value="Imprimir" onclick="imprimir('imprimir_Libro_Compras.php')" class="botonNegro" />
    </span></td>
    <td width="6%"><span style="margin-bottom:7px;margin-top:5px">
      <input type="button" value="Da Vinci" onclick="imprimir('comprasDavinchi.php')" class="botonNegro" />
    </span></td>
    <td width="4%">&nbsp;</td>
    </tr>
</table>


</div><div style="text-align:right;margin-top:0px;background:#FFF;height:21px;margin-top:2px;"><strong>PLAN DE CUENTAS:</strong>
<?php 
	  $consulta = "select codigo,cuenta,nivel,referencia FROM plandecuenta 
				   where referencia = '' and nivel in(1,5,6) order by codigo asc;";
	  $resultado = mysql_query($consulta) or die ( mysql_error() );
	  echo "<select style='font-size:14px' id='codigocuenta' name='codigocuenta' >";
	  echo "<option value= '0'> -- Seleccione una Cuenta -- </option>";
	  $i=1;
	  while($datos = mysql_fetch_assoc($resultado))
	  {
		  $i++;
		  if ($datos['nivel']>=1 and $datos['nivel']<5)   
			 echo "<optgroup style='font-size:9px' label='".calculaespacio($datos['nivel'])."$datos[codigo]-$datos[cuenta]'>";              
		   else
			 if ($datos['referencia'] == '')
			  echo "<option value= '".$datos['codigo']."'>".calculaespacio($datos['nivel']).$datos['codigo']." - ".$datos['cuenta']."</option>";
			 else
			  echo "<option id='combo".$i."' value= '".$datos['codigo']."'>".
				   calculaespacio($datos['nivel']).$datos['codigo']." - ".$datos['cuenta']."</option>
					<script>
						document.getElementById('combo'+$i).disabled='disabled';
				   </script>";
	  }
	  echo '</select>';
	?></div>
       <div class="cuerpo_insert">
       <div class="subtitle" align="center">Tipo Libro:&nbsp; 
       <select id="tipo">
         <option value="1">FACTURA</option>
         <option value="2">POLIZA</option>
         <option value="3">MIXTO</option>           
       </select>
       </div>
  <hr>
  
        <div style="position:relative;width:210px;">
         <table width="102%" border="0">
           
           <tr>
             <td colspan="2" height="25">
             <div id="mensajeLibro" class="msjFacturas"></div> 
             </td>
           </tr>
           <tr>
            <td><div class="subtitle">DIA</div>
           
             <input name="fecha" type="text" id="fecha" size="10" onkeypress="return cambiarFoco(event,this.value,'nit')" autocomplete="off"/>
             <input type="hidden" id="id" name="id" value="-1"  />
             <input type="hidden" id="fila" name="fila" value="-1"  /></td>
            <td valign="bottom" align="center">
            <input type="button" name="Guardar2" id="Guardar2" value="Sin Movimiento"  
            class="botonNegro" onclick="generarSinMovimiento();" style="width:100px;"/></td>
           </tr>
           
           <tr>
             <td width="39%" align="left"><div class="subtitle">NIT</div>             
             <input name="nit" type="text" id="nit" size="10" onchange="recuperaNombre(this.value,'Dcompras.php')"
              onkeypress="return soloNumeros(event)" onfocus="ocultarMensaje()" autocomplete="off"/></td>
             <td width="61%" align="left"></td>
           </tr>
           
           <tr>
              <td colspan="2"><div class="subtitle">Nombre o Razón Social</div>
               <input name="razonsocial" type="text" id="razonsocial" style="width:90%" autocomplete="off"/></td>
           </tr>
           
           <tr>
             <td ><div class="subtitle">N° Factura</div>
               <input type="text" id="numerofactura" name="numerofactura" size="10" value="<? echo $_GET['fac']?>" 
               onkeypress="return soloNumeros(event)" autocomplete="off"/>
             </td>
             <td align="left"><div class="subtitle">N° Autorización</div>

                <input name="numeroautorizacion" type="text" id="numeroautorizacion" 
                   value="" style="width:90%"/>
             </td>
           </tr>
            <tr>
              <td align="right" style="font-size:12px;"><div class="subtitle">Imp. Total (a)</div> </td>
              <td>
                <input name="importetotal" onkeypress="return soloNumeros(event)" type="text" id="importetotal" style="width:90%" autocomplete="off"/>
              </td>
            </tr>
            <tr>
              <td align="right"  style="font-size:12px;"><div class="subtitle">Imp. ICE (b)</div></td>
              <td>
                <input name="ice" onkeypress="return soloNumeros(event)" type="text" id="ice" style="width:90%" autocomplete="off"/></td>
            </tr>
            <tr>
              <td  align="right"style="font-size:12px;"><div class="subtitle">Imp. Exento(c)</div></td>
              <td>
                <input name="excento" onkeypress="return soloNumeros(event)" type="text" id="excento" style="width:90%" autocomplete="off"/></td>
               
            </tr>
            <tr>
              <td align="right" bgcolor="#999999"style="font-size:12px;"> I. Neto (a-b-c)</td>
              <td >
                <input name="neto" onkeypress="return false" type="text" id="neto" style="width:90%" onfocus="calculaNeto();" /></td>
            </tr>
            <tr>
              <td align="right" bgcolor="#999999"style="font-size:12px;">Imp. IVA (13%)</td>
              <td>
                <input name="iva" onkeypress="return false" type="text" id="iva" style="width:90%" onfocus="calculaIva();" /></td>
            </tr>
            
          <tr>
              <td colspan="2" align="center"><div class="subtitle">Código de Control</div>
            <input name="codigocontrol" type="text" id="codigocontrol" size="20" onkeyup="eventoText(event)" autocomplete="off"/></td>
           </tr>
            <tr>
              <td colspan="2" align="center" >
              <input type="button" name="Guardar" id="Guardar" value="Guardar" class="botonseycon" onclick="ejecutarTransaccion();"/></td>
           </tr>
       </table>
     </div>
</div> 

    <div id="cuerpo" class="cuerpo">
    <div id="cubrir" class="overlay"></div>
         <div id="tr_div" style="position:relative;width:100%;height:418px;overflow:auto;">
          <table width="100%" border="0" id="tabla" style="margin-top:0px;">
            
            <tr style="background-image:url(../iconos/fondofactura.jpg); line-height:14px; font-size:11px; font-weight:bold">
              <td width="1%"  >&nbsp;</td>
              <td width="1%"  >&nbsp;</td>
              <td width="2%"  >N°</td>
              <td width="5%" ><div align="center">Fecha Emisión</div></td>
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
              <td style="display:none;">tipolibro</td>
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
