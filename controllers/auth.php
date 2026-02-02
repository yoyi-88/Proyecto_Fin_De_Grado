<?php

class Auth extends Controller
{

    // Crea una instancia del controlador auth
    // Llama al constructor de la clase padre Controller
    // Crea una vista para el controlador auth
    // Carga el modelo si existe auth.model.php
    function __construct()
    {

        parent::__construct();
    }

    /*
        Método:  login()
        Descripción: Muestra el formulario de login 
        URL asociada: auth/login
        Vista asociada: views/auth/login/index.php
        Modelo asociado: models/auth.model.php
           
    */

    function login()
    {

        // iniciar o continuar sesión
        sec_session_start();

        // Crear un token CSRF para los formularios
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        

        // Inicializo los campos del formulario
        $this->view->email = null;
        $this->view->pass = null;

        // Comprobar si existe alguna notificación o mensaje
        if (isset($_SESSION['notify'])) {
            $this->view->notify = $_SESSION['notify'];
            unset($_SESSION['notify']);
        }

        // Verificar si existe algún error
        if (isset($_SESSION['errors'])) {

            // detalles del error
            $this->view->errors = $_SESSION['errors'];
            unset($_SESSION['errors']);

            // Creo la propiedad error
            $this->view->error = "Error de autenticación, revise el formulario";

            // Retroalimento los detalles del formulario
            $this->view->email = $_SESSION['email'];
            $this->view->pass = $_SESSION['pass'];

            unset($_SESSION['email']);
            unset($_SESSION['pass']);
        }

        // Obtengo los datos del  modelo para mostrar en la vista

        // Creo la propiedad  title para la vista
        $this->view->title = "Autenticación de Usuarios";

        // Llama a la vista para renderizar la página
        $this->view->render('auth/login/index');
    }

    /*
        Método:  register()
        Descripción: Muestra el formulario de registro 
        URL asociada: auth/register
        Vista asociada: views/auth/register/index.php
        Modelo asociado: models/auth.model.php 
    */

    /*
            Método: validate()
            Descripción: Recibe los datos de autenticación para validarla: emial, pass
                - Validar usuario mediante email y pass
                - En caso de error de valiación. Restroalimenta el formulario y muestra errores
                - En caso de validación. Inicia sesión segura y redirecciona a la página de libro

            url asociada: auth/validate_login

            POST:
                - email
                - pass
                - csrf_token
    */
    public function validate()
    {

        // inicio o continúo sesión
        sec_session_start();

        // Verificar el token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Recogemos los datos del formulario saneados
        // Prevenir ataques XSS
        $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);
        $pass = filter_var($_POST['pass'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

        // Validación de los datos del formulario

        // Creo un array asociativo para almacenar los posibles errores del formulario
        $errors    = [];
        $validate  = true;

        // Vallidación email
        // Reglas de validación: obligatorio, formato email y existir en la tabla user
        if (empty($email)) {
            $errors['email'] = "El campo email es obligatorio";
            $validate = false;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "El formato email no es correcto";
            $validate = false;
        }

       
        // Solo voy a validar el email y el password si el email es correcto
        if ($validate) {

            // Obtener los detalles del usuario a partir del email
            // Obtener un objeto de la clase user con las propiedades nombre, email, pass
            $user = $this->model->get_user_email($email);

            // Voy a validar en el mismo bloque el email y el password
            if (!$user) {
                $errors['email'] = 'Email  no ha sido registrado';

                // Verificación del password
                // Reglas de validación: obligatorio, longitud mínima sea 7 caracteres, coincidente con
                // password del usuario
            } else if (empty($pass)) {
                $errors['pass'] = "El password no ha sido introducido";
            } else if (strlen($pass) < 7) {
                $errors['pass'] = "Longitud mínima 7 caracteres";
            } else if (!password_verify($pass, $user->password)) {
                $errors['pass'] = "El password no es correcto";
            }
        }

        


        // Fin Validación

        // Si hay errores vuelvo al formulario de autenticación
        if (!empty($errors)) {

            // Almaceno los errores en la sesión
            $_SESSION['errors'] = $errors;
            
            // Almaceno email
            $_SESSION['email'] = $email;

            // Almaceno el password
            $_SESSION['pass'] = $pass;

            // Redirecciono al controlador auth/login
            header('Location: ' . URL . 'auth/login');
            exit();
        }

        // Autentiación correcta
        // - Almaceno los datos del usuario en la sesión
        // - Redirecciono al panel de control de libros


        // Almaceno los datos del usuario en la sesión
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;

        // Obtengo los datos del rol del usuario
        $_SESSION['role_id'] = $this->model->get_id_role_user($user->id);
        $_SESSION['role_name'] = $this->model->get_name_role_user($_SESSION['role_id']);

         // Generar mensaje de inicio de sesión
        $_SESSION['notify'] = "Usuario ". $user->name. " ha iniciado sesión.";

        // redirección al panel de control
        header("location:". URL. "libro");
        exit();
    }

    function register() {
        sec_session_start();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Inicializar valores para la vista (por si hay errores y volvemos)
        $this->view->name = $_SESSION['name'] ?? null;
        $this->view->email = $_SESSION['email'] ?? null;
        $this->view->errors = $_SESSION['errors'] ?? [];
        
        unset($_SESSION['errors'], $_SESSION['name'], $_SESSION['email']);

        $this->view->title = "Registro de Nuevo Usuario";
        $this->view->render('auth/register/index');
    }

    public function validate_register(){
        // Inicio o continúo sesión
        sec_session_start();

        // Verificar el token CSRF para evitar ataques cross-site
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Recogida y saneamiento de datos
        $name  = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $pass  = $_POST['pass'] ?? '';
        $pass_confirm = $_POST['pass_confirm'] ?? '';

        // Validación de los datos
        $errors = [];

        // Validar Nombre
        if (empty($name)) {
            $errors['name'] = "El nombre es obligatorio";
        }

        // Validar Email
        if (empty($email)) {
            $errors['email'] = "El email es obligatorio";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Formato de email no válido";
        } else if ($this->model->validate_exists_email($email)) {
            $errors['email'] = "Este email ya está registrado";
        }

        // Validar Password
        if (empty($pass)) {
            $errors['pass'] = "El password es obligatorio";
        } else if (strlen($pass) < 7) {
            $errors['pass'] = "La contraseña debe tener al menos 7 caracteres";
        } else if ($pass !== $pass_confirm) {
            $errors['pass_confirm'] = "Las contraseñas no coinciden";
        }

        // 4. Manejo de errores de validación
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['name']   = $name;
            $_SESSION['email']  = $email;

            header('Location: ' . URL . 'auth/register');
            exit();
        }

        // 5. Registro exitoso
        // Encriptamos la contraseña antes de guardarla
        $password_hashed = password_hash($pass, PASSWORD_BCRYPT);

        // Llamamos al modelo para insertar (el método create que hicimos antes)
        if ($this->model->create($name, $email, $password_hashed)) {
            $_SESSION['notify'] = "Registro completado con éxito. Ya puedes iniciar sesión.";
            header('Location: ' . URL . 'auth/login');
            exit();
        } else {
            // Error inesperado en la base de datos
            header('Location: ' . URL . 'errores');
            exit();
        }
    }

