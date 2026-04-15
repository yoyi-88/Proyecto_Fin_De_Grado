<?php
/*
    Modelo:  auth.model.php
    Descripción: Modelo para gestionar los datos del controlador auth
*/


class authModel extends Model
{

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
    public function get_user_email($email)
    {
        try {
            // Generamos select 
            $sql = "SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1";
            // Conectar con la base de datos
            $dmcalt = $this->db->connect();
            // Preparar la consulta obteniendo el objeto PDOStatement
            $stmt = $dmcalt->prepare($sql);
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
        Método: get_user_by_id($id)
        Descripción: Obtiene los detalles de un usuario a partir de su ID
    */
    public function get_user_by_id($id)
    {
        try {
            // Generamos select buscando por ID
            $sql = "SELECT id, name, email, password FROM users WHERE id = :id LIMIT 1";
            
            // Conectar con la base de datos
            $dmcalt = $this->db->connect();
            
            // Preparar la consulta obteniendo el objeto PDOStatement
            $stmt = $dmcalt->prepare($sql);
            
            // Tipo fetch
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            
            // Vincular los parámetros (usamos PARAM_INT porque es un ID numérico)
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
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
            $dmcalt = $this->db->connect();

            // ejecuto prepare
            $stmt = $dmcalt->prepare($sql);

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
            $dmcalt = $this->db->connect();

            // ejecuto prepare
            $stmt = $dmcalt->prepare($sql);

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
    public function validate_exists_email($email)
    {
        try {
            // Generamos select 
            $sql = "SELECT email FROM users WHERE email = :email";
            // Conectar con la base de datos
            $dmcalt = $this->db->connect();
            // Preparar la consulta obteniendo el objeto PDOStatement
            $stmt = $dmcalt->prepare($sql);
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
        Método: validate_exists_name($name)
        Descripción: valida name existe en la tabla user
        Parámetros: 
            - $name
        Devuelve:
            - Verdadero - name existente
            - Falso - si no existe
    */
    public function validate_exists_name($name)
    {
        try {

            // sentencia sql
            $sql = "SELECT * FROM users WHERE name = :name";


            // conectamos con la base de datos
            $dmcalt = $this->db->connect();

            // ejecuto prepare
            $stmt = $dmcalt->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':name', $name, PDO::PARAM_STR, 50);

            // ejecutamos
            $stmt->execute();

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
        Método: create()
        Descripción: Crea nuevo usuario en la tabla user y le asigna rol de usuario registrado (id = 3)
        Observaciones: Realizar en una transacción puesto que esta operación afecta 
            a más de una tabla de la base de datos dmcalt: users y roles_users
    */
    public function create($name, $email, $password)
    {

        try {
            // Preparación previa (fuera de la transacción)
            $password_enc = password_hash($password, PASSWORD_DEFAULT);
            $role_id = 3; // Rol por defecto

            // Conectar e iniciar transacción
            $db = $this->db->connect();
            $db->beginTransaction();

            // Primera inserción: Usuario
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR, 50);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
            $stmt->bindParam(':password', $password_enc, PDO::PARAM_STR, 255);
            $stmt->execute();

            // Obtener el ID generado
            $ultimo_id = $db->lastInsertId();

            // Segunda inserción: Rol (reutilizamos $sql o usamos una nueva)
            $sql = "INSERT INTO roles_users (user_id, role_id) VALUES (:user_id, :role_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $ultimo_id, PDO::PARAM_INT);
            $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
            $stmt->execute();

            // Confirmar todo
            $db->commit();

            return $ultimo_id;

        } catch (PDOException $e) {
            // SEGURIDAD: Solo hacemos rollback si la conexión se llegó a crear
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->handleError($e);
        }
    }

    /*
        Guarda el token de recuperación y su fecha de caducidad
    */
    public function setResetToken($email, $token, $expires_at)
    {
        try {
            $sql = "UPDATE users SET reset_token = :token, reset_token_expires_at = :expires_at WHERE email = :email";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /*
        Verifica si el token es válido y no ha caducado. Devuelve el ID del usuario si es correcto.
    */
    public function verifyResetToken($token)
    {
        try {
            // Comprobamos que el token coincida y que la fecha de caducidad sea MAYOR que la fecha/hora actual
            $sql = "SELECT id FROM users WHERE reset_token = :token AND reset_token_expires_at > NOW() LIMIT 1";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user ? $user->id : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /*
        Actualiza la contraseña y borra el token para que no se pueda volver a usar
    */
    public function updatePasswordWithToken($id, $hashed_password)
    {
        try {
            $sql = "UPDATE users SET password = :password, reset_token = NULL, reset_token_expires_at = NULL WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // models/auth.model.php

    /*
        método: saveRememberToken($user_id, $token, $expires_at)
        descripción: guarda un token seguro para el autologin
        @param: - user_id: id del usuario
                - token: token seguro (hash)
                - expires_at: fecha de expiración (MySQL datetime string)
    */
    public function saveRememberToken($user_id, $token, $expires_at)
    {
        try {
            // sentencia sql: usar REPLACE para sobrescribir tokens viejos si existen
            $sql = "REPLACE INTO remember_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)";

            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR, 255);
            $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_STR);

            // ejecutamos
            $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        método: getRememberToken($token)
        descripción: obtiene la info de un token para autologin
        @param: token seguro
    */
    public function getRememberToken($token)
    {
        try {
            // sentencia sql: buscar token que no haya expirado
            $sql = "SELECT * FROM remember_tokens WHERE token = :token AND expires_at > NOW() LIMIT 1";

            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':token', $token, PDO::PARAM_STR, 255);

            // ejecutamos
            $stmt->execute();

            // Tipo de fetch
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            // Devuelvo el token encontrado (o null)
            return $stmt->fetch();

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        método: deleteRememberToken($user_id)
        descripción: elimina el token de un usuario (al hacer logout)
        @param: user_id
    */
    public function deleteRememberToken($user_id)
    {
        try {
            // sentencia sql
            $sql = "DELETE FROM remember_tokens WHERE user_id = :user_id";

            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // ejecutamos
            $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }


    public function handleError(PDOException $e)
    {
        // Incluir y cargar el controlador de errores
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';

        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $mensaje = $e->getMessage() . " en la línea " . $e->getLine() . " del archivo " . $e->getFile();
            $controller = new Errores('DE BASE DE DATOS', 'Mensaje de Error: ', $mensaje);
            exit();

        } else {
            // Fallback en caso de que el controlador de errores no exista
            echo "Error crítico: " . $e->getMessage();
            exit();
        }
    }



}

?>