<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos desde la API</title>
</head>
<body>
    <h1>Lista de Usuarios</h1>
    <?php
    $api_url = "http://localhost/api_example/api.php"; // Cambia esto según tu entorno
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);

    if ($data['status'] === 'success') {
        echo "<table border='1'>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Edad</th>
                    <th>Registrado En</th>
                </tr>";
        foreach ($data['data'] as $usuario) {
            echo "<tr>
                    <td>{$usuario['nombre']}</td>
                    <td>{$usuario['correo']}</td>
                    <td>{$usuario['edad']}</td>
                    <td>{$usuario['creado_en']}</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Error al obtener los datos: {$data['message']}</p>";
    }
    ?>
</body>
</html>
