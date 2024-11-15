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
        <h2 class="text-lg font-bold mb-4">Mis Desafíos</h2>
        <ul id="challengeList">
            <?php foreach ($challenges as $challenge): ?>
                <?php include '../assets/addChallenge.php'; ?>
                <li class="mb-2 p-2 bg-gray-700 rounded cursor-pointer" onclick="openModal(<?php echo $challenge['id']; ?>)">
                    <?php echo htmlspecialchars($challenge['description']); ?>
                </li>

                <!-- Modal para mostrar detalles del desafío -->
                <div class="modal" id="modal-<?php echo $challenge['id']; ?>">
                    <div class="modal-content">
                        <?php include '../assets/getStagesChallenge.php'?>
                        <form action="../assets/completeChallenge.php" method="POST">
                            <input type="hidden" name="challenge_id" value="<?php echo $challenge['id']; ?>">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mt-4">Completar Desafío</button>
                        </form>
                        <button type="button" class="close-modal bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mt-4" onclick="closeModal(<?php echo $challenge['id']; ?>)">Cerrar</button>
                    </div>
                </div>
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
        <?php if (!empty($availableChallenges)): ?>
            <?php foreach ($availableChallenges as $challenge): ?>
                <li class="mb-2 p-2 bg-white rounded shadow cursor-pointer hover:bg-gray-200" onclick="openModal(<?php echo $challenge['id']; ?>)">
                    <?php echo htmlspecialchars($challenge['description']); ?>
                </li>

                <!-- Modal para mostrar detalles del desafío -->
                <div class="modal" id="modal-<?php echo $challenge['id']; ?>">
                    <div class="modal-content">
                            <?php include '../assets/showData.php' ?>
                        <form action="../assets/selectChallenge.php" method="POST">
                            <input type="hidden" name="challenge_id" value="<?php echo htmlspecialchars($challenge['id']); ?>">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mt-4">Unirse al Desafío</button>
                        </form>
                            <button type="button" class="close-modal bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mt-4" onclick="closeModal(<?php echo $challenge['id']; ?>)">Cerrar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="mb-2 p-2 bg-white rounded shadow">No hay desafíos disponibles en este momento.</li>
        <?php endif; ?>
    </ul>

    <h2 class="text-xl font-semibold mb-2 text-center">Crear Nuevo Desafío</h2>
        <form id="addChallenge" action="../assets/addChallenge.php" method="POST" class="bg-white p-4 rounded shadow">
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Titulo del Desafio</label>
                <input type="text" id="title" name="title" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Titulo del desafío">
            </div>
            <div class="mb-4">
                <label for="duration" class="block text-gray-700">Duración (días)</label>
                <input type="number" id="duration" name="duration" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Duración del desafío">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Descripción del Desafío:</label>
                <input type="text" id="description" name="description" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Description del desafío">
            </div>

            <!-- Agregar el challenge_id como campo oculto -->
            <input type="hidden" name="challenge_id" value="<?php echo $challenge_id; ?>">
            <select id="etapas" name="etapas_count" class="mt-1 p-2 border border-gray-300 rounded w-full mb-4" required onchange="mostrarCamposEtapas()">
                <option value="">Selecciona el número de etapas</option>
                <option value="1">1 Etapa</option>
                <option value="2">2 Etapas</option>
                <option value="3">3 Etapas</option>
            </select>

            <!-- Contenedor de Campos de Etapas -->
            <div id="etapasContainer"></div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded">Crear Desafío</button>
        </form>

        <script>
            function mostrarCamposEtapas() {
                const etapasContainer = document.getElementById('etapasContainer');
                const etapasCount = document.getElementById('etapas').value;
                
                // Limpiar el contenedor de etapas antes de agregar nuevos campos
                etapasContainer.innerHTML = '';
                
                // Crear campos dinámicamente para las etapas seleccionadas
                for (let i = 1; i <= etapasCount; i++) {
                    const etapaDiv = document.createElement('div');
                    etapaDiv.classList.add('mb-4');
                    etapaDiv.innerHTML = `
                        <h3 class="font-bold text-lg text-gray-700">Etapa ${i}</h3>
                        <label for="stages${i}" class="block text-gray-700">Título de la Etapa ${i}</label>
                        <input type="text" id="stages${i}" name="stages[${i}][stage_title]" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Título de la etapa">
                        <textarea id="descripcionEtapa${i}" name="stages[${i}][stage_description]" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Descripción de la etapa" maxlength="500"></textarea>
                    `;
                    etapasContainer.appendChild(etapaDiv);
                }
            }
        </script>

</div>

    </body>
</html>