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

        $this->view->title = "Menús";
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

        // Procesamiento y saneamiento de la Imagen
        $nombreImagen = 'default.jpg'; // Imagen por defecto si no sube ninguna
        $erroresImagen = [];

        // Verificamos si existe el array de la imagen y si el usuario realmente seleccionó un archivo (error 4 es "no file")
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== 4) {
            // Llamamos a nuestro método privado para que valide y guarde la imagen
            $resultadoImagen = $this->procesarImagen($_FILES['imagen']);
            
            if (is_array($resultadoImagen)) {
                // Si devuelve un array, significa que hubo errores (peso, formato incorrecto)
                $erroresImagen = $resultadoImagen;
            } else {
                // Si devuelve un texto, es el nombre seguro generado (ej: menu_65ab34f.jpg)
                $nombreImagen = $resultadoImagen;
            }
        }
        // Crear objeto para persistencia
        $menu = new class_menu(null, $nombre, $descripcion, $nombreImagen, $precio);

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

        // Unir los errores de texto con los posibles errores de la imagen
        $errores = array_merge($errores, $erroresImagen);

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
        // Iniciamos o continuamos sesión
        sec_session_start();

        // No incluimos requireLogin ni requirePrivilege, ya que queremos que mostrar sea 
        // visible incluso para los usuarios no registrados
        
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
        // Iniciamos o continuamos sesión
        sec_session_start();

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
        // Iniciamos o continuamos sesión
        sec_session_start();
        
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
        $menu_db = $this->model->read($id);

        $errores = [];
        $cambios = false;

        // Procesar images si se subió
        $nombreImagen = $menu_db->imagen; // Por defecto, conservamos la imagen que ya tenía
        $erroresImagen = [];

        // Comprobamos si el usuario seleccionó un archivo nuevo (error 4 significa "vacío")
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== 4) {
            $resultadoImagen = $this->procesarImagen($_FILES['imagen']);
            
            if (is_array($resultadoImagen)) {
                $erroresImagen = $resultadoImagen;
            } else {
                $nombreImagen = $resultadoImagen; // Guardamos el nombre de la foto nueva
                $cambios = true; // ¡Importante! Ha cambiado la foto, así que hay cambios que guardar
                
                // (Opcional pero recomendado) Borrar la foto antigua del servidor para no acumular basura
                if ($menu_db->imagen != 'default.jpg' && file_exists('public/images/menus/' . $menu_db->imagen)) {
                    unlink('public/images/menus/' . $menu_db->imagen);
                }
            }
        }

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

        // Fusionar los dos arrays de errores
        $errores = array_merge($errores, $erroresImagen);

        // Crear objeto actualizado
        $menu_act = new class_menu($id, $nombre, $descripcion, $nombreImagen, $precio);
        
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

    /*
        Método Privado: procesarImagen
        Descripción: Valida y mueve la imagen subida al servidor.
        Devuelve: El nombre del archivo o un array con errores.
    */
    private function procesarImagen($file) {
        // Si no se subió ningún archivo, devolvemos el valor por defecto
        if ($file['error'] == 4) {
            return 'default.jpg';
        }

        $errores = [];
        $nombreArchivo = $file['name'];
        $tipoArchivo = $file['type'];
        $tamanoArchivo = $file['size'];
        $rutaTemporal = $file['tmp_name'];

        // Validar Tamaño (Máximo 10MB)
        $max_size = 10 * 1024 * 1024; 
        if ($tamanoArchivo > $max_size) {
            $errores['imagen'] = "La imagen supera el límite de 10MB.";
        }

        // Validar Tipo (Solo imágenes)
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($tipoArchivo, $tiposPermitidos)) {
            $errores['imagen'] = "Solo se permiten formatos JPG, PNG o WEBP.";
        }

        // Si hay errores, devolvemos el array de errores
        if (!empty($errores)) {
            return $errores;
        }

        // Generar un nombre único seguro (evita sobreescribir archivos)
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreUnico = uniqid('menu_') . '.' . $extension;
        $rutaDestino = 'public/images/menus/' . $nombreUnico;

        // Mover el archivo
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            return $nombreUnico;
        } else {
            return ['imagen' => 'Error al guardar la imagen en el servidor.'];
        }
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