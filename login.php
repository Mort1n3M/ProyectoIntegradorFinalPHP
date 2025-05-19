<?php
session_start();

// Leer usuarios desde un archivo JSON
$usuarioActivo = '';
$mensajeError = '';
$jsonFile = __DIR__ . '/datos/usuarios.json';
$usuarios = [];
$admin_user     ="admin";
$admin_password ="321";

if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $usuarios = json_decode($jsonContent, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    
    // Validar el usuario con datos JSON
    $usuarioValido = false;
    
    if ($nombre === $admin_user && $password === $admin_password) {
        $usuarioActivo = $admin_user;
        $usuarioValido = true;
    } else {
        foreach ($usuarios as $usuario) {
            if ($usuario['nombre'] === $nombre && $usuario['password'] === $password) {
                $usuarioActivo = $nombre;
                $usuarioValido = true;
                break;
            }
        }
    }


    if (!$usuarioValido) {
        $mensajeError = 'Usuario no válido';
    } else {
        $_SESSION['usuarioActivo'] = $usuarioActivo;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso a Usuario</title>
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

        .loginContainer {
            max-width: 300px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 16px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: rgb(23, 179, 179);
        }

        .form-group input {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            background-color: rgb(9, 134, 134);
            margin-bottom: .5rem;
        }

        #isLogin {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            width: 19.5rem;
            cursor: pointer;
            border-radius: 20px;
            margin-bottom: .5rem;
        }
        #isLogin:hover {
            background-color: #088124;
        }

        #btInicio {
            background-color: rgb(4, 70, 141);
            color: white;
            border: none;
            padding: 10px 15px;
            width: 19.5rem;
            cursor: pointer;
            border-radius: 20px;
        }
        #btInicio:hover {
            background-color: rgb(1, 46, 95);
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

        /* Mensaje de error */
        .error {
            background-color: rgb(202, 99, 99);
            color:rgb(248, 224, 6);
            text-align: center;
            font-weight: bold;
            letter-spacing: 0.125rem;
            padding: 1rem;
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <header>       
        <h1>Lenguaje de programación Back End</h1>
        <h2>Acceso a usuario</h2>
        <?php if (!empty($_SESSION['usuarioActivo'])): ?>
            <p id="usuarioActivo">Usuario activo | <?php echo htmlspecialchars($_SESSION['usuarioActivo']); ?> |</p>
        <?php endif; ?>
    </header>
    
    <div class="loginContainer">
        <!-- Si se encuentra el usuario -->
        <?php if ($usuarioActivo): ?>
            <div id="usuarioActivo">
            <?php if ($usuarioActivo === $admin_user): ?>
                    <h3>Bienvenido(a) Administrador</h3>
                <?php else: ?>
                    <h3>Bienvenido(a) amigo(a), <?php echo htmlspecialchars($usuarioActivo); ?></h3>
                <?php endif; ?>
                <!-- <h3>Bienvenido(a) amigo(a), <?php echo htmlspecialchars($usuarioActivo); ?></h3>    -->
                 

            </div>
        <?php else: ?>
            <?php if ($mensajeError): ?>
                <div class="error"><?php echo htmlspecialchars($mensajeError); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre">Nombre de usuario:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button id="isLogin" type="submit">Iniciar sesión</button>
                </div>
            </form>
        <?php endif; ?>
        
        <form method="GET" action="./index.php">
            <div class="form-group">
                <button id="btInicio" type="submit">Ir al inicio</button>
            </div>
        </form>
    </div>

    <footer>
        <p>UdeG virtual - Acceso a Usuario |≡| Sesión personalizada: <?php echo htmlspecialchars($_SESSION["nombre"]) ?> |</p>
    </footer>
</body>
</html>