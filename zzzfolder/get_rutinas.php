<?php
try {
    $client = new SoapClient("http://localhost/App_Fitness/zzzfolder/routines.wsdl");

    // Solicitar rutinas del tipo "Sentadillas"
    $requestSentadillas = new stdClass();
    $requestSentadillas->tipo = "Sentadillas";
    $responseSentadillas = $client->obtenerRutinas($requestSentadillas);

    // Solicitar rutinas del tipo "LegPress"
    $requestLegPress = new stdClass();
    $requestLegPress->tipo = "LegPress";
    $responseLegPress = $client->obtenerRutinas($requestLegPress);

    // Solicitar rutinas del tipo "LegExtension"
    $requestLegExtension = new stdClass();
    $requestLegExtension->tipo = "LegExtension";
    $responseLegExtension = $client->obtenerRutinas($requestLegExtension);

    // Solicitar rutinas del tipo "FrontSquat"
    $requestFrontSquat = new stdClass();
    $requestFrontSquat->tipo = "FrontSquat";
    $responseFrontSquat = $client->obtenerRutinas($requestFrontSquat);

    // Solicitar rutinas del tipo "ForwardLunges"
    $requestForwardLunges = new stdClass();
    $requestForwardLunges->tipo = "ForwardLunges";
    $responseForwardLunges = $client->obtenerRutinas($requestForwardLunges);

    // Combinar las respuestas correctamente
    $result = [
        "Sentadillas" => $responseSentadillas,
        "LegPress" => $responseLegPress,
        "LegExtension" => $responseLegExtension,
        "FrontSquat" => $responseFrontSquat, // Corregido
        "ForwardLunges" => $responseForwardLunges
    ];

    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (SoapFault $e) {
    // Manejo de errores
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>