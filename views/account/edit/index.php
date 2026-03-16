<div class="container form-page">
    <?php require_once("template/partials/mensaje.partial.php") ?>
    <?php require_once("template/partials/error.partial.php") ?>

    <div class="form-card shadow-sm">
        <div class="form-card-header bg-dark text-white flex-column align-items-stretch gap-3 pb-0">
            <h5 class="form-card-title"><i class="bi bi-pencil-square"></i> <?= $this->title ?></h5>
            <?php require_once("views/account/partials/menu.partial.php") ?>
        </div>
        
        <div class="form-card-body">
            <form action="<?= URL ?>account/update" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="name" class="custom-label">Nombre</label>
                    <input id="name" type="text" class="form-control <?= (isset($this->errors['name'])) ? 'is-invalid' : '' ?>" name="name" value="<?= htmlspecialchars($this->account->name); ?>" required autofocus>
                    <small class="form-error"><?= $this->errors['name'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="email" class="custom-label">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control <?= (isset($this->errors['email'])) ? 'is-invalid' : '' ?>" name="email" value="<?= htmlspecialchars($this->account->email); ?>" required>
                    <small class="form-error"><?= $this->errors['email'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label class="custom-label">Rol (No editable)</label>
                    <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars($_SESSION['role_name']); ?>" disabled>
                </div>

                <div class="form-actions border-top-actions">
                    <button type="reset" class="btn btn-light">Restablecer</button>
                    <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                </div>
            </form>
        </div>
    </div>
</div>