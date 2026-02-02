<nav class="navbar navbar-expand-lg bg-body-tertiary primary">
    <div class="container-fluid">
        <i class="bi bi-book"></i>
        <a class="navbar-brand" href="<?= URL ?>libro">Libros</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= URL ?>libro/new">Nuevo</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ordenar por
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= URL ?>libro/order/1/<?= $_SESSION['csrf_token'] ?>">Id</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>libro/order/2/<?= $_SESSION['csrf_token'] ?>">Título</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>libro/order/3/<?= $_SESSION['csrf_token'] ?>">Autor</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>libro/order/4/<?= $_SESSION['csrf_token'] ?>">Editorial</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>libro/order/5/<?= $_SESSION['csrf_token'] ?>">Géneros</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>libro/order/6/<?= $_SESSION['csrf_token'] ?>">Stock</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>libro/order/7/<?= $_SESSION['csrf_token'] ?>">Precio</a></li>
                    </ul>
                </li>
               
            </ul>
            <form class="d-flex" method="GET" action="<?=  URL ?>libro/search">
                <input class="form-control me-2" type="search" placeholder="buscar..." aria-label="Search" name="term">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>