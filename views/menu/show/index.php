<div class="container form-page">
    <div class="detail-card shadow-sm">
        <div class="detail-header bg-dark text-white">
            <h5 class="detail-title"><i class="bi bi-eye"></i> <?= $this->title ?></h5>
            <span class="badge bg-warning text-dark price-badge"><?= number_format($this->menu->precio, 2) ?> €</span>
        </div>
        
        <div class="detail-body">
            <div class="detail-img-container">
                <img src="<?= URL ?>public/images/menus/<?= $this->menu->imagen ?? 'default.jpg' ?>" alt="<?= $this->menu->nombre ?>" class="detail-img">
            </div>

            <div class="detail-section">
                <label class="custom-label">Nombre del Plato</label>
                <p class="detail-text main-title"><?= htmlspecialchars($this->menu->nombre) ?></p>
            </div>

            <div class="detail-section">
                <label class="custom-label">Descripción / Ingredientes</label>
                <p class="detail-text"><?=nl2br(htmlspecialchars(html_entity_decode($this->menu->descripcion, ENT_QUOTES, 'UTF-8'))) ?></p>
            </div>

            <div class="form-actions mt-5">
                <a href="<?= URL ?>menu" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a la Carta
                </a>
                <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                    <a href="<?= URL ?>menu/edit/<?= $this->menu->id ?>" class="btn btn-dark">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>