<?php
// Incluye las credenciales de conexión
require_once '../database/config.php';

session_start();
$user_id = $_SESSION['user_id']; // ID del usuario actual

// Manejar adición de actividades predefinidas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_predefined_activity'])) {
    $activity_id = $_POST['activity_id'];
    $duration = (int)$_POST['duration'];

    $query_insert = "
        INSERT INTO user_activities (user_id, activity_id, duration) 
        VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query_insert);
    $stmt->bind_param("iii", $user_id, $activity_id, $duration);
    $stmt->execute();
}

// Manejar adición de actividades personalizadas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_custom_activity'])) {
    $custom_activity_name = $_POST['custom_activity_name'];
    $duration = (int)$_POST['duration'];

    $query_insert = "
        INSERT INTO user_activities (user_id, custom_activity_name, duration) 
        VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query_insert);
    $stmt->bind_param("isi", $user_id, $custom_activity_name, $duration);
    $stmt->execute();
}

// Manejar eliminación de actividades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_activity'])) {
    $activity_id = $_POST['activity_id'];

    $query_delete = "DELETE FROM user_activities WHERE id = ?";
    $stmt = $conn->prepare($query_delete);
    $stmt->bind_param("i", $activity_id);
    $stmt->execute();
}

// Obtener actividades seleccionadas por el usuario
$query_user_activities = "
    SELECT ua.id, a.name AS predefined_name, ua.custom_activity_name, ua.duration 
    FROM user_activities ua
    LEFT JOIN available_activities a ON ua.activity_id = a.id
    WHERE ua.user_id = ?";
$stmt = $conn->prepare($query_user_activities);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_activities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Obtener actividades disponibles
$query_available_activities = "
    SELECT id, name 
    FROM available_activities 
    WHERE id NOT IN (
        SELECT activity_id FROM user_activities WHERE user_id = ?
    )";
$stmt = $conn->prepare($query_available_activities);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$available_activities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Body Styles */
        body {
            background-color: #f7fafc;
            font-family: 'Roboto', sans-serif;
            color: #2d3748;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background-color: #2d3748;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* Header */
        .header {
            text-align: center;
            padding: 20px;
            background-color: #3182ce;
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }

        /* Card Style */
        .card {
            background-color: rgba(56, 189, 248, 0.1); /* Azul transparente */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: #2d3748;
        }

        .card:hover {
            background-color: rgba(56, 189, 248, 0.2); /* Hover azul más fuerte */
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-body {
            font-size: 1rem;
        }

        /* Container Layout */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Buttons in the header */
        .button-container {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-top: -40px;
        }
        .button-container a {
            text-decoration: none;
            font-size: 1.25rem;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #3182ce;
            transition: background-color 0.3s;
        }
        .button-container a:hover {
            background-color: #2b6cb0;
        }

        /* Activities layout */
        .activities-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Activity Cards */
        .activity-card {
            display: flex;
            justify-content: space-between;
            background-color: rgba(56, 189, 248, 0.1); /* Azul transparente */
            padding: 20px;
            gap: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        
        .add-activities-container {
            display: flex;
            flex-direction: column; /* Mantiene las cartas una debajo de otra */
            gap: 10px; /* Reduce el espacio entre las cartas */
        }



        .add-activities-container form {
            width: 48%;
            background-color: rgba(56, 189, 248, 0.1); /* Azul transparente */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Button Styles */
        .button {
            background-color: #38a169;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #2f855a;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            Actividades Fitness
        </div>

        <!-- Buttons in the Header (Right Side) -->
        <div class="button-container">
            <a href="activities.php" class="text-white hover:text-green-500" title="Actividades Físicas">
                <i class="fas fa-dumbbell fa-lg"></i>
            </a>
            <a href="stats.php" class="text-white hover:text-blue-500" title="Ver Estadísticas">
                <i class="fas fa-chart-line fa-lg"></i>
            </a>
            <a href="../assets/logout.php" class="text-white hover:text-red-500" title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt fa-lg"></i>
            </a>
        </div>

        <!-- Actividades del Usuario -->
        <div class="activities-container">
            <div class="card">
                <h3 class="card-title">Tus Actividades</h3>
                <?php if (!empty($user_activities)): ?>
                    <ul class="list-disc ml-5">
                        <?php foreach ($user_activities as $activity): ?>
                            <li>
                                <?php echo htmlspecialchars($activity['predefined_name'] ?: $activity['custom_activity_name']); ?>
                                (<?php echo $activity['duration']; ?> minutos)
                                <form method="post" class="inline">
                                    <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                                    <button type="submit" name="remove_activity" class="text-red-500 hover:text-red-700 ml-2">Eliminar</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Aún no has seleccionado ninguna actividad.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Agregar Actividad -->
        <div class="add-activities-container">
            <!-- Agregar Actividad Disponible -->
            <div class="card">
                <h3 class="card-title">Agregar Actividad Disponible</h3>
                <?php if (!empty($available_activities)): ?>
                    <form method="post">
                        <select name="activity_id" required>
                            <option value="">Selecciona una actividad</option>
                            <?php foreach ($available_activities as $activity): ?>
                                <option value="<?php echo $activity['id']; ?>">
                                    <?php echo htmlspecialchars($activity['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="duration" min="1" placeholder="Duración (minutos)" required>
                        <button type="submit" name="add_predefined_activity" class="button">Agregar</button>
                    </form>
                <?php else: ?>
                    <p>Ya has añadido todas las actividades disponibles.</p>
                <?php endif; ?>
            </div>

            <!-- Agregar Actividad Personalizada -->
            <div class="card">
                <h3 class="card-title">Agregar Actividad Personalizada</h3>
                <form method="post">
                    <input type="text" name="custom_activity_name" placeholder="Nombre de la actividad" required>
                    <input type="number" name="duration" min="1" placeholder="Duración (minutos)" required>
                    <button type="submit" name="add_custom_activity" class="button">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
