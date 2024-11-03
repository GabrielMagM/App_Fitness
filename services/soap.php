<?php
class InfoSoap{
    public function ObtenerInfo($idDesafio){
        $info = /*Aqui Creamos Un Procedimiento Almacenado SQL para consultar desafios*/ array(
            1 => "El reto de la semana es hacer 100 abdominales",
            2 => "El reto de la semana es hacer 50 sentadillas",
            3 => "El reto de la semana es correr 5 km"
        );
        return isset($info[$idDesafio]) ? $info[$idDesafio] : "No se encontro el reto";
    }
}

$server = new SoapServer(null, array('uri' => 'http://localhost/soap.php'));
$server->setClass('InfoSoap');
$server->handle();
?>