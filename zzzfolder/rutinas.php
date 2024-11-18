<?php
session_start();
require_once '../database/config.php'; // Archivo que contiene la conexión a la base de datos
/*
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

// Obtener desafíos disponibles (aquellos que no están completados por el usuario)
$availableChallenges = $conn->query("
    SELECT * FROM challenges 
    WHERE user_id = $user_id AND id NOT IN (SELECT challenge_id FROM user_challenges WHERE user_id = $user_id)
")->fetch_all(MYSQLI_ASSOC);

// Obtener los desafíos del usuario (excluyendo los completados)
$userChallenges = $conn->prepare("
    SELECT c.* FROM user_challenges uc 
    JOIN challenges c ON uc.challenge_id = c.id 
    WHERE uc.user_id = ? AND uc.completed = 0
");
$userChallenges->bind_param("i", $user_id);
$userChallenges->execute();
$userChallengesResult = $userChallenges->get_result();
$challenges = $userChallengesResult->fetch_all(MYSQLI_ASSOC);
*/?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Informacion de Rutinas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <script>
        function openModal(challengeId) {
            document.getElementById('modal-' + challengeId).classList.add('active');
        }
        function closeModal(challengeId) {
            document.getElementById('modal-' + challengeId).classList.remove('active');
        }
    </script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <div class="bg-gray-800 text-white w-64 p-5">
        <h2 class="text-lg font-bold mb-4">Informacion de Rutinas</h2>
        <ul id="Rutinas">
            
        </ul>
    </div>

<!-- Main Content -->
<div class="flex-1 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Dashboard - </h1>
        <div class="flex items-center">
            <a href="stats.php" class="text-gray-700 hover:text-blue-500 mr-4" title="Ver Estadísticas">
                <i class="fas fa-chart-line fa-lg"></i>
            </a>
            <a href="../assets/logout.php" class="text-gray-700 hover:text-red-500" title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt fa-lg"></i>
            </a>
        </div>
    </div>

    
</div>

</body>
</html>