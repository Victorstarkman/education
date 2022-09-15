<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Lista de usuarios</h4>
    </div>
    <div class="results">
        <p class="title-results">Usuarios</p>
        <div class="mx-auto form-group row col-lg-12 col-md-12">
            <div class="pl-0 col-12">
                <a href="<?= $this->Url->build( DS . strtolower($this->request->getParam('prefix')) . '/usuarios/agregar', ['fullBase' => true]); ?>" class="btn btn-outline-primary col-12"><i class="mr-2 fas fa-plus-circle" aria-hidden="true"></i>Agregar usuario</a>
            </div>
        </div>
		<?= $this->Flash->render() ?>
        <table class="table table-bordered" id="tabla_actualizaciones">
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('lastname') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('group_id') ?></th>
                <th class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $this->Number->format($user->id) ?></td>
                    <td><?= h($user->name) ?></td>
                    <td><?= h($user->lastname) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= $user->groupName() ?></td>
                    <td class="actions">
						<?= $this->Html->link('<i class="mr-2 fas fa-pencil" aria-hidden="true"></i>', ['action' => 'edit', $user->id], ['class' => 'btn btn-outline-primary text-center', 'escape' => false]) ?>
						<?= $this->Form->postLink('<i class="mr-2 fas fa-trash" aria-hidden="true"></i>', ['action' => 'delete', $user->id], ['class' => 'btn btn-outline-danger text-center', 'escape' => false, 'confirm' => __('Estas por eliminar el registro # {0}, estas seguro?', $user->id)]) ?>
                    </td>
                </tr>
			<?php endforeach;?>
            </tbody>
        </table>
		<?= $this->element('paginator'); ?>
    </div>
</div>
