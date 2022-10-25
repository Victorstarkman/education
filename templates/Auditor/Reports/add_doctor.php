<?= $this->Flash->render() ?>
<div class="patientForm">
	<?= $this->Form->create($privateDoctor, ['class' => 'row', 'id' => 'userForm']) ?>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
			<?= $this->Form->control('name', ['label' => 'Nombre *',
				'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
			<?= $this->Form->control('lastname', ['label' => 'Apellido *',
				'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
			<?= $this->Form->control('licenseNational', ['label' => 'Matricula nacional',
				'class' => 'form-control form-control-blue m-0 col-12']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
			<?= $this->Form->control('license', ['label' => 'Matricula Provincial',
				'class' => 'form-control form-control-blue m-0 col-12']); ?>
        </div>
    </div>
    <div class="modal-footer w-100" style="justify-content: flex-end!important;">
        <button type="button" class="btn btn-outline-secondary" onclick="$('.modal').modal('hide');">Cerrar</button>
        <button type="submit" id="guardar" class="btn btn-outline-primary" name="guardar">
            <i class="far fa-save"></i> Guardar
        </button>
    </div>
	<?= $this->Form->end();?>
</div>


