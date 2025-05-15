<?php
require __DIR__ . '/postulaciones.php';
?>

<?php
// Handle error message for no selection
if (isset($_GET['error']) && $_GET['error'] === 'sin-seleccion') {
    echo "
    <html>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Debes seleccionar al menos un registro para eliminar.',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    window.location.href = 'index.php'; // Clean the URL
                });
            });
        </script>
    </body>
    </html>";
    exit;
}

// Handle success message for deleted records
if (isset($_GET['success']) && $_GET['success'] === 'deleted') {
    echo "
    <html>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Los registros seleccionados han sido movidos a la papelera.',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    window.location.href = 'index.php'; // Clean the URL
                });
            });
        </script>
    </body>
    </html>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Lista de Postulaciones</title>
</head>
<body>
    <div class="logo-container">
        <img src="LOGO.-removebg-preview.png" alt="Logo de la empresa" class="logo">
    </div>

    <h2>Lista de Postulaciones</h2>
  <!-- FORMULARIO PARA ELIMINACIÓN MASIVA -->
    <form method="POST" action="eliminar_masivo.php" onsubmit="return confirm('¿Estás seguro de eliminar los seleccionados?');">
    <div class = "tools">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar...">
   <!-- BOTÓN PARA ELIMINACIÓN MASIVA -->
        <button type="submit" class="delete-btn" title="Eliminar seleccionados">
            <i class="fa-solid fa-trash-can"></i>
        </button>
          <a href="papelera.php" class="recycle-bin-link" title="Ver papelera">
        <i class="fa-solid fa-trash-can"></i> Papelera
    </a>

    </div>

   
  
        <table id="dataTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
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
                    echo "<td><input type='checkbox' name='ids[]' value='" . $row['id'] . "'></td>";
                    echo "<td data-label='Nombre y Apellidos'>" . htmlspecialchars($row['nombre_apellidos']) . "</td>";
                    echo "<td data-label='Correo Electrónico'>" . htmlspecialchars($row['correo_electronico']) . "</td>";
                    echo "<td data-label='Primera Opción Rol'>" . htmlspecialchars($row['primera_opcion_rol']) . "</td>";
                    echo "<td data-label='Segunda Opción Rol'>" . htmlspecialchars($row['segunda_opcion_rol']) . "</td>";
                    echo "<td data-label='Más Información'>
                        <button type='button' class='details-btn' onclick='showDetails(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")'>Ver detalles</button>
                    </td>";
                    echo "</tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>

     
    </form>

    <!-- Modal para mostrar detalles -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">×</span>
            <h3>Detalles del Registro</h3>
            <div id="modalBody"></div>
        </div>
    </div>

    <script src="filtro.js"></script>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>