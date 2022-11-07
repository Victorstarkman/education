<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Nuevo Agente</h4>
    </div>
    <div class="results">
        <div class="container mx-auto row">
            <div class="col-12 showForm">
                <p class="title-results">Datos Personales<br/>
                    <small>Los campos indicados con
                        <span style="color:red">*</span> son de llenado obligatorio
                    </small>
                </p>
            </div>
            <?= $this->Flash->render() ?>
            <div class="patientForm container mx-auto row">
                <?= $this->Form->create($patient, ['class' => 'col-lg-12 col-md-12 row', 'id' => 'userForm']) ?>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('name', ['label' => 'Nombre *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('lastname', ['label' => 'Apellido *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('document', ['label' => 'DNI *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true ]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('email', ['label' => 'Email *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('birthday', ['label' => 'Fecha de nacimiento *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('age', ['label' => 'Edad',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('address', ['label' => 'Domicilio',
                            'class' => 'form-control form-control-blue m-0 col-12']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('phone', ['label' => 'Telefono',
                            'class' => 'form-control form-control-blue m-0 col-12']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('job', ['label' => 'Puesto de trabajo',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('seniority', ['label' => 'Antiguedad (años)',
                            'class' => 'form-control form-control-blue m-0 col-12']); ?>
                    </div>
                </div>
                <?= $this->element('partForm/addCity', ['city' => $patient->city_id]); ?>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('company_id', ['label' => 'Empresa',
                            'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione',
                            'options' => $companies, 'required' => true]); ?>
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

