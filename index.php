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
            <th>Nombre y Apellidos</th>
            <th>Correo Electrónico</th>
            <th>Primera Opción Rol</th>
            <th>Segunda Opción Rol</th>
            <th>Más Información</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td data-label='Nombre y Apellidos'>" . htmlspecialchars($row['nombre_apellidos']) . "</td>";
            echo "<td data-label='Correo Electrónico'>" . htmlspecialchars($row['correo_electronico']) . "</td>";
            echo "<td data-label='Primera Opción Rol'>" . htmlspecialchars($row['primera_opcion_rol']) . "</td>";
            echo "<td data-label='Segunda Opción Rol'>" . htmlspecialchars($row['segunda_opcion_rol']) . "</td>";
          echo "<td data-label='Más Información'>
        <button class='details-btn' onclick='showDetails(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")'>Ver detalles</button>
        <button class='delete-btn' onclick=\"deletePostulacion(" . $row['id'] . ", this)\">Eliminar</button>
      </td>";

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


<script>
function deletePostulacion(id, buttonElement) {
    if (!confirm("¿Estás seguro de que deseas eliminar esta postulación?")) {
        return;
    }

    fetch('eliminar_postulacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Elimina la fila de la tabla en la interfaz
            const row = buttonElement.closest("tr");
            row.remove();
            alert("Postulación eliminada exitosamente.");
        } else {
            alert("Ocurrió un error al intentar eliminar.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Ocurrió un error al intentar eliminar.");
    });
}
</script>



</body>
</html>