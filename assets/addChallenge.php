<?php
session_start();
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $goal = $_POST['goal'];

    $stmt = $conn->prepare("INSERT INTO challenges (user_id, description, duration, goal) VALUES (NULL, ?, ?, ?)");
    $stmt->bind_param("sis", $description, $duration, $goal);

    if ($stmt->execute()) {
        header('Location: ../pages/main.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
