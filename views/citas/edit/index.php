<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            
            <div class="form-card shadow-sm border border-warning">
                <div class="form-card-header bg-warning text-dark">
                    <h5 class="form-card-title"><i class="bi bi-gear"></i> Gestionar Reserva #<?= $this->cita->id ?></h5>
                </div>
                
                <div class="form-card-body">
                    <div class="bg-light p-4 rounded mb-4">
                        <div class="row mb-2">
                            <div class="col-4 custom-label mb-0">Cliente</div>
                            <div class="col-8 fw-bold"><?= $this->cita->cliente_nombre ?? 'Usuario Registrado' ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 custom-label mb-0">Fecha / Hora</div>
                            <div class="col-8"><?= date('d/m/Y', strtotime($this->cita->fecha)) ?> a las <?= date('H:i', strtotime($this->cita->hora)) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-4 custom-label mb-0">Menú</div>
                            <div class="col-8"><?= $this->cita->menu_nombre ?></div>
                        </div>
                    </div>

                    <form action="<?= URL ?>citas/update/<?= $this->cita->id ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="form-group">
                            <label class="custom-label">Estado de la Reserva</label>
                            <select name="estado" class="form-select form-select-lg">
                                <option value="Pendiente" <?= $this->cita->estado == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                <option value="Confirmada" <?= $this->cita->estado == 'Confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                <option value="Cancelada" <?= $this->cita->estado == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                <option value="Finalizada" <?= $this->cita->estado == 'Finalizada' ? 'selected' : '' ?>>Finalizada</option>
                            </select>
                        </div>

                        <div class="form-actions mt-4 pt-4 border-top-actions">
                            <a href="<?= URL ?>citas" class="btn btn-light">Volver</a>
                            <button type="submit" class="btn btn-dark fw-bold">Actualizar Estado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>