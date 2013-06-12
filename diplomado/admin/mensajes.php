<?php
include 'verificar.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Administración' ?>
        <?php include_once '../elements/admin_head.php' ?>         
        <script type="text/javascript">
            $(document).ready(function(){
                $('.control').click(function(){
                    control = $(this).attr('control');
                    $("#"+control).toggle();
                });
            });
        </script>
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
                    <div class="content-top">\
                        <div class="content-title">
                        Administración
                    </div>
                        <img src="../image/content-bg-top-bigger.png" alt=""/>
                    </div>
                    <div class="content-middle" id="content-bigger">
                        <div>Click sobre el nombre para mostrar el mensaje completo.</div><br/>
                        <table class="tabla" border="1" cellpadding="0" cellspacing="0">
                            <tr>
                                <th>Fecha</th>
                                <th>Informacion</th>
                            </tr>                        
                            <?php
                            include_once '../lib/database.php';
                            $db = new DataBase();
                            $db->setQuery('SELECT *from messages  order by created desc');
                            $messages = $db->loadObjectList();
                            $i = 0;
                            if (count($messages) > 0) {
                                foreach ($messages as $message) {
                                    echo '<tr>
                                    <td>' . $message->created . '</td>
                                    <td style="width:400px;"><span class="control" control="informacion_completa' . $i . '"><span class="message_title">Nombre : </span>' . $message->name . '</span>
                                    <div class="control-informacion" id="informacion_completa' . $i . '">
                                        <span class="message_title">Email : </span>'.$message->email.'<br/>
                                        <span class="message_title">Edad : </span>'.$message->edad.'<br/>
                                        <span class="message_title">Telefono : </span>'.$message->phone.'<br/>
                                        <span class="message_title">Mensaje : </span>'.$message->message.'<br/>
                                    </div>    
                                    </td>
                                  </tr>';
                                $i++;                                    
                                }
                            } else {
                                echo '<tr><td colspan="3">No hay Mensajes registradas hasta el momento.</td></tr>';
                            }
                            ?>
                        </table>
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