<?php
/*
    Modelo:  libroModel
    Descripción: Modelo para gestionar los datos de los libros
*/
   

class libroModel extends Model {

    
    /*
        Método: get()
        Descripción: Obtiene todos los libros de la base de datos geslibros
    */

    public function get() {

        try {
        // Consulta SQL para obtener todos los libros
        $sql = "
            SELECT 
                l.id,
                l.titulo,
                a.nombre AS autor,
                e.nombre AS editorial,
                GROUP_CONCAT(t.tema ORDER BY t.tema SEPARATOR ', ') AS generos,
                l.stock,
                l.precio_venta precio 
            FROM libros AS l
            LEFT JOIN autores AS a         ON l.autor_id = a.id
            LEFT JOIN editoriales AS e     ON l.editorial_id = e.id
            LEFT JOIN libros_temas AS lt   ON l.id = lt.libro_id
            LEFT JOIN temas AS t           ON lt.tema_id = t.id
            GROUP BY l.id
            ORDER BY l.id ASC
        ";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Establecer modo de obtención de datos  fectch
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Ejecutar la consulta
        $stmt->execute();
        
        // Devuelvo objeto de la clase PDOStatement o array con los datos
        return $stmt;

        } catch (PDOException $e) {

            // Manejo del error
           $this->handleError($e); 
          
        }
    }

    /*
    método:  get_editoriales()
    descripción: obtiene un array acociativo con las editoriales de la base de datos
    Indices: id => titulo
    */

