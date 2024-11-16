<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
</head>
<body>
    <h1>¡Bienvenido!</h1>
    <p>Nombre: <?php echo htmlspecialchars($_GET['nombre']); ?></p>
    <p>Correo: <?php echo htmlspecialchars($_GET['correo']); ?></p>
    <p>Edad: <?php echo intval($_GET['edad']); ?></p>
</body>
</html>
