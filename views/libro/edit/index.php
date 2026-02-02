<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= htmlspecialchars($this->title) ?> </title>
</head>

<body>
    <?php require_once("template/partials/menu.partial.php") ?>

    <div class="container">
        <br><br><br><br>

        <?php require_once("template/partials/mensaje.partial.php") ?>
        <?php require_once("template/partials/error.partial.php") ?>

        <main>
            <legend><?= htmlspecialchars($this->title) ?></legend>
            
            <form action="<?= URL ?>libro/update/<?= htmlspecialchars($this->id) ?>" method="POST">

                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control <?= isset($this->errors['titulo']) ? 'is-invalid' : '' ?>"
                        name="titulo" id="titulo" 
                        value="<?= htmlspecialchars($this->libro->titulo ?? '') ?>" required>
                    <?php if (isset($this->errors['titulo'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($this->errors['titulo']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="autor_id" class="form-label">Autor</label>
                        <select class="form-select <?= isset($this->errors['autor_id']) ? 'is-invalid' : '' ?>"
                            name="autor_id" id="autor_id">
                            <?php foreach ($this->autores as $indice => $autor): ?>
                                <option value="<?= htmlspecialchars($indice) ?>" 
                                    <?= ($this->libro->autor_id == $indice) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($autor) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="editorial_id" class="form-label">Editorial</label>
                        <select class="form-select <?= isset($this->errors['editorial_id']) ? 'is-invalid' : '' ?>"
                            name="editorial_id" id="editorial_id">
                            <?php foreach ($this->editoriales as $indice => $editorial): ?>
                                <option value="<?= htmlspecialchars($indice) ?>" 
                                    <?= ($this->libro->editorial_id == $indice) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($editorial) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="precio_venta" class="form-label">Precio (€)</label>
                        <input type="number" step="0.01" class="form-control"
                            name="precio_venta" id="precio_venta"
                            value="<?= htmlspecialchars($this->libro->precio_venta ?? '') ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control"
                            name="stock" id="stock"
                            value="<?= htmlspecialchars($this->libro->stock ?? '0') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fecha_edicion" class="form-label">Fecha Edición</label>
                        <?php 
                            $fechaISO = "";
                            if (!empty($this->libro->fecha_edicion)) {
                                // Convertimos cualquier formato a YYYY-MM-DD para el input
                                $fechaISO = date('Y-m-d', strtotime($this->libro->fecha_edicion));
                            }
                        ?>
                        <input type="date" class="form-control"
                            name="fecha_edicion" id="fecha_edicion"
                            value="<?= htmlspecialchars($fechaISO) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN (13 dígitos)</label>
                    <input type="text" class="form-control <?= isset($this->errors['isbn']) ? 'is-invalid' : '' ?>"
                        name="isbn" id="isbn" maxlength="13"
                        value="<?= htmlspecialchars($this->libro->isbn ?? '') ?>" required>
                    <?php if (isset($this->errors['isbn'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($this->errors['isbn']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Géneros</label>
                    <div class="form-control <?= isset($this->errors['genero']) ? 'is-invalid' : '' ?>">
                        <?php foreach ($this->generos as $indice => $genero): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="genero[]" 
                                    value="<?= htmlspecialchars($indice) ?>"
                                    id="tema_<?= htmlspecialchars($indice) ?>"
                                    <?= (in_array($indice, $this->temas_libros)) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tema_<?= htmlspecialchars($indice) ?>">
                                    <?= htmlspecialchars($genero) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <hr>
                <div class="mb-3">
                    <a class="btn btn-secondary" href="<?= URL ?>libro" role="button">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Libro</button>
                </div>
            </form>
        </main>

    </div>

    <!-- /.container -->

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>