<?php
include 'verificar.php';
?>
<?php
include_once '../lib/database.php';
$db = new DataBase();
$db->setQuery('DELETE FROM movies where id = '.$_GET['id']);
$db->execute();
header('location: index.php')
?>
