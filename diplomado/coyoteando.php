<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Coyoteando' ?>
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
                <div style="width: 540px;" id="content">                    
                    <div class="content-top">
                        <div class="content-title">
                            Coyoteando
                        </div>
                        <img src="image/content-bg-top-bigger2.png" alt=""/>
                    </div>
                    <div id="content-bigger2" class="content-middle">    
                        <?php
                        include_once 'elements/instagram.php';
                        include_once 'elements/twitter.php';
                        include_once 'elements/facebook.php';
                        include_once 'elements/youtube.php';
                        ?>                        
                        <div class="cleared"></div>
                    </div>               
                    <div class="content-bottom">
                        <img src="image/content-bg-bottom-bigger2.png" alt=""/>                        
                    </div>
                    <div class="cleared"></div>
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
                </div>
            </div>            
            <div class="cleared"></div>
        </div>
    </body>
</html>