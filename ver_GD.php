<?php
    $testGD = get_extension_funcs("gd"); // Lista de funciones de agarre
    if (!$testGD){
        echo "GD no instalada.";
        phpinfo();  // Mostrar la configuración de php para el servidor web
        exit;
    }
    echo"<pre>".print_r($testGD,true)."</pre>";  // Mostrar la lista de funciones GD
