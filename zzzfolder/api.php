<?php
require 'conexion.php';

header("Content-Type: application/json");

try {
    $stmt = $pdo->query("SELECT nombre, correo, edad, creado_en FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $usuarios
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
