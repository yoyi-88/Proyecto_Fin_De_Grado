<?php

class Citas extends Controller
{
    function __construct()
    {
        parent::__construct();
        sec_session_start();
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
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['citas']['render']);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

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
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['citas']['new']);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

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

        // 1. Validar fecha (no puede ser en el pasado)
        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha es obligatoria.';
        } elseif (strtotime($fecha) < strtotime(date('Y-m-d'))) {
            $errores['fecha'] = 'No puedes reservar en una fecha pasada.';
        }

        // 2. Validar hora (ejemplo simple, horario laboral)
        if (empty($hora)) $errores['hora'] = 'La hora es obligatoria.';
        
        // 3. Validar menú
        if (empty($menu_id)) $errores['menu_id'] = 'Debes seleccionar un menú.';

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
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['citas']['update']);

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
             // Error CSRF
        }

        $id = (int)$params[0];
        $estado = $_POST['estado'] ?? 'Pendiente';
        // Aquí podrías recoger también fecha/hora si permites reprogramar

        $this->model->update_estado($id, $estado);

        $_SESSION['mensaje'] = "Estado de la cita actualizado.";
        header('Location: ' . URL . 'citas');
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
}
?>