<?php
include_once 'lib/database.php';
$db = new DataBase();
$fecha_actual = date('Y-m-d');
$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$db->setQuery("SELECT *from movies WHERE date >= '" . $fecha_actual . "' ORDER BY date asc,start_time desc ");
//$db->setQuery("SELECT *from movies ORDER BY date asc");
$movies = $db->loadObjectList();
if (count($movies) > 0) {
    $mes_menor = 11;
    $mes_mayor = 0;
    foreach ($movies as $movie) {
        $actual = date('n', strtotime($movie->date)) - 1;
        if ($actual >= $mes_mayor) {
            $mes_mayor = $actual;
        }
        if ($actual <= $mes_menor) {
            $mes_menor = $actual;
        }
    }
    if ($mes_mayor == $mes_menor)
        echo '<div style="margin-bottom:10px;" class="movie-main-title">' . $meses[$mes_menor] . ' ' . date('Y') . '</div>';
    else
        echo '<div style="margin-bottom:10px;" class="movie-main-title">' . $meses[$mes_menor] . ' - ' . $meses[$mes_mayor] . ' ' . date('Y') . '</div>';
    //echo '<div class="movie-main-title">Noviembre 2012</div>';
}
?>
<p style="padding-right:15px;margin-top:5px;margin-bottom: 20px;" class="red">* Precio por boleto de función sencilla: 200 pesos por coche sin límite de personas adentro (desglosados de la siguiente manera: 120 por la exhibición de la película y 80 por el estacionamiento)</p>
<?php
if (count($movies) == 0) {
    echo '<div class="movie-block">
        <div style="margin-top: 0px;" class="movie-image border-blue">
            <img height="166px" width="117px" src="image/movie_image/sorpresa.jpg"/>
        </div>
        <div class="movie-information">
                <p class="movie-aclaracion" style="margin-top:8px;font-size:20px;">Muy pronto anunciaremos nuestra cartelera! <br/><a href="contacto.php">Sugieran sus películas favoritas.</a></p>
        </div>        
        </div>
        <div class="cleared"></div>
';
}

