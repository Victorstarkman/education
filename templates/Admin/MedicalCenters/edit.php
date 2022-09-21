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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $medicalCenter->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $medicalCenter->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Medical Centers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="medicalCenters form content">
            <?= $this->Form->create($medicalCenter) ?>
            <fieldset>
                <legend><?= __('Edit Medical Center') ?></legend>
                <?php
                    echo $this->Form->control('name');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
