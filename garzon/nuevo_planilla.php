<?php
    include("../conexion.php");
    $db = new MySQL();
 
    if (!isset($_SESSION['softLogeoadmin'])){
        header("Location: ../index.php");	
    }
 
    function filtro($cadena)
	{
        return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
    } 
 
    
    $transaccion = "insertar";
    if (isset($_GET['nro'])) {
        $transaccion = "modificar";
        $datosG = $db->arrayConsulta("select * from entregadinero where identrega=$_GET[nro]");  	 
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="Nplanilla.js"></script>
<link href="../autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script src="../autocompletar/funciones.js"></script>

<script>
 var setValorCheck = function(id){
	if ($$(id).checked){
	  $$(id).value = "1";	
	}else{
	  $$(id).value = "0";	
	}
 }
 

 
  $(document).ready(function()
 {
	 
	$("#formulario").validate({});
	 
	$('#fecha').datepicker({
    showOn: 'button',
    buttonImage: '../css/images/calendar.gif',
    buttonImageOnly: true,
    dateFormat: 'dd/mm/yy' });
 });
</script>
</head>

<body>



<div id="modal1" class="modalBusqueda"></div>
  <div id="modalInterior1" class="modalInteriorBusqueda">
  <div class="headerInterior"><div class="tituloVentanaclave">Planilla de  Apoyo</div>
  <div class="posicionCloseSubBusqueda" onclick="closeVentanaClave();">
  <img src="../iconos/borrar2.gif" width="12" height="12"></div>
  </div>  
  <br />
  <div class="listadoOption">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
    <th width="25%" style="border-right:1px solid; border-right-color:#FFF;">Sucursal</th>
    <th width="11%" class="lateralDerecho">Fecha</th>
    <th width="11%" class="lateralDerecho">Venta</th>
    <th width="13%" class="lateralDerecho">Comisión</th>
    <th width="12%" class="lateralDerecho">Botella</th>
    <th width="10%" class="lateralDerecho">Haber</th>
    <th width="10%" class="lateralDerecho">Faltante</th>
    <th width="8%" align="center">Pagar</th>
    <th width="8%" align="center" style="display:none">idsucursal</th>
    <th width="8%" align="center" style="display:none">seleccion</th>
  </tr>
  <tbody id="deudasPendiente">
  
  </tbody>
  
  </table>
  </div>
  
  <div class="posTotal">Total:&nbsp;<input type="text" name="totalDeuda" id="totalDeuda" disabled="disabled" value="0.00"/></div>
 </div>      


<div id="overlay" class="overlays"></div>
<div id="gif" class="gifLoader"></div>

 <div class="contendedor">
   <div class="tela_izq"></div>
   <div class="tela_cierreizq"></div>
   <div class="tela_der"></div>
   <div class="tela_cierreder"></div> 
   <div class="derechosReservados">Copyright © Consultora Guez – Diseñado y Desarrollado</div>
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
    <td colspan="2" valign="top"><div class="tituloMenu"><< BUFFALO >></div></td>
    </tr>
  <tr>
    <td height="336" colspan="2">
    <div class="contenedorMenu">
     <div id="opcion1" onclick="location.href='inicio_restaurante.php'"><div class="sombraButon"></div> 
     <div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_personal.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Personal Apoyo</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_usuario.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Usuario</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_configuracion.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Configuración</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_entrega.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Entregar dinero</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_planilla.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Planilla Apoyo</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_reporte.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Reportes</div></div>
    </div>
    </td>
    </tr>

</table>

    
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion">
         <div class="textoTituloTransaccion">Planilla de Apoyo</div></div>
          <div class="separador"></div>
            
            <table width="98%" border="0" align="center">
        <tr>
    <td width="17%" height="3"></td>
    <td width="38%"></td>
    <td width="16%" align="right"></td>
    <td width="17%"></td>
    <td width="4%"></td>
    <td width="8%"></td>
  </tr>
  <tr>
    <td><div id="textoConfiguracion" onclick="location.href='listar_planilla.php'" >Listar Planilla</div></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>
    </td>
  </tr>
  </table>
            
            
   
            
            
            
            
            
            
            
            
            
            
            
            
            
            
       <form id="formulario" name="formulario" method="post" action="nuevo_planilla.php">  
       
       <table width="92%" border="0" align="center">
  <tr>
    <td width="5%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="59%">&nbsp;</td>
    <td width="15%"><input type="button" value="Guardar"  class="botonrestaurante" onclick="guardarDatos()" id="botonUsuario"/></td>
    <td width="16%"><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
  </tr>
</table>
   
       <table width="90%" border="0" align="center">
          <tr>
            <td>Los campos con <span class="rojo">(*)</span> son requeridos.</td>
            <td align="center" class="letra2">&nbsp;</td>
            <td align="center" class="letra2">&nbsp;</td>
            <td align="center" class="letra2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="33%">&nbsp;</td>
            <td width="24%" align="center" class="letra2">&nbsp;</td>
            <td width="22%" align="center" class="letra2">&nbsp;</td>
            <td width="4%" align="center" class="letra2">&nbsp;</td>
            <td width="17%"><input type="hidden" name="transaccion" id="transaccion" value="<?php echo $transaccion;?>"/>
            <input type="hidden" name="idtransaccion" id="idtransaccion" value="<?php echo $datosG['identrega'];?>"/>
            </td>
            </tr>
          <tr>
            <td align="right" >Fecha:</td>
            <td align="left">
            <input type="text" name="fecha" size="10" id="fecha" class="date" value="<?php if (isset($datosG['fecha'])) echo $db->GetFormatofecha($datosG['fecha'],"-"); else echo date("d/m/Y"); ?>"/>
            <div id="msjfecha" class="msjError"></div>
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" >Trabajador:<span class="rojo">*</span></td>
            <td align="left">
            <input type="text"  id="trabajador" autocomplete="off" 
            onkeyup="tipoBusquedaTrabajador(event)" onfocus="this.select()" style="width:180px;" 
            value=""/>                 
            <br>
               <div  id="resultados"  class="divresultado" style="width:184px;"></div>
            
            <input type="hidden" name="trabajadorapoyo" id="trabajadorapoyo" />
            <div id="msjtrabajador" class="msjError"></div>
              </td>
            <td align="left"><div id="autoL1" class="autoLoading"></div></td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" >Monto:<span class="rojo">*</span></td>
            <td align="left"><input type="text" name="monto" id="monto" style="width:100px;" disabled="disabled"/>
            <input type="button" id="buscar" name="buscar" value="" class='botonseyconBuscar' onclick="consultarPendientes();"/>
            <div id="msjmonto" class="msjError"></div> 
            </td>
            <td align="left">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right">Caja/Banco:<span class="rojo">*</span></td>
            <td><select id="caja" name="caja" style="background:#FFF;border:solid 1px #999;width:130px;" class="required">
              <option value="">-- Seleccione --</option>
              <?php
				 $sql = "select t.modalidadcontrato as 'modalidad' from trabajador t,usuario u where 
				 u.idtrabajador=t.idtrabajador and u.idusuario=$_SESSION[id_usuario]";
				 $MTrabajador = $db->arrayConsulta($sql);
				 if ($MTrabajador['modalidad'] == "Consultor"){
					$sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
					from cajero c,trabajador t where t.idtrabajador=c.idtrabajador and t.estado=1;"; 					
				 }else{				 
				    $sql = "select c.*,left(concat(t.nombre,' ',t.apellido),20) as 'nombre' 
					from cajero c,usuario u,trabajador t where t.idtrabajador=u.idtrabajador 
					and u.idtrabajador=c.idtrabajador and u.idusuario=$_SESSION[id_usuario]";				 
 			     }
				   $cajas = $db->consulta($sql);
					while ($data = mysql_fetch_array($cajas)){
					echo "<optgroup label='$data[nombre]'>";	
					if ($data['cuentacaja1']!= "" && $data['textocaja1'] != "")  
					  echo "<option value='$data[cuentacaja1]'>$data[textocaja1]</option>";  
					if ($data['cuentacaja2']!= "" && $data['textocaja2'] != "")  
					  echo "<option value='$data[cuentacaja2]'>$data[textocaja2]</option>"; 
					if ($data['cuentabanco1']!= "" && $data['textobanco1'] != "")    
					  echo "<option value='$data[cuentabanco1]'>$data[textobanco1]</option>"; 
					if ($data['cuentabanco2']!= "" && $data['textobanco2'] != "")    
					  echo "<option value='$data[cuentabanco2]'>$data[textobanco2]</option>"; 
					if ($data['cuentabanco3']!= "" && $data['textobanco3'] != "")    
					  echo "<option value='$data[cuentabanco3]'>$data[textobanco3]</option>"; 
					  echo "</optgroup>";
					}	  
		  
		  
		  ?>
            </select><div id="msjcaja" class="msjError"></div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><p>.</p>
              <p>&nbsp;</p></td>
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
          </table>

</form>

      </div>
    </td>
  </tr>
</table>

   
 </div>
</body>
</html>

<script>
 // seleccionarCombo('tipo','<?php echo $datosG['tipo'];?>');
</script>
