<?php
try {
    $client = new SoapClient("http://localhost/App_Fitness/zzzfolder/routines.wsdl");

    // Solicitar rutinas de diferentes tipos
    $requestSentadillas = new stdClass();
    $requestSentadillas->tipo = "Sentadillas";
    $responseSentadillas = $client->obtenerRutinas($requestSentadillas);

    $requestAbdominales = new stdClass();
    $requestAbdominales->tipo = "Abdominales";
    $responseAbdominales = $client->obtenerRutinas($requestAbdominales);

    $requestFlexiones = new stdClass();
    $requestFlexiones->tipo = "Flexiones";
    $responseFlexiones = $client->obtenerRutinas($requestFlexiones);

    // Combinar las respuestas correctamente
    $result = [
        "Sentadillas" => $responseSentadillas,
        "Abdominales" => $responseAbdominales,
        "Flexiones" => $responseFlexiones
    ];

    // Retornar como JSON
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (SoapFault $e) {
    // Manejo de errores
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>