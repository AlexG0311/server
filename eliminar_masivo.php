<?php
require_once __DIR__ . '/conexion.php';

if (isset($_POST['ids']) && is_array($_POST['ids'])) {
    $ids = array_map('intval', $_POST['ids']);
    $ids_placeholder = implode(',', array_fill(0, count($ids), '?'));

    // Step 1: Copy records to papelera_postulaciones
    $sql = "INSERT INTO papelera_postulaciones (
        original_id, marca_temporal, nombre_apellidos, correo_electronico, tipo_identificacion, 
        numero_identificacion, numero_contacto, fecha_nacimiento, institucion, programa_academico, 
        modalidad_estudio, semestre, labora_actualmente, entidad_donde_labora, ideas_mejorar_cecarmun, 
        experiencia_comite_organizador, primera_opcion_rol, segunda_opcion_rol, compromiso_eventos, 
        leyo_terminos, autoriza_habeas_data, created_at
    ) 
    SELECT 
        id, marca_temporal, nombre_apellidos, correo_electronico, tipo_identificacion, 
        numero_identificacion, numero_contacto, fecha_nacimiento, institucion, programa_academico, 
        modalidad_estudio, semestre, labora_actualmente, entidad_donde_labora, ideas_mejorar_cecarmun, 
        experiencia_comite_organizador, primera_opcion_rol, segunda_opcion_rol, compromiso_eventos, 
        leyo_terminos, autoriza_habeas_data, created_at
    FROM postulaciones_cecarmun 
    WHERE id IN ($ids_placeholder)";

    $stmt = $conexion->prepare($sql);
    if ($stmt->execute($ids)) {
        // Step 2: Delete records from postulaciones_cecarmun
        $sql = "DELETE FROM postulaciones_cecarmun WHERE id IN ($ids_placeholder)";
        $stmt = $conexion->prepare($sql);
        if ($stmt->execute($ids)) {
            header("Location: index.php?success=deleted");
            exit;
        } else {
            echo "Error al eliminar: " . $conexion->error;
        }
    } else {
        echo "Error al mover a papelera: " . $conexion->error;
    }
} else {
    // Redirect with error parameter
    header("Location: index.php?error=sin-seleccion");
    exit;
}

$conexion->close();
?>