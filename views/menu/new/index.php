<div class="container form-page">
    <div class="form-card shadow-sm">
        <div class="form-card-header bg-dark text-white">
            <h5 class="form-card-title"><i class="bi bi-journal-plus"></i> Añadir Nuevo Menú</h5>
        </div>
        
        <div class="form-card-body">
            
            <?php if (isset($this->error)): ?>
                <div class="alert alert-danger"><?= $this->error ?></div>
            <?php endif; ?>

            <form action="<?= URL ?>menu/create" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="nombre" class="custom-label">Nombre del Plato</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?= $this->menu->nombre ?? '' ?>" required>
                    <small class="form-error"><?= $this->errors['nombre'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="descripcion" class="custom-label">Descripción e Ingredientes</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="4" required><?= $this->menu->descripcion ?? '' ?></textarea>
                    <small class="form-error"><?= $this->errors['descripcion'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="precio" class="custom-label">Precio (€)</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" step="0.01" class="form-control" name="precio" id="precio" value="<?= $this->menu->precio ?? '' ?>" required>
                    </div>
                    <small class="form-error"><?= $this->errors['precio'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="imagen" class="custom-label">Fotografía del Menú</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" accept="image/jpeg, image/png, image/webp" onchange="validarTamanoImagen(this)">
                    <small class="form-hint">Formatos recomendados: JPG, PNG, WEBP. Tamaño máximo: 10MB.</small>
                    <small class="form-error d-block"><?= $this->errors['imagen'] ?? '' ?></small>
                </div>

                <div class="form-actions">
                    <a href="<?= URL ?>menu" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-warning">Guardar Menú</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validarTamanoImagen(input) {
        if (input.files && input.files[0]) {
            var tamano = input.files[0].size / 1024 / 1024;
            if (tamano > 10) {
                alert("La imagen es demasiado grande. Máximo 10MB.");
                input.value = "";
            }
        }
    }
</script>