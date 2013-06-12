<?php
	/*require_once('usuario/DUsuario.php');
	$usuario= new class_usuario();
	if(false){
		$usuario->guardar();
	}*/
	
	$transacion=(isset($_GET['idmenu']))?'modificar':'insertar';
	include("conexion.php");
//		$db = new MySQL();
	if($transacion=='modificar'){
		$sql="Select * from menu where idmenu={$_GET['idmenu']}";
		$menu=$db->arrayConsulta($sql);
	}
	
		$sql="Select * from pagina where estado='1'";
		$pagina=$db->consulta($sql);
		
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
<!--Libreria Editor-->    
    <script type="text/javascript" src="js/tiny_mce.js"></script>
<script type="text/javascript">

	tinyMCE.init({
		mode : "exact",
		elements : "pie",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "black",
		plugins : "lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>

<!--Libreria Subir imagenes-->

<script language="javascript" src="js/AjaxUpload.2.0.min.js"></script>
<script language="javascript">
$(document).ready(function(){
	var button = $('#upload_button'), interval;
	new AjaxUpload('#upload_button', {
        action: 'upload.php',
		onSubmit : function(file , ext){
		if (! (ext && /^(jpg|flv)$/.test(ext))){
			// extensiones permitidas
			alert('Error: Solo se permiten imagenes jpg o videos flv');
			// cancela upload
			return false;
		} else {
			button.text('Subiendo ...');
			this.disable();
		}
		},
		onComplete: function(file, response){
			button.text('Subir');
			// enable upload button
			this.enable();			
			// Agrega archivo a la lista
//			$('#lista').appendTo('.files').text(file);
			document.getElementById('lista').value=file;
		}	
	});
	var button = $('#upload_button2'), interval;
	new AjaxUpload('#upload_button2', {
        action: 'upload.php',
		onSubmit : function(file , ext){
		if (! (ext && /^(jpg|flv)$/.test(ext))){
			// extensiones permitidas
			alert('Error: Solo se permiten imagenes jpg o videos flv');
			// cancela upload
			return false;
		} else {
			button.text('Subiendo ...');
			this.disable();
		}
		},
		onComplete: function(file, response){
			button.text('Subir');
			// enable upload button
			this.enable();			
			// Agrega archivo a la lista
//			$('#lista').appendTo('.files').text(file);
			document.getElementById('lista2').value=file;
		}	
	});
	var button = $('#upload_button3'), interval;
	new AjaxUpload('#upload_button3', {
        action: 'upload.php',
		onSubmit : function(file , ext){
		if (! (ext && /^(jpg|flv)$/.test(ext))){
			// extensiones permitidas
			alert('Error: Solo se permiten imagenes jpg o videos flv');
			// cancela upload
			return false;
		} else {
			button.text('Subiendo ...');
			this.disable();
		}
		},
		onComplete: function(file, response){
			button.text('Subir');
			// enable upload button
			this.enable();			
			// Agrega archivo a la lista
//			$('#lista').appendTo('.files').text(file);
			document.getElementById('lista3').value=file;
		}	
	});

});

</script>

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
				<h2>Registro Menu</h2>				
				<form method="post" action="menu/Dmenu.php?transaccion=<?php echo $transacion;?>"><!-- Form -->
				<!-- Fieldset -->
				<fieldset><legend><!--This is a simple fieldset--></legend>
					<div class="input_field">

					  <label for="dropdown">Pagina: </label>
						<select name="idpagina" class="dropdown">
                           <?php while( $fila = mysql_fetch_row($pagina)){?>
						  <option value="<?php echo $fila[0]; ?>" <?php echo (isset($menu))?(($menu['plantilla']==$fila[0])?' selected="selected" ':''):''; ?> ><?php echo $fila[2]; ?></option>
						  
                           <?php }?>
                        </select>
 					   <input type="hidden" name="idmenu" value="<?php echo(isset($_GET['idmenu']))?$_GET['idmenu']:'0';?>">	
					</div>
					
					<div class="input_field">
						<label for="mf">Titulo: </label>
					  <input class="smallfield" name="titulo" type="text" value="<?php echo (isset($menu))?$menu['texto']:'';?>" maxlength="50"/> 
					</div>
                    
					
				  
                 
                 
                    
                    
					
					<div class="input_field no_margin_bottom">
						<input class="submit" type="submit" value="Aceptar" />
						<input class="submit" type="reset" value="Limpiar" />
						<a href="listar_menu.php" class="button">Salir</a>
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