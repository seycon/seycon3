<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Navegador</title>
<link href='estiloslistado.css' rel='stylesheet' type='text/css' />
</head>
<body>
<script language="javascript">
function anular(){
if (confirm("Desea anular este cliente?")==false){
return false;
}else{
return true;
}
}
</script>
<table width='75%' border='0'> <tr><td bgcolor='#E2E2E2'>
<table width='100%' border='0'>
<tr style='background-image: url(fondotit.jpg);'>
<td align='center' ><strong class='letrastabla' style='font-size:20px;' >Informe</strong></td>
</tr>
</table>

<span class='letrastabla'>Buscar informe:</span><br /><input type='text' id='input'/><?php
echo "<select name='filtro' id='filtro'>";
include('bdlocal.php');
$consulta = 'SHOW FIELDS FROM informe';
$resultado = mysql_query($consulta) or die ( mysql_error() );
while($datos = mysql_fetch_array($resultado))
{
	echo "<option value= ".$datos['Field'].">".$datos['Field']."</option>";
}
echo '</select>';
?>
&nbsp;<input name='buscar' type='button' class='botongeneral' id='buscar' value='Buscar' onclick="location.href='listar_informe.php?abuscar='+document.getElementById('input').value+'&campo='+document.getElementById('filtro').value"/>
<input name='nuevo' type='button' class='botongeneral' id='nuevo' value='Nuevo' onClick="location.href='nuevo_informe.php'"/>
<input name='imprimir' type='button' class='botongeneral' id='imprimir' value='Imprimir' onClick="location.href='imprimir_informe.php'"/>
<input name='exportar' type='button' class='botongeneral' id='exportar' value='Exportar' onClick="location.href='excel_informe.php'"/>
<input name='buscarav' type='button' class='botongeneral' id='buscarav' value='Reportes' onClick="location.href='buscar_informe.php'"/>
<br />
<table width='100%' border='0' id='tabla' style='margin-top:5px;'>
<tr style='background-image: url(a.jpg);'>
<td width='695' >&nbsp;</td>
<td width='101'><img src='inicio.png' alt='inicio' /> ... <img src='fin.png'  alt='fin' /></td>
</tr>
</table>


<?php
include('includes/funciones.php');
include('bdlocal.php');
if ($_GET['abuscar']!='')
$condicion= " where ".$_GET['campo']." like '%".$_GET['abuscar']."%'";
else $condicion='';
$sql = 'SELECT * from informe'.$condicion.' order by idinforme desc';
mysql_query("SET NAMES 'utf8'");
$res = consulta($sql);
$n = mysql_num_rows($res);



if( $n > 0 ){
$nombres = array(  "idinforme", "titulo", "informe", "escritapor", "observacion", "fecha" );
echo "<table border='0'  align='center'>";
echo "<table width='100%' border='0' align='center'>";
echo "<thead><tr style='background-image: url(fondo.jpg); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;'>";
echo "<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>";

for( $i = 0 ; $i <=5; $i++ ){
if  ($i==0) echo '<th>Id</th>';else
echo "<th>".ucfirst($nombres[$i])."</th>";
}

$par=0;
while( $fila = mysql_fetch_row($res)){
if ($par%2!=0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";
$par++;	

	
$id = $fila[0];
echo "<td><div align='center'><a href='modificar_informe.php?idinforme=".$id."&sw=1' onclick='return modificar(this)'> <img src='css/images/edit.gif' alt='editar' border='0' /><br /></a></div></td>";
	echo "<td><div align='center'><a href='anular_informe.php?idinforme=".$id."' onclick='return anular(this)'><img src='css/images/borrar.gif' alt='borrar' border='0' /><br /></a></div></td>";
	echo "<td><div align='center'><a href='imprimir_informe.php?idinforme=".$id."' onclick='return imprimir(this)'><img src='css/images/imprimir.gif' alt='imprimir' border='0' /><br /></a></div></td>";
		for( $i = 0 ; $i <=5 ; $i++ ){
		if($fila[$i] == "")
		{
		echo "<td class='tdpar'>&nbsp;</td>";
		}
		else
		{
		echo "<td class='tdpar'>".$fila[$i]."</td>";
		}
	}
	echo "</tr>";
}
echo "</table>";
}
else{
echo "No se obtuvieron resultados";
}
?>






</tr></td></table>
</body>
</html>