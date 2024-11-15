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
        // Inserta Desafios por Default a los nuevos usuarios
        $user_id = $conn->insert_id;
        $stmt = $conn->prepare("INSERT INTO challenges (user_id, title, duration, description) VALUES 
        (?, 'Desafío de 5km al día', 30, 'Correr 5 km diariamente'),
        (?, 'Desafío de abdominales', 14, 'Hacer 50 abdominales diarias'),
        (?, 'Desafío de agua', 7, 'Beber 2 litros de agua diarios')");
        $stmt->bind_param("iii", $user_id, $user_id, $user_id);
        $stmt->execute();

        header('Location: ../pages/main.php'); // Redirige al dashboard
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>
