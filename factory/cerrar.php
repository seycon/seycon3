<?php
 session_start();
 $_SESSION['nombreusuarioF'] = NULL;
 unset($_SESSION['nombreusuarioF']);
 $_SESSION['idusuarioF'] = NULL;
 unset($_SESSION['idusuarioF']);
 session_unset();
 session_destroy();
 header("Location: index.php");
?>