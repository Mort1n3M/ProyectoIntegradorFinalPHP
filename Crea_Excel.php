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

    require 'vendor/autoload.php';  // Cargar la librería PhpWord
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;


    // Crear un objeto Spreadsheet y una hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();    // Obtener la hoja de cálculo activa
    $sheet->setTitle('Users List');             // Establecer el título de la hoja de cálculo

    // Estilo para los títulos
    $styleTitle = [  'font' => [  'bold' => true,  'size' => 24, 'color' => ['rgb' => '206AC1']  ],
        'alignment' => [  'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER  ]
    ];

    // Estilo para los subtítulos
    $styleSubTitle = [  'font' => [ 'bold' => true, 'size' => 16],
         'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER  ]
    ];

    //  Set titulos y  subtitulos
    $sheet->setCellValue('A1', 'Mi primer archivo en Excel');   // Set titulo
    $sheet->mergeCells('A1:E1');                                // Mergen de celdas
    $sheet->getStyle('A1')->applyFromArray($styleTitle);        // Aplicar estilo

    // Usuarios en el archivo JSON
    $sheet->setCellValue('A3', 'Número de usuarios encontrados: ' . count($usuarios));
    $sheet->mergeCells('A3:E3');
    $sheet->getStyle('A3')->applyFromArray($styleSubTitle);

    // Estilo de la cabecera
    $styleHead = [  'font' => ['bold' => true], 'size' => 18, 
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF00BFFF']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
    ];

    // Establecer encabezados para datos JSON
    $sheet->setCellValue('A5', 'Nombre');
    $sheet->setCellValue('B5', 'Correo');
    $sheet->setCellValue('C5', 'Password');
    $sheet->setCellValue('D5', 'Intereses');
    $sheet->setCellValue('E5', 'Fecha Nacimiento');

    // Aplicar estilos de encabezado
    $sheet->getStyle('A5:E5')->applyFromArray($styleHead);

    // Columnas de tamaño automático
    foreach(range('A','E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Agregar datos de usuarios ----------------------------------------------
    $row = 6;
    foreach ($usuarios as $usuario) {
        $sheet->setCellValue('A'.$row, $usuario['nombre'] ?? '');
        $sheet->setCellValue('B'.$row, $usuario['correo'] ?? '');
        $sheet->setCellValue('C'.$row, $usuario['password'] ?? '');
        $intereses = is_array($usuario['intereses']) 
            ? implode(", ", $usuario['intereses']) 
            : ($usuario['intereses'] ?? '');
        $sheet->setCellValue('D'.$row, $intereses);
        $sheet->setCellValue('E'.$row, $usuario['fecha_nacimiento'] ?? '');
        $row++;
    }
    // FIN de Agregar datos de usuarios ---------------------------------------

    
    // Centrar todos los datos
    $lastRow = $row - 1;
    $sheet->getStyle('A6:E'.$lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Establecer encabezados para la descarga
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Mi_Primer_Documento_Excel.xlsx"');
    header('Cache-Control: max-age=0');

    // Crear un objeto Xlsx y guardar el archivo
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
?>