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
                $('.eliminar').click(function(){
                    if(confirm('Esta seguro de eliminar esta pelicula?')){
                        return true;    
                    }
                    else{
                        return false;    
                    }                  
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
                    <div class="content-top">
                        <div class="content-title">
                        Administración
                    </div>
                        <img src="../image/content-bg-top-bigger.png" alt=""/>
                    </div>
                    <div class="content-middle" id="content-bigger">
                        <div><a href="add_movie.php">Adicionar una Película</a></div><br/>
                        <table class="tabla" border="1" cellpadding="0" cellspacing="0">
                            <tr>
                                <th>Película</th>
                                <th>Fecha</th>                                
                                <th>Acción</th>
                            </tr>                        
                            <?php
                            include_once '../lib/database.php';
                            $db = new DataBase();
                            $db->setQuery('SELECT *from movies');
                            $movies = $db->loadObjectList();
                            $i = 0;
                            if(count($movies)>0){
                             foreach ($movies as $movie) {
                                echo '<tr>
                                    <td style="width:280px;">' . $movie->name . '</td>
                                    <td>' . $movie->date . '</td>
                                    <td><a href="editar.php?id=' . $movie->id . '">Editar</a> - <a class="eliminar" href="eliminar.php?id=' . $movie->id . '">Eliminar</a></td>
                                  </tr>';
                            }   
                            }
                            else{
                                echo '<tr><td colspan="3">No hay películas registradas hasta el momento.</td></tr>';
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