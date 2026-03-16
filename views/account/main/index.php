<div class="container form-page">
    <?php require_once("template/partials/mensaje.partial.php") ?>
    <?php require_once("template/partials/error.partial.php") ?>

    <div class="form-card shadow-sm">
        <div class="form-card-header bg-dark text-white flex-column align-items-stretch gap-3 pb-0">
            <h5 class="form-card-title"><i class="bi bi-person-badge"></i> <?= $this->title ?></h5>
            <?php require_once("views/account/partials/menu.partial.php") ?>
        </div>
        
        <div class="detail-body">
            <div class="detail-section">
                <label class="custom-label">Nombre Registrado</label>
                <p class="detail-text main-title"><?= htmlspecialchars($this->account->name); ?></p>
            </div>

            <div class="detail-section">
                <label class="custom-label">Correo Electrónico</label>
                <p class="detail-text"><?= htmlspecialchars($this->account->email); ?></p>
            </div>

            <div class="detail-section">
                <label class="custom-label">Rol del Usuario</label>
                <p class="detail-text">
                    <span class="badge bg-warning text-dark fs-6"><?= htmlspecialchars($_SESSION['role_name']); ?></span>
                </p>
            </div>

            <div class="form-actions mt-4">
                <a class="btn btn-light" href="<?= URL ?>main">Volver al Inicio</a>
            </div>
        </div>
    </div>
</div>