<?php
try {
    $client = new SoapClient("http://localhost/App_Fitness/zzzfolder/routines.wsdl");

    // Solicitar rutinas del tipo "fuerza"
    $requestFuerza = new stdClass();
    $requestFuerza->tipo = "fuerza";
    $responseFuerza = $client->obtenerRutinas($requestFuerza);

    // Solicitar rutinas del tipo "cardio"
    $requestCardio = new stdClass();
    $requestCardio->tipo = "cardio";
    $responseCardio = $client->obtenerRutinas($requestCardio);

    // Combinar las respuestas y devolverlas en formato JSON
    $result = [
        "fuerza" => $responseFuerza,
        "cardio" => $responseCardio
    ];

    echo json_encode($result);
} catch (SoapFault $e) {
    // Manejo de errores
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
?>