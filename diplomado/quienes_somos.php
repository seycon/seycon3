<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Quienes Somos' ?>
        <?php include_once 'elements/head.php' ?>           
    </head>
    <body>                
        <div id="wrap">            
            <div id="small-logo">
                <a href="index.php"><img src="image/small-logo.png" alt="Autocinema Coyote" title="Autocinema Coyote"/></a>
            </div>
            <div id="sidebar-left">
                <div id="main-menu">
                    <?php include_once 'elements/main_menu.php'; ?>
                </div>                
            </div>
            <div id="sidebar-right">                             
                <div style="width: 670px;" id="content">                                        
                    <div style="position:absolute;margin-left:592px;margin-top:5px;">
                        <a href="http://www.cinemacoyote.com" target="_blank"><img src="image/logo-other2.png"></a><div class="cleared"></div>
                        <a href="http://cinemacoyote.com" target="_blank"><img src="image/logo2_luna.png"></a><div class="cleared"></div>
                        <!--<a href="http://www.fullmoondrivein.com" target="_blank"><img src="image/logo-other3.png"></a>-->
                        
                    </div>
                    <div class="content-top">                        
                        <div class="content-title">
                            Quiénes Somos
                        </div>
                        <img src="image/content-bg-top-bigger.png" alt=""/>
                    </div>
                    <div class="content-middle about-us" id="content-bigger">
                        <p style="margin-top: 0px;">
                            El Autocinema Coyote es el primer autocinema en México en 25 años con ciclos regulares de proyección cada semana, presentando películas clásicas en formato digital, todas muy queridas y anheladas por el público. El lugar cuenta con una decoración temática de los años 50’s, destacando en aspectos como la fachada, los letreros de la cartelera, la dulcería, los anuncios y animaciones antes de la película y muchos más, con el objetivo de brindarle al espectador una experiencia mucho más grande que la de solamente ver una película: transportarse al pasado, a algo que ya no existe hoy en día como tal, y vivir una velada inolvidable.
                        </p>
                        <p style="margin-bottom: 0px;">
                            En el autocinema, la gente puede disfrutar de una película desde la comodidad de su coche, lo cual también implica una privacidad muy deseada que trae enormes recompensas. Por ejemplo, es un lugar ideal para llevar a una pareja en una primera cita, o para acudir con amigos o familia a disfrutar y comentar de una buena película, en un espacio cerrado como si estuvieran en sus hogares, pero a la vez conservando la magia de la colectividad del público en el cine. Es una mezcla entre ir al cine y ver una película en tu casa, y es la combinación perfecta de ambas, ya que tiene lo mejor de ambas.
                        </p>    <hr/>
                        <?php
                        include_once 'lib/database.php';
                        $db = new DataBase();
                        $db->setQuery('SELECT *from contenidos where publicado=1 order by fecha desc');
                        $contenidos = $db->loadObjectList();
                        $i = 0;
                        foreach ($contenidos as $contenido) {
                            echo '<br/>';
                            if ($contenido->primero_titulo == 1) {
                                echo $contenido->contenido;
                                if ($contenido->imagen1) {
                                    echo '<img style="max-width:500px;" src="image/movie_image/' . $contenido->imagen1 . '" alt=""/>';
                                }
                            } else {
                                if ($contenido->imagen1) {                                                                    
                                echo '<img style="max-width:500px;" src="image/movie_image/' . $contenido->imagen1 . '" alt=""/>';
                                }
                                echo $contenido->contenido;
                            }
                        }
                        ?>
                    </div>                    
                    <div class="content-bottom">
                        <img src="image/content-bg-bottom-bigger.png" alt=""/>                        
                    </div>
                </div>                          
                <div class="cleared"></div>
                <div id="sidebar-footer">
                    <div id="sidebar-social">
                        <?php include_once 'elements/social.php'; ?>
                    </div>
                    <div style="margin-top: 39px;" id="main-logo">
                        <a href="index.php"><img src="image/main-logo.png" alt="Pagina Principal" title="Pagina Principal"/></a>
                    </div>                    
                    <div class="cleared"></div>
                    <div class="cleared"></div>
                </div>
            </div>            
            <div class="cleared"></div>
        </div>
    </body>
</html>