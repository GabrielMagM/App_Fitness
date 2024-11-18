<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rutinas de Fitness</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #383940;
            color: #333;
            margin: 0;
            padding: 0;
            color: white;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
        }
        .rutinas-lista {
            list-style-type: none;
            padding: 0;
        }
        .rutinas-lista li {
            background-color: #2f3552;
            color: white;
            padding: 10px;
            margin-bottom: 5px;
            cursor: pointer;
            border-radius: 5px;
        }
        .rutinas-lista li:hover {
            background-color: #45a049;
        }
        .descripcion {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: black;

        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Informacion de Rutinas</h1>
        <ul class="rutinas-lista">
            <li onclick="mostrarDescripcion('Sentadillas')">Sentadillas</li>
            <li onclick="mostrarDescripcion('LegPress')">LegPress</li>
            <li onclick="mostrarDescripcion('LegExtension')">LegExtension</li>
            <li onclick="mostrarDescripcion('FrontSquat')">FrontSquat</li>
            <li onclick="mostrarDescripcion('ForwardLunges')">ForwardLunges</li>
        </ul>

        <div class="descripcion" id="descripcion">
            <!-- La descripción se mostrará aquí -->
            Selecciona una rutina para ver la descripción.
        </div>
    </div>

    <script>
        // Función para obtener la descripción de la rutina desde el servidor
        function mostrarDescripcion(tipo) {
    fetch(`get_rutinas.php?tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);  // Verifica lo que se está recibiendo
            const descripcionDiv = document.getElementById("descripcion");

            if (data.error) {
                descripcionDiv.innerText = `Error: ${data.message}`;
            } else {
                // Accede a la propiedad "rutinas" dentro del objeto correspondiente al tipo
                const descripcion = data[tipo] ? data[tipo].rutinas : 'Descripción no disponible';
                descripcionDiv.innerText = descripcion;
            }

            // Mostrar el contenedor de descripción
            descripcionDiv.style.display = "block";
        })
        .catch(error => {
            const descripcionDiv = document.getElementById("descripcion");
            descripcionDiv.innerText = "Hubo un problema al obtener los datos.";
            descripcionDiv.style.display = "block";
        });
}

    </script>
</body>
</html>