<!--
	25/10/21 actualizacion de estilos
 -->
<!doctype html>
<html lang="en">
<head>
	<title>
		Dienst - <?= $this->fetch('title') ?>
	</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<?= $this->Html->meta('icon', 'favicon.jpg'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<?= $this->Html->css(
		[
            'general/generalStyles',
			'general/login',
            'general/forms',
			'general/icons',

		]) ?>
	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
	<?= $this->fetch('script') ?>
	<script src="https://code.jquery.com/jquery-3.4.1.js"
	        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
</head>
<body class="text-center login">
<div class="m-0 row h-100">
	<div class="mx-auto mt-5 mb-5 show-md col-12">
		<?= $this->Html->image('logo-blue.png', ['alt' => 'Logo Dienst']); ?>
	</div>
	<div class="hide-md col-lg-6 col-md-6 col-sm-12 left-column">
		<div class="row align-items-center vh-100">
			<div class="mx-auto col-12">
				<?= $this->Html->image('logo-white.png', ['alt' => 'Logo Dienst']); ?>
			</div>
		</div>
	</div>
	<div class="text-center col-lg-6 col-md-6 col-sm-12 right-column align-self-center position-static">
		<div class="container">
			<?= $this->fetch('content') ?>
		</div>
		<footer class="mt-1 mb-1 text-right">
			<span class="text-muted">2022 Dienst Consulting. Todos los derechos reservados.</span>
		</footer>
	</div>
</div>
</body>
</html>
