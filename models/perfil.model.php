<?php

class perfilModel extends Model {

    // Obtener datos del usuario por ID
    public function get_user_id($id) {
        try {
            $sql = "SELECT id, name, email FROM users WHERE id = :id LIMIT 1";
            $conexion = $this->db->connect();
            $stmt = $conexion->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    // Eliminar perfil
    public function delete_user($id) {
        try {
            $conexion = $this->db->connect();
            // Al borrar el usuario, la tabla roles_users debería borrarlo en cascada 
            // si configuraste bien la FK en MySQL, si no, borra primero en roles_users.
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    // Obtener solo el password para validaciones de seguridad
    public function get_user_password_actual($id) {
        try {
            $sql = "SELECT password FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    // Actualizar el password en la base de datos
    public function update_password($id, $new_password) {
        try {
            $sql = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Verifica si el email existe en otros registros distintos al del usuario actual
    */
    public function validate_unique_email($id, $email) {
        try {
            $sql = "SELECT id FROM users WHERE email = :email AND id <> :id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Actualiza nombre y email
    */
    public function update_user($id, $name, $email) {
        try {
            $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }
    
    // Aquí irían update_user() y update_password()...
}