<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://img.freepik.com/foto-gratis/equipo-gimnasio-3d_23-2151114139.jpg?uid=R85484454&ga=GA1.1.1020600503.1723322301&semt=ais_hybrid'); /* Cambia la URL a tu imagen deseada */
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-900 bg-opacity-75">

    <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-center mb-6">Registro</h2>
        <form action="../assets/addUser.php" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nombre</label>
                <input type="text" id="name" name="name" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Tu nombre">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" name="email" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="tuemail@example.com">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Tu contraseña">
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Confirma tu contraseña">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded">Registrarse</button>
        </form>
        <p class="mt-4 text-center">
            ¿Ya tienes una cuenta? <a href="login.php" class="text-blue-500 hover:underline">Iniciar sesión</a>
        </p>
    </div>

</body>
</html>
