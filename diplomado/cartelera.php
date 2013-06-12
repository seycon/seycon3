<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Cartelera' ?>
        <?php include_once 'elements/head.php' ?>  
        <link rel="stylesheet" type="text/css" href="/cakephp/autocinemacoyote/css/jquery.fancybox-1.3.4.css" />
        <!--[if IE ]><link rel="stylesheet" type="text/css" href="/cakephp/autocinemacoyote/css/ie.css" /><![endif]-->        
        <script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
        <script type="text/javascript" src="js/jquery.fancybox-1.3.4.pack.js"></script>
    </head>
    <body>               
        <div style="display: none;width: 99%;" id="main-popup">            
            <div id="capa">&nbsp;</div>
            <div id="main-popup-content">
                <img id="close" src="image/fancy_close.png" alt="Cerrar" title="Cerrar"/>
                <div id="main-popup-content-content"></div>
            </div>
        </div>
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
                <div style="width: 540px;" id="content">                    
                    <div class="content-top">
                        <div class="content-title">
                        Cartelera
                    </div>
                        <img src="image/content-bg-top-bigger2.png" alt=""/>
                    </div>
                    <div style="padding-left: 45px;" id="content-bigger2" class="content-middle">
                        <?php include_once 'elements/cartelera.php'; ?>
                    </div>                    
                    <div class="content-bottom">
                        <img src="image/content-bg-bottom-bigger2.png" alt=""/>                        
                    </div>
                </div>                          
                <div class="cleared"></div>
                <div id="sidebar-footer">
                    <div id="sidebar-social">
                        <?php include_once 'elements/social.php'; ?>
                    </div>
                    <div style="margin-top: 33px;" id="main-logo">
                        <a href="index.php"><img src="image/main-logo.png" alt="Pagina Principal" title="Pagina Principal"/></a>
                    </div>
                    <div class="cleared"></div>
                </div>
            </div>            
            <div class="cleared"></div>
        </div>
    </body>
</html>