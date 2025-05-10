<?php
require_once __DIR__ . '/conexion.php';

// Consulta para obtener todos los datos de la tabla
$sql = "SELECT * FROM postulaciones_cecarmun";
$result = $conexion->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Postulaciones</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            padding: 6px;
            width: 100%;
            max-width: 300px;
            margin-bottom: 10px;
        }
    </style>
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
                <th>Tipo Identificación</th>
                <th>Número Identificación</th>
                <th>Número Contacto</th>
                <th>Fecha Nacimiento</th>
                <th>Institución</th>
                <th>Programa Académico</th>
                <th>Modalidad Estudio</th>
                <th>Semestre</th>
                <th>Labora Actualmente</th>
                <th>Entidad donde Labora</th>
                <th>Ideas Mejorar CECARMUN</th>
                <th>Experiencia Comité Organizador</th>
                <th>Primera Opción Rol</th>
                <th>Segunda Opción Rol</th>
                <th>Compromiso Eventos</th>
                <th>Leyó Términos</th>
                <th>Autoriza Habeas Data</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['marca_temporal']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre_apellidos']) . "</td>";
                echo "<td>" . htmlspecialchars($row['correo_electronico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tipo_identificacion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numero_identificacion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numero_contacto']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_nacimiento']) . "</td>";
                echo "<td>" . htmlspecialchars($row['institucion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['programa_academico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['modalidad_estudio']) . "</td>";
                echo "<td>" . htmlspecialchars($row['semestre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['labora_actualmente']) . "</td>";
                echo "<td>" . htmlspecialchars($row['entidad_donde_labora']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ideas_mejorar_cecarmun']) . "</td>";
                echo "<td>" . htmlspecialchars($row['experiencia_comite_organizador']) . "</td>";
                echo "<td>" . htmlspecialchars($row['primera_opcion_rol']) . "</td>";
                echo "<td>" . htmlspecialchars($row['segunda_opcion_rol']) . "</td>";
                echo "<td>" . htmlspecialchars($row['compromiso_eventos']) . "</td>";
                echo "<td>" . htmlspecialchars($row['leyo_terminos']) . "</td>";
                echo "<td>" . htmlspecialchars($row['autoriza_habeas_data']) . "</td>";
                echo "</tr>";
            }
            $conexion->close();
            ?>
        </tbody>
    </table>

    <script>
        function filterTable() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toLowerCase();
            var table = document.getElementById("dataTable");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) { // Comienza desde 1 para omitir la cabecera
                var found = false;
                var td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    var cell = td[j];
                    if (cell) {
                        var text = cell.textContent || cell.innerText;
                        if (text.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }
    </script>
</body>
</html>