<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($input["id"])) {
    $id = intval($input["id"]);

    require_once __DIR__ . '/conexion.php';

    $sql = "DELETE FROM postulaciones_cecarmun WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conexion->error]);
    }

    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(["success" => false, "error" => "Solicitud inválida o ID no recibido."]);
}
?>
