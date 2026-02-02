# Fase 1: Infraestructura y Entorno de Producción

## 1. Aprovisionamiento (RA3.b, RA3.g)

Dado que el proyecto "De Mi Casa a la Tuya" busca validar un modelo de negocio con un coste inicial nulo, se ha optado por un modelo de **Alojamiento Compartido (Shared Hosting)** en lugar de un servidor dedicado o VPS.

### Selección del Entorno
* **Proveedor:** InfinityFree.
* **Tipo de Servicio:** Hosting Compartido Gratuito (LAMP Stack).
* **Sistema Operativo:** Linux (v10+ / Debian-based). *Gestionado por el proveedor*.
* **Entorno de Desarrollo (Local):** Windows 11 con XAMPP (simulando el entorno de producción).

### Justificación de Costes y Recursos
[cite_start]Al utilizar InfinityFree, el coste de infraestructura es **0 €**, cumpliendo con la viabilidad económica del MVP[cite: 286, 361].

* **CPU/RAM:** Asignación dinámica compartida (suficiente para tráfico bajo/medio).
* **Almacenamiento:** 5 GB (Suficiente para código PHP, imágenes de platos y BBDD inicial).
* **Ancho de Banda:** Ilimitado.
* [cite_start]**Escalabilidad:** Si el tráfico aumenta, se contempla la migración a Hostinger (Plan Premium) o un VPS en AWS Lightsail[cite: 301].

---

## 2. Instalación del Stack (RA3.d)

En un entorno de hosting compartido, el software base ya viene preinstalado. La actividad consiste en la **configuración y selección de versiones** compatibles con nuestro desarrollo MVC.

### Stack Tecnológico (LAMP)
1.  **Servidor Web:** Apache HTTP Server.
    * *Configuración:* Se realiza mediante archivos `.htaccess` en la raíz del sitio para gestionar el enrutamiento amigable del modelo MVC (reescritura de URLs).
2.  **Runtime:** PHP 8.2.
    * *Configuración:* Seleccionado desde el "VistaPanel" (Control Panel) de InfinityFree. Se habilitan extensiones críticas: `pdo_mysql`, `gd` (imágenes), `mbstring`.
3.  **Base de Datos:** MySQL / MariaDB.
    * *Gestión:* A través de phpMyAdmin proporcionado por el hosting.
    * *Conexión:* Host remoto (no `localhost` en producción), usuario y contraseña específicos del panel.

### Despliegue
* **Método:** FTP/SFTP usando FileZilla.
* **Estructura de Directorios:** Se replica la estructura local MVC (`/app`, `/public`, `/views`, `/models`), asegurando que el `DocumentRoot` apunte a la carpeta `/public` o protegiendo los directorios sensibles.

---

## 3. Seguridad y Hardening (RA3.c, RA3.e)

Al no tener acceso `root` ni consola de comandos en InfinityFree, el *hardening* (endurecimiento) se realiza a nivel de aplicación y configuración del panel.

### Configuración de Firewall y Accesos
* **Firewall de Aplicación (.htaccess):**
    * Bloqueo de listado de directorios (`Options -Indexes`).
    * Protección de archivos sensibles de configuración (`.env`, `db.php`) para evitar acceso directo vía navegador.
* **Gestión de Usuarios:**
    * **FTP:** Contraseña generada aleatoriamente y rotada periódicamente.
    * **Base de Datos:** Usuario con permisos restringidos únicamente a la base de datos del proyecto (principio de mínimo privilegio), distinto al usuario del panel.

### Comunicaciones Seguras
* **Certificado SSL/TLS:**
    * Se implementa **SSL Gratuito (Let's Encrypt / ZeroSSL)** a través de la herramienta "Free SSL Certificates" del panel de InfinityFree.
    * [cite_start]Se fuerza la redirección de HTTP a HTTPS mediante `.htaccess` para asegurar que todo el tráfico de datos (reservas, logins) viaje cifrado[cite: 261].
    
    ```apache
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    ```

* **Acceso Remoto:** Se utiliza conexión **FTPS (FTP sobre SSL)** para la subida de archivos, evitando el envío de credenciales en texto plano.