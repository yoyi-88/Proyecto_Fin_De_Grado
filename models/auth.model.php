<?php
/*
    Modelo:  auth.model.php
    Descripción: Modelo para gestionar los datos del controlador auth
*/
   

class authModel extends Model {

    /*
        Método: get_user_emial($email)
        Descripción: obtiene los detalles  de un usuario a partir del email
        Parámetros: 
            - email
        Devuelve:
            - Objeto de la clase  user
                - id
                - name
                - email
                - password
            - False. Si el email no corresponde a nigún usuario
    */
    public function get_user_email($email) {
        try {
        // Generamos select 
        $sql = "SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1";
        // Conectar con la base de datos
        $geslibros = $this->db->connect();
        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);
        // Tipo fetch
         $stmt->setFetchMode(PDO::FETCH_OBJ);
        // Vincular los parámetros
        $stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
        // Ejecutamos sql
        $stmt->execute();
        // Devolvemos el objeto o falso
        return $stmt->fetch();
        
        } catch (PDOException $e) {
            // Manejo del error
            $this->handleError($e); 

        }
    }

    /*
        Método: get_id_role_user($user_id)
        Descripción: obtiene el id del perfil de un usuario
        @param: 
            - $user_id: id del usuario
        Devuelve:
            - id del rol del usuario
        Observaciones:
            - Sólo va a devolver el primer rol asignado en la tabla roles_users por simplicidad

    */
    public function get_id_role_user(int $user_id)
    {

        try {

            // sentencia sql
            $sql = "SELECT role_id FROM roles_users WHERE user_id = :user_id LIMIT 1"; 

            // conectamos con la base de datos
            $geslibros = $this->db->connect();

            // ejecuto prepare
            $stmt = $geslibros->prepare($sql);

            // Tipo de fetch
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            // vinculamos parámetros
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // ejecutamos
            $stmt->execute();

            // Devuelvo el id del rol
            return $stmt->fetch()->role_id;
            

        } catch (PDOException $e) {

            $this->handleError($e); 

        }
    }

/*
        Método: get_name_role_user($role_id)
        Descripción: obtiene el nombre del perfil de un usuario
        @param: 
            - $role_id: id del rol
        Devuelve:
            - nombre del rol del usuario
        Observaciones:
            - Sólo va a devolver el primer rol asignado en la tabla roles_users por simplicidad

    */

    public function get_name_role_user(int $role_id)
    {

        try {

            // sentencia sql
            $sql = "SELECT name FROM roles WHERE id = :role_id LIMIT 1"; 

            // conectamos con la base de datos
            $geslibros = $this->db->connect();

            // ejecuto prepare
            $stmt = $geslibros->prepare($sql);

            // Tipo de fetch
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            // vinculamos parámetros
            $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

            // ejecutamos
            $stmt->execute();

            // Devuelve el nombre del rol
            return $stmt->fetch()->name;

        } catch (PDOException $e) {

            $this->handleError($e); 

        }
    }

    /*
        Método: validate_exists_email($email)
        Descripción: valida email existe en la tabla user
        Parámetros: 
            - $email
        Devuelve:
            - Verdadero - email existente
            - Falso - si no existe
    */
    public function validate_exists_email($email) {
        try {
        // Generamos select 
        $sql = "SELECT email FROM users WHERE email = :email";
        // Conectar con la base de datos
        $geslibros = $this->db->connect();
        // Preparar la consulta obteniendo el objeto PDOStatement
        $stmt = $geslibros->prepare($sql);
        // Vincular los parámetros
        $stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
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
        Método: create($name, $email, $password)
        Descripción: Registra un nuevo usuario y le asigna el rol de usuario (ID 3)
        Parámetros: $name, $email, $password (ya hasheado)
    */
    public function create($name, $email, $password) {
        try {
            $geslibros = $this->db->connect();

            // Iniciamos una transacción
            $geslibros->beginTransaction();

            // Insertar el usuario en la tabla 'users'
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $geslibros->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR, 50);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR, 60);
            $stmt->execute();

            // Obtenemos el ID del usuario recién insertado
            $last_id = $geslibros->lastInsertId();

            // Asignar rol por defecto (suponiendo que ID 3 es 'usuario')
            // Puedes cambiar el 3 por el ID que corresponda en tu tabla 'roles'
            $role_id = 3; 
            $sql_role = "INSERT INTO roles_users (user_id, role_id) VALUES (:user_id, :role_id)";
            $stmt_role = $geslibros->prepare($sql_role);
            $stmt_role->bindParam(':user_id', $last_id, PDO::PARAM_INT);
            $stmt_role->bindParam(':role_id', $role_id, PDO::PARAM_INT);
            $stmt_role->execute();

            // Si todo ha ido bien, confirmamos los cambios
            $geslibros->commit();

            return TRUE;

        } catch (PDOException $e) {
            // Si algo falla, revertimos los cambios para no dejar datos inconsistentes
            $geslibros->rollBack();
            $this->handleError($e);
        }
    }

    public function get_user_id($id) {
        try {
            $sql = "SELECT id, name, email FROM users WHERE id = :id LIMIT 1";
            $geslibros = $this->db->connect();
            $stmt = $geslibros->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    // Verifica si un email ya existe excluyendo al usuario actual (para edición)
    public function validate_unique_email($id, $email) {
        $sql = "SELECT id FROM users WHERE email = :email AND id <> :id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update_profile($id, $name, $email) {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function get_password_by_id($id) {
        $sql = "SELECT password FROM users WHERE id = :id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update_password($id, $password) {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    

    public function handleError(PDOException $e) {
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