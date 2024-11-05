<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $goal = $_POST['goal'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO challenges (user_id, description, duration, goal) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $description, $duration, $goal);

    if ($stmt->execute()) {
        echo "Desafío creado exitosamente.";
        header('Location: ../pages/main.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>