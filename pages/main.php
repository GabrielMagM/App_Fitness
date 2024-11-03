<?php
session_start();
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
            <!-- Desafíos inscritos se añadirán aquí -->
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <div class="flex items-center">
                <a href="stats.php" class="text-gray-700 hover:text-blue-500 mr-4" title="Ver Estadísticas">
                    <i class="fas fa-chart-line fa-lg"></i>
                </a>
                <a href="logout.php" class="text-gray-700 hover:text-red-500" title="Cerrar Sesión">
                    <i class="fas fa-sign-out-alt fa-lg"></i>
                </a>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2 text-center">Desafíos Disponibles</h2>
        <ul id="availableChallenges" class="mb-6">
            <!-- Desafíos disponibles se añadirán aquí -->
        </ul>

        <h2 class="text-xl font-semibold mb-2 text-center">Crear Nuevo Desafío</h2>
        <form id="createChallengeForm" class="bg-white p-4 rounded shadow">
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Descripción</label>
                <input type="text" id="description" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Descripción del desafío">
            </div>
            <div class="mb-4">
                <label for="duration" class="block text-gray-700">Duración (días)</label>
                <input type="number" id="duration" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Duración del desafío">
            </div>
            <div class="mb-4">
                <label for="goal" class="block text-gray-700">Objetivo</label>
                <input type="text" id="goal" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Objetivo del desafío">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded">Crear Desafío</button>
        </form>
    </div>

    <!-- Modal
    <div id="modal" class="modal fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 id="modalTitle" class="text-lg font-bold mb-2">Título del Desafío</h3>
            <p id="modalDescription" class="mb-4">Descripción del desafío...</p>
            <button id="checkButton" class="bg-green-500 text-white px-4 py-2 rounded">Completar Desafío</button>
            <button id="closeButton" class="bg-red-500 text-white px-4 py-2 rounded ml-2">Cerrar</button>
        </div>
    </div> -->

    <script>
        // Datos de ejemplo
        const userChallenges = [
            { id: 1, title: "Desafío 1", description: "Pierde 5 kg en un mes" },
            { id: 2, title: "Desafío 2", description: "Correr 5 km diariamente" },
        ];

        const availableChallenges = [
            { id: 3, title: "Desafío 3", description: "Hacer 50 flexiones diarias" },
            { id: 4, title: "Desafío 4", description: "Beber 2 litros de agua al día" },
        ];

        // Renderiza los desafíos inscritos en el sidebar
        function renderUserChallenges() {
            const challengeList = document.getElementById('challengeList');
            challengeList.innerHTML = ''; // Limpiar la lista antes de renderizar
            userChallenges.forEach(challenge => {
                const li = document.createElement('li');
                li.className = "cursor-pointer hover:text-blue-300";
                li.innerText = challenge.title;
                li.onclick = () => showChallengeDetails(challenge);
                challengeList.appendChild(li);
            });
        }

        // Renderiza los desafíos disponibles
        function renderAvailableChallenges() {
            const availableList = document.getElementById('availableChallenges');
            availableList.innerHTML = ''; // Limpiar la lista antes de renderizar
            availableChallenges.forEach(challenge => {
                const li = document.createElement('li');
                li.className = "mb-2 p-2 bg-white rounded shadow cursor-pointer hover:bg-gray-200";
                li.innerText = challenge.title;
                li.onclick = () => enrollInChallenge(challenge);
                availableList.appendChild(li);
            });
        }

        // Muestra los detalles del desafío en el modal
        function showChallengeDetails(challenge) {
            document.getElementById('modalTitle').innerText = challenge.title;
            document.getElementById('modalDescription').innerText = challenge.description;
            document.getElementById('modal').classList.add('active');
        }

        // Inscribirse en un desafío
        function enrollInChallenge(challenge) {
            userChallenges.push(challenge);
            renderUserChallenges();
            renderAvailableChallenges(); // Actualizar la lista de desafíos disponibles
        }

        // Cerrar el modal
        document.getElementById('closeButton').onclick = function() {
            document.getElementById('modal').classList.remove('active');
        };

        // Completar un desafío
        document.getElementById('checkButton').onclick = function() {
            alert('Desafío completado!');
            document.getElementById('modal').classList.remove('active');
        };

        // Manejo de creación de nuevos desafíos
        document.getElementById('createChallengeForm').onsubmit = function(e) {
            e.preventDefault();
            const newChallenge = {
                id: Date.now(), // Generar un ID único
                title: document.getElementById('description').value,
                description: `Duración: ${document.getElementById('duration').value} días. Objetivo: ${document.getElementById('goal').value}`,
            };
            availableChallenges.push(newChallenge);
            renderAvailableChallenges();
            this.reset(); // Limpiar el formulario
        };

        // Inicializar las listas al cargar la página
        renderUserChallenges();
        renderAvailableChallenges();
    </script>
</body>
</html>
