<?php
    // SISTEMA DE SEGURIDAD PARA EL NOMBRE: 
    // Comprueba si el controlador pasó la variable. Si no, usa "Usuario" por defecto.
    $nombreUsuario = "Usuario";
    if (isset($this->account->name)) {
        $nombreUsuario = $this->account->name;
    } elseif (isset($_SESSION['name'])) { 
        // Si guardas el nombre en la sesión al loguear, también lo cogerá de ahí
        $nombreUsuario = $_SESSION['name']; 
    }
    
    // Sacamos la primera letra para el avatar
    $inicial = strtoupper(substr($nombreUsuario, 0, 1));
?>

<div class="account-sidebar p-4 bg-light border-end h-100">
    <div class="text-center mb-4 pb-3 border-bottom">
        <div class="avatar-circle bg-primary text-white mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 60px; height: 60px; border-radius: 50%;">
            <?= $inicial ?>
        </div>
        <h6 class="mb-0 text-dark"><?= htmlspecialchars($nombreUsuario) ?></h6>
        <small class="text-muted"><?= htmlspecialchars($_SESSION['role_name'] ?? 'Cliente'); ?></small>
    </div>

    <ul class="nav flex-column account-nav-vertical gap-2">
        <li class="nav-item">
            <a class="nav-link rounded <?= ($this->view == 'account/main/index') ? 'active bg-primary text-white shadow-sm' : 'text-dark hover-bg-light' ?>" href="<?= URL ?>account">
                <i class="bi bi-person me-2"></i> Mis Datos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded <?= ($this->view == 'account/edit/index') ? 'active bg-primary text-white shadow-sm' : 'text-dark hover-bg-light' ?>" href="<?= URL ?>account/edit">
                <i class="bi bi-pencil-square me-2"></i> Editar Perfil
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded <?= ($this->view == 'account/password/index') ? 'active bg-primary text-white shadow-sm' : 'text-dark hover-bg-light' ?>" href="<?= URL ?>account/password">
                <i class="bi bi-shield-lock me-2"></i> Contraseña
            </a>
        </li>
    </ul>

    <div class="mt-5 pt-3 border-top">
        <a class="text-danger text-decoration-none small" href="<?= URL ?>account/delete/<?= $_SESSION['csrf_token'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta para siempre?');">
            <i class="bi bi-trash3"></i> Eliminar mi cuenta
        </a>
    </div>
</div>