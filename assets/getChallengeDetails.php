<?php
require_once '../database/config.php';

if (isset($_GET['challenge_id'])) {
    $challenge_id = $_GET['challenge_id'];

    $stmt = $conn->prepare("SELECT * FROM challenges WHERE id = ?");
    $stmt->bind_param("i", $challenge_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $challenge = $result->fetch_assoc();

    echo json_encode($challenge);
}
?>
