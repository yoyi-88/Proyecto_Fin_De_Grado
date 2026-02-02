<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= URL ?>index">MVC - Gestión FP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
        <li class="nav-item">
          <a class="nav-link active" href="<?= URL ?>index">Home</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL ?>libro">Libros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL ?>autor">Autores</a>
          </li>
        <?php endif; ?>
      </ul>

      <div class="d-flex">
        <ul class="nav navbar-nav flex-row">
          <?php if (!isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a href="<?= URL ?>auth/login" class="nav-link">Login</a></li>
            <li class="nav-item"><a href="<?= URL ?>auth/register" class="nav-link">Register</a></li>
          <?php else: ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                <?= $_SESSION['user_name'] ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= URL ?>auth/show_profile">Mostrar Perfil</a></li>
                <li><a class="dropdown-item" href="<?= URL ?>auth/edit_profile">Editar Perfil</a></li>
                <li><a class="dropdown-item" href="<?= URL ?>auth/change_password">Cambiar Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= URL ?>auth/logout">Cerrar Sesión</a></li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</nav>