# 👨‍🍳 De Mi Casa a la Tuya - Web App de Chef Privado

> 🚧 **ESTADO DEL PROYECTO: EN DESARROLLO (WORK IN PROGRESS)** 🚧
> 
> *Este proyecto se encuentra actualmente en fase de desarrollo activo. Es mi Trabajo de Fin de Grado (TFG) para el Ciclo Superior de Desarrollo de Aplicaciones Web (DAW) y constituye una pieza central de mi portafolio técnico para la búsqueda de prácticas en empresa (FCT).*

---

## 📖 Descripción General

**De Mi Casa a la Tuya** es una aplicación web B2C diseñada para gestionar los servicios de un chef privado. Permite a los clientes explorar una carta de menús de alta cocina y reservar experiencias gastronómicas en su propio domicilio, mientras que proporciona al administrador (el Chef) un panel de control integral para gestionar su negocio.

El proyecto destaca por estar construido sobre una **arquitectura MVC (Modelo-Vista-Controlador) nativa y personalizada**, creada desde cero sin el uso de frameworks pesados (como Laravel o Symfony). Esto demuestra un conocimiento profundo del funcionamiento interno de PHP, el enrutamiento y la interacción con bases de datos.

## 🚀 Tecnologías y Herramientas

* **Backend:** PHP 8.x (Arquitectura MVC propia, POO).
* **Base de Datos:** MySQL / MariaDB (Acceso mediante `PDO`).
* **Frontend:** HTML5, CSS3, JavaScript (ES6+).
* **Framework UI:** Bootstrap 5 (Responsive Design).
* **Control de Versiones:** Git / GitHub.
* **Infraestructura:** Entorno local (XAMPP/MAMP) y despliegue proyectado en Hosting Compartido (Posible cambio a instancia de AWS).

## 🛡️ Características Destacadas (Implementadas y en curso)

### 1. Sistema de Seguridad Robusto
* **Autenticación:** Encriptación de contraseñas mediante `password_hash()` nativo de PHP.
* **Autorización (ACL):** Sistema de control de acceso basado en roles (RBAC) con tabla pivote `roles_users`. Diferenciación estricta entre Administrador (Chef) y Clientes registrados.
* **Protección CSRF:** Implementación de tokens CSRF (`hash_equals`) en todos los formularios que mutan estado para prevenir ataques de falsificación de peticiones.
* **Prevención SQL Injection:** Uso exclusivo de sentencias preparadas (`prepare` y `bindParam`) con PDO.
* **Sanitización de Datos:** Uso de `filter_var()` para validar y limpiar el *input* de los usuarios.

### 2. Panel Privado (Chef / Admin)
* **CRUD de Menús:** Alta, baja, modificación y listado del catálogo gastronómico.
* **Gestión de Citas:** Panel de control para visualizar reservas y cambiar su estado (Pendiente, Confirmada, Cancelada, Finalizada).
* **Gestión de Usuarios:** Visualización y administración de los clientes registrados.

### 3. Área Pública y de Clientes
* **Catálogo Dinámico:** Visualización de la carta alimentada directamente desde la base de datos.
* **Sistema de Reservas:** Formulario de solicitud de fechas, horas y menús asociado a la sesión del usuario.
* **Registro/Login:** Sistema de autoregistro para nuevos clientes.

## ⚙️ Estructura del Proyecto (Micro-Framework MVC)

El proyecto sigue una estricta separación de responsabilidades:
* `/controllers`: Lógica de negocio y validación de permisos.
* `/models`: Lógica de acceso a datos y sentencias SQL.
* `/views`: Interfaces de usuario (HTML/Bootstrap) y *partials* reutilizables.
* `/libs`: Núcleo del framework (Enrutador `App`, clases base `Controller`, `Model`, `View` y `Database`).
* `/config`: Variables globales, conexión PDO y gestión de privilegios.

## 💻 Instalación en Entorno Local

Si deseas probar la aplicación en tu máquina local:

1.  Clona este repositorio:
    ```bash
    git clone [https://github.com/yoyi-88/Proyecto_Fin_De_Grado.git](https://github.com/yoyi-88/Proyecto_Fin_De_Grado.git)
    ```
2.  Importa la base de datos:
    * Crea una base de datos llamada `dmcalt` en tu SGBD (ej. phpMyAdmin).
    * Importa los archivos dmcalt y menu `.sql` ubicado en la carpeta `/bd`.
3.  Configura el entorno:
    * Edita el archivo `config/config.php` con tus credenciales locales (DB_USER, DB_PASS, URL base).
4.  Inicia tu servidor Apache (XAMPP) y accede a la URL configurada.

---
*Desarrollado por **Yoël Gómez Benítez** - Estudiante de 2º DAW.*
