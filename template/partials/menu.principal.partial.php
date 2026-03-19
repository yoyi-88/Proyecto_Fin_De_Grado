<nav class="navbar sticky-top navbar-expand-xl navbar-dark bg-dark shadow-sm">
  <div class="container"> 
    
    <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="<?= URL ?>main">
      <img src="<?= URL ?>public/images/logo.png" alt="Logo" class="brand-logo">
      <span class="ms-2 d-none d-sm-inline">De Mi Casa a la Tuya</span>
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
      aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto mb-2 mb-xl-0 gap-2 ms-xl-4">
        <li class="nav-item">
          <a class="nav-link <?= ($this->view == 'menu/main/index') ? 'active fw-bold' : '' ?>" href="<?= URL ?>menu">Nuestra Carta</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($this->view == 'citas/main/index') ? 'active fw-bold' : '' ?>" href="<?= URL ?>citas">Mis Reservas</a>
          </li>

          <?php if (in_array($_SESSION['role_id'], [1])): ?>
            <li class="nav-item ms-xl-3">
              <a class="nav-link text-warning" href="<?= URL ?>user"><i class="bi bi-people me-1"></i>Usuarios</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-warning" href="<?= URL ?>dashboard"><i class="bi bi-graph-up me-1"></i>Dashboard</a>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link <?= ($this->view == 'contact/index') ? 'active fw-bold' : '' ?>" href="<?= URL ?>contact">Contacto</a>
        </li>
      </ul>

      <div class="d-flex align-items-center mt-3 mt-xl-0 ms-xl-5">
        <ul class="navbar-nav ms-auto gap-2 w-100">
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white border border-secondary rounded px-3 py-2 d-flex align-items-center justify-content-between" href="#" role="button" data-bs-toggle="dropdown">
                <span><i class="bi bi-person-circle me-2"></i> <?= htmlspecialchars($_SESSION['user_name']) ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                <li><a class="dropdown-item py-2" href="<?= URL ?>account"><i class="bi bi-person-badge me-2 text-muted"></i>Mi Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger fw-bold" href="<?= URL ?>auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item"><a href="<?= URL ?>auth/login" class="btn btn-outline-light px-4 w-100 mb-2 mb-xl-0">Entrar</a></li>
            <li class="nav-item"><a href="<?= URL ?>auth/register" class="btn btn-primary px-4 fw-bold w-100">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</nav>