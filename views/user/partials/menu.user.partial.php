<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
    <div class="dropdown">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-sort-down me-1"></i> Ordenar por
        </button>
        <ul class="dropdown-menu shadow-sm border-0">
            <li><a class="dropdown-item" href="<?= URL ?>user/order/1">ID</a></li>
            <li><a class="dropdown-item" href="<?= URL ?>user/order/2">Nombre</a></li>
            <li><a class="dropdown-item" href="<?= URL ?>user/order/3">Email</a></li>
            <li><a class="dropdown-item" href="<?= URL ?>user/order/4">Rol</a></li>
        </ul>
    </div>

    <form class="d-flex" method="GET" action="<?= URL ?>user/search">
        <div class="input-group input-group-sm shadow-sm">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input class="form-control border-start-0 ps-0" type="search" placeholder="Buscar usuario..." name="term">
            <button class="btn btn-dark" type="submit">Buscar</button>
        </div>
    </form>
</div>