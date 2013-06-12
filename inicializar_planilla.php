<?php
	include_once('bdlocal.php');
   

    $consulta = "select idplanilla from planilla where month(fecha)=". $_POST['meses']." and year(fecha)=".	$_POST['anio'].";";
	$sql = mysql_query($consulta);
	if (mysql_num_rows($sql)<1){   
	
          $error = "";
		  $fecha= $_POST['anio']."/".$_POST['meses']."/".date("d");
		  
		  
		  $consulta = "select *from trabajador;";
		  $result = mysql_query($consulta);	  
		  
		  while($row = mysql_fetch_array($result)){
			 $idtrabajador = $row['idtrabajador'];	   
			 $consultaplanilla = "insert into planilla values(null,$idtrabajador,'".$fecha."',30,0,0,0,0,0,'',(select iddatosplanilla from datosplanilla where estado=1),1);";
			 mysql_query($consultaplanilla);	
		  }
	}
	//echo $error;
	



?>