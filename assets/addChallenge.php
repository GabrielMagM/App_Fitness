<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de configuración de la base de datos
require_once '../database/config.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $stages = $_POST['stages'];  // Las etapas enviadas en el formulario

    // Preparar la consulta SQL para insertar el nuevo desafío
    $stmt = $conn->prepare("INSERT INTO challenges (user_id, title, duration, description) VALUES (?, ?, ?, ?)");
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión
    $stmt->bind_param("isis", $user_id, $title, $duration, $description);

    // Ejecutar la consulta para insertar el desafío
    if ($stmt->execute()) {
        // Obtener el ID del desafío recién creado
        $challenge_id = $stmt->insert_id;

        // Insertar las etapas en la tabla stages
        $stmt = $conn->prepare("INSERT INTO stages (challenge_id, stage_num, stage_title, stage_description) VALUES (?, ?, ?, ?)");
        $stage_num = 1;

        foreach ($stages as $stage) {
            $stage_title = $stage['stage_title'];
            $stage_description = $stage['stage_description'];

            // Insertar cada etapa con el challenge_id
            $stmt->bind_param("iiss", $challenge_id, $stage_num, $stage_title, $stage_description);
            $stmt->execute();
            $stage_num++;
        }

        // Redirigir o mostrar un mensaje de éxito
        header('Location: ../pages/main.php');  // Redirigir al dashboard o página deseada
        exit();

    } else {
        // Mostrar un mensaje de error si la inserción falla
        echo "Error: " . $stmt->error;
    }

    // Cerrar la sentencia
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>