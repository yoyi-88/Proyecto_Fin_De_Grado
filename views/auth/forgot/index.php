<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="form-card shadow-sm">
                <div class="form-card-header bg-dark text-white">
                    <h5 class="form-card-title"><i class="bi bi-key"></i> Recuperar Contraseña</h5>
                </div>
                
                <div class="form-card-body">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>auth/send_reset_token" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        
                        <div class="form-group">
                            <label class="custom-label">Correo electrónico registrado</label>
                            <input type="email" name="email" class="form-control form-control-lg" required autofocus>
                            <small class="form-hint mt-2 d-block">Te enviaremos un enlace seguro para crear una nueva contraseña.</small>
                        </div>

                        <div class="d-grid mt-4 pt-2">
                            <button type="submit" class="btn btn-dark btn-lg">Enviar enlace de recuperación</button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="<?= URL ?>auth/login" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Volver al login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>