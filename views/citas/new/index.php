<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 font-serif">Solicitar Experiencia</h3>
                    
                    <?php if(isset($this->error)): ?>
                        <div class="alert alert-danger"><?= $this->error ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>citas/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">FECHA</label>
                                <input type="date" name="fecha" class="form-control" 
                                       min="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">HORA</label>
                                <input type="time" name="hora" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">SELECCIONA MENÚ</label>
                            <select name="menu_id" class="form-select form-select-lg" required>
                                <option value="" disabled selected>-- Ver opciones disponibles --</option>
                                <?php foreach($this->menus as $menu): ?>
                                    <option value="<?= $menu->id ?>">
                                        <?= $menu->nombre ?> (<?= number_format($menu->precio, 2) ?>€)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg">Confirmar Solicitud</button>
                            <a href="<?= URL ?>citas" class="btn btn-link text-muted mt-2">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>