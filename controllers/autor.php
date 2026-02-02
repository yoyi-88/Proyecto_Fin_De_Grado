<?php

    class Autor Extends Controller {

        // Crea una instancia del controlador Autor
        // Llama al constructor de la clase padre Controller
        // Crea una vista para el controlador Autor
        // Carga el modelo si existe autor.model.php
        function __construct() {

            parent ::__construct(); 
            sec_session_start();

            // 2. Control de acceso
        if (!isset($_SESSION['user_id'])) {
            // Si no está logueado, lo mandamos al login con un mensaje
            $_SESSION['notify'] = "Debe iniciar sesión para acceder a la gestión.";
            header('location:' . URL . 'auth/login');
            exit();
        }
            
        }

        /*
            Método:  render
            Descripción: Renderiza la vista del autor

            views/autor/main/index.php
        */

        function render() {

            
            // Obtengo los datos del  modelo para mostrar en la vista
            
            // Creo la propiedad  title para la vista
            $this->view->title = "Tabla Autores de GesLibros";

            // Obtengo los datos del modelo
            $this->view->autores = $this->model->get();

            // Llama a la vista para renderizar la página
            $this->view->render('autor/main/index');
        }

        /*
            Método:new
            Descripción: Muestra el formulario para crear un nuevo autor
        */
        function new() {

            // Creo la propiedad  title para la vista
            $this->view->title = "Nuevo Autor";

            // Llama a la vista para renderizar la página
            $this->view->render('autor/new/index');
        }

       /*
            Método: create
            Descripción: Recibe los datos del formulario para crear un nuevo autor
            url asociada: autor/create
       */
       public function create() {

        // Obtengo los datos del formulario
        $nombre = $_POST['nombre']?? '';
        $nacionalidad = $_POST['nacionalidad']?? '';
        $fecha_nac = $_POST['fecha_nac']?? '';
        $email = $_POST['email']?? '';
        $premios = $_POST['premios']?? '';

        // Validar los datos, se omite en este ejemplo

        // Crear un objeto de la clase Autor
        $autor = new class_autor (
            null, 
            $nombre,
            $nacionalidad,
            $fecha_nac,
            $email,
            $premios
        );

        // Llamar al modelo para insertar el nuevo autor
        $this->model->create($autor);

        // Redirigir a la lista de autores
        header('Location: ' . URL . 'autor');


    }   
    
    /*
        Método: edit()
        Descripción: permite cargar los datos necesarios para editar los detalles
        de un autor.

        Parámetros:
            - id: autor a editar
    */
    public function edit($params) {

        // Obtener el id del autor que voy a editar
        // autor/edit/4 -> voy a editar el autor con id=4
        // $param es un array en la posición 0 está el id
        $id = (int) $params[0];
        
        // Obtener el objeto de la class_autor con los detalles de este autor
        $this->view->autor = $this->model->read($id);

        // Creo la propiedad id en la vista
        $this->view->id = $id;

        // Creo el titulo para la  vista
        $this->view->title = "Formulario Editar autor";

        // Cargo la vista
        $this->view->render('autor/edit/index');


    }

    /*
        Método: update()
        Descripción: Recibe los datos del formulario para actualizar un autor
        url asociada: autor/update/id

        Parámetros:
            - id (GET): autor a actualizar
            - datos del formulario (POST)
    */
    public function update($params) {

        // Obtener el id del autor que voy a actualizar
        $id = (int) $params[0];

        // Obtengo los datos del formulario
        $nombre = $_POST['nombre']?? '';
        $nacionalidad = $_POST['nacionalidad']?? '';
        $fecha_nac = $_POST['fecha_nac']?? '';
        $email = $_POST['email']?? '';
        $premios = $_POST['premios']?? '';

        // Validar los datos, se omite en este ejemplo

        // Crear un objeto de la clase autor
        $autor = new class_autor (
            null, 
            $nombre,
            $nacionalidad,
            $fecha_nac,
            $email,
            $premios
        );

        // Llamar al modelo para actualizar el autor
        $this->model->update($autor, $id);

        // Redirigir a la lista de autores
        header('Location: ' . URL . 'autor');     
    
    
    }

    /*
        Método: show()
        Descripción: Muestra los detalles de un autor
        Los detalles del autor se mostran en un formulario de solo lectura
        Parámetros:
            - id: autor a mostrar
    */
    public function show($params) {

        // Obtener el id del autor que voy a mostrar
        // autor/show/4 -> voy a mostrar el autor con id=4
        // $param es un array en la posición 0 está el id
        $id = (int) $params[0];
        
        // Obtener el objeto de la class_autor con los detalles de este autor
        $this->view->autor = $this->model->read_show($id);

        // Creo la propiedad id en la vista
        $this->view->id = $id;

        // Creo el titulo para la  vista
        $this->view->title = "Detalles del autor";

        // Cargo la vista
        $this->view->render('autor/show/index');
    }

    /*
        Método: delete()
        Descripción: Elimina un autor de la base de datos
        Parámetros:
            - id: autor a eliminar
    */
    public function delete($params) {

        // Obtener el id del autor que voy a eliminar
        // autor/delete/4 -> voy a eliminar el autor con id=4
        // $param es un array en la posición 0 está el id
        $id = (int) $params[0];
        
        // Llamar al modelo para eliminar el autor
        $this->model->delete($id);

        // Redirigir a la lista de autores
        header('Location: ' . URL . 'autor');
    }

    /*
        Método: search()
        Descripción: Busca a partir de una expresión en todos los detalles de los autores
        url asociada: autor/search
    */
    public function search() {

        // Obtengo el término de búsqueda del formulario
        $term = $_GET['term'] ?? '';

        // Creo la propiedad  title para la vista
        $this->view->notify = "Resultados de la búsqueda";

        // Llamar al modelo para buscar los autores
        $this->view->autores = $this->model->search($term);

        // Llama a la vista para renderizar la página
        $this->view->render('autor/main/index');
    }

    /*
        Método: order()
        Descripción: Ordena la lista de autores por un criterio
        url asociada: autor/order/criterio

        Parámetros:
            - criterio: campo por el que se ordena la lista
                1: id
                2: nombre
                3: nacionalidad
                4: fecha_nac
                5: email
                6: premios
    */
    public function order($params) {

        // Obtener el criterio de ordenación
        $criterio = (int) $params[0];

        // Mapeo de criterios a columnas de la base de datos
        $columnas = [
            1 => 'id',
            2 => 'nombre',
            3 => 'nacionalidad',
            4 => 'fecha_nac',
            5 => 'email',
            6 => 'premios'
        ];

        // Creo la propiedad  title para la vista
        $this->view->title = "autores ordenados por " . ($columnas[$criterio] ?? 'Id');  

        // Creo la propiedad  notify para la vista
        $this->view->notify = "autores ordenados por " . ($columnas[$criterio] ?? 'Id');

        // Llamar al modelo para ordenar los autores
        $this->view->autores = $this->model->order($criterio);

        // Llama a la vista para renderizar la página
        $this->view->render('autor/main/index');
    }
    
}

?>