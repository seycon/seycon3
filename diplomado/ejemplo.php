<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Dudas' ?>
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
                            Preguntas Frecuentes
                        </div>
                        <img src="image/content-bg-top-bigger2.png" alt=""/>
                    </div>
                    <div id="content-bigger2" class="content-middle faq"> 











                        <script type="text/javascript">
                            setTimeout('r_show_popup()', 2000)
                            function r_show_popup(){
                                $(document).ready(function(){
                                    //$("#r_manager_popup").click(function(){
                                    var window_width = $(window).width();
                                    var window_height = $(window).height();
                                    $("#r_overlay").css('height',window_height);
                                    $("#r_overlay").css('width',window_width);
                                    $("#r_overlay").show();
                                    //$("#r_popup").show();
                                    $("#r_popup").fadeIn(800);
                                    //}); 
                                    $("#r_close").click(function(){                                    
                                        $("#r_popup").hide();  
                                        $("#r_overlay").hide();  
                                    });
                                });
                            }
                        </script>                      


                        <div id="r_overlay" style="position: absolute;top: 0;
                             left: 0;background: #000000;opacity: 0.7;
                             z-index: 100;width: 100%;display: none;position: fixed;">
                            &nbsp;
                        </div>                                                            
                        <div id="r_popup" style="
                             opacity: 1!important;margin: 0px auto;
                             display: block;width: 600px;
                             height: 350px;border: 15px solid #21B6DB;
                             border-radius: 15px;opacity: 500;
                             background: white;display: none;position: fixed;
                             z-index: 150;margin-left: -75px;margin-top: -50px;">
                            <img id="r_close" style="
                                 position:absolute;float:right;right:0;
                                 margin-top:-28px;margin-right:-23px" 
                                 src="image/close_r.png" alt="close"/>
                            <div id="r_content_popup">
                                Este es el contenido del popup
                            </div>
                            <br/>                                                            
                        </div>





<?php

if (isset($_SESSION['show_popup'])){
    
}
else{
       
    $_SESSION['show_popup'] = true;
}
?>









                    </div>  
                    <div class="content-bottom">
                        <img src="image/content-bg-bottom-bigger2.png" alt=""/>                        
                    </div>
                    <!--<div class="suggest_movie"><a href="contacto.php">Sugierenos tu Película</a></div>-->
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