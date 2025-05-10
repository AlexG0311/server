<?php
// Incluir el archivo de conexión
require_once __DIR__ . '/conexion.php';

// Configurar los encabezados
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Obtener los datos enviados desde Google Apps Script
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Verificar la estructura de los datos y manejar errores
if (!$data) {
    echo json_encode([
        "status" => "error", 
        "message" => "No se recibieron datos o formato incorrecto",
        "input" => $input // Devolver el input para depuración
    ]);
    exit;
}

// Verificar si $data es un objeto individual o un array de objetos
if (isset($data['marca_temporal'])) {
    // Si $data es un objeto individual, convertirlo a un array de un solo elemento
    $data = [$data];
}

// Insertar los datos en MySQL
$inserted = 0;
$errors = [];

foreach ($data as $row) {
    if (!is_array($row)) {
        $errors[] = "Fila no válida: no es un array";
        continue;
    }
    
    // Verificar que todas las claves necesarias existan
    $required_keys = [
        'marca_temporal', 'nombre_apellidos', 'correo_electronico', 'tipo_identificacion', 
        'numero_identificacion', 'numero_contacto', 'fecha_nacimiento', 'institucion', 
        'programa_academico', 'modalidad_estudio', 'semestre', 'labora_actualmente', 
        'entidad_donde_labora', 'ideas_mejorar_cecarmun', 'experiencia_comite_organizador', 
        'primera_opcion_rol', 'segunda_opcion_rol', 'compromiso_eventos', 'leyo_terminos', 
        'autoriza_habeas_data'
    ];
    
    $missing_keys = [];
    foreach ($required_keys as $key) {
        if (!isset($row[$key])) {
            $missing_keys[] = $key;
        }
    }
    
    if (!empty($missing_keys)) {
        $errors[] = "Faltan claves requeridas: " . implode(', ', $missing_keys);
        continue;
    }
    
    $sql = "INSERT INTO postulaciones_cecarmun (
        marca_temporal, nombre_apellidos, correo_electronico, tipo_identificacion, 
        numero_identificacion, numero_contacto, fecha_nacimiento, institucion, 
        programa_academico, modalidad_estudio, semestre, labora_actualmente, 
        entidad_donde_labora, ideas_mejorar_cecarmun, experiencia_comite_organizador, 
        primera_opcion_rol, segunda_opcion_rol, compromiso_eventos, leyo_terminos, 
        autoriza_habeas_data
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        $errors[] = "Error en la preparación de la consulta: " . $conexion->error;
        continue;
    }
    
    $stmt->bind_param(
        "ssssssssssssssssssss", // 20 strings
        $row['marca_temporal'],
        $row['nombre_apellidos'],
        $row['correo_electronico'],
        $row['tipo_identificacion'],
        $row['numero_identificacion'],
        $row['numero_contacto'],
        $row['fecha_nacimiento'],
        $row['institucion'],
        $row['programa_academico'],
        $row['modalidad_estudio'],
        $row['semestre'],
        $row['labora_actualmente'],
        $row['entidad_donde_labora'],
        $row['ideas_mejorar_cecarmun'],
        $row['experiencia_comite_organizador'],
        $row['primera_opcion_rol'],
        $row['segunda_opcion_rol'],
        $row['compromiso_eventos'],
        $row['leyo_terminos'],
        $row['autoriza_habeas_data']
    );
    
    if (!$stmt->execute()) {
        $errors[] = "Error al ejecutar la consulta: " . $stmt->error;
    } else {
        $inserted++;
    }
    
    $stmt->close();
}

// Cerrar la conexión
$conexion->close();

// Enviar respuesta
if (empty($errors)) {
    echo json_encode([
        "status" => "success", 
        "message" => "Se insertaron $inserted registros correctamente"
    ]);
} else {
    echo json_encode([
        "status" => "partial_error",
        "message" => "Se insertaron $inserted registros, pero hubo errores",
        "errors" => $errors
    ]);
}
?>