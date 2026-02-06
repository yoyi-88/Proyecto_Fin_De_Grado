<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">MVC - Gestión FP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= URL ?>index">Home</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link active" href="<?= URL ?>libro">Libros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="<?= URL ?>autor">Autores</a>
          </li>
          <!-- Definimos el enlace a usuarios para que solo puedan entrar administradores -->
          <?php if (in_array($_SESSION['role_id'], [1])): ?>
            <li class="nav-item">
              <a class="nav-link active" href="<?= URL ?>user">Usuarios</a>
            </li>
          <?php endif; ?>
          
        <?php endif; ?>
      </ul>

      <div class="d-flex">
          <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><span class="nav-link text-white"><?= $_SESSION['user_name'] ?> |</span></li>
            <li class="nav-item"><a href="<?= URL ?>auth/logout" class="nav-link active">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a href="<?= URL ?>auth/login" class="nav-link active">Login</a></li>
            <li class="nav-item"><a href="<?= URL ?>auth/register" class="nav-link active">Register</a></li>
          <?php endif; ?>
          </ul>
      </div>
    </div>
  </div>
</nav>