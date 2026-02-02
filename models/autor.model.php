<?php
/*
    Modelo:  autorModel
    Descripción: Modelo para gestionar los datos de los autores
*/
   

class autorModel extends Model {

    
    /*
        Método: get()
        Descripción: Obtiene todos los libros de la base de datos geslibros
    */

    public function get() {

        try {
        // Consulta SQL para obtener todos los libros
        $sql = "
            SELECT 
                id,
                nombre,
                nacionalidad,
                date_format(fecha_nac, '%d/%m/%Y') as fecha_nac,
                email,
                premios
            FROM autores
            ORDER BY 1
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
        Método: create($autor)
        Descripción: Inserta un nuevo autor en la base de datos geslibros
        Parámetros: 
            - $autor: objeto de la clase class_autor con los datos del autor a insertar
        Devuelve:
            - id del nuevo autor insertado
            - falso en caso de error
    */
    public function create($autor) {

        try {
        // Consulta SQL para insertar un nuevo autor
        $sql = "INSERT INTO autores 
                (nombre, nacionalidad, fecha_nac, email, premios) 
                VALUES 
                (:nombre, :nacionalidad, :fecha_nac, :email, :premios)";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $autor->nombre, PDO::PARAM_STR, 30);
        $stmt->bindParam(':nacionalidad', $autor->nacionalidad, PDO::PARAM_STR, 10);
        $stmt->bindParam(':fecha_nac', $autor->fecha_nac, PDO::PARAM_STR, 50);
        $stmt->bindParam(':email', $autor->email, PDO::PARAM_STR, 9);
        $stmt->bindParam(':premios', $autor->premios, PDO::PARAM_STR, 100);

        // Ejecutar la consulta
        $stmt->execute();

        // Devuelvo el id del nuevo autor insertado
        return $geslibros->lastInsertId();

        } catch (PDOException $e) {

           // Manejo del error
           $this->handleError($e); 
        }
    }

    /*
        Método: read()
        Descripción: obtiene los detalles de un autor devolviendo un objeto de la clase  class_autor
        Paráemtros: 
            - $id: id del autor

        Devuelve:
            - $autor: objeto de la clase class_autor
    */
    public function read(int $id){
        try {

            $sql = "SELECT 
                    id, nombre, nacionalidad, fecha_nac, email, premios
                    FROM autores WHERE id = :id
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
        Método: read_show()
        Descripción: obtiene los detalles de un autor devolviendo un objeto con los detalles del autor
        incluido el nombre del curso
        Paráemtros: 
            - $id: id del autor

        Devuelve:
            - $autor: objeto de la clase autor con los detalles del autor
    */
    public function read_show(int $id){
        try {

            $sql = "SELECT 
                    id, nombre, nacionalidad, fecha_nac, email, premios
                    FROM autores 
                    WHERE id = :id
                    LIMIT 1
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
        Método: update($autor)
        Descripción: Actualiza los datos de un autor en la base de datos geslibros
        Parámetros: 
            - $autor: objeto de la clase class_autor con los datos del autor a actualizar
            - $id: id del autor a actualizar
        Devuelve:
            - true si la actualización fue exitosa
            - false en caso de error
    */
    public function update($autor , int $id) {

        try {
        // Consulta SQL para actualizar un autor
        $sql = "UPDATE autores SET 
                    nombre = :nombre, 
                    nacionalidad = :nacionalidad, 
                    fecha_nac = :fecha_nac, 
                    email = :email, 
                    premios = :premios
                WHERE id = :id";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $autor->nombre, PDO::PARAM_STR, 30);
        $stmt->bindParam(':nacionalidad', $autor->nacionalidad, PDO::PARAM_STR, 10);
        $stmt->bindParam(':fecha_nac', $autor->fecha_nac, PDO::PARAM_STR, 10);
        $stmt->bindParam(':email', $autor->email, PDO::PARAM_STR, 9);
        $stmt->bindParam(':premios', $autor->premios, PDO::PARAM_STR, 100);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        return $stmt->execute();

        } catch (PDOException $e) {

           // Manejo del error
           $this->handleError($e); 
        }
    }

    /*
        Método: delete($id)
        Descripción: Elimina un autor de la base de datos geslibros
        Parámetros: 
            - $id: id del autor a eliminar
        Devuelve:
            - true si la eliminación fue exitosa
            - false en caso de error
    */
    public function delete(int $id) {
        try {
        // Consulta SQL para eliminar un autor
        $sql = "DELETE FROM autores WHERE id = :id LIMIT 1";

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
        Método: search($term)
        Descripción: Busca autores en la base de datos geslibros que coincidan con el término de búsqueda
        Parámetros: 
            - $term: término de búsqueda
        Devuelve:
            - objeto PDOStatement con los resultados de la búsqueda
    */
    public function search(string $term) {

        try {
        // Consulta SQL para buscar autores
        $sql = "SELECT 
                    id, 
                    nombre, 
                    nacionalidad, 
                    date_format(fecha_nac, '%d/%m/%Y') as fecha_nac, 
                    email, 
                    premios 
                FROM autores
                WHERE 
                    concat_ws(' ',
                        nombre, 
                        nacionalidad, 
                        email, 
                        premios
                    ) LIKE :term
                ORDER BY 1";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Vincular los parámetros
        $likeTerm = '%' . $term . '%';
        $stmt->bindParam(':term', $likeTerm, PDO::PARAM_STR);

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
        Descripción: Ordena la lista de autores por un criterio
        Parámetros:
            - $criterio: campo por el que se ordena la lista
                1: id
                2: nombre
                3: email
                4: nacionalidad
                5: dni
                6: edad
                7: curso
        Devuelve:
            - objeto PDOStatement con los resultados ordenados
    */
    public function order(int $criterio) {

        try {

        // Consulta SQL para ordenar autores
        $sql = "SELECT 
                    id,
                    nombre,
                    nacionalidad,
                    date_format(fecha_nac, '%d/%m/%Y') as fecha_nac,
                    email,
                    premios
                FROM autores
                ORDER BY :criterio";

        // Conectar con la base de datos
        $geslibros = $this->db->connect();

        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':criterio', $criterio, PDO::PARAM_INT);

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