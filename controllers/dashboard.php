<?php

class Dashboard extends Controller {

    function __construct() {
        parent::__construct();
    }

    function render() {
        sec_session_start();

        // Seguridad: Solo los logueados y que sean Admin (rol 1) pueden ver esto
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'auth/login'); exit();
        }
        if ($_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "Acceso denegado.";
            header('Location: ' . URL . 'main'); exit();
        }

        $this->view->title = "Panel de Control";

        // Obtenemos los datos del modelo
        $this->view->resumen = $this->model->getResumenRapido();
        $this->view->statsEstado = $this->model->getEstadisticasEstado();
        $this->view->statsIngresos = $this->model->getIngresosMensuales();

        // Renderizamos la vista
        $this->view->render('dashboard/index');
    }
}
?>