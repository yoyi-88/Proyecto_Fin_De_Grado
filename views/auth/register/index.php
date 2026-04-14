<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="form-card shadow-sm">
                <div class="form-card-header bg-dark text-white">
                    <h5 class="form-card-title"><i class="bi bi-person-plus"></i> Crear Nueva Cuenta</h5>
                </div>
                
                <div class="form-card-body">

                    <form method="POST" action="<?= URL ?>auth/validate_register">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                        <div class="form-group">
                            <label for="name" class="custom-label">Nombre Completo</label>
                            <input id="name" type="text" class="form-control <?= (isset($this->errors['name'])) ? 'is-invalid' : '' ?>" name="name" value="<?= htmlspecialchars($this->name ?? ''); ?>" required autocomplete="name" autofocus>
                            <small class="form-error"><?= $this->errors['name'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="email" class="custom-label">Correo Electrónico</label>
                            <input id="email" type="email" class="form-control <?= (isset($this->errors['email'])) ? 'is-invalid' : '' ?>" name="email" value="<?= htmlspecialchars($this->email ?? ''); ?>" required autocomplete="email">
                            <small class="form-error"><?= $this->errors['email'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="password" class="custom-label">Contraseña</label>
                            <input id="password" type="password" class="form-control <?= (isset($this->errors['password'])) ? 'is-invalid' : '' ?>" name="password" required autocomplete="new-password">
                            <small class="form-error"><?= $this->errors['password'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm" class="custom-label">Confirmar Contraseña</label>
                            <input id="password_confirm" type="password" class="form-control" name="password_confirm" required autocomplete="new-password">
                        </div>

                        <div class="form-actions border-top-actions mt-4 pt-4">
                            <a class="btn btn-light" href="<?= URL ?>auth/login">Volver al Login</a>
                            <button type="submit" class="btn btn-dark">Completar Registro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>