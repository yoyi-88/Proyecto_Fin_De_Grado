<!doctype html>
<html lang="es">

<head>
	<?php require_once 'template/layouts/head.layout.php'; ?>
	<title>Proyecto 6.2 Gestión Libros - geslibros</title>
</head>

<body>
	<!-- Menú fijo superior -->
	<?php require_once("template/partials/menu.principal.partial.php") ?>

	<!-- Capa Principal -->
	<div class="container">
		<br><br><br><br>

		<!-- capa de mensajes -->
		<?php require_once("template/partials/mensaje.partial.php") ?>

		<!-- Estilo card de bootstrap -->
		<div class="card">
			<div class="card-header">
				MVC - PRO
			</div>
			<div class="card-body">
				<?php require_once("template/partials/cabecera.partial.php") ?>
			</div>
			<div class="card-footer">
				Curso 25/26
			</div>
			<!-- Añadimos una imagen para hacer la interfaz más atractiva -->
			<img src="https://static.eldiario.es/clip/f3c3436b-c8f1-4193-938c-dd01205bd9b8_16-9-discover-aspect-ratio_default_0.jpg" alt="librería">
		</div>

	</div>

	<!-- /.container -->

	<?php require_once("template/partials/footer.partial.php") ?>
	<?php require_once("template/layouts/javascript.layout.php") ?>

</body>

</html>