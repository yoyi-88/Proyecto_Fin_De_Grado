<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white fw-bold">INICIAR SESIÓN</div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= URL ?>auth/validate_login">
                        
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control <?= (isset($this->errors['email'])) ? 'is-invalid' : null ?>"
                                    name="email" value="<?= htmlspecialchars($this->email ?? ''); ?>" required
                                    autocomplete="email" autofocus>
                                
                                <span class="text-danger small">
                                    <?= $this->errors['email'] ?? '' ?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Contraseña</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control <?= (isset($this->errors['pass'])) ? 'is-invalid' : null ?>"
                                    name="pass" value="<?= htmlspecialchars($this->pass ?? '') ?>" required
                                    autocomplete="current-password">

                                <span class="text-danger small">
                                    <?= $this->errors['pass'] ?? '' ?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">Recordarme</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-dark px-4">Acceder</button>
                                <a class="btn btn-outline-secondary ms-2" href="<?= URL ?>auth/register">Registrarme</a>
                                
                                <div class="mt-3">
                                    <a class="text-decoration-none text-muted small" href="#">¿Olvidó su contraseña?</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>