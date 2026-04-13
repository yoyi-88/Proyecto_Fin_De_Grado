<div class="container form-page py-5">
    <?php require_once("template/partials/mensaje.partial.php") ?>
    <?php require_once("template/partials/error.partial.php") ?>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            
            <div class="col-md-4 col-lg-3">
                <?php require_once("views/account/partials/menu.partial.php") ?>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="p-5">
                    <h4 class="mb-4 text-dark font-serif fw-bold">Información de la Cuenta</h4>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-1">Nombre Registrado</label>
                            <p class="fs-5 text-dark mb-0"><?= htmlspecialchars($this->account->name); ?></p>
                        </div>
                        
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-1">Correo Electrónico</label>
                            <p class="fs-5 text-dark mb-0"><?= htmlspecialchars($this->account->email); ?></p>
                        </div>
                    </div>

                    <div class="mb-5 pb-4 border-bottom">
                        <label class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-1">Estado de la cuenta</label>
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                                Activa - <?= htmlspecialchars($_SESSION['role_name']); ?>
                            </span>
                        </div>
                    </div>

                    <div>
                        <a class="btn btn-outline-primary px-4" href="<?= URL ?>main">Volver al Inicio</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>