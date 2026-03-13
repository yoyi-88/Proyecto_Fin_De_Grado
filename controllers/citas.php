<?php

class Citas extends Controller
{
    function __construct()
    {
        parent::__construct();
        
    }

    /*
        Método: render
        Descripción: Muestra la lista de reservas.
        Lógica: 
         - Si es Admin (Rol 1): Ve TODAS.
         - Si es Cliente (Rol 3): Ve SOLO LAS SUYAS.
    */
    function render()
    {
        // Iniciamos o continuamos sesión
        sec_session_start();

        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['citas']['render']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        // Feedback de mensajes
        if (isset($_SESSION['mensaje'])) {
            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }

        // Determinar qué datos mostrar según el rol
        $role_id = $_SESSION['role_id'];
        $user_id = $_SESSION['user_id'];

        if ($role_id == 1) {
            // Admin: Ver todas
            $this->view->title = "Gestión de Reservas (Global)";
            $this->view->citas = $this->model->get_all(); 
        } else {
            // Cliente: Ver solo las suyas
            $this->view->title = "Mis Reservas";
            $this->view->citas = $this->model->get_by_user($user_id);
        }

        $this->view->render('citas/main/index');
    }

    /*
        Método: new
        Descripción: Formulario de reserva
    */
    function new() {
        // Iniciamos o continuamos sesión
        sec_session_start();

        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['citas']['new']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $this->view->cita = new class_cita();

        // Recuperar datos (Sticky Form)
        if (isset($_SESSION['errores'])) {
            $this->view->errors = $_SESSION['errores'];
            unset($_SESSION['errores']);
            if (isset($_SESSION['cita'])) {
                $this->view->cita = $_SESSION['cita'];
                unset($_SESSION['cita']);
            }
        }

        $this->view->title = "Nueva Reserva";
        
        // Cargar lista de menús para el <select>
        $this->view->menus = $this->model->get_menus_disponibles();

        $this->view->render('citas/new/index');
    }

