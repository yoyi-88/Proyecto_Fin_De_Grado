<div class="container form-page">
    <div class="form-card shadow-sm">
        <div class="form-card-header bg-warning text-dark">
            <h5 class="form-card-title"><i class="bi bi-pencil-square"></i> <?= $this->title ?> #<?= $this->id ?></h5>
        </div>
        
        <div class="form-card-body">

            <form action="<?= URL ?>menu/update/<?= $this->id ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="nombre" class="custom-label">Nombre del Plato</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($this->menu->nombre ?? '') ?>" required>
                    <small class="form-error"><?= $this->errors['nombre'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="descripcion" class="custom-label">Descripción</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="4" required><?= htmlspecialchars($this->menu->descripcion ?? '') ?></textarea>
                    <small class="form-error"><?= $this->errors['descripcion'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="precio" class="custom-label">Precio (€)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">€</span>
                        <input type="number" step="0.01" class="form-control border-start-0" name="precio" id="precio" value="<?= htmlspecialchars($this->menu->precio ?? '') ?>" required>
                    </div>
                    <small class="form-error"><?= $this->errors['precio'] ?? '' ?></small>
                </div>

                <div class="form-group">
                    <label for="imagen" class="custom-label">Imagen del Plato</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" accept="image/jpeg, image/png, image/webp" onchange="validarTamanoImagen(this)">
                    <small class="form-hint">Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 10MB.</small>
                    <small class="form-error d-block"><?= $this->errors['imagen'] ?? '' ?></small>
                </div>

                <div class="form-actions border-top-actions">
                    <a href="<?= URL ?>menu" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-warning fw-bold">Actualizar Menú</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validarTamanoImagen(input) {
        if (input.files && input.files[0]) {
            if (input.files[0].size > 10 * 1024 * 1024) {
                alert('El tamaño de la imagen no puede superar los 10MB.');
                input.value = ''; 
            }
        }
    }
</script>