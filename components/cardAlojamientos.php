<?php
// Incluir la conexión a la base de datos
require_once __DIR__ . '/../includes/db.php';

// Verificar si se envió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos los datos del formulario
    $nombre = $_POST['nombre'];
    $ubicacion = $_POST['ubicacion'];
    $huespedes = $_POST['huespedes'];
    $habitaciones = $_POST['habitaciones'];
    $camas = $_POST['camas'];
    $banos = $_POST['banos'];
    $precio = $_POST['precio'];

    // URL por defecto para la imagen
    $imagenRuta = 'https://firebasestorage.googleapis.com/v0/b/cafe-34972.appspot.com/o/Hoteles-asia.jpg?alt=media&token=93ecf57d-cd94-4ec9-a86e-03868a1dcd19';

    // Eliminar el alojamiento
    if (isset($_POST['Alojamiento_Id']) && isset($_POST['eliminarAlojamiento'])) {
        $alojamientoId = $_POST['Alojamiento_Id'];

        // Eliminar el alojamiento de la base de datos
        $stmt = $pdo->prepare("DELETE FROM alojamientos WHERE Alojamiento_Id = :Alojamiento_Id");
        $stmt->execute([':Alojamiento_Id' => $alojamientoId]);

        exit;
    }

    // Editar alojamiento existente
    if (isset($_POST['editarAlojamiento'])) {
        $alojamientoId = $_POST['Alojamiento_Id'];
        $stmt = $pdo->prepare("UPDATE alojamientos SET nombre = :nombre, ubicacion = :ubicacion, Huespedes = :huespedes, Habitaciones = :habitaciones, camas = :camas, Baños = :banos, precio = :precio WHERE Alojamiento_Id = :Alojamiento_Id");
        $stmt->execute([
            ':nombre' => $nombre,
            ':ubicacion' => $ubicacion,
            ':huespedes' => $huespedes,
            ':habitaciones' => $habitaciones,
            ':camas' => $camas,
            ':banos' => $banos,
            ':precio' => $precio,
            ':Alojamiento_Id' => $alojamientoId
        ]);
        exit;
    }

    // Insertamos el alojamiento con la URL de imagen por defecto
    $stmt = $pdo->prepare("INSERT INTO alojamientos (nombre, ubicacion, Huespedes, Habitaciones, camas, Baños, precio, Cover_foto) 
     VALUES (:nombre, :ubicacion, :huespedes, :habitaciones, :camas, :banos, :precio, :imagenRuta)");
    $stmt->execute([
        ':nombre' => $nombre,
        ':ubicacion' => $ubicacion,
        ':huespedes' => $huespedes,
        ':habitaciones' => $habitaciones,
        ':camas' => $camas,
        ':banos' => $banos,
        ':precio' => $precio,
        ':imagenRuta' => $imagenRuta
    ]);
}

