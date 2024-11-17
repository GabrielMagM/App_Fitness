<?php
require_once '../database/config.php';

if (isset($_GET['challenge_id'])) {
    $challenge_id = $_GET['challenge_id'];

    // Obtener los detalles del desafío
    $stmt = $conn->prepare("SELECT * FROM challenges WHERE id = ?");
    $stmt->bind_param("i", $challenge_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $challenge = $result->fetch_assoc();

    // Obtener las etapas del desafío
    $stmt_stages = $conn->prepare("SELECT * FROM stages WHERE challenge_id = ?");
    $stmt_stages->bind_param("i", $challenge_id);
    $stmt_stages->execute();
    $result_stages = $stmt_stages->get_result();


    echo json_encode($challenge);
}
?>
