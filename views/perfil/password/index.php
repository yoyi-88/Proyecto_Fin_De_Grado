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

            <form method="POST" action="<?= URL ?>auth/validate_password">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="mb-3">
                    <label>Contraseña Actual</label>
                    <input type="password" name="pass_actual" class="form-control <?= isset($this->errors['pass_actual']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= $this->errors['pass_actual'] ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="pass_nuevo" class="form-control <?= isset($this->errors['pass_nuevo']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= $this->errors['pass_nuevo'] ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="pass_confirm" class="form-control <?= isset($this->errors['pass_confirm']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= $this->errors['pass_confirm'] ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                <a href="<?= URL ?>perfil/show" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>


    </div>

    <!-- /.container -->

    <?php require_once 'template/partials/footer.partial.php' ?>
    <?php require_once 'template/layouts/javascript.layout.php' ?>

</body>