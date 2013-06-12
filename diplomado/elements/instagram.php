<?php 
error_reporting(0);
?>
<?php $instagram_count = 0; ?>
<?php $instagram_limit = 15; ?>
<div class="social-item">
    <div class="red">
        <a href="http://statigr.am/tag/autocinemacoyote/" target="_blank">Autocinema Coyote</a> en Instagram
    </div>    
    <p>Agrega tus propias fotos a este feed desde Instagram, toma un foto e incluye  
        <a href="http://statigr.am/tag/autocinemacoyote/" target="_blank">#autocinemacoyote</a>
        en el titulo!</p>
    <?php
    $rss_instagram2 = file_get_contents("http://instagr.am/tags/autocinemacoyote/feed/recent.rss");
    $xml_instagram2 = new SimpleXMLElement($rss_instagram2);
    foreach ($xml_instagram2->channel->item as $instagram) {
        if ($instagram_count < $instagram_limit) {
            echo '<div class="instagram-image">';
            echo '<a target="_blank" href="http://statigr.am/tag/autocinemacoyote/">' . $instagram->description . '</a>';
            echo '</div>';
            $instagram_count++;
        }
    }

//    $rss_instagram = file_get_contents("http://statigr.am/feed/autocinemacoyote");
//    $xml_instagram = new SimpleXMLElement($rss_instagram);
//    foreach ($xml_instagram->channel->item as $instagram) {
//        if ($instagram_count < $instagram_limit) {
//            echo '<div id= "ronald"class="instagram-image">';
//            echo $instagram->description;
//            echo '</div>';
//            $instagram_count++;
//        }
//    }
    ?>
    <div class="cleared"></div>
</div>