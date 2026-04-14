<div class="container menu-catalog">

    <div class="catalog-header">
        <h1 class="catalog-title">Gestión de Usuarios</h1>
        <a href="<?= URL ?>user/new" class="btn btn-dark shadow-sm">
            <i class="bi bi-person-plus"></i> Nuevo Usuario
        </a>
    </div>

    <div class="card form-card shadow-sm border-0 mt-4">
        
        <div class="p-4 pb-0">
            <?php require_once("views/user/partials/menu.user.partial.php") ?>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold">ID</th>
                        <th class="py-3 text-muted small fw-bold">NOMBRE</th>
                        <th class="py-3 text-muted small fw-bold">EMAIL</th>
                        <th class="py-3 text-muted small fw-bold">ROL</th>
                        <th class="py-3 text-muted small fw-bold">FECHA REGISTRO</th>
                        <th class="text-end pe-4 py-3 text-muted small fw-bold">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $this->users->fetch()): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?= $user->id ?></td>
                            <td class="fw-medium text-dark"><?= htmlspecialchars($user->name) ?></td>
                            <td><?= htmlspecialchars($user->email) ?></td>
                            <td>
                                <?php 
                                    $bg = match($user->role_name) {
                                        'Administrador' => 'bg-danger',
                                        'Editor' => 'bg-warning text-dark',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <span class="badge <?= $bg ?> px-2 py-1"><?= htmlspecialchars($user->role_name) ?></span>
                            </td>
                            <td class="text-muted small"><?= date('d/m/Y', strtotime($user->created_at)) ?></td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= URL ?>user/show/<?= $user->id ?>" class="btn btn-sm btn-outline-primary" title="Ver Perfil">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="<?= URL ?>user/edit/<?= $user->id ?>" class="btn btn-sm btn-outline-dark" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <?php if ($_SESSION['user_id'] != $user->id): ?>
                                        <form method="POST" action="<?= URL ?>user/delete/<?= $user->id ?>" onsubmit="return confirm('¿Confirmar eliminación del usuario <?= htmlspecialchars($user->name) ?>?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="No puedes borrar tu propia cuenta">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="6" class="ps-4 text-muted small">
                            Total usuarios registrados: <strong><?= $this->users->rowCount() ?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>