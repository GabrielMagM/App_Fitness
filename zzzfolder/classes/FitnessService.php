<?php

class FitnessService
{
        public function obtenerRutinas($request)
    {
        $tipo = $request->tipo; // Extraer el campo 'tipo'

        $rutinas = [
            "fuerza" => ["Sentadilla", "Press banca", "Peso muerto"],
            "cardio" => ["Correr", "Bicicleta", "Saltar la cuerda"],
        ];

        if (!isset($rutinas[$tipo])) {
            throw new SoapFault("Server", "El tipo de rutina '{$tipo}' no está disponible.");
        }

        return $rutinas[$tipo];
    }
}
