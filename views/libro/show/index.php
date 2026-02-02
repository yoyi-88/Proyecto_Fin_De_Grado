<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
</head>

<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
</head>

<body>
    <?php require_once("template/partials/menu.partial.php") ?>

    <div class="container">
        <br><br><br><br>

        <?php require_once("template/partials/mensaje.partial.php") ?>
        <?php require_once("template/partials/error.partial.php") ?>

        <main>
            <legend><?= $this->title ?></legend>
            <form>
                
                <div class="mb-3">
                    <label for="id" class="form-label">ID:</label>
                    <input type="text" class="form-control" name="id" value="<?= $this->libro->id ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="titulo" value="<?= $this->libro->titulo ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="autor" class="form-label">Autor:</label>
                    <input type="text" class="form-control" name="autor" value="<?= $this->libro->autor ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="editorial" class="form-label">Editorial:</label>
                    <input type="text" class="form-control" name="editorial" value="<?= $this->libro->editorial ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="precio_venta" class="form-label">Precio:</label>
                    <input type="text" class="form-control" name="precio_venta" value="<?= number_format($this->libro->precio_venta, 2, ',', '.') ?> €" disabled>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock:</label>
                    <input type="text" class="form-control" name="stock" value="<?= $this->libro->stock ?>" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="generos" class="form-label">Géneros:</label>
                    <input type="text" class="form-control" name="generos" value="<?= $this->libro->generos ?>" disabled>
                </div>
                
                <a class="btn btn-secondary" href="<?= URL ?>libro" role="button">Volver a la lista</a>
                
            </form>

            <br><br><br>
        </main>

    </div>

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>
</html>