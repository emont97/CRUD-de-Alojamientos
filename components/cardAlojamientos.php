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

<div class="container">
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