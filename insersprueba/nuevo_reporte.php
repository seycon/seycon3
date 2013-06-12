<?php
    include("../conexion.php");
    $db = new MySQL(); 
    $transaccion = "insertar";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<link href="../autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="Nreporte.js"></script>
<script src="../autocompletar/funciones.js"></script>
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="../js/jquery.validate.js"></script>

<script>

 $(document).ready(function()
 {
	 
	$("#formulario").validate({});
	 
	$('#fechar3').datepicker({
    showOn: 'button',
    buttonImage: '../css/images/calendar.gif',
    buttonImageOnly: true,
    dateFormat: 'dd/mm/yy' });
 });
 
</script>
</head>

<body>
 <div class="contendedor">
   <div class="header"><div class="gradient7"><h1><span></span>Discoteca</h1></div>  </div>
   
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
    <td colspan="2" valign="top"><div class="tituloMenu"><< BUFFALO >></div></td>
    </tr>
  <tr>
    <td height="336" colspan="2">
    <div class="contenedorMenu">
     <div id="opcion1" onclick="location.href='inicio_restaurante.php'"><div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_personal.php'"><div id="textoOpcion">Personal Apoyo</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_usuario.php'"><div id="textoOpcion">Usuario</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_configuracion.php'"><div id="textoOpcion">Configuración</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_reporte.php'"><div id="textoOpcion">Reportes</div></div>
    </div>
    </td>
    </tr>
  <tr>
    <td height="21" align="center" class="letra" colspan="2">Fecha: <?php echo date("d/m/Y");?></td>
  </tr>
</table>

    
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion"><div class="textoTituloTransaccion">Reportes</div></div>
          <div class="separador"></div>
            <table width="98%" border="0" align="center">
        <tr>
    <td width="17%" height="3"></td>
    <td width="23%"></td>
    <td width="24%" align="right"></td>
    <td width="7%"></td>
    <td width="21%"></td>
    <td width="8%"></td>
  </tr>
  <tr>
    <td><div id="textoConfiguracion" onclick="efectoClick('cgarzon')" >Garzon</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cventasucursal')" >Venta por Sucursal</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cdinero')" >Entrega de Dinero</div></td>
    <td></td>
    <td>&nbsp;</td>
    <td><input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"/></td>
  </tr>
  </table>
            
       <form id="formulario" name="formulario" method="post" >     
       <table width="100%" border="0" align="center">
  <tr>
    <td width="37%" colspan="6" valign="top">
      <div class="contenedorConfiguracion3" id="cgarzon">    
        <table width="100%" border="0" align="center">
          <tr>
            <td width="33%">&nbsp;</td>
            <td width="29%" align="center" class="letra2">&nbsp;</td>
            <td width="7%" align="center" class="letra2">&nbsp;</td>
            <td width="13%" align="center" class="letra2">&nbsp;</td>
            <td width="18%">&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Tipo:</td>
            <td align="left"><select name="tipo" id="tipo" onchange="getPersonal(this.value,setPersonal)" style="width:155px;">
              <option value="fijo">Fijo</option>
              <option value="apoyo">Apoyo</option>
            </select></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Trabajador:</td>
            <td align="left">
             <select id="trabajador2" name="trabajador2" style="width:155px;">
              <?php
			      $tipo = (isset($datosG['idtrabajador'])) ? $datosG['idtrabajador'] : 'fijo';
				  if ($tipo == 'fijo') {
					  $sql = "select idtrabajador,left(concat(nombre,' ',apellido),25)as 'nombre1'"
						  ." from trabajador where estado=1 order by nombre";
				  } else {
					  $sql = "select idpersonalapoyo,concat(nombre,' ',apellido)as 'nombre' "
						  ."from personalapoyo order by nombre,apellido;";
					  $db->imprimirCombo($sql,$datosG['idtrabajador']);
				  }
			  ?>
            </select>
            
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right"><span class="letra">Fecha:</span></td>
            <td><input type="text" id="fecha" name="fecha" size="12" value="<?php echo $valores['ayudantem1'];?>"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td height="23">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="button" value="Imprimir" id="botonrestaurante" onclick="getReporte3()"/></td>
            <td><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
        
        </div>
      
      
      
      
      <div class="contenedorConfiguracion4" id="cventasucursal">    
        <table width="100%" border="0" align="center">
          <tr>
            <td width="33%">&nbsp;</td>
            <td width="29%" align="center" class="letra2">&nbsp;</td>
            <td width="7%" align="center" class="letra2">&nbsp;</td>
            <td width="13%" align="center" class="letra2">&nbsp;</td>
            <td width="18%">&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Sucursal:</td>
            <td align="left"><select name="sucursal" id="sucursal" style="width:135px;">
              <option value='' selected='selected'>-- Seleccione --</option>
              <?php
                  $almacen = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1";
                  $db->imprimirCombo($almacen);
              ?>
              </select></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right"><span class="letra">Fecha:</span></td>
            <td><input type="text" id="ayudantem1" name="ayudantem1" size="12" value="<?php echo $valores['ayudantem1'];?>"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td height="23">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="button" value="Imprimir" id="botonrestaurante"/></td>
            <td><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
        
        </div>
        
        <div class="contenedorConfiguracion4" id="cdinero">    
        <table width="100%" border="0" align="center">
          <tr>
            <td width="33%">&nbsp;</td>
            <td width="29%" align="center" class="letra2">&nbsp;</td>
            <td width="7%" align="center" class="letra2">&nbsp;</td>
            <td width="13%" align="center" class="letra2">&nbsp;</td>
            <td width="18%">&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Fecha</td>
            <td align="left">
            <input type="text" name="fechar3" size="10" id="fechar3" class="date" value="<?php echo date("d/m/Y"); ?>"/>
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Tipo:</td>
            <td align="left"><select name="tipo2" id="tipo2" onchange="getPersonal(this.value,setPersonal2)" style="width:100px;">
              <option value="fijo">Fijo</option>
              <option value="apoyo">Apoyo</option>
            </select></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Trabajador:</td>
            <td align="left">           
            <input type="text"  id="trabajadorr3" autocomplete="off" 
            onkeyup="autocompletar(event,this.id)" onfocus="this.select()" style="width:100px;"/><br>
               <div  id="resultados"  class="divresultado" ></div>
               <input type="hidden" id="idusuarior3" />
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right"><span class="letra">Total Venta:</span></td>
            <td><input type="text" id="totalventar3" disabled="disabled" name="totalventar3" size="12" value="0"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Total Entregado:</td>
            <td><input type="text" id="entregador3" name="entregador3" size="12" onkeyup="calcularFaltante(this.id)" value="0"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Faltante/Demás:</td>
            <td><input type="text" disabled="disabled" id="faltanter3" name="faltanter3" size="12" value="0"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td >&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="button" value="Imprimir" id="botonrestaurante" onclick="getReporte3()"/></td>
            <td><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
        
        </div>
        
        
      
      </td>
  </tr>
  </table>

</form>

      </div>
    </td>
  </tr>
</table>

   
 </div>
</body>
</html>