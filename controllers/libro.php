<?php

class Libro extends Controller
{

    // Crea una instancia del controlador libro
    // Llama al constructor de la clase padre Controller
    // Crea una vista para el controlador libro
    // Carga el modelo si existe libro.model.php
    function __construct()
    {

        parent::__construct();
        sec_session_start();
    }

    /*
            Método:  render
            Descripción: Renderiza la vista del libro

            views/libro/index.php
        */

    function render()
    {

        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['render']);

        // Crear un token CSRF para los formularios
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Comprobar si hay mensajes en la sesión y pasarlos a la vista
        if (isset($_SESSION['mensaje'])) {
            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }

        // Comprobar si hay mensajes de error en la sesión y pasarlos a la vista
        if (isset($_SESSION['error'])) {
            $this->view->mensaje = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        //  IMPORTANTE: Borrarlo de la sesión para que no se repita al recargar
        unset($_SESSION['mensaje']);

        $this->view->title = "Tabla Libros de GesLibros";
        $this->view->libros = $this->model->get();

        $this->view->render('libro/main/index');
    }

    /*
            Método:new
            Descripción: Muestra el formulario para crear un nuevo libro

            Carga de datos: lista de cursos para la lista dinámica del select
        */
    function new() {

        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['new']);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // 1. Inicializar con un objeto vacío por defecto
        $this->view->libro = new class_libro();

        // 2. IMPORTANTE: Usar 'errores' (o 'errors') de forma consistente
        if (isset($_SESSION['errores'])) { 
            $this->view->errors = $_SESSION['errores']; // Pasamos a la vista
            unset($_SESSION['errores']);

            // Recuperamos los datos que el usuario ya había escrito
            if (isset($_SESSION['libro'])) {
                $this->view->libro = $_SESSION['libro'];
                unset($_SESSION['libro']);
            }

            $this->view->error = "Errores en el formulario";
        }

        $this->view->title = "Nuevo Libro";
        $this->view->autores = $this->model->get_autores();
        $this->view->editoriales = $this->model->get_editoriales();
        $this->view->generos = $this->model->get_generos();

