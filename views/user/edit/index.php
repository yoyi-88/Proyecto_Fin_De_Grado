<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="form-card shadow-sm border border-warning">
                <div class="form-card-header bg-warning text-dark">
                    <h5 class="form-card-title"><i class="bi bi-person-gear"></i> <?= htmlspecialchars($this->title) ?></h5>
                </div>
                
                <div class="form-card-body">
                    <form action="<?= URL ?>user/update/<?= htmlspecialchars($this->id) ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                        <div class="form-group">
                            <label for="name" class="custom-label">Nombre</label>
                            <input type="text" class="form-control <?= isset($this->errors['name']) ? 'is-invalid' : '' ?>" name="name" id="name" value="<?= htmlspecialchars($this->user->name ?? '') ?>" required>
                            <small class="form-error"><?= $this->errors['name'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="email" class="custom-label">Email</label>
                            <input type="email" class="form-control <?= isset($this->errors['email']) ? 'is-invalid' : '' ?>" name="email" id="email" value="<?= htmlspecialchars($this->user->email ?? '') ?>" required>
                            <small class="form-error"><?= $this->errors['email'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="role_id" class="custom-label">Perfil / Rol</label>
                            <select class="form-select <?= isset($this->errors['role_id']) ? 'is-invalid' : '' ?>" name="role_id" id="role_id">
                                <?php foreach ($this->roles as $id => $role): ?>
                                    <option value="<?= htmlspecialchars($id) ?>" <?= ($this->user->role_id == $id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($role) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-error"><?= $this->errors['role_id'] ?? '' ?></small>
                        </div>

                        <div class="form-group mt-4 p-3 bg-light rounded">
                            <label for="password" class="custom-label text-dark"><i class="bi bi-shield-lock"></i> Cambiar Contraseña</label>
                            <input type="password" class="form-control mt-2 <?= isset($this->errors['password']) ? 'is-invalid' : '' ?>" name="password" id="password" placeholder="Dejar en blanco para mantener la actual">
                            <small class="form-hint d-block mt-2">Solo rellene este campo si desea cambiar la contraseña del usuario.</small>
                            <small class="form-error"><?= $this->errors['password'] ?? '' ?></small>
                        </div>

                        <div class="form-actions border-top-actions mt-4 pt-4">
                            <a class="btn btn-light" href="<?= URL ?>user">Cancelar</a>
                            <button type="submit" class="btn btn-dark fw-bold">Actualizar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>