<?php
	include('aumentaComa.php');
	include('conexion.php');
	
	$db = new MySQL();
	$sql = 'SELECT * FROM usuario WHERE estado=1';
	mysql_query("SET NAMES 'utf8'");
	$res = $db->consulta($sql);
	$n = mysql_num_rows($res);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Black Admin v2 - Template Admin - Nice Theme</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css" media="all">
		@import url(css/style.css);
		img {behavior:url('js/iepngfix.htc') !important;}
	</style>	
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/jquery_ui.js" type="text/javascript"></script>
	<script src="js/wysiwyg.js" type="text/javascript"></script>
	<script src="js/functions.js" type="text/javascript"></script>

</head>
<body>
<div id="container"> <!-- Container -->
	<div id="header"> <!-- Header -->
		<div id="title">
		Panel Administrador</div>
		<div class="logged">
			<p>Good morning, <a href="#" title="">Administrator</a>!</p>
			<p><a href="#">Mi cuenta</a> | <a href="index.php" >Salir</a></p>
			<p>&nbsp;</p>
		</div>
	</div>	
	<div id="sidebar"> <!-- Sidebar -->
	  <div class="sidebox">
		  <span class="stitle">Calendario</span>
	    <div id="datepicker"></div>

	  </div>
  </div> <!-- END Sidebar -->
	<div id="main" style=" min-height:600px;"> <!-- Main, right side content -->
	  <div id="content"> <!-- Content begins here -->
				<ul class="modals">
					<li>
						<a href="registrar_usuario.php">
							<img src="iconsee/pencil_48.png" alt="Write an article" />
							<span>Nuevo Usuario</span>
						</a>
					</li>
					<li>
						<a href="registrar_pagina.php">
							<img src="iconsee/diagram_48.png" alt="View statistics" />
							<span>Nueva Pagina</span>
						</a>
					</li>
					<li>
						<a href="registrar_menu.php">
							<img src="iconsee/letter_48.png" alt="Check inbox" />
							<span>Nuevo Menu</span>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="iconsee/clipboard_48.png" alt="To-do list" />
							<span>Todo list</span>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="iconsee/gear_48.png" alt="Settings" />
							<span>Settings</span>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="iconsee/save_48.png" alt="Save this!" />
							<span>Save this!</span>
						</a>
					</li>
				</ul>
				<h2>Listar Usuario</h2>
                <table cellspacing="0" cellpadding="0" border="0"><!-- Table -->
					<thead>
						<tr>
							<th>No</th>
							<th>Nombre</th>
							<th>Nick</th>
							<th>Fecha</th>
							<th>Actions</th>
						</tr>
					</thead>
				
					<tbody>
                    <?php 
						$i=1;
						while( $fila = mysql_fetch_row($res)){
							
							$clase=($i%2==0)?'class="alt"':'';
							echo '<tr '.$clase.'>
								  <td>'.$i.'</td>
								  <td>'.$fila[1].'</td>
								  <td>'.$fila[2].'</td>
								  <td>'.$fila[4].'</td>
								  <td><a href="registrar_usuario.php?idusuario='.$fila[0].'"><img src="assets/action_add.png" alt="Add" /></a><a href="usuario/DUsuario.php?transaccion=eliminar&idusuario='.$fila[0].'"><img src="assets/action_delete.png" alt="Delete" /></a></td>
								</tr>';
							$i++;	
						}
					?>	
						
					</tbody>
				</table>
                <center>
               <!-- <ul class="paginator">
					<li><a href="#">Previous</a></li>
					<li class="current"><a href="#"> Paginas 1 de 5</a></li>					
					<li><a href="#">Next</a></li>
				</ul>
                </center>-->
		<!-- END Table -->
		<h2>&nbsp;</h2>
		<h2>&nbsp;</h2><!-- /Form -->

		  <!-- The paginator --><!-- Paginator end -->
		</div> 
		<!-- END Content -->

	</div> 	
		<div id="footer"> 
			<p>&nbsp;</p>
		</div>	
</div> <!-- END Container -->
</body>
</html>