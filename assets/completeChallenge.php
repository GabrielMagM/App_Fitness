<?php
session_start();
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../pages/login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $challenge_id = $_POST['challenge_id'];

    // Actualizar el desafío como completado y registrar la fecha de culminación
    $stmt = $conn->prepare("UPDATE user_challenges SET completed = 1, end_date = NOW() WHERE user_id = ? AND challenge_id = ?");
    $stmt->bind_param("ii", $user_id, $challenge_id);

    if ($stmt->execute()) {
        // Redirigir al usuario de vuelta al dashboard
        header('Location: ../pages/main.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
