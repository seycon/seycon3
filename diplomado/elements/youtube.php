<?php
$feedURL = 'http://gdata.youtube.com/feeds/api/users/AutocinemaCoyote/uploads?max-results=4';
$sxml = simplexml_load_file($feedURL);
?>
<div class="social-item">
    <div class="red">
        <a href="https://twitter.com/autocinemac" target="_blank">Autocinema Coyote</a> en Youtube
    </div><br/>  
    <?php
    foreach ($sxml->entry as $entry) {
        // get nodes in media: namespace for media information
        $media = $entry->children('http://search.yahoo.com/mrss/');

        // get video player URL
        $attrs = $media->group->player->attributes();
        $watch = $attrs['url'];

        // get video thumbnail
        $attrs = $media->group->thumbnail[0]->attributes();
        $thumbnail = $attrs['url'];

        // get <yt:duration> node for video length
        $yt = $media->children('http://gdata.youtube.com/schemas/2007');
        $attrs = $yt->duration->attributes();
        $length = $attrs['seconds'];

        // get <yt:stats> node for viewer statistics
        $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
        $attrs = $yt->statistics->attributes();
        $viewCount = $attrs['viewCount'];

        // get <gd:rating> node for video ratings
        $gd = $entry->children('http://schemas.google.com/g/2005');
        if ($gd->rating) {
            $attrs = $gd->rating->attributes();
            $rating = $attrs['average'];
        } else {
            $rating = 0;
        }
        ?>
        <div class="youtube">
            <div class="youtube-image">
                <a target="_blank" href="<?php echo $watch; ?>"><img  alt="<?php echo $media->group->title?>" title="<?php echo $media->group->title?>" style="padding-top: 2px;" src="<?php echo $thumbnail; ?>" /></a>               
            </div>
            <div class="youtube-content">
                <div style="height: 75px;">
                    <span><a class="title" target="_blank" href="<?php echo $watch; ?>"><?php                     
                    if (strlen($media->group->title)>50)
                        echo substr($media->group->title, 0, 50).'...'; 
                    else
                        echo $media->group->title; 
                    
                    ?></a></span>
                    <span><?php
    if (strlen($media->group->description) > 120)
        echo substr($media->group->description, 0, 120) . '...';
    else
        echo $media->group->description;
        ?>
                    </span>
                </div>
                <span><label>Visitas:</label> <?php echo $viewCount; ?> veces </span>
                <span><label>Duración:</label> <?php printf('%0.2f', $length / 60); ?> min. </span>                
            </div>
            <div class="cleared"></div>
        </div>        
        <?php
    }
    ?>
    <div class="cleared"></div>
</div>
