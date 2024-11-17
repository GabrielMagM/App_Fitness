<?php
require 'conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $edad = $_POST['edad'];

    try {
        // Inserta los datos en la tabla
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, edad) VALUES (:nombre, :correo, :edad)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':edad', $edad);
        $stmt->execute();

        // Redirige al usuario a la página de bienvenida
        header("Location: bienvenida.php?nombre=" . urlencode($nombre) . "&correo=" . urlencode($correo) . "&edad=" . urlencode($edad));
        exit;
    } catch (PDOException $e) {
        echo "Error al guardar los datos: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>
