<?php

//error_reporting(0);
function convert_time($twitter_time) {
    $intervalNames = array('segundo', 'minuto', 'hora', 'dia', 'semana', 'mes', 'año');
    $intervalSeconds = array(1, 60, 3600, 86400, 604800, 2630880, 31570560);
    $time = 'en este momento';
    $secondsPassed = time() - strtotime($twitter_time);
    if ($secondsPassed > 0) {
        // see what interval are we in
        for ($j = count($intervalSeconds) - 1; ($j >= 0); $j--) {
            $crtIntervalName = $intervalNames[$j];
            $crtInterval = $intervalSeconds[$j];

            if ($secondsPassed >= $crtInterval) {
                $value = floor($secondsPassed / $crtInterval);
                if ($value > 1)
                    $crtIntervalName .= 's';

                $time = 'Hace ' . $value . ' ' . $crtIntervalName . ' atrás';

                break;
            }
        }
    }
    return $time;
}
?>

<div class="social-item">
    <div class="red">
        <a href="https://twitter.com/autocinemac" target="_blank">Autocinema Coyote</a> en Twitter
    </div><br/>        
    <?php
    //$rss_twitter = file_get_contents("https://twitter.com/statuses/user_timeline.rss?screen_name=AutocinemaC&count=2");
    //$xml_twitter = new SimpleXMLElement($rss_twitter);
    //foreach ($xml_twitter->channel->item as $tweet) {
    ?>
    <!--<div class="tweet">
        <div class="tweet-image"><img src="image/twitter_bird.png"/></div>
        <div class="tweet-content">
    <?php //echo $tweet->description;  ?>
            <br/><span class="tweet-time">
<?php //echo convert_time($tweet->pubDate);  ?>
            </span>
        </div>
        <div class="cleared"></div>
    </div>-->
    <?php //} ?>
    <?php
    $rss_twitter2 = file_get_contents("http://search.twitter.com/search.atom?q=@AutocinemaC&rpp=4");
    $xml_twitter2 = new SimpleXMLElement($rss_twitter2);
    foreach ($xml_twitter2->entry as $tweet) {
        ?>
        <div class="tweet">
            <div class="tweet-image">
    <?php $aux_image = $tweet->link[1]->attributes()->href; ?>
                <img src="<?php echo $aux_image; ?>"/>                
            </div>
            <div class="tweet-content" style="font-family: Arial;font-size: 14px;padding-top: 3px;">
                <div style="height: 57px;">
    <?php echo $tweet->content; ?>
                </div>
                <span class="tweet-time" style="font-size: 11px;font-weight: bold;font-style: italic;">
    <?php echo convert_time($tweet->published); ?>
                </span>
            </div>
            <div class="cleared"></div>
        </div>
        <?php
    }
    ?>    
    <div class="cleared"></div>
</div>