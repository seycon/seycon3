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
            $destino = '../image/movie_image/' . $name;
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
    $imagen1 = upload_file('imagen1');
    $imagen2 = upload_file('imagen2');
    $imagen3 = upload_file('imagen3');
    $publicado = 0;
    $primero_titulo = 0;
    //if (isset($_POST['publicado']))
        //$publicado = ($_POST['publicado'] == 'on') ? 1 : 0;
    if (isset($_POST['primero_titulo']))
        $primero_titulo = ($_POST['primero_titulo'] == 'on') ? 1 : 0;
    $contenido = mysql_escape_string($_POST['contenido']);
    $query = "INSERT INTO contenidos(contenido,fecha,titulo,imagen1,imagen2,imagen3,publicado,primero_titulo) values 
                ('" . $contenido . "',
                    '" . date('Y-m-d') . "',
                    '" . $_POST['titulo'] . "',
                    '" . $imagen1 . "',
                    '" . $imagen2 . "',
                    '" . $imagen3 . "',
                    1,
                    " . $primero_titulo . "
                    )";
    $db->setQuery($query);
    if ($db->execute()) {
        ?>
        <script type="text/javascript">
            if(confirm('El contenido se guardo correctamente. Desea añadir otra mas?')){
                $("#Form").reset();
            }
            else{
                window.location="contenidos.php";
            }
        </script>
        <?php
    } else {
        showAlert('No se pudo insertar el contenido. Intente otra vez');
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
<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        new nicEditor({buttonList : ['fontSize','fontFormat','link','unlink','bold',
                'italic','underline','left','center','right','justify','ol','ul','hr','forecolor','']
        }).panelInstance('contenido');
    });
</script>
<a style="font-size: 12px;" href="#" id="informacion">Informacion acerca de los campos</a>
<div id="informacion-contenido" style="display: none;font-family: Arial;font-size: 13px;">
    <br/>
    <strong>Titulo : </strong>No se publica en el sitio. Es solo para diferenciar el contenido.<br/>
    <strong>Contenido : </strong>Es opcional. Algun texto formateado para mostrarse junto con las imagenes.<br/>
    <strong>Contenido despues de la imagen : </strong>Marcar si se quiere mostrar el contenido antes o despues de mostrarse la imagen<br/>
    <!--<strong>Publicado :</strong> Marcar si se quiere publicar el contenido<br/>-->
    <strong>Imagen: </strong>De preferencia con 550px de ancho.<br/>
</div>
<br/><br/>
<div>Llenar los siguientes campos para adicionar contenido nuevo.</div>
<br/>
<form id="Form" class="form_admin" enctype="multipart/form-data" method="post" action="">
    <div class="input">
        <label style="min-width: 210px;">Titulo : </label>
        <input type="text" name="titulo" class="required"/>
    </div>   
    <div class="input">
        <label style="min-width: 210px;">Contenido : </label> <div class="cleared"></div>  
        <div class="editable">
            <textarea style="width: 400px;"cols="120" rows="4" id="contenido" name="contenido"></textarea>	
        </div>
    </div>   
    <!--<div class="input">
        <label style="min-width: 210px;">Publicado : </label>
        <input type="checkbox" name="publicado"/>
    </div>   -->
    <input type="hidden" value="1" name="publicado"/>
    <div class="input">
        <label style="min-width: 210px;">Contenido despues de la Imagen : </label>
        <input type="checkbox" name="primero_titulo"/>
    </div>   

    <div class="input">
        <label style="min-width: 210px;"> Imagen : </label>
        <input type="file" name="imagen1"/>
    </div>
    <!--<div class="input">
        <label style="min-width: 210px;"> Imagen 2 : </label>
        <input type="file" name="imagen2"/>
    </div>
    <div class="input">
        <label style="min-width: 210px;"> Imagen 3 : </label>
        <input type="file" name="imagen3"/>
    </div>-->
    <div class="submit">        
        <input type="submit" value="adicionar" name="adicionar"/>
    </div>
</form>