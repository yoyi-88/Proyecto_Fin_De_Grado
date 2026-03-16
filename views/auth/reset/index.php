<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="form-card shadow-sm">
                <div class="form-card-header bg-warning text-dark">
                    <h5 class="form-card-title"><i class="bi bi-shield-lock"></i> Nueva Contraseña</h5>
                </div>
                
                <div class="form-card-body">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>auth/update_password" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="token" value="<?= $this->token ?>">
                        
                        <div class="form-group">
                            <label class="custom-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg" required minlength="7">
                        </div>

                        <div class="form-group">
                            <label class="custom-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirm" class="form-control form-control-lg" required minlength="7">
                        </div>

                        <div class="d-grid mt-4 pt-2">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold">Guardar y Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>