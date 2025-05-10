<?php
require_once __DIR__ . '/conexion.php';

$sql = "SELECT * FROM postulaciones_cecarmun";
$result = $conexion->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}
?>