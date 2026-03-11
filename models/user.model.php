<?php

class userModel extends Model {

    /*
        Método: get()
        Descripción: Obtiene todos los usuarios con sus roles
    */
    public function get() {
        try {
            $sql = "
                SELECT 
                    u.id,
                    u.name,
                    u.email,
                    r.name as role_name,
                    u.created_at
                FROM users AS u
                LEFT JOIN roles_users AS ru ON u.id = ru.user_id
                LEFT JOIN roles AS r ON ru.role_id = r.id
                ORDER BY u.id ASC
            ";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: get_roles()
        Descripción: Obtiene lista de roles para el select (Administrador, Editor, etc)
    */
    public function get_roles() {
        try {
            $sql = "SELECT id, name FROM roles ORDER BY id ASC";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: create()
        Descripción: Crea usuario y asigna rol en roles_users
    */
    public function create($user) {
        try {
            $conn = $this->db->connect();
            
            // Iniciamos transacción
            $conn->beginTransaction(); 

            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $user->name, PDO::PARAM_STR, 50);
            $stmt->bindParam(':email', $user->email, PDO::PARAM_STR, 50);
            $stmt->bindParam(':password', $user->password, PDO::PARAM_STR, 60);
            $stmt->execute();

            $user_id = $conn->lastInsertId();

            // Asignar rol pasándole la MISMA conexión
            $this->assign_role($user_id, $user->role_id, $conn);

            // Si todo correcto, confirmamos cambios
            $conn->commit();

            return $user_id;

        } catch (PDOException $e) {
            // Si algo falla, revertimos cambios
            if (isset($conn)) {
                $conn->rollBack();
            }
            $this->handleError($e);
        }
    }

    /*
        Método: read()
        Descripción: Obtiene datos para edición
    */
    public function read($id) {
        try {
            $sql = "
                SELECT 
                    u.id, u.name, u.email, u.password, ru.role_id 
                FROM users u
                LEFT JOIN roles_users ru ON u.id = ru.user_id
                WHERE u.id = :id LIMIT 1
            ";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: update()
        Descripción: Actualiza usuario. Si password es null, no la actualiza.
    */
    public function update($user) {
        try {
            $conn = $this->db->connect();
            
            // Iniciamos transacción
            $conn->beginTransaction();

            if (!empty($user->password)) {
                $sql = "UPDATE users SET name = :name, email = :email, password = :password, update_at = CURRENT_TIMESTAMP WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
            } else {
                $sql = "UPDATE users SET name = :name, email = :email, update_at = CURRENT_TIMESTAMP WHERE id = :id";
                $stmt = $conn->prepare($sql);
            }

            $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
            $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);
            $stmt->execute();

            // Pasamos la conexión a assign_role
            $this->assign_role($user->id, $user->role_id, $conn);

            // Si todo correcto, confirmamos cambios
            $conn->commit();
            return true;
            
        } catch (PDOException $e) {
            // Si algo falla, revertimos cambios
            if (isset($conn)) {
                $conn->rollBack();
            }
            $this->handleError($e);
        }
    }

    /*
        Método: delete()
    */
    public function delete($id) {
        try {
            // Eliminar de roles_users primero (si no hay CASCADE en la DB)
            $sql_roles = "DELETE FROM roles_users WHERE user_id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql_roles);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Eliminar usuario
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: assign_role
        Asigna un rol a un usuario. Primero borra cualquier rol asignado, luego inserta el nuevo (si no es null).
         Recibe la conexión como parámetro para asegurar que ambas operaciones (borrar e insertar) formen parte de la misma transacción.
    */
    private function assign_role($user_id, $role_id, $conn) {
        
        $sql_del = "DELETE FROM roles_users WHERE user_id = :user_id";
        $stmt_del = $conn->prepare($sql_del);
        $stmt_del->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_del->execute();

        if ($role_id) {
            $sql_in = "INSERT INTO roles_users (user_id, role_id) VALUES (:user_id, :role_id)";
            $stmt_in = $conn->prepare($sql_in);
            $stmt_in->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_in->bindParam(':role_id', $role_id, PDO::PARAM_INT);
            $stmt_in->execute();
        }
    }

    // Validaciones
    public function validateUniqueEmail($email, $user_id = null) {
        try {
            $sql = "SELECT id FROM users WHERE email = :email";
            if ($user_id) {
                $sql .= " AND id != :id";
            }
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            if ($user_id) $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() == 0;
        } catch (PDOException $e) {
            $this->handleError($e);
            return false; // En caso de error, consideramos el email como no único para evitar duplicados
        }
        
    }

    public function validateRole($role_id) {
        try {
            $sql = "SELECT id FROM roles WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $role_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->handleError($e);
            return false; // En caso de error, consideramos el rol como inválido
        }
        
    }

    public function validateIdUser($id) {
        try {
            $sql = "SELECT id FROM users WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->handleError($e);
            return false; // En caso de error, consideramos el ID como inválido
        }
        
    }
    
    // Métodos order() y search() siguen la misma lógica que en Libros pero con campos de usuario
    public function order(int $criterio) {
        try {
            $column_map = [
                1 => 'u.id',
                2 => 'u.name',
                3 => 'u.email',
                4 => 'role_name',
                5 => 'u.created_at'
            ];
            $order_col = $column_map[$criterio] ?? 'u.id';

            $sql = "
                SELECT u.id, u.name, u.email, r.name as role_name, u.created_at
                FROM users u
                LEFT JOIN roles_users ru ON u.id = ru.user_id
                LEFT JOIN roles r ON ru.role_id = r.id
                ORDER BY $order_col ASC
            ";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) { $this->handleError($e); }
    }

    public function search($term) {
        try {
            $sql = "
                SELECT u.id, u.name, u.email, r.name as role_name, u.created_at
                FROM users u
                LEFT JOIN roles_users ru ON u.id = ru.user_id
                LEFT JOIN roles r ON ru.role_id = r.id
                WHERE CONCAT_WS(' ', u.name, u.email, r.name) LIKE :term
                ORDER BY u.id ASC
            ";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $like = "%" . $term . "%";
            $stmt->bindParam(':term', $like, PDO::PARAM_STR);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) { $this->handleError($e); }
    }

    private function handleError(PDOException $e) {
        // Tu lógica de errores existente
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $mensaje = $e->getMessage();
            new Errores('DE BASE DE DATOS', 'Error: ', $mensaje);
        } else {
            die('Error crítico: ' . $e->getMessage());
        }
    }
}
?>