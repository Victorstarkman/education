<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Candidate[]|\Cake\Collection\CollectionInterface $candidates
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Lista de Auditorías</h4>
    </div>
    <div class="results">
        <p class="title-results">Agentes</p>

        <?= $this->Flash->render() ?>
        <?= $this->Form->create(null, ['type' => 'GET', 'class' => 'col-lg-12 col-md-12 row p-0 m-0']) ?>
        <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('document', [
                    'label' => 'Buscar',
                    'placeholder' => 'Buscar por DNI o Email',
                    'class' => 'form-control form-control-blue m-0 col-12',
                    'value' => $search['document'] ?? '']); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('modes_id', [
                                'label' => 'Tipo de Servicio',
                                'class' => 'form-control form-control-blue m-0 col-12',
                                'required' => true,
                                'empty' => 'Seleccione',
                                'value' => $search['modes']?? '']);
                                ?>

            </div>
        </div>
        <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control(
                    'status',
                    [
                        'options' => $getStatuses,
                        'label' => 'Estado',
                        'empty' => 'Estado',
                        'class' => 'form-control form-control-blue m-0 col-12',
                        'value' => $search['status'] ?? '']
                ); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
			    <?= $this->Form->control(
				    'company_id',
				    [
					    'options' => $companies,
					    'label' => 'Empresa',
					    'empty' => 'Todas',
					    'class' => 'form-control form-control-blue m-0 col-12',
					    'value' => $search['company_id'] ?? '']
			    ); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control(
                    'start_date',
                    [
                        'label' => 'Creada desde',
                        'type' => 'date',
                        'class' => 'form-control form-control-blue m-0 col-12',
                        'value' => $search['start_date'] ?? '']
                ); ?>
            </div>
        </div>
        <div class="col-6 mb-3">
            <?php echo $this->Html->link(
                'Reiniciar',
                $redirectPrefix . '/listado/',
                ['fullBase' => true, 'class' => 'btn btn-outline-secondary col-12']
            );
?>
        </div>
        <div class="col-6 mb-3">
            <?= $this->Form->button(__('Buscar'), ['class' => 'btn btn-outline-primary col-12']) ?>
        </div>
        <?= $this->Form->end() ?>
        <table class="table table-bordered" id="tabla_actualizaciones">
            <thead>
            <tr>
            <th><?= $this->Paginator->sort('id', '#') ?></th>
                <th><?= $this->Paginator->sort('name', 'Nombre') ?></th>
                <th><?= $this->Paginator->sort('lastname', 'Apellido') ?></th>
                <th><?= $this->Paginator->sort('company','Empresa')?></th>
                <th><?= $this->Paginator->sort('created', 'fecha de solictud') ?></th>
                <th><?= $this->Paginator->sort('mode','Tipo de Servicio')?></th>
                <th><?= $this->Paginator->sort('area', 'Especialidad') ?></th>
                <th><?= $this->Paginator->sort('askedDays', 'Días solicitados') ?></th>
                <th class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reports as $report) : ?>
                <tr>
                <td><?= $this->Number->format($report->id) ?></td>
                    <td><?= h($report->patient->name) ?></td>
                    <td><?= h($report->patient->lastname) ?></td>
                    <td><?= h($report->patient->company->name)?></td>
                    <td><?= $report->created->format('d/m/Y'); ?></td>
                    <td><?= !empty($report->mode->name)?h($report->mode->name):''?></td>
                    <td><?= $report->getSpeciality(); ?></td>
                    <td><?= $report->askedDays; ?></td>
                    <td class="actions">
                        <?php if ($report->isWaitingResults() && $report->isOwner($this->Identity->get('id'))) :
                            echo $this->Html->link('Tomar', $redirectPrefix . '/licencias/diagnosticar/' . $report->id, ['fullBase' => true]);
                        else :
                             echo $this->Html->link('Ver', $redirectPrefix . '/licencias/ver/' . $report->id, ['fullBase' => true]);
                        endif;?>
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
