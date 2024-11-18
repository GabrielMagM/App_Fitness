<?php
try {
    $client = new SoapClient("http://localhost/App_Fitness/zzzfolder/routines.wsdl");

    // Solicitar rutinas del tipo "fuerza"
    $request = new stdClass();
    $request->tipo = "fuerza";
    $request->tipo = "cardio";
    $request->tipo = "flexibilidad";
    $response = $client->obtenerRutinas($request);

    // Devolver las rutinas en formato JSON
    echo json_encode($response);
} catch (SoapFault $e) {
    // Manejo de errores
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
