<?php
    
	//////////////////////////////// BUSCAR FOTO //////////////////////////////////////////////////////////////////////////////////
	
     if(isset($_GET['direccion'])){
	   echo "<img src = $_GET[direccion] WIDTH=75 HEIGHT=78/>";
	   exit();
     }
	 
	 include('../conexion.php');
	 $db = new MySQL();
    

	
	///////////////////////////////// BUSCAR CLIENTE /////////////////////////////////////////////////////////////////////////
	
   if (isset($_POST['buscar'])){
		if ($_POST['buscar'] == "nombre"){
			$sql = "SELECT * FROM cliente WHERE nombre LIKE '".$_POST['cliente']."%' ORDER BY nombre LIMIT 10"; 
			$r = $db->consulta($sql);
			$i = 0;
			if (mysql_num_rows($r) > 0){
			echo "<ul id=\"navmenu-v\">";
			while($filas=mysql_fetch_array($r)){
				  echo "<li><a id=$i".time()." href='#' 
				       onclick=\"javascript:selectItemClient('".$filas["idcliente"]."','".$filas["nombre"]."', 
					   '".$filas["apellido"]."' )\">".$filas["nombre"]." ".$filas["apellido"]."</a></li>";	   
		$i++;
			}
		}
		}
    exit();
	
   }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	
	
	
?>
