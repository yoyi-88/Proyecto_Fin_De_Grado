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
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="card-title mb-0">Gestionar Reserva #<?= $this->cita->id ?></h5>
                                </div>
                                <div class="card-body">
                                    <dl class="row mb-4">
                                        <dt class="col-sm-4">Cliente:</dt>
                                        <dd class="col-sm-8"><?= $this->cita->cliente_nombre ?? 'Usuario Registrado' ?></dd>

                                        <dt class="col-sm-4">Fecha/Hora:</dt>
                                        <dd class="col-sm-8"><?= $this->cita->fecha ?> a las <?= $this->cita->hora ?></dd>

                                        <dt class="col-sm-4">Menú:</dt>
                                        <dd class="col-sm-8"><?= $this->cita->menu_nombre ?></dd>
                                    </dl>

                                    <form action="<?= URL ?>citas/update/<?= $this->cita->id ?>" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                                        <label class="form-label fw-bold">Estado de la Reserva</label>
                                        <select name="estado" class="form-select mb-4">
                                            <option value="Pendiente" <?= $this->cita->estado == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="Confirmada" <?= $this->cita->estado == 'Confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                            <option value="Cancelada" <?= $this->cita->estado == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                            <option value="Finalizada" <?= $this->cita->estado == 'Finalizada' ? 'selected' : '' ?>>Finalizada</option>
                                        </select>

                                        <div class="d-flex justify-content-between">
                                            <a href="<?= URL ?>citas" class="btn btn-outline-secondary">Volver</a>
                                            <button type="submit" class="btn btn-dark">Actualizar Estado</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- /.container -->

    <?php require_once 'template/partials/footer.partial.php' ?>
    <?php require_once 'template/layouts/javascript.layout.php' ?>

</body>

</html>