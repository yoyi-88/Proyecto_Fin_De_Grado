<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
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
                <div class="card">
                    <div class="card-header">Formulario de Contacto</div>
                    <div class="card-body">
                        <form method="POST"action="<?= URL ?>contact/validate">
                            <!-- token csrf -->
                            <input type="hidden" name="csrf_token"
                                value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                            <!-- campo name -->
                            <div class="mb-3 row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="name"
                                        class="form-control <?= (isset($this->errores['name'])) ? 'is-invalid' : null ?>"
                                        name="name" value="<?= htmlspecialchars($this->contact->name); ?>" required
                                        autocomplete="name" autofocus>
                                    <!-- control de errores -->
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->error['name']  ??= '' ?>
                                    </span>
                                </div>
                            </div>    

                            <!-- campo email -->
                            <div class="mb-3 row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control <?= (isset($this->errores['email'])) ? 'is-invalid' : null ?>"
                                        name="email" value="<?= htmlspecialchars($this->contact->email); ?>" required
                                        autocomplete="email" autofocus>
                                    <!-- control de errores -->
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->error['email']  ??= '' ?>
                                    </span>
                                </div>
                            </div>

                             <!-- campo subject -->
                             <div class="mb-3 row">
                                <label for="subject" class="col-md-4 col-form-label text-md-right">Asunto</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control <?= (isset($this->errores['asunto'])) ? 'is-invalid' : null ?>"
                                        name="subject" value="<?= htmlspecialchars($this->contact->subject); ?>" required
                                        autocomplete="subject" autofocus>
                                    <!-- control de errores -->
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->error['subject']  ??= '' ?>
                                    </span>
                                </div>
                            </div> 
                            
                            <!-- campo message -->
                            <div class="mb-3 row">
                                <label for="message" class="col-md-4 col-form-label text-md-right">Mensaje</label>
                                <div class="col-md-6">
                                    <textarea id="message" type="text"
                                        class="form-control <?= (isset($this->errores['message'])) ? 'is-invalid' : null ?>"
                                        name="message" value="<?= htmlspecialchars($this->contact->message); ?>" required
                                        autocomplete="message" autofocus></textarea>
                                    <!-- control de errores -->
                                    <span class="form-text text-danger" role="alert">
                                        <?= $this->error['message']  ??= '' ?>
                                    </span>
                                </div>
                            </div>

                            <!-- botones de acción -->
                            <div class="mb-3 row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a class="btn btn-secondary" href="<?=URL?>index" role="button">Cancelar</a>
                                    <button type="reset" class="btn btn-secondary" >Reset</button>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
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