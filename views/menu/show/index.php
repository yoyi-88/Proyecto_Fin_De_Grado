<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title"><i class="bi bi-eye"></i> <?= $this->title ?></h5>
                    <span class="badge bg-warning text-dark fs-6"><?= number_format($this->menu->precio, 2) ?> €</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <img src="<?= URL ?>public/images/menus/<?= $this->menu->imagen ?? 'default.jpg' ?>" alt="<?= $this->menu->nombre ?>"
                            class="img-fluid rounded shadow-sm" style="width: 100%; height: auto; object-fit: cover;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold small">NOMBRE DEL PLATO</label>
                        <p class="fs-5 fw-medium border-bottom pb-2"><?= htmlspecialchars($this->menu->nombre) ?></p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold small">DESCRIPCIÓN / INGREDIENTES</label>
                        <p class="fs-6 border-bottom pb-2"><?= nl2br(htmlspecialchars($this->menu->descripcion)) ?></p>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
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
    </div>
</div>