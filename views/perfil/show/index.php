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

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mi Perfil</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <th class="w-25">ID</th>
                                <td><?= htmlspecialchars($this->user->id) ?></td>
                            </tr>
                            <tr>
                                <th>Nombre</th>
                                <td><?= htmlspecialchars($this->user->name) ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= htmlspecialchars($this->user->email) ?></td>
                            </tr>
                            <tr>
                                <th>Rol</th>
                                <td><span class="badge bg-info text-dark"><?= $_SESSION['role_name'] ?></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer text-muted">
                        <div class="d-flex justify-content-between">
                            <a href="<?= URL ?>index" class="btn btn-secondary">Volver al Inicio</a>
                            <div>
                                <a href="<?= URL ?>perfil/edit" class="btn btn-primary">Editar Datos</a>
                                <a href="<?= URL ?>perfil/password" class="btn btn-warning">Cambiar Contraseña</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'template/partials/footer.partial.php' ?>
    <?php require_once 'template/layouts/javascript.layout.php' ?>

</body>

</html>