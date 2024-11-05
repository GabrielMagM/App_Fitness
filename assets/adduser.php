<?php
session_start();
require_once '../database/config.php'; // Archivo que contiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash de la contraseña

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id; // Guarda el ID del usuario en la sesión
        header('Location: ../pages/main.php'); // Redirige al dashboard
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
