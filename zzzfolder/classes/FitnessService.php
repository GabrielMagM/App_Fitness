<?php

class FitnessService
{
    public function obtenerRutinas($request)
    {
        $tipo = $request->tipo; // Extraer el campo 'tipo'

        // Definir rutinas con estructura compleja
        $rutinas = [
            "Sentadillas" => [
                [
                    "descripcion" => "Ejercicio compuesto que trabaja principalmente los cuadriceps, pero también glúteos y core.",
                ],
            ],
            "Abdominales" => [
                [

                    "descripcion" => "Ejercicio compuesto que trabaja principalmente el core.",
                ],
            ],
            "Flexiones" => [
                [
                    "descripcion" => "Ejercicio compuesto que los brazos, pecho y triceps.",
                ],
            ],
        ];

        if (!isset($rutinas[$tipo])) {
            throw new SoapFault("Server", "El tipo de rutina '{$tipo}' no está disponible.");
        }

        return ["Ejercicios" => $rutinas[$tipo]];
    }
}