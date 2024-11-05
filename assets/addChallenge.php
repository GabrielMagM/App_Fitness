<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de configuración de la base de datos
require_once '../database/config.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $goal = $_POST['goal'];

    // Preparar la consulta SQL para insertar el nuevo desafío
    $stmt = $conn->prepare("INSERT INTO challenges (user_id, description, duration, goal) VALUES (?, ?, ?, ?)");
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión
    $stmt->bind_param("isis", $user_id, $description, $duration, $goal);

    // Ejecutar la consulta y verificar si se ha insertado correctamente
    if ($stmt->execute()) {
        // Redirigir al usuario de vuelta al dashboard
        header('Location: ../pages/main.php');
        exit();
    } else {
        // Mostrar un mensaje de error si la inserción falla
        echo "Error: " . $stmt->error;
    }
}
?>