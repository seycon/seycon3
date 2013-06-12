<?php
	/*require_once('usuario/DUsuario.php');
	$usuario= new class_usuario();
	if(false){
		$usuario->guardar();
	}*/
	$transacion=(isset($_GET['idusuario']))?'modificar':'insertar';
	if($transacion=='modificar'){
		include("conexion.php");
		$db = new MySQL();
		$sql="Select * from usuario where idusuario={$_GET['idusuario']}";
		$usuario=$db->arrayConsulta($sql);
		
	}
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
				<h2>Registro Usuario</h2>				
				<form method="post" action="usuario/DUsuario.php?transaccion=<?php echo $transacion;?>"><!-- Form -->
				<!-- Fieldset -->
				<fieldset><legend><!--This is a simple fieldset--></legend>
					<div class="input_field">
						<label for="sf">Nombre: </label>
					  <input class="bigfield" name="nombre" type="text" value="<?php echo (isset($usuario))?$usuario['nombre']:'';?>" maxlength="100"/>
 					   <input type="hidden" name="idusuario" value="<?php echo(isset($_GET['idusuario']))?$_GET['idusuario']:'0';?>">	
					</div>
					
					<div class="input_field">
						<label for="mf">Nick: </label>
					  <input class="smallfield" name="nick" type="text" value="<?php echo (isset($usuario))?$usuario['nick']:'';?>" maxlength="50"/> 
					</div>
					
				  <div class="input_field">
					<label for="lf">Pasword: </label>
					<input class="smallfield" name="password" type="password" value="" maxlength="50"/>
					</div>
					<div class="input_field">

					  <label for="dropdown">Permiso: </label>
						<select name="permiso" class="dropdown">
						  <option value="1" <?php echo (isset($usuario))?(($usuario['permiso']==1)?' selected="selected" ':''):''; ?> >Administrador</option>
						  <option value="2" <?php echo (isset($usuario))?(($usuario['permiso']==2)?' selected="selected" ':''):''; ?>>Usuario</option>
                        </select>
				  </div>
					<div class="input_field no_margin_bottom">
						<input class="submit" type="submit" value="Aceptar" />
						<input class="submit" type="reset" value="Limpiar" />
						<a href="listar_usuario.php" class="button">Salir</a>
				  </div>
				</fieldset>
				<!-- End of fieldset -->
		  </form>
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