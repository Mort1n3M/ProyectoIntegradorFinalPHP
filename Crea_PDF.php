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

    require('./fpdf186/fpdf.php'); 
    $pdf = new FPDF('L');

    // Poner acentos
    // mb_convert_encoding() convierte una cadena de caracteres de un encoding a otro
    $acento_a_minuscula = mb_convert_encoding("á", "ISO-8859-1", "UTF-8");  
    $acento_e_minuscula = mb_convert_encoding("é", "ISO-8859-1", "UTF-8");
    $acento_i_minuscula = mb_convert_encoding("í", "ISO-8859-1", "UTF-8");
    $acento_o_minuscula = mb_convert_encoding("ó", "ISO-8859-1", "UTF-8");
    $acento_u_minuscula = mb_convert_encoding("ú", "ISO-8859-1", "UTF-8");
    $nombre             = "Mart".$acento_i_minuscula."n Escobedo M".$acento_e_minuscula."ndez";
    
    // Crear una nueva página
    $pdf = new FPDF('L');
    $pdf->AddPage();
    
    // Centrar todo el contenido - Calcular la posición central
    $pageWidth = $pdf->GetPageWidth();
    $margin = 20;
    $contentWidth = $pageWidth - (2 * $margin);
    $startX = $margin;

    // Logo centrado
    $logoWidth = 33;
        
    // Titulo centrado
    $pdf->SetFont('Arial','B',15);
    $pdf->SetTextColor(32,106,193); 
    $pdf->Cell($contentWidth, 10, $nombre, 1, 1, 'C');
       
    $pdf->Image('./img/logo.png', ($pageWidth - $logoWidth)/2, 30, $logoWidth);
    $pdf->Ln(35);
    
    
    // Texto principal centrado
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell($contentWidth, 10, 'Hola, Mundo!', 0, 1, 'C');
    $pdf->Ln(10);

    // Título de la tabla centrado
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor(32, 106, 193);
    $pdf->Cell($contentWidth, 10, 'N'.$acento_u_minuscula.'mero de usuarios encontrados: ' . count($usuarios), 0, 1, 'C');
    $pdf->Ln(5);

    // Tabla centrada: calcular el ancho de la tabla
    $tableWidth = 250; // Ancho de la tabla
    $startX = ($pageWidth - $tableWidth) / 2;   // Posición X inicial para centrar la tabla
    $pdf->SetX($startX);                        // Establecer la posición X

    // Encabezados de tabla con anchos de columna ajustados
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(32, 106, 193);
    
    $pdf->Cell(38, 10, 'Nombre', 1, 0, 'C', true);
    $pdf->Cell(38, 10, 'Correo', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Password', 1, 0, 'C', true);
    $pdf->Cell(100, 10, 'Intereses', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Fecha Nac.', 1, 1, 'C', true);

    // Datos de tabla con anchos de columna coincidentes
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetTextColor(0, 0, 0);

    foreach ($usuarios as $usuario) {   // Recorrer el arreglo de usuarios
        $pdf->SetX($startX);
        $pdf->Cell(38, 10, $usuario['nombre'] ?? 'N/A', 1, 0, 'C');
        $pdf->Cell(38, 10, $usuario['correo'] ?? 'N/A', 1, 0, 'C');
        $pdf->Cell(25, 10, $usuario['password'] ?? 'N/A', 1, 0, 'C');
        
        // Convierte una matriz de intereses en una cadena con la codificación de acento adecuada
        $intereses = is_array($usuario['intereses']) 
            ? mb_convert_encoding(implode(", ", $usuario['intereses']), "ISO-8859-1", "UTF-8")
            : mb_convert_encoding($usuario['intereses'] ?? 'N/A', "ISO-8859-1", "UTF-8");
            
        $pdf->Cell(100, 10, $intereses, 1, 0, 'C');
        $pdf->Cell(40, 10, $usuario['fecha_nacimiento'] ?? 'N/A', 1, 1, 'C');
    }

    $pdf->Output(); // Generar el PDF
?>