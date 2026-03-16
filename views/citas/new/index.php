<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

<div class="container form-page">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="form-card shadow-sm">
                <div class="form-card-header bg-dark text-white">
                    <h5 class="form-card-title"><i class="bi bi-calendar-plus"></i> Solicitar Experiencia</h5>
                </div>
                
                <div class="form-card-body">
                    <?php if (isset($this->error)): ?>
                        <div class="alert alert-danger"><?= $this->error ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>citas/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="custom-label">FECHA</label>
                                    <input type="text" name="fecha" id="calendario" class="form-control" placeholder="Selecciona un día..." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="custom-label">HORA</label>
                                    <input type="time" name="hora" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="custom-label">SELECCIONA MENÚ</label>
                            <select name="menu_id" class="form-select form-select-lg" required>
                                <option value="" disabled selected>-- Ver opciones disponibles --</option>
                                <?php foreach ($this->menus as $menu): ?>
                                    <option value="<?= $menu->id ?>">
                                        <?= $menu->nombre ?> (<?= number_format($menu->precio, 2) ?>€)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-actions border-top-actions mt-4 pt-4">
                            <a href="<?= URL ?>citas" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-dark fw-bold">Confirmar Solicitud</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetch('<?= URL ?>citas/disponibilidad')
            .then(response => response.json())
            .then(diasOcupados => {
                flatpickr("#calendario", {
                    locale: "es",           
                    minDate: "today",       
                    dateFormat: "Y-m-d",    
                    disable: [
                        function (date) {
                            let fechaStr = date.getFullYear() + "-" +
                                String(date.getMonth() + 1).padStart(2, '0') + "-" +
                                String(date.getDate()).padStart(2, '0');
                            return diasOcupados.includes(fechaStr);
                        },
                        function (date) {
                            return (date.getDay() === 0 || date.getDay() === 1);
                        }
                    ]
                });
            })
            .catch(error => console.error("Error al cargar la disponibilidad:", error));
    });
</script>