    /*
        Método: create
        Descripción: Guarda la reserva
    */
    public function create()
    {
        // Iniciamos o continuamos sesión
        sec_session_start();

        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['citas']['create']);

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            header('location:' . URL . 'error'); exit();
        }

        // Saneamiento
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $menu_id = filter_var($_POST['menu_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
        
        // El usuario es el que está logueado
        $user_id = $_SESSION['user_id'];

        $cita = new class_cita(null, $fecha, $hora, 'Pendiente', $user_id, $menu_id);

        // Validaciones
        $errores = [];

        // Validar fecha (no puede ser en el pasado)
        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha es obligatoria.';
        } elseif (strtotime($fecha) < strtotime(date('Y-m-d'))) {
            $errores['fecha'] = 'No puedes reservar en una fecha pasada.';
        }

        // Validar hora (ejemplo simple, horario laboral)
        if (empty($hora)) {
            $errores['hora'] = 'La hora es obligatoria.';
        } 
        
        // Validar menú
        if (empty($menu_id)) {
            $errores['menu_id'] = 'Debes seleccionar un menú.';
        } elseif (!filter_var($menu_id, FILTER_VALIDATE_INT)) {
            $errores['menu_id'] = 'Formato de menú no válido.';
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['cita'] = $cita;
            header('Location: ' . URL . 'citas/new');
            exit();
        }

        // Guardar
        $this->model->create($cita);
        
        $_SESSION['mensaje'] = "Reserva solicitada correctamente. Espera confirmación.";
        header('Location: ' . URL . 'citas');
        exit();
    }

    /*
        Método: edit
        Descripción: Principalmente para que el ADMIN cambie el estado (Confirmar/Cancelar)
    */
    public function edit($params) {
        // Iniciamos o continuamos sesión
        sec_session_start();

        $this->requireLogin();
        // Solo el Admin puede editar el estado de una cita
        $this->requirePrivilege($GLOBALS['citas']['edit']);

        $id = (int)$params[0];
        $this->view->cita = $this->model->read($id);
        $this->view->menus = $this->model->get_menus_disponibles(); // Por si quiere cambiar menú
        $this->view->title = "Gestionar Cita #$id";

        $this->view->render('citas/edit/index');
    }

    public function update($params) {
        // Iniciamos o continuamos sesión
        sec_session_start();

        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['citas']['update']);

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $_SESSION['error'] = "Error de seguridad (CSRF). Inténtalo de nuevo.";
            header('Location: ' . URL . 'citas'); 
            exit();
        }

        $id = (int)$params[0];
        $estado_nuevo = filter_var($_POST['estado'] ?? 'Pendiente', FILTER_SANITIZE_SPECIAL_CHARS);
        // 1. LEER DATOS ANTES DE ACTUALIZAR (Para saber el email, nombre y estado antiguo)
        $cita_db = $this->model->read($id);

        // 2. ACTUALIZAR ESTADO (Tu código original)
        $this->model->update_estado($id, $estado_nuevo);

        // 3. LÓGICA DEL CORREO: Si el estado ha cambiado, enviamos el email
        if ($cita_db->estado !== $estado_nuevo) {
            
            // Usamos los datos que trajimos en el paso 1
            $destinatario = $cita_db->cliente_email; 
            $nombre = $cita_db->cliente_nombre;
            $asunto = "Actualización de tu reserva - Chef Privado";

            $cuerpoEmail = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #d4af37;'>Hola, {$nombre}</h2>
                    <p>El estado de tu reserva con <strong>De Mi Casa a la Tuya</strong> ha cambiado.</p>
                    
                    <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #d4af37; margin: 20px 0;'>
                        <p><strong>Menú:</strong> {$cita_db->menu_nombre}</p>
                        <p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($cita_db->fecha)) . " a las {$cita_db->hora}</p>
                        <p><strong>Nuevo Estado:</strong> <span style='font-size: 1.2em; text-transform: uppercase;'>{$estado_nuevo}</span></p>
                    </div>
                    
                    <p>Un saludo cordial,<br><strong>El equipo de Chef Privado</strong></p>
                </div>
            ";

            // Envio del correo
            $this->enviarEmail($nombre, $destinatario, $asunto, $cuerpoEmail);
        }

        $_SESSION['mensaje'] = "Estado de la cita actualizado.";
        header('Location: ' . URL . 'citas');
        exit();
    }

    /*
        Método: disponibilidad
        Descripción: Endpoint (API) que devuelve en JSON los días ocupados.
    */
    public function disponibilidad() {
        // No necesitamos cargar vistas, esto es una respuesta invisible para JavaScript
        header('Content-Type: application/json');
        
        $diasOcupados = $this->model->get_dias_ocupados();
        
        // Imprimimos el array convertido a formato JSON
        echo json_encode($diasOcupados);
        exit(); // Cortamos la ejecución para que no intente cargar el diseño (footer, header, etc.)
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'auth/login'); exit();
        }
    }

    private function requirePrivilege($allowedRoles) {
        if (!in_array($_SESSION['role_id'], $allowedRoles)) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción.';
            header('Location: ' . URL . 'citas'); exit();
        }
    }

    /*
        Envía un email desde el sistema al usuario
    */
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
            $mail->setFrom(SMTP_USER, 'De Mi Casa a la Tuya');
            $mail->addAddress($email, $name);
            
            // Le decimos a PHPMailer que el cuerpo del mensaje contiene etiquetas HTML
            $mail->isHTML(true); 

            $mail->Subject = $subject;
            $mail->Body = $message;
            
            // Opcional pero muy recomendado para evitar ir a Spam: 
            // Versión en texto plano por si el cliente de correo del usuario no soporta HTML
            $mail->AltBody = strip_tags($message); 

            $mail->send();
        } catch (Exception $e) {
            $mensaje_error = "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
            $this->handleError($mensaje_error); // Reutilizamos tu manejador de errores
        }
    }

    public function factura($params)
    {
        sec_session_start();

        // Capa Login (Requisito de tu proyecto)
        $this->requireLogin();

        // Validamos que tenga permisos (ajusta el nombre del privilegio si es necesario)
        $this->requirePrivilege($GLOBALS['citas']['read']); 

        $id = (int)$params[0];

        // Obtener los datos del modelo (una sola cita)
        $cita = $this->model->read($id);

        // Verificación de seguridad
        if (!$cita || $cita->estado !== 'Finalizada') {
            $_SESSION['error'] = "La factura solo está disponible para reservas finalizadas.";
            header('Location: ' . URL . 'citas');
            exit();
        }

        // Requerir la nueva clase PDF que vamos a crear
        require_once 'class/pdf_factura.class.php';

        // Instanciar y configurar PDF
        $pdf = new pdf_factura();
        $pdf->AliasNbPages(); // Necesario para que el {nb} del Footer calcule el total
        
        // Añadir la primera página
        $pdf->AddPage();

        // Imprimir el título y los datos del cliente/empresa pasándole el objeto cita
        $pdf->titulo($cita);

        // Configurar fuente para los datos
        $pdf->SetFont('Arial', '', 11); 

        // Usamos mb_substr para cortar correctamente en UTF-8 sin romper tildes
        $cliente = mb_substr($cita->cliente_nombre, 0, 40, 'UTF-8');
        $menu = mb_substr($cita->menu_nombre, 0, 50, 'UTF-8');
        
        // Cálculos del IVA
        $precio_total = (float)$cita->menu_precio;
        $base_imponible = $precio_total / 1.10;
        $cuota_iva = $precio_total - $base_imponible;

        // Fila de encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(120, 10, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Concepto'), 1, 0, 'L', true);
        $pdf->Cell(35, 10, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Base Imponible'), 1, 0, 'C', true);
        $pdf->Cell(35, 10, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Importe Total'), 1, 1, 'C', true);

        // Fila de datos (El Menú)
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(120, 10, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Menú: ' . $menu), 1, 0, 'L');
        $pdf->Cell(35, 10, number_format($base_imponible, 2, ',', '.') . ' ' . chr(128), 1, 0, 'C');
        $pdf->Cell(35, 10, number_format($precio_total, 2, ',', '.') . ' ' . chr(128), 1, 1, 'C');

        $pdf->Ln(10);

        // Totales alineados a la derecha
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(120, 8, '', 0, 0); // Celda invisible para empujar el texto
        $pdf->Cell(35, 8, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Base Imponible:'), 0, 0, 'R');
        $pdf->Cell(35, 8, number_format($base_imponible, 2, ',', '.') . ' ' . chr(128), 0, 1, 'R');

        $pdf->Cell(120, 8, '', 0, 0);
        $pdf->Cell(35, 8, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'IVA (10%):'), 0, 0, 'R');
        $pdf->Cell(35, 8, number_format($cuota_iva, 2, ',', '.') . ' ' . chr(128), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(120, 10, '', 0, 0);
        $pdf->Cell(35, 10, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'TOTAL:'), 0, 0, 'R');
        $pdf->Cell(35, 10, number_format($precio_total, 2, ',', '.') . ' ' . chr(128), 0, 1, 'R');

        // Generar y mostrar el PDF en el navegador ('I' = Inline)
        $pdf->Output('I', 'Factura_' . str_pad($cita->id, 4, '0', STR_PAD_LEFT) . '.pdf', true);
        
        exit(); 
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
?>