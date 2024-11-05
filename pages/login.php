<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body{
            background-image: url('https://img.freepik.com/foto-gratis/vista-angulo-hombre-musculoso-irreconocible-preparandose-levantar-barra-club-salud_637285-2497.jpg?t=st=1730662087~exp=1730665687~hmac=b7390650204a7b3962e1f222c437f85ff1a56a524109c217366f21857a4ee485&w=826'); /* Cambia la URL a tu imagen deseada */
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-900 bg-opacity-75">

    <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-center mb-6">Iniciar Sesión</h2>
        <form action="../assets/loginUser.php" method="POST"> <!-- Ruta de envio del formulario -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" name="email" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="tuemail@example.com">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Tu contraseña">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded">Iniciar Sesión</button>
        </form>
        <p class="mt-4 text-center">
            ¿No tienes una cuenta? <a href="register.php" class="text-blue-500 hover:underline">Registrarse</a>
        </p>
    </div>

</body>
</html>
