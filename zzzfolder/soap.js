  // Variables para almacenar las rutinas
        let rutinasFuerza = [];
        let rutinasCardio = [];

        // Función para obtener las rutinas desde el servidor
        function obtenerRutinas() {
            fetch('http://localhost/App_Fitness/zzzfolder/get_rutinas.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('rutinas').innerHTML = `Error: ${data.message}`;
                        return;
                    }

                    // Almacenar las rutinas de fuerza y cardio en variables
                    rutinasFuerza = data.fuerza.rutinas;
                    rutinasCardio = data.cardio.rutinas;
                    
                    // Mostrar las rutinas de fuerza por defecto
                    mostrarRutinas("fuerza");
                })
                .catch(error => {
                    document.getElementById('rutinas').innerHTML = 'Error al obtener las rutinas.';
                    console.error('Error:', error);
                });
        }

        // Función para mostrar las rutinas según el tipo
        function mostrarRutinas(tipo) {
            const rutinasDiv = document.getElementById('rutinas');
            let rutinasHtml = '';

            if (tipo === "fuerza") {
                rutinasHtml += `<h3 class="text-xl font-semibold">Rutinas de Fuerza</h3><ul class="space-y-2">`;
                rutinasFuerza.forEach(rutina => {
                    rutinasHtml += `<li class="bg-gray-100 p-3 rounded-md">${rutina}</li>`;
                });
                rutinasHtml += '</ul>';
            } else if (tipo === "cardio") {
                rutinasHtml += `<h3 class="text-xl font-semibold">Rutinas de Cardio</h3><ul class="space-y-2">`;
                rutinasCardio.forEach(rutina => {
                    rutinasHtml += `<li class="bg-gray-100 p-3 rounded-md">${rutina}</li>`;
                });
                rutinasHtml += '</ul>';
            }

            rutinasDiv.innerHTML = rutinasHtml;
        }

        // Funciones para cambiar entre las rutinas al hacer clic en los botones
        document.getElementById('btnFuerza').addEventListener('click', function() {
            mostrarRutinas("fuerza");
        });

        document.getElementById('btnCardio').addEventListener('click', function() {
            mostrarRutinas("cardio");
        });

        // Llamar a la función obtenerRutinas para cargar los datos cuando la página se carga
        obtenerRutinas();
 