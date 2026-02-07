<?php
class MainModel extends Model {
    public function __construct(){
        parent::__construct();
    }

    public function getBiografia(){
        $query = $this->db->connect()->query("SELECT * FROM chef WHERE id_chef = 1");
        return $query->fetch();
    }
}
?>