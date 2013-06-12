<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['user'])){
    header('location: ingreso.php');
}
?>
