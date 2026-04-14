<div class="container menu-catalog"> <div class="catalog-header">
        <h1 class="catalog-title">Gestión de Reservas</h1>
        <a href="<?= URL ?>citas/new" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Nueva Reserva
        </a>
    </div>

    <div class="card form-card shadow-sm border-0 mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold">FECHA</th>
                        <th class="py-3 text-muted small fw-bold">HORA</th>
                        <th class="py-3 text-muted small fw-bold">MENÚ</th>
                        <?php if($_SESSION['role_id'] == 1): ?>
                            <th class="py-3 text-muted small fw-bold">CLIENTE</th>
                        <?php endif; ?>
                        <th class="py-3 text-muted small fw-bold">ESTADO</th>
                        <th class="text-end pe-4 py-3 text-muted small fw-bold">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($this->citas as $cita): ?>
                    <tr>
                        <td class="ps-4 fw-bold">
                            <?= date('d/m/Y', strtotime($cita->fecha)) ?>
                        </td>
                        <td><i class="bi bi-clock text-muted me-1"></i> <?= date('H:i', strtotime($cita->hora)) ?></td>
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
                            <span class="badge <?= $bg ?> px-3 py-2"><?= $cita->estado ?></span>
                        </td>
                        
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2 align-items-center">
                                
                                <?php if($_SESSION['role_id'] == 1): ?>
                                    <a href="<?= URL ?>citas/edit/<?= $cita->id ?>" class="btn btn-sm btn-outline-dark">Gestionar</a>
                                <?php else: ?>
                                    <small class="text-muted">Ref: #<?= $cita->id ?></small>
                                <?php endif; ?>

                                <?php if($cita->estado === 'Finalizada'): ?>
                                    <a href="<?= URL ?>citas/factura/<?= $cita->id ?>" target="_blank" class="btn btn-sm btn-danger" title="Descargar Factura">
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </a>
                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>