<?php

class Controller
{

    function __construct()
    {

        $this->view = new View();

        $this->checkAutologin();

    }

    function loadModel($model)
    {

        $url = 'models/' . $model . '.model.php';
        if (file_exists($url)) {

            require $url;

            $modelName = $model . 'Model';
            $this->model = new $modelName();
        }
    }

    private function checkAutologin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            sec_session_start();
        }

        if (isset($_SESSION['user_id'])) {
            return;
        }

        // Comprobamos la cookie específica de larga duración
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

            $token = $_COOKIE['remember_token'];

            require_once 'models/auth.model.php';
            $authModel = new AuthModel();

            $tokenData = $authModel->getRememberToken($token);

            if ($tokenData) {
                // Buscamos al usuario por ID con el método que creamos anteriormente
                $user = $authModel->get_user_by_id($tokenData->user_id);

                if ($user) {
                    // Recreamos la sesión
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->name;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['role_id'] = $authModel->get_id_role_user($user->id);
                    $_SESSION['role_name'] = $authModel->get_name_role_user($_SESSION['role_id']);
                }
            } else {
                // Si el token es viejo o no existe en BD, matamos la cookie
                setcookie('remember_token', '', time() - 3600, '/', '', false, true);
            }
        }
    }
}




?>