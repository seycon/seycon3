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
 
    if (isset($_POST['transaccion'])) {
        $fechaIngreso = $db->GetFormatofecha($_POST['fechaingreso'],"/"); 	 
	    if($_POST['transaccion'] == "insertar"){
	        $dias = "$_POST[lunes]-$_POST[martes]-$_POST[miercoles]-$_POST[jueves]-
			$_POST[viernes]-$_POST[sabado]-$_POST[domingo]";  
	        $sql = "INSERT INTO personalapoyo(idpersonalapoyo,nombre,apellido,ci,fechaingreso,cargo"
	            .",diastrabajo,honorario,comision,diassemana,estado,idusuariosistema) VALUES" 
	            ."(NULL,'".filtro($_POST['nombre'])."','".filtro($_POST['apellido'])
	            ."','".filtro($_POST['ci'])."','$fechaIngreso','$_POST[cargo]'"
	            .",'$_POST[diastrabajados]','".filtro($_POST['honorario'])."','"
	            .filtro($_POST['comision'])."','$dias',1,'$_SESSION[id_usuario]');";
	    }
        else{
            $dias = "$_POST[lunes]-$_POST[martes]-$_POST[miercoles]-$_POST[jueves]-$_POST[viernes]-$_POST[sabado]-$_POST[domingo]";  
            $sql = "update personalapoyo set nombre='".filtro($_POST['nombre'])."',apellido='".filtro($_POST['apellido'])
			    ."',ci='".filtro($_POST['ci'])."',fechaingreso='$fechaIngreso',cargo='".filtro($_POST['cargo'])
				."',diastrabajo='$_POST[diastrabajados]',honorario='".filtro($_POST['honorario'])
                ."',comision='".filtro($_POST['comision'])."',diassemana='$dias',idusuariosistema='$_SESSION[id_usuario]' " 
                ." where idpersonalapoyo=$_POST[identificador];";	  
        }  
        $db->consulta($sql);  
        header("Location: nuevo_personalapoyo.php");
    }
 
    if (isset($_GET['nro'])) {
        $transaccion = "modificar";
        $datosG = $db->arrayConsulta("select * from personalapoyo where idpersonalapoyo=$_GET[nro]");
    }else{
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
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="../js/jquery.validate.js"></script>
<script>
 var $$ = function(id){
   return document.getElementById(id);	 
 }
 
 function validarFecha(value, element){  
    var Fecha= new String(value);   
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length));  
    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")));
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")));  
  
    if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
        return false;  
    }  
 
    if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
        return false;  
    }  

    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
        return false  ;
    }  
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
        if (Mes==2 && Dia > 28 || Dia>30) {  
            return false;  
        }  
    }      

  return true;   
}  

