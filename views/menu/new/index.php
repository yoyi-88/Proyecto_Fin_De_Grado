<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?></title>
</head>

<body>
    <!-- Menú fijo superior -->

    <!-- Capa Principal -->
    <div class="container">
        <br><br><br><br><br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php require_once("template/partials/mensaje.partial.php") ?>
                <?php require_once("template/partials/error.partial.php") ?>
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="card shadow border-0">
                                <div class="card-header bg-dark text-white py-3">
                                    <h5 class="mb-0 card-title"><i class="bi bi-journal-plus"></i> Añadir Nuevo Menú</h5>
                                </div>
                                <div class="card-body p-4">

                                    <?php if (isset($this->error)): ?>
                                        <div class="alert alert-danger"><?= $this->error ?></div>
                                    <?php endif; ?>

                                    <form action="<?= URL ?>menu/create" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                                        <div class="mb-3">
                                            <label for="nombre" class="form-label fw-bold">Nombre del Plato</label>
                                            <input type="text" class="form-control" name="nombre" id="nombre"
                                                value="<?= $this->menu->nombre ?? '' ?>" required>
                                            <small class="text-danger"><?= $this->errors['nombre'] ?? '' ?></small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label fw-bold">Descripción e Ingredientes</label>
                                            <textarea class="form-control" name="descripcion" id="descripcion" rows="4" required><?= $this->menu->descripcion ?? '' ?></textarea>
                                            <small class="text-danger"><?= $this->errors['descripcion'] ?? '' ?></small>
                                        </div>

                                        <div class="mb-4">
                                            <label for="precio" class="form-label fw-bold">Precio (€)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">€</span>
                                                <input type="number" step="0.01" class="form-control" name="precio" id="precio"
                                                    value="<?= $this->menu->precio ?? '' ?>" required>
                                            </div>
                                            <small class="text-danger"><?= $this->errors['precio'] ?? '' ?></small>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="<?= URL ?>menu" class="btn btn-light me-md-2">Cancelar</a>
                                            <button type="submit" class="btn btn-warning">Guardar Menú</button>
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

    <?php require_once 'template/layouts/javascript.layout.php' ?>

</body>

</html>