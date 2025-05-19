<?php
    $targetDir = "./MisDescargas/";
    $archivos = array_diff(scandir($targetDir), array('..', '.'));
    echo json_encode($archivos);
?>