<?php
    // Inicio de sesión 
    session_start();
    
    $jsonFile = __DIR__ . '/datos/usuarios.json';   // Definir la ruta completa Y NOMBRE del archivo JSON
    $usuarios = [];                                 // Arreglo para almacenar usuarios
    
    // Verifica la exixtencia del archivo JSON y lee su contenido
    if (file_exists($jsonFile)) {
        try {
            $jsonContent = file_get_contents($jsonFile);
            if ($jsonContent === false) {
                throw new Exception("Error reading the JSON file");
            }
            
            $usuarios = json_decode($jsonContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error decoding JSON: " . json_last_error_msg());
            }
            
            $usuarios = $usuarios ?? [];
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            // You might want to show a user-friendly error message
            $usuarios = [];
        }
    }
?>



<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Usuarios Registrados</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>        
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
        <style>
            /* Estilo para el cuerpo */
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                padding-top: 140px;
            }

            /* Estilo para el encabezado */
            header {
                background-color: #161569;
                color: white;
                padding: 10px 0;
                text-align: center;
                position: fixed;        /* Make header fixed */
                top: 0;                /* Position at top */
                width: 100%;           /* Full width */
                z-index: 1000;         /* Ensure header stays above other content */
            }

            /* Estilo para el contenedor de paneles */
            .panelContenedor {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin: 0 auto;
                width: 90%;
                max-width: 1200px;
                padding: 0 20px;
            }

                    /* Estilo para los paneles izquierdo y derecho */
                    .panelIzquierdo {
                        flex: 1;
                        padding: 10px;
                        max-width: 1100px;
                        background-color:rgb(118, 118, 118);
                    }
 
                    /* Estilo para los paneles izquierdo y derecho */
                    .panelDerecho {
                        width: 3000px;
                        padding: 40px;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        background-color:rgb(59, 11, 113, .1);
                    }
                    .panelDerecho img {
                        width: 70px;
                        height: 70px;
                        object-fit: contain;
                    }

            .creaDocumentos {
                margin: .5rem 0;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), filter 0.3s ease;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .creaDocumentos:hover {
                transform: translateY(-8px);
                filter: drop-shadow(0 4px 12px rgba(0, 102, 204, 0.4));
            }

                    
            /* Estilo para la tabla */
            table {
                border-collapse: collapse;
                width: 1000px;    /* Ancho de la tabla */
            }
            /* Estilo para los encabezados de la tabla */
            table th {
                background-color: #161569;
                color: white;
                padding: 10px;
                border: 1px solid #ddd;
            }
            /* Estilo para las filas de la tabla */
            table td {
                padding: 8px;
                border: 1px solid #ddd;
            }
            /* Estilo para las filas pares de la tabla */
            table tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            /* Estilo para las filas al pasar el cursor sobre ellas */
            table tr:hover {
                background-color: #0066cc;
                color: white;
            }

            /* Estilo para la fila seleccionada */
            table tr.selected { 
                background-color: #0066cc; 
                color: white; 
            }
            /* Fin estilo para tabla */ 
                        
            
            /* Estilo para el botón de mostrar usuario */
            #showUserImage {
                background-color:rgb(76, 125, 175);
                color: white;
                border: none;
                padding: 10px 15px;
                width: 12.5rem;
                cursor: pointer;
                border-radius: 20px;
            }
            
            /* Al pasar el cursor sobre botón "Mostrar Usuario" */
            #showUserImage:hover {  background-color:rgb(45, 113, 137);  }

            /* Estilo para el popup (Tarjeta) de imagen */
            .image-popup {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 1000;
                padding: 20px;
                background: white;
                box-shadow: 0 0 20px rgba(0,0,0,0.5);
                border-radius: 20px;
                border: 2px solid #333;
            }

            /* Estilo para la imagen dentro del popup (Tarjeta) */
            .image-popup img {
                max-width: 400px;
                max-height: 400px;
            }

            /* Estilo para el overlay */
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.67);
                z-index: 999;
            }            
                        
            /* Centrar titulos */
            h1, h2 {  margin: 0;  }

            /* Poner un margen al final */
            h1 { margin-bottom: 1rem; }

            /* Estilo para el contenedor de login */
            .loginContenedor H1 {  font-size: 1.5rem; margin-Top: 1.5rem;  }

            /* Estilo para el contenedor de login */
            #noUsuario {  font-size: 1.5rem;  margin-Top: 1.5rem; }
            
            #usuarioActivo { text-align: left;  margin: 20px;  }

            /* Botón para ir a index.php */
            #btInicio {
                background-color:rgb(4, 70, 141);
                color: white;
                border: none;
                padding: 10px 15px;
                width: 12.5rem; /* Ancho fijo */
                cursor: pointer;
                border-radius: 20px;
            }

            /*  Al pasar el cursor sobre botón "Ir al inicio (index.php)"  */
            #btInicio:hover  {  background-color:rgb(1, 46, 95) }

            /* Estilo para centrar el contenedor de login */
            .loginContenedor {
                width: 80%; /* Ajusta el ancho según sea necesario */
                max-width: 950px; /* Ancho máximo */
                margin: 0 auto; /* Centra el contenedor */
                text-align: center; /* Centra el texto dentro del contenedor */
            }

            .btnTarjeta {
                background-color: rgb(76, 125, 175);
                color: white;
                border: none;
                padding: 10px 15px;
                width: 12.5rem;
                cursor: pointer;
                border-radius: 20px;
                margin-bottom: 10px;
            }
            
            .btnTarjeta:hover {
                background-color: rgb(45, 113, 137);
            }

            /* Estilo para el footer */
            footer {
                background-color: #6c757d;
                color: white;
                text-align: center;
                padding: 10px 0;

                width: 100%;
                text-align: center; /* Centra el texto del footer */
                margin-top: 20px; /* Espacio superior para el footer */
            }

            .modalTitulo {  font-size: 2rem; font-weight: bold; font-style: italic; color:rgb(28, 104, 67);   }

 /* Estilo para el contenedor de la imagen */
