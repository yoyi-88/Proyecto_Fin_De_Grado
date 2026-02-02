<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
</head>

<body>
    <!-- Menú fijo superior -->
    <?php require_once("template/partials/menu.partial.php") ?>

    <!-- Capa Principal -->
    <div class="container">
        <br><br><br><br>

        <!-- capa de mensajes -->
        <?php require_once("template/partials/mensaje.partial.php") ?>

        <!-- capa de errores -->
        <?php require_once("template/partials/error.partial.php") ?>

        <!-- Mostrar tabla de  autor$autores -->
        <!-- contenido principal -->
        <main>
            <legend>Tabla de Autores - GesLibros</legend>
            <!-- Menú principal de gestión de autor$autores de FP -->
            <?php require_once("views/autor/partials/menu.autor.partial.php") ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <!-- cabecera tabla autor$autores -->
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Nacionalidad</th>
                            <th scope="col" class="text-end">Fecha Nacimiento</th>
                            <th scope="col">Email</th>
                            <th scope="col">Premios</th>
                            <th scope="col">Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- $autores es un objeto mysqli_result, se puede usar foreach directamente  -->
                        <!-- solo cuando cada iteración devuelve un array asociativo -->
                        <?php while ($autor = $this->autores->fetch()): ?>
                            <tr class="">
                                <td><?= $autor['id'] ?></td>
                                <td><?= $autor['nombre'] ?></td>
                                <td><?= $autor['nacionalidad'] ?></td>
                                <td class="text-end"><?= $autor['fecha_nac'] ?></td>
                                <td><?= $autor['email'] ?></td>
                                <td><?= $autor['premios'] ?></td>

                                <!-- botones de acción -->
                                <td>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <!-- boton eliminar -->
                                        <a href="<?=  URL ?>autor/delete/<?= $autor['id'] ?>" class="btn btn-danger btn-sm" title="Eliminar"
                                            onclick="return confirm('Confimar elimación del autor <?= $autores['nombre'] ?>')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                        <!-- boton editar -->
                                        <a href="<?=  URL ?>autor/edit/<?= $autor['id'] ?>" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <!-- boton ver -->
                                        <a href="<?=  URL ?>autor/show/<?= $autor['id'] ?>" class="btn btn-primary btn-sm" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>


                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total autores: <?= $this->autores->rowCount() ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br><br><br>

        </main>

    </div>

    <!-- /.container -->

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>