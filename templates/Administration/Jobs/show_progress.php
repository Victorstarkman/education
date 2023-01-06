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
