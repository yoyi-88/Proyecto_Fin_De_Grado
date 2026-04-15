<?php
class Account extends Controller
{

    function __construct()
    {

        parent::__construct();
    }

    /*
        Método principal

        Se  carga siempre que la url contenga sólo el primer parámetro

        url: /account
    */
    public function render()
    {
        // inicio o continuo la sesión
        sec_session_start();

        // Comprobar si hay un usuario logueado
        $this->requireLogin();

        // Crear un token CSRF para los formularios
        // Por si el usuario abre dos pestañas simultáneas del mismo formulario
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Compruebo si hay mensaje de éxito
        if (isset($_SESSION['mensaje'])) {

            // Creo la propiedad mensaje en la vista
            $this->view->mensaje = $_SESSION['mensaje'];

            // Elimino la variable de sesión mensaje
            unset($_SESSION['mensaje']);
        }

        // Compruebo si hay mensaje de error
        if (isset($_SESSION['error'])) {

            // Creo la propiedad mensaje en la vista
            $this->view->error = $_SESSION['error'];

            // Elimino la variable de sesión mensaje
            unset($_SESSION['error']);
        }


        # Obtenemos los detalles completos del usuario
        $this->view->account = $this->model->getUserId($_SESSION['user_id']);

        // Creo la propiedad title de la vista
        $this->view->title = "Mi cuenta " . $_SESSION['user_name'];

        $this->view->render('account/main/index');
    }

    /*
        Método para actualizar los datos del usuario. 
        Muestra en la vista el formulario con los datos del usuario en modo edición. 

        url: /account/edit

        @param $id int : id del usuario

    */
    public function edit()
    {
        // inicio o continuo la sesión
        sec_session_start();

        // Comprobar si hay un usuario logueado
        $this->requireLogin();

        // Crear un token CSRF para los formularios
        // Por si el usuario abre dos pestañas simultáneas del mismo formulario
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Compruebo si hay mensaje de éxito
        if (isset($_SESSION['mensaje'])) {

            // Creo la propiedad mensaje en la vista
            $this->view->mensaje = $_SESSION['mensaje'];

            // Elimino la variable de sesión mensaje
            unset($_SESSION['mensaje']);
        }

        // Compruebo si hay mensaje de error
        if (isset($_SESSION['error'])) {

            // Creo la propiedad mensaje en la vista
            $this->view->error = $_SESSION['error'];

            // Elimino la variable de sesión mensaje
            unset($_SESSION['error']);
        }

        // Obtenemos el id del usuario
        $id = $_SESSION['user_id'];

        // Obtenemos los detalles completos del usuario
        $this->view->account = $this->model->getUserId($id);

        // Capa no validación del formulario
        if (isset($_SESSION['errors'])) {

            // Creo la propiedad error en la vista
            $this->view->errors = $_SESSION['errors'];

            // Elimino la variable de sesión error
            unset($_SESSION['errors']);

            // Asigno a perfil los detalles del formulario
            $this->view->account = $_SESSION['account'];

            // Elimino la variable de sesión perfil
            unset($_SESSION['account']);

            // Creo la propiedad mensaje error
            $this->view->error = 'Revise los errores del formulario';
        }

        // Creo la propiedad title de la vista
        $this->view->title = "Editar cuenta " . $_SESSION['user_name'];
        $this->view->render('account/edit/index');
    }

