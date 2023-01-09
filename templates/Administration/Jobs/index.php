<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company[]|\Cake\Collection\CollectionInterface $companies
 */
?>
<div class="mx-auto mt-5 col-12">
	<div class="col-12 title-section">
		<h4>Lista de Estado de Procesamiento de Datos</h4>
	</div>
	<div class="results">
		<div class="mx-auto form-group row col-lg-12 col-md-12">

		</div>
		<p class="title-results">Procesamiento de Datos</p>
        <div class="col-4 offset-8 mb-3 pr-0">
            <button type="submit" id="procesar-datos" class="btn btn-outline-primary col-12"
                <i class="fa fa-play"></i> Procesar datos
            </button>
        </div>
        <div class="alert col-lg-12 mensajeProcesando" role="alert"  style="display: none;">
            <div class="message"></div>
        </div>
		<?= $this->Flash->render() ?>
        <div class="show-results">
	        <?php $actualTime = new \Cake\I18n\FrozenTime(null,  'America/Argentina/Buenos_Aires'); ?>
            <table class="table table-bordered" id="tabla_actualizaciones">
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id', __('#')) ?></th>
                    <th><?= $this->Paginator->sort('razon', __('Tipo')) ?></th>
                    <th><?= $this->Paginator->sort('name', __('Estado')) ?></th>
                    <th><?= $this->Paginator->sort('cuit', __('Porcentaje')) ?></th>
                </tr>
                </thead>
                <tbody>
		        <?php foreach ($jobs as $job) : ?>
                    <tr>
                        <td><?= $this->Number->format($job->id) ?></td>
                        <td><?= h($job->getName()) ?></td>
                        <td><?= h($job->getStatus()) ?></td>
                        <td>
	                        <?= $job->progressBar(); ?>
                        </td>
                    </tr>
		        <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="row">
                    <div class="pt-0 col-lg-8 col-sm-12">
				        <?= $this->element('paginator'); ?>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<?php $this->start('scriptBottom');
$group = $this->Identity->get('groupIdentity');
$prefix = !empty($group['prefix']) ? $group['prefix'] : 'default';
$redirect = !empty($group) ? $group['redirect'] : ''; ?>
<script>
    function reload() {
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build($redirect . 'jobs/showProgress/', ['fullBase' => true]); ?>',
            dataType: "html",
            success: function (response) {
                $('.show-results').html(response)
            }
        });
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build($redirect . 'jobs/checkProcess/', ['fullBase' => true]); ?>',
            dataType: "json",
            success: function (response) {
                let data = response.data,
                    text = '',
                    buttonStatus = true;

                if (data.running) {
                    text = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Procesando';
                } else {
                    text = '<i class="fa fa-play"></i> Procesar datos';
                    buttonStatus = false;
                }

                $("#procesar-datos")
                    .html(text)
                    .attr('disabled', buttonStatus);
            }
        });
    }

    setInterval(reload, 5000);

    $("#procesar-datos").on('click', function () {
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build($redirect . 'jobs/run/', ['fullBase' => true]); ?>',
            dataType: "json",
            success: function (response) {
                let data = response.data,
                    text = '',
                    buttonStatus = true,
                    addClass = '',
                    removeClass = '';
                if (!data.error) {
                    addClass = 'alert-success';
                    removeClass = 'alert-danger';
                    if (data.running) {
                        text = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Procesando';
                    } else {
                        text = '<i class="fa fa-play"></i> Procesar datos';
                        buttonStatus = false;
                    }
                } else {
                    text = '<i class="fa fa-play"></i> Procesar datos';
                    buttonStatus = false;
                    addClass = 'alert-danger';
                    removeClass = 'alert-success';

                }

                $("#procesar-datos")
                    .html(text)
                    .attr('disabled', buttonStatus);
                $('.mensajeProcesando .message').html(data.msg);
                $('.mensajeProcesando').removeClass(removeClass).addClass(addClass).show();


            }
        });
    });
</script>
<?php $this->end(); ?>

