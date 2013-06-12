<?php
    include("conexion.php");
    $db = new MySQL();  
	session_start();
    if (!isset($_SESSION['userID'])) {
       header("Location: index.php");	
	}
	
	function insertarFila($nro, $descripcion, $id) 
	 {
		if ($nro % 2 == 0) {
			$clase = "background-color:#ECECEC;";	
		} else {
			$clase = "";
		}
		
		echo "<tr>
	  <td  class='filaTableI' style='$clase' align='center'>$nro</td>
	  <td  class='filaTable' style='$clase'>$descripcion</td>
	  <td  class='filaTableF' style='$clase' align='center'><a href='listar_menu.php?plantilla=$id'>
	  <img src='img/user_edit.png' alt='Modificar' title='Modificar' border='0' onclick='goEliminar($id)'  class='optionref'/>
	  </a>
	  </td>
	</tr>"; 
	 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Panel Administrativo</title>
<link rel="stylesheet" type="text/css" href="styles/style.css" />
</head>

<body>

<div class="head"> 

 <div class="acopleCabecera">
  <div class="logeo">
  <a href="cerrar.php">
    <div class="imagenLogeo"></div>
    <div class="textoLogeo">Salir</div>
  </a>
  </div>  

   <div class="tituloPrincipal">Panel Administrativo</div>
   <div class="tituloSecundario">Gestión y Configuración de Paginas Web.    </div>
   <div class="tituloUsuario"> Bienvenido <span style="color:#666"><?php echo $_SESSION['userName'];?></span></div>
   
   <div class="panelOpciones">
     <div class="opcionesPanel">   
            <div class="opcion1"><a href="admingeneral.php">
            <div class="icono"><img src="img/icon_dashboard.png" width="48" height="48"/></div>
            <div class="titulo">Inicio</div> </a> </div>
            <div class="opcion2"><a href="nuevo_usuario.php">
            <div class="icono"><img src="img/icon_users.png" width="48" height="48"/></div>
            <div class="titulo">Usuario</div></a>  </div>
            
     </div>
   </div>
   </div>
   
   
 </div>
<div class="contem">
   <div class="titleFrom">
      <div class="imagenFrom"><img  src="img/icon_dashboard_small.gif" width="26" height="26"/> </div>
      <div class="nombreFrom">Inicio</div>
   </div>
   <div class="titleCaso">
      <div class="optionSubMenu">Plantillas</div>
   </div>
   
   <div class="contemDataFrom">
     <br />
     <table width="85%" border="0" align="center">
      <tr>
        <td class="parrafoBienvenida"><span style="font-size:16px;color:#000;"> Bienvenido usuario</span>. Te damos la bienvenida al panel administrativo donde puedes realizar la configuración general de la página web, definiendo cada una de las características con las que contara tu página. A continuación te mostramos un listado de las plantillas a las que tienes acceso y  puedes configurar.  </td>
      </tr>
    </table>
     <br />
     
      <table border="0" width="80%" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<th width="136"  class="table-header-check">Nº</th>
					<th width="371"  class="table-header-checkI">Descripción</th>
					<th width="213"  class="table-header-checkF">Opciones</th>
				</tr>
                
                <?php
				 $sql = "select * from plantilla where idplantilla = $_SESSION[IDplantilla];";
				 $dato = $db->consulta($sql);
				 $nro = 0;
				 while ($data = mysql_fetch_array($dato)) {
					$nro++; 
					insertarFila($nro, $data['nombre'], $data['idplantilla']); 
				 }
				
				?>
                
				
      </table>
   
   </div>
</div>

<div class="pie">
    <div class="acoplePie">
    <div class="autor">Copyright © Consultora Guez – Diseñado y Desarrollado</div>
    </div>
</div>

</body>
</html>