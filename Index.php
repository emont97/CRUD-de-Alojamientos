<?php

//funcion para cargar las variables del archivo .env
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        die("El archivo .env no existe.");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Ignorar comentarios
        }

        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            putenv("$key=$value"); // Cargar la variable al entorno
        }
    }
}

// Cargar las variables desde el archivo .env
loadEnv(__DIR__ . '/.env');

// Acceder a las variables
$host = getenv('DB_HOST');
$port = getenv('DB_PORT'); // Nueva variable para el puerto
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');


try {
    // Incluyendo el puerto en el DSN
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
// Consulta de alojamientos
$query = "SELECT * FROM alojamientos";
$stmt = $pdo->query($query);
$alojamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alojamientos</title>
    <!-- Enlazamos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-content {
            padding: 15px;
        }
        .card-content h2 {
            font-size: 1.5em;
            margin: 0 0 10px;
        }
        .card-content p {
            margin: 5px 0;
            color: #555;
        }
        .card-content .price {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Alojamientos disponibles</h1>
        <div class="row g-4">
            <?php foreach ($alojamientos as $alojamiento): ?>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($alojamiento['Cover_foto'] ?: 'placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($alojamiento['nombre']); ?>">
                        <div class="card-content">
                            <h2><?php echo htmlspecialchars($alojamiento['nombre']); ?></h2>
                            <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($alojamiento['ubicacion']); ?></p>
                            <p><strong>Huespedes:</strong> <?php echo htmlspecialchars($alojamiento['Huespedes']); ?></p>
                            <p><strong>Habitaciones:</strong> <?php echo htmlspecialchars($alojamiento['Habitaciones']); ?></p>
                            <p><strong>Camas:</strong> <?php echo htmlspecialchars($alojamiento['camas']); ?></p>
                            <p><strong>Baños:</strong> <?php echo htmlspecialchars($alojamiento['Baños']); ?></p>
                            <p class="price">$<?php echo number_format($alojamiento['precio'], 2); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Enlazamos el JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
