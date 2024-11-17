<?php
require 'db.php'; // Incluir la conexión a la base de datos

header("Content-Type: application/json");

$request_method = $_SERVER['REQUEST_METHOD'];
$user_id = $_GET['user_id']; // Asumimos que el ID del usuario se pasa en la URL

// Obtener todas las etapas para los desafíos del usuario
if ($request_method == 'GET') {
    $challenge_id = $_GET['challenge_id']; // Asumimos que el ID del desafío se pasa en la URL

    $sql = "SELECT * FROM stages WHERE user_id = ? AND challenge_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $challenge_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stages = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $stages
    ]);
}

// Insertar una nueva etapa para un desafío
if ($request_method == 'POST') {
    $challenge_id = $_POST['challenge_id'];
    $stage_num = $_POST['stage_num'];
    $stage_name = $_POST['stage_name'];
    $stage_goal = $_POST['stage_goal'];

    $sql = "INSERT INTO stages (user_id, challenge_id, stage_num, stage_name, stage_goal) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $user_id, $challenge_id, $stage_num, $stage_name, $stage_goal);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Stage created successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error creating stage"
        ]);
    }
}

// Eliminar una etapa
if ($request_method == 'DELETE') {
    $stage_id = $_GET['stage_id'];

    $sql = "DELETE FROM stages WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $stage_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Stage deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error deleting stage"
        ]);
    }
}
?>
