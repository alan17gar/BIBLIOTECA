# Biblioteca App - Sistema de Gestión de Biblioteca

`biblioteca-app` es una aplicación web completa para la gestión de una biblioteca, diseñada para funcionar de forma totalmente local en un entorno XAMPP, WAMP o LAMP. La aplicación está construida con un frontend de HTML5, CSS3 y JavaScript puro, y un backend en PHP 8.x siguiendo la arquitectura MVC.

## Funcionalidades Principales

### Roles de Usuario
- **Administrador (Bibliotecario):** Gestión completa de libros (CRUD), inventario, préstamos, usuarios y tareas. Acceso a un dashboard con estadísticas clave.
- **Estudiante:** Búsqueda de libros, solicitud de préstamos, visualización de historial, descarga de PDFs, realización de tareas y acceso a juegos interactivos.

### Módulos Clave
- **Autenticación:** Sistema de login seguro con gestión de sesiones y redirección por roles.
- **Gestión de Libros:** CRUD completo para libros, incluyendo detalles como sinopsis, portada, ubicación y cantidad.
- **Gestión de Préstamos:** Registro de préstamos, devoluciones, renovaciones y cálculo de multas (funcionalidad a extender).
- **Gestión de Tareas:** Asignación de actividades a estudiantes, como reseñas de libros.
- **Juegos Interactivos:** Módulo con un "Quiz de Comprensión Lectora" y un "Juego de Memoria con Portadas" para fomentar la interacción.

## Requisitos Técnicos

- Servidor web local: **XAMPP**, **WAMP** o **LAMP**.
- **PHP 8.0** o superior.
- **MySQL** (MariaDB) con soporte para PDO.
- Navegador web moderno (Chrome, Firefox, Edge).

---

## Guía de Instalación y Puesta en Marcha

Sigue estos pasos para ejecutar el proyecto en tu entorno local.

### 1. Descargar y Configurar el Servidor

- Si aún no lo tienes, descarga e instala **XAMPP** desde [su sitio web oficial](https://www.apachefriends.org/index.html).
- Inicia los módulos de **Apache** y **MySQL** desde el panel de control de XAMPP.

### 2. Copiar los Archivos del Proyecto

- Clona o descarga este repositorio.
- Copia la carpeta completa `biblioteca-app` dentro del directorio `htdocs` de tu instalación de XAMPP (normalmente `C:\xampp\htdocs\` en Windows).

### 3. Crear la Base de Datos

- Abre tu navegador y ve a `http://localhost/phpmyadmin`.
- Crea una nueva base de datos llamada `biblioteca_app`. Asegúrate de usar el cotejamiento `utf8mb4_unicode_ci`.
- Selecciona la base de datos recién creada y ve a la pestaña **Importar**.
- Haz clic en "Seleccionar archivo" y busca el archivo `biblioteca.sql` que se encuentra en la carpeta `sql/` del proyecto.
- Haz clic en **Importar** para ejecutar el script. Esto creará la estructura de todas las tablas.

### 4. Poblar la Base de Datos con Datos de Prueba

Para garantizar que las credenciales de los usuarios de prueba funcionen correctamente, debes ejecutar un script "seeder" que creará las cuentas con contraseñas hasheadas correctamente.

- Abre tu navegador y visita la siguiente URL:
  **`http://localhost/biblioteca-app/sql/seed.php`**
- Deberías ver un mensaje de éxito indicando que los usuarios (`admin`, `estudiante1`, `estudiante2`) han sido creados.

### 5. Configurar la Conexión a la Base de Datos

- El archivo de configuración `config/db.php` ya viene preconfigurado con las credenciales por defecto de XAMPP:
  - **Host:** `localhost`
  - **Usuario:** `root`
  - **Contraseña:** (vacía)
  - **Base de datos:** `biblioteca_app`
- Si tu configuración de MySQL es diferente, ajusta estos valores en el archivo `config/db.php`.

### 5. Acceder a la Aplicación

- ¡Listo! Abre tu navegador y visita la siguiente URL:
  **`http://localhost/biblioteca-app/`**
- Deberías ver la página de inicio de sesión.

---

## Cómo Usar la Aplicación

### Credenciales de Prueba

Puedes usar las siguientes cuentas para acceder al sistema:

- **Rol Administrador:**
  - **Usuario:** `admin`
  - **Contraseña:** `admin123`

- **Rol Estudiante:**
  - **Usuario:** `estudiante1`
  - **Contraseña:** `estudiante123`

### Flujo de Uso

1. **Inicia Sesión:** Usa las credenciales de prueba para entrar como admin o estudiante.
2. **Navega por el Panel:**
   - El **admin** puede añadir/editar libros, gestionar usuarios, ver todos los préstamos y asignar tareas.
   - El **estudiante** puede buscar libros en el catálogo, solicitar préstamos, ver su historial, completar tareas y jugar.
3. **Prueba las Funcionalidades:**
   - Como **admin**, crea un nuevo libro.
   - Como **estudiante**, busca ese libro y solicita un préstamo.
   - Como **admin**, ve al panel de préstamos y marca el libro como devuelto.
   - Como **estudiante**, visita la sección de juegos y prueba el quiz o el juego de memoria.

---

## Estructura del Proyecto

El proyecto sigue una arquitectura MVC (Modelo-Vista-Controlador) simple:

- `index.php`: Punto de entrada único y enrutador principal.
- `.htaccess`: Reescribe las URLs para que sean amigables.
- `config/`: Contiene la configuración de la base de datos.
- `controllers/`: Contiene la lógica de la aplicación.
- `models/`: Contiene las clases que interactúan con la base de datos.
- `views/`: Contiene los archivos de presentación (HTML/PHP).
- `public/`: Contiene los assets públicos (CSS, JS, imágenes).
- `sql/`: Contiene el script de la base de datos.
- `uploads/`: Directorio para las portadas y PDFs subidos (se debe crear dentro de `public/`).
