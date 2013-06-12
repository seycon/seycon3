<?php
	/*require_once('usuario/DUsuario.php');
	$usuario= new class_usuario();
	if(false){
		$usuario->guardar();
	}*/
	$transacion=(isset($_GET['idpagina']))?'modificar':'insertar';
	if($transacion=='modificar'){
		include("conexion.php");
		$db = new MySQL();
		$sql="Select * from pagina where idpagina={$_GET['idpagina']}";
		$pagina=$db->arrayConsulta($sql);
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Black Admin v2 - Template Admin - Nice Theme</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	

<!--Libreria Editor-->    
    <script type="text/javascript" src="js/tiny_mce.js"></script>
<script type="text/javascript">

	tinyMCE.init({
		mode : "exact",
		elements : "pie",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "black",
		
	});
</script>

<!--Libreria Subir imagenes-->


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
						<a href="#">
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
				<h2>Registro Pagina</h2>				
				<form method="post" action="pagina/Dpagina.php?transaccion=<?php echo $transacion;?>"><!-- Form -->
				<!-- Fieldset -->
				<fieldset><legend><!--This is a simple fieldset--></legend>
					<div class="input_field">
						<label for="sf">Dominio: </label>
					  <input class="bigfield" name="dominio" type="text" value="<?php echo (isset($pagina))?$pagina['dominio']:'';?>" maxlength="100"/>
 					   <input type="hidden" name="idpagina" value="<?php echo(isset($_GET['idpagina']))?$_GET['idpagina']:'0';?>">	
					</div>
					
					<div class="input_field">
						<label for="mf">Titulo: </label>
					  <input class="smallfield" name="titulo" type="text" value="<?php echo (isset($pagina))?$pagina['titulo']:'';?>" maxlength="50"/> 
					</div>
                    <div class="input_field">
						<label for="mf">Sub Titulo: </label>
					  <input class="smallfield" name="subtitulo" type="text" value="<?php echo (isset($pagina))?$pagina['subtitulo']:'';?>" maxlength="50"/> 
					</div>
					
				  <div class="input_field">
					<label for="lf">Imagen 1: </label>
					<input class="smallfield" name="imagen1" id="lista" type="text" value="<?php echo (isset($pagina))?$pagina['imagen1']:'';?>" maxlength="50" readonly="readonly"/>
					(960 x 313)
					<div id="upload_button" class="button">Subir</div>
                  </div>
                  <div class="input_field">
					<label for="lf">Imagen 2: </label>
					<input class="smallfield" name="imagen2" id="lista2" type="text" value="<?php echo (isset($pagina))?$pagina['imagen2']:'';?>" maxlength="50" readonly="readonly"/>
					(960 x 313)
					<div id="upload_button2" class="button">Subir</div>
                  </div>
                  <div class="input_field">
					<label for="lf">Imagen 3: </label>
					<input class="smallfield" name="imagen3" id="lista3" type="text" value="<?php echo (isset($pagina))?$pagina['imagen3']:'';?>" maxlength="50" readonly="readonly"/>
					(960 x 313)
					<div id="upload_button3" class="button">Subir</div>
                  </div>
                    <div class="input_field">
					<label for="lf">Pie: </label>
					<textarea id="pie" name="pie" rows="10" cols="200" style="width:400px">
					<?php echo (isset($pagina))?$pagina['pie']:'';?>
					</textarea>
					</div>
                    
					<div class="input_field">

					  <label for="dropdown">Plantilla: </label>
						<select name="plantilla" class="dropdown">
						  <option value="1" <?php echo (isset($pagina))?(($pagina['plantilla']==1)?' selected="selected" ':''):''; ?> >Plomo</option>
						  <option value="2" <?php echo (isset($pagina))?(($pagina['plantilla']==2)?' selected="selected" ':''):''; ?>>Cafe</option>
                        </select>
				  </div>
					<div class="input_field no_margin_bottom">
						<input class="submit" type="submit" value="Aceptar" />
						<input class="submit" type="reset" value="Limpiar" />
						<a href="listar_pagina.php" class="button">Salir</a>
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