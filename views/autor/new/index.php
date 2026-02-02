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

        <!-- Mostrar tabla de  autores -->
        <!-- contenido principal -->
        <main>
            <legend>Formulario Nuevo Autor</legend>
            <!-- Formulario para crear un nuevo autor -->
            <form action="<?= URL ?>autor/create" method="POST">

                <!-- Se exculyen los campos id, poblacion, provincia y dirección por simplicidad -->

                <!-- campo nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>

                <!-- campo nacionalidad -->
                <div class="mb-3">
                    <label for="nacionalidad" class="form-label">Nacionalidad:</label>
                    <input type="text" class="form-control" name="nacionalidad" required>
                </div>

                <!-- campo fecha_nacimiento -->
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha nacimiento:</label>
                    <input type="date" class="form-control" name="fecha_nac" required>
                </div>

                <!-- campo email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <!-- campo premios -->
                <div class="mb-3">
                    <label for="premios" class="form-label">Premios:</label>
                    <input type="text" class="form-control" name="premios" required>
                </div>

                <!-- botones de acción -->
                <a class="btn btn-secondary" href="<?=  URL ?>autor" role="button"
                    onclick="return confirm('Confimar cancelación autor')">Cancelar</a>
                <button type="reset" class="btn btn-secondary" onclick="return confirm('Confimar reseteo autor')">Limpiar</button>
                <button type="submit" class="btn btn-primary">Guardar autor</button>
            </form>

            <br><br><br>
        </main>

    </div>

    <!-- /.container -->

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>