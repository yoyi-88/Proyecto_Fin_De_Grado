<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
</head>

<body>
    <?php require_once("template/partials/menu.auth.partial.php") ?>

    <div class="container">
        <br><br><br><br>

        <?php require_once("template/partials/mensaje.partial.php") ?>

        <?php require_once("template/partials/error.partial.php") ?>

        <main>
            <legend>Gestión de Usuarios - GesLibros</legend>
            
            <?php require_once("views/user/partials/menu.user.partial.php") ?>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Creado el</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $this->users->fetch()): ?>
                            <tr class="">
                                <td><?= $user->id ?></td>
                                <td><?= htmlspecialchars($user->name) ?></td>
                                <td><?= htmlspecialchars($user->email) ?></td>
                                <td>
                                    <span class="badge <?= ($user->role_name == 'Administrador') ? 'bg-danger' : (($user->role_name == 'Editor') ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                        <?= htmlspecialchars($user->role_name) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></td>

                                <td>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <?php if($_SESSION['user_id'] != $user->id): ?>
                                            <form method="POST" action="<?= URL ?>user/delete/<?= $user->id ?>" style="display:inline;">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                title="Eliminar" onclick="return confirm('¿Confirmar eliminación del usuario <?= htmlspecialchars($user->name) ?>?')">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled title="No puedes borrarte a ti mismo"><i class="bi bi-trash3"></i></button>
                                        <?php endif; ?>

                                        <a href="<?= URL ?>user/edit/<?= $user->id ?>" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="<?= URL ?>user/show/<?= $user->id ?>" class="btn btn-primary btn-sm" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Total usuarios: <?= $this->users->rowCount() ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br><br><br>
        </main>
    </div>

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>
</body>
</html>