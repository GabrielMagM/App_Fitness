<?php

require_once 'classes/FitnessService.php';

try {
    $server = new SoapServer("http://localhost/App_Fitness/zzzfolder/routines.wsdl");
    $server->setClass("FitnessService");
    $server->handle();
} catch (SoapFault $fault) {
    echo "Error en el servidor SOAP: " . $fault->getMessage();
}
