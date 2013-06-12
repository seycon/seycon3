<?php
session_start();
  include('conexion.php');  
  $db = new MySQL();   
?>
<?php
$consulta="select * from proyecto where proyectoterminado=0 and estado=1; ";
$consulta= mysql_query($consulta);
 $i = 0;
 
 while($datos=mysql_fetch_array($consulta)){
	if ($i==0){ 
    echo "<table width='100%' border='0' align='center' style='min-width:638.2px'>";
    echo  "<tr>"; 
    echo "<td class='color1'><center>Proyectos</center></td>";
    echo "  </tr>";
    echo "</table>";
	echo "<table width='100%' border='0' align='center'>";
    echo "<tr>";
    echo "<td width='7%'class='color'>ID Proyecto</td>";
    echo "<td width='15%'class='color'>Titulo</td>";
    echo "<td width='15%'class='color'>Fecha Inicio</td>";
    echo "<td width='13%'class='color'>Fecha Finalización</td>";
    echo "<td width='12%'class='color'>Responsable</td>";
    echo "<td width='18%'class='color'>Trabajar Con</td>";
    echo "<td width='17%'class='color'>Presupuesto</td>";
	echo "<td width='3%'class='color'></td>";
    echo "</tr>";
	}
	$i=1;
  
      echo "<tr>";
      echo "<td>$datos[idproyecto]</td>";
      echo "<td>$datos[titulo]</td>";
      echo "<td>$datos[fechaincio]</td>";
      echo "<td>$datos[fechafinalizacion]</td>";
      echo "<td>$datos[responsable]</td>";
	  echo "<td>$datos[trabajarcon]</td>";
	  echo "<td>$datos[presupuesto]</td>";
	  echo "<td><a href='modificar_proyecto.php?idproyecto=".$datos['idproyecto']."&sw=1'><img src='css/images/edit.gif' title='Modificar' border='0'/></a> </td>";
      echo "</tr>";
}

if ($i>0)
echo "</table>";

echo "<br>";
echo "<br>";



 $sql="select * from tarea where estado=1 and tareaterminada=0;";
 $consulta = $db->consulta($sql);
 $i = 0;
 
 
 
 while($datos=mysql_fetch_array($consulta)){
	if ($i==0){ 
    echo "<table width='100%' border='0' align='center' style='min-width:563.2px'>";
    echo  "<tr>"; 
    echo "<td class='color1'><center>Tareas</center></td>";
    echo "  </tr>";
    echo "</table>";
	echo "<table width='100%' border='0' align='center'>";
    echo "<tr>";
    echo "<td width='7%'class='color'>ID Tarea</td>";
    echo "<td width='15%'class='color'>Titulo</td>";
    echo "<td width='15%'class='color'>Fecha Inicio</td>";
    echo "<td width='13%'class='color'>Fecha Finalización</td>";
    echo "<td width='12%'class='color'>Asignado por</td>";
    echo "<td width='18%'class='color'>Trabajar Con</td>";
    echo "<td width='17%'class='color'>Tarea Pendiente</td>";
    echo "<td width='3%'class='color'></td>";
    echo "</tr>";
	}
	$i=1;
  
      echo "<tr>";
      echo "<td>$datos[idtarea]</td>";
      echo "<td>$datos[titulo]</td>";
      echo "<td>$datos[fechaincio]</td>";
      echo "<td>$datos[fechafinalizacion]</td>";
      echo "<td>$datos[asignadopor]</td>";
	  echo "<td>$datos[trabajarcon]</td>";
	  echo "<td>$datos[tareapendiente]</td>";
      echo "<td><a href='modificar_tarea.php?idtarea=".$datos['idtarea']."&sw=1'><img src='css/images/edit.gif' title='Modificar' border='0'/></a> </td>";
      echo "</tr>";
}

if ($i>0)
echo "</table>";

echo "<br>";
echo "<br>";



