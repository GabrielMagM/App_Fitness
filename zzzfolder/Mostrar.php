<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rutinas de Ejercicio</title>
    <style>
        .rutina-button {
            margin: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .rutina-button:hover {
            background-color: #45a049;
        }
        .descripcion {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <h1>Elige una rutina para ver la descripción</h1>
    
    <!-- Botones para cada rutina -->
    <button class="rutina-button" onclick="mostrarDescripcion('Sentadillas')">Sentadillas</button>
    <button class="rutina-button" onclick="mostrarDescripcion('Abdominales')">Abdominales</button>
    <button class="rutina-button" onclick="mostrarDescripcion('Flexiones')">Flexiones</button>

    <div id="descripcion" class="descripcion"></div>

    <script>
    // Función para obtener las rutinas desde el servicio PHP
        async function obtenerRutinas() {
            try {
                const response = await fetch('get_rutinas.php');
                const data = await response.json();
                return data; // Retorna las rutinas obtenidas
            } catch (error) {
                console.error('Error al obtener las rutinas:', error);
                return {};
            }
        }

        // Función para mostrar la descripción de la rutina
        async function mostrarDescripcion(tipo) {
            const rutinas = await obtenerRutinas();  // Obtener las rutinas
            const rutina = rutinas[tipo]?.rutinas;  // Accede a la propiedad "rutinas" dentro de cada tipo

            if (rutina) {
                const descripcion = rutina.descripcion;
                document.getElementById('descripcion').innerHTML = `
                    <h3>${rutina.nombre}</h3>
                    <p>${descripcion}</p>
                `;
            } else {
                document.getElementById('descripcion').innerHTML = `<p>No se encontró la rutina solicitada.</p>`;
            }
        }
    </script>

</body>
</html>
