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
        
  /* Estilo del navbar */
  .custom-navbar {
    background-color: #333; /* Fondo oscuro */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
  }

  /* Estilo del logo o nombre del sitio */
  .navbar-brand {
    color: #fff !important; /* Texto blanco */
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: 1px;
  }

  /* Estilo de los enlaces del menú */
  .navbar-nav {
    display: flex;
    justify-content: center;
    width: 100%;
  }

  /* Estilo de cada enlace */
  .nav-link {
    color: #ddd !important; /* Color gris claro */
    padding: 12px 20px;
    font-size: 1rem;
    font-weight: 500;
    text-transform: uppercase;
    transition: color 0.3s, background-color 0.3s;
  }

  /* Efecto al pasar el ratón sobre los enlaces */
  .nav-link:hover {
    color: #fff !important;
    background-color: #007bff; /* Fondo azul */
    border-radius: 5px;
  }

  /* Estilo para el enlace activo */
  .nav-link.active {
    background-color: #0056b3; /* Fondo azul más oscuro */
    color: #fff !important;
    border-radius: 5px;
  }

  /* Estilo del botón de toggle para dispositivos móviles */
  .navbar-toggler-icon {
    background-color: #fff;
  }

  /* Diseño responsivo: centra los enlaces */
  .navbar-collapse {
    justify-content: center;
  }

  /* Sombra en los enlaces deshabilitados */
  .nav-link.disabled {
    color: #aaa !important;
  }
</style>

<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container">
    <a class="navbar-brand" href="#">CRUD Alojamientos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="../Index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Reservaciones</a>
        </li>
    
      </ul>
    </div>
  </div>
</nav>

<?php
// Incluir la conexión a la base de datos
require_once '../includes/db.php';

// Iniciar variables para mostrar mensaje
$mensaje = "";

// Consultar la lista de responsables
$responsables = [];
$stmt = $pdo->query("SELECT Usuario_Id, nombre FROM usuarios");
$responsables = $stmt->fetchAll();

// Consultar la lista de alojamientos con imágenes y precios
$alojamientos = [];
$stmt = $pdo->query("SELECT Alojamiento_Id, nombre, Cover_foto, precio FROM alojamientos");
$alojamientos = $stmt->fetchAll();

