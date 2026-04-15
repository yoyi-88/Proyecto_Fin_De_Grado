# 🍽️ De Mi Casa a la Tuya

**Plataforma Premium de Alta Cocina a Domicilio** | *Proyecto de Fin de Grado*

![Estado](https://img.shields.io/badge/Estado-Completado-success)
<img src="https://img.shields.io/badge/PHP-8.2.12-777BB4?logo=php&logoColor=white" alt="PHP Badge">
![MySQL](https://img.shields.io/badge/MySQL-PDO-4479A1?logo=mysql&logoColor=white)
![Seguridad](https://img.shields.io/badge/Seguridad-Avanzada-red)

"De Mi Casa a la Tuya" es una aplicación web desarrollada bajo la arquitectura **MVC (Modelo-Vista-Controlador)** en PHP puro. Diseñada para un servicio exclusivo de chef privado, permite a los clientes registrarse, explorar la carta, gestionar sus reservas y administrar su perfil bajo un entorno estricto de seguridad, mientras que proporciona al Chef un panel de control total sobre el negocio.

---

## 🛠️ Tecnologías y Herramientas Utilizadas

**Backend & Arquitectura:**
* **PHP 8.2.12:** Lenguaje principal del servidor estructurado en POO (Programación Orientada a Objetos).
* **Arquitectura MVC:** Patrón de diseño personalizado desde cero para separar la lógica de negocio, la interfaz y el acceso a datos.
* **MySQL / MariaDB:** Sistema de gestión de bases de datos relacionales. Acceso mediante **PDO** (PHP Data Objects).

**Frontend & Diseño:**
* **HTML5 & CSS3:** Estructura semántica y estilos personalizados.
* **Bootstrap 5:** Framework CSS para un diseño *responsive*, sistema de rejillas y componentes UI (tarjetas, modales, alertas).
* **Bootstrap Icons:** Sistema de iconografía vectorial (`bi bi-*`).
* **JavaScript (Vanilla):** Interactividad del lado del cliente.

**Librerías y Ecosistema:**
* **Composer:** Gestor de dependencias de PHP.
* **PHPMailer:** Librería para el envío seguro de correos electrónicos transaccionales vía SMTP.
* **Git & GitHub:** Control de versiones del código fuente.

---

## ✨ Funcionalidades del Sistema

La plataforma distingue las funcionalidades según el rol del usuario (Sistema de Control de Acceso basado en Roles - RBAC):

### 👤 Para el Cliente (Usuario Registrado)
* **Autenticación Avanzada:** Registro e inicio de sesión seguros.
* **Sistema "Recordarme":** Autologin mediante tokens persistentes guardados en base de datos y cookies de larga duración.
* **Recuperación de Credenciales:** Solicitud de nueva contraseña mediante enlace temporal (caduca en 1 hora) enviado al correo.
* **Gestión de Perfil:** Edición de datos personales, cambio de contraseña y eliminación de cuenta definitiva.
* **Gestión de Reservas:** Acceso directo a su historial de experiencias gastronómicas y reservas pendientes.
* **Notificaciones Premium:** Recepción de correos transaccionales en formato HTML con diseño corporativo (Registro, Edición, Baja, Recuperación).

### 👨‍🍳 Para el Chef (Administrador)
* **Dashboard Analítico:** Panel de control principal con métricas globales del negocio e indicadores de rendimiento.
* **Gestión de Usuarios (CRUD):** Visualización del listado de clientes registrados en la plataforma, con capacidad para buscar, editar, asignar roles o eliminar cuentas.
* **Administración de Reservas:** Control total sobre el calendario de citas, aprobando, modificando o cancelando reservas de clientes.
* **Gestión de la Carta:** Administración del menú de la plataforma, pudiendo actualizar la oferta gastronómica visible para el público.

---

## 🛡️ Seguridad y Buenas Prácticas

La plataforma ha sido construida priorizando la ciberseguridad, implementando las siguientes medidas en su núcleo:



* **Protección CSRF:** Generación y validación estricta de tokens únicos (Cross-Site Request Forgery) en cada formulario del sistema.
* **Prevención SQL Injection:** Uso exclusivo de consultas preparadas PDO (`bindParam`) en todos los modelos.
* **Prevención XSS:** Saneamiento exhaustivo de entradas (`filter_var` con `SANITIZE`) y salidas HTML (`htmlspecialchars`).
* **Cifrado Fuerte:** Algoritmo de hashing moderno para contraseñas (`password_hash` con `PASSWORD_DEFAULT`).
* **Integridad Relacional:** Uso de `ON DELETE CASCADE` y transacciones SQL (`beginTransaction`, `commit`, `rollBack`) para evitar datos huérfanos.
* **Sesiones Blindadas:** Gestión estricta del ID de sesión (`session_regenerate_id` tras login), configuración de cookies `HttpOnly` y soporte para `Secure`.

---

## 🚀 Instalación y Despliegue (Entorno Local)

### Prerrequisitos
* **Servidor Local:** XAMPP, Laragon, WAMP o similar (Apache/Nginx).
* **PHP:** Versión 8.0 o superior.
* **Base de datos:** MySQL / MariaDB.
* **Composer:** Instalado en el sistema.

### Pasos para la instalación

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/De-Mi-Casa-a-la-Tuya.git](https://github.com/tu-usuario/De-Mi-Casa-a-la-Tuya.git)

2. **Instalar las dependencias:**
Navega a la carpeta del proyecto en tu terminal y ejecuta:
    ```bash
    cd De-Mi-Casa-a-la-Tuya
    composer install
3. **Base de datos:**
- Crea una base de datos en tu servidor local llamada dmcalt.

- Importa el archivo de estructura proporcionado (ej. database/dmcalt.sql).

4. **Configuración del entorno:**
Configura las variables globales en tu directorio config/ (ej. config.php y smtp_gmail.php):

Ajustes de Base de Datos y URL (config.php):
```bash
define('URL', 'http://localhost/Proyecto_Fin_De_Grado/');
define('HOST', 'localhost');
define('DB', 'dmcalt');
define('USER', 'root');
define('PASSWORD', '');
define('CHARSET', 'utf8mb4');
```

Ajustes de Correo (smtp_gmail.php):
```bash
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'tu-correo@gmail.com');
define('SMTP_PASS', 'tu-password-de-aplicacion');
define('SMTP_PORT', 587);
```

### 🌐 Despliegue en Producción (HTTPS)
Para subir el proyecto a un hosting real con certificado SSL, realiza estos ajustes:

1. URL: Cambia la constante URL a https://tudominio.com/.

2. Cookies Seguras: En functions/session_seg.php y Auth.php, establece el parámetro $secure = true;.

3. Forzar HTTPS: Añade lo siguiente a tu archivo .htaccess:

```bash
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```
---

### 📁 Estructura de Carpetas

```bash
├── config/             # Archivos de configuración y constantes
├── controllers/        # Controladores (Lógica de la aplicación)
├── libs/               # Clases base del Core (MVC)
├── models/             # Modelos (Acceso a Datos)
├── public/             # CSS, JS, Imágenes y recursos estáticos
├── views/              # Vistas HTML y plantillas
├── vendor/             # Librerías externas (PHPMailer)
└── index.php           # Front Controller
```

### 👨‍💻 Autor
Yoël Gómez Benítez

- Proyecto de Fin de Grado

- [LinkedIn](https://www.linkedin.com/in/yo%C3%ABl-g%C3%B3mez-ben%C3%ADtez-832a0a346/?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3BucDU4oifToO1TcfJ%2Be2rdQ%3D%3D)

- Contacto: yoelgomezbenitez@gmail.com