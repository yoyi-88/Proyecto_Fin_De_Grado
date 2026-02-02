<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?></title>
</head>

<body>
    <?php require_once 'template/partials/menu.principal.partial.php' ?>

    <div class="container">
        <br><br><br><br><br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php require_once("template/partials/mensaje.partial.php") ?>
                <?php require_once("template/partials/error.partial.php") ?>
                
                <div class="card">
                    <div class="card-header">REGISTRO DE USUARIO</div>
                    <div class="card-body">
                        <form method="POST" action="<?= URL ?>auth/validate_register">
                            
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                            <div class="mb-3 row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Nombre Completo</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control <?= (isset($this->errors['name'])) ? 'is-invalid' : null ?>"
                                        name="name" value="<?= htmlspecialchars($this->name ?? ''); ?>" required
                                        autocomplete="name" autofocus>
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->errors['name'] ?? '' ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control <?= (isset($this->errors['email'])) ? 'is-invalid' : null ?>"
                                        name="email" value="<?= htmlspecialchars($this->email ?? ''); ?>" required
                                        autocomplete="email">
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->errors['email'] ?? '' ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control <?= (isset($this->errors['pass'])) ? 'is-invalid' : null ?>"
                                        name="pass" required autocomplete="new-password">
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->errors['pass'] ?? '' ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="pass_confirm" class="col-md-4 col-form-label text-md-right">Confirmar Password</label>
                                <div class="col-md-6">
                                    <input id="pass_confirm" type="password"
                                        class="form-control <?= (isset($this->errors['pass_confirm'])) ? 'is-invalid' : null ?>"
                                        name="pass_confirm" required autocomplete="new-password">
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->errors['pass_confirm'] ?? '' ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3 row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a class="btn btn-secondary" href="<?= URL ?>auth/login" role="button">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'template/partials/footer.partial.php' ?>
    <?php require_once 'template/layouts/javascript.layout.php' ?>

</body>
</html>