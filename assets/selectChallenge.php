<?php
session_start();
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $challenge_id = $_POST['challenge_id'];

    // Insertar en la tabla user_challenges
    $stmt = $conn->prepare("INSERT INTO user_challenges (user_id, challenge_id, start_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $challenge_id);

    if ($stmt->execute()) {
        // Actualizar el challenge para asignarlo al usuario
        $updateStmt = $conn->prepare("UPDATE challenges SET user_id = ? WHERE id = ?");
        $updateStmt->bind_param("ii", $user_id, $challenge_id);
        $updateStmt->execute();

        header('Location: ../pages/main.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
