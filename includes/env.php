<?php
// Funcion para cargar las variables del archivo .env
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
?>
