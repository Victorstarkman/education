<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Candidate[]|\Cake\Collection\CollectionInterface $candidates
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Lista de agentes sin diagnosticar</h4>
    </div>
    <div class="results">
        <p class="title-results">Agentes</p>

        <?= $this->Flash->render() ?>
        <?= $this->Form->create(null, ['type' => 'GET', 'class' => 'col-lg-12 col-md-12 row p-0 m-0']) ?>
        <div class="pt-0 col-lg-3 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('document', [
                    'label' => 'Buscar',
                    'placeholder' => 'Buscar por DNI o Email',
                    'class' => 'form-control form-control-blue m-0 col-12',
                    'value' => $search['document'] ?? '']); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-3 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control(
                    'license_type',
                    [
                        'options' => $getLicenses,
                        'label' => 'Tipo de licencia',
                        'empty' => 'Licencia',
                        'class' => 'form-control form-control-blue m-0 col-12',
                        'value' => $search['license_type'] ?? '']
                ); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-3 col-sm-12">
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
        <div class="pt-0 col-lg-3 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control(
                    'end_date',
                    [
                        'label' => 'Creada hasta',
                        'type' => 'date',
                        'class' => 'form-control form-control-blue m-0 col-12',
                        'value' => $search['end_date'] ?? '']
                ); ?>
            </div>
        </div>
        <div class="col-6 mb-3">
            <?php echo $this->Html->link(
                'Reniciar',
                $redirectPrefix . '/',
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
                <th><?= $this->Paginator->sort('age', 'Edad') ?></th>
                <th><?= $this->Paginator->sort('type', 'Licencia') ?></th>
                <th><?= $this->Paginator->sort('pathology', 'Patologia') ?></th>
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
                    <td><?= $this->Number->format($report->patient->age) ?></td>
                    <td><?= $report->getNameLicense(); ?></td>
                    <td><?= $report->pathology; ?></td>
                    <td><?= $report->askedDays; ?></td>
                    <td class="actions">
                        <?= $this->Html->link('Diagnosticar', $redirectPrefix . '/licencias/diagnosticar/' . $report->id, ['fullBase' => true]); ?>
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
