<?php
    include("../conexion.php");
    $db = new MySQL();
 
    function filtro($cadena)
	{
        return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
    } 
	
	if (!isset($_SESSION['softLogeoadmin'])){
        header("Location: ../index.php");	
    }
 
    if (isset($_POST['transaccion'])) {		
		$claveUser = md5($_POST['clave']);
        $claveUser2 = crc32($claveUser);
        $claveUser3 = crypt($claveUser2, "xmas");
        $claveFinal = sha1("xmas".$claveUser3);
		
        if ($_POST['transaccion'] == "insertar") {
		    $sql = "INSERT INTO usuariorestaurante(idusuario,idtrabajador,tipo,login,clave,estado,idsucursal,turno,idusuariosistema)"
			    ."VALUES (NULL,'$_POST[idpersonarecibida]','".filtro($_POST['tipo'])."','".filtro($_POST['usuario'])
				."','".filtro($claveFinal)."','1','".filtro($_POST['sucursal'])."','"
				.filtro($_POST['turno'])."','".$_SESSION['id_usuario']."');";
        } else {
	        $sql = "update usuariorestaurante set idtrabajador='$_POST[idpersonarecibida]',clave='".filtro($claveFinal)
	            ."',login='".filtro($_POST['usuario'])
				."',idsucursal='".filtro($_POST['sucursal'])."',turno='".filtro($_POST['turno'])
				."',idusuariosistema='".$_SESSION['id_usuario']."' where idusuario='$_POST[identificador]'";	  	
        }
        $db->consulta($sql);
        header("Location: nuevo_usuario.php");
     } 
 
    if (isset($_GET['nro'])) {
        $transaccion = "modificar";
        $datosG = $db->arrayConsulta("select * from usuariorestaurante where idusuario=$_GET[nro]");  	 
    } else {
        $transaccion = "insertar";
    }

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="Nrestaurante.js"></script>
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
 });
</script>
</head>

<body>
 <div class="contendedor">
 
   <div class="tela_izq"></div>
   <div class="tela_cierreizq"></div>
   <div class="tela_der"></div>
   <div class="tela_cierreder"></div> 
   <div class="derechosReservados"><!--Copyright © Consultora Guez – Diseñado y Desarrollado-->
   </div>
   <div class="header"><div class="gradient7"><h1><span></span>Discoteca</h1></div>  </div>
   <div class="subTitulo">Nuestros Servicios al Alcance del Cliente.</div>
   
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
     <div id="opcion1" onclick="location.href='nuevo_reporte.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Reportes</div></div>
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
       <div class="tituloTransaccion">
         <div class="textoTituloTransaccion">Usuario Restaurante</div></div>
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
    <td><div id="textoConfiguracion" onclick="location.href='listar_usuario.php'" >Listar Usuario</div></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>
    </td>
  </tr>
  </table>
            
            
            
       <form id="formulario" name="formulario" method="post" action="nuevo_usuario.php">  
       <audio id="audio1" src="Jorge1.wav" controls preload="auto" autobuffer style="display:none">
</audio>
       <table width="92%" border="0" align="center">
  <tr>
    <td width="5%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="59%">&nbsp;</td>
    <td width="15%">
    <input type="button" value="Guardar"  class="botonrestaurante" onclick="getValidacionUsuario()" id="botonUsuario"/></td>
    <td width="16%"><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
  </tr>
</table>
   
       <table width="92%" border="0" align="center">
  <tr>
    <td></td>
    <td colspan="2">Los campos con <span class="rojo">(*)</span> son requeridos.</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">Tipo:</td>
    <td><select name="tipo" id="tipo" onchange="getPersonal(this.value)" <?php echo $atributo;?>>
      <option value="fijo">Fijo</option>
      <option value="apoyo">Apoyo</option>
      </select></td>
    <td colspan="2">&nbsp;</td>
    <td><input type="hidden" id="identificador" name="identificador" value="<?php echo $_GET['nro'];?>"/>
      <input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"/></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">Sucursal:<span class="rojo">*</span></td>
    <td><select id="sucursal" name="sucursal" class="required" style="width:135px;">
      <option value="">-- Seleccione --</option>
     <?php
         $sql = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1;";
         $db->imprimirCombo($sql,$datosG['idsucursal']);
     ?>
    </select><div id="Vsucursal" style="display:none;">Este campo es requerido.</div> </td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">Trabajador:<span class="rojo">*</span></td>
    <td>    
    <input style="width:90%" type="text" id="texto" onclick="this.select()" class="required" onkeyup="tipoBusqueda(event);" 
    value="<?php  
	if (isset($datosG['idusuario'])){	
	    $tipo = $datosG['tipo'];	
		if ($tipo == 'fijo') {
		   $sql = "select idtrabajador,left(concat(nombre,' ',apellido),25)as 'nombre' from trabajador"
		       ." where estado=1 and idtrabajador=$datosG[idtrabajador]";
		  } else {
		   $sql = "select idpersonalapoyo,concat(nombre,' ',apellido)as 'nombre' from personalapoyo"
		       ." where estado=1 and idpersonalapoyo=$datosG[idtrabajador];";
		  }		
	
	    $dato = $db->arrayConsulta($sql);
	    echo $dato['nombre'];
	
	}?>" autocomplete="off" <?php echo $atributo;?>/>
    
    <div id="cliente" class="divresultado"></div>
    <input type="hidden" id="idpersonarecibida" name="idpersonarecibida" value="<?php echo $datosG['idtrabajador'];?>" />
    <div id="Vtrabajador" style="display:none;">Este campo es requerido.</div>
    </td>
    <td colspan="2"><div id="autoL1" class="autoLoading"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">Turno:</td>
    <td><select name="turno" id="turno">
      <option value="AM">Día</option>
      <option value="PM">Noche</option>
    </select></td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">Usuario:<span class="rojo">*</span></td>
    <td><input type="text" name="usuario" id="usuario" class="required" minlength="4"  maxlength="20" 
    value="<?php echo $datosG['login'];?>"/>
    <div id="msjError" style="display:none;"></div>
    </td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">Contraseña:<span class="rojo">*</span></td>
    <td><input type="password" name="clave" id="clave" class="required" minlength="6" maxlength="20" value=""/>
    <div id="Vclave" style="display:none;">Este campo es requerido.</div>
    </td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="5%"></td>
    <td width="15%"></td>
    <td width="18%" align="right"></td>
    <td width="31%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="13%">&nbsp;</td>
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
  seleccionarCombo('tipo','<?php echo $datosG['tipo'];?>');
  seleccionarCombo('turno','<?php echo $datosG['turno'];?>');
</script>
