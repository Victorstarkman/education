<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MedicalCenter $medicalCenter
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Medical Center'), ['action' => 'edit', $medicalCenter->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Medical Center'), ['action' => 'delete', $medicalCenter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $medicalCenter->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Medical Centers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Medical Center'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="medicalCenters view content">
            <h3><?= h($medicalCenter->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($medicalCenter->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($medicalCenter->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($medicalCenter->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($medicalCenter->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
