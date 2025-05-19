<?php
    session_start();
    $nombre_sesion = session_name();
    $_SESSION["nombre"] = "UdeGPlus";

    // Crear directorio de datos si no existe
    $datosDir = __DIR__ . '/datos';
    if (!file_exists($datosDir)) {
        mkdir($datosDir, 0777, true);
    }

    // Definir la ruta y nombre del archivo JSON
    $jsonFile = $datosDir . '/usuarios.json';

    // Initializa o carga los usuarios existentes
    $usuarios = [];
    if (file_exists($jsonFile)) {
        $usuarios = json_decode(file_get_contents($jsonFile), true) ?? [];
    }

    // Guarda la sesión en un Cookie
    if (!isset($_COOKIE['UdeGPlus_inicio_sesion'])) {
        setcookie('UdeGPlus_inicio_sesion', date('Y-m-d H:i:s'), time() + 3600);
    }

    // Creando el arreglo
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = [
            'nombre' => $_POST['nombre'],
            'correo' => $_POST['correo'],
            'password' => $_POST['password'],
            'intereses' => $_POST['intereses'],
            'fecha_nacimiento' => date("d/m/Y", strtotime($_POST['fecha_nacimiento'])),
        ];

        // Agrega un nuevo usuario al arreglo
        $usuarios[] = $usuario;

        // Guarda a un archivo con formato JSON con caracteres Unicode (Pone acentos)
        file_put_contents($jsonFile, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));   

        // Redireccionar 
        header('Location: ./alta_usuarios.php');
        exit();
    }

    // Determina el stado de los link
    $hayUsuarios = !empty($usuarios);
    $usuarioActivo = !empty($_SESSION['usuarioActivo']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Usuarios</title>    
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="encabezadoAU">
            <h1 id="encabezadoAU_h1">Lenguaje de programación Back End</h1>
            <h2 id="encabezadoAU_h2">Alta de usuarios</h2>
            <?php if ($usuarioActivo): ?>
                <p id="usuarioActivoAU">Usuario activo | <?php echo htmlspecialchars($_SESSION['usuarioActivo']); ?> |</p>
                <?php endif; ?>
        </div>   
    </header>
    
    <main>
        <div class="containerFormAU">
            <form id="auForma" method="POST" action="">
                <div class="form-groupAU">
                    <label for="nombre">Nombre de usuario:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-groupAU">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="form-groupAU">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-groupAU">
                    <label for="intereses">Intereses:</label>
                    <select id="intereses" name="intereses[]" multiple required>
                        <option value="Cine">Cine</option>
                        <option value="Deportes">Deportes</option>
                        <option value="Lectura">Lectura</option>
                        <option value="Música">Música</option>
                        <option value="Tecnología">Tecnología</option>
                        <option value="Viajes">Viajes</option>
                    </select>
                </div>
                <div class="form-groupAU">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimientoAU" name="fecha_nacimiento" required>
                </div>
                <hr>
                <div class="form-groupAU">
                    <button id="btnAgregaUsuarioAU" type="submit">Agregar usuario</button>
                </div>
            </form>
            
            <form method="GET" action="./usuarios.php">
                <div class="form-groupAU">
                    <button id="btnVerUserRegAU" type="submit">Ver usuarios registrados</button>
                </div>
            </form>
            
            <form method="GET" action="./index.php">
                <div class="form-groupAU">
                    <hr>
                    <button id="btInicioAU" type="submit">Ir al inicio</button>
                </div>
            </form>           
        </div>
    </main>
    
    <footer class="footerAU">
        <p>UdeG virtual - Alta de Usuarios |≡| Sesión personalizada:  <?php echo htmlspecialchars($_SESSION["nombre"])?> |</p>
    </footer>
</body>
</html>