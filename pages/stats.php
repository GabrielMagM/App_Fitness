<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
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
                <p id="completedChallenges" class="text-3xl">5</p>
            </div>
            <div class="bg-green-500 text-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-bold">Calorías Quemadas</h2>
                <p id="caloriesBurned" class="text-3xl">1500</p>
            </div>
            <div class="bg-yellow-500 text-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-bold">Progreso General</h2>
                <p id="overallProgress" class="text-3xl">75%</p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2">Desafíos Completados</h2>
        <ul id="completedChallengesList" class="list-disc pl-5">
            <li class="mb-2">Desafío 1: Pierde 5 kg en un mes</li>
            <li class="mb-2">Desafío 2: Correr 5 km diariamente</li>
            <li class="mb-2">Desafío 3: Hacer 50 flexiones diarias</li>
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

</body>
</html>
