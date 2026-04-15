<?php


function sec_session_start()
{

	$session_name = 'sec_session_id';
	$secure = false;
	$httponly = true;

	// 1. COMPROBACIÓN CRÍTICA: Si la sesión ya está activa, no tocamos nada y salimos
	if (session_status() !== PHP_SESSION_NONE) {
		return;
	}

	// 2. CONFIGURACIÓN (Solo se ejecuta si no hay sesión previa)
	if (ini_set('session.use_only_cookies', 1) === FALSE) {
		// En lugar de redireccionar (que causa bucles), lanzamos un error silencioso o log
		error_log("No se pudo configurar session.use_only_cookies");
	}

	$cookieParams = session_get_cookie_params();
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

	session_name($session_name);

	session_start();

}

function sec_session_destroy()
{

	// Aseguramos que hay sesión para poder destruirla
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION = array();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    session_destroy();

}


?>