<?php
    session_start();
    session_destroy();

    // Redireccionar al formulario de registro
    header('Location: index.php');
    exit();
?>