// Recuperamos los alojamientos de la base de datos
$stmt = $pdo->query("SELECT * FROM alojamientos");
$alojamientos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Alojamiento</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <!-- Botón para abrir el formulario de SweetAlert -->
    <button id="agregarAlojamiento" class="btn btn-success">Agregar Alojamiento</button>

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
                        <button class="btn btn-danger eliminar-btn" data-id="<?php echo $alojamiento['Alojamiento_Id']; ?>">Eliminar</button>
                        <button class="btn btn-primary editar-btn" data-id="<?php echo $alojamiento['Alojamiento_Id']; ?>" data-nombre="<?php echo htmlspecialchars($alojamiento['nombre']); ?>" data-ubicacion="<?php echo htmlspecialchars($alojamiento['ubicacion']); ?>" data-huespedes="<?php echo htmlspecialchars($alojamiento['Huespedes']); ?>" data-habitaciones="<?php echo htmlspecialchars($alojamiento['Habitaciones']); ?>" data-camas="<?php echo htmlspecialchars($alojamiento['camas']); ?>" data-banos="<?php echo htmlspecialchars($alojamiento['Baños']); ?>" data-precio="<?php echo htmlspecialchars($alojamiento['precio']); ?>">Editar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Botones de eliminación
    document.querySelectorAll('.eliminar-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const alojamientoId = this.getAttribute('data-id');

            // Confirmación antes de eliminar
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡Este alojamiento será eliminado!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envia la solicitud de eliminación al mismo archivo (index.php)
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'Alojamiento_Id': alojamientoId,
                            'eliminarAlojamiento': true
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire('Eliminado', 'El alojamiento ha sido eliminado.', 'success').then(() => {
                                location.reload(); // Recargamos la página para reflejar los cambios
                            });
                        } else {
                            Swal.fire('Error', 'Hubo un problema al eliminar el alojamiento.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                    });
                }
            });
        });
    });

    // Botón de edición
    document.querySelectorAll('.editar-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const alojamientoId = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const ubicacion = this.getAttribute('data-ubicacion');
            const huespedes = this.getAttribute('data-huespedes');
            const habitaciones = this.getAttribute('data-habitaciones');
            const camas = this.getAttribute('data-camas');
            const banos = this.getAttribute('data-banos');
            const precio = this.getAttribute('data-precio');

            Swal.fire({
                title: 'Editar Alojamiento',
                html: `
                    <form id="formAlojamientoEdit" class="container" style="font-size: 0.9rem;">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="nombre" class="form-label font-weight-bold">Nombre del Alojamiento</label>
                                <input type="text" class="form-control form-control-sm" id="nombre" value="${nombre}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="ubicacion" class="form-label font-weight-bold">Ubicación</label>
                                <input type="text" class="form-control form-control-sm" id="ubicacion" value="${ubicacion}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="huespedes" class="form-label font-weight-bold">Huéspedes</label>
                                <input type="number" class="form-control form-control-sm" id="huespedes" value="${huespedes}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="habitaciones" class="form-label font-weight-bold">Habitaciones</label>
                                <input type="number" class="form-control form-control-sm" id="habitaciones" value="${habitaciones}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="camas" class="form-label font-weight-bold">Camas</label>
                                <input type="number" class="form-control form-control-sm" id="camas" value="${camas}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="banos" class="form-label font-weight-bold">Baños</label>
                                <input type="number" class="form-control form-control-sm" id="banos" value="${banos}" required>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="precio" class="form-label font-weight-bold">Precio</label>
                                <input type="number" class="form-control form-control-sm" id="precio" value="${precio}" required>
                            </div>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar Cambios',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const nombre = document.getElementById('nombre').value;
                    const ubicacion = document.getElementById('ubicacion').value;
                    const huespedes = document.getElementById('huespedes').value;
                    const habitaciones = document.getElementById('habitaciones').value;
                    const camas = document.getElementById('camas').value;
                    const banos = document.getElementById('banos').value;
                    const precio = document.getElementById('precio').value;

                    return {
                        alojamientoId,
                        nombre,
                        ubicacion,
                        huespedes,
                        habitaciones,
                        camas,
                        banos,
                        precio
                    };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const formData = result.value;
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'Alojamiento_Id': formData.alojamientoId,
                            'nombre': formData.nombre,
                            'ubicacion': formData.ubicacion,
                            'huespedes': formData.huespedes,
                            'habitaciones': formData.habitaciones,
                            'camas': formData.camas,
                            'banos': formData.banos,
                            'precio': formData.precio,
                            'editarAlojamiento': true
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire('¡Alojamiento actualizado!', '', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'Hubo un problema al actualizar el alojamiento.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                    });
                }
            });
        });
    });

    document.getElementById('agregarAlojamiento').addEventListener('click', function () {
        Swal.fire({
            title: 'Agregar Alojamiento',
            html: `
                <form id="formAlojamiento" class="container" style="font-size: 0.9rem;">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="nombre" class="form-label font-weight-bold">Nombre del Alojamiento</label>
                            <input type="text" class="form-control form-control-sm" id="nombre" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="ubicacion" class="form-label font-weight-bold">Ubicación</label>
                            <input type="text" class="form-control form-control-sm" id="ubicacion" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="huespedes" class="form-label font-weight-bold">Huéspedes</label>
                            <input type="number" class="form-control form-control-sm" id="huespedes" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="habitaciones" class="form-label font-weight-bold">Habitaciones</label>
                            <input type="number" class="form-control form-control-sm" id="habitaciones" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="camas" class="form-label font-weight-bold">Camas</label>
                            <input type="number" class="form-control form-control-sm" id="camas" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="banos" class="form-label font-weight-bold">Baños</label>
                            <input type="number" class="form-control form-control-sm" id="banos" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="precio" class="form-label font-weight-bold">Precio</label>
                            <input type="number" class="form-control form-control-sm" id="precio" required>
                        </div>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar Alojamiento',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const nombre = document.getElementById('nombre').value;
                const ubicacion = document.getElementById('ubicacion').value;
                const huespedes = document.getElementById('huespedes').value;
                const habitaciones = document.getElementById('habitaciones').value;
                const camas = document.getElementById('camas').value;
                const banos = document.getElementById('banos').value;
                const precio = document.getElementById('precio').value;

                if (!nombre || !ubicacion || !huespedes || !habitaciones || !camas || !banos || !precio) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                }

                return {
                    nombre,
                    ubicacion,
                    huespedes,
                    habitaciones,
                    camas,
                    banos,
                    precio
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Realiza la petición para guardar los datos en la base de datos
                const formData = new FormData();
                formData.append('nombre', result.value.nombre);
                formData.append('ubicacion', result.value.ubicacion);
                formData.append('huespedes', result.value.huespedes);
                formData.append('habitaciones', result.value.habitaciones);
                formData.append('camas', result.value.camas);
                formData.append('banos', result.value.banos);
                formData.append('precio', result.value.precio);
                formData.append('imagenRuta', 'https://firebasestorage.googleapis.com/v0/b/cafe-34972.appspot.com/o/Hoteles-asia.jpg?alt=media&token=93ecf57d-cd94-4ec9-a86e-03868a1dcd19');

                fetch('', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        Swal.fire('¡Alojamiento agregado!', '', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', 'Hubo un problema al agregar el alojamiento.', 'error');
                    }
                }).catch(error => {
                    Swal.fire('Error', 'Hubo un problema al agregar el alojamiento.', 'error');
                });
            }
        });
    });
</script>

</body>
</html>
