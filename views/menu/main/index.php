<div class="container menu-catalog">

    <div class="catalog-header">
        <h1 class="catalog-title"><?= $this->title ?></h1>

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
                <div class="card menu-card h-100">

                    <div class="menu-card-header">
                        <img src="<?= URL ?>public/images/menus/<?= $menu->imagen ?? 'default.jpg' ?>" 
                             class="menu-card-img" alt="<?= $menu->nombre ?>">
                        
                        <span class="badge menu-card-price">
                            <?= number_format($menu->precio, 2) ?> €
                        </span>
                    </div>

                    <div class="card-body">
                        <h4 class="menu-card-title"><?= htmlspecialchars($menu->nombre) ?></h4>
                        <p class="menu-card-desc"><?= htmlspecialchars(html_entity_decode($menu->descripcion, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                    
                    <div class="card-footer menu-card-actions">
                        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                            <a href="<?= URL ?>menu/edit/<?= $menu->id ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                        <?php endif; ?>

                        <a href="<?= URL ?>menu/show/<?= $menu->id ?>" class="btn btn-sm btn-outline-primary">Ver Detalles</a>

                        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                            <form action="<?= URL ?>menu/delete/<?= $menu->id ?>" method="POST" onsubmit="return confirm('¿Borrar?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>