[data-tooltip] {   position: relative;  }

/* Estilo para el tooltip */
[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 20%;  /* Ajusta la posición horizontal del tooltip */
    transform: translateX(-10%);    /* Ajusta la posición vertical del tooltip */
    padding: 5px 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    border-radius: 4px;
    font-size: 14px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s;
}
/* Estilo para el tooltip cuando se muestra */
[data-tooltip]:hover:before {
    opacity: 1;
    visibility: visible;
}


        </style>
    </head>

    <body>
        <header>
            <h1>Lenguaje de programación Back End</h1>
            <h2>Módulo: Usuarios</h2>
            <?php if (!empty($_SESSION['usuarioActivo'])): ?>
                <p id="usuarioActivo">Usuario activo | <?php echo htmlspecialchars($_SESSION['usuarioActivo']); ?> |</p>
            <?php endif; ?>            
        </header>
        
<!--         <div class="overlay" id="overlay"></div>    
        <div class="image-popup" id="imagePopup">
            <img id="userImage" src="" alt="Datos de usuario...">
        </div> -->
        
        <!-- Contenedor de login -->
        <div class="loginContenedor">
            <h1>Usuarios Registrados</h1>
            <!-- PanelContenedor -->
            <div class="panelContenedor">
                
                <!-- Inicio de Panel Izquierdo -->
                <div class="panelIzquierdo">
                    <!-- Verifica que exista el archivo JSON y tenga usuarios --> 
                    <?php if (!empty($usuarios)): ?>
                    
                        <!--  Se utiliza tabla para mostrar datos  (Tamaño de 950px) -->
                        <table id ="registroUsuario" width="950"> 
                            <colgroup>
                                <col style="background-color: white" span="5" font-style: italic; />
                            </colgroup>

                            <!-- Encabezado de la tabla -->
                            <tr>
                                <th>Nombre</th>
                                <th>Password</th>
                                <th>Correo</th>
                                <th>Intereses</th>
                                <th>Fecha de Nacimiento</th>
                            </tr>

                            <!-- Ciclo para mostrar usuarios desde el archivo JSON -->
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['password']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                    <td><?php echo htmlspecialchars(implode(', ', $usuario['intereses'])); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            
                        </table>
                    <?php else: ?>
                        <p id="noUsuario">No hay usuarios registrados en el archivo.</p>
                    <?php endif; ?>
                    
                </div>  <!-- Cierra div "panelIzquierdo" -->
                <!-- Fin Panel Izquierdo -->
                    
                <!-- Inicio de Panel Derecho -->    
                <div class="panelDerecho">
                <!-- fin de Creacion de docuemntos -->

                    <form method="POST" action="#">
                         <div class="creaDocumentos">
                             <button type="button" id="borrarUsuario" data-tooltip="Borrar un Usuario" style="background: none; border: none; padding: 0; cursor: pointer;">
                                 <img src="./img/baja_usuario.png" style="width: 70px; height: 70px; object-fit: contain;">
                             </button>
                         </div>
                    </form>

                    <form method="POST" action="./ir_alta_usuarios.php">
                         <div class="creaDocumentos">
                             <button type="button" id="verTarjeta" data-tooltip="Generar Tarjeta de Usuario" style="background: none; border: none; padding: 0; cursor: pointer;">
                                 <img src="./img/tarjeta.jpg" style="width: 70px; height: 70px; object-fit: contain;">
                             </button>
                         </div>
                    </form>

                    <form method="POST" action="">
                        <div class="creaDocumentos">
                            <a href="./Crea_Word.php" data-tooltip="Generar Documento Word">
                                <img src="./img/Word-01.jpg" alt="Word">
                            </a>
                        </div>
                    </form>

                    <form method="POST" action="">
                        <div class="creaDocumentos">
                            <a href="./Crea_Excel.php" data-tooltip="Generar Hoja de Cálculo Excel">
                                <img src="./img/Excel-01.jpg" alt="Excel">
                            </a>
                        </div>
                    </form>

                    <form method="POST" action="">
                        <div class="creaDocumentos">
                            <a href="./Crea_PDF.php" data-tooltip="Generar Documento PDF">
                                <img src="./img/PDF-01.jpg" alt="PDF">
                            </a>
                        </div>
                    </form>
                    
                    <form method="POST" action="">
                        <div class="creaDocumentos">
                            <a href="javascript: history.go(-1)" data-tooltip="Ir al inicio">
                                <img src="./img/home.png" alt="PDF">
                            </a>
                        </div>
                    </form>


                </div>
                <!-- Fin Panel Derecho -->
                
            </div>  
            <!-- Cierra div "panelContenedor" -->

        </div>
        <!-- Cierra div "loginContenedor" --> 

        
        <footer>
            <p>UdeG virtual - Página de Inicio  |≡| Sesión personalizada:  <?php echo htmlspecialchars($_SESSION["nombre"])?> |</p>
        </footer>
        
        <!-- Pantalla Modal" -->
        <!-- Modal para mostrar la imagen -->
        <div class="modal" id="imagenModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modalTitulo">Tarjeta Generada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if(isset($_GET['imagen']) && isset($_GET['modal'])): ?>
                            <img src="tarjeta/<?php echo htmlspecialchars($_GET['imagen']); ?>" class="img-fluid" alt="Tarjeta de Usuario">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Se utiliza para mostrar el modal-->
        <script>     
            <?php if(isset($_GET['modal']) && $_GET['modal'] === 'true'): ?>    // Si se recibe el parámetro "modal" y su valor es "true", entonces se muestra el modal 
            window.addEventListener('DOMContentLoaded', (event) => {            // Cuando se cargue el DOM, se muestra el modal
                // Se crea el objeto "modal". Por defecto, el modal está oculto. Utilizo la clase "bootstrap.Modal" para mostrarlo. 
                var modal = new bootstrap.Modal(document.getElementById('imagenModal'));    
                modal.show();   // Se muestra el modal
            });
            <?php endif; ?>
        </script>

        <!-- Se utiliza para mostrar la tarjeta-->
        <script>
            let selectedRow = null; // Variable para almacenar la fila seleccionada
            const table = document.querySelector('table');                  // Selecciona la tabla
            const verTarjetaBtn = document.getElementById('verTarjeta');    // Selecciona el botón "Ver tarjeta"

            // Selección de fila
            table.addEventListener('click', (e) => {    // 1. Selecciona la tabla y 2. Agrega un evento click
                const row = e.target.closest('tr');     // 3. Selecciona la fila más cercana al elemento clickeado
                if (!row || !row.cells || row.cells.length === 0 || row.cells[0].tagName === 'TH') return;  // 4. Si no se selecciona una fila 
                                                                                                            // o si la fila no tiene celdas o 
                                                                                                            // si la primera celda es un encabezado, 
                                                                                                            // entonces no se hace nada
            
                if (selectedRow) selectedRow.classList.remove('selected');  // Si hay una fila seleccionada, se le quita la clase "selected"    
                row.classList.add('selected');      // Se agrega la clase "selected" a la fila seleccionada
                selectedRow = row;                  // Se almacena la fila seleccionada en la variable "selectedRow"
                verTarjetaBtn.disabled = false;     // Se habilita el botón "Ver tarjeta"
            });
        
            // Generar tarjeta
            verTarjetaBtn.addEventListener('click', () => {     //   Agrega un evento click al botón "Ver tarjeta", en caso de error.
            if (!selectedRow) {                                 // Si no se ha seleccionado una fila
                alert('Por favor, seleccione un usuario primero');   // Se muestra una alerta
                return;    // Se sale de la función 
            }
        
            const cells = selectedRow.cells;        // Selecciona las celdas de la fila seleccionada
            const params = new URLSearchParams({    // Crea una instancia de URLSearchParams, con los datos de la fila seleccionada
                nombre: cells[0].textContent,
                correo: cells[2].textContent,
                intereses: cells[3].textContent,
                fecha: cells[4].textContent,
                titleSize: '3rem',
                dataSize: '2.5rem',
                fontWeight: 'bold'
            });
        
            window.location.href = `generar_tarjeta.php?${params.toString()}`;  // Dirigir a la páginag "Generar_tarjeta.php"
        });
        </script>

    </body>
    
</html>