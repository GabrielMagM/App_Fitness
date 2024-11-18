<?php

class FitnessService
{
        public function obtenerRutinas($request)
    {
        $tipo = $request->tipo; // Extraer el campo 'tipo'

        $rutinas = [
            "Sentadillas" => ["Ejercicio compuesto que trabaja principalmente los cuadriceps, pero tambien gluteos y core."],
            "LegPress" => ["Permite cargar mas peso y aislar los cuadriceps al ajustar la posicion de los pies."],
            "LegExtension" => ["Ejercicio aislado ideal para focalizar el trabajo en los cuadriceps."],
            "FrontSquat" => ["Variante de la sentadilla donde el peso se coloca al frente, enfocandose mas en los cuadriceps."],
            "ForwardLunges" => ["Trabaja cuadriceps y gluteos, mejorando tambien el equilibrio."]
            
        ];

        if (!isset($rutinas[$tipo])) {
            throw new SoapFault("Server", "El tipo de rutina '{$tipo}' no está disponible.");
        }

        return $rutinas[$tipo];
    }
}
