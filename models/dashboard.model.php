<?php

class dashboardModel extends Model {

    /*
        Cuenta cuántas reservas hay de cada estado (Pendiente, Confirmada, etc.)
    */
    public function getEstadisticasEstado() {
        try {
            $sql = "SELECT estado, COUNT(id) as total FROM citas GROUP BY estado";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /*
        Calcula los ingresos previstos agrupados por mes (Solo Confirmadas o Finalizadas)
    */
    public function getIngresosMensuales() {
        try {
            // Usamos funciones de fecha de SQL para agrupar por mes en el año actual
            $sql = "
                SELECT 
                    MONTH(c.fecha) as mes, 
                    SUM(m.precio) as total_ingresos
                FROM citas c
                INNER JOIN menus m ON c.menu_id = m.id
                WHERE c.estado IN ('Confirmada', 'Finalizada') 
                  AND YEAR(c.fecha) = YEAR(CURDATE())
                GROUP BY MONTH(c.fecha)
                ORDER BY MONTH(c.fecha) ASC
            ";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /*
        Tarjetas de resumen rápido (Total clientes, Total Platos, Citas Pendientes)
    */
    public function getResumenRapido() {
        try {
            $conn = $this->db->connect();
            
            // Citas pendientes
            $stmt = $conn->query("SELECT COUNT(id) as total FROM citas WHERE estado = 'Pendiente'");
            $pendientes = $stmt ? $stmt->fetch(PDO::FETCH_OBJ)->total : 0;

            // Total Clientes (Corregido con INNER JOIN hacia roles_users)
            $sqlUsuarios = "
                SELECT COUNT(u.id) as total 
                FROM users u 
                INNER JOIN roles_users ru ON u.id = ru.user_id 
                WHERE ru.role_id != 1
            ";
            $stmt = $conn->query($sqlUsuarios);
            $clientes = $stmt ? $stmt->fetch(PDO::FETCH_OBJ)->total : 0;

            // Total Platos en carta
            $stmt = $conn->query("SELECT COUNT(id) as total FROM menus");
            $platos = $stmt ? $stmt->fetch(PDO::FETCH_OBJ)->total : 0;

            return [
                'pendientes' => $pendientes,
                'clientes'   => $clientes,
                'platos'     => $platos
            ];
            
        } catch (PDOException $e) {
            // Si algo falla, devolvemos 0 por defecto de forma segura
            return ['pendientes' => 0, 'clientes' => 0, 'platos' => 0];
        }
    }
}
?>