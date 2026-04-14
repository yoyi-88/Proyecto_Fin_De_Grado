<?php

/*
    perfil.model.php

    Modelo del controlador perfil

    Métodos:


*/

class accountModel extends Model
{
    /*

        método: getUserId(int $id)

        descripción: obtiene un usuario por id

        @param: id del usuario

    */
    public function getUserId(int $id)
    {

        try {

            // sentencia sql
            $sql = "SELECT name, email, password FROM users WHERE id = :id LIMIT 1";

            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // Tipo de fetch
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            // vinculamos parámetros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // ejecutamos
            $stmt->execute();

            // Devuelvo objeto usuario
            return $stmt->fetch();

        } catch (PDOException $e) {

            // Manejo del error
            $this->handleError($e);
        }
    }

    /*

        método: update($name, $email, $id)

        descripción: actualiza los datos del usuario

        @param: 

            - name: nombre del usuario
            - email: email del usuario

    */
    public function update($name, $email, $id)
    {

        try {

            // sentencia sql
            $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";

            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':name', $name, PDO::PARAM_STR, 50);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // ejecutamos
            $stmt->execute();

            // Devuelvo objeto usuario
            return $stmt->rowCount();

        } catch (PDOException $e) {

            // error base de datos
            $this->handleError($e);
        }
    }

    /*
        método: validateUniqueName()

        Valida el name de usuario, devuelve verdadero si el  nombre no existe en la base de datos


        @param: name del usuario
    */
    public function validateUniqueName($name)
    {

        try {

            // sentencia sql
            $sql = "SELECT * FROM users WHERE name = :name";


            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':name', $name, PDO::PARAM_STR, 50);

            // ejecutamos
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return FALSE;
            }

            return TRUE;

        } catch (PDOException $e) {

            // error base de datos
            $this->handleError($e);
        }
    }

    /*
        método: validateUniqueEmail()

        descripción: comprueba si un email ya existe en la base de datos, 
        devuelve verdadero si es un valor único

        @param: email del usuario

    */
    public function validateUniqueEmail($email)
    {

        try {

            // sentencia sql
            $sql = "SELECT * FROM users WHERE email = :email";

            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);

            // ejecutamos
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return FALSE;
            }

            return TRUE;

        } catch (PDOException $e) {

            // error base de datos
            $this->handleError($e);

        }
    }

    /*
        método: update_pass($password, $id)

        descripción: actualiza la contraseña del usuario

        @param: 

            - password: contraseña del usuario
            - id: id del usuario

    */
    public function updatePass($password, $id)
    {

        try {

            // encriptar password
            $password = password_hash($password, PASSWORD_DEFAULT);

            // sentencia sql
            $sql = "UPDATE users SET password = :password WHERE id = :id";

            // conectamos con la base de datos
            $fp = $this->db->connect();

            // ejecuto prepare
            $stmt = $fp->prepare($sql);

            // vinculamos parámetros
            $stmt->bindParam(':password', $password, PDO::PARAM_STR, 255);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // ejecutamos
            $stmt->execute();

            // Devuelvo objeto usuario
            return $stmt->rowCount();

        } catch (PDOException $e) {

            // error base de datos
            $this->handleError($e);

        }
    }

    /*
        método: delete($id)
        descripción: elimina definitivamente un usuario y todos sus registros asociados 
        (citas y roles) utilizando una transacción.
        @param: - id: id del usuario
    */
    public function delete($id)
    {
        try {
            // conectamos con la base de datos
            $fp = $this->db->connect();

            // iniciamos transacción
            $fp->beginTransaction();

            // Borramos las reservas
            $sqlCitas = "DELETE FROM citas WHERE user_id = :id";
            $stmtCitas = $fp->prepare($sqlCitas);
            $stmtCitas->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCitas->execute();

            // Borramos los roles
            $sqlRoles = "DELETE FROM roles_users WHERE user_id = :id";
            $stmtRoles = $fp->prepare($sqlRoles);
            $stmtRoles->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtRoles->execute();

            // Finalmente borramos el usuario
            $sqlUser = "DELETE FROM users WHERE id = :id";
            $stmtUser = $fp->prepare($sqlUser);
            $stmtUser->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUser->execute();

            // Guardamos el número de filas afectadas del usuario
            $rowCount = $stmtUser->rowCount();

            // Confirmamos los cambios
            $fp->commit();

            return $rowCount;

        } catch (PDOException $e) {
            // Si algo falla, deshacemos todo
            if (isset($fp) && $fp->inTransaction()) {
                $fp->rollBack();
            }

            // error base de datos
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

        } else {
            // Fallback en caso de que el controlador de errores no exista
            echo "Error crítico: " . $e->getMessage();
            exit();
        }
    }





}
