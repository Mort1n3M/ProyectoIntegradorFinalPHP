<?php
    session_start();
    
    // Definimos la ruta del directorio "MisDescargas"
    $directorio = 'MisDescargas';
    
    // Obtenemos la lista de archivos en el directorio
    $archivos = array_diff(scandir($directorio), array('..', '.'));
    
    // Filtramos los archivos por las extensiones permitidas
    $extensiones_permitidas = ['txt', 'xml', 'pdf', 'mp4', 'doc', 'xlsx', 'png', 'jpg'];
    $archivos_filtrados = array_filter($archivos, function($archivo) use ($extensiones_permitidas) {
        return in_array(pathinfo($archivo, PATHINFO_EXTENSION), $extensiones_permitidas);
    });
    
    // Manejo de la eliminación de archivos
    if (isset($_POST['eliminar'])) {
        $archivo_a_borrar = $_POST['archivo'];
        $ruta_archivo = $directorio . '/' . $archivo_a_borrar;
        if (file_exists($ruta_archivo)) {
            unlink($ruta_archivo);
            header("Location: " . $_SERVER['PHP_SELF']); // Redirigir para evitar reenvío del formulario
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visualizador de Archivos</title>
        <style>
            body {
                margin: 0; 
                padding: 0;
                font-family: Arial, sans-serif;
                display: flex;
                flex-direction: column;
                height: 100vh;
            }

            .headerVA {
                background-color: #161569;
                color: white;
                padding: 12px 0;
                text-align: center;
            }

            .contenedorPanelControl {
                display: flex;
                justify-content: center; 
                background-color: rgb(228, 228, 228); 
                padding: 10px; 
                margin-bottom: 1rem; 
            }

            #btnPanelControl {
                color: white; 
                text-decoration: none; 
                padding: 10px; 
                background-color: rgb(255, 0, 0); 
                border-radius: 5px;
                width: 14rem;
                text-align: center;
            }
            #btnPanelControl:hover {
                background-color: rgb(214, 6, 6); 
            }

            #panel-izquierdo {
                width: 30%;
                border-right: 1px solid #ccc;
                padding: 10px;
                background-color: rgb(182, 182, 182); 
                height: 82%;
                overflow-y: scroll;
                margin: .4rem;
            }

            #panel-izquierdo ul {
                list-style-type: none;
                padding: 0;
            }
            #panel-izquierdo li {
                margin: 5px 0;
                cursor: pointer;
            }
            #panel-izquierdo li:hover {
                font-size: 1rem;
                color: rgb(255, 255, 255); 
                background-color: rgb(112, 2, 2); 
                padding: .3rem;
            }

            iframe {
                width: 100%;
                height: 500px;
                border: none;
            }

            .contenedorPrincipal {
                display: flex; 
                flex: 1; 
                justify-content: center; 
                align-items: center; 
            }

            /* Estilos para la ventana modal */
            .modal {
                display: none; 
                position: fixed; 
                z-index: 1; 
                left: 0;
                top: 0;
                width: 60%; /* Cambiado a 60% */
                height: auto; 
                overflow: auto; 
                background-color: rgba(0,0,0,0.4); 
                padding-top: 90px;  
                left: 50%; /* Centrado horizontalmente */
                transform: translateX(-50%); /* Centrado horizontalmente */
            }

            .modal-content {
                background-color: #fefefe;
                margin: 5% auto; 
                padding: 20px;
                border: 1px solid #888;
                width: 80%; 
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            .close:hover, .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
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

            /* Estilos para los botones */
            .btn-borrar {
                background-color: blue; /* Color azul para "Sí, borrar" */
                color: white;
                padding: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .btn-cancelar {
                background-color: red; /* Color rojo para "Cancelar" */
                color: white;
                padding: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .btn-borrar:hover {
                background-color: darkblue; /* Efecto hover para "Sí, borrar" */
            }

            .btn-cancelar:hover {
                background-color: darkred; /* Efecto hover para "Cancelar" */
            }

            .advertencia {
                color: red; /* Color rojo para la advertencia */
            }
        </style>

        <!-- Funciones para el manejo de la ventana modal  -->
        <script>
            function confirmarBorrado(archivo) {
                document.getElementById('archivoAEliminar').value = archivo;
                document.getElementById('myModal').style.display = "block";
                document.getElementById('nombreArchivo').innerHTML = archivo; // Mostrar el nombre del archivo
            }

            function cerrarModal() {
                document.getElementById('myModal').style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == document.getElementById('myModal')) {
                    cerrarModal();
                }
            }
        </script>
    </head>

    <body>       
        <header class="headerVA"> 
            <h1 id="tituloVA">Lenguaje de programación Back End - Borrar archivos</h1>
            <?php if (!empty($_SESSION['usuarioActivo'])): ?>
                    <p id="usuarioActivo">Usuario activo | <?php echo htmlspecialchars($_SESSION['usuarioActivo']); ?> |</p>
            <?php endif; ?>
        </header>

        <div class="contenedorPanelControl">  
            <a id="btnPanelControl" href="panel_img.php">Ir a Panel de Control</a>
        </div>

        <div class="contenedorPrincipal">
            <div id="panel-izquierdo">
                <h3>Archivos en MisDescargas</h3>
                <ul>
                    <?php foreach ($archivos_filtrados as $archivo): ?>
                        <li onclick="confirmarBorrado('<?php echo htmlspecialchars($archivo); ?>')"><?php echo htmlspecialchars($archivo); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Ventana modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="cerrarModal()">&times;</span>
                <p class="advertencia">¡Advertencia!</p>
                <p>¿Estás seguro de que deseas borrar este archivo: <span class="advertencia" id="nombreArchivo"></span>?</p>
                <form id="formEliminar" method="post">
                    <input type="hidden" name="archivo" id="archivoAEliminar">
                    <button type="submit" name="eliminar" class="btn-borrar">Sí, borrar</button>
                    <button type="button" onclick="cerrarModal()" class="btn-cancelar">Cancelar</button>
                </form>
            </div>
        </div>    

        <footer>
            <p>UdeG virtual - Borrar archivos |≡| Sesión personalizada: <?php echo htmlspecialchars($_SESSION["nombre"]) ?> |</p>        
        </footer>  
    </body>
</html>