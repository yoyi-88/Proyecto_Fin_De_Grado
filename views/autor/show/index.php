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
            <legend><?= $this->title ?></legend>

            <!-- Formulario para editar autor -->

            <!-- campo nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" value="<?= $this->autor->nombre ?>" disabled>
            </div>

            <!-- campo nacionalidad -->
            <div class="mb-3">
                <label for="nacionalidad" class="form-label">Nacionalidad:</label>
                <input type="text" class="form-control" name="nacionalidad" value="<?= $this->autor->nacionalidad ?>" disabled>
            </div>

            <!-- campo fecha nacimiento -->
            <div class="mb-3">
                <label for="fecha_nac" class="form-label">Fecha Nacimiento:</label>
                <input type="date" class="form-control" name="fecha_nac" value="<?= date('Y-m-d', strtotime($this->autor->fecha_nac)) ?>" disabled>
            </div>

            <!-- campo email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" value="<?= $this->autor->email ?>" disabled>
            </div>

            <!-- campo teléfono -->
            <div class="mb-3">
                <label for="premios" class="form-label">Premios:</label>
                <input type="tel" class="form-control" name="premios" value="<?= $this->autor->premios ?>" disabled>
            </div>

            <!-- botones de acción -->
            <a class="btn btn-secondary" href="<?= URL ?>autor" role="button">Volver</a>

            <br><br><br>
        </main>

    </div>

    <!-- /.container -->

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>