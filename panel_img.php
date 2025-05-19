<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control</title>
    <style>
        body {
            margin: 0; 
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .panelImg {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .panelImg hr {
            width: 20rem;
            margin: .5rem auto;
        } 
        
        header {
            background-color: #161569;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        /* Centrar texto */
        h1, h2 {  margin: 0; }

        h1 {  margin-bottom: 1rem; }

        .containerPI {    
            max-width: 26rem;
            margin: 30px auto;
            padding: 15px 0 35px 15px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        input[type="file"] { margin: 12px 0;  }

        #files, input[type="submit"], #btInicio, #verArchivo, #BorrarArchivo {
            background-color: rgb(167, 40, 89);
            color: white;
            border: none;
            padding: 10px 15px;
            width: 19.5rem;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }
        
        input[type="submit"] {
            background-color: rgb(1, 24, 88);
        }
        
        #verArchivo {
            background-color: rgb(1, 24, 88);
        }
        #verArchivo:hover {
            background-color: rgb(127, 3, 131);
        }

        #BorrarArchivo {
            background-color: rgb(1, 24, 88);
        }
        #BorrarArchivo:hover {
            background-color: rgb(214, 6, 6); /* Color al pasar el mouse */
        }

        #btInicio {
            background-color: rgb(1, 24, 88);
        }
        #btInicio:hover {
            background-color: rgb(23, 100, 182);
        }

        input[type="submit"]:hover {
            background-color: #088124;
        }

        #xArchivo {
            font-weight: bold;
            font-size: 1.4rem;
            color: rgb(31, 29, 145);
            margin-bottom: .5rem;
        }

        footer {
            background-color: #6c757d;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            /* Posición fija a Píe de Página
            position: fixed;
            bottom: 0;*/
        }
    </style>
</head>
<body>
    <header>          
        <h1>Lenguaje de programación Back End</h1>
        <h2>Panel de control</h2>
        <?php if (!empty($_SESSION['usuarioActivo'])): ?>
            <p id="usuarioActivo">Usuario activo | <?php echo htmlspecialchars($_SESSION['usuarioActivo']); ?> |</p>
        <?php endif; ?>
    </header>

    <main>
        <div class="containerPI"> 
            <form class="panelImg" action="" method="POST" enctype="multipart/form-data">
                <label id="xArchivo" for="files">Selección de archivos</label>
                <hr>
                <input type="file" name="files[]" id="files" multiple required>
                <hr>
                <input id="subirArchivo" type="submit" value="Subir archivos al servidor">
                <button id="verArchivo" onclick="window.location.href='ver_archivos.php';">Ver archivo</button>
                <button id="BorrarArchivo" onclick="window.location.href='borrar_archivo.php';">Borrar Archivo</button>
                <button id="btInicio" onclick="window.location.href='index.php';">Ir al inicio</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $targetDir = "MisDescargas/";

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                echo '<br><hr>';

                $files = $_FILES['files'];
                $fileCount = count($files['name']);
                $uploadSuccess = true;

                for ($i = 0; $i < $fileCount; $i++) {
                    $targetFile = $targetDir . basename($files['name'][$i]);
                    if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                        echo "<p>El archivo " . "<b>" . htmlspecialchars($files['name'][$i]) . "</b>" . " se ha subido correctamente.</p>";
                    } else {
                        echo "<p>Error al subir el archivo " . htmlspecialchars($files['name'][$i]) . ".</p>";
                        $uploadSuccess = false;
                    }
                }
                if ($uploadSuccess) {
                    echo '<hr><br><span style="color:#161569; font-style: italic; size 1rem;">Todos los archivos (' . $fileCount . ') se han subido correctamente.</span><br>';
                }
            }
            ?>
        </div>            
    </main>
    
    <footer>
        <p>UdeG virtual - Panel de control |≡| Sesión personalizada:  <?php echo htmlspecialchars($_SESSION["nombre"])?> |</p>        
    </footer>

</body>
</html>