    public function get_editoriales()
    {
        try {

            $sql = "SELECT id, nombre FROM editoriales ORDER BY nombre ASC";

            // Conectar con la base de datos
            $geslibros = $this->db->connect();

            // Preparar la consulta obteniendo el objeto PDOStatement
            $stmt = $geslibros->prepare($sql);

            // Establecer modo de obtención de datos  fectch
            $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);

            // Ejecutar la consulta
            $stmt->execute();

            // **CAMBIO CLAVE 2:** Obtener todos los resultados y devolverlos
            $editoriales = $stmt->fetchAll();
            return $editoriales;
        } catch (PDOException $e) {

            // Manejo del error
           $this->handleError($e); 
        }
        
    }

     /*
    método:  get_autories()
    descripción: obtiene un array acociativo con los autores de la base de datos
    */
    
    public function get_autores() {
        try {

            $sql = "SELECT id, nombre FROM autores ORDER BY nombre ASC";

            // Conectar con la base de datos
            $geslibros = $this->db->connect();

            // Preparar la consulta obteniendo el objeto PDOStatement
            $stmt = $geslibros->prepare($sql);

            // Establecer modo de obtención de datos  fectch
            $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);

            // Ejecutar la consulta
            $stmt->execute();

            $autores = $stmt->fetchAll();

            return $autores;
        } catch (PDOException $e) {

            // Manejo del error
           $this->handleError($e);
        }
    }

    public function get_generos() {
        try {

            $sql = "SELECT id, tema FROM temas ORDER BY tema ASC";

            // Conectar con la base de datos
            $geslibros = $this->db->connect();

            // Preparar la consulta obteniendo el objeto PDOStatement
            $stmt = $geslibros->prepare($sql);

            // Establecer modo de obtención de datos  fectch
            $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);

            // Ejecutar la consulta
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {

            // Manejo del error
           $this->handleError($e);
        }
    }

    /*
    Método: get_temas_libro(int $id)
    Descripción: Obtiene un array con los IDs de los temas de un libro específico
    */
    public function get_temas_libro(int $id) {
        try {
            $sql = "SELECT tema_id FROM libros_temas WHERE libro_id = :id";
            
            $geslibros = $this->db->connect();
            $stmt = $geslibros->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Devolvemos un array simple de los IDs de los temas
            return $stmt->fetchAll(PDO::FETCH_COLUMN);

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }


    /*
        Método: create($libro)
        Descripción: Inserta un nuevo libro en la base de datos ges$geslibros
        Parámetros: 
            - $libro: objeto de la clase class_libro con los datos del libro a insertar
        Devuelve:
            - id del nuevo libro insertado
            - falso en caso de error
    */
    public function create($libro) {
        try {
            // Consulta SQL actualizada con ISBN y Fecha Edición
            $sql = "INSERT INTO libros 
                    (titulo, autor_id, editorial_id, stock, precio_venta, fecha_edicion, isbn) 
                    VALUES 
                    (:titulo, :autor_id, :editorial_id, :stock, :precio_venta, :fecha_edicion, :isbn)";

            $geslibros = $this->db->connect();
            $stmt = $geslibros->prepare($sql);

            // Vincular los parámetros existentes
            $stmt->bindParam(':titulo', $libro->titulo, PDO::PARAM_STR, 80);
            $stmt->bindParam(':autor_id', $libro->autor_id, PDO::PARAM_INT);
            $stmt->bindParam(':editorial_id', $libro->editorial_id, PDO::PARAM_INT);
            $stmt->bindParam(':stock', $libro->stock, PDO::PARAM_INT);
            $stmt->bindParam(':precio_venta', $libro->precio_venta, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_edicion', $libro->fecha_edicion, PDO::PARAM_STR);
            $stmt->bindParam(':isbn', $libro->isbn, PDO::PARAM_STR, 13);

            $stmt->execute();
            
            $libro_id = $geslibros->lastInsertId();

            // Insertar géneros (relación N:M)
            if (!empty($libro->temas)) {
                $this->insert_temas_libro($libro_id, $libro->temas); 
            }

            return $libro_id;

        } catch (PDOException $e) {
            $this->handleError($e); 
        }

        
    }

    /*
        Método: read()
        Descripción: obtiene los detalles de un libro devolviendo un objeto de la clase  class_libro
        Paráemtros: 
            - $id: id del libro

        Devuelve:
            - $libro: objeto de la clase class_libro
    */
    public function read(int $id){
        try {

            $sql = "SELECT 
                    id, 
                    titulo, 
                    autor_id, 
                    editorial_id, 
                    fecha_edicion, 
                    isbn, 
                    stock, 
                    precio_venta
                FROM libros 
                WHERE id = :id";
            
            // conectamos con la base de datos
            $geslibros = $this->db->connect();

            // prepare
            $stmt = $geslibros->prepare($sql);

            // Vincular los parámetros del prepare
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // establecemos tipo fetch
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            // Ejectuamos
            $stmt->execute();

            // Devolvemos el primer y único valor del stmt
            return $stmt->fetch();

        }
         catch (PDOException $e){
            // Manejo del error
           $this->handleError($e); 
        }
    }

    /*
        Método: read_show()
        Descripción: obtiene los detalles de un libro devolviendo un objeto con los detalles del libro
        incluido el titulo del curso
        Paráemtros: 
            - $id: id del libro

        Devuelve:
            - $libro: objeto de la clase libro con los detalles del libro
    */
    public function read_show(int $id){
        try {

            $sql = "SELECT 
                    l.id,
                    l.titulo,
                    a.nombre AS autor,
                    e.nombre AS editorial,
                    GROUP_CONCAT(t.tema ORDER BY t.tema SEPARATOR ', ') AS generos,
                    l.stock,
                    l.precio_venta
                FROM libros AS l
                LEFT JOIN autores AS a         ON l.autor_id = a.id
                LEFT JOIN editoriales AS e     ON l.editorial_id = e.id
                LEFT JOIN libros_temas AS lt   ON l.id = lt.libro_id
                LEFT JOIN temas AS t           ON lt.tema_id = t.id
                WHERE l.id = :id
                GROUP BY l.id
                ";
            
            // conectamos con la base de datos
            $geslibros = $this->db->connect();

            // prepare
            $stmt = $geslibros->prepare($sql);

            // Vincular los parámetros del prepare
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // establecemos tipo fetch
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            // Ejectuamos
            $stmt->execute();

            // Devolvemos el primer y único valor del stmt
            return $stmt->fetch();

        }
        catch (PDOException $e){
            // Manejo del error
            $this->handleError($e); 
        }
    }

    /*
        Método: update($libro)
        Descripción: Actualiza los datos de un libro en la base de datos ges$geslibros
        Parámetros: 
            - $libro: objeto de la clase class_libro con los datos del libro a actualizar
            - $id: id del libro a actualizar
        Devuelve:
            - true si la actualización fue exitosa
            - false en caso de error
    */
    public function update($libro) {

        try {
        // Consulta SQL para actualizar un libro
        $sql = "UPDATE libros SET 
                    titulo = :titulo, 
                    autor_id = :autor_id, 
                    editorial_id = :editorial_id, 
                    stock = :stock, 
                    precio_venta = :precio_venta,
                    fecha_edicion = :fecha_edicion,
                    isbn = :isbn
                WHERE id = :id";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();
        $stmt = $geslibros->prepare($sql);

        // 2. Vincular los parámetros de la tabla 'libros'
        $stmt->bindParam(':titulo', $libro->titulo, PDO::PARAM_STR, 80); // Ajustar longitud
        $stmt->bindParam(':autor_id', $libro->autor_id, PDO::PARAM_INT);
        $stmt->bindParam(':editorial_id', $libro->editorial_id, PDO::PARAM_INT);
        $stmt->bindParam(':stock', $libro->stock, PDO::PARAM_INT);
        $stmt->bindParam(':precio_venta', $libro->precio_venta, PDO::PARAM_STR); // DECIMAL
        $stmt->bindParam(':fecha_edicion', $libro->fecha_edicion, PDO::PARAM_STR); // DATE
        $stmt->bindParam(':isbn', $libro->isbn, PDO::PARAM_STR, 13);
        $stmt->bindParam(':id', $libro->id, PDO::PARAM_INT);

        // Ejecutar la actualización de la tabla 'libros'
        $stmt->execute();
        
        // Eliminar temas existentes
        $this->delete_temas_libro($libro->id);

        // Insertar temas nuevos (el array está en $libro->temas)
        if (!empty($libro->temas)) {
            $this->insert_temas_libro($libro->id, $libro->temas);
        }
        
        return true; 

        } catch (PDOException $e) {

            // Manejo del error
            $this->handleError($e); 
        }
    }

    /*
        Método: delete($id)
        Descripción: Elimina un libro de la base de datos ges$geslibros
        Parámetros: 
            - $id: id del libro a eliminar
        Devuelve:
            - true si la eliminación fue exitosa
            - false en caso de error
    */
    public function delete(int $id) {
        try {
        // Consulta SQL para eliminar un libro
        $sql = "DELETE FROM libros WHERE id = :id LIMIT 1";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        return $stmt->execute();

        } catch (PDOException $e) {

           // Manejo del error
           $this->handleError($e); 
        }
    }

    /*
    Método: delete_temas_libro(int $libro_id)
    Descripción: Elimina los temas existentes para un libro.
    */
    public function delete_temas_libro(int $libro_id) {
        try {
            $sql = "DELETE FROM libros_temas WHERE libro_id = :libro_id";
            $geslibros = $this->db->connect();
            $stmt = $geslibros->prepare($sql);
            $stmt->bindParam(':libro_id', $libro_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
    Método: insert_temas_libro(int $libro_id, array $temas_seleccionados)
    Descripción: Inserta los temas nuevos para un libro.
    */
    public function insert_temas_libro(int $libro_id, array $temas_seleccionados) {
        try {
            $sql = "INSERT INTO libros_temas (libro_id, tema_id) VALUES (:libro_id, :tema_id)";
            $geslibros = $this->db->connect();
            $stmt = $geslibros->prepare($sql);

            foreach ($temas_seleccionados as $tema_id) {
                $stmt->bindParam(':libro_id', $libro_id, PDO::PARAM_INT);
                $stmt->bindParam(':tema_id', $tema_id, PDO::PARAM_INT);
                // Ejecutar dentro del bucle
                $stmt->execute(); 
            }
            return true;
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: search($term)
        Descripción: Busca libros en la base de datos ges$geslibros que coincidan con el término de búsqueda
        Parámetros: 
            - $term: término de búsqueda
        Devuelve:
            - objeto PDOStatement con los resultados de la búsqueda
    */
    public function search(string $term) {

        try {
        // Consulta SQL para buscar libros
        $sql = "SELECT 
                    l.id,
                    l.titulo,
                    a.nombre AS autor,
                    e.nombre AS editorial,
                    GROUP_CONCAT(t.tema ORDER BY t.tema SEPARATOR ', ') AS generos,
                    l.stock,
                    l.precio_venta precio
                FROM libros AS l
                LEFT JOIN autores AS a       ON l.autor_id = a.id
                LEFT JOIN editoriales AS e   ON l.editorial_id = e.id
                LEFT JOIN libros_temas AS lt ON l.id = lt.libro_id
                LEFT JOIN temas AS t         ON lt.tema_id = t.id
                WHERE 
                    concat_ws(' ',
                        l.titulo, 
                        a.nombre, 
                        e.nombre, 
                        t.tema
                    ) LIKE :term
                GROUP BY l.id
                ORDER BY l.id ASC";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Vincular los parámetros con comodines para LIKE
        $like_term = '%' . $term . '%';
        $stmt->bindParam(':term', $like_term, PDO::PARAM_STR);

        // Establecer modo de obtención de datos  fectch
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Ejecutar la consulta
        $stmt->execute();
        
        // Devuelvo objeto de la clase PDOStatement o array con los datos
        return $stmt;

        } catch (PDOException $e) {

            // Manejo del error
            $this->handleError($e); 
        }
    }

    /*
        Método: order($criterio)
        Descripción: Ordena la lista de libros por un criterio
        Parámetros:
            - $criterio: campo por el que se ordena la lista
                1: id
                2: titulo
                3: editorial_id
                4: precio_venta
                5: generos
                6: edad
                7: curso
        Devuelve:
            - objeto PDOStatement con los resultados ordenados
    */
    public function order(int $criterio) {
        try {
        // Mapeo de criterios a columnas SQL
        $column_map = [
            1 => 'l.id',
            2 => 'l.titulo',
            3 => 'a.nombre',
            4 => 'e.nombre',
            5 => 'generos',
            6 => 'l.stock',
            7 => 'l.precio_venta'
        ];

        // Verificar si el criterio es válido
        if (!array_key_exists($criterio, $column_map)) {
            throw new InvalidArgumentException("Criterio de ordenación inválido.");
        }

        $order_by_column = $column_map[$criterio];

        // Consulta SQL para ordenar libros
        $sql = "SELECT 
                    l.id,
                    l.titulo,
                    a.nombre AS autor,
                    e.nombre AS editorial,
                    GROUP_CONCAT(t.tema ORDER BY t.tema SEPARATOR ', ') AS generos,
                    l.stock,
                    l.precio_venta precio
                FROM libros AS l
                LEFT JOIN autores AS a       ON l.autor_id = a.id
                LEFT JOIN editoriales AS e   ON l.editorial_id = e.id
                LEFT JOIN libros_temas AS lt ON l.id = lt.libro_id
                LEFT JOIN temas AS t         ON lt.tema_id = t.id
                GROUP BY l.id
                ORDER BY $order_by_column ASC";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Establecer modo de obtención de datos  fectch
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Ejecutar la consulta
        $stmt->execute();
        
        // Devuelvo objeto de la clase PDOStatement o array con los datos
        return $stmt;

        } catch (PDOException $e) {

            // Manejo del error
            $this->handleError($e); 
        }
    }

    // Valida si el autor existe en la tabla autores
    public function validateAutor($id) {
        $sql = "SELECT id FROM autores WHERE id = :id LIMIT 1";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Valida si la editorial existe
    public function validateEditorial($id) {
        $sql = "SELECT id FROM editoriales WHERE id = :id LIMIT 1";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Valida si el ISBN es único
    public function validateUniqueIsbn($isbn) {
        $sql = "SELECT id FROM libros WHERE isbn = :isbn LIMIT 1";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute(['isbn' => $isbn]);
        return $stmt->rowCount() == 0; // True si no existe
    }

    // valida si el isbn es unico para edición (excluyendo el libro actual)
    public function validateUniqueIsbnUpdate($isbn, $id) {
        $sql = "SELECT id FROM libros WHERE isbn = :isbn AND id != :id LIMIT 1";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute(['isbn' => $isbn, 'id' => $id]);
        return $stmt->rowCount() == 0; // True si no existe
    }

    /*
        metodo: validate_id_libro_exists($id)
        Descripción: valida que el libro exista en la tabla libros
        Parámetros: 
            - $libro_id
        Devuelve:
            - Falso - libro no existente
            - Verdadero - libro existente
    */
    public function validate_id_libro_exists($libro_id) {
        try {
        // Generamos select 
        $sql = "SELECT id FROM libros WHERE id = :libro_id";
        // Conectar con la base de datos
        $fp = $this->db->connect();
        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $fp->prepare($sql);
        // Vincular los parámetros
        $stmt->bindParam(':libro_id', $libro_id, PDO::PARAM_INT);
        // Ejecutamos sql
        $stmt->execute();

        // Validamos
        if ($stmt->rowCount() > 0) {
            return TRUE;
        }

        return FALSE; 

        } catch (PDOException $e) {
            // Manejo del error
            $this->handleError($e); 

        }
    }


    /*
        Método: handleError
        Descripción: Maneja los errores de la base de datos
    */

    private function handleError(PDOException $e)
    {
        // Incluir y cargar el controlador de errores
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';
        
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $mensaje = $e->getMessage() . " en la línea " . $e->getLine() . " del archivo " . $e->getFile();
            $controller = new Errores('DE BASE DE DATOS', 'Mensaje de Error: ', $mensaje);
            
        } else {
            // Fallback en caso de que el controlador de errores no exista
            echo "Error crítico: " . $e->getMessage();
            exit();
        }
    }

   }

?>