<?php
require_once __DIR__ . '/conexion.php';

$sql = "SELECT * FROM postulaciones_cecarmun ORDER BY nombre_apellidos ASC";
$result = $conexion->query($sql);

$sl = "SELECT count(id) as cantidad from postulaciones_cecarmun"; 
$cantidad = $conexion->query($sl);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}
?>