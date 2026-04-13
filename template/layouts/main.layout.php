<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->title ?? 'Chef Privado' ?></title>

    <link rel="stylesheet" href="<?= URL ?>public/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link href="<?= URL ?>public/css/style.css" rel="stylesheet">

</head>

<body>

    <?php include 'template/partials/menu.principal.partial.php'; ?>

    <main>

        <?php include 'views/' . $this->view . '.php'; ?>

    </main>

    <?php include 'template/partials/footer.partial.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                duration: 800,   
                easing: 'ease-out-cubic', // Curva de aceleración suave
                once: true,      // La animación solo ocurre la primera vez que bajas
                offset: 50       // Cuántos píxeles antes de llegar al elemento se dispara
            });
        });
    </script>
</body>

</html>