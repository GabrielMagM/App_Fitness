<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Rutinas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro */
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="bg-gray-800 text-white w-64 p-5">
        <h2 class="text-lg font-bold mb-4">Información de Rutinas</h2>
        <ul>
            <li>
                <button id="btnFuerza" class="w-full text-left px-3 py-2 bg-gray-700 hover:bg-gray-600 rounded-md">
                    Rutinas de Fuerza
                </button>
            </li>
            <li>
                <button id="btnCardio" class="w-full text-left px-3 py-2 bg-gray-700 hover:bg-gray-600 rounded-md">
                    Rutinas de Cardio
                </button>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4">Dashboard de Rutinas</h1>
        <div id="rutinas" class="space-y-4">
            <!-- El contenido de las rutinas se cargará aquí -->
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>