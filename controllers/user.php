<?php

class User extends Controller {

    function __construct() {
        parent::__construct();
        sec_session_start();
    }

    function render() {
        $this->requireLogin();
        // Solo Admin puede ver esto. Asumimos que tienes una config de privilegios
        $this->requireAdmin(); 

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        if (isset($_SESSION['mensaje'])) {
            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }
        if (isset($_SESSION['error'])) { // Corrección para mostrar errores en rojo si tienes estilo
            $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $this->view->title = "Gestión de Usuarios";
        $this->view->users = $this->model->get();
        $this->view->render('user/main/index');
    }

    function new() {
        $this->requireLogin();
        $this->requireAdmin();

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->view->user = new class_user();

        if (isset($_SESSION['errores'])) {
            $this->view->errors = $_SESSION['errores'];
            unset($_SESSION['errores']);
            if (isset($_SESSION['user'])) {
                $this->view->user = $_SESSION['user'];
                unset($_SESSION['user']);
            }
        }

        $this->view->title = "Nuevo Usuario";
        $this->view->roles = $this->model->get_roles(); // Cargar roles para el select
        $this->view->render('user/new/index');
    }

    public function create() {
        $this->requireLogin();
        $this->requireAdmin();

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            header('location:' . URL . 'error'); exit();
        }

        $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $role_id = filter_var($_POST['role_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        $user = new class_user(null, $name, $email, $password, $role_id);
        $errores = [];

        // Validaciones
        if (empty($name)) $errores['name'] = "El nombre es obligatorio.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores['email'] = "Email inválido.";
        elseif (!$this->model->validateUniqueEmail($email)) $errores['email'] = "Email ya registrado.";
        
        if (empty($password) || strlen($password) < 5) $errores['password'] = "La contraseña debe tener al menos 5 caracteres.";
        
        if (empty($role_id) || !$this->model->validateRole($role_id)) $errores['role_id'] = "Rol inválido.";

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['user'] = $user;
            header('Location: ' . URL . 'user/new');
            exit();
        }

        // Hashing de contraseña antes de guardar
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        $this->model->create($user);
        $_SESSION['mensaje'] = "Usuario creado correctamente.";
        header('Location: ' . URL . 'user');
        exit();
    }

    public function edit($params) {
        $this->requireLogin();
        $this->requireAdmin();
        
        $id = (int)$params[0];
        $this->view->user = $this->model->read($id);
        $this->view->id = $id;
        $this->view->roles = $this->model->get_roles();
        $this->view->title = "Editar Usuario";

        // Manejo de errores de validación al rebotar del update
        if (isset($_SESSION['errores'])) {
            $this->view->errors = $_SESSION['errores'];
            unset($_SESSION['errores']);
            // Sobreescribir con datos del form fallido
            $this->view->user = $_SESSION['user'];
            unset($_SESSION['user']);
        }

        $this->view->render('user/edit/index');
    }

    public function update($params) {
        $this->requireLogin();
        $this->requireAdmin();

        $id = (int)$params[0];
        // Validar CSRF...

        $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? ''; // Puede estar vacío si no se cambia
        $role_id = filter_var($_POST['role_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        $user_act = new class_user($id, $name, $email, $password, $role_id);
        $user_db = $this->model->read($id); // Obtener datos actuales

        $errores = [];
        $cambios = false;

        // Comprobación de cambios y validaciones
        if ($name != $user_db->name) { $cambios = true; if(empty($name)) $errores['name'] = "Nombre obligatorio."; }
        
        if ($email != $user_db->email) { 
            $cambios = true; 
            if(empty($email)) $errores['email'] = "Email obligatorio.";
            elseif(!$this->model->validateUniqueEmail($email, $id)) $errores['email'] = "Email ya existe.";
        }

        if ($role_id != $user_db->role_id) { $cambios = true; }

        // Si password no está vacío, significa que quiere cambiarlo
        if (!empty($password)) {
            $cambios = true;
            if(strlen($password) < 5) $errores['password'] = "La contraseña debe tener 5+ caracteres.";
            // Hashear nueva contraseña
            $user_act->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $user_act->password = null; // Indicar al modelo que no toque la contraseña
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['user'] = $user_act;
            header('Location: ' . URL . 'user/edit/' . $id);
            exit();
        }

        if ($cambios) {
            $this->model->update($user_act);
            $_SESSION['mensaje'] = "Usuario actualizado.";
        } else {
            $_SESSION['mensaje'] = "No hubo cambios.";
        }

        header('Location: ' . URL . 'user');
        exit();
    }

    public function delete($params) {
        $this->requireLogin();
        $this->requireAdmin();
        // Validar CSRF...
        
        $id = (int)$params[0];
        if (!$this->model->validateIdUser($id)) {
            $_SESSION['error'] = "Usuario no existe.";
            header('Location: ' . URL . 'user'); exit();
        }

        // Evitar que el admin se borre a sí mismo
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "No puedes eliminar tu propia cuenta.";
            header('Location: ' . URL . 'user'); exit();
        }

        $this->model->delete($id);
        $_SESSION['mensaje'] = "Usuario eliminado.";
        header('Location: ' . URL . 'user');
    }

    public function show($params) {
        $this->requireLogin();
        $this->requireAdmin();
        $id = (int)$params[0];
        $this->view->user = $this->model->read($id); // Ojo: create un read_show si quieres unir tablas para mostrar nombre de rol en vez de ID
        
        // Pequeño parche para mostrar nombre del rol en show
        $roles = $this->model->get_roles();
        $this->view->role_name = $roles[$this->view->user->role_id] ?? 'Sin Rol';

        $this->view->title = "Detalles Usuario";
        $this->view->render('user/show/index');
    }

    public function order($params) {
        $this->requireLogin();
        $this->requireAdmin();
        $criterio = (int)$params[0];
        $this->view->users = $this->model->order($criterio);
        $this->view->title = "Usuarios Ordenados";
        $this->view->render('user/main/index');
    }

    public function search() {
        $this->requireLogin();
        $this->requireAdmin();
        $term = $_GET['term'] ?? '';
        $this->view->users = $this->model->search($term);
        $this->view->title = "Búsqueda: $term";
        $this->view->render('user/main/index');
    }

    // Helpers de seguridad
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'auth/login'); exit();
        }
    }

    private function requireAdmin() {
        // Validación estricta: Solo rol ID 1 (Admin) pasa
        if ($_SESSION['role_id'] != 1) { 
            $_SESSION['error'] = "Acceso denegado. Área exclusiva de Administradores.";
            header('Location: ' . URL . 'libro'); // O a home
            exit();
        }
    }
}
?>