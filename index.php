<?php
// Incluir el archivo de conexión
require_once __DIR__ . '/conexion.php'; // Ajuste: Usa la ruta correcta para cargar conexion.php

// Configurar los encabezados
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Obtener los datos enviados desde Google Apps Script
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si se recibieron datos
if (!$data) {
    echo json_encode(["status" => "error", "message" => "No se recibieron datos"]);
    exit;
}

// Insertar los datos en MySQL
foreach ($data as $row) {
    $sql = "INSERT INTO postulaciones_cecarmun (
        marca_temporal, nombre_apellidos, correo_electronico, tipo_identificacion, 
        numero_identificacion, numero_contacto, fecha_nacimiento, institucion, 
        programa_academico, modalidad_estudio, semestre, labora_actualmente, 
        entidad_donde_labora, ideas_mejorar_cecarmun, experiencia_comite_organizador, 
        primera_opcion_rol, segunda_opcion_rol, compromiso_eventos, leyo_terminos, 
        autoriza_habeas_data
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
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
    $stmt->execute();
    $stmt->close();
}

// Cerrar la conexión
$conexion->close();

echo json_encode(["status" => "success", "message" => "Datos insertados correctamente"]);
?>