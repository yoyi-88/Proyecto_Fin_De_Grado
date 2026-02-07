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
                        <div class="col-lg-5 col-md-8">
                            <div class="card shadow-lg border-0">
                                <div class="card-body p-5">
                                    <h3 class="text-center mb-4 fw-bold font-monospace">RESERVAR EXPERIENCIA</h3>

                                    <?php if (isset($this->error)): ?>
                                        <div class="alert alert-danger"><?= $this->error ?></div>
                                    <?php endif; ?>

                                    <form action="<?= URL ?>citas/create" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" name="fecha" id="fecha"
                                                min="<?= date('Y-m-d') ?>"
                                                value="<?= $this->cita->fecha ?? '' ?>" required>
                                            <label for="fecha">Fecha deseada</label>
                                            <small class="text-danger ps-2"><?= $this->errors['fecha'] ?? '' ?></small>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="time" class="form-control" name="hora" id="hora"
                                                value="<?= $this->cita->hora ?? '' ?>" required>
                                            <label for="hora">Hora de llegada</label>
                                            <small class="text-danger ps-2"><?= $this->errors['hora'] ?? '' ?></small>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-muted small text-uppercase fw-bold ls-1">Selección de Menú</label>
                                            <select class="form-select form-select-lg" name="menu_id" required>
                                                <option value="" selected disabled>-- Elige tu plato --</option>
                                                <?php foreach ($this->menus as $menu): ?>
                                                    <option value="<?= $menu->id ?>" <?= (isset($this->cita->menu_id) && $this->cita->menu_id == $menu->id) ? 'selected' : '' ?>>
                                                        <?= $menu->nombre ?> (<?= number_format($menu->precio, 2) ?> €)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-danger ps-2"><?= $this->errors['menu_id'] ?? '' ?></small>
                                        </div>

                                        <div class="d-grid mt-5">
                                            <button type="submit" class="btn btn-dark btn-lg">CONFIRMAR RESERVA</button>
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