        $this->view->render('libro/new/index');
    }

    /*
            Método: create
            Descripción: Recibe los datos del formulario para crear un nuevo libro
            url asociada: libro/create
       */
    public function create()
    {
        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['new']);



        // Validar el token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            header('location:' . URL . 'error');
            exit();
        }

        // Saneamiento de datos (Prevención XSS)
        // Usamos filter_input para limpiar entradas básicas
        $titulo = filter_var($_POST['titulo'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $autor_id = filter_var($_POST['autor_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
        $editorial_id = filter_var($_POST['editorial_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
        $precio_venta = filter_var($_POST['precio_venta'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $stock = filter_var($_POST['stock'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $fecha_edicion = $_POST['fecha_edicion'] ?? '';
        $isbn = filter_var($_POST['isbn'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $generos_seleccionados = $_POST['genero'] ?? [];

        // Recogida de datos para persistencia (Sticky Form)
        // Si hay error, el formulario recuperará estos valores
        $libro = new class_libro(
            null,
            $titulo,
            $autor_id,
            $editorial_id,
            $precio_venta,
            $stock,
            $fecha_edicion,
            $isbn,
            $generos_seleccionados
        );

        // Validación
        $errores = [];

        // Título: Obligatorio
        if (empty($titulo)) $errores['titulo'] = 'El título es obligatorio.';

        // Autor: Obligatorio, numérico y existe
        if (empty($autor_id) || !is_numeric($autor_id)) {
            $errores['autor_id'] = 'Debe seleccionar un autor válido.';
        } else if (!$this->model->validateAutor($autor_id)) {
            $errores['autor_id'] = 'El autor seleccionado no existe.';
        }

        // Editorial: Obligatorio, numérico y existe
        if ($editorial_id === null || $editorial_id === '' || $editorial_id <= 0) {
            $errores['editorial_id'] = 'Debe seleccionar una editorial válida.';
        }


        // Precio: Obligatorio y numérico
        if (empty($precio_venta) || $precio_venta <= 0) $errores['precio_venta'] = 'El precio debe ser un valor positivo.';

        // Fecha Edición: Obligatorio
        if (empty($fecha_edicion)) $errores['fecha_edicion'] = 'La fecha de edición es obligatoria.';

        // ISBN: Obligatorio, 13 dígitos y único
        if (empty($isbn) || !preg_match('/^\d{13}$/', $isbn)) {
            $errores['isbn'] = 'El ISBN debe tener exactamente 13 dígitos numéricos.';
        } else if (!$this->model->validateUniqueIsbn($isbn)) {
            $errores['isbn'] = 'Este ISBN ya está registrado.';
        }

        // Géneros: Al menos 1 y que existan
        if (empty($generos_seleccionados)) {
            $errores['genero'] = 'Debe seleccionar al menos un género.';
        }

        // Comprobar errores
        if (!empty($errores)) {
            // Guardamos errores y datos en la sesión para la vista
            $_SESSION['errores'] = $errores;
            $libro->generos = $generos_seleccionados;
            $_SESSION['libro'] = $libro;
            // Redirigir de nuevo al formulario de creación
            header('Location: ' . URL . 'libro/new');
            exit();
        }

        $this->model->create($libro);
            
        $_SESSION['mensaje'] = "Libro creado correctamente";
        

        // Redirigir a la lista de libros
        header('Location: ' . URL . 'libro');
        exit();
    }

    /*
        Método: edit()
        Descripción: permite cargar los datos necesarios para editar los detalles
        de un libro.

        Parámetros:
            - id: libro a editar
    */
    public function edit($params)
    {
        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['edit']);



        // Obtener el id del libro que voy a editar
        // libro/edit/4 -> voy a editar el libro con id=4
        // $param es un array en la posición 0 está el id
        $id = (int) $params[0];

        // Obtener el token CSRF para el formulario
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Obtener el objeto de la class_libro con los detalles de este libro
        $this->view->libro = $this->model->read($id);

        // Creo la propiedad id en la vista
        $this->view->id = $id;

        // comprobar no validación previa
        if (isset($_SESSION['errores'])) {
            // Creo la propiedad errors en la vista
            $this->view->errors = $_SESSION['errores'];
            unset($_SESSION['errores']);

            // Creo la propiedad libro en la vista con los datos del formulario
            $this->view->libro = $_SESSION['libro'];
            unset($_SESSION['libro']);

            // Creo la propiead error para la vista
            $this->view->error = "Errores en el formulario ";
        }

        // Creo el titulo para la  vista
        $this->view->title = "Formulario Editar libro";

        // Cargamos los autores
        $this->view->autores = $this->model->get_autores();

        // Cargamos los editoriales
        $this->view->editoriales = $this->model->get_editoriales();

        // Cargamos los generos
        $this->view->generos = $this->model->get_generos();

        // Cargamos los temas
        $this->view->temas_libros = $this->model->get_temas_libro($id);

        // Cargo la vista
        $this->view->render('libro/edit/index');
    }

    /*
        Método:     ()
        Descripción: Recibe los datos del formulario para actualizar un libro
        url asociada: libro/update/id

        Parámetros:
            - id (GET): libro a actualizar
            - datos del formulario (POST)
    */
    public function update($params)
    {
        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['edit']);
 

        // Obtener el id del libro que voy a actualizar
        $id = (int) htmlspecialchars($params[0]);

        // Verificar el token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Saneamiento de datos (Prevención XSS)
        // Usamos filter_input para limpiar entradas básicas
        $titulo = filter_var($_POST['titulo'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $autor_id = filter_var($_POST['autor_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
        $editorial_id = filter_var($_POST['editorial_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
        $precio_venta = filter_var($_POST['precio_venta'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $stock = filter_var($_POST['stock'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $fecha_edicion = $_POST['fecha_edicion'] ?? '';
        $isbn = filter_var($_POST['isbn'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $generos_seleccionados = $_POST['genero'] ?? [];


        // Validar los datos, se omite en este ejemplo

        // Crear un objeto de la clase libro
        $libro_act = new class_libro(
            $id,
            $titulo,
            $autor_id,
            $editorial_id,
            $precio_venta,
            $stock,
            $fecha_edicion,
            $isbn,
            $generos_seleccionados
        );

        // Obtengo detalles del libro antes de la actualización para validaciones
        $libro = $this->model->read($id);

        // array asociativo para almacenar los errores de validación
        $errores = [];

        // Control cambios
        $cambios = false;

        // Título: Obligatorio
        if (strcmp($titulo, $libro->titulo) != 0) {
            $cambios = true;
            if (empty($titulo)) {
                $errores['titulo'] = 'El campo titulo es obligatorio';
            }
        }

        // Autor: Validación Clave Ajena: Obligatorio, Numérico, id de autor existe en la tabla autores.
        if (strcmp($autor_id, $libro->autor_id) != 0) {
            $cambios = true;
            if (empty($autor_id) || !is_numeric($autor_id)) {
                $errores['autor_id'] = 'Debe seleccionar un autor válido.';
            } else if (!$this->model->validateAutor($autor_id)) {
                $errores['autor_id'] = 'El autor seleccionado no existe.';
            }
        }
        // Editorial Validación Clave Ajena. Idem 
        if (strcmp($editorial_id, $libro->editorial_id) != 0) {
            $cambios = true;
            if (empty($editorial_id) || !is_numeric($editorial_id)) {
                $errores['editorial_id'] = 'Debe seleccionar una editorial válida.';
            } else if (!$this->model->validateEditorial($editorial_id)) {
                $errores['editorial_id'] = 'La editorial seleccionada no existe.';
            }
        }
        // Precio: Valor obligatorio.
        if (strcmp($precio_venta, $libro->precio_venta) != 0) {
            $cambios = true;
            if (empty($precio_venta) || !is_numeric($precio_venta) || $precio_venta <= 0) {
                $errores['precio_venta'] = 'El precio debe ser un valor positivo.';
            }
        }
        // Unidades: Opcional
        if (strcmp($stock, $libro->stock) != 0) {
            $cambios = true;
            if (!is_numeric($stock) || $stock < 0) {
                $errores['stock'] = 'El stock debe ser un número entero no negativo.';
            }
        }

        // Fecha Edición: Obligatorio, Formato tipo fecha
        if (strcmp($fecha_edicion, $libro->fecha_edicion) != 0) {
            $cambios = true;
            if (empty($fecha_edicion)) {
                $errores['fecha_edicion'] = 'La fecha de edición es obligatoria.';
            }
        }

        // Isbn: Obligatorio, formato isbn (13 dígitos numéricos), Valor único
        if (strcmp($isbn, $libro->isbn) != 0) {
            $cambios = true;
            if (empty($isbn) || !preg_match('/^\d{13}$/', $isbn)) {
                $errores['isbn'] = 'El ISBN debe tener exactamente 13 dígitos numéricos.';
            } else if (!$this->model->validateUniqueIsbnUpdate($isbn, $id)) {
                $errores['isbn'] = 'Este ISBN ya está registrado.';
            }
        }

        // Géneros: Obligatorio (tengo que elegir al menos 1), valores numéricos, valores existentes en la tabla géneros.
        if ($generos_seleccionados != $this->model->get_temas_libro($id)) {
            $cambios = true;
            if (empty($generos_seleccionados)) {
                $errores['genero'] = 'Debe seleccionar al menos un género.';
            }
        }

        // Si hay errores, redirijo al formulario de edición
        if (!empty($errores)) {
            // Guardamos errores y datos en la sesión para la vista
            $_SESSION['errores'] = $errores;
            $libro_act->generos = $generos_seleccionados;
            $_SESSION['libro'] = $libro_act;
            // Redirigir de nuevo al formulario de edición
            header('Location: ' . URL . 'libro/edit/' . $id);
            exit();
        }

        // Si no hay cambios redirijo a la lista de libros
        if (!$cambios) {
            // Genero mensaje de notificación sin cambios
            $_SESSION['mensaje'] = "No se han realizado cambios en el libro.";
            header('Location: ' . URL . 'libro');
            exit();
        }

        // Llamar al modelo para actualizar el libro
        $this->model->update($libro_act, $id);

        // Genero mensaje de notificación de éxito
        $_SESSION['mensaje'] = "Libro actualizado correctamente";

        // Redirigir a la lista de libros
        header('Location: ' . URL . 'libro');
        exit();
    }

    /*
        Método: show()
        Descripción: Muestra los detalles de un libro
        Los detalles del libro se mostran en un formulario de solo lectura
        Parámetros:
            - id: libro a mostrar
    */
    public function show($params)
    {
        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['show']);
  

        // Obtener el id del libro que voy a mostrar
        // libro/show/4 -> voy a mostrar el libro con id=4
        // $param es un array en la posición 0 está el id
        $id = (int) htmlspecialchars($params[0]);

        // No es necesario el token csfr para mostrar datos

        // Validar id del libro que voy a mostrar
        if (!$this->model->validate_id_libro_exists($id)) {
            // Generar un mensaje de error
            $_SESSION['error'] = "El libro que intentas ver no existe";
            // Redirigir a la lista de libros si el id no es válido
            header('Location: ' . URL . 'libro');
            exit();
        }

        // Obtener el objeto de la class_libro con los detalles de este libro
        $this->view->libro = $this->model->read_show($id);

        // Creo la propiedad id en la vista
        $this->view->id = $id;

        // Creo el titulo para la  vista
        $this->view->title = "Detalles del libro";

        // Cargo la vista
        $this->view->render('libro/show/index');
    }

    /*
        Método: delete()
        Descripción: Elimina un libro de la base de datos
        Parámetros:
            - id: libro a eliminar
    */
    public function delete($params)
    {
        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['delete']);


        // obtengo el token CSRF url
        $csrf_token = $_POST['csrf_token'] ??= '';

        // verificar el token CSRF
        if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $this->handleError();
        }

        // Obtener el id del libro que voy a eliminar
        // libro/delete/4 -> voy a eliminar el libro con id=4
        // $param es un array en la posición 0 está el id
        $id = (int) $params[0];
        
        // validar id del libro que voy a eliminar
        if (!$this->model->validate_id_libro_exists($id)) {
            // Generar un mensaje de error
            $_SESSION['error'] = "El libro que intentas eliminar no existe";
            // Redirigir a la lista de libros si el id no es válido
            header('Location: ' . URL . 'libro');
            exit();
        }

        // Llamar al modelo para eliminar el libro
        $this->model->delete($id);

        // Generar un mensaje de éxito
        $_SESSION['mensaje'] = "Libro eliminado correctamente";

        // Redirigir a la lista de libros
        header('Location: ' . URL . 'libro');
    }

    /*
        Método: search()
        Descripción: Busca a partir de una expresión en todos los detalles de los libros
        url asociada: libro/search
    */
    public function search()
    {
        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['search']);


        // Obtengo el término de búsqueda del formulario
        $term = filter_var($_GET['term'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

        // No es necesario el token csrf para búsquedas GET

        // Creo la propiedad  title para la vista
        $this->view->mensaje = "Resultados de la búsqueda: " . $term;

        // Llamar al modelo para buscar los libros
        $this->view->libros = $this->model->search($term);

        // Llama a la vista para renderizar la página
        $this->view->render('libro/main/index');
    }

    /*
        Método: order()
        Descripción: Ordena la lista de libros por un criterio
        url asociada: libro/order/criterio

        Parámetros:
            - criterio: campo por el que se ordena la lista
                1: Título
                2: Autor
                3: Editorial
                4: Precio venta
                5: Stock
                6: Género
    */
    public function order($params)
    {
        // Capa Login
        $this->requireLogin();

        // Capa gestión rol de usuario
        // Solo los usuarios con privilegios pueden acceder a esta funcionalidad
        $this->requirePrivilege($GLOBALS['libro']['order']);


        // No es necesario el toke csfr para ordenación GET  

        // Obtengo el criterio de ordenación
        $criterio = (int) $params[0];

        // Mapeo de criterios a columnas de la base de datos
        $columnas = [
            1 => 'id',
            2 => 'titulo',
            3 => 'autor',
            4 => 'editorial',
            5 => 'generos',
            6 => 'stock',
            7 => 'precio_venta'
        ];

        // Creo la propiedad  title para la vista
        $this->view->title = "Libros ordenados por " . ($columnas[$criterio] ?? 'Id');  

        // Creo la propiedad  mensaje para la vista
        $this->view->mensaje = "Libros ordenados por " . ($columnas[$criterio] ?? 'Id');

        // Llamar al modelo para ordenar los libros
        $this->view->libros = $this->model->order($criterio);

        // Llama a la vista para renderizar la página
        $this->view->render('libro/main/index');
    }

    /*
        Método: requirePrivilege
        Descripción: Verifica que el usuario tiene privilegios para acceder a la funcionalidad
    */
    private function requirePrivilege($allowedRoles)
    {
        if (!in_array($_SESSION['role_id'], $allowedRoles)) {
            $_SESSION['error'] = 'Acceso denegado. No tiene permisos suficientes';
            header('Location: ' . URL . 'libro');
            exit();
        }
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
        Método: handleErrores
        Descripción: Maneja los erroreses de la base de datos
    */
    private function handleError()
    {
        // Incluir y cargar el controlador de erroreses
        $erroresControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';
        
        if (file_exists($erroresControllerFile)) {
            require_once $erroresControllerFile;
            $mensaje = "Errores de validación de seguridad del formulario. Intenta acceder de nuevo desde la página principal";
            $controller = new Errores('403', 'Mensaje de Errores: ', $mensaje);
            
        } else {
            // Fallback en caso de que el controlador de errores no exista
            echo "Errores crítico: " . "No se pudo cargar el controlador de errores.";
            exit();
        }
    }
}
