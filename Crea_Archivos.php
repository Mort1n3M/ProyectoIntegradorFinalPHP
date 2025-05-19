<?php
    // Inicio de sesión 
    session_start();
    
    $jsonFile = __DIR__ . '/datos/usuarios.json';   // Definir la ruta completa Y NOMBRE del archivo JSON
    $usuarios = [];                                 // Arreglo para almacenar usuarios
    
    // Verifica la exixtencia del archivo JSON y lee su contenido
    if (file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);        // file_get_contents() lee el contenido de un archivo
        $usuarios = json_decode($jsonContent, true) ?? [];  // json_decode() decodifica un string en formato JSON
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Creación de Archivos</title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        
        header {
            background-color: #161569;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        
        h1, h2 { margin: 0; }
        h1 { margin-bottom: 1rem; }

        .archivosContenedor {
            display: flex;
            align-items: center;
            max-width: 450px;
            margin: 10% auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 16px rgba(0, 0, 0, 0.1);
        }
        h3 {
            background-color:rgb(213, 224, 234);
            color:rgb(31, 31, 31);
            padding: 2rem; 
            margin: auto;
            text-align: center;
            font-size: 1.25rem;
        }

        .form-group {
            transition: all 0.3s ease-in-out;
            padding: 10px;
        }

        .form-group:hover {
            transform: scale(1.1);
            filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.3));
        }

        footer {
            background-color: #6c757d;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        </style>
    </head>
    <!-- -->
    <body>
        <header>       
            <h1>Lenguaje de programación Back End</h1>
            <h2>Creación de Archivos</h2>
            <?php if (!empty($_SESSION['usuarioActivo'])): ?>
                <p>Usuario activo | <?php echo htmlspecialchars($_SESSION['usuarioActivo']); ?> |</p>
            <?php endif; ?>
        </header>
        <!-- -->
        <h3>Seleccione el tipo de archivo a crear</h3>
        <div class="archivosContenedor">
            <form method="POST" action="">
                <div class="form-group">
                   <a href="./Crea_Word.php"><img src="./img/Word-01.jpg" alt=""></a>
                </div>
            </form>
            
            <form method="POST" action="">
                <div class="form-group">
                   <a href="./Crea_Excel.php"><img src="./img/Excel-01.jpg" alt=""></a>
                </div>
            </form>
            
            <form method="POST" action="">
                <div class="form-group">
                   <a href="./Crea_PDF.php"><img src="./img/PDF-01.jpg" alt=""></a>
                </div>
            </form>
        </div>
    <footer>
        <p>UdeG virtual - Acceso a Usuario |≡| Sesión personalizada: <?php echo htmlspecialchars($_SESSION["nombre"]) ?> |</p>
    </footer>
    </body>
</html>