<style>
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
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="CRUD-de-Alojamientos/../Pages/reservaciones.php">Reservaciones</a>
        </li>
    
      </ul>
    </div>
  </div>
</nav>
