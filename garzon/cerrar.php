<?php
    session_start();
    $_SESSION['nombretrestaurante'] = NULL;
    unset($_SESSION['nombretrestaurante']);
    $_SESSION['idusuariorestaurante'] = NULL;
    unset($_SESSION['idusuariorestaurante']);
    session_unset();
    session_destroy();
    header("Location: index.php");
?>