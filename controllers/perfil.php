<?php

class Perfil extends Controller {

    function __construct() {
        parent::__construct();
        sec_session_start();

        // Verificación de seguridad: Si no hay sesión, al login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL . 'auth/login');
            exit();
        }
    }

    // Muestra los datos del perfil actual
    public function show() {
        $this->view->title = "Perfil de Usuario";
        $this->view->user = $this->model->get_user_id($_SESSION['user_id']);
        $this->view->render('perfil/show/index');
    }

    // Formulario para editar el perfil
    public function edit() {
        $this->view->title = "Editar Perfil";
        $this->view->user = $this->model->get_user_id($_SESSION['user_id']);
        
        // CSRF para el formulario de edición
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        $this->view->render('perfil/edit/index');
    }

    // Formulario para cambiar password
    public function password() {
        $this->view->title = "Cambiar Contraseña";
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->view->render('perfil/password/index');
    }

    // Proceso de eliminación
    public function delete() {
        $this->model->delete_user($_SESSION['user_id']);
        
        // Tras eliminar, destruimos la sesión (puedes reutilizar lógica de logout)
        header('Location: ' . URL . 'auth/logout');
        exit();
    }

    public function validate_password() {
        // Seguridad básica
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Recogida de datos
        $password_actual = $_POST['password_actual'] ?? '';
        $password_nuevo  = $_POST['password_nuevo'] ?? '';
        $confirmacion    = $_POST['confirmacion'] ?? '';

        $errors = [];

        // Obtener el password actual de la BD para comparar
        $user = $this->model->get_user_password_actual($_SESSION['user_id']);

        // Validaciones
        if (!password_verify($password_actual, $user->password)) {
            $errors['password_actual'] = "La contraseña actual no es correcta";
        }

        if (strlen($password_nuevo) < 7) {
            $errors['password_nuevo'] = "La nueva contraseña debe tener al menos 7 caracteres";
        }

        if ($password_nuevo !== $confirmacion) {
            $errors['confirmacion'] = "La confirmación no coincide";
        }

        // Gestión de errores
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . URL . 'perfil/password');
            exit();
        }

        // Éxito: Hashear e insertar
        $new_password_hashed = password_hash($password_nuevo, PASSWORD_BCRYPT);
        $this->model->update_password($_SESSION['user_id'], $new_password_hashed);

        $_SESSION['notify'] = "Contraseña actualizada correctamente";
        header('Location: ' . URL . 'perfil/show');
        exit();
    }

    public function validate_edit() {
        // Seguridad
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Recogida de datos
        $name  = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

        $errors = [];

        // Validaciones
        if (empty($name)) $errors['name'] = "El nombre es obligatorio";
        
        // Validar email
        if (empty($email)) {
            $errors['email'] = "El email es obligatorio";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email no válido";
        } else {
            // Verificar si el email ya existe PERO excluyendo al usuario actual
            if ($this->model->validate_unique_email($_SESSION['user_id'], $email)) {
                $errors['email'] = "Este email ya está en uso por otro usuario";
            }
        }

        // Gestión de errores
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . URL . 'perfil/edit');
            exit();
        }

        // Actualizar en BD
        $this->model->update_user($_SESSION['user_id'], $name, $email);

        // Actualizar datos de Sesión (importante para el menú)
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        $_SESSION['notify'] = "Perfil actualizado con éxito";
        header('Location: ' . URL . 'perfil/show');
        exit();
    }
}
?>