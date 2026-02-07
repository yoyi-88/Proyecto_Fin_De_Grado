<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= URL ?>index">Chef Privado</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= URL ?>index">Inicio</a>
        </li>
        
        <li class="nav-item">
           <a class="nav-link active" href="<?= URL ?>menu">Nuestra Carta</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link active" href="<?= URL ?>citas">Reservas</a>
          </li>

          <?php if (in_array($_SESSION['role_id'], [1])): ?>
            <li class="nav-item">
              <a class="nav-link active text-warning" href="<?= URL ?>user">Usuarios</a>
            </li>
          <?php endif; ?>
          
        <?php endif; ?>
      </ul>

      <div class="d-flex">
          <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                    <?= $_SESSION['user_name'] ?> (<?= $_SESSION['role_name'] ?? 'Usuario' ?>)
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= URL ?>account">Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= URL ?>auth/logout">Cerrar Sesión</a></li>
                </ul>
            </li>
          <?php else: ?>
            <li class="nav-item"><a href="<?= URL ?>auth/login" class="nav-link active">Entrar</a></li>
            <li class="nav-item"><a href="<?= URL ?>auth/register" class="nav-link active">Registrarse</a></li>
          <?php endif; ?>
          </ul>
      </div>
    </div>
  </div>
</nav>