<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company $company
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Agregar empresa</h4>
    </div>
    <div class="results">
        <div class="container mx-auto row">
            <div class="col-12 showForm">
                <p class="title-results">Datos de empresa<br/>
                    <small>Los campos indicados con
                        <span style="color:red">*</span> son de llenado obligatorio
                    </small>
                </p>
            </div>
            <?= $this->Flash->render() ?>
            <div class="patientForm container mx-auto row">
                <?= $this->Form->create($company, ['class' => 'col-lg-12 col-md-12 row', 'id' => 'userForm']) ?>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('razon', ['label' => 'Razón social *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('name', ['label' => 'Nombre *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('cuit', ['label' => 'CUIT *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('no_dienst', ['label' => 'Dienst *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true,
                            'empty' => 'Seleccione', 'options' => [0 => 'No', 1 => 'Si']]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('status', ['label' => 'Estado *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true,
                            'empty' => 'Seleccione', 'options' => $statuses]); ?>
                    </div>
                </div>
                <div class="mx-auto form-group row col-lg-12 col-md-12">
                    <div class="pl-0 col-12">
                        <button type="submit" id="guardar" class="btn btn-outline-primary col-12" name="guardar">
                            <i class="far fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
            <?= $this->Form->end();?>
        </div>
    </div>
</div>

