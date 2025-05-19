<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
    <head class="encabezadoIndex">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página de inicio</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="bodyAU">
            <header class="encabezadoIndex">
                <?php if (!empty($_SESSION['usuarioActivo'])): ?>
                    <p id="usuarioActivo">Usuario activo | <?php echo htmlspecialchars($_SESSION['usuarioActivo']); ?> |</p>
                    <?php else: ?>
                        <p id="usuarioActivo">Bienvenido: Usuario no activo...</p>
                <?php endif; ?>
            </header>
        
            <nav>
                <a href="login.php" id="login">Iniciar sesión</a>
                <a href="alta_usuarios.php">Crear usuario</a>
                <a href="usuarios.php"  id="panelImg" class="<?php echo empty($_SESSION['usuarioActivo']) ? 'disabled' : ''; ?>">Administrar usuarios</a>
                <a href="panel_img.php" id="panelImg" class="<?php echo empty($_SESSION['usuarioActivo']) ? 'disabled' : ''; ?>">Panel de control</a>
                <a href="cerrar_sesion.php">Cerrar sesión</a>
            </nav>
                            
            <section class="contenidoEnIndex">
                <h1>Lenguaje de programación Back End</h1>
                <h2>Unidad 3 - 3.1. Archivos de Metadatos</h2>
            </section>
                                
            <footer>
                <p>UdeG virtual - Página de Inicio. Bienvenido  !</p>
            </footer>
        </div>
    </body>
</html>