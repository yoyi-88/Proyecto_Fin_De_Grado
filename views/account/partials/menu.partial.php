<ul class="nav nav-pills account-nav gap-2">
    <li class="nav-item">
        <a class="nav-link <?= ($this->view == 'account/main/index') ? 'active' : 'bg-light text-dark' ?>" href="<?= URL ?>account">Mis Datos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($this->view == 'account/edit/index') ? 'active' : 'bg-light text-dark' ?>" href="<?= URL ?>account/edit">Editar Perfil</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($this->view == 'account/password/index') ? 'active' : 'bg-light text-dark' ?>" href="<?= URL ?>account/password">Contraseña</a>
    </li>
    <li class="nav-item ms-auto"> <a class="btn btn-outline-danger" href="<?= URL ?>account/delete/<?= $_SESSION['csrf_token'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta para siempre?');">
            <i class="bi bi-trash3"></i> Eliminar Cuenta
        </a>
    </li>
</ul>