<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Candidate[]|\Cake\Collection\CollectionInterface $candidates
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Lista de pacientes sin diagnosticar</h4>
    </div>
    <div class="results">
        <p class="title-results">Pacientes</p>

        <?= $this->Flash->render() ?>
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
