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

        // Crear un token CSRF solo si no existe
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }


        // Inicializo los campos del formulario
        $this->view->email = null;
        $this->view->pass = null;

        // Comprobar si existe alguna notificación o mensaje
        if (isset($_SESSION['mensaje'])) {
            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
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
            Método: validate()
            Descripción: Recibe los datos de autenticación para validarla: emial, pass
                - Validar usuario mediante email y pass
                - En caso de error de valiación. Restroalimenta el formulario y muestra errores
                - En caso de validación. Inicia sesión segura y redirecciona a la página de De Mi Casa a la Tuya

            url asociada: auth/validate_login

            POST:
                - email
                - pass
                - csrf_token
    */
    public function validate_login()
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
        $errors = [];
        $validate = true;

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

        // Regenero la sessión para evitar fijación de sesión
        session_regenerate_id(true);

        // - Almaceno los datos del usuario en la sesión
        // - Redirecciono al panel de control de De Mi Casa a la Tuya

        // Almaceno los datos del usuario en la sesión
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;

        // Obtengo los datos del rol del usuario
        $_SESSION['role_id'] = $this->model->get_id_role_user($user->id);
        $_SESSION['role_name'] = $this->model->get_name_role_user($_SESSION['role_id']);

        // Generar mensaje de inicio de sesión
        $_SESSION['mensaje'] = "Usuario " . $user->name . " ha iniciado sesión.";

        // redirección al panel de control
        header("location:" . URL . "main");
        exit();
    }

    /*
        Método: register()
        Descripción: permite el autoregistro. 
        Muestra el formulario de registro con los campos:
            - Name
            - Email
            - Password
    */
    public function register()
    {

        // inicio o continuo la sesión
        sec_session_start();

        // Crear un token CSRF solo si no existe
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Inicializo los campos del formulario
        $this->view->name = null;
        $this->view->email = null;
        $this->view->password = null;

        // Comrpuebo si hay errores en la validación
        if (isset($_SESSION['errors'])) {

            // Creo la propiedad error en la vista
            $this->view->errors = $_SESSION['errors'];

            // Retroalimento los campos del  formulario
            $this->view->name = $_SESSION['name'];
            $this->view->email = $_SESSION['email'];
            $this->view->password = $_SESSION['password'];

            // Creo la propiedad mensaje de error
            $this->view->error = 'Revisar formulario, hay errores de validación';

            // Elimino la variable de sesión errors
            unset($_SESSION['errors']);

            // Elimino la variable de sesión De Mi Casa a la Tuya
            unset($_SESSION['name']);
            unset($_SESSION['email']);
            unset($_SESSION['password']);
        }

        // Creo la propiead título
        $this->view->title = "Registro de Usuarios";

        // Cargo la vista Registro de usuarios
        $this->view->render('auth/register/index');

    }

    /*
        Método validete_regiser()

        Permite:
            - Validar nuevo usuario
            - En caso de error de validación. Retroalimenta el formulario y muestra errores
            - En caso de validación. Añade usuario con perfil de registrado

        url asociada: /auth/validate_register()

        POST: detalles del nuevo usuario

            - name
            - email
            - password
            - password-confirm
    */
    public function validate_register()
    {

        // inicio o continúo sesión
        sec_session_start();

        // Verificar el token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Recogemos los detalles del formulario saneados
        // Prevenir ataques XSS
        $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
        $password_confirm = filter_var($_POST['password_confirm'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
        $enlace_login = "URL . 'auth/login";
        // Validación del formulario de registro

        // Creo un array para almacenar los errores
        $errors = [];

        // Validación name
        // Reglas: obligatorio, longitud mínima 5 caracteres, 
        // longitud máxima 20 caracteres, clave secundaria
        if (empty($name)) {
            $errors['name'] = 'El nombre es obligatorio';
        } else if ((strlen($name) < 5) || (strlen($name) > 20)) {
            $errors['name'] = 'La longitud del nombre debe estar entre 5 y 20 caracteres';
        } else if ($this->model->validate_exists_name($name)) {
            $errors['name'] = 'Name ya ha sido registrado';
        }

        // Validación email
        // Reglas: obligatorio, formato email, clave secundaria
        if (empty($email)) {
            $errors['email'] = 'El email es obligatorio';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El formato del email no es correcto';
        } else if ($this->model->validate_exists_email($email)) {
            $errors['email'] = 'El email ya ha sido registrado';
        }

        // Validación password
        // Reglas: obligatorio, longitud mínima 7 caracteres, campos coincidentes
        if (empty($password)) {
            $errors['password'] = 'La contraseña es obligatoria';
        } else if (strlen($password) < 7) {
            $errors['password'] = 'La contraseña debe tener al menos 7 caracteres';
        } else if (strcmp($password, $password_confirm) !== 0) {
            $errors['password'] = 'Las contraseñas no son coincidentes';
        }

        // Si hay errores
        if (!empty($errors)) {

            // Formulario no ha sido validado
            // Tengo que redireccionar al formulario de nuevo

            // Creo la variable de sessión name con los datos del formulario
            $_SESSION['name'] = $name;

            // Creo la variable de sessión email con los datos del formulario
            $_SESSION['email'] = $email;

            // Creo la variable de sessión password con los datos del formulario
            $_SESSION['password'] = $password;

            // Creo la variable de sessión error con los errores
            $_SESSION['errors'] = $errors;

            // redireciona al formulario de nuevo
            header('location:' . URL . 'auth/register');
            exit();
        }

        // Formulario validado
        // Añadir usuario a la base de datos
        // Obtengo el id asignado al nuevo usuario
        // Asigno el rol de registrado al nuevo usuario
        // 3 es el id del rol de usuario registrado
        $id = $this->model->create($name, $email, $password);

        // Email de registro
        $asunto = "¡Bienvenido a De Mi Casa a la Tuya, " . $name . "!";
        $cuerpo_mensaje = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 40px 0;">
    
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(17, 35, 49, 0.08);">
        
        <div style="background-color: #0F4C75; padding: 40px 20px; text-align: center;">
            <h1 style="margin: 0; color: #ffffff; font-family: Georgia, serif; font-size: 28px; font-weight: normal;">De Mi Casa a la Tuya</h1>
            <p style="margin: 10px 0 0 0; color: #D1A054; font-size: 16px; letter-spacing: 1px;">ALTA COCINA A DOMICILIO</p>
        </div>

        <div style="padding: 40px 30px; color: #3B4A53; line-height: 1.6; font-size: 16px;">
            <p style="margin-top: 0;">¡Hola <strong>{$name}</strong>!</p>
            
            <p>Nos hace muchísima ilusión darte la bienvenida. Tu cuenta se ha creado con éxito y ya formas parte de nuestra exclusiva comunidad gastronómica.</p>
            
            <div style="background-color: #f8f9fa; border-left: 4px solid #D1A054; padding: 20px; margin: 30px 0; border-radius: 0 4px 4px 0;">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #6c757d; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Tus datos de acceso</p>
                <p style="margin: 0;"><strong>Usuario:</strong> {$email}</p>
                <p style="margin: 10px 0 0 0; font-size: 13px; color: #6c757d;"><em>(Por tu seguridad, nunca enviaremos tu contraseña por correo. Usarás la que introdujiste al registrarte).</em></p>
            </div>

            <p>A partir de ahora, puedes acceder a tu panel para descubrir nuestra carta, reservar fechas y gestionar tus experiencias.</p>
            
            <div style="text-align: center; margin: 40px 0 20px 0;">
                <a href="{$enlace_login}" style="background-color: #0F4C75; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 5px; font-weight: bold; display: inline-block;">Acceder a mi cuenta</a>
            </div>
        </div>

        <div style="background-color: #FBF9F4; padding: 20px; text-align: center; border-top: 1px solid #e5e5e5;">
            <p style="margin: 0; font-size: 13px; color: #6c757d;">&copy; 2026 De Mi Casa a la Tuya.<br>Cocinando momentos únicos.</p>
        </div>
    </div>
</body>
</html>
HTML;

        $this->enviarEmail($name, $email, $asunto, $cuerpo_mensaje);

        // Genero mensaje de éxito
        $_SESSION['mensaje'] = 'Usuario registrado correctamente';

        // Redireciona al formulario de login
        header('location:' . URL . 'auth/login');
        exit();
    }

    /*
        Método: logout()
        Descripción: cierra sesión del usuario autenticado y redirigimos al home de la aplicación
    */
    public function logout()
    {
        // inicio o continuo la sesión
        sec_session_start();

        // Destruyo la sesión
        sec_session_destroy();

        // Redirección al formulario de login
        header('location:' . URL . 'index');
        exit();
    }

    /*
        Envía un email desde el sistema al usuario
    */
    function enviarEmail($name, $email, $subject, $message)
    {
        require_once 'config/smtp_gmail.php';
        require_once 'vendor/autoload.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "quoted-printable";
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->Port = SMTP_PORT;
            $mail->SMTPSecure = 'tls';

            // Configurar el email: Remitente (Sistema) -> Destinatario (Usuario)
            $mail->setFrom(SMTP_USER, 'Administración De Mi Casa a la Tuya');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->AltBody = strip_tags($message);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            $mensaje_error = "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
            $this->handleError($mensaje_error); // Reutilizamos tu manejador de errores
        }
    }


    private function handleError($mensaje_personalizado = null)
    {
        // Incluir y cargar el controlador de errores
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';

        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            
            // Si nos pasan un mensaje (ej: fallo de correo), lo usamos. Si no, usamos el del CSRF.
            $mensaje = $mensaje_personalizado ?? "Error de validación de seguridad del formulario. Intenta acceder de nuevo desde la página principal.";
            
            // Si es un error de correo, mostramos un 500. Si es de seguridad, un 403.
            $codigo = $mensaje_personalizado ? '500' : '403';
            
            $controller = new Errores($codigo, 'Mensaje de Error: ', $mensaje);
            exit(); // Detenemos la ejecución
        } else {
            echo "Error crítico: No se pudo cargar el controlador de errores.";
            exit();
        }
    }

    /*
        Método: forgot_password()
        Descripción: Muestra el formulario para solicitar la recuperación de contraseña
    */
    public function forgot_password()
    {
        sec_session_start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $this->view->title = "Recuperar Contraseña";
        $this->view->render('auth/forgot/index');
    }

    /*
        Método: send_reset_token()
        Descripción: Procesa el email, genera el token y envía el correo
    */
    public function send_reset_token()
    {
        sec_session_start();

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato de email incorrecto.";
            header('Location: ' . URL . 'auth/forgot_password');
            exit();
        }

        $user = $this->model->get_user_email($email);

        // Si el usuario existe, generamos el token
        if ($user) {
            // Generar token criptográficamente seguro
            $token = bin2hex(random_bytes(32));
            // Fecha de caducidad: 1 hora desde ahora
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Guardamos el token en la base de datos
            $this->model->setResetToken($email, $token, $expires_at);

            // Preparamos el email
            $enlace_recuperacion = URL . "auth/reset_password/" . $token;
            $asunto = "Recuperación de contraseña - De Mi Casa a la Tuya";

            // HTML del correo
            $mensaje = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 40px 0;">
    
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(17, 35, 49, 0.08);">
        
        <div style="background-color: #0F4C75; padding: 40px 20px; text-align: center;">
            <h1 style="margin: 0; color: #ffffff; font-family: Georgia, serif; font-size: 28px; font-weight: normal;">De Mi Casa a la Tuya</h1>
            <p style="margin: 10px 0 0 0; color: #D1A054; font-size: 16px; letter-spacing: 1px;">ALTA COCINA A DOMICILIO</p>
        </div>

        <div style="padding: 40px 30px; color: #3B4A53; line-height: 1.6; font-size: 16px;">
            <p style="margin-top: 0;">¡Hola <strong>{$user->name}</strong>!</p>
            
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Entendemos que los despistes ocurren, así que estamos aquí para ayudarte a recuperar el acceso.</p>
            
            <div style="text-align: center; margin: 40px 0;">
                <a href="{$enlace_recuperacion}" style="background-color: #0F4C75; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 5px; font-weight: bold; display: inline-block;">Crear Nueva Contraseña</a>
            </div>
            
            <div style="background-color: #f8f9fa; border-left: 4px solid #F09A54; padding: 20px; margin: 30px 0 0 0; border-radius: 0 4px 4px 0;">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #6c757d; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;"><span style="font-size: 16px;">🔒</span> Información de Seguridad</p>
                <p style="margin: 0; font-size: 14px; color: #555;">
                    Por motivos de seguridad, este enlace <strong>caducará en 1 hora</strong>. Si no has sido tú quien ha solicitado este cambio, ignora este correo. Tu contraseña actual seguirá siendo la misma y tu cuenta permanecerá totalmente segura.
                </p>
            </div>
        </div>

        <div style="background-color: #FBF9F4; padding: 20px; text-align: center; border-top: 1px solid #e5e5e5;">
            <p style="margin: 0; font-size: 13px; color: #6c757d;">&copy; 2026 De Mi Casa a la Tuya.<br>Cocinando momentos únicos.</p>
        </div>

    </div>
    
</body>
</html>
HTML;

            // Enviar correo (Asegúrate de tener $mail->isHTML(true); en tu método enviarEmail de Auth)
            $this->enviarEmail($user->name, $email, $asunto, $mensaje);
        }

        // MENSAJE GENÉRICO (Medida de seguridad contra enumeración de usuarios)
        $_SESSION['mensaje'] = "Si el correo electrónico está registrado, recibirás un enlace para restablecer tu contraseña.";
        header('Location: ' . URL . 'auth/login');
        exit();
    }

    /*
        Método: reset_password($params)
        Descripción: Verifica el token en la URL y muestra el formulario para la nueva clave
    */
    public function reset_password($params)
    {
        sec_session_start();

        $token = $params[0] ?? '';

        if (empty($token)) {
            $_SESSION['error'] = "Enlace de recuperación no válido.";
            header('Location: ' . URL . 'auth/login');
            exit();
        }

        // Verificamos si el token es válido y no ha caducado
        $user_id = $this->model->verifyResetToken($token);

        if (!$user_id) {
            $_SESSION['error'] = "El enlace de recuperación ha caducado o no es válido. Vuelve a solicitarlo.";
            header('Location: ' . URL . 'auth/forgot_password');
            exit();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $this->view->title = "Crear Nueva Contraseña";
        $this->view->token = $token; // Pasamos el token a la vista
        $this->view->render('auth/reset/index');
    }

    /*
        Método: update_password()
        Descripción: Procesa la nueva contraseña y actualiza la BD
    */
    public function update_password()
    {
        sec_session_start();

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        $token = filter_var($_POST['token'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST['password'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $password_confirm = filter_var($_POST['password_confirm'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

        // Validaciones básicas
        if (empty($password) || strlen($password) < 7) {
            $_SESSION['error'] = "La contraseña debe tener al menos 7 caracteres.";
            header('Location: ' . URL . 'auth/reset_password/' . $token);
            exit();
        }

        if ($password !== $password_confirm) {
            $_SESSION['error'] = "Las contraseñas no coinciden.";
            header('Location: ' . URL . 'auth/reset_password/' . $token);
            exit();
        }

        // Verificamos el token POR SEGUNDA VEZ (Crítico por seguridad)
        $user_id = $this->model->verifyResetToken($token);

        if (!$user_id) {
            $_SESSION['error'] = "El token ha caducado durante el proceso.";
            header('Location: ' . URL . 'auth/login');
            exit();
        }

        // Hasheamos la nueva contraseña
        $password_enc = password_hash($password, PASSWORD_DEFAULT);

        // Actualizamos la base de datos (borrando también el token)
        if ($this->model->updatePasswordWithToken($user_id, $password_enc)) {
            $_SESSION['mensaje'] = "Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión.";
        } else {
            $_SESSION['error'] = "Hubo un problema al actualizar la contraseña.";
        }

        header('Location: ' . URL . 'auth/login');
        exit();
    }
}