$i = 0;
foreach ($movies as $movie) {
    $i++;
    $main_image = '';
    echo '<div class="movie-block">';
    if (($i % 2) == 0)
        $main_image = '<div style="margin-top: 0px;" class="movie-image border-blue">';
    else
        $main_image = '<div style="margin-top: 0px;" class="movie-image border-red">';
    $main_image.= '<img src="image/movie_image/' . $movie->image . '" alt="' . $movie->name . '" title="' . $movie->name . '" height="166px" width="117px"/>';
    if ($movie->sold_out) {
        if (($i % 2) == 0)
            $main_image.= '<img src="image/sold_out2.png" class="sold_out2"/>';
        else
            $main_image.= '<img src="image/sold_out.png" class="sold_out2"/>';
    }
    $main_image.= '</div>';
    echo $main_image;
    echo '<div class="movie-information">';

    //$week_day = $dias[date('w', strtotime($movie->date))] . ' ' . date('j', strtotime($movie->date)) . ' de ' . $meses[date('n', strtotime($movie->date)) - 1];
    $week_day = $dias[date('w', strtotime($movie->date))] . ' ' . date('j', strtotime($movie->date)) . ' de ' . $meses[date('n', strtotime($movie->date)) - 1];
    //if ($movie->aclaracion == "CINEMA PARADISO ") {
    //    $week_day = $dias[date('w', strtotime($movie->date))] . ' ' . date('j', strtotime($movie->date)) . ' de ' . $meses[date('n', strtotime($movie->date)) - 1];
    //    $week_day.= ' y Domingo 9 de Diciembre';        
   // }
   // if ($movie->aclaracion == "EL ORIGEN") {
   //     $week_day = $dias[date('w', strtotime($movie->date))] . ' ' . date('j', strtotime($movie->date)) . ' de ' . $meses[date('n', strtotime($movie->date)) - 1];
   //     $week_day.= ' y Sábado 8 de Diciembre';        
   // }    
   // if ($movie->aclaracion == "500 DÍAS CON ELLA") {
   //     $week_day = $dias[date('w', strtotime($movie->date))] . ' ' . date('j', strtotime($movie->date)) . ' de ' . $meses[date('n', strtotime($movie->date)) - 1];
    //    $week_day.= ' y Jueves 6 de Diciembre';        
    //}    
    
    echo '<p class="movie-title">"' . $movie->name . '"&nbsp;&nbsp;&nbsp;';
    if ($movie->aclaracion != '')
        echo '<span class="movie-aclaracion">(' . $movie->aclaracion . ')</span>';
    echo'</p>';
    echo '<p class="movie-aclaracion2">' . $movie->aclaracion2 . '</p>';
    echo '<p class="movie-day">' . $week_day . ' <br/><span class="blue-small"> Abren puertas ' . $movie->end_time . ', función comienza ' . $movie->start_time . '</span></p>';
    echo '<p class="movie-price"><span class="red-small">Precio : </span> ' . $movie->cost . '  </p>';
    echo '<p class="movie-duration"><span class="red-small">Clasificación : </span> ' . $movie->rating . '</p>';
    $imagen1 = '';
    $imagen2 = '';
    $imagen3 = '';
    if ($movie->imagen1 !== null) {
        $imagen1 = '<img src="image/movie_image/' . $movie->imagen1 . '" alt=""/>';
    }
    if ($movie->imagen2 !== null) {
        $imagen2 = '<img src="image/movie_image/' . $movie->imagen2 . '" alt=""/>';
    }
    if ($movie->imagen3 !== null) {
        $imagen3 = '<img src="image/movie_image/' . $movie->imagen3 . '" alt=""/>';
    }
    $title = urlencode($movie->name);
    $url = urlencode('http://www.autocinemacoyote.com/home/cartelera.php');
    $summary = urlencode($movie->description);
    $image = urlencode('http://autocinemacoyote.com/home/image/movie_image/' . $movie->image);
    $link_facebook = "'http://www.facebook.com/sharer.php?s=100&amp;p[title]=" . $title . "&amp;p[summary]=" . $summary . "&amp;p[url]=" . $url . "&amp;&p[images][0]=" . $image . "', 'sharer', 'toolbar=0,status=0,width=548,height=325'";
    $aclaracion = '';
    if ($movie->aclaracion != '')
        $aclaracion = '<span class="movie-aclaracion">(' . $movie->aclaracion . ')</span>';
    echo '<p class="movie-link-information" id="' . $i . '">
                            <a href="#">+ Información</a>
                            <div class="popup" id="popup_' . $i . '">
                                <div class="popup-content">
                                    <div>
                                        <p style="font-size:24px;margin-bottom:5px" class="popup-title">"' . $movie->name . '"&nbsp;&nbsp;&nbsp; ' . $aclaracion . '<br/>
                                            <span class="movie-aclaracion">' . $movie->aclaracion2 . '</span></p>
                                        <div class="popup-image">' . $main_image . '</div>
                                        <div class="popup-information">                                            
                                            <p><span style="color:#EA000D">Año : </span>' . $movie->year . '</p>
                                            <p><span style="color:#EA000D">Género : </span>' . $movie->genero . '</p>
                                            <p><span style="color:#EA000D">Duración : </span>' . $movie->duration . '</p>
                                            <p><span style="color:#EA000D">Clasificación : </span>' . $movie->rating . '</p>
                                            <p><span style="color:#EA000D">Dirige : </span>' . $movie->directores . '</p>
                                                <p><span style="color:#EA000D">Actuan : </span>' . $movie->actores . '</p>
                                            <p class="movie-link-buy">                                            
                                                <a style="color:#EA000D" href="http://boletos.autocinemacoyote.com/" target="_blank"><span style="color:#EA000D">Boletos</span></a>                                                         
                                            </p>
                                            <p style="width:150px;" class="movie-link-buy">                                                                                                                                                     
                                                 <a onClick="window.open(' . $link_facebook . ')" target="_parent" href="javascript: void(0)">
                                                        <span style="color:#EA000D">Compartir en Facebook</span>
                                                 </a>
                                            </p>
                                        </div>
                                        <div class="cleared"></div>
                                    </div>
                                    <div style="padding: 5px 15px;margin-bottom:10px;"><span style="color:#EA000D">Sinopsis : </span>' . $movie->description . '</div>
                                    <div class="cleared"></div>
                                    <div style="height:auto;" class="popup-gallery">
                                        ' . $imagen1 . '
                                        ' . $imagen2 . '
                                        ' . $imagen3 . '
                                    </div>
                                    <div class="cleared"></div><br/>
                                    <div style="padding: 5px 15px;margin-bottom:10px;"><span style="color:#EA000D">LO TENEMOS EN AUTOCINEMA COYOTE PORQUE : </span>' . $movie->porque . '</div>
                                    <div style="margin-top:8px;" class="view-trailer"><a target="_blank" href="' . $movie->video_url . '">Ver Trailer</a></div>
                                 </div>
                            </div>
                        </p>';
    echo '<p class="movie-link-buy"><a target="_blank" href="http://boletos.autocinemacoyote.com/">Boletos</a></p>';
    echo '</div>';
    echo '<div class="cleared"></div>';
    echo '</div>';
}
?>
<br/>
<p style="margin-top:0px;padding-right:10px">
    Precio por boleto de función sencilla: 200 pesos por coche sin límite de personas adentro (desglosados de la siguiente manera: 120 por la exhibición de la película y 80 por el estacionamiento)
</p>
<p class="red">
    * Boletos en venta directamente <a href="http://boletos.autocinemacoyote.com" target="_blank">aquí</a> o en taquilla del AC.<br/>
    * Cada boleto es válido para un auto sin límite de pasajeros.<br/>
    * Si tu auto mide más de 1.5 mts. de altura va en la última fila.<br/>
    * Debes llegar con anticipación para reservar un buen lugar.<br/>
    * Llega con tu boleto impreso, de lo contrario se negará tu entrada.<br/>
    * La puerta de acceso se cierra al comenzar la función.<br/>
    * Se prohibe entrar con alimentos y bebidas ajenos. <br/>
</p>
<div class="cleared"></div>
<script type="text/Javascript">
    $(document).ready(function() {      
        $("#main-popup").click(function(){            
            $(this).css('display','none');
        });
        $("#close").click(function(){
            $("#main-popup").css('display','none');
        });        
        $(".movie-link-information").click(function(){           
            alto = $("#wrap").height();
            $("#capa").css('height', alto+50)
            $("#main-popup-content-content").html($('#popup_'+$(this).attr('id')).html());
            $("#main-popup").css('display','block');
        });
    });
</script>