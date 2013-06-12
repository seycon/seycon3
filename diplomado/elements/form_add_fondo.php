<?php

function showAlert($message) {
    echo '<script type="text/javascript">
        alert("' . $message . '");
</script>';
}

function upload_file($var) {
    if (isset($_FILES[$var])) {
        if ($_FILES[$var]['error'] == 0) {
            $name = date("FjYgia") . '_' . $_FILES[$var]['name'];
            $type = $_FILES[$var]['type'];
            $destino = '../image/fondos/' . $name;
            if (copy($_FILES[$var]['tmp_name'], $destino)) {
                return $name;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
    return '';
}

if (isset($_POST['adicionar'])) {
    include_once '../lib/database.php';
    $db = new DataBase();
    $imagen = upload_file('imagen');        
    $titulo = mysql_escape_string($_POST['titulo']);
    $query = "INSERT INTO fondos(titulo,imagen) values 
                ('" . $titulo . "',                    
                    '" . $imagen . "'
                    )";
    $db->setQuery($query);
    if ($db->execute()) {
        ?>
        <script type="text/javascript">
            if(confirm('El fondo se guardo correctamente. Desea añadir otra mas?')){
                $("#Form").reset();
            }
            else{
                window.location="fondos.php";
            }
        </script>
        <?php
    } else {
        showAlert('No se pudo insertar el fondo. Intente otra vez');
    }
}
?>


<script type="text/javascript" src="../js/jquery.validate.js"></script>   
<script type="text/javascript">
    $(document).ready(function(){
        $("#Form").validate();
        $("#informacion").click(function(){
            $("#informacion-contenido").toggle();
        });
    });
</script>
<script src="../js/nicEdit.js" type="text/javascript"></script>
<a style="font-size: 12px;" href="#" id="informacion">Informacion acerca de los campos</a>
<div id="informacion-contenido" style="display: none;font-family: Arial;font-size: 13px;">
    <br/>
    <strong>Titulo : </strong>Un texto que represente el fondo.<br/>
    <strong>Imagen : </strong>Que tengan como minimo las siguietes dimensiones : 910px de ancho y 620 de alto.Que no superen los 400kb. Esto ultimo para que la pagina no tarde en cargar mucho tiempo<br/>
</div>
<br/><br/>
<div>Llenar los siguientes campos para adicionar fondo nuevo.</div>
<br/>
<form id="Form" class="form_admin" enctype="multipart/form-data" method="post" action="">
    <div class="input">
        <label style="min-width: 210px;">Titulo : </label>
        <input type="text" name="titulo" class="required"/>
    </div>      
    <div class="input">
        <label style="min-width: 210px;"> Imagen : </label>
        <input type="file" name="imagen"/>
    </div>    
    <div class="submit">        
        <input type="submit" value="adicionar" name="adicionar"/>
    </div>
</form>