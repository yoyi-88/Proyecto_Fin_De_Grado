<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <?php require_once("template/partials/mensaje.partial.php") ?>
            <?php require_once("template/partials/error.partial.php") ?>

            <div class="form-card shadow-sm border-0">
                <div class="form-card-header bg-dark text-white">
                    <h5 class="form-card-title"><i class="bi bi-person-plus"></i> <?= $this->title ?></h5>
                </div>
                
                <div class="form-card-body">
                    <form action="<?= URL ?>user/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                        <div class="form-group">
                            <label for="name" class="custom-label">Nombre de Usuario</label>
                            <input type="text" class="form-control <?= isset($this->errors['name']) ? 'is-invalid' : '' ?>" name="name" id="name" placeholder="Ej: Juan Pérez" value="<?= htmlspecialchars($this->user->name ?? '') ?>" required autofocus>
                            <small class="form-error"><?= $this->errors['name'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="email" class="custom-label">Correo Electrónico</label>
                            <input type="email" class="form-control <?= isset($this->errors['email']) ? 'is-invalid' : '' ?>" name="email" id="email" placeholder="ejemplo@email.com" value="<?= htmlspecialchars($this->user->email ?? '') ?>" required>
                            <small class="form-error"><?= $this->errors['email'] ?? '' ?></small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="password" class="custom-label">Contraseña</label>
                                <input type="password" class="form-control <?= isset($this->errors['password']) ? 'is-invalid' : '' ?>" name="password" id="password" required>
                                <small class="form-error"><?= $this->errors['password'] ?? '' ?></small>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="role_id" class="custom-label">Perfil / Rol</label>
                                <select class="form-select <?= isset($this->errors['role_id']) ? 'is-invalid' : '' ?>" name="role_id" id="role_id" required>
                                    <option selected disabled value="">Selecciona rol...</option>
                                    <?php foreach ($this->roles as $id => $role): ?>
                                        <option value="<?= $id ?>" <?= (isset($this->user->role_id) && $this->user->role_id == $id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($role) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-error"><?= $this->errors['role_id'] ?? '' ?></small>
                            </div>
                        </div>

                        <div class="form-actions border-top-actions mt-4 pt-4">
                            <a class="btn btn-light" href="<?= URL ?>user" onclick="return confirm('¿Seguro que desea cancelar?')">Cancelar</a>
                            <button type="submit" class="btn btn-dark fw-bold">Crear Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>