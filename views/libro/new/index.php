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
            <form action="<?= URL ?>libro/create" method="POST">

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control <?= isset($this->errors['titulo']) ? 'is-invalid' : '' ?>"
                        name="titulo" id="titulo" placeholder="Ej: El Quijote"
                        value="<?= htmlspecialchars($this->libro->titulo ?? '') ?>">
                    <?php if (isset($this->errors['titulo'])): ?>
                        <div class="invalid-feedback"><?= $this->errors['titulo'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="autor_id" class="form-label">Autor</label>
                        <select class="form-select <?= isset($this->errors['autor_id']) ? 'is-invalid' : '' ?>"
                            name="autor_id" id="autor_id">
                            <option selected disabled value="">Seleccione Autor</option>
                            <?php foreach ($this->autores as $indice => $autor): ?>
                                <option value="<?= $indice ?>" <?= (isset($this->libro->autor_id) && $this->libro->autor_id == $indice) ? 'selected' : '' ?>>
                                    <?= $autor ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($this->errors['autor_id'])): ?>
                            <div class="invalid-feedback"><?= $this->errors['autor_id'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="editorial_id" class="form-label">Editorial</label>
                        <select class="form-select <?= isset($this->errors['editorial_id']) ? 'is-invalid' : '' ?>"
                            name="editorial_id" id="editorial_id">
                            <option selected disabled value="">Seleccione Editorial</option>
                            <?php foreach ($this->editoriales as $indice => $editorial): ?>
                                <option value="<?= $indice ?>" <?= (isset($this->libro->editorial_id) && $this->libro->editorial_id == $indice) ? 'selected' : '' ?>>
                                    <?= $editorial ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($this->errors['editorial_id'])): ?>
                            <div class="invalid-feedback"><?= $this->errors['editorial_id'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="precio_venta" class="form-label">Precio (€)</label>
                        <input type="number" step="0.01"
                            class="form-control <?= isset($this->errors['precio_venta']) ? 'is-invalid' : '' ?>"
                            name="precio_venta" id="precio_venta" placeholder="0.00"
                            value="<?= $this->libro->precio_venta ?? '' ?>"
                            inputmode="decimal" required>
                        <?php if (isset($this->errors['precio_venta'])): ?>
                            <div class="invalid-feedback"><?= $this->errors['precio_venta'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control <?= isset($this->errors['stock']) ? 'is-invalid' : '' ?>"
                            name="stock" id="stock"
                            value="<?= $this->libro->stock ?? '0' ?>">
                        <?php if (isset($this->errors['stock'])): ?>
                            <div class="invalid-feedback"><?= $this->errors['stock'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fecha_edicion" class="form-label">Fecha Edición</label>
                        <input type="date" class="form-control <?= isset($this->errors['fecha_edicion']) ? 'is-invalid' : '' ?>"
                            name="fecha_edicion" id="fecha_edicion"
                            value="<?= $this->libro->fecha_edicion ?? '' ?>">
                        <?php if (isset($this->errors['fecha_edicion'])): ?>
                            <div class="invalid-feedback"><?= $this->errors['fecha_edicion'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN (13 dígitos)</label>
                    <input type="text"
                        class="form-control <?= isset($this->errors['isbn']) ? 'is-invalid' : '' ?>"
                        name="isbn" id="isbn" maxlength="13" minlength="13" pattern="\d{13}"
                        placeholder="Ej: 9788445077111"
                        value="<?= $this->libro->isbn ?? '' ?>"
                        inputmode="numeric" required>
                    <?php if (isset($this->errors['isbn'])): ?>
                        <div class="invalid-feedback"><?= $this->errors['isbn'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Géneros</label>
                    <div class="form-control <?= isset($this->errors['genero']) ? 'is-invalid' : '' ?>">
                        <?php foreach ($this->generos as $indice => $genero): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="genero[]" value="<?= $indice ?>"
                                    id="tema_<?= $indice ?>"
                                    <?= (isset($this->libro->generos) && is_array($this->libro->generos) && in_array($indice, $this->libro->generos)) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tema_<?= $indice ?>"><?= $genero ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (isset($this->errors['genero'])): ?>
                        <p class="text-danger small mt-1"><?= $this->errors['genero'] ?></p>
                    <?php endif; ?>
                </div>

                <hr>
                <div class="mb-3">
                    <a class="btn btn-secondary" href="<?= URL ?>libro" role="button"
                        onclick="return confirm('¿Seguro que desea cancelar?')">Cancelar</a>
                    <button type="reset" class="btn btn-warning">Limpiar</button>
                    <button type="submit" class="btn btn-primary">Guardar Libro</button>
                </div>
            </form>
        </main>

    </div>

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

    <script>
        document.getElementById('isbn').addEventListener('input', function(e) {
            // Elimina cualquier carácter que no sea un número
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>

</body>