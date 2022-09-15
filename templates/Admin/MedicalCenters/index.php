<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MedicalCenter[]|\Cake\Collection\CollectionInterface $medicalCenters
 */
?>
<div class="medicalCenters index content">
    <?= $this->Html->link(__('New Medical Center'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Medical Centers') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicalCenters as $medicalCenter): ?>
                <tr>
                    <td><?= $this->Number->format($medicalCenter->id) ?></td>
                    <td><?= h($medicalCenter->name) ?></td>
                    <td><?= h($medicalCenter->created) ?></td>
                    <td><?= h($medicalCenter->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $medicalCenter->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $medicalCenter->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $medicalCenter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $medicalCenter->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
