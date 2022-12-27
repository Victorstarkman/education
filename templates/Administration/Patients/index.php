<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Candidate[]|\Cake\Collection\CollectionInterface $candidates
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Lista de Agentes</h4>
    </div>
    <div class="results">
        <div class="mx-auto form-group row col-lg-12 col-md-12">
            <div class="pl-0 col-6">
                <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] . '/nuevo-ausente', ['fullBase' => true]); ?>" class="btn btn-outline-primary col-12"><i class="mr-2 fas fa-info-circle" aria-hidden="true"></i>Nueva Auditoría</a>
            </div>
            <div class="pl-0 col-6">
                <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] . '/nuevo-agente', ['fullBase' => true]); ?>" class="btn btn-outline-primary col-12"><i class="mr-2 fas fa-info-circle" aria-hidden="true"></i>Nuevo Agente</a>
            </div>
        </div>
        <p class="title-results">Agentes</p>

        <?= $this->Flash->render() ?>
       <div class="row">
            <?= $this->Form->create(null, ['type' => 'GET', 'class' => 'col-lg-12 col-md-12 row p-0 m-0']) ?>
                <div class="col-lg-4 col-sm-12" >
                    <div class="form-group">
                        <?= $this->Form->control('cuil', [
                            'label' => 'Buscar',
                            'placeholder' => 'Buscar por CUIL',
                            'class' => 'form-control form-control-blue mb-1 col-12',
                            'value' => $search['cuil'] ?? '']); ?>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12" ><a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] . '/listado', ['fullBase' => true]); ?>" class="btn btn-outline-secondary col-12 mt-5">Reiniciar</a></div>
                <div class="col-lg-4 col-sm-12">  <?= $this->Form->button(__('Buscar'), ['class' => 'btn btn-outline-primary col-12 mt-5']) ?></div>
            <?= $this->Form->end() ?>
       </div>
        <table class="table table-bordered" id="tabla_actualizaciones">
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', '#') ?></th>
                <th><?= $this->Paginator->sort('name', 'Nombre') ?></th>
                <th><?= $this->Paginator->sort('cuil', 'CUIL') ?></th>
                <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                <th class="actions"><?= __('Total Reportes') ?></th>
                <th class="actions"><?= __('Sin revisar') ?></th>
                <th class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($patients as $patient) : ?>
                <tr>
                    <td><?= $this->Number->format($patient->id) ?></td>
                    <td><?= h($patient->name) ?></td>
                    <td><?= h($patient->cuil) ?></td>
                    <td><?= h($patient->email) ?></td>
                    <td><?= count($patient->reports) ?></td>
                    <td><?= count($patient->reports_without_check) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(
                            'Ver',
                            $redirectPrefix . '/agente/ver/' . $patient->id,
                            ['fullBase' => true]
                        ); ?>
                        |
                        <?= $this->Html->link(
                            'Editar',
                            $redirectPrefix . '/agente/editar/' . $patient->id,
                            ['fullBase' => true]
                        ); ?>
                        |
                        <?= $this->Html->link(
                            'Ausente',
                            $redirectPrefix . '/nuevo-ausente?dni=' . $patient->document,
                            ['fullBase' => true]
                        ); ?>

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