    /*
        Método: logout()
        Descripción: Cierra la sesión de forma segura
    */
    public function logout() {
        sec_session_start();
        // Vaciar el array de sesión
        $_SESSION = [];
        // Destruir la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        // Destruir la sesión
        session_destroy();
        // Redirigir al login
        header("location:" . URL . "auth/login");
        exit();
    }

    // Métodos para el perfil 
    public function show_profile() {
        sec_session_start();
        if (!isset($_SESSION['user_id'])) header("location:" . URL . "auth/login");
        
        $this->view->title = "Perfil de Usuario";
        $this->view->user = $this->model->get_user_id($_SESSION['user_id']);
        $this->view->render('perfil/show/index');
    }

    // Métodos de Edición de Perfil 

    public function edit_profile() {
        sec_session_start();
        if (!isset($_SESSION['user_id'])) header("location:" . URL . "auth/login");

        $this->view->title = "Editar Perfil";
        $this->view->user = $this->model->get_user_id($_SESSION['user_id']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Nuevo token para el formulario
        
        $this->view->errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $this->view->render('perfil/edit/index');
    }

    public function validate_profile() {
        sec_session_start();
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) $this->handleError();

        $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $id = $_SESSION['user_id'];

        $errors = [];
        if (empty($name)) $errors['name'] = "El nombre es obligatorio";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email no válido";
        } elseif ($this->model->validate_unique_email($id, $email)) {
            $errors['email'] = "Este email ya está en uso por otro usuario";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("location:" . URL . "auth/edit_profile");
            exit();
        }

        if ($this->model->update_profile($id, $name, $email)) {
            $_SESSION['user_name'] = $name; // Actualizar nombre en la barra de menú
            $_SESSION['notify'] = "Perfil actualizado con éxito";
            header("location:" . URL . "auth/show_profile");
        }
    }

    // Métodos de Cambio de Contraseña

    public function change_password() {
        sec_session_start();
        if (!isset($_SESSION['user_id'])) header("location:" . URL . "auth/login");

        $this->view->title = "Cambiar Contraseña";
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->view->errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $this->view->render('perfil/password/index');
    }

    public function validate_password() {
        sec_session_start();
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) $this->handleError();

        $pass_actual = $_POST['pass_actual'] ?? '';
        $pass_nuevo = $_POST['pass_nuevo'] ?? '';
        $pass_confirm = $_POST['pass_confirm'] ?? '';
        $id = $_SESSION['user_id'];

        $errors = [];
        $user_db = $this->model->get_password_by_id($id);

        // Validar contraseña actual
        if (!password_verify($pass_actual, $user_db->password)) {
            $errors['pass_actual'] = "La contraseña actual no es correcta";
        }
        // Validar nueva contraseña
        if (strlen($pass_nuevo) < 7) {
            $errors['pass_nuevo'] = "Mínimo 7 caracteres";
        } elseif ($pass_nuevo !== $pass_confirm) {
            $errors['pass_confirm'] = "Las contraseñas no coinciden";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("location:" . URL . "auth/change_password");
            exit();
        }

        $hashed_pass = password_hash($pass_nuevo, PASSWORD_BCRYPT);
        if ($this->model->update_password($id, $hashed_pass)) {
            $_SESSION['notify'] = "Contraseña cambiada correctamente";
            header("location:" . URL . "auth/show_profile");
        }
    }

    
    


    private function handleError()
    {
        // Incluir y cargar el controlador de errores
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';

        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $mensaje = "Error de validación de seguridad del formulario. Intenta acceder de nuevo desde la página principal";
            $controller = new Errores('403', 'Mensaje de Error: ', $mensaje);
        } else {
            // Fallback en caso de que el controlador de errores no exista
            echo "Error crítico: " . "No se pudo cargar el controlador de errores.";
            exit();
        }
    }
}
