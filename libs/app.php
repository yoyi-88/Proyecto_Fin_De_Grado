<?php

class App {
    
    // Definir constantes para rutas
    const CONTROLLER_PATH = 'controllers/';
    const MODEL_PATH = 'models/';
    const DEFAULT_CONTROLLER = 'main';
    const ERROR_CONTROLLER = 'error';

    public function __construct()
    {
        // Obtener la URL y sanitizarla
        $url = isset($_GET['url']) ? filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL) : null;
        $url = explode('/', $url);

        // Determinar el controlador
        $controllerName = (empty($url[0]) || $url[0] === 'index') ? self::DEFAULT_CONTROLLER : $url[0];
        $controllerFile = self::CONTROLLER_PATH . $controllerName . '.php';

        try {
            if (file_exists($controllerFile)) {
                // Incluir y cargar el controlador
                require_once $controllerFile;
                $controller = new $controllerName();

                // Cargar el modelo asociado al controlador, si existe
                $controller->loadModel($controllerName);

                // Determinar el método y los parámetros
                $methodName = isset($url[1]) ? $url[1] : 'render';
                $params = array_slice($url, 2);

                // Validar que el método exista
                if (method_exists($controller, $methodName)) {
                    call_user_func_array([$controller, $methodName], $params);
                } else {
                    throw new Exception("El método '{$methodName}' no existe en el controlador '{$controllerName}'.");
                }
            } else {
                throw new Exception("El controlador '{$controllerName}' no se encuentra.");
            }
        } catch (Exception $e) {
            // Manejo centralizado de errores
            $this->handleError($e);
        }
    }

    private function handleError(Exception $e)
    {
        // Incluir y cargar el controlador de errores
        $errorControllerFile = self::CONTROLLER_PATH . self::ERROR_CONTROLLER . '.php';
        
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $controller = new Errores($e->getMessage());
            // $controller->renderError($e->getMessage());
        } else {
            // Fallback en caso de que el controlador de errores no exista
            echo "Error crítico: " . $e->getMessage();
        }
    }
}

?>