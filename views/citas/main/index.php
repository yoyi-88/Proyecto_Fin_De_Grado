<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?></title>
</head>

<body>
    <!-- Menú fijo superior -->
    <?php require_once 'template/partials/menu.principal.partial.php' ?>

    <!-- Capa Principal -->
    <div class="container">
        <br><br><br><br><br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php require_once("template/partials/mensaje.partial.php") ?>
                <?php require_once("template/partials/error.partial.php") ?>
                <div class="container py-5">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold"><?= $this->title ?></h2>
                        <a href="<?= URL ?>citas/new" class="btn btn-primary">
                            <i class="bi bi-calendar-check"></i> Solicitar Reserva
                        </a>
                    </div>

                    <?php if (isset($this->mensaje)): ?>
                        <div class="alert alert-info"><?= $this->mensaje ?></div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Fecha</th>
                                            <th>Hora</th>
                                            <th>Menú</th>
                                            <?php if ($_SESSION['role_id'] == 1): ?>
                                                <th>Cliente</th>
                                            <?php endif; ?>
                                            <th>Estado</th>
                                            <th class="text-end pe-4">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // El fetchAll depende de cómo lo devuelva tu modelo (array de objetos)
                                        // Si tu modelo devuelve stmt, usa un while como en Menu.
                                        // Asumiendo que usas fetchAll(PDO::FETCH_CLASS...):
                                        foreach ($this->citas as $cita):
                                        ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary">
                                                    <?= date('d/m/Y', strtotime($cita->fecha)) ?>
                                                </td>
                                                <td><?= date('H:i', strtotime($cita->hora)) ?></td>
                                                <td>
                                                    <span class="d-block text-dark fw-bold"><?= $cita->menu_nombre ?></span>
                                                </td>

                                                <?php if ($_SESSION['role_id'] == 1): ?>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-light rounded-circle text-center me-2" style="width:30px;height:30px;line-height:30px">
                                                                <i class="bi bi-person"></i>
                                                            </div>
                                                            <span><?= $cita->cliente_nombre ?></span>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>

                                                <td>
                                                    <?php
                                                    $badgeColor = match ($cita->estado) {
                                                        'Pendiente' => 'bg-warning text-dark',
                                                        'Confirmada' => 'bg-success',
                                                        'Cancelada' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                    ?>
                                                    <span class="badge rounded-pill <?= $badgeColor ?>"><?= $cita->estado ?></span>
                                                </td>

                                                <td class="text-end pe-4">
                                                    <?php if ($_SESSION['role_id'] == 1): ?>
                                                        <a href="<?= URL ?>citas/edit/<?= $cita->id ?>" class="btn btn-sm btn-outline-dark">
                                                            Gestionar
                                                        </a>
                                                    <?php else: ?>
                                                        <small class="text-muted">#<?= $cita->id ?></small>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (empty($this->citas)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">No hay reservas registradas.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- /.container -->

    <?php require_once 'template/partials/footer.partial.php' ?>
    <?php require_once 'template/layouts/javascript.layout.php' ?>

</body>

</html>