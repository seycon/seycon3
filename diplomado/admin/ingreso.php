<?php
session_start();
if (isset($_POST['ingresar'])) {
    if (($_POST['usuario'] == 'jacquelinekajo') && ($_POST['password'] == 'coyote2012')) {
        session_register('user');
        $_SESSION['user'] = 'jacquelinekajo';
        header('location: index.php');
    } else {
        echo '<div style="background-color: #EA000D;color:white;text-align:center">
                                    Usuario y Contraseña Invalidos</div>';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Administración' ?>
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
                            Ingreso
                        </div>
                        <img src="../image/content-bg-top-bigger.png" alt=""/>
                    </div>
                    <div class="content-middle" id="content-bigger">                       
                        Ingrese el usuario y contraseña correctos para administrar el sitio.

                        <form id="Form" class="form_admin" method="post" action="">
                            <div class="input">
                                <label>Usuario : </label>
                                <input type="text" name="usuario" class="required"/>
                            </div>  
                            <div class="input">
                                <label>Contraseña : </label>
                                <input type="password" name="password" class="required"/>
                            </div>  
                            <div class="submit">        
                                <input type="submit" value="Ingresar" name="ingresar"/>
                            </div>
                        </form>
                    </div>                    
                    <div class="content-bottom">
                        <img src="../image/content-bg-bottom-bigger.png" alt=""/>                        
                    </div>
                </div>                          
                <div class="cleared"></div>                
            </div>            
            <div class="cleared"></div>
        </div>
    </body>
</html>