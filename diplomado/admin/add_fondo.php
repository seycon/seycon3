<?php
include 'verificar.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Adicionar Fondo' ?>
        <?php include_once '../elements/admin_head.php' ?>           
    </head>
    <body>                
        <div id="wrap">            
            <div id="small-logo">
                <a href="../index.php"><img src="../image/small-logo.png" alt="Autocinema Coyote" title="Autocinema Coyote"/></a>
            </div>
            <div id="sidebar-left">
                <div id="main-menu">
                    <?php include_once '../elements/admin_main_menu.php'; ?>
                </div>                
            </div>
            <div style="background: none;" id="sidebar-right">                
                <div style="width: 540px;" id="content">                    
                    <div class="content-top">
                        <div class="content-title">
                        Adicionar Fondo
                    </div>
                        <img src="../image/content-bg-top-bigger2.png" alt=""/>
                    </div>
                    <div class="content-middle" id="content-bigger2">
                        <?php include_once '../elements/form_add_fondo.php';?>                        
                    </div>                    
                    <div class="content-bottom">
                        <img src="../image/content-bg-bottom-bigger2.png" alt=""/>                        
                    </div>
                </div>                          
                <div class="cleared"></div>                
            </div>            
            <div class="cleared"></div>
        </div>
    </body>
</html>