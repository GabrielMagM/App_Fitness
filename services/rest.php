<?php
header("Content-Type: application/json");
require_once '../database/config.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
$inputData = json_decode(file_get_contents("php://input"), true);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_user_activities':
        if ($requestMethod === 'GET') {
            getUserActivities($conn, $_GET['user_id']);
        } else {
            respond(405, ["message" => "Método no permitido"]);
        }
        break;

    case 'get_available_activities':
        if ($requestMethod === 'GET') {
            getAvailableActivities($conn, $_GET['user_id']);
        } else {
            respond(405, ["message" => "Método no permitido"]);
        }
        break;

    case 'register_activity':
        if ($requestMethod === 'POST') {
            registerActivity($conn, $inputData);
        } else {
            respond(405, ["message" => "Método no permitido"]);
        }
        break;

    case 'remove_activity':
        if ($requestMethod === 'POST') {
            removeActivity($conn, $inputData);
        } else {
            respond(405, ["message" => "Método no permitido"]);
        }
        break;

    case 'get_challenge_stats':
        if ($requestMethod === 'GET') {
            getChallengeStats($conn);
        } else {
            respond(405, ["message" => "Método no permitido"]);
        }
        break;

    case 'get_user_stats':
        if ($requestMethod === 'GET') {
            getUserStats($conn, $_GET['user_id']);
        } else {
            respond(405, ["message" => "Método no permitido"]);
        }
        break;

    default:
        respond(404, ["message" => "Endpoint no encontrado"]);
        break;
}

function getUserActivities($conn, $userId) {
    $query = "
        SELECT ua.id, a.name, ua.custom_activity_name, ua.duration 
        FROM user_activities ua
        LEFT JOIN available_activities a ON ua.activity_id = a.id
        WHERE ua.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $activities = $result->fetch_all(MYSQLI_ASSOC);
    respond(200, $activities);
}

function getAvailableActivities($conn, $userId) {
    $query = "
        SELECT id, name 
        FROM available_activities 
        WHERE id NOT IN (
            SELECT activity_id FROM user_activities WHERE user_id = ?
        )";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $activities = $result->fetch_all(MYSQLI_ASSOC);
    respond(200, $activities);
}

function registerActivity($conn, $data) {
    $userId = $data['user_id'] ?? null;
    $activityId = $data['activity_id'] ?? null;
    $customActivityName = $data['custom_activity_name'] ?? null;
    $duration = $data['duration'] ?? null;

    if (!$userId || !$duration || (!$activityId && !$customActivityName)) {
        respond(400, ["message" => "Datos incompletos"]);
        return;
    }

    $query = "INSERT INTO user_activities (user_id, activity_id, custom_activity_name, duration) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisi", $userId, $activityId, $customActivityName, $duration);

    if ($stmt->execute()) {
        respond(201, ["message" => "Actividad registrada con éxito"]);
    } else {
        respond(500, ["message" => "Error al registrar la actividad"]);
    }
}

function removeActivity($conn, $data) {
    $activityId = $data['activity_id'] ?? null;

    if (!$activityId) {
        respond(400, ["message" => "ID de actividad no proporcionado"]);
        return;
    }

    $query = "DELETE FROM user_activities WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $activityId);

    if ($stmt->execute()) {
        respond(200, ["message" => "Actividad eliminada con éxito"]);
    } else {
        respond(500, ["message" => "Error al eliminar la actividad"]);
    }
}

function getChallengeStats($conn) {
    $query = "
        SELECT 
            c.id AS challenge_id, 
            c.description, 
            COUNT(uc.user_id) AS participants, 
            SUM(uc.completed) AS completed_count 
        FROM challenges c
        LEFT JOIN user_challenges uc ON c.id = uc.challenge_id
        GROUP BY c.id";
    $result = $conn->query($query);

    if ($result) {
        $stats = $result->fetch_all(MYSQLI_ASSOC);
        respond(200, ["challenges" => $stats]);
    } else {
        respond(500, ["message" => "Error al obtener estadísticas"]);
    }
}

function getUserStats($conn, $userId) {
    // Desafíos completados
    $stmt = $conn->prepare("SELECT COUNT(*) as completed_count FROM user_challenges WHERE user_id = ? AND completed = 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $completedCount = $result->fetch_assoc()['completed_count'] ?? 0;

    // Tiempo total
    $stmt = $conn->prepare("SELECT SUM(c.duration) as total_days FROM user_challenges uc JOIN challenges c ON uc.challenge_id = c.id WHERE uc.user_id = ? AND uc.completed = 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalDays = $result->fetch_assoc()['total_days'] ?? 0;

    // Progreso general
    $stmt = $conn->prepare("SELECT COUNT(*) as total_challenges FROM user_challenges WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalChallenges = $result->fetch_assoc()['total_challenges'] ?? 0;

    $overallProgress = $totalChallenges > 0 ? ($completedCount / $totalChallenges) * 100 : 0;

    // Lista de desafíos completados
    $stmt = $conn->prepare("SELECT c.description FROM user_challenges uc JOIN challenges c ON uc.challenge_id = c.id WHERE uc.user_id = ? AND uc.completed = 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $completedChallenges = $result->fetch_all(MYSQLI_ASSOC);

    respond(200, [
        "completed_count" => $completedCount,
        "total_days" => $totalDays,
        "overall_progress" => round($overallProgress, 2),
        "completed_challenges" => $completedChallenges
    ]);
}

function respond($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}
?>