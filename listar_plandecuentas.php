<?php
include_once('conexion.php');
$db = new MySQL();

if (!isset($_SESSION['softLogeoadmin'])){
 header("Location: index.php");	
}
$estructura = $_SESSION['estructura'];
if (!$db->tieneAccesoFile($estructura['Contabilidad'],'Plan de Cuenta','listar_plandecuentas.php')){
 header("Location: cerrar.php");	
}

if (isset($_POST['idplan'])){
  if ($_POST['idplan'] == 0){
    $sql = "INSERT INTO plandecuenta
    (idplandecuenta,codigo,cuenta,nivel,moneda,referencia,valor,descripcion,auxiliar,estado) VALUES (NULL,'".$_POST['codigo']."','".$_POST['cuenta']."','".$_POST['nivel']."','".  $_POST['moneda']."','".$_POST['referencia']."','".$_POST['valor']."','".$_POST['descripcion']."','".$_POST['auxiliar']."',1);";	
  }else{
	$sql = "update plandecuenta set codigo='$_POST[codigo]',cuenta='$_POST[cuenta]',moneda='$_POST[moneda]' where idplandecuenta='$_POST[idplan]'";	
  }
  mysql_query($sql);
  header("location: listar_plandecuentas.php");
}


function calculaespacio($nivel){
       $espacio="&nbsp;";
	   for ($i=1;$i<$nivel;$i++)
	           $espacio.=$espacio;	
		return $espacio;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PLAN DE CUENTAS</title>
<link href='plandecuentas/plandecuentas.css' rel='stylesheet' type='text/css' />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<script src="plandecuentas/plandec.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
</head>
<body onload="quitarMas(document.getElementById('101')); on();">
<script language="javascript">

function anular(){
	if (confirm("Desea anular este elemento de la tabla plandecuenta?")==false){
		return false;
    }else{
		return true;
	}
}

function estado() {
  if (document.getElementById('estado').checked){
	  is = 1;
	  recorrer(document.getElementById('101'));
	  recorrer(document.getElementById('101'));
  } else {
	  is = 0;
	  recorrer(document.getElementById('101'));
	  recorrer(document.getElementById('101'));
  }
}

</script>



<div id="overlay" class="overlays"></div> 

<div id="modal" class="contenedorF1">
<div class="modal_interiorF1"></div>
 <div class="modal">
    <form action="listar_plandecuentas.php" id="formulario" method="post" >
      <table width="100%" border="0">
      <caption class="textotitulo">                
           <strong><div id="tituloTransaccion" class="tituloF1"></div></strong>
           <div class="posicionClose">
           <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer"  onclick="ocultar();"></div>
       </caption>
       <input name="nivel" type='hidden'  id="nivel" size="15" />
       <input name="idplan" type='hidden'  id="idplan" size="15" value="-1"/>
       <input name="haspost" type='hidden'  id="haspost" size="15" />
        <tr>
          <td align='right' valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
          <td  align='right' valign='top'>&nbsp;</td>
          <td valign='top'>&nbsp;</td>
        </tr>
        <tr>
          <td width="21%" align='right' valign='top'>Codigo:</td>
          <td width="25%" valign='top'><input name="codigo" type='text'  class="required" id="codigo" 
                                        onfocus="document.getElementById('cuenta').focus()" size="15" /></td>
          <td width="18%"  align='right' valign='top'>Moneda<span class='rojo'></span>:</td>
          <td width="36%" valign='top'>
          <select name="moneda" id="moneda">
           <option value='Bolivianos' selected='selected'>Bolivianos</option>
           <option value='Dolares'>Dolares</option>
          </select>
          </td>
        </tr>
        <tr>
          <td align='right' valign='top'>Cuenta:</td>
          <td valign='top'>
          <input name="cuenta" type='text'  class="required" id="cuenta" size="15" onkeyup="eventoText(event);"/></td>
          <td  align='right' valign='top'>
          </td>
          <td valign='top'></td>
        </tr>
      </table>
      
      <div class="boton1_subventana"><input  type='button' class='botonNegro' onclick="has();" value='Aceptar'/></div>
      <div class="boton2_subventana"><input type='button' class='botonNegro' value='Cancelar' onclick="ocultar()"/></div>
    </form>
 </div>
</div>


 <div id="modal_mensajes" class="contenedorMsgBox">
  <div class="modal_interiorMsgBox"></div>
  <div class="modalContenidoMsgBox">
      <div class="cabeceraMsgBox">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBox">ADVERTENCIA</div>
        <div class="modal_cerrarMsgBox">
         <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
      </div>
      <div class="contenidoMsgBox">
        <div class="modal_ico1MsgBox"><img src="iconos/alerta.png" width="28" height="28"></div>
        <div class="modal_datosMsgBox" id="modal_contenido">Debe Seleccionar un Almacén de Origen.</div>
        <div class="modal_boton1MsgBox">
        <input type="button" value="Cancelar" class="botonNegro" onclick="closeMensaje()"/></div>
        <div class="modal_boton2MsgBox" id="mb2">
        <input type="button" value="Aceptar" class="botonNegro" onclick="eliminarTransaccion()"/></div>
      </div>
  </div>
 </div>





<div id="modal_vendido" class="contenedorMsgBoxOption">
  <div class="modal_interiorMsgBoxOption"></div>
  <div class="modalContenidoMsgBoxOption">
      <div class="cabeceraMsgBoxOption">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBoxOption">Opciones del Sistema</div>
        <div class="modal_cerrarMsgBoxOption">
         <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="cerrarPagina()"></div>
      </div>
      <div class="contenidoMsgBoxOption">
        <div class="modal_datosMsgBoxOption" id="modal_contenido"> Seleccione los parámetros para 
        poder visualizar el reporte.  </div>
         <table width="311" style="margin-top:40px;" align="center">
            <tr>            
            <td><input type="button" value=" Ver Reporte " onclick="accionPostRegistro();" class="botonNegro"/></td>
            <td align="right">Logo:</td>
            <td><input type="checkbox" name="logo" id="logo" checked="checked"/></td>
            </tr>
         </table>   
        
      </div>
  </div>
</div>








<div class="contenido">

<div class="cabeceraReporte">
    <div class="tituloPlan">PLAN DE CUENTAS</div>
    <div class="modal_cerrar" onclick="closeVentana()">
    <img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" ></div>
</div>
<table width='100%' align="center" border='0' cellpadding="0" cellspacing="0" > 
<tr >
<td align='center' >


</td>
</tr>

<tr>
  <td bgcolor='#FFF'><table width='100%' border='0' id='tabla' style='margin-top:5px;'>
  <tr style='background-image: url(a.jpg);'>
<td width='305' align="right" >
  <?php if (isset($_GET['estado']) && ($_GET['estado'] == 0))  echo ' Registros con estado inactivo ';?>
  <input type="hidden" name="idDelete" id="idDelete" />
  <input type="hidden" name="idHas" id="idHas" />
  
  </td>
<td width='337' align="right"><span style="font-family: Verdana, Geneva, sans-serif;font-size:12px;">Ver/Ocultar Auxiliares 
  <input type='checkbox'  title= 'Click Aqui para ver solo los registros activos'
<? if (isset($_GET['estado']) && ($_GET['estado'] == 1))  echo ' checked ';?>  name='estado' id='estado'  onclick='contraerDescontraer();' />
</span></td>
<td width='103' align="right" style='font-family: Verdana, Geneva, sans-serif;font-size:12px;'><input name='imprimir' type='button' class='botonNegro' id='imprimir' value='Imprimir' onclick="openVentanaImprimir()"/></td>

<td width='21'></td>
</tr>
</table>


<?php
include('includes/funciones.php');

$sql = 'SELECT idplandecuenta,codigo,cuenta,moneda,nivel from plandecuenta where estado = 1 order by codigo,referencia asc';
mysql_query("SET NAMES 'utf8'");
$res = consulta($sql);
$n = mysql_num_rows($res);



if( $n > 0 ){
$nombres = array( "", "código", "cuenta", "moneda", "" );
echo "<table border='0' width='100%'  align='center'>";
echo "<table width='100%' border='0' align='center' id='objetotabla'>";
echo "<tr class='cabeceraInicialListar'>";
echo "<th>&nbsp;</th><th>&nbsp;</th>";

echo "<th class='ocultar'>id</th>";
echo "<th width='170px'>Código</th>";
echo "<th >Cuenta</th>";
echo "<th >Moneda</th>";
echo "<th></th><th>&nbsp;</th></tr>";


$par=0;
$id_img = 1000;
$fila_id = 100;
while( $fila = mysql_fetch_row($res)){
	$fila_id++;

if ($par%2!=0) echo "<tr id='".$fila_id."' class='fondoCelda'>"; else echo "<tr id='".$fila_id."' class='fondoCelda'>";
$par++;	
$id = $fila[0];
$id_img++;

echo "<td class='fondoCelda'><div align='center'><a href="."'#".$fila_id."'"." onclick=\"toggle((this.parentNode.parentNode.parentNode),'$id_img');\">
     <img id='$id_img' src='images/arrowarriba.png' title='Modificar' alt='editar' border='0' /><br /></a></div></td>";
if ($fila[6] !=7){
	                                   
echo "<td>
         <div align='center' class='fondoCelda'>
		    <a href='listar_plandecuentas.php' onclick= \"mostarInsert(this.parentNode.parentNode.parentNode,'insertar'); return false;\"> 
               <img src='images/mas.png'  border='0' />
			   <br />
			 </a>
		  </div>
	  </td>";
}

		for( $i = 0 ; $i <=3 ; $i++ ){
			if ($fila_id%2!=0){
			  $clase = "fondoCelda";	
			}else{
			  $clase = "fondo2";	
			}
			
		
			if($fila[$i] == "")
			   echo "<td class='$clase' id='$i'>&nbsp;</td>";
			else
			{
				switch($i){
				case 0:
				 echo "<td class='ocultar'>".$fila[$i]."</td>";	
				break;
				case 2:
				  echo "<td class='$clase'>".calculaespacio($fila[4]).$fila[$i]."</td>";
				break;

				case 3:
				  echo "<td class='centro'>".$fila[$i]."</td>";
				break;
				default:
				  echo "<td class='$clase'>".$fila[$i]."</td>";	
				}
			}
			$hasc = $fila_id;
			if (strlen($fila[1]) == 2 && $i == 5){				 
			   echo "<script>
			             elemento = document.getElementById('$fila_id');
			            document.getElementById('$fila_id').className='plancuenta';
						/*if (elemento.addEventListener)
						{
						elemento.addEventListener('click', function(){toggle(document.getElementById('$fila_id'),'$id_img')}, false);
						}
						else
						{
						elemento.attachEvent('onclick', function(){toggle(document.getElementById('$fila_id'),'$id_img')});
						}*/
					 </script>";
			}
	}

	echo "<td class='fondoCelda'><div align='center' class='cursorDelete'><img src='css/images/borrar.gif' title='Anular' alt='borrar' border='0' onclick='openMensaje($id,$hasc)'/><br /></div></td>";
	echo "<td class='fondoCelda'><div align='center' class='fondoCelda'>
	            <a href='modificar_plandecuenta.php?idplandecuenta=".$id."&sw=1&has=$hasc'
	      onclick=\"mostarInsert(this.parentNode.parentNode.parentNode,'modificar'); return false;\"> <img src='css/images/edit.gif' title='Modificar' alt='editar' border='0' /></a></div></td>";



}
	
echo "</table>";
}
else{
echo "No se obtuvieron resultados";
}
?>

</tr></td></table>
<br />
</div>
</body>
</html>