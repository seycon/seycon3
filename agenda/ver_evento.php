<?


			
			
include('../conexion.php');
$bd = new MySQL();

			 function imprimirAgenda($sql){
				$result = $bd->consulta($sql);
				
				while ($row = $bd->getRow($result)){		
				           $variables = $row[0]."&abuscar=".$row[0]."&campo=idevento";		   
						   echo "\t<td>".$row[1]."</td>\n";
						   echo "\t<td>".substr( $row[2], 0, 35 )." 
						   <a href='../listar_evento.php?$variables' target='_blank' class='leermas'> Leer mas...</a></td>\n";										
                            return $row[0];
				}
				
			}
			

if ($_GET['id']){
	$sql = "delete from evento where idevento='$_GET[id]'";
	$bd->consulta($sql);
}



//list($dia, $mes, $anio, $hr, $min) = explode('-', date('d-m-y-G-i', $_GET['fecha']));
$v = explode('-',  $_GET['fecha']);
	echo "<br><br><table width=100% border=0 align=center>
	       <tr bgcolor=#E8FFE8>
		      <th>Hora</th>
		      <th width='200'>Titulo</th>
			  <th>Descripcion</th>
			  <th colspan=2>Opciones</th>
		   </tr>";
    for ($i=7;$i<21;$i++){
	        $sql = "select idevento, titulo,descripcion from evento where 
			EXTRACT(DAY FROM inicio) = '$v[2]' AND EXTRACT(MONTH FROM inicio) = '$v[1]' 
			AND EXTRACT(YEAR FROM inicio) = '$v[0]' AND EXTRACT(HOUR FROM inicio) = '".$i."'";	
	     if (mysql_num_rows($bd->consulta($sql))>0){
			 echo "<tr bgcolor=#FFE7CE>";
			echo "<td>".$i.":00</td>"; 	
			$id = imprimirAgenda($sql);		
		 } else {
			 echo "<tr bgcolor=#F7F7F7>";
			  echo "<td>".$i.":00</td>
		      <td>&nbsp;</td>

			  <td>&nbsp;</td>";
			 
		 }
		  echo "\t<td><a href='../modificar_evento.php?idevento=$id&sw=1' target='_blank'><img src='icon_edit_s.png'></a></td>\n";
		  echo "\t<td><img style='cursor:pointer' src='icon_cross.png' onclick=\"eliminarEvento('$id','derecha')\"></td>\n";
	echo "</tr>";	

}

	echo "</table>";
/*


list($dia, $mes, $anio, $hr, $min) = explode('-', date('d-m-y-G-i', $fecha));


/*
echo ("Día: " . $fecha["mday"]."<br>");
echo ("Mes: " . $fecha["mon"]."<br>");
echo ("Año: " . $fecha["year"]."<br>");
echo ("Hora: " . $fecha["hours"]."<br>");
echo ("Minutos: " . $fecha["minutes"]."<br>");
echo ("Segundos: " . $fecha["seconds"]."<br>");
*/


//$fecha = getdate(); 

?>
