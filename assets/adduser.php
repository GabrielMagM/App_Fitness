<?php
session_start();
require_once '../database/config.php'; // Archivo que contiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash de la contraseña

    // Inserta el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id; // Guarda el ID del usuario en la sesión
        $user_id = $conn->insert_id;

        // Inserta desafíos por defecto para el nuevo usuario, incluyendo total_stages
        $stmt = $conn->prepare("INSERT INTO challenges (user_id, description, duration, goal, total_stages) VALUES 
        (?, 'Desafío de 5km al día', 30, 'Correr 5 km diariamente', 2),
        (?, 'Desafío de abdominales', 14, 'Hacer 50 abdominales diarias', 2),
        (?, 'Desafío de agua', 7, 'Beber 2 litros de agua diarios', 2)");
        $stmt->bind_param("iii", $user_id, $user_id, $user_id);
        
        if ($stmt->execute()) {
            // Obtén los IDs de los desafíos recién creados
            $challenge_ids = [];
            $stmt = $conn->prepare("SELECT id FROM challenges WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $challenge_ids[] = $row['id'];
            }

            // Inserta las etapas para cada desafío
            $stmt = $conn->prepare("INSERT INTO stages (user_id, challenge_id, stage_num, stage_name, stage_goal) VALUES 
            (?, ?, 1, 'Calentamiento', 'Hacer 10 minutos de estiramientos'),
            (?, ?, 2, 'Meta principal', 'Cumplir el objetivo diario'),
            (?, ?, 1, 'Preparación', 'Planificar los horarios'),
            (?, ?, 2, 'Ejercicio', 'Ejecutar los ejercicios recomendados'),
            (?, ?, 1, 'Inicio del hábito', 'Beber 1 vaso de agua al levantarse'),
            (?, ?, 2, 'Mantener hidratación', 'Beber agua durante el día')");
            
            // Vincula los parámetros
            $stmt->bind_param("iiiiiiiiiiii",
                $user_id, $challenge_ids[0], $user_id, $challenge_ids[0],
                $user_id, $challenge_ids[1], $user_id, $challenge_ids[1],
                $user_id, $challenge_ids[2], $user_id, $challenge_ids[2]
            );

            // Verifica si la ejecución fue exitosa
            if ($stmt->execute()) {
                echo "Etapas insertadas correctamente.";
                header('Location: ../pages/main.php'); // Redirige al dashboard
                exit();
            } else {
                echo "Error al insertar las etapas: " . $stmt->error; // Muestra el error de ejecución
            }
        } else {
            echo "Error al insertar los desafíos: " . $stmt->error; // Muestra el error de inserción de desafíos
        }
    } else {
        echo "Error: " . $stmt->error; // Muestra el error al insertar el usuario
    }
}
?>