$consulta="select *from evento where fecha>now() and estado=1;";
$consulta= mysql_query($consulta);
 $i = 0;
 
 while($datos=mysql_fetch_array($consulta)){
	if ($i==0){ 
    echo "<table width='95%' border='0' align='center' style='min-width:567px'>";
    echo  "<tr>"; 
    echo "<td class='color1'><center>Actividades</center></td>";
    echo "  </tr>";
    echo "</table>";
	echo "<table width='95%' border='0' align='center'>";
    echo "<tr>";
    echo "<td width='7%'class='color'>ID Evento</td>";
    echo "<td width='15%'class='color'>Titulo</td>";
    echo "<td width='15%'class='color'>Fecha </td>";
    echo "<td width='13%'class='color'>Fecha Inicio</td>";
    echo "<td width='12%'class='color'>Fecha Finalización</td>";
    echo "<td width='18%'class='color'>Lugar</td>";
    echo "<td width='17%'class='color'>Fecha Record</td>";
	echo "<td width='3%'class='color'></td>";
    echo "</tr>";
	}
	$i=1;
  
      echo "<tr>";
      echo "<td>$datos[idevento]</td>";
      echo "<td>$datos[titulo]</td>";
      echo "<td>$datos[fecha]</td>";
      echo "<td>$datos[inicio]</td>";
      echo "<td>$datos[fin]</td>";
	  echo "<td>$datos[lugar]</td>";
	  echo "<td>$datos[fecharecord]</td>";
	   echo "<td><a href='modificar_evento.php?idevento=".$datos['idevento']."&sw=1'><img src='css/images/edit.gif' title='Modificar' border='0'/></a> </td>";
      echo "</tr>";
}

if ($i>0)
echo "</table>";


echo "<br>";
echo "<br>";



$consulta="select *from cuentaporcobrar where fecha>now() and estado=1;";
$consulta= mysql_query($consulta);
 $i = 0;
 
 while($datos=mysql_fetch_array($consulta)){
	if ($i==0){ 
    echo "<table width='95%' border='0' align='center' style='min-width:419px'>";
    echo  "<tr>"; 
    echo "<td class='color1'><center>Cuentas por Cobrar</center></td>";
    echo "  </tr>";
    echo "</table>";
	echo "<table width='95%' border='0' align='center'>";
    echo "<tr>";
    echo "<td width='12%'class='color'>ID por Cobrar</td>";
    echo "<td width='20%'class='color'>Deudor</td>";
    echo "<td width='25%'class='color'>Responsable</td>";
    echo "<td width='22%'class='color'>Monto</td>";
    echo "<td width='18%'class='color'>Fecha</td>";
	echo "<td width='3%'class='color'></td>";
    echo "</tr>";
	}
	$i=1;
  
      echo "<tr>";
      echo "<td>$datos[idporcobrar]</td>";
      echo "<td>$datos[deudor]</td>";
      echo "<td>$datos[responsable]</td>";
      echo "<td>$datos[monto]</td>";
      echo "<td>$datos[fecha]</td>";
	  echo "<td><a href='modificar_cuentaporcobrar.php?idporcobrar=".$datos['idporcobrar']."&sw=1'><img src='css/images/edit.gif' title='Modificar' border='0'/></a> </td>";
      echo "</tr>";
}

if ($i>0)
echo "</table>";


echo "<br>";
echo "<br>";

$consulta="select *from cuentaporpagar where fecha>now() and estado=1;";
$consulta= mysql_query($consulta);
 $i = 0;
 
 while($datos=mysql_fetch_array($consulta)){
	if ($i==0){ 
    echo "<table width='95%' border='0' align='center' style='min-width:42px'>";
    echo  "<tr>"; 
    echo "<td class='color1'><center>Cuentas por Pagar</center></td>";
    echo "  </tr>";
    echo "</table>";
	echo "<table width='95%' border='0' align='center'>";
    echo "<tr>";
    echo "<td width='12%'class='color'>ID por Pagar</td>";
    echo "<td width='20%'class='color'>Acreedor</td>";
    echo "<td width='25%'class='color'>Responsable</td>";
    echo "<td width='22%'class='color'>Monto</td>";
    echo "<td width='18%'class='color'>Fecha</td>";
	echo "<td width='3%'class='color'></td>";
    echo "</tr>";
	}
	$i=1;
  
      echo "<tr>";
      echo "<td>$datos[idporpagar]</td>";
      echo "<td>$datos[acreedor]</td>";
      echo "<td>$datos[responsable]</td>";
      echo "<td>$datos[monto]</td>";
      echo "<td>$datos[fecha]</td>";
  	  echo "<td><a href='modificar_cuentaporpagar.php?idporpagar=".$datos['idporpagar']."&sw=1'><img src='css/images/edit.gif' title='Modificar' border='0'/></a> </td>";
      echo "</tr>";
}

if ($i>0)
echo "</table>";

?>

