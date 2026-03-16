<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <?php require_once("template/partials/mensaje.partial.php") ?>
            <?php require_once("template/partials/error.partial.php") ?>
            
            <div class="form-card shadow-sm">
                <div class="form-card-header bg-dark text-white">
                    <h5 class="form-card-title"><i class="bi bi-envelope-paper"></i> Formulario de Contacto</h5>
                </div>
                
                <div class="form-card-body">
                    <p class="text-muted mb-4">¿Tienes alguna duda sobre nuestros menús o servicios de Chef Privado? Escríbenos y te responderemos lo antes posible.</p>

                    <form method="POST" action="<?= URL ?>contact/validate">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="custom-label">Tu Nombre</label>
                                    <input id="name" type="text" class="form-control <?= (isset($this->errores['name'])) ? 'is-invalid' : '' ?>" name="name" value="<?= htmlspecialchars($this->contact->name ?? ''); ?>" required autocomplete="name" autofocus>
                                    <small class="form-error"><?= $this->error['name'] ?? '' ?></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="custom-label">Tu Email</label>
                                    <input id="email" type="email" class="form-control <?= (isset($this->errores['email'])) ? 'is-invalid' : '' ?>" name="email" value="<?= htmlspecialchars($this->contact->email ?? ''); ?>" required autocomplete="email">
                                    <small class="form-error"><?= $this->error['email'] ?? '' ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="custom-label">Asunto</label>
                            <input id="subject" type="text" class="form-control <?= (isset($this->errores['asunto'])) ? 'is-invalid' : '' ?>" name="subject" value="<?= htmlspecialchars($this->contact->subject ?? ''); ?>" required autocomplete="subject">
                            <small class="form-error"><?= $this->error['subject'] ?? '' ?></small>
                        </div>

                        <div class="form-group">
                            <label for="message" class="custom-label">Mensaje</label>
                            <textarea id="message" class="form-control <?= (isset($this->errores['message'])) ? 'is-invalid' : '' ?>" name="message" rows="5" required autocomplete="off"><?= htmlspecialchars($this->contact->message ?? ''); ?></textarea>
                            <small class="form-error"><?= $this->error['message'] ?? '' ?></small>
                        </div>

                        <div class="form-actions border-top-actions mt-4 pt-4">
                            <a class="btn btn-light" href="<?= URL ?>index">Cancelar</a>
                            <button type="submit" class="btn btn-dark fw-bold">
                                <i class="bi bi-send me-2"></i> Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>