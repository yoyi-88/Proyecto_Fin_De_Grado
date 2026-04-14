<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">

            <div class="detail-card shadow-sm border-0">
                <div class="detail-header bg-dark text-white">
                    <h5 class="detail-title"><i class="bi bi-person-vcard"></i> <?= $this->title ?></h5>
                    <span class="badge bg-light text-dark fs-6">ID: #<?= $this->user->id ?></span>
                </div>
                
                <div class="detail-body text-center pt-5 pb-4">
                    <div class="mb-4">
                        <i class="bi bi-person-circle text-muted" style="font-size: 5rem; opacity: 0.5;"></i>
                    </div>

                    <h3 class="mb-1 fw-bold font-serif text-primary"><?= htmlspecialchars($this->user->name) ?></h3>
                    <p class="text-muted mb-4"><?= htmlspecialchars($this->user->email) ?></p>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <div class="p-3 bg-light rounded text-center" style="min-width: 120px;">
                            <label class="custom-label mb-1">Rol Asignado</label>
                            <span class="badge bg-dark"><?= htmlspecialchars($this->role_name) ?></span>
                        </div>
                        
                        <?php if (isset($this->user->created_at)): ?>
                        <div class="p-3 bg-light rounded text-center" style="min-width: 120px;">
                            <label class="custom-label mb-1">Registrado el</label>
                            <span class="fw-bold text-dark small"><?= date('d/m/Y', strtotime($this->user->created_at)) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions justify-content-center mt-5">
                        <a class="btn btn-outline-secondary px-4" href="<?= URL ?>user">
                            <i class="bi bi-arrow-left me-2"></i> Volver a la lista
                        </a>
                        <a class="btn btn-dark px-4" href="<?= URL ?>user/edit/<?= $this->user->id ?>">
                            <i class="bi bi-pencil me-2"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>