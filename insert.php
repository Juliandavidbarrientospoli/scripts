<?php

include('connection.php');
// biblioteca PhpSpreadsheet
require 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory;

$excel_file = 'data\test-data-import.xlsx'; 

// Array de mapeo para relacionar las columnas del archivo XLSX con las columnas de la base de datos
$column_mapping = array(
    0 => 'product_ordering',
    1 => 'brand',
    2 => 'product_line',
    3 => 'item_number',
    4 => 'sap_material_number',
    5 => 'upc',
    6 => 'product_name',
    7 => 'category',
    8 => 'components',
    9 => 'msrp_retail',
    10 => 'map_retail',
    11 => 'spec_net_regular',
    12 => 'map_sale_retail_kicker',
    13 => 'spec_net_kicker',
    14 => 'price_change',
    15 => 'gp_percent_regular',
    16 => 'description',
    17 => 'features',
    18 => 'product_height',
    19 => 'product_length',
    20 => 'product_width',
    21 => 'product_weight',
    22 => 'country_of_origin',
    23 => 'color',
    24 => 'warranty',
    25 => 'master_carton_qty',
    26 => 'master_carton_dimensions', 
    27 => 'master_carton_weight',
    28 => 'qty_per_inner_case_pack',
    29 => 'inner_case_pack_dimensions',
    30 => 'inner_case_pack_weight',
    31 => 'image_1',
    32 => 'image_2',
    33 => 'image_3',
    34 => 'image_4',
    35 => 'image_5',
    36 => 'image_6'
);

try {
    $spreadsheet = IOFactory::load($excel_file);
} catch (Exception $e) {
    die('Error al cargar el archivo: ' . $e->getMessage());
}

$worksheet = $spreadsheet->getActiveSheet();
$data = $worksheet->toArray();

// variable de control para omitir la primera fila
$isFirstRow = true;

// Iterar a través de las filas del archivo XLSX e insertar en la tabla MySQL
foreach ($data as $key => $row) {
    // Si es la primera fila, omítela
    if ($isFirstRow) {
        $isFirstRow = false;
        continue;
    }

    // Verificar si la fila está vacía 
    if (empty(array_filter($row))) {
        continue;
    }

    // Construir la consulta SQL de inserción con marcadores de posición dinámicos
    $columns = implode(", ", array_values($column_mapping));
    $placeholders = implode(", ", array_fill(0, count($column_mapping), "?"));

    // consulta SQL
    $sql = "INSERT IGNORE INTO productos ($columns) VALUES ($placeholders)";

    // consulta SQL y manejar errores
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular los valores de forma dinámica utilizando el mapeo de columnas
    $params = array();
    foreach ($column_mapping as $column_index => $column_name) {
        $params[] = $row[$column_index]; 
    }
    $types = str_repeat("s", count($column_mapping)); 
    $stmt->bind_param($types, ...$params);

    // ...

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro insertado correctamente.<br>";
    } else {
        echo "Error al insertar registro: " . $stmt->error;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
