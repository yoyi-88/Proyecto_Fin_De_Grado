<?php

class Contact extends Controller
{

    // Crea una instancia del controlador Alumno
    // Llama al constructor de la clase padre Controller
    // Crea una vista para el controlador Alumno
    // Carga el modelo si existe alumno.model.php
    function __construct()
    {

        parent::__construct();
    }

    /*
            Método:  render
            Descripción: Renderiza la vista del alumno

            views/alumno/index.php
        */

    function render()
    {

        // iniciar o continuar sesión para validar formulario de contacto
        sec_session_start();

        // Crear un token CSRF para los formularios
        // Por si el usuario abre dos pestañas simultáneas del mismo formulario
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Comprobar si hay mensajes en la sesión y pasarlos a la vista
        if (isset($_SESSION['notify'])) {
            $this->view->notify = $_SESSION['notify'];
            unset($_SESSION['notify']);
        }

        // Comprobar si hay mensajes de error en la sesión y pasarlos a la vista
        if (isset($_SESSION['error'])) {
            $this->view->notify = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        // Inicializar los campos del formulario de contacto  como objeto contacto
        $this->view->contact = new class_contact();

        // Compruebo si hay errores de una no validación anterior
        if (isset($_SESSION['errors'])) {

            // Muestro los errores
            $this->view->error = $_SESSION['errors'];

            // Retroalimento el formulario
            $this->view->contact = $_SESSION['contact'];

            // Elimino la variable de sesión
            unset($_SESSION['errors']);

            // Elimino la variable de sesión del formulario
            unset($_SESSION['contact']);
        }

        // Creo la propiedad  title para la vista
        $this->view->title = "Formulario de Contacto";


        // Renderizo la vista del formulario de contacto
        $this->view->render('contact/index');
    }

    /*
        Método: validate()
        Descripción: Valida el formulario de contacto y si es correcto envía un email al administrador 
        con los datos del formulario
    */

    public function validate()
    {

        // inicio o continúo sesión
        sec_session_start();

        // Verificar el token CSRF
        $this->checkTokenCsrf($_POST['csrf_token'] ??= '');


        // Recogemos los datos del formulario saneados
        // Prevenir ataques XSS
        $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);
        $subject = filter_var($_POST['subject'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
        $message = filter_var($_POST['message'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

        // Crear un objeto de la clase Contact
        $contact = new class_contact(
            $name,
            $email,
            $subject,
            $message
        );

        // Validamos los campos del formulario

        // Creo un array asociativo para almacenar los posibles errores del formulario
        // $error['nombre'] =  'Nombre es obligatorio'

        $errors = [];

        // Validamos el name
        // Regla validación: obligatorio
        if (empty($name)) {
            $errors['name'] = "El campo name es obligatorio";
        }

        // Validación de los apellidos
        // Regla validación: obligatorio
        if (empty($email)) {
            $errors['email'] = "El email es obligatorio";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "El formato del email no es correcto";
        }

        // Vaidamos el subject
        // Regla validación: obligatorio
        if (empty($subject)) {
            $errors['subject'] = "El campo subject es obligatorio";
        }

        // Validamos el message
        // Regla validación: obligatorio
        if (empty($message)) {
            $errors['message'] = "El campo message es obligatorio";
        }


        // Fin Validación

        // Si hay errores vuelvo al formulario mostrando los errores
        if (!empty($errors)) {

            // Almaceno los errores en la sesión
            $_SESSION['errors'] = $errors;

            // Almaceno los datos del formulario en la sesión para rellenar el formulario
            $_SESSION['contact'] = $contact;

            // Mensaje de error general para la vista
            $_SESSION['error'] = "Errores en el formulario";

            // Redirijo al formulario
            header('Location: ' . URL . 'contact');
            exit();
        }

        // Enviar correo al administrador con los datos del formulario
        $cuerpo_mensaje = "Nombre: $name\n";
        $cuerpo_mensaje .= "Email: $email\n";
        $cuerpo_mensaje .= "Asunto: $subject\n";
        $cuerpo_mensaje .= "Mensaje: $message\n";

        // Enviar el correo
        // Si no hay errores, envío el email
        $this->enviarEmail($name, $email, $subject, $cuerpo_mensaje);

        // Generar un mensaje de éxito
        $_SESSION['notify'] = "Mensaje enviado correctamente";

        // Redirigir a la lista de alumnos
        header('Location: ' . URL . 'contact');
        exit();
    }





    /*
        Envía un email
    */
    function enviarEmail($name, $email, $subject, $message)
    {
        // Configuración de la cuenta de correo
        require_once 'config/smtp_gmail.php';

        // Instalar PHPMailer con Composer
        // composer require phpmailer/phpmailer
        // Cargar el autoload de Composer para PHPMailer
        require_once 'vendor/autoload.php';

        // Crear una nueva instancia de PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {

            // Configuración juego caracteres
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "quoted-printable";

            // Servidor SMTP
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->Port = SMTP_PORT;
            $mail->SMTPSecure = 'tls';

            // Configurar el email
            $mail->setFrom($email, $name);
            $mail->addAddress(SMTP_USER);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Configurar el email
            $mail->send();
        } catch (Exception $e) {
            $mensaje_error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $this->handleError($mensaje_error);
        }
    }

    /*
        Método checkTokenCsrf()
        Permite checkear si el token CSRF es válido
        @param
            - string $csrf_token: token CSRF
    */
    public function checkTokenCsrf($csrf_token)
    {

        // Validación CSRF
        if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $this->handleError('Error de validación CSRF: Token no válido');
        }
    }

    /*
        Método: handleError
        Descripción: Maneja los errores de la base de datos
    */

    private function handleError($mensaje_error)
    {
        // Incluir y cargar el controlador de errores
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';

        if (file_exists($errorControllerFile)) {
            sec_session_destroy();
            require_once $errorControllerFile;
            $mensaje = $mensaje_error;
            $controller = new Errores('403', 'Mensaje de Error: ', $mensaje);
        } else {
            // Fallback en caso de que el controlador de errores no exista
            echo "Error crítico: " . "No se pudo cargar el controlador de errores.";
            exit();
        }
    }
}
