# CRUD-de-Alojamientos

Este proyecto consiste en desarrollar una aplicación web que permita gestionar alojamientos mediante operaciones CRUD (Crear, Leer, Actualizar y Eliminar) en una base de datos MySQL utilizando PHP. Incluye funcionalidades como: gestión personalizada desde una vista de cuenta y administrador.

## Requisitos:

1. Landing Page de Alojamientos:

La aplicación debe tener una página principal (landing page) que muestre los alojamientos. Estos alojamientos deben ser precargados desde la base de datos y se mostrarán de manera atractiva en la página principal.

2. Crear una Cuenta e Iniciar Sesión:

Debe existir la funcionalidad para que los usuarios puedan crear una cuenta en la aplicación y luego iniciar sesión con sus credenciales.

3. Vista de Cuenta de Usuario:

Una vez que el usuario haya iniciado sesión, debe ser redirigido a una vista de cuenta de usuario. En esta vista, el usuario puede seleccionar libremente alojamientos, los cuales se reflejarán en su cuenta de usuario.

4. Función de Eliminar Alojamientos:

Los alojamientos seleccionados por el usuario deben tener la función de ser eliminados, en caso de que el usuario decida retirarlos de su cuenta.

5. Usuario Administrador:

Se debe crear un usuario de tipo administrador, el cual tendrá privilegios especiales. El administrador solo podrá agregar alojamientos a la base de datos, no tendrá la capacidad de eliminarlos.

## Estructura de las carpetas

CRUD-DE-Alojamientos  
│  
├─/assets # Archivos estáticos (imágenes, CSS, JS personalizados)  
│ ├─/css # Archivos CSS personalizados  
│ │ └── styles.css # Estilos globales  
│ ├──/img  
│ └──/js  
│  
├─/components # Componentes reutilizables del frontend  
│ ├── navbar.php # Barra de navegación  
│ ├── cardAlojamiento.php # Tarjeta para mostrar los alojamientos de la base de datos  
│ └── footer.php # Pie de página  
│  
├─/includes # Archivos PHP para lógica compartida (funciones, configuración)  
│ └── config.php
│  
├─/pages # Páginas específicas del sitio  
│ ├── index.php  
│ ├── alojamiento.php
│ └── regiatroUsuarios.php
│  
├─/templates # Plantillas generales (estructura común de la página)  
│ ├── footer.php # Inicia con la etiqueta </body cerrada> e incluye el componente footer y bootstrap link  
│ ├── header.php # Termina con la etiqueta <body abierta> e incluye Navbar, bootstrap, y header de HTML
│ └── layout.php  
│  
└── index.php # Landing Page
