<?php
require '../database/config.php'; // Incluir la conexión a la base de datos

header("Content-Type: application/json");

$request_method = $_SERVER['REQUEST_METHOD'];
$user_id = $_GET['user_id']; // Asumimos que el ID del usuario se pasa en la URL

// Obtener todos los desafíos del usuario
if ($request_method == 'GET') {
    $sql = "SELECT * FROM challenges WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $challenges = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $challenges
    ]);
}

// Insertar un nuevo desafío
if ($request_method == 'POST') {
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $goal = $_POST['goal'];

    $sql = "INSERT INTO challenges (user_id, description, duration, goal) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $user_id, $description, $duration, $goal);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Challenge created successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error creating challenge"
        ]);
    }
}

// Eliminar un desafío
if ($request_method == 'DELETE') {
    $challenge_id = $_GET['challenge_id'];

    $sql = "DELETE FROM challenges WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $challenge_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Challenge deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error deleting challenge"
        ]);
    }
}
?>
