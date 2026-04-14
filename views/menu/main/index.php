<div class="catalog-header">
        <h1 class="catalog-title"><?= $this->title ?></h1>

        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <a href="<?= URL ?>menu/new" class="btn btn-dark margenBoton">
                <i class="bi bi-plus-lg"></i> Nuevo Plato
            </a>
        <?php endif; ?>
    </div>

<?php while ($menu = $this->menus->fetch(PDO::FETCH_OBJ)): ?>
            <div class="col">
                <div class="card menu-card h-100">

                    <div class="menu-card-header">
                        <img src="<?= URL ?>public/images/menus/<?= $menu->imagen ?? 'default.jpg' ?>" 
                             class="menu-card-img" alt="<?= $menu->nombre ?>">
                        
                        <span class="badge menu-card-price bg-secondary text-dark rounded-pill shadow-sm">
                            <?= number_format($menu->precio, 2) ?> €
                        </span>
                    </div>

                    <div class="card-body">
                        <h4 class="menu-card-title font-serif fw-bold"><?= htmlspecialchars($menu->nombre) ?></h4>
                        <p class="menu-card-desc"><?= htmlspecialchars(html_entity_decode($menu->descripcion, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                    
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                        
                        <a href="<?= URL ?>menu/show/<?= $menu->id ?>" class="btn btn-outline-primary w-100 mb-2 fw-bold">
                            Ver Detalles
                        </a>

                        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <a href="<?= URL ?>menu/edit/<?= $menu->id ?>" class="text-secondary text-decoration-none small" title="Editar plato">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>

                                <form action="<?= URL ?>menu/delete/<?= $menu->id ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas borrar este menú?');" class="m-0">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none" title="Borrar plato">
                                        <i class="bi bi-trash3"></i> Borrar
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endwhile; ?>