    /*
        Método para actualizar los datos del usuario. 
        Actualiza los datos del usuario name y email. 

        Incluye:
         - validación token crsf.
         - validación de los datos del formulario.
         - prevención ataques csrf.

        url: /account/update

    */
    public function update()
    {
        // inicio o continuo la sesión
        sec_session_start();

        // comprobar si hay usuario logueado
        $this->requireLogin();

        // Validación token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Saneamos los detalles del formulario
        $name = filter_var($_POST['name'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ??= null, FILTER_SANITIZE_EMAIL);

        // Obtengo los detalles del usuario
        $account = $this->model->getUserId($_SESSION['user_id']);

        // validación de los datos del formulario
        $errors = [];

        // validación name
        // antes de validar compruebo se ha modificado
        if ($name != $account->name) {
            if (empty($name)) {
                $errors['name'] = 'El nombre es obligatorio';
            } else if (strlen($name) < 5) {
                $error['name'] = 'El nombre debe tener al menos 5 caracteres';
            } else if (strlen($name) > 20) {
                $errors['name'] = 'El nombre debe tener como máximo 20 caracteres';
            } else if (!$this->model->validateUniqueName($name)) {
                $errors['name'] = 'Nombre usuario existente';
            }
        }

        // validación email
        // antes de validar compruebo se ha modificado
        if ($email != $account->email) {
            if (empty($email)) {
                $errors['email'] = 'El email es obligatorio';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'El email no es válido';
            } else if (!$this->model->validateUniqueEmail($email)) {
                $errors['email'] = 'Email existente';
            }
        }

        // Si hay errores
        if (!empty($errors)) {
            // Creo la variable de sesión error
            $_SESSION['errors'] = $errors;

            // Creo la variable de sesión perfil
            $_SESSION['account'] = (object) [
                'name' => $name,
                'email' => $email
            ];

            // Redirecciono al formulario de edición
            header('location:' . URL . 'account/edit');
            exit();
        }

        // Actualizo los datos del usuario
        $this->model->update($name, $email, $_SESSION['user_id']);

        // Email por edifción de perfil
        $asunto = "Modificación de perfil en De Mi Casa a la Tuya";
        $cuerpo_mensaje = "¡Hola $name!\n\nTe informamos que los datos de tu perfil han sido modificados correctamente.\nSi no has sido tú, ponte en contacto con nosotros inmediatamente.\n\nUn saludo.";
        $this->enviarEmail($name, $email, $asunto, $cuerpo_mensaje);

        // Actualizo el posible nuevo nombre del usuario
        $_SESSION['user_name'] = $name;

        // Genero mensaje de éxito
        $_SESSION['mensaje'] = 'Cuenta actualizada correctamente';

        // Redirecciono a la vista principal de perfil
        header('location:' . URL . 'account');
    }

    /*
        Método para cambiar la contraseña del usuario. 
        Muestra en la vista el formulario para cambiar la contraseña. 

        url: /account/password

    */
    public function password()
    {
        // inicio o continuo la sesión
        sec_session_start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Comprobar si hay un usuario logueado
        $this->requireLogin();

        // Compruebo si hay mensaje de éxito
        if (isset($_SESSION['mensaje'])) {

            // Creo la propiedad mensaje en la vista
            $this->view->mensaje = $_SESSION['mensaje'];

            // Elimino la variable de sesión mensaje
            unset($_SESSION['mensaje']);
        }

        // Compruebo si hay mensaje de error
        if (isset($_SESSION['error'])) {

            // Creo la propiedad mensaje en la vista
            $this->view->error = $_SESSION['error'];

            // Elimino la variable de sesión mensaje
            unset($_SESSION['error']);
        }

        // Capa no validación del formulario
        if (isset($_SESSION['errors'])) {

            // Creo la propiedad error en la vista
            $this->view->errors = $_SESSION['errors'];

            // Elimino la variable de sesión error
            unset($_SESSION['errors']);

            // Creo la propiedad mensaje error
            $this->view->error = 'Formulario con errores, revísalos por favor';
        }

        // Obtenemos los detalles completos del usuario
        $this->view->account = $this->model->getUserId($_SESSION['user_id']);

        // Creo la propiedad title de la vista
        $this->view->title = "Cambiar password " . $_SESSION['user_name'];

        $this->view->render('account/password/index');
    }

    /*
        Método para actualizar la contraseña del usuario. 
        Actualiza la contraseña del usuario. 

        Incluye:
         - validación token crsf.
         - validación de los datos del formulario.
         - prevención ataques csrf.

        url: /account/update_password

    */
    public function update_password()
    {
        // inicio o continuo la sesión
        sec_session_start();

        // comprobar si hay usuario logueado
        $this->requireLogin();

        // Validación toekn CSRF
        // Verificar el token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Saneamos los detalles del formulario
        $password = filter_var($_POST['password'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $new_password = filter_var($_POST['new_password'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password = filter_var($_POST['confirm_password'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);

        // Obtengo los detalles del usuario
        $account = $this->model->getUserId($_SESSION['user_id']);

        // validación de los datos del formulario
        $errors = [];

        // validación password
        if (empty($password)) {
            $errors['password'] = 'Introduce el password actual';
        } else if (!password_verify($password, $account->password)) {
            $errors['password'] = 'El password actual no es correcto';
        }

        // validación new_password
        if (empty($new_password)) {
            $errors['new_password'] = 'El nuevo password es obligatorio';
        } else if (strlen($new_password) < 7) {
            $errors['new_password'] = 'El nuevo password debe tener al menos 7 caracteres';
        } else if (strcmp($new_password, $confirm_password) !== 0) {
            $errors['new_password'] = 'Passwords no coincidentes';
        }

        // Si hay errores
        if (!empty($errors)) {
            // Creo la variable de sesión error
            $_SESSION['errors'] = $errors;

            // Redirecciono al formulario de edición
            header('location:' . URL . 'account/password');
            exit();
        }

        // Actualizo password del usuario
        $this->model->updatePass($new_password, $_SESSION['user_id']);

        // Email por cambio de password
        $nombre_usuario = $_SESSION['user_name'];
        $email_usuario = $account->email; // Lo sacamos del objeto $account que obtuviste arriba
        $asunto = "Cambio de contraseña en De Mi Casa a la Tuya";
        $cuerpo_mensaje = "¡Hola $nombre_usuario!\n\nTu contraseña ha sido actualizada correctamente.\nTu nueva contraseña es: $new_password\n\nUn saludo.";
        $this->enviarEmail($nombre_usuario, $email_usuario, $asunto, $cuerpo_mensaje);

        // Genero mensaje de éxito
        $_SESSION['mensaje'] = 'Password actualizado correctamente';

        // Redirecciono a la vista principal de perfil
        header('location:' . URL . 'account');
        exit();
    }

    /*
        delete()

        Método para eliminar el usuario. 
        Elimina el usuario de la base de datos. 

        url: /account/delete


    */
    public function delete()
    {
        // inicio o continuo la sesión
        sec_session_start();

        // Comprobar si hay un usuario logueado
        $this->requireLogin();

        // Crear un token CSRF para los formularios
        // Por si el usuario abre dos pestañas simultáneas del mismo formulario
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        # Obtenemos los detalles completos del usuario
        $this->view->account = $this->model->getUserId($_SESSION['user_id']);

        // Creo la propiedad title de la vista
        $this->view->title = "Mi cuenta " . $_SESSION['user_name'];

        $this->view->render('account/delete/index');


    }

    public function delete_confirmed()
    {
        // inicio o continuo la sesión
        sec_session_start();

        // Comprobar si hay un usuario logueado
        $this->requireLogin();

        // Validación token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Email por eliminación de cuenta
        $nombre_usuario = $_SESSION['user_name'];
        // Obtenemos el email antes de borrar el usuario usando el modelo
        $usuario_a_borrar = $this->model->getUserId($_SESSION['user_id']);
        $email_usuario = $usuario_a_borrar->email;

        // Elimino el usuario
        $this->model->delete($_SESSION['user_id']);

        $asunto = "Confirmación de baja - De Mi Casa a la Tuya";
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
            <p style="margin-top: 0;">¡Hola <strong>{$nombre_usuario}</strong>,</p>
            
            <p>Te escribimos para confirmarte que tu cuenta ha sido eliminada correctamente de nuestra plataforma, tal y como solicitaste.</p>
            
            <div style="background-color: #f8f9fa; border-left: 4px solid #7A8B70; padding: 20px; margin: 30px 0; border-radius: 0 4px 4px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Confirmación de borrado:</strong> Todos tus datos personales, preferencias y el historial de reservas han sido destruidos de forma segura de nuestros servidores, en cumplimiento estricto con nuestra política de privacidad y protección de datos.
                </p>
            </div>

            <p>Ha sido un verdadero placer tenerte en nuestra comunidad gastronómica. Si en el futuro te apetece volver a disfrutar de la alta cocina sin moverte de tu salón, nuestras puertas (y nuestros fogones) siempre estarán abiertos para ti.</p>
            
            <p style="margin-top: 40px; margin-bottom: 0;">Un cordial saludo,<br>
            <strong>Rafael Gómez Albela</strong><br>
            <span style="font-size: 14px; color: #6c757d;">Chef de De Mi Casa a la Tuya</span></p>
        </div>

        <div style="background-color: #FBF9F4; padding: 20px; text-align: center; border-top: 1px solid #e5e5e5;">
            <p style="margin: 0; font-size: 13px; color: #6c757d;">&copy; 2026 De Mi Casa a la Tuya.<br>Esperamos volver a verte pronto.</p>
        </div>

    </div>
    
</body>
</html>
HTML;
        $this->enviarEmail($nombre_usuario, $email_usuario, $asunto, $cuerpo_mensaje);

        

        //Limpieza total de la sesión
        $_SESSION = [];

        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

        // Cierro la sesión
        session_destroy();

        // Redirecciono a la vista principal de perfil
        header('location:' . URL . 'auth/login?deleted=true');
        exit();
    }

    /*
        Método: requireLogin
        Descripción: Verifica que el usuario ha iniciado sesión
    */
    private function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['mensaje'] = "Debes iniciar sesión para acceder al sistema";
            header('Location: ' . URL . 'auth/login');
            exit();
        }
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
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            $mensaje_error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $this->handleError($mensaje_error); // Reutilizamos tu manejador de errores
        }
    }

    /*
        Método: handleError
        Descripción: Maneja los errores de la base de datos
    */

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
