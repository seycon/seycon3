<div style="padding: 0px 10px 0px 70px;width: 605px;">
    <?php
    foreach ($movies as $movie) {
        echo '<div style="display:inline-block;margin:3px 0px;" class="movie-block">';
        echo '<a target="_blank" href="http://boletos.autocinemacoyote.com/">';
        if (($i % 2) == 0)
            echo '<div class="movie-image border-blue">';
        else
            echo '<div class="movie-image border-red">';
        echo '<img alt="' . $movie->name . '" title="' . $movie->name . '" src="image/movie_image/' . $movie->image . '" width="117" height="166">';
        if ($movie->sold_out) {
            if (($i % 2) == 0)
                echo '<img src="image/sold_out2.png" class="sold_out2"/>';
            else
                echo '<img src="image/sold_out.png" class="sold_out2"/>';
        }
        echo '</div>';
        echo '<div class="cleared"></div>';
        echo '</a>';
        //echo '<p style="margin-left:10px;margin-top:3px;margin-bottom:0px;" class="movie-link-buy"><a href="http://boletos.autocinemacoyote.com/" target="_blank">Boletos</a></p>';
        echo '</div>';
        $i++;
    }
    ?>
    <div class="cleared"></div>
</div>
<div class="cleared"></div>