$.validator.addMethod("esfecha", validarFecha, "Fecha Invalida.");
 
 
 $(document).ready(function()
 {
	 
	$("#formulario").validate({});
	 
	$('#fechaingreso').datepicker({
    showOn: 'button',
    buttonImage: '../css/images/calendar.gif',
    buttonImageOnly: true,
    dateFormat: 'dd/mm/yy' });
 });
 
 var setValorCheck = function(id){
	if ($$(id).checked){
	  $$(id).value = "1";	
	}else{
	  $$(id).value = "0";	
	}
 }
 
 var seleccionarCombo = function(combo,opcion){	 
	 var cb = document.getElementById(combo);   
	 for (var i=0;i<cb.length;i++){
		if (cb[i].value==opcion){
		cb[i].selected = true;
		break;
		}
	 }	 
 }

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
         <div class="textoTituloTransaccion">Personal de Apoyo</div></div>
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
    <td><div id="textoConfiguracion" onclick="location.href='listar_personal.php'" >Listar Personal</div></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  </table>
            
       <form id="formulario" name="formulario" method="post" action="nuevo_personalapoyo.php">  
       
       <table width="92%" border="0" align="center">
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="5%">&nbsp;</td>
          <td width="56%">&nbsp;</td>
          <td width="14%"><input type="submit" value="Guardar" id="botonrestaurante"/></td>
          <td width="20%"><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
        </tr>
      </table>

          
       <table width="92%" border="0" align="center">
  <tr>
    <td></td>
    <td colspan="2">Los campos con <span class="rojo">(*)</span> son requeridos.</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"/></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">Nombre:<span class="rojo">*</span></td>
    <td align="left"><input type="text" name="nombre" id="nombre" class="required" value="<?php echo $datosG['nombre'];?>"/></td>
    <td align="right">Apellido:<span class="rojo">*</span></td>
    <td><input type="text" name="apellido" id="apellido" class="required" value="<?php echo $datosG['apellido'];?>"/></td>
    <td><input type="hidden" id="identificador" name="identificador" value="<?php echo $_GET['nro'];?>"/></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">C.I.:<span class="rojo">*</span></td>
    <td align="left"><input type="text" class="required number" maxlength="10" name="ci" id="ci" value="<?php echo $datosG['ci'];?>"/></td>
    <td align="right">Fecha Ingreso:</td>
    <td><input type="text" name="fechaingreso" size="10" id="fechaingreso" class="esfecha" value="<?php if (isset($datosG['fechaingreso'])) 
	echo $db->GetFormatofecha($datosG['fechaingreso'],'-'); else echo date("d/m/Y"); ?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td align="right">Días de Trabajo:</td>
    <td align="right">
	<?php	
        if (isset($datosG['diassemana'])) {
            $dias = $datosG['diassemana'];	 
        } else {
            $dias = '1-1-1-1-1-1-1';	
        }    
        $dias = explode('-',$dias);
    ?>
    
    <table width="97%" border="0">
      <tr height="10">
        <td width="16%"><div align="center">L</div></td>
        <td width="15%"><div align="center">M</div></td>
        <td width="13%"><div align="center">M</div></td>
        <td width="13%"><div align="center">J</div></td>
        <td width="13%"><div align="center">V</div></td>
        <td width="13%"><div align="center">S</div></td>
        <td width="17%"><div align="center">D</div></td>
      </tr>
      <tr>
        <td height="22"><input type='checkbox' onclick='setValorCheck(this.id)'  value='<?php if ($dias[0] == '1') echo 1;else echo 0;?>' 
        <?php if ($dias[0] == '1')  echo "checked='checked'";?>  id="lunes" name="lunes"  size="32" /></td>
        <td><input type='checkbox' onclick='setValorCheck(this.id)'  value='<?php if ($dias[1] == '1') echo 1;else echo 0;?>'  
        <?php if ($dias[1] == '1') echo "checked='checked'";?> id="martes" name="martes" size="32" /></td>
        <td><input type='checkbox' onclick='setValorCheck(this.id)'  value='<?php if ($dias[2] == '1') echo 1;else echo 0;?>'  
        <?php if ($dias[2] == '1') echo "checked='checked'";?> id="miercoles" name="miercoles" size="32" /></td>
        <td><input type='checkbox' onclick='setValorCheck(this.id)'  value='<?php if ($dias[3] == '1') echo 1;else echo 0;?>'  
        <?php if ($dias[3] == '1') echo "checked='checked'";?> id="jueves" name="jueves" size="32" /></td>
        <td><input type='checkbox' onclick='setValorCheck(this.id)'  value='<?php if ($dias[4] == '1') echo 1;else echo 0;?>'  
        <?php if ($dias[4] == '1') echo "checked='checked'";?> id="viernes" name="viernes" size="32" /></td>
        <td><input type='checkbox' onclick='setValorCheck(this.id)'  value='<?php if ($dias[5] == '1') echo 1;else echo 0;?>'  
        <?php if ($dias[5] == '1') echo "checked='checked'";?> id="sabado" name="sabado" size="32" /></td>
        <td><input type='checkbox' onclick='setValorCheck(this.id)'  value='<?php if ($dias[6] == '1') echo 1;else echo 0;?>'  
        <?php if ($dias[6] == '1') echo "checked='checked'";?> id="domingo" name="domingo" size="32" /></td>
      </tr>
    </table></td>
    <td align="right">Cargo:</td>
    <td><select id="cargo" name="cargo" style="width:135px;">
      <option value="guardia">Guardia</option>
      <option value="ayudante">Ayudante de Barra</option>
      <option value="garzon">Garzon</option>
    </select></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td align="right">Honorario:</td>
    <td align="left"><select name="honorario" id="honorario">
      <option value="m1">Modalidad 1</option>
      <option value="m2">Modalidad 2</option>
      <option value="m3">Modalidad 3</option>
    </select></td>
    <td align="right">Comisión Ventas:</td>
    <td><input type="text" name="comision" id="comision" class="number" value="<?php echo $datosG['comision'];?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td align="right"></td>
    <td align="left"></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td colspan="2"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="1%"></td>
    <td width="17%"></td>
    <td width="27%" align="right"></td>
    <td width="20%">&nbsp;</td>
    <td width="22%" align="center">&nbsp;</td>
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
  seleccionarCombo('honorario','<?php echo $datosG['honorario'];?>');
  seleccionarCombo('cargo','<?php echo $datosG['cargo'];?>');
</script>

