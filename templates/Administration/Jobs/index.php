<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company[]|\Cake\Collection\CollectionInterface $companies
 */
?>
<div class="mx-auto mt-5 col-12">
	<div class="col-12 title-section">
		<h4>Lista de Scrappers</h4>
	</div>
	<div class="results">
		<div class="mx-auto form-group row col-lg-12 col-md-12">

		</div>
		<p class="title-results">Scrappers</p>
		<?= $this->Flash->render() ?>
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
					<td><?= $job->getPorcentaje() ?></td>
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