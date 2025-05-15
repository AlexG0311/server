<?php
require_once __DIR__ . '/conexion.php';

// Handle success/error messages
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
                    text: 'Debes seleccionar al menos un registro para realizar esta acción.',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    window.location.href = 'papelera.php';
                });
            });
        </script>
    </body>
    </html>";
    exit;
}

if (isset($_GET['success']) && $_GET['success'] === 'restored') {
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
                    text: 'Los registros seleccionados han sido restaurados.',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    window.location.href = 'papelera.php';
                });
            });
        </script>
    </body>
    </html>";
    exit;
}

if (isset($_GET['success']) && $_GET['success'] === 'permanently_deleted') {
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
                    text: 'Los registros seleccionados han sido eliminados permanentemente.',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    window.location.href = 'papelera.php';
                });
            });
        </script>
    </body>
    </html>";
    exit;
}

// Fetch records from papelera_postulaciones
$query = "SELECT * FROM papelera_postulaciones ORDER BY deleted_at DESC";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilosPapelera.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Papelera de Postulaciones</title>
</head>
<body>
    <div class="logo-container">
        <img src="LOGO.-removebg-preview.png" alt="Logo de la empresa" class="logo" >
    </div>

    <h2>Papelera de Postulaciones</h2>

<form method="POST" action="papelera_acciones.php" onsubmit="return confirm('¿Estás seguro de realizar esta acción?');">
                
<div class = "tools"> 
  <a href="index.php" class="back-link" title="Volver a la lista">
     <i class="fa-solid fa-arrow-left"></i> Volver  
  </a>   
  <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar...">
 


     <button type="submit" name="action" value="restore" class="restore-btn" title="Restaurar seleccionados">
            <i class="fa-solid fa-undo"></i> Restaurar
        </button>
        <button type="submit" name="action" value="delete" class="delete-btn" title="Eliminar permanentemente">
            <i class="fa-solid fa-trash-can"></i> 
        </button>
</div>
  

        <table id="dataTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nombre y Apellidos</th>
                    <th>Correo Electrónico</th>
                    <th>Primera Opción Rol</th>
                    <th>Segunda Opción Rol</th>
                    <th>Eliminado el</th>
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
                    echo "<td data-label='Eliminado el'>" . htmlspecialchars($row['deleted_at']) . "</td>";
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

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        function showDetails(row) {
            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <p><strong>ID Original:</strong> ${row.original_id}</p>
                <p><strong>Nombre y Apellidos:</strong> ${row.nombre_apellidos}</p>
                <p><strong>Correo Electrónico:</strong> ${row.correo_electronico}</p>
                <p><strong>Tipo de Identificación:</strong> ${row.tipo_identificacion}</p>
                <p><strong>Número de Identificación:</strong> ${row.numero_identificacion}</p>
                <p><strong>Número de Contacto:</strong> ${row.numero_contacto}</p>
                <p><strong>Fecha de Nacimiento:</strong> ${row.fecha_nacimiento}</p>
                <p><strong>Institución:</strong> ${row.institucion}</p>
                <p><strong>Programa Académico:</strong> ${row.programa_academico}</p>
                <p><strong>Modalidad de Estudio:</strong> ${row.modalidad_estudio}</p>
                <p><strong>Semestre:</strong> ${row.semestre}</p>
                <p><strong>Labora Actualmente:</strong> ${row.labora_actualmente}</p>
                <p><strong>Entidad Donde Labora:</strong> ${row.entidad_donde_labora}</p>
                <p><strong>Ideas para Mejorar CECARMUN:</strong> ${row.ideas_mejorar_cecarmun}</p>
                <p><strong>Experiencia en Comité Organizador:</strong> ${row.experiencia_comite_organizador}</p>
                <p><strong>Primera Opción Rol:</strong> ${row.primera_opcion_rol}</p>
                <p><strong>Segunda Opción Rol:</strong> ${row.segunda_opcion_rol}</p>
                <p><strong>Compromiso Eventos:</strong> ${row.compromiso_eventos}</p>
                <p><strong>Leyó Términos:</strong> ${row.leyo_terminos}</p>
                <p><strong>Autoriza Habeas Data:</strong> ${row.autoriza_habeas_data}</p>
                <p><strong>Eliminado el:</strong> ${row.deleted_at}</p>
            `;
            document.getElementById('detailsModal').style.display = 'block';
        }
    </script>
</body>
</html>