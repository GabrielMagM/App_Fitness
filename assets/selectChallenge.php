<?php
session_start();
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $challenge_id = $_POST['challenge_id'];

    // Verificar si el usuario ya está inscrito en el desafío
    $checkStmt = $conn->prepare("SELECT * FROM user_challenges WHERE user_id = ? AND challenge_id = ?");
    $checkStmt->bind_param("ii", $user_id, $challenge_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        // Si no está inscrito, insertar en user_challenges
        $stmt = $conn->prepare("INSERT INTO user_challenges (user_id, challenge_id, start_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $user_id, $challenge_id);

        if ($stmt->execute()) {
            header('Location: ../pages/main.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Ya estás inscrito en este desafío.";
    }
}
?>
