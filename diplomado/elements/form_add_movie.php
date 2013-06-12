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
                showAlert('No se pudo copiar');
            }
        } else {
            showAlert('No se pudo copiar2');
        }
    }
    return NULL;
}

if (isset($_POST['adicionar'])) {
    include_once '../lib/database.php';
    $db = new DataBase();
    $image = upload_file('image');
    if ($image) {
        $image1 = upload_file('image1');
        if ($image1) {
            $image2 = upload_file('image2');
            if ($image2) {
                $image3 = upload_file('image3');
                if ($image3) {
                    $sold_out = ($_POST['sold']=='on') ? 1 : 0 ;
                    $query = "INSERT INTO movies(name,aclaracion,description,aclaracion2,date,start_time,end_time,cost,duration,
                                rating,genero,directores,actores,porque,year,image,imagen1,imagen2,imagen3,video_url,sold_out) values ('" . $_POST['name'] . "','" . $_POST['aclarativo'] . "',
                    '" . $_POST['description'] . "',
                    '" . $_POST['aclarativo2'] . "',
                    '" . $_POST['date'] . "',
                    '" . $_POST['start_time'] . "',
                    '" . $_POST['end_time'] . "',
                    '" . $_POST['cost'] . "',
                    '" . $_POST['duration'] . "',
                    '" . $_POST['rating'] . "',
                    '" . $_POST['genero'] . "',
                    '" . $_POST['directores'] . "',
                    '" . $_POST['actores'] . "',
                    '" . $_POST['porque'] . "',
                    '" . $_POST['year'] . "',
                    '" . $image . "',
                    '" . $image1 . "',
                    '" . $image2 . "',
                    '" . $image3 . "',
                    '" . $_POST['video_url'] . "',
                    " . $sold_out . ")";
                    $db->setQuery($query);
                    if ($db->execute()) {
                        ?>
                        <script type="text/javascript">
                            if(confirm('La película se guardo correctamente. Desea añadir otra mas?')){
                                $("#Form").reset();
                            }
                            else{
                                window.location="index.php";
                            }
                        </script>
                        <?php
                    } else {
                        showAlert('No se pudo insertar la película. Intente otra vez');
                    }
                } else {
                    showAlert('Problemas al subir la imagen 3 del trailer');
                }
            } else {
                showAlert('Problemas al subir la imagen 2 del trailer');
            }
        } else {
            showAlert('Problemas al subir la imagen 1 del trailer');
        }
    } else {
        showAlert('Problemas al subir la imagen Principal');
    }
}
?>


<script type="text/javascript" src="../js/jquery.validate.js"></script>   
<script type="text/javascript">
    $(document).ready(function(){
        $("#Form").validate();
    });
</script>
<div>Llenar los siguientes campos para adicionar una nueva película en la cartelera. Evite subir archivos de imagenes que contengan ñ o espacios en sus nombres.</div>
<br/>
<form id="Form" class="form_admin" enctype="multipart/form-data" method="post" action="">
    <div class="input">
        <label>Nombre : </label>
        <input type="text" maxlength="180" class="required" name="name">
    </div>
    <div class="input">
        <label> Texto Aclarativo : </label>
        <input type="text" maxlength="180" name="aclarativo">
    </div>
    <div class="input">
        <label> Texto Aclarativo2 : </label>
        <input type="text" maxlength="180" name="aclarativo2">
    </div>
    <div class="input">
        <label> Descripción: </label>
        <textarea name="description" rows="6" class="required"></textarea>
    </div>
    <div class="input">
        <label> Año de la Película: </label>
        <input name="year" class="required"/>
    </div>
    <div class="input">
        <label> Lo tenemos porque : </label>
        <textarea name="porque" rows="6" class="required"></textarea>
    </div>
    <div class="input">
        <label> Fecha: </label>
        <input name="date" placeholder="yyyy/mm/dd" class="required date"/>
    </div>  
    <div class="input">
        <label> Hora <span>(Inicio de la Película)</span>: </label>
        <input name="start_time" class="required"/>
    </div>  
    <div class="input">
        <label> Hora <span>(Apertura de las puertas): </span></label>
        <input name="end_time" class="required"/>
    </div>  
    <div class="input">
        <label> Precio por coche <span>(ej: $200): </span></label>
        <input name="cost" class="required"/>
    </div>  
    <div class="input">
        <label> Duración <span>(ej: 120 min.): </span></label>
        <input name="duration" class="required"/>
    </div>  
    <div class="input">
        <label> Clasificación : </label>
        <input name="rating" maxlength="14" class="required"/>
    </div> 
    <div class="input">
        <label> Genero : </label>
        <input name="genero" class="required"/>
    </div> 
    <div class="input">
        <label> Directores : </label>
        <input name="directores" class="required"/>
    </div> 
    <div class="input">
        <label> Actores : </label>
        <input name="actores" class="required"/>
    </div>
    <div class="input">
        <label> Agotado : </label>
        <input type="checkbox" name="sold"/>
    </div>
    <div class="input">
        <label> URL Trailer : </label>
        <input type="text" name="video_url"/>
    </div>
    <div class="input">
        <label> Imagen Principal : </label>
        <input type="file" class="required" name="image"/>
    </div>
    <div class="input">
        <label> Imagen Trailer 1 : </label>
        <input type="file" class="required" name="image1"/>
    </div>
    <div class="input">
        <label> Imagen Trailer 2 : </label>
        <input type="file" class="required" name="image2"/>
    </div>
    <div class="input">
        <label> Imagen Trailer 3 : </label>
        <input type="file" class="required" name="image3"/>
    </div>
    <div class="submit">        
        <input type="submit" value="adicionar" name="adicionar"/>
    </div>
</form>