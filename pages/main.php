<?php
session_start();
require_once '../database/config.php'; // Archivo que contiene la conexión a la base de datos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener el nombre del usuario
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Obtener desafíos disponibles (aquellos que no están asignados a un usuario)
$availableChallenges = $conn->query("SELECT * FROM challenges WHERE user_id IS NULL")->fetch_all(MYSQLI_ASSOC);

// Obtener los desafíos del usuario
$userChallenges = $conn->prepare("SELECT c.* FROM user_challenges uc JOIN challenges c ON uc.challenge_id = c.id WHERE uc.user_id = ?");
$userChallenges->bind_param("i", $user_id);
$userChallenges->execute();
$userChallengesResult = $userChallenges->get_result();
$challenges = $userChallengesResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Desafíos Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .modal {
            display: none;
        }
        .modal.active {
            display: block;
        }
    </style>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <div class="bg-gray-800 text-white w-64 p-5">
        <h2 class="text-lg font-bold mb-4">Mis Desafíos</h2>
        <ul id="challengeList">
            <?php foreach ($challenges as $challenge): ?>
                <li class="mb-2 p-2 bg-gray-700 rounded"><?php echo htmlspecialchars($challenge['description']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Dashboard - <?php echo htmlspecialchars($user['name']); ?></h1>
            <div class="flex items-center">
                <a href="stats.php" class="text-gray-700 hover:text-blue-500 mr-4" title="Ver Estadísticas">
                    <i class="fas fa-chart-line fa-lg"></i>
                </a>
                <a href="../assets/logout.php" class="text-gray-700 hover:text-red-500" title="Cerrar Sesión">
                    <i class="fas fa-sign-out-alt fa-lg"></i>
                </a>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2 text-center">Desafíos Disponibles</h2>
        <ul id="availableChallenges" class="mb-6">
            <?php foreach ($availableChallenges as $challenge): ?>
                <li class="mb-2 p-2 bg-white rounded shadow cursor-pointer hover:bg-gray-200">
                    <?php echo htmlspecialchars($challenge['description']); ?>
                    <form action="../assets/selectChallenge.php" method="POST" class="inline">
                        <input type="hidden" name="challenge_id" value="<?php echo $challenge['id']; ?>">
                        <button type="submit" class="ml-4 text-blue-500 hover:underline">Unirse</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <h2 class="text-xl font-semibold mb-2 text-center">Crear Nuevo Desafío</h2>
        <form id="addChallenge" action="../assets/addChallenge.php" Method="POST" class="bg-white p-4 rounded shadow">
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Descripción</label>
                <input type="text" id="description" name="description" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Descripción del desafío">
            </div>
            <div class="mb-4">
                <label for="duration" class="block text-gray-700">Duración (días)</label>
                <input type="number" id="duration" name="duration" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Duración del desafío">
            </div>
            <div class="mb-4">
                <label for="goal" class="block text-gray-700">Objetivo</label>
                <input type="text" id="goal" name="goal" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Objetivo del desafío">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded">Crear Desafío</button>
        </form>
    </div>

</body>
</html>
