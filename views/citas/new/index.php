<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 font-serif">Solicitar Experiencia</h3>

                    <?php if (isset($this->error)): ?>
                        <div class="alert alert-danger"><?= $this->error ?></div>
                    <?php endif; ?>

                    <form action="<?= URL ?>citas/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">FECHA</label>
                                <input type="text" name="fecha" id="calendario" class="form-control"
                                    placeholder="Selecciona un día..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">HORA</label>
                                <input type="time" name="hora" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">SELECCIONA MENÚ</label>
                            <select name="menu_id" class="form-select form-select-lg" required>
                                <option value="" disabled selected>-- Ver opciones disponibles --</option>
                                <?php foreach ($this->menus as $menu): ?>
                                    <option value="<?= $menu->id ?>">
                                        <?= $menu->nombre ?> (<?= number_format($menu->precio, 2) ?>€)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg">Confirmar Solicitud</button>
                            <a href="<?= URL ?>citas" class="btn btn-link text-muted mt-2">Cancelar</a>
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

        // Hacemos una petición (Fetch) a nuestra ruta PHP para obtener los días ocupados
        fetch('<?= URL ?>citas/disponibilidad')
            .then(response => response.json())
            .then(diasOcupados => {

                //Iniciamos el calendario Flatpickr con las reglas que queremos
                flatpickr("#calendario", {
                    locale: "es",           // Idioma español
                    minDate: "today",       // No pueden reservar en el pasado (desde hoy)
                    dateFormat: "Y-m-d",    // Formato que necesita la base de datos (YYYY-MM-DD)
                    disable: [
                        // Bloqueamos las fechas que vienen de la Base de Datos
                        function (date) {
                            // Formateamos la fecha del calendario para que coincida con la BD (YYYY-MM-DD)
                            let fechaStr = date.getFullYear() + "-" +
                                String(date.getMonth() + 1).padStart(2, '0') + "-" +
                                String(date.getDate()).padStart(2, '0');

                            return diasOcupados.includes(fechaStr); // Si la fecha está en el array, se bloquea
                        },

                        // Bloqueamos los DOMINGOS (0) y LUNES (1) porque el Chef descansa
                        function (date) {
                            return (date.getDay() === 0 || date.getDay() === 1);
                        }
                    ]
                });

            })
            .catch(error => console.error("Error al cargar la disponibilidad:", error));
    });
</script>