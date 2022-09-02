<!doctype html>
<html lang="es">
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
	        [   'general/forms',
                'general/icons',
                'general/generalStyles'
            ]) ?>
	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
    <script src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
</head>
<body  class="home">
<div class="row m-0">
	<?= $this->element('aside-bar'); ?>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-12 content">
	    <?= $this->element('top-bar'); ?>
		<?= $this->fetch('content') ?>
    </div>
</div>
<?= $this->element('footer'); ?>   
</body>
</html>
