<?php

include('connection.php');

require 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory;

$excel_file = 'data\test-data-import.xlsx';

try {
    $spreadsheet = IOFactory::load($excel_file);
} catch (Exception $e) {
    die('Error al cargar el archivo Excel: ' . $e->getMessage());
}

$worksheet = $spreadsheet->getActiveSheet();
$data = $worksheet->toArray();

$isFirstRow = true;

// Iterar a través de las filas del archivo XLSX
foreach ($data as $key => $row) {
    // Si es la primera fila, omítela
    if ($isFirstRow) {
        $isFirstRow = false;
        continue;
    }

    // Obtener las URLs de las columnas AA hasta AK
    for ($i = 27; $i <= 36; $i++) {
        $column_index = 'A' . chr(64 + $i); // Convertir el índice en letra (AA, AB, AC, ..., AK)
        $image_url = $row[$i - 1]; // Restar 1 para ajustar el índice a partir de 0 en el array
        if (!empty($image_url)) {
            // Descargar la imagen y guardarla en una carpeta de destino
            $image_data = file_get_contents($image_url);
            $image_filename = basename($image_url);
            $destination_folder = 'images/';
            if (!file_exists($destination_folder)) {
                mkdir($destination_folder, 0777, true);
            }
            file_put_contents($destination_folder . $image_filename, $image_data);
            echo "Imagen descargada y guardada: $image_filename<br>";
        }
    }
}

echo "Proceso de descarga de imágenes finalizado.";

// Cerrar la conexión a la base de datos
$conn->close();
?>
