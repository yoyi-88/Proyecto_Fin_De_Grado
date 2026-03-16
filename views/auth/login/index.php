<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5"> <div class="form-card shadow-sm">
                <div class="form-card-header bg-dark text-white">
                    <h5 class="form-card-title"><i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión</h5>
                </div>
                
                <div class="form-card-body">
                    <?php if (isset($this->mensaje)): ?>
                        <div class="alert alert-success"><?= $this->mensaje ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($this->error)): ?>
                        <div class="alert alert-danger"><?= $this->error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= URL ?>auth/validate_login">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                        <div class="form-group">
                            <label for="email" class="custom-label">Correo Electrónico</label>
                            <input id="email" type="email" class="form-control <?= (isset($this->errors['email'])) ? 'is-invalid' : '' ?>" name="email" value="<?= htmlspecialchars($this->email ?? ''); ?>" required autocomplete="email" autofocus>
                            <small class="form-error"><?= $this->errors['email'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="password" class="custom-label">Contraseña</label>
                            <input id="password" type="password" class="form-control <?= (isset($this->errors['pass'])) ? 'is-invalid' : '' ?>" name="pass" value="<?= htmlspecialchars($this->pass ?? '') ?>" required autocomplete="current-password">
                            
                            <div class="d-flex justify-content-between mt-2">
                                <small class="form-error mb-0"><?= $this->errors['pass'] ?? '' ?></small>
                                <a class="text-decoration-none small text-muted" href="<?= URL ?>auth/forgot_password">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label text-muted" for="remember">Recordarme en este equipo</label>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark btn-lg">Acceder</button>
                        </div>
                        
                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted">¿No tienes cuenta?</span> 
                            <a class="text-decoration-none fw-bold text-dark" href="<?= URL ?>auth/register">Regístrate aquí</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>