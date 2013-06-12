<?php
include_once 'lib/database.php';
$db = new DataBase();
//$db->setQuery("SELECT *from movies where id = 6");
//$movies = $db->loadObjectList();
//echo '<pre>';
//print_r($movies);
//echo '</pre>';
//$sql = "insert into movies(name,description,date,start_time,end_time,
//    image,cost,duration,sold_out,imagen1,imagen2,imagen3,imagen4,imagen5,
//    video_url,rating,year,genero,directores,actores,aclaracion,porque,aclaracion2)
//select name,description,date,start_time,end_time,image,cost,duration,
//        sold_out,imagen1,imagen2,imagen3,imagen4,imagen5,video_url,rating,year,
//        genero,directores,actores,aclaracion,porque,aclaracion2 from movies where id=9
//";
//$sql = "update movies set sold_out = 0 where id = 14";
//echo $sql;
//$db->setQuery($sql);
//$db->execute();


$db->setQuery("SELECT id,name,date,start_time,end_time from movies ORDER BY date asc,start_time desc");
$movies = $db->loadObjectList();
echo '<pre>';
print_r($movies);
echo '</pre>';
?>
