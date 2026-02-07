<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-dark"><?= $this->title ?></h1>

        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <a href="<?= URL ?>menu/new" class="btn btn-dark">
                <i class="bi bi-plus-lg"></i> Nuevo Plato
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($this->mensaje)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->mensaje ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php while ($menu = $this->menus->fetch(PDO::FETCH_OBJ)): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <span class="badge bg-warning text-dark float-end"><?= number_format($menu->precio, 2) ?> €</span>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title fw-bold"><?= $menu->nombre ?></h4>
                        <p class="card-text text-muted mt-3"><?= $menu->descripcion ?></p>
                    </div>

                    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between pb-3">
                            <a href="<?= URL ?>menu/edit/<?= $menu->id ?>" class="btn btn-sm btn-outline-secondary">Editar</a>

                            <form action="<?= URL ?>menu/delete/<?= $menu->id ?>" method="POST" onsubmit="return confirm('¿Eliminar este menú permanentemente?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>