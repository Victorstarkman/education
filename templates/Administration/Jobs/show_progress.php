<?php $actualTime = new \Cake\I18n\FrozenTime(null,  'America/Argentina/Buenos_Aires'); ?>
<p>Informacion se actualiza automaticamente cada 5 segundos. Ultima actualización: <?= $actualTime->format('d/m/Y H:i:s'); ?></p>
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
				<?php if ($progressValue = $job->showProgressBar()) : ?>
					<div class="progress">
						<?php $class = ($progressValue < 100) ? 'progress-bar-striped progress-bar-animated' : ''; ?>
						<div class="progress-bar bg-success <?= $class; ?>" role="progressbar" aria-valuenow="<?= $progressValue; ?>>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $progressValue; ?>%;color: black;font-weight: bold;"><?= $job->getPercentage() ?></div>
					</div>
				<?php else : ?>
					<?= $job->getPercentage() ?>
				<?php endif; ?>
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