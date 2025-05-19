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

    try {
            require 'vendor/autoload.php';

            $phpword = new \PhpOffice\PhpWord\PhpWord();    // Crear un objeto PHPWord
            if (!$phpword) { throw new Exception('No se pudo crear el objeto PHPWord');  }      // Si no se pudo crear el objeto PHPWord, lanzar una excepción

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');    // Tipo de archivo
            header('Content-Disposition: attachment;filename=Mi_Primer_Documento_Word.docx');  // Nombre del archivo
            header('Cache-Control: max-age=0'); // No guardar en caché

            $section = $phpword->addSection();  //  Crear una sección

            $section->addText("Mi primer documento en Word con PHP", 
                array('size' => 18, 'color' => '206AC1', 'bold' => true),
                array('alignment' => 'center')
            );

            $section->addTextBreak();    // Salto de linea
            
            $section->addText("Este es un ejemplo de como crear un archivo word con PHP.",
            array('name' => 'Arial', 'size' => 12),     // Fuente y tamaño de letra
            array('alignment' => 'left')                // Alineación
        );
        
        $section->addText("Despúes de batallar con la librería PhpWord, me di cuenta que era muy fácil de utilizar. Sólo era cuestión de instalarla y probar sus funciones. No sin antes instalar la última version de PHP (8.2.12) ya que la versión 8.0.0 y anteriores genera error en algunas variables del sistema.",
        array('name' => 'Arial', 'size' => 12), array('alignment' => 'left')   );
        $section->addTextBreak();   // Salto de linea
        
        $section->addText("Impresión de datos en: " . $jsonFile);     
        $section->addTextBreak();   // Salto de linea

        // Imprimir datos den la tabla -------------------------------------------
        $section->addText("Número de usuarios encontrados: " . count($usuarios), 
            array('size' => 13, 'color' => '206AC1', 'bold' => true),
            array('alignment' => 'center')
        );
        $section->addTextBreak();   // Salto de linea

        // Agregar encabezado de tabla con estilo mejorado
        $tableStyle = array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80);
        $table = $section->addTable($tableStyle);
        $table->addRow();
        $cellStyle = array('bold' => true, 'size' => 11, 'name' => 'Arial');
        $table->addCell(2500)->addText('Nombre', $cellStyle);
        $table->addCell(3000)->addText('Correo', $cellStyle);
        $table->addCell(2000)->addText('Password', $cellStyle);
        $table->addCell(3000)->addText('Intereses', $cellStyle);
        $table->addCell(2000)->addText('Fecha Nacimiento', $cellStyle);

        // Añadir datos de usuario con un manejo mejorado de datos
        foreach ($usuarios as $usuario) {
            $table->addRow();
            $table->addCell(2500)->addText($usuario['nombre'] ?? 'N/A');
            $table->addCell(3000)->addText($usuario['correo'] ?? 'N/A');
            $table->addCell(2000)->addText($usuario['password'] ?? 'N/A');
            // Handle array of interests if applicable
            $intereses = is_array($usuario['intereses']) 
                ? implode(", ", $usuario['intereses']) 
                : ($usuario['intereses'] ?? 'N/A');
            $table->addCell(3000)->addText($intereses);
            $table->addCell(2000)->addText($usuario['fecha_nacimiento'] ?? 'N/A');
        }
        //--------------------

        $section->addTextBreak();    // Salto de linea

        $objetwriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');    // Crear un objeto para escribir el archivo
        $objetwriter->save('php://output');     // Guardar el archivo

        } 
        catch (Exception $e) {    // Si se produce una excepción, mostrar un mensaje de error
            //-------
            error_log($e->getMessage());    // Mostrar un mensaje de error
            header('HTTP/1.1 500 Internal Server Error');   // Mostrar un mensaje de error
            echo "An error occurred while creating the document.";      // Mostrar un mensaje de error
        }
       
?>