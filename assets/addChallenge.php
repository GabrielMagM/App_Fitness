<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de configuración de la base de datos
require_once '../database/config.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $description = $_POST['description'];  // Descripción del desafío
    $duration = $_POST['duration'];       // Duración del desafío
    $goal = $_POST['goal'];               // Objetivo del desafío
    $t_stages = $_POST['t_stages']; // Total de etapas
    
    // Preparar la consulta SQL para insertar el nuevo desafío en la tabla challenges
    $stmt = $conn->prepare("INSERT INTO challenges (user_id, description, duration, goal, t_stages) VALUES (?, ?, ?, ?, ?)");
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión
    $stmt->bind_param("isisii", $user_id, $description, $duration, $goal, $t_stages);

    // Ejecutar la consulta para insertar el desafío
    if ($stmt->execute()) {
        // Obtener el ID del desafío recién creado
        $challenge_id = $stmt->insert_id;

        // Insertar las etapas en la tabla stages
        $stmt = $conn->prepare("INSERT INTO stages (user_id, challenge_id, stage_num, stage_name, stage_goal) VALUES (?, ?, ?, ?, ?)");
        $stage_num = 1;

        // Iterar sobre las etapas enviadas en el formulario
        foreach ($_POST['stages'] as $stage) {
            $stage_name = $stage['stage_name'];    // Acción de la etapa
            $stage_goal = $stage['stage_goal'];    // Descripción de la etapa

            // Insertar cada etapa con el challenge_id correspondiente
            $stmt->bind_param("iiiss", $user_id, $challenge_id, $stage_num, $stage_name, $stage_goal);
            $stmt->execute(); // Ejecutar la inserción de cada etapa
            $stage_num++;     // Incrementar el número de la etapa
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
