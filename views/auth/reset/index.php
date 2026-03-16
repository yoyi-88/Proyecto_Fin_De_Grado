<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 fw-light">Nueva Contraseña</h3>
                    
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>auth/update_password" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="token" value="<?= $this->token ?>">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg" required minlength="7">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Confirmar Contraseña</label>
                            <input type="password" name="password_confirm" class="form-control form-control-lg" required minlength="7">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Guardar y Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>