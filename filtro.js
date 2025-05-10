   function filterTable() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toLowerCase();
            var table = document.getElementById("dataTable");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var found = false;
                var td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length - 1; j++) { // Excluir la columna de "Acciones"
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

        function showDetails(row) {
            var modal = document.getElementById("detailsModal");
            var modalBody = document.getElementById("modalBody");
            modalBody.innerHTML = `
                <p><strong>Marca Temporal:</strong> ${row.marca_temporal}</p>
                <p><strong>Nombre y Apellidos:</strong> ${row.nombre_apellidos}</p>
                <p><strong>Correo Electrónico:</strong> ${row.correo_electronico}</p>
                <p><strong>Tipo Identificación:</strong> ${row.tipo_identificacion}</p>
                <p><strong>Número Identificación:</strong> ${row.numero_identificacion}</p>
                <p><strong>Número Contacto:</strong> ${row.numero_contacto}</p>
                <p><strong>Fecha Nacimiento:</strong> ${row.fecha_nacimiento}</p>
                <p><strong>Institución:</strong> ${row.institucion}</p>
                <p><strong>Programa Académico:</strong> ${row.programa_academico}</p>
                <p><strong>Modalidad Estudio:</strong> ${row.modalidad_estudio}</p>
                <p><strong>Semestre:</strong> ${row.semestre}</p>
                <p><strong>Labora Actualmente:</strong> ${row.labora_actualmente}</p>
                <p><strong>Entidad donde Labora:</strong> ${row.entidad_donde_labora}</p>
                <p><strong>Ideas Mejorar CECARMUN:</strong> ${row.ideas_mejorar_cecarmun}</p>
                <p><strong>Experiencia Comité Organizador:</strong> ${row.experiencia_comite_organizador}</p>
                <p><strong>Primera Opción Rol:</strong> ${row.primera_opcion_rol}</p>
                <p><strong>Segunda Opción Rol:</strong> ${row.segunda_opcion_rol}</p>
                <p><strong>Compromiso Eventos:</strong> ${row.compromiso_eventos}</p>
                <p><strong>Leyó Términos:</strong> ${row.leyo_terminos}</p>
                <p><strong>Autoriza Habeas Data:</strong> ${row.autoriza_habeas_data}</p>
            `;
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("detailsModal");
            modal.style.display = "none";
        }

        // Cerrar el modal al hacer clic fuera de él
        window.onclick = function(event) {
            var modal = document.getElementById("detailsModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }