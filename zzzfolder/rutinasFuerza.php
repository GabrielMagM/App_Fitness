<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Fitness</title>
    <style>
        #rutinas { margin-top: 20px; font-size: 18px; }
        button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Bienvenido a App Fitness</h1>
    <h2>Rutinas recomendadas</h2>

    <!-- Botones para cambiar entre las rutinas -->
    <button id="btnFuerza">Mostrar Rutinas de Fuerza</button>
    <button id="btnCardio">Mostrar Rutinas de Cardio</button>

    <div id="rutinas">Cargando rutinas...</div>

    <script>
        // Variables para almacenar las rutinas
        let rutinasFuerza = [];
        let rutinasCardio = [];

        // Función para obtener las rutinas
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
                    
                    // Mostrar las rutinas de fuerza por defecto (puedes cambiar este comportamiento)
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
                rutinasHtml += `<h3>Fuerza</h3><ul>`;
                rutinasFuerza.forEach(rutina => {
                    rutinasHtml += `<li>${rutina}</li>`;
                });
                rutinasHtml += '</ul>';
            } else if (tipo === "cardio") {
                rutinasHtml += `<h3>Cardio</h3><ul>`;
                rutinasCardio.forEach(rutina => {
                    rutinasHtml += `<li>${rutina}</li>`;
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
    </script>

</body>
</html>
