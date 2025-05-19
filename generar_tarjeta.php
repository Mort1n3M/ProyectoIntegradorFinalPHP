<?php
    // Inicio de sesión 
    session_start();

    // Verificar si se recibieron los datos
    if (!isset($_GET['nombre']) || !isset($_GET['correo']) || !isset($_GET['intereses']) || !isset($_GET['fecha'])) {
        die('Datos incompletos');   // Si no se recibieron los datos, se termina la ejecución.
                                    // El mensaje "Datos incompletos" se muestra en la ventana modal.
                                    // die() es una función que termina el programa y es equivalente a exit()
    }

    // Crear directorio si no existe
    $dir = __DIR__ . '/tarjeta';
    if (!file_exists($dir)) {  mkdir($dir, 0777, true);  }

    // Establecer el tipo de contenido, ene este caso, imágen del tipo PNG
    header('Content-Type: image/png');

    // Dimensiones de la imagen 
    $width  = 950;
    $height = 380;
    $imagen = imagecreatetruecolor($width, $height);    // Crea una nueva imagen

    // Definir Colores
    $blanco = imagecolorallocate($imagen, 255, 255, 255);   
    $negro  = imagecolorallocate($imagen, 0, 0, 0);
    $azul   = imagecolorallocate($imagen, 0, 102, 204);
    $rojo   = imagecolorallocate($imagen, 255, 0, 0);

    
    /* ------------------------------------------------------------------------
        Definir fuente y tamaños.
        NOTA: El titulo "Tarjeta Generada" y las propiedades de esta Ventana 
        Modal, se ubican en el archivo "usuarios.php", clase "modalTitulo".
    -------------------------------------------------------------------------*/
    $font      = 'C:\Windows\Fonts\arial.ttf';
    $fontBold  = 'C:\Windows\Fonts\ARLRDBD.TTF'; // Letra: Arial Bold font
    $titleSize = 30;
    $textSize  = 25;

    // Convertir a ISO-8859-1 (para uso de acentos) 
    $titulo = mb_convert_encoding("Datos del Usuario", 'ISO-8859-1', 'UTF-8');
    
    // Rellenar fondo (solo una vez)
    imagefilledrectangle($imagen, 0, 0, $width, $height, $blanco);
    
    // Dibujar borde
    imagerectangle($imagen, 0, 0, $width-1, $height-1, $azul);
    
    // Poner el título "Tarjeta de Usuario"
    imagettftext($imagen, $titleSize, 0, 20, 60, $azul, $fontBold, $titulo);
    
    // Agregar datos del usuario con acentos
    $nombre = mb_convert_encoding("Nombre: " . $_GET['nombre'], 'ISO-8859-1', 'UTF-8');
    $correo = mb_convert_encoding("Correo: " . $_GET['correo'], 'ISO-8859-1', 'UTF-8');
    $intereses = mb_convert_encoding("Intereses: " . $_GET['intereses'], 'ISO-8859-1', 'UTF-8');
    $fecha = mb_convert_encoding("Fecha de nacimiento: " . $_GET['fecha'], 'ISO-8859-1', 'UTF-8');

    /*--------------------------------------------------------------------------------------
        imagettftext ($imagen, $size, $angle, $x, $y, $color, $fontfile, $text). 
        Funcion para agregar texto en una imagen utilizando la fuente de texto especificada.
    --------------------------------------------------------------------------------------*/
    // Agregar línea horizontal
    imagesetthickness($imagen, 2); // Grosor de la línea
    imageline($imagen, 20, 80, $width - 20, 80, $azul); // Línea horizontal
    imagettftext($imagen, $textSize, 0, 20, 140, $azul, $font, $nombre);
    imagettftext($imagen, $textSize, 0, 20, 200, $azul, $font, $correo);
    imagettftext($imagen, $textSize, 0, 20, 260, $azul, $font, $intereses);
    imagettftext($imagen, $textSize, 0, 20, 320, $azul, $font, $fecha);

    // Generar nombre único para la imágen
    $nombreArchivo = $dir . '/' . str_replace(' ', '_', $_GET['nombre']) . '.png';
    
    // Guardar imágen
    imagepng($imagen, $nombreArchivo);
    
    // Liberar memoria
    imagedestroy($imagen);  

    // Redireccionar a la página de usuarios con el nombre de la imagen
    header('Location: usuarios.php?imagen=' . basename($nombreArchivo) . '&modal=true');
    exit();
?>