<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container"> <a class="navbar-brand fw-bold fs-4" href="<?= URL ?>main"><img src="public/images/logo.png"
        alt="logo" class="brand-logo">De Mi Casa a la Tuya</a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
      aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
        <li class="nav-item">
          <a class="nav-link <?= ($this->view == 'menu/main/index') ? 'active fw-bold' : '' ?>"
            href="<?= URL ?>menu">Nuestra Carta</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($this->view == 'citas/main/index') ? 'active fw-bold' : '' ?>"
              href="<?= URL ?>citas">Mis Reservas</a>
          </li>

          <?php if (in_array($_SESSION['role_id'], [1])): ?>
            <li class="nav-item ms-lg-3">
              <a class="nav-link text-warning" href="<?= URL ?>user"><i class="bi bi-people me-1"></i>Usuarios</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-warning" href="<?= URL ?>dashboard"><i class="bi bi-graph-up me-1"></i>Dashboard</a>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link <?= ($this->view == 'contact/index') ? 'active fw-bold' : '' ?>"
            href="<?= URL ?>contact">Contacto</a>
        </li>
      </ul>

      <div class="d-flex align-items-center">
        <ul class="navbar-nav ms-auto gap-2">
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white border border-secondary rounded px-3 py-2" href="#"
                role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                <li><a class="dropdown-item py-2" href="<?= URL ?>account"><i
                      class="bi bi-person-badge me-2 text-muted"></i>Mi Perfil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item py-2 text-danger fw-bold" href="<?= URL ?>auth/logout"><i
                      class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item"><a href="<?= URL ?>auth/login" class="btn btn-outline-light px-4">Entrar</a></li>
            <li class="nav-item"><a href="<?= URL ?>auth/register" class="btn btn-primary px-4 fw-bold">Registrarse</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</nav>