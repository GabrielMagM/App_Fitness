<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "desafios_fitness";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// API REST: Registrar actividad y obtener estadísticas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $activity_id = $_POST['activity_id'];
    $duration = $_POST['duration'];

    $stmt = $conn->prepare("INSERT INTO user_activities (user_id, activity_id, duration) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $activity_id, $duration);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Actividad registrada.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar actividad.']);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_statistics') {
    $query = "
        SELECT activity_id, COUNT(*) AS participants, SUM(duration) AS total_duration
        FROM user_activities
        GROUP BY activity_id";
    $result = $conn->query($query);

    $statistics = [];
    while ($row = $result->fetch_assoc()) {
        $statistics[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $statistics]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas en Tiempo Real</title>
</head>
<body>
    <h1>Estadísticas de Actividades</h1>
    <div id="statistics"></div>

    <script>
        // Conexión WebSocket
        const ws = new WebSocket('ws://localhost:8080');

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.event === 'activity_registered') {
                fetch('real_time.php?action=get_statistics')
                    .then(response => response.json())
                    .then(data => renderStatistics(data.data));
            }
        };

        // Mostrar estadísticas
        function renderStatistics(stats) {
            const container = document.getElementById('statistics');
            container.innerHTML = stats.map(stat => `
                <p>Actividad ${stat.activity_id}: ${stat.participants} participantes, ${stat.total_duration} minutos</p>
            `).join('');
        }

        // Actualización inicial
        fetch('real_time.php?action=get_statistics')
            .then(response => response.json())
            .then(data => renderStatistics(data.data));
    </script>
</body>
</html>
