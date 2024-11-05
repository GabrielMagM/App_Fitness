<?php
session_start();
require_once '../database/config.php'; // Asegúrate de incluir tu conexión a la base de datos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener el ID del usuario
$user_id = $_SESSION['user_id'];

// Obtener el número de desafíos completados
$stmt = $conn->prepare("SELECT COUNT(*) as completed_count FROM user_challenges WHERE user_id = ? AND completed = 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$completedChallenges = $result->fetch_assoc()['completed_count'];

// Obtener el tiempo total en días de los desafíos completados
$stmt = $conn->prepare("SELECT SUM(c.duration) as total_days FROM user_challenges uc JOIN challenges c ON uc.challenge_id = c.id WHERE uc.user_id = ? AND uc.completed = 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$totalDays = $result->fetch_assoc()['total_days'];

// Calcular el progreso general (porcentaje de desafíos completados)
$stmt = $conn->prepare("SELECT COUNT(*) as total_challenges FROM user_challenges WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$totalChallenges = $result->fetch_assoc()['total_challenges'];

$overallProgress = $totalChallenges > 0 ? ($completedChallenges / $totalChallenges) * 100 : 0;

// Obtener la lista de desafíos completados
$stmt = $conn->prepare("SELECT c.description FROM user_challenges uc JOIN challenges c ON uc.challenge_id = c.id WHERE uc.user_id = ? AND uc.completed = 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$completedChallengesList = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Desafíos Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MXwyMDg4NjB8MHwxfGFsbHwxfHx8fHx8fHwxNjE2MjE5NzY0&ixlib=rb-1.2.1&q=80&w=1080');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 bg-opacity-80">
        <h1 class="text-2xl font-bold mb-4 text-center">Estadísticas de Desafíos Fitness</h1>

        <!-- Estadísticas Resumidas -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-500 text-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-bold">Desafíos Completados</h2>
                <p id="completedChallenges" class="text-3xl"><?php echo $completedChallenges; ?></p>
            </div>
            <div class="bg-green-500 text-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-bold">Tiempo Total</h2>
                <p id="totalTime" class="text-3xl"><?php echo $totalDays ? $totalDays : 0; ?> días</p>
            </div>
            <div class="bg-yellow-500 text-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-bold">Progreso General</h2>
                <p id="overallProgress" class="text-3xl"><?php echo round($overallProgress, 2); ?>%</p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2">Desafíos Completados</h2>
        <ul id="completedChallengesList" class="list-disc pl-5">
            <?php foreach ($completedChallengesList as $challenge): ?>
                <li class="mb-2"><?php echo htmlspecialchars($challenge['description']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Botón de Regresar -->
    <div class="absolute top-5 left-5">
        <a href="main.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Regresar</a>
    </div>

    <!-- Icono de Logout -->
    <div class="absolute top-5 right-5">
        <a href="logout.php" class="text-red-600 hover:text-red-800">
            <i class="fas fa-sign-out-alt text-2xl"></i>
        </a>
    </div>

    <script>
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>

</body>
</html>
