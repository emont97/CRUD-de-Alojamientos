<style> .custom-navbar {
    background-color:rgb(224, 224, 224); /* Gris claro */
}

.navbar-nav {
    display: flex;
    justify-content: center; /* Centra los enlaces horizontalmente */
    width: 100%; /* Asegura que ocupe todo el ancho disponible */
}

.nav-link {
    padding: 10px 15px; /* Ajusta el espacio entre los enlaces */
}
</style>


<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container">
    <a class="navbar-brand" href="#">CRUD Alojamientos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
