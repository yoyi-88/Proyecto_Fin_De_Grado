<?php

class Menu extends Controller
{
    function __construct()
    {
        parent::__construct();
        sec_session_start();
    }

    /*
        Método: render
        Descripción: Renderiza la lista de menús (Catálogo)
    */
    function render()
    {
        // NOTA: El render lo dejo público o solo logueados según decidas. 
        // Si es público, quita requireLogin. Si no, déjalo.
        // $this->requireLogin(); 
        // Antes: $this->requireLogin(); (o nada)
        $this->requireLogin();

        $this->requirePrivilege($GLOBALS['menu']['render']);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Gestión de mensajes de sesión (Feedback usuario)
        if (isset($_SESSION['mensaje'])) {
            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }
        if (isset($_SESSION['error'])) {
            $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $this->view->title = "Carta de Menús";
        $this->view->menus = $this->model->get();

        $this->view->render('menu/main/index');
    }

    /*
        Método: new
        Descripción: Formulario crear menú
    */
    function new() {
        // Seguridad: Solo Admin (Chef) puede crear
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['menu']['new']);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Inicializar objeto vacío
        $this->view->menu = new class_menu();

        // Recuperar datos si hubo error (Sticky Form)
        if (isset($_SESSION['errores'])) {
            $this->view->errors = $_SESSION['errores'];
            unset($_SESSION['errores']);

            if (isset($_SESSION['menu'])) {
                $this->view->menu = $_SESSION['menu'];
                unset($_SESSION['menu']);
            }
            $this->view->error = "Errores en el formulario";
        }

        $this->view->title = "Nuevo Menú";
        $this->view->render('menu/new/index');
    }

    /*
        Método: create
        Descripción: Inserta menú en BD
    */
    public function create()
    {
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['menu']['create']);

        // Validar CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            header('location:' . URL . 'error');
            exit();
        }

        // Saneamiento
        $nombre = filter_var($_POST['nombre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $descripcion = filter_var($_POST['descripcion'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $precio = filter_var($_POST['precio'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Crear objeto para persistencia
        $menu = new class_menu(null, $nombre, $descripcion, $precio);

        // Validación
        $errores = [];

        if (empty($nombre)) $errores['nombre'] = 'El nombre es obligatorio.';
        if (empty($descripcion)) $errores['descripcion'] = 'La descripción es obligatoria.';
        if (empty($precio) || $precio <= 0) $errores['precio'] = 'El precio debe ser positivo.';

        // Si hay errores
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['menu'] = $menu;
            header('Location: ' . URL . 'menu/new');
            exit();
        }

        // Guardar
        $this->model->create($menu);
        
        $_SESSION['mensaje'] = "Menú creado correctamente";
        header('Location: ' . URL . 'menu');
        exit();
    }

    /*
        Método: delete
        Descripción: Elimina un menú
    */
    public function delete($params)
    {
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['menu']['delete']);

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $this->handleError(); // O redirigir a error
        }

        $id = (int) $params[0];
        
        // Validar que existe antes de borrar
        if (!$this->model->validate_id_menu_exists($id)) {
            $_SESSION['error'] = "El menú no existe";
            header('Location: ' . URL . 'menu');
            exit();
        }

        $this->model->delete($id);
        $_SESSION['mensaje'] = "Menú eliminado correctamente";
        header('Location: ' . URL . 'menu');
    }

    // Helpers de seguridad (Copiados de tu estructura Auth)
    private function requirePrivilege($allowedRoles)
    {
        if (!in_array($_SESSION['role_id'], $allowedRoles)) {
            $_SESSION['error'] = 'Acceso denegado. No tiene permisos de Chef.';
            header('Location: ' . URL . 'menu');
            exit();
        }
    }

    private function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['mensaje'] = "Debes iniciar sesión.";
            header('Location: ' . URL . 'auth/login');
            exit();
        }
    }
}
?>