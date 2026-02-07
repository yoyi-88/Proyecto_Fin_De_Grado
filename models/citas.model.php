<?php

class citasModel extends Model {

    /*
        Método: get_all()
        Descripción: Para el ADMIN. Trae todas las citas con nombre de cliente y menú.
    */
    public function get_all() {
        try {
            $sql = "
                SELECT 
                    c.*, 
                    u.name as cliente_nombre, 
                    m.nombre as menu_nombre,
                    m.precio as menu_precio
                FROM citas c
                INNER JOIN users u ON c.user_id = u.id
                INNER JOIN menus m ON c.menu_id = m.id
                ORDER BY c.fecha DESC, c.hora ASC
            ";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'class_cita');
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) { $this->handleError($e); }
    }

    /*
        Método: get_by_user($user_id)
        Descripción: Para el CLIENTE. Trae solo sus citas.
    */
    public function get_by_user($user_id) {
        try {
            $sql = "
                SELECT 
                    c.*, 
                    u.name as cliente_nombre, 
                    m.nombre as menu_nombre,
                    m.precio as menu_precio
                FROM citas c
                INNER JOIN users u ON c.user_id = u.id
                INNER JOIN menus m ON c.menu_id = m.id
                WHERE c.user_id = :user_id
                ORDER BY c.fecha DESC
            ";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'class_cita');
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) { $this->handleError($e); }
    }

    /*
        Método: get_menus_disponibles()
        Descripción: Llena el select del formulario
    */
    public function get_menus_disponibles() {
        try {
            $sql = "SELECT id, nombre, precio FROM menus ORDER BY nombre ASC";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) { $this->handleError($e); }
    }

    /*
        Método: create($cita)
    */
    public function create($cita) {
        try {
            $sql = "INSERT INTO citas (fecha, hora, estado, user_id, menu_id) 
                    VALUES (:fecha, :hora, 'Pendiente', :user_id, :menu_id)";
            
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':fecha', $cita->fecha);
            $stmt->bindParam(':hora', $cita->hora);
            $stmt->bindParam(':user_id', $cita->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':menu_id', $cita->menu_id, PDO::PARAM_INT);

            $stmt->execute();
            return $conn->lastInsertId();

        } catch (PDOException $e) { $this->handleError($e); }
    }

    /*
        Método: read($id)
        Descripción: Obtener una cita específica (para editar)
    */
    public function read($id) {
        try {
            $sql = "SELECT * FROM citas WHERE id = :id LIMIT 1";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'class_cita');
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) { $this->handleError($e); }
    }

    /*
        Método: update_estado($id, $estado)
    */
    public function update_estado($id, $estado) {
        try {
            $sql = "UPDATE citas SET estado = :estado WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) { $this->handleError($e); }
    }

    private function handleError(PDOException $e) {
        // Tu controlador de errores estándar
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $mensaje = $e->getMessage();
            new Errores('DE BASE DE DATOS', 'Error: ', $mensaje);
        } else {
            exit("Error crítico: " . $e->getMessage());
        }
    }
}
?>