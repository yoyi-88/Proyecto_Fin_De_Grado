<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?></title>
</head>

<body>
    <!-- Menú fijo superior -->
    <?php require_once 'template/partials/menu.principal.partial.php' ?>

    <!-- Capa Principal -->
    <div class="container">
        <br><br><br><br><br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php require_once("template/partials/mensaje.partial.php") ?>
                <?php require_once("template/partials/error.partial.php") ?>

            </div>
            <form method="POST" action="<?= URL ?>auth/validate_profile">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="mb-3">
                    <label>Nombre Completo</label>
                    <input type="text" name="name"
                        value="<?= htmlspecialchars($this->user->name) ?>"
                        class="form-control <?= isset($this->errors['name']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= $this->errors['name'] ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email"
                        value="<?= htmlspecialchars($this->user->email) ?>"
                        class="form-control <?= isset($this->errors['email']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= $this->errors['email'] ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="<?= URL ?>perfil/show" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>


    </div>

    <!-- /.container -->

    <?php require_once 'template/partials/footer.partial.php' ?>
    <?php require_once 'template/layouts/javascript.layout.php' ?>

</body>

</html>