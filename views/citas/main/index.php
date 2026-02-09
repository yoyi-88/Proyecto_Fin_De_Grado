<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-light text-uppercase ls-2">Gestión de Reservas</h2>
        <a href="<?= URL ?>citas/new" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nueva Reserva
        </a>
    </div>

    <?php if(isset($this->mensaje)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $this->mensaje ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Fecha</th>
                        <th>Hora</th>
                        <th>Menú</th>
                        <?php if($_SESSION['role_id'] == 1): ?>
                            <th>Cliente</th>
                        <?php endif; ?>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($this->citas as $cita): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">
                            <?= date('d/m/Y', strtotime($cita->fecha)) ?>
                        </td>
                        <td><?= date('H:i', strtotime($cita->hora)) ?></td>
                        <td><?= $cita->menu_nombre ?></td>
                        
                        <?php if($_SESSION['role_id'] == 1): ?>
                            <td><span class="badge bg-light text-dark border"><?= $cita->cliente_nombre ?></span></td>
                        <?php endif; ?>

                        <td>
                            <?php 
                            $bg = match($cita->estado) {
                                'Confirmada' => 'bg-success',
                                'Cancelada' => 'bg-danger',
                                default => 'bg-warning text-dark'
                            };
                            ?>
                            <span class="badge <?= $bg ?>"><?= $cita->estado ?></span>
                        </td>
                        
                        <td class="text-end pe-4">
                            <?php if($_SESSION['role_id'] == 1): ?>
                                <a href="<?= URL ?>citas/edit/<?= $cita->id ?>" class="btn btn-sm btn-outline-dark">Gestionar</a>
                            <?php else: ?>
                                <small class="text-muted">#<?= $cita->id ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>