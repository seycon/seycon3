<?php
    session_start();
    $_SESSION['userID'] = NULL;
    unset($_SESSION['userID']);
    $_SESSION['userName'] = NULL;
    unset($_SESSION['userName']);
    session_unset();
    session_destroy();
    header("Location: index.php");
?>