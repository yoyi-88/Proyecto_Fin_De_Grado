<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
</head>

<body>
    <?php require_once("template/partials/menu.partial.php") ?>

    <div class="container">
        <br><br><br><br>

        <?php require_once("template/partials/mensaje.partial.php") ?>
        <?php require_once("template/partials/error.partial.php") ?>

        <main>
            <legend><?= $this->title ?></legend>
            <form action="<?= URL ?>user/create" method="POST">

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control <?= isset($this->errors['name']) ? 'is-invalid' : '' ?>"
                        name="name" id="name" placeholder="Ej: Juan Perez"
                        value="<?= htmlspecialchars($this->user->name ?? '') ?>" required>
                    <?php if (isset($this->errors['name'])): ?>
                        <div class="invalid-feedback"><?= $this->errors['name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control <?= isset($this->errors['email']) ? 'is-invalid' : '' ?>"
                        name="email" id="email" placeholder="ejemplo@email.com"
                        value="<?= htmlspecialchars($this->user->email ?? '') ?>" required>
                    <?php if (isset($this->errors['email'])): ?>
                        <div class="invalid-feedback"><?= $this->errors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control <?= isset($this->errors['password']) ? 'is-invalid' : '' ?>"
                        name="password" id="password" required>
                    <?php if (isset($this->errors['password'])): ?>
                        <div class="invalid-feedback"><?= $this->errors['password'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Perfil / Rol</label>
                    <select class="form-select <?= isset($this->errors['role_id']) ? 'is-invalid' : '' ?>"
                        name="role_id" id="role_id" required>
                        <option selected disabled value="">Seleccione un Rol</option>
                        <?php foreach ($this->roles as $id => $role): ?>
                            <option value="<?= $id ?>" <?= (isset($this->user->role_id) && $this->user->role_id == $id) ? 'selected' : '' ?>>
                                <?= $role ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($this->errors['role_id'])): ?>
                        <div class="invalid-feedback"><?= $this->errors['role_id'] ?></div>
                    <?php endif; ?>
                </div>

                <hr>
                <div class="mb-3">
                    <a class="btn btn-secondary" href="<?= URL ?>user" role="button"
                        onclick="return confirm('¿Seguro que desea cancelar?')">Cancelar</a>
                    <button type="reset" class="btn btn-warning">Limpiar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </main>
    </div>

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>
</html>