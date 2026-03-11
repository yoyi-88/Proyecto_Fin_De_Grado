<?php

class Menu extends Controller
{
    function __construct()
    {
        parent::__construct();
        
    }

    /*
        Método: render
        Descripción: Renderiza la lista de menús (Catálogo)
    */
    function render()
    {
        // Iniciamos o continuamos sesión
        sec_session_start();
        // NOTA: El render lo dejo público o solo logueados según decidas. 
        // Si es público, quita requireLogin. Si no, déjalo.
        // $this->requireLogin(); 
        // Antes: $this->requireLogin(); (o nada)
        // $this->requireLogin();

        // $this->requirePrivilege($GLOBALS['menu']['render']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

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
        // Iniciamos o continuamos sesión
        sec_session_start();
        // Seguridad: Solo Admin (Chef) puede crear
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['menu']['new']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

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
        // Iniciamos o continuamos sesión
        sec_session_start();

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

        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } 

        if (empty($descripcion)) {
            $errores['descripcion'] = 'La descripción es obligatoria.';
        } 
        
        if (empty($precio)) {
            $errores['precio'] = 'El precio es obligatorio.';
        } elseif (!filter_var($precio, FILTER_VALIDATE_FLOAT)) {
            $errores['precio'] = 'El precio debe ser un número decimal válido.';
        } elseif ((float)$precio <= 0) {
            $errores['precio'] = 'El precio debe ser positivo.';
        }

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
        // Iniciamos o continuamos sesión
        sec_session_start();

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

    /*
        Método: show
        Descripción: Muestra los detalles de un menú específico (solo lectura)
    */
    public function show($params) {
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['menu']['show']);
        
        $id = (int) $params[0];
        
        // Validar que el menú existe
        if (!$this->model->validate_id_menu_exists($id)) {
            $_SESSION['error'] = "El menú que intentas ver no existe.";
            header('Location: ' . URL . 'menu');
            exit();
        }

        $this->view->title = "Detalle del Menú";
        $this->view->menu = $this->model->read($id);
        $this->view->render('menu/show/index');
    }

    /*
        Método: edit
        Descripción: Carga el formulario con los datos del menú para editarlo
    */
    public function edit($params) {
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['menu']['edit']); // Solo Chef
        
        $id = (int) $params[0];
        
        if (!$this->model->validate_id_menu_exists($id)) {
            $_SESSION['error'] = "El menú que intentas editar no existe.";
            header('Location: ' . URL . 'menu');
            exit();
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        // Cargar datos actuales
        $this->view->id = $id;
        $this->view->menu = $this->model->read($id);

        // Comprobar si venimos rebotados por un error de validación
        if (isset($_SESSION['errores'])) {
            $this->view->errors = $_SESSION['errores'];
            unset($_SESSION['errores']);
            if (isset($_SESSION['menu'])) {
                $this->view->menu = $_SESSION['menu']; // Sobrescribir con lo que el usuario tecleó
                unset($_SESSION['menu']);
            }
            $this->view->error = "Revisa los errores del formulario.";
        }

        $this->view->title = "Editar Plato";
        $this->view->render('menu/edit/index');
    }

    /*
        Método: update
        Descripción: Procesa los datos del formulario de edición y actualiza la BD
    */
    public function update($params) {
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['menu']['update']); // Solo Chef

        $id = (int) $params[0];

        // Validar CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            header('location:' . URL . 'error');
            exit();
        }

        // Sanear datos
        $nombre = filter_var($_POST['nombre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $descripcion = filter_var($_POST['descripcion'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $precio = filter_var($_POST['precio'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Crear objeto con los datos actualizados y obtener los originales de la BD
        $menu_act = new class_menu($id, $nombre, $descripcion, $precio);
        $menu_db = $this->model->read($id);

        $errores = [];
        $cambios = false;

        // Comprobar cambios y validar
        if ($nombre != $menu_db->nombre) {
            $cambios = true;
            if (empty($nombre)) $errores['nombre'] = 'El nombre es obligatorio.';
        }
        if ($descripcion != $menu_db->descripcion) {
            $cambios = true;
            if (empty($descripcion)) $errores['descripcion'] = 'La descripción es obligatoria.';
        }
        if ($precio != $menu_db->precio) {
            $cambios = true;
            if (empty($precio) || $precio <= 0) $errores['precio'] = 'El precio debe ser un número positivo.';
        }

        // Si hay errores, volver al formulario
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['menu'] = $menu_act;
            header('Location: ' . URL . 'menu/edit/' . $id);
            exit();
        }

        // Si no hay cambios, no hacemos query a la BD
        if (!$cambios) {
            $_SESSION['mensaje'] = "No se han realizado cambios.";
            header('Location: ' . URL . 'menu');
            exit();
        }

        // Ejecutar actualización
        $this->model->update($menu_act);
        $_SESSION['mensaje'] = "Menú actualizado correctamente.";
        header('Location: ' . URL . 'menu');
        exit();
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