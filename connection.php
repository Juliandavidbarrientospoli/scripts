<?php
// configuraciones 
include('data\config.php'); 

try {
    $conn_create_db = new mysqli($db_config["servername"], $db_config["username"], $db_config["password"]);

    if ($conn_create_db->connect_error) {
        throw new Exception("Error de conexión: " . $conn_create_db->connect_error);
    }
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS " . $db_config["dbname"];
    if ($conn_create_db->query($sql_create_db) === TRUE) {
        echo "Base de datos creada correctamente.<br>";
    } else {
        throw new Exception("Error al crear la base de datos: " . $conn_create_db->error);
    }

    $conn_create_db->close();
    $conn = new mysqli($db_config["servername"], $db_config["username"], $db_config["password"], $db_config["dbname"]);

    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para crear la tabla de productos con todas las columnas
    $sql_create_table = "
    CREATE TABLE IF NOT EXISTS productos (
        product_ordering INT AUTO_INCREMENT PRIMARY KEY,
        brand VARCHAR(255),
        product_line VARCHAR(255),
        item_number VARCHAR(255),
        sap_material_number VARCHAR(255),
        upc VARCHAR(255),
        product_name VARCHAR(255),
        category VARCHAR(255),
        components VARCHAR(255),
        msrp_retail VARCHAR(255), 
        map_retail VARCHAR(255), 
        spec_net_regular VARCHAR(255), 
        map_sale_retail_kicker VARCHAR(255), 
        spec_net_kicker VARCHAR(255), 
        price_change VARCHAR(255), 
        gp_percent_regular VARCHAR(255), 
        description TEXT, 
        features TEXT, 
        product_height VARCHAR(255), 
        product_length VARCHAR(255), 
        product_width VARCHAR(255), 
        product_weight VARCHAR(255), 
        country_of_origin VARCHAR(255), 
        color VARCHAR(255), 
        warranty VARCHAR(255), 
        master_carton_qty VARCHAR(255), 
        master_carton_dimensions VARCHAR(255), 
        master_carton_weight VARCHAR(255), 
        qty_per_inner_case_pack VARCHAR(255), 
        inner_case_pack_dimensions VARCHAR(255), 
        inner_case_pack_weight VARCHAR(255), 
        image_1 VARCHAR(255), 
        image_2 VARCHAR(255), 
        image_3 VARCHAR(255), 
        image_4 VARCHAR(255), 
        image_5 VARCHAR(255), 
        image_6 VARCHAR(255)
    )";
    
    if ($conn->query($sql_create_table) === TRUE) {
        echo "Tabla de productos creada correctamente.<br>";
    } else {
        throw new Exception("Error al crear la tabla de productos: " . $conn->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
