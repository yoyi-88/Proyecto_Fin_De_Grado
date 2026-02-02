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

        <!-- Mostrar tabla de  libro$libros -->
        <!-- contenido principal -->
        <main>
            <legend>Tabla de Libros - GesLibros</legend>
            <!-- Menú principal de gestión de libro$libros de FP -->
            <?php require_once("views/libro/partials/menu.libro.partial.php") ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <!-- cabecera tabla libro$libros -->
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Título</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Editorial</th>
                            <th scope="col">Géneros</th>
                            <th scope="col" class="text-end">Stock</th>
                            <th scope="col" class="text-end">Precio</th>
                            <th scope="col">Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- $libros es un objeto mysqli_result, se puede usar foreach directamente  -->
                        <!-- solo cuando cada iteración devuelve un array asociativo -->
                        <?php while ($libro = $this->libros->fetch()): ?>
                            <tr class="">
                                <td><?= $libro['id'] ?></td>
                                <td><?= $libro['titulo'] ?></td>
                                <td><?= $libro['autor'] ?></td>
                                <td><?= $libro['editorial'] ?></td>
                                <td><?= $libro['generos'] ?></td>
                                <td class="text-end"><?= $libro['stock'] ?></td>
                                <!-- usamos el formato en euros -->
                                <td class="text-end"><?= number_format($libro['precio'], 2, ',', '.') ?> €</td>

                                <!-- botones de acción -->
                                <td>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <!-- boton eliminar -->
                                        <form method="POST" action="<?= URL ?>libro/delete/<?= $libro['id'] ?>" style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm 
                                            <?= !in_array($_SESSION['role_id'], $GLOBALS['libro']['delete'])? 'disabled':null ?>"
                                            title="Eliminar" onclick="return confirm('Confirmar eliminación del libro <?= $libro['titulo'] ?>')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>

                                        <!-- boton editar -->
                                        <a href="<?=  URL ?>libro/edit/<?= $libro['id'] ?>" class="btn btn-warning btn-sm
                                        <?= !in_array($_SESSION['role_id'], $GLOBALS['libro']['edit'])? 'disabled':null ?>" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- boton ver -->
                                        <a href="<?=  URL ?>libro/show/<?= $libro['id'] ?>" class="btn btn-primary btn-sm
                                        <?= !in_array($_SESSION['role_id'], $GLOBALS['libro']['show'])? 'disabled':null ?>" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>


                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total libros: <?= $this->libros->rowCount() ?></td>
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