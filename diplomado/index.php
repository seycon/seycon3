<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Pagina Principal' ?>
        <?php include_once 'elements/head.php' ?>        
        <link rel="stylesheet" type="text/css" href="CSS.css"> 
            <style type="text/css"> 
                #trailer-image { 
                    -webkit-transform: rotate(20deg);
                    -moz-transform: rotate(20deg);
                    rotation: 20deg;
                    filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=20);    
                } 
            </style>     
            <?php

            function get_user_browser() {
                $u_agent = $_SERVER['HTTP_USER_AGENT'];
                $ub = '';
                if (preg_match('/MSIE/i', $u_agent)) {
                    $ub = "ie";
                } elseif (preg_match('/Firefox/i', $u_agent)) {
                    $ub = "firefox";
                } elseif (preg_match('/Safari/i', $u_agent)) {
                    $ub = "safari";
                } elseif (preg_match('/Chrome/i', $u_agent)) {
                    $ub = "chrome";
                } elseif (preg_match('/Flock/i', $u_agent)) {
                    $ub = "flock";
                } elseif (preg_match('/Opera/i', $u_agent)) {
                    $ub = "opera";
                }

                return $ub;
            }

//            $browser = get_user_browser();
//            if ($browser == "ie") {
//                echo '<bgsound src="files/fondo.mp3" loop="1" delay="1">';
//            }
            ?>
    </head>
    <body>     
        <?php
//        $browser2 = get_user_browser();
//        if ($browser2 != "ie") {
//            echo '<div style="display: none;">
//                    <audio src="files/fondo.mp3" autoplay="true"></audio>
//                </div>';
//        }
        ?>        
        <?php
        include_once 'lib/database.php';
        $db2 = new DataBase();
        $db2->setQuery('Select *from fondos');
        $resultados = $db2->loadObjectList();
        $limite = count($resultados);
        if ($limite > 0) {
            mt_srand(time());
            $elegido = mt_rand(1, $limite - 1);
            if ($resultados[$elegido]->imagen != "")
                echo '<div id="wrap" style="min-height: 600px;background: url(image/fondos/' . $resultados[$elegido]->imagen . ') no-repeat scroll 0px 0px #787878;background-size:940px;">';
            else
                echo '<div id="wrap" style="min-height: 600px;background: url(image/fondos/December112012102am_vista_pantalon.jpg) no-repeat scroll 0px 0px #787878;background-size:940px;">';
        } else {
            echo '<div id="wrap" style="min-height: 600px;background: url(image/pic2.jpg) no-repeat scroll 0px 0px #787878">';
        }
        ?>
        <?php
        $i = 1;
        include_once 'lib/database.php';
        $db = new DataBase();
        $fecha_actual = date('Y-m-d');
        $db->setQuery("SELECT *from movies WHERE  date >=  '" . $fecha_actual . "' and WEEK(DATE_SUB(date,INTERVAL 1 DAY)) = WEEK('" . $fecha_actual . "') ORDER BY date asc LIMIT 5 ");
        //$db->setQuery("SELECT *from movies ORDER BY date asc LIMIT 5 ");
        $movies = $db->loadObjectList();
        $margin_footer = '-150px';
//        $margin_content = '70px';
//        $padding_content = '185px';
        $margin_content = '0px';
        $padding_content = '295px';
        if (count($movies) > 5) {
            $margin_footer = '50px';
            $margin_content = '50px';
            $padding_content = '0px';
        }
        ?>
        <div id="small-logo">
            <div style="float: right;display: block;width: 100%;">
                <a href="index.php"><img src="image/small-logo.png" alt="Autocinema Coyote" title="Autocinema Coyote"/></a>
            </div>
            <div style="display: inline-block;float: right;margin-top: 10px;padding-right: 8px;">
                <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FAutocinemaCoyote&amp;send=false&amp;layout=button_count&amp;width=130&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=162933353832616" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:21px;float:right;margin-bottom: 5px;" allowTransparency="true"></iframe>
                <br/>
                <a href="https://twitter.com/autocinemaC" class="twitter-follow-button" data-show-count="true" data-lang="es" data-show-screen-name="false">Seguir a @autocinemaC</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
        </div>
        <div id="sidebar-left">
            <div id="main-menu">
                <?php include_once 'elements/main_menu.php'; ?>
            </div>                
        </div>
        <div id="sidebar-right">    
            <div class="content-title" style="color: #0055A6;font-size: 30px;margin-top: 35px;padding-left: 2px;">

            </div>               
            <div style="height: 235px;width: 665px;margin-top: <?php echo $margin_content ?>; padding-top: <?php echo $padding_content ?>; min-height:100px;padding-bottom: 0px;" id="content">                          
                <div style="margin-bottom: 15px;background:#dd0727;color:white;padding:3px;display:block;font-size:28px;width:145px;border-radius:7px;margin-left:75px;">Esta Semana!</div>    
                <div style="padding: 0px 10px 0px 65px;width: 615px;">
                    <?php
                    foreach ($movies as $movie) {
                        echo '<div style="display:inline-block;margin:3px 0px;" class="movie-block2">';
                        echo '<a target="_blank" href="http://boletos.autocinemacoyote.com/">';
                        if (($i % 2) == 0)
                            echo '<div style="padding-top:8px;" class="movie-image border-blue">';
                        else
                            echo '<div style="padding-top:8px;" class="movie-image border-red">';
                        echo '<img alt="' . $movie->name . '" title="' . $movie->name . '" src="image/movie_image/' . $movie->image . '" width="90" height="130">';
                        if ($movie->sold_out) {
                            if (($i % 2) == 0)
                                echo '<img src="image/sold_out2.png" style="width:118px;height:147px;margin-top:-8px;" class="sold_out2"/>';
                            else
                                echo '<img src="image/sold_out.png" style="width:118px;height:147px;margin-top:-8px;" class="sold_out2"/>';
                        }
                        echo '</div>';                        
                        echo '<div class="cleared"></div>';
                        echo '</a>';
                        //echo '<p style="margin-left:10px;margin-top:3px;margin-bottom:0px;" class="movie-link-buy"><a href="http://boletos.autocinemacoyote.com/" target="_blank">Boletos</a></p>';
                        echo '</div>';
                        $i++;
                    }
                    echo '<div style="color:white;text-align:left;font-size:25px;margin-top:-13px;">Call center: 01 800 681 5381</div>';
                    ?>
                    <div class="cleared"></div>
                </div>
                <div class="cleared"></div>
                                    <!--<img  style="padding: 0px;width:618px;height:445px" alt="Muy pronto" src="image/home.jpg"/>-->                    
            </div>                       
            <div class="cleared"></div>
            <div id="sidebar-footer" style="margin-top: <?php echo $margin_footer ?>;">
                <div id="sidebar-social">
                   
                </div>
                <div style="margin-top: 39px;" id="main-logo">
                    <a href="index.php"><img src="image/main-logo.png" alt="Pagina Principal" title="Pagina Principal"/></a>
                </div>
                <div class="cleared"></div>
            </div>
        </div>            
        <div class="cleared"></div>
        </div>
    </body>
</html>
