<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company[]|\Cake\Collection\CollectionInterface $companies
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Lista de empresas</h4>
    </div>
    <div class="results">
        <div class="mx-auto form-group row col-lg-12 col-md-12">
            <div class="pl-0 col-12">
                <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] .
                    '/empresas/crear', ['fullBase' => true]); ?>" class="btn btn-outline-primary col-12">
                    <i class="mr-2 fas fa-info-circle" aria-hidden="true"></i>Nueva empresa</a>
            </div>
        </div>
        <p class="title-results">Empresas</p>
        <?= $this->Flash->render() ?>
        <table class="table table-bordered" id="tabla_actualizaciones">
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', __('#')) ?></th>
                <th><?= $this->Paginator->sort('razon', __('Razón social')) ?></th>
                <th><?= $this->Paginator->sort('name', __('Nombre')) ?></th>
                <th><?= $this->Paginator->sort('cuit', __('CUIT')) ?></th>
                <th><?= $this->Paginator->sort('no_dienst', __('Dienst')) ?></th>
                <th><?= $this->Paginator->sort('status', __('Estado')) ?></th>
                <th class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($companies as $company) : ?>
                <tr>
                    <td><?= $this->Number->format($company->id) ?></td>
                    <td><?= h($company->razon) ?></td>
                    <td><?= h($company->name) ?></td>
                    <td><?= h($company->cuit) ?></td>
                    <td><?= $company->isDienst(); ?></td>
                    <td><?= $company->getNameStatus();?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $company->id]) ?>
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
