<div class="container py-5">

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            
            <div class="col-md-4 col-lg-3 bg-light border-end">
                <?php require_once("views/account/partials/menu.partial.php") ?>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="p-5">
                    <h4 class="mb-4 text-dark font-serif fw-bold">Seguridad y Contraseña</h4>
                    <p class="text-muted mb-4">Asegúrate de usar una contraseña que sea difícil de adivinar para mantener tu cuenta segura.</p>

                    <form action="<?= URL ?>account/update_password" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="mb-4 form-group">
                            <label for="password" class="form-label text-muted small fw-bold text-uppercase letter-spacing-1">Contraseña Actual</label>
                            <input id="password" type="password" class="form-control form-control-lg bg-light border-0 <?= (isset($this->errors['password'])) ? 'is-invalid' : '' ?>" name="password" required autofocus>
                            <small class="text-danger"><?= $this->errors['password'] ?? '' ?></small>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6 form-group">
                                <label for="new_password" class="form-label text-muted small fw-bold text-uppercase letter-spacing-1">Nueva Contraseña</label>
                                <input id="new_password" type="password" class="form-control form-control-lg bg-light border-0 <?= (isset($this->errors['new_password'])) ? 'is-invalid' : '' ?>" name="new_password" required>
                                <small class="text-danger"><?= $this->errors['new_password'] ?? '' ?></small>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="confirm_password" class="form-label text-muted small fw-bold text-uppercase letter-spacing-1">Confirmar Contraseña</label>
                                <input id="confirm_password" type="password" class="form-control form-control-lg bg-light border-0 <?= (isset($this->errors['confirm_password'])) ? 'is-invalid' : '' ?>" name="confirm_password" required>
                                <small class="text-danger"><?= $this->errors['confirm_password'] ?? '' ?></small>
                            </div>
                        </div>

                        <div class="d-flex gap-3 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Actualizar Contraseña</button>
                            <button type="reset" class="btn btn-link text-muted text-decoration-none">Limpiar campos</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>