// Consultar las reservaciones ya existentes
$reservaciones = [];
$stmt = $pdo->query("SELECT * FROM reservaciones");
$reservaciones = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservacion_id'])) {
    $reservacion_id = $_POST['reservacion_id'];

    try {
        // Eliminar la reservación de la base de datos
        $stmt = $pdo->prepare("DELETE FROM reservaciones WHERE Num_Reservacion = ?");
        $stmt->execute([$reservacion_id]);

        $mensaje = "Reservación eliminada con éxito.";
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar la reservación: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reservacion_id'])) {
    // Obtener y validar los datos del formulario de creación
    var_dump($_POST); // Esto te ayudará a ver qué datos están llegando.
    $usuario_id = $_POST['usuario_id'] ?? null;
    $alojamiento_id = $_POST['alojamiento_id'] ?? null;
    $check_in = $_POST['check_in'] ?? null;
    $check_out = $_POST['check_out'] ?? null;
    $total_dias = $_POST['num_dias'] ?? null;
    $total_pagado = $_POST['total_pagado'] ?? null;
    $num_reservacion = $_POST['num_reservacion'] ?? null;

    // Validar que check_out no sea menor que check_in
    if ($check_in && $check_out && $check_in >= $check_out) {
        $mensaje = "La fecha de salida debe ser posterior a la fecha de entrada.";
    }

    if ($usuario_id && $alojamiento_id && $check_in && $check_out && $total_pagado && $num_reservacion && !$mensaje) {
        try {
            // Verificar si ya existe una reservación en el rango de fechas
            $stmt = $pdo->prepare("
                SELECT 1 
                FROM reservaciones 
                WHERE Alojamiento_Id = ? 
                AND (
                    (check_in BETWEEN ? AND ?) OR 
                    (check_out BETWEEN ? AND ?) OR 
                    (check_in <= ? AND check_out >= ?)
                )
            ");
            $stmt->execute([$alojamiento_id, $check_in, $check_out, $check_in, $check_out, $check_in, $check_out]);
            $existing_reservation = $stmt->fetchColumn();

            if ($existing_reservation) {
                $mensaje = "Ya existe una reservación en esas fechas. Por favor, elija otro rango de fechas.";
            } else {
                // Insertar la nueva reservación sin el campo 'Total_Dias'
                $stmt = $pdo->prepare("
                    INSERT INTO reservaciones (Usuario_Id, Alojamiento_Id, check_in, check_out, Total_Pagado, Num_Reservacion, fecha_reservacion) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$usuario_id, $alojamiento_id, $check_in, $check_out, $total_pagado, $num_reservacion]);

                $mensaje = "Reservación creada con éxito.";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al realizar la reservación: " . $e->getMessage();
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function () {
    $('#alojamiento_id').change(function () {
        const selectedOption = $(this).find(':selected');
        const precio = selectedOption.data('precio');
        const imgSrc = selectedOption.data('img');

        // Actualiza el precio
        $('#precio').val(precio);
        calcularTotal();

        // Actualiza la imagen
        if (imgSrc) {
            $('#alojamiento_imagen').attr('src', imgSrc).show();
        } else {
            $('#alojamiento_imagen').hide();
        }
    });

    $('#num_dias').on('input', function () {
        calcularTotal();
    });

    function calcularTotal() {
        const precio = parseFloat($('#precio').val()) || 0;
        const numDias = parseInt($('#num_dias').val()) || 0; // Asegúrate de que sea 0 si no se ha introducido nada
        const total = precio * numDias;
        $('#total_pagado').val(total.toFixed(2));
    }

    // Inicializar el valor de Total_Dias al cargar la página si ya hay un valor en el campo
    const initialNumDias = $('#num_dias').val();
    if (initialNumDias && !isNaN(initialNumDias)) {
        $('#num_dias').val(initialNumDias);
        calcularTotal();
    }
});

    </script>
</head>
<body class="bg-light">


    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Gestión de Reservaciones</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                    <div class="card-body">
                        <h5 class="card-title text-center">Crear Nueva Reservación</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label">Responsable</label>
                                <select name="usuario_id" id="usuario_id" class="form-control" required>
                                    <option value="">Seleccione un responsable</option>
                                    <?php foreach ($responsables as $responsable): ?>
                                        <option value="<?= $responsable['Usuario_Id'] ?>"><?= htmlspecialchars($responsable['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="alojamiento_id" class="form-label">Alojamiento</label>
                                <select name="alojamiento_id" id="alojamiento_id" class="form-control" required>
                                    <option value="" data-img="">Seleccione un alojamiento</option>
                                    <?php foreach ($alojamientos as $alojamiento): ?>
                                        <option 
                                            value="<?= $alojamiento['Alojamiento_Id'] ?>" 
                                            data-img="<?= htmlspecialchars($alojamiento['Cover_foto']) ?>" 
                                            data-precio="<?= $alojamiento['precio'] ?>">
                                            <?= htmlspecialchars($alojamiento['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3 text-center">
                                <img id="alojamiento_imagen" src="" alt="Imagen del alojamiento" class="img-fluid" style="max-height: 200px; display: none;">
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio por día</label>
                                <input type="text" id="precio" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="check_in" class="form-label">Fecha de Entrada</label>
                                <input type="date" name="check_in" id="check_in" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="check_out" class="form-label">Fecha de Salida</label>
                                <input type="date" name="check_out" id="check_out" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="num_dias" class="form-label">Número de días</label>
                                <input type="number" name="num_dias" id="num_dias" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="total_pagado" class="form-label">Total a pagar</label>
                                <input type="text" name="total_pagado" id="total_pagado" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="num_reservacion" class="form-label">Número de Reservación</label>
                                <input type="text" name="num_reservacion" id="num_reservacion" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Crear Reservación</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Reservaciones existentes -->
            <div class="col-md-8">
                <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                    <div class="card-body">
                        <h5 class="card-title">Reservaciones Existentes</h5>
                        <table class="table table-striped mt-4">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Alojamiento</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservaciones as $reservacion): ?>
                                    <tr>
                                        <td><?= $reservacion['Num_Reservacion'] ?></td>
                                        <td><?= htmlspecialchars($reservacion['Usuario_Id']) ?></td>
                                        <td><?= htmlspecialchars($reservacion['Alojamiento_Id']) ?></td>
                                        <td><?= htmlspecialchars($reservacion['check_In']) ?></td>
                                        <td><?= htmlspecialchars($reservacion['check_Out']) ?></td>
                                        <td><?= htmlspecialchars($reservacion['Total_Pagado']) ?></td>
                                        <td>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="reservacion_id" value="<?= $reservacion['Num_Reservacion'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
