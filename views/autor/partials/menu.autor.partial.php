<nav class="navbar navbar-expand-lg bg-body-tertiary primary">
    <div class="container-fluid">
        <i class="bi bi-book"></i>
        <a class="navbar-brand" href="<?= URL ?>autor">Autores</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= URL ?>autor/new">Nuevo</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ordenar por
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= URL ?>autor/order/1">Id</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>autor/order/2">Nombre</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>autor/order/3">Nacionalidad</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>autor/order/4">Fecha nacimiento</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>autor/order/5">Email</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>autor/order/6">Premios</a></li>
                       
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
               
            </ul>
            <form class="d-flex" method="GET" action="<?=  URL ?>autor/search">
                <input class="form-control me-2" type="search" placeholder="buscar..." aria-label="Search" name="term">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>