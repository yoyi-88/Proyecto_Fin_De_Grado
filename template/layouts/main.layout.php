<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->title ?? 'Chef Privado' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <link href="<?= URL ?>public/css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 56px; /* Ajuste fino para la navbar */
            background-color: #f8f9fa; /* Fondo gris muy suave para toda la web */
        }
        h1, h2, h3, h4, .navbar-brand { font-family: 'Playfair Display', serif; }
        main { flex: 1; }
    </style>
</head>
<body>

    <?php include 'template/partials/menu.principal.partial.php'; ?>

    <main>

        <?php include 'views/' . $this->view . '.php'; ?>
        
    </main>

    <?php include 'template/partials/footer.partial.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>