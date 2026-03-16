<div class="container form-page">
    <?php require_once("template/partials/mensaje.partial.php") ?>
    <?php require_once("template/partials/error.partial.php") ?>

    <div class="form-card shadow-sm">
        <div class="form-card-header bg-dark text-white flex-column align-items-stretch gap-3 pb-0">
            <h5 class="form-card-title"><i class="bi bi-shield-lock"></i> <?= $this->title ?></h5>
            <?php require_once("views/account/partials/menu.partial.php") ?>
        </div>
        
        <div class="form-card-body">
            <form action="<?= URL ?>account/update_password" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="password" class="custom-label">Contraseña Actual</label>
                    <input id="password" type="password" class="form-control <?= (isset($this->errors['password'])) ? 'is-invalid' : '' ?>" name="password" required autofocus>
                    <small class="form-error"><?= $this->errors['password'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="new_password" class="custom-label">Nueva Contraseña</label>
                    <input id="new_password" type="password" class="form-control <?= (isset($this->errors['new_password'])) ? 'is-invalid' : '' ?>" name="new_password" required>
                    <small class="form-error"><?= $this->errors['new_password'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="custom-label">Confirmar Nueva Contraseña</label>
                    <input id="confirm_password" type="password" class="form-control <?= (isset($this->errors['confirm_password'])) ? 'is-invalid' : '' ?>" name="confirm_password" required>
                    <small class="form-error"><?= $this->errors['confirm_password'] ?? '' ?></small>
                </div>

                <div class="form-actions border-top-actions">
                    <button type="reset" class="btn btn-light">Limpiar</button>
                    <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>