<?php

class menuModel extends Model {

    /*
        Método: get()
        Descripción: Obtiene todos los menús
    */
    public function get() {
        try {
            $sql = "SELECT * FROM menus ORDER BY id ASC";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ); // Usamos objetos para la vista
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: create($menu)
    */
    public function create($menu) {
        try {
            $sql = "INSERT INTO menus (nombre, descripcion, precio) 
                    VALUES (:nombre, :descripcion, :precio)";
            
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':nombre', $menu->nombre, PDO::PARAM_STR, 100);
            $stmt->bindParam(':descripcion', $menu->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $menu->precio, PDO::PARAM_STR);

            $stmt->execute();
            return $conn->lastInsertId();

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: delete($id)
    */
    public function delete(int $id) {
        try {
            $sql = "DELETE FROM menus WHERE id = :id LIMIT 1";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: validate_id_menu_exists($id)
    */
    public function validate_id_menu_exists($id) {
        try {
            $sql = "SELECT id FROM menus WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    private function handleError(PDOException $e)
    {
        // Reutilizamos tu lógica de errores
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $mensaje = $e->getMessage();
            $controller = new Errores('DE BASE DE DATOS', 'Error: ', $mensaje);
        } else {
            exit("Error crítico: " . $e->getMessage());
        }
    }
}
?>