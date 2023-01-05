<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Candidate[]|\Cake\Collection\CollectionInterface $candidates
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Auditorías Pendientes</h4>
    </div>
    <div class="results">
        <div class="mx-auto form-group row col-lg-12 col-md-12">
            <div class="pl-0 col-6">
                <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] . '/nuevo-ausente', ['fullBase' => true]); ?>" class="btn btn-outline-primary col-12"><i class="mr-2 fas fa-info-circle" aria-hidden="true"></i>Nueva Auditoría</a>
            </div>
            <div class="pl-0 col-6">
                <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] . '/nuevo-agente', ['fullBase' => true]); ?>" class="btn btn-outline-primary col-12"><i class="mr-2 fas fa-info-circle" aria-hidden="true"></i>Nuevo Agente</a>
            </div>

        </div><!-- fin de row -->
        <p class="title-results">Auditorías</p>

        <?= $this->Flash->render() ?>
        <?= $this->Form->create(null, ['type' => 'GET', 'class' => 'col-lg-12 col-md-12 row p-0 m-0']) ?>
        <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('cuil', [
                        'label' => 'Buscar',
                    'placeholder' => 'Buscar por CUIL',
                    'class' => 'form-control form-control-blue m-0 col-12',
                    'value' => $search['cuil'] ?? '']); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control(
                    'modes',
                    [
                         'options' => $getmodes,
                        'label' => 'Estado',
                        'empty' => 'Estado',
                        'class' => 'form-control form-control-blue m-0 col-12',
                        'value' => $search['mode_id'] ?? '']
                ); ?>
            </div>
        </div>
        <?php if($medical_center== 0):?>
         <div class="pt-0 col-lg-2 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('medical_center', [
                        'label' => 'Asignado a',
                    'options' => $getMedicalCenter,
                    'empty' => 'Asignado a',
                    'class' => 'form-control form-control-blue m-0 col-12',
                    'value' => $search['medicalCenter'] ?? '']); ?>
            </div>
        </div>
        <?php endif;?>
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
        <div class="pt-0 col-lg-2 col-sm-12">
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
            <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] . '/listado-sin-resultados', ['fullBase' => true]); ?>" class="btn btn-outline-secondary col-12">Reiniciar</a>
        </div>
        <div class="col-6 mb-3">
            <?= $this->Form->button(__('Buscar'), ['class' => 'btn btn-outline-primary col-12']) ?>
        </div>
        <?= $this->Form->end() ?>
        <table class="table table-bordered" id="tabla_actualizaciones">
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', '#') ?></th>
                <th><?= $this->Paginator->sort('externalID', 'id Ministerio') ?></th>
                <th><?= $this->Paginator->sort('name', 'Nombre') ?></th>
                <th><?= $this->Paginator->sort('cuil', 'Cuil') ?></th>
                <th><?= $this->Paginator->sort('askedDays', 'Días solicitados') ?></th>
                <th><?= $this->Paginator->sort('created', 'Creada') ?></th>
                <th><?= $this->Paginator->sort('mode', 'Estado') ?></th>
                <th><?= $this->Paginator->sort('medicalCenter', 'Asignado a') ?></th>
                <th class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reports as $report) : ?>
                <tr>
                    <td><?= $this->Number->format($report->id) ?></td>
                    <td><?= $this->Number->format($report->externalID) ?></td>
                    <td><?= h($report->patient->name) ?></td>
                    <td><?= $report->patient->cuil; ?></td>
                     <!-- <td><?//= $report->getSpeciality(); ?></td>   -->
                    <td><?= $report->askedDays; ?></td>
                    <td><?= $report->created->format('d/m/Y'); ?></td>
                    <td><?= $report->mode->name; ?></td>
                    <td><?= (!is_null($report->medical_center) && !is_null($report->medical_center->district)) ? $report->medical_center->district : 'NO DEFINIDO'; ?></td>
                    <td class="actions">
                        <?php if ($report->isWaitingResults()) :
                            echo $this->Html->link('Editar', $redirectPrefix . '/licencias/editar/' . $report->id, ['fullBase' => true]);
                            echo ' | ';
                            echo $this->Form->postLink(
                                __('Eliminar'),
                                [
                                    'controller' => 'Patients',
                                    'action' => 'deleteReport', $report->id],
                                [
                                    'confirm' => __(
                                        'Estas seguro que queres eliminar la licencia # {0}?',
                                        $report->id
                                    ),
                                ]
                            );
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
