<?php
error_reporting(0);

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
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    return NULL;
}

include_once '../lib/database.php';
$db = new DataBase();

if (isset($_POST['editar'])) {
    $update_image = " ";
    $update_image1 = " ";
    $update_image2 = " ";
    $update_image3 = " ";
    if (isset($_FILES['image'])) {
        $image = upload_file('image');
        if ($image)
            $update_image = " image = '" . $image . "', ";
    }
    if (isset($_FILES['image1'])) {
        $image1 = upload_file('image1');
        if ($image1)
            $update_image1 = " imagen1 = '" . $image1 . "', ";
    }
    if (isset($_FILES['image2'])) {
        $image2 = upload_file('image2');
        if ($image2)
            $update_image2 = " imagen2 = '" . $image2 . "', ";
    }
    if (isset($_FILES['image3'])) {
        $image3 = upload_file('image3');
        if ($image3)
            $update_image3 = " imagen3 = '" . $image3 . "', ";
    }


    $sold_out = ($_POST['sold'] == 'on') ? 1 : 0;
    $query = "UPDATE movies set 
             name = '" . $_POST['name'] . "', 
             aclaracion = '" . $_POST['aclarativo'] . "', 
             description = '" . $_POST['description'] . "', 
             aclaracion2 = '" . $_POST['aclarativo2'] . "', 
             start_time = '" . $_POST['start_time'] . "', 
             end_time = '" . $_POST['end_time'] . "', 
             duration = '" . $_POST['duration'] . "', 
             date = '" . $_POST['date'] . "', 
             rating = '" . $_POST['rating'] . "', 
             genero = '" . $_POST['genero'] . "', 
             cost = '" . $_POST['cost'] . "', 
            ".$update_image."     
            ".$update_image1."     
            ".$update_image2."     
            ".$update_image3."     
             directores = '" . $_POST['directores'] . "', 
             actores = '" . $_POST['actores'] . "', 
             porque = '" . $_POST['porque'] . "', 
             year = '" . $_POST['year'] . "', 
             video_url = '" . $_POST['video_url'] . "', 
             sold_out = " . $sold_out . " where id = " . $_GET['id'] . "                
            ;";
    $db->setQuery($query);
    if ($db->execute()) {
        //echo $query;
        ?>
        <script type="text/javascript">
            alert('La película se edito correctamente.')                            
            window.location="index.php";            
        </script>
        <?php
    } else {
        showAlert('No se pudo editar la película. Intente otra vez ');
        //echo $query;
    }
} else {
    $query = "select *from movies where id = " . $_GET['id'];
    $db->setQuery($query);
    $movie_seleccionada = $db->loadObject();
}
?>


<script type="text/javascript" src="../js/jquery.validate.js"></script>   
<script type="text/javascript">
    $(document).ready(function(){
        $("#Form").validate();
    });
</script>
<div>Modificar los siguientes campos para editar la película en la cartelera.</div>
<br/>
<form id="Form" class="form_admin" enctype="multipart/form-data" method="post" action="">
    <div class="input">
        <label>Nombre : </label>
        <input type="text" value="<?php echo $movie_seleccionada->name ?>" maxlength="180" class="required" name="name">
    </div>
    <div class="input">
        <label> Texto Aclarativo : </label>
        <input type="text" value="<?php echo $movie_seleccionada->aclaracion ?>"  maxlength="180" name="aclarativo">
    </div>
    <div class="input">
        <label> Texto Aclarativo2 : </label>
        <input type="text" maxlength="180"  value="<?php echo $movie_seleccionada->aclaracion2 ?>" name="aclarativo2">
    </div>
    <div class="input">
        <label> Descripción: </label>
        <textarea name="description" rows="6" class="required"><?php echo $movie_seleccionada->description ?></textarea>
    </div>
    <div class="input">
        <label> Año de la Película: </label>
        <input name="year"  value="<?php echo $movie_seleccionada->year ?>" class="required"/>
    </div>
    <div class="input">
        <label> Lo tenemos porque : </label>
        <textarea name="porque" rows="6" class="required"><?php echo $movie_seleccionada->porque ?></textarea>
    </div>
    <div class="input">
        <label> Fecha: </label>
        <input name="date"  value="<?php echo $movie_seleccionada->date ?>"  class="required date"/>
    </div>  
    <div class="input">
        <label> Hora <span>(Inicio de la Película)</span>: </label>
        <input name="start_time"  value="<?php echo $movie_seleccionada->start_time ?>" class="required"/>
    </div>  
    <div class="input">
        <label> Hora <span>(Apertura de las puertas): </span></label>
        <input name="end_time"  value="<?php echo $movie_seleccionada->end_time ?>"  class="required"/>
    </div>  
    <div class="input">
        <label> Precio por coche <span>(ej: $200): </span></label>
        <input name="cost"  value="<?php echo $movie_seleccionada->cost ?>"  class="required"/>
    </div>  
    <div class="input">
        <label> Duración <span>(ej: 120 min.): </span></label>
        <input name="duration" value="<?php echo $movie_seleccionada->duration ?>"  class="required"/>
    </div>  
    <div class="input">
        <label> Clasificación : </label>
        <input name="rating"  value="<?php echo $movie_seleccionada->rating ?>" maxlength="14" class="required"/>
    </div> 
    <div class="input">
        <label> Genero : </label>
        <input name="genero"  value="<?php echo $movie_seleccionada->genero ?>" class="required"/>
    </div> 
    <div class="input">
        <label> Directores : </label>
        <input name="directores"  value="<?php echo $movie_seleccionada->directores ?>"  class="required"/>
    </div> 
    <div class="input">
        <label> Actores : </label>
        <input name="actores"  value="<?php echo $movie_seleccionada->actores ?>" class="required"/>
    </div>
    <div class="input">
        <label> Agotado : </label>
        <input type="checkbox" <?php echo ($movie_seleccionada->sold_out == 0) ? '' : ' checked ' ?> name="sold"/>
    </div>
    <div class="input">
        <label> URL Trailer : </label>
        <input type="text" value="<?php echo $movie_seleccionada->video_url ?>"  name="video_url"/>
    </div>   
    <div class="input">
        <label> Imagen Principal : </label>
        <input type="file"  name="image"/>
    </div>
    <div class="input">
        <label> Imagen Trailer 1 : </label>
        <input type="file"  name="image1"/>
    </div>
    <div class="input">
        <label> Imagen Trailer 2 : </label>
        <input type="file" name="image2"/>
    </div>
    <div class="input">
        <label> Imagen Trailer 3 : </label>
        <input type="file" name="image3"/>
    </div>
    <div class="submit">        
        <input type="submit" value="editar" name="editar"/>
    </div>
</form>