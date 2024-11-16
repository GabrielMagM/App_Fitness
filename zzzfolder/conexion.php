<?php
$host = "localhost";
$dbname = "usuarios_db";
$username = "root"; // Cambia esto si tienes otro usuario en MySQL
$password = "newpassword"; // Cambia esto si tienes una contraseña para MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>