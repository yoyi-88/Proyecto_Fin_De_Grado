<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 fw-light">Recuperar Contraseña</h3>
                    
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>auth/send_reset_token" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Correo electrónico registrado</label>
                            <input type="email" name="email" class="form-control form-control-lg" required autofocus>
                            <div class="form-text">Te enviaremos un enlace seguro para crear una nueva contraseña.</div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark btn-lg">Enviar enlace de recuperación</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?= URL ?>auth/login" class="text-decoration-none text-muted">Volver al login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>