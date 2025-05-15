<?php
require_once __DIR__ . '/conexion.php';

if (isset($_POST['ids']) && is_array($_POST['ids']) && isset($_POST['action'])) {
    $ids = array_map('intval', $_POST['ids']);
    $ids_placeholder = implode(',', array_fill(0, count($ids), '?'));

    if ($_POST['action'] === 'restore') {
        // Restore records to postulaciones_cecarmun
        $sql = "INSERT INTO postulaciones_cecarmun (
            id, marca_temporal, nombre_apellidos, correo_electronico, tipo_identificacion, 
            numero_identificacion, numero_contacto, fecha_nacimiento, institucion, programa_academico, 
            modalidad_estudio, semestre, labora_actualmente, entidad_donde_labora, ideas_mejorar_cecarmun, 
            experiencia_comite_organizador, primera_opcion_rol, segunda_opcion_rol, compromiso_eventos, 
            leyo_terminos, autoriza_habeas_data, created_at
        ) 
        SELECT 
            original_id, marca_temporal, nombre_apellidos, correo_electronico, tipo_identificacion, 
            numero_identificacion, numero_contacto, fecha_nacimiento, institucion, programa_academico, 
            modalidad_estudio, semestre, labora_actualmente, entidad_donde_labora, ideas_mejorar_cecarmun, 
            experiencia_comite_organizador, primera_opcion_rol, segunda_opcion_rol, compromiso_eventos, 
            leyo_terminos, autoriza_habeas_data, created_at
        FROM papelera_postulaciones 
        WHERE id IN ($ids_placeholder)";

        $stmt = $conexion->prepare($sql);
        if ($stmt->execute($ids)) {
            // Delete from papelera_postulaciones
            $sql = "DELETE FROM papelera_postulaciones WHERE id IN ($ids_placeholder)";
            $stmt = $conexion->prepare($sql);
            if ($stmt->execute($ids)) {
                header("Location: papelera.php?success=restored");
                exit;
            } else {
                echo "Error al eliminar de papelera: " . $conexion->error;
            }
        } else {
            echo "Error al restaurar: " . $conexion->error;
        }
    } elseif ($_POST['action'] === 'delete') {
        // Permanently delete from papelera_postulaciones
        $sql = "DELETE FROM papelera_postulaciones WHERE id IN ($ids_placeholder)";
        $stmt = $conexion->prepare($sql);
        if ($stmt->execute($ids)) {
            header("Location: papelera.php?success=permanently_deleted");
            exit;
        } else {
            echo "Error al eliminar permanentemente: " . $conexion->error;
        }
    }
} else {
    header("Location: papelera.php?error=sin-seleccion");
    exit;
}

$conexion->close();
?>