<?php
session_start();
require_once '../database/config.php'; // Archivo que contiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id; // Guarda el ID del usuario en la sesión
            header('Location: ../pages/main.php'); // Redirige al dashboard
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.');</script>";
        }
    } else {
        echo "<script>
                alert('Usuario no encontrado.');
                window.location.href = '../pages/login.php'; // Cambia la URL si tu página de login es diferente
              </script>";
    }
}
?>
