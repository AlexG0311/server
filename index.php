<?php
require __DIR__ . '/postulaciones.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Lista de Postulaciones</title>
</head>
<body>
    <h2>Lista de Postulaciones</h2>
    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar...">
    <table id="dataTable">
        <thead>
            <tr>
                <th>Marca Temporal</th>
                <th>Nombre y Apellidos</th>
                <th>Correo Electrónico</th>
                <th>Primera Opción Rol</th>
                <th>Mas Informacion</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td data-label='Marca Temporal'>" . htmlspecialchars($row['marca_temporal']) . "</td>";
                echo "<td data-label='Nombre y Apellidos'>" . htmlspecialchars($row['nombre_apellidos']) . "</td>";
                echo "<td data-label='Correo Electrónico'>" . htmlspecialchars($row['correo_electronico']) . "</td>";
                echo "<td data-label='Primera Opción Rol'>" . htmlspecialchars($row['primera_opcion_rol']) . "</td>";
                echo "<td data-label='Mas Informacion'><button class='details-btn' onclick='showDetails(" . json_encode($row) . ")'>Ver detalles</button></td>";
                echo "</tr>";
            }
            $conexion->close();
            ?>
        </tbody>
    </table>

    <!-- Modal para mostrar detalles -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Detalles del Registro</h3>
            <div id="modalBody"></div>
        </div>
    </div>
    <script src="filtro.js"></script>
</body>
</html>