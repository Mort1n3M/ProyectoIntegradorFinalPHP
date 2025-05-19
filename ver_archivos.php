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

        /* Propiedades del encabezado */
        .headerVA {
            background-color: #161569;
            color: white;
            padding: 12px 0;
            text-align: center;
        }

        /* Contenedor del BOTÓN para ir a panel de control */
        .contenedorPanelControl {
            display: flex;
            justify-content: center; /* Centrar el botón */
            background-color:rgb(228, 228, 228); 
            padding: 10px; /* Espaciado interno */
            margin-bottom: 1rem; /* Margen inferior */
        }

        #btnPanelControl {
            color: white; 
            text-decoration: none; 
            padding: 10px; 
            background-color: rgb(83, 4, 122); 
            border-radius: 5px;
            width: 14rem;
            text-align: center;
        }
        #btnPanelControl:hover {
            background-color: rgb(187, 81, 190);
        }
        
        /* Estilos para los paneles */
        #panel-izquierdo {
            width: 30%;
            border-right: 1px solid #ccc;
            padding: 10px;
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
            text-decoration: underline;
        }
        
        #panel-izquierdo {
            background-color: rgb(182, 182, 182); 
            height: 5em;
            line-height: 1em;
            overflow-x: hidden;
            overflow-y: scroll;
            width: 30%;
            height: 82%;
            border: 1px solid;
            margin: .4rem;
        }
        
        #panel-derecho {
            width: 70%;
            padding: 10px;
            background-color: rgb(182, 182, 182); 
            margin: .4rem;            
        }

        iframe {
            width: 100%;
            height: 500px;
            border: none;
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

        /* Contenedor principal para los paneles */
        .contenedorPrincipal {
            display: flex; /* Usar flexbox para alinear los paneles */
            flex: 1; /* Tomar el espacio restante */
        }
    </style>
</head>

<body>

<header class="headerVA"> 
    <h1 id="tituloVA">Lenguaje de programación Back End - Visualizar archivos</h1>
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
                <li onclick="cargarArchivo('<?php echo $archivo; ?>')"><?php echo $archivo; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="panel-derecho">
        <h3>Vista Previa</h3>
        <iframe id="visor" src="" frameborder="0"></iframe>
    </div>
</div>

<footer>
    <p>UdeG virtual - Visualizar archivos |≡| Sesión personalizada: <?php echo htmlspecialchars($_SESSION["nombre"]) ?> |</p>        
</footer>  

<script>
    function cargarArchivo(archivo) {
        // Cambiar la fuente del iframe al archivo seleccionado
        const visor = document.getElementById('visor');
        visor.src = 'MisDescargas/' + archivo;
    }
</script>


</body>
</html>