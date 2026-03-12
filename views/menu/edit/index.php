<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-warning">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0 card-title"><i class="bi bi-pencil-square"></i> <?= $this->title ?> #<?= $this->id ?></h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($this->error)): ?>
                        <div class="alert alert-danger"><?= $this->error ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>menu/update/<?= $this->id ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold text-muted small">NOMBRE DEL PLATO</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" 
                                   value="<?= htmlspecialchars($this->menu->nombre ?? '') ?>" required>
                            <small class="text-danger"><?= $this->errors['nombre'] ?? '' ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold text-muted small">DESCRIPCIÓN</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" rows="4" required><?= htmlspecialchars($this->menu->descripcion ?? '') ?></textarea>
                            <small class="text-danger"><?= $this->errors['descripcion'] ?? '' ?></small>
                        </div>

                        <div class="mb-4">
                            <label for="precio" class="form-label fw-bold text-muted small">PRECIO (€)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">€</span>
                                <input type="number" step="0.01" class="form-control border-start-0" name="precio" id="precio" 
                                       value="<?= htmlspecialchars($this->menu->precio ?? '') ?>" required>
                            </div>
                            <small class="text-danger"><?= $this->errors['precio'] ?? '' ?></small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-4">
                            <a href="<?= URL ?>menu" class="btn btn-light me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-warning fw-bold">Actualizar Menú</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>