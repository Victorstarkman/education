<?php if (empty($patient)) : ?>
    <div class="alert alert-danger col-lg-12" role="alert">
        <div class="message error">No se encontro ninguna persona con el DNI ingresado. Ingrese los datos de la persona a crear.</div>
    </div>
<?php elseif ($type) : ?>
    <div class="alert alert-info col-lg-12" role="alert">
        <div class="message error">Ingrese los datos de la persona a crear.</div>
    </div>
<?php else : ?>
    <div class="alert alert-success col-lg-12" role="alert">
        <div class="message error">Se encontro la persona <?= $patient->name; ?> <?= $patient->lastname; ?> con el DNI <?= $patient->document; ?></div>
    </div>
<?php endif;?>

<?= $this->Form->create($patient, ['class' => 'col-lg-12 col-md-12 row', 'id' => 'userForm']) ?>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('name', ['label' => 'Nombre *',
            'class' => 'form-control form-control-blue m-0 col-12']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('lastname', ['label' => 'Apellido *',
            'class' => 'form-control form-control-blue m-0 col-12']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('document', ['label' => 'DNI *',
            'class' => 'form-control form-control-blue m-0 col-12']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('email', ['label' => 'Email *',
            'class' => 'form-control form-control-blue m-0 col-12']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('birthday', ['label' => 'Fecha de nacimiento *',
            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('age', ['label' => 'Edad',
            'class' => 'form-control form-control-blue m-0 col-12']); ?>
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
            'class' => 'form-control form-control-blue m-0 col-12']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
		<?= $this->Form->control('company_id', ['label' => 'Empresa',
			'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione',
			'options' => $companies]); ?>
    </div>
</div>
<div class="row col-12">
    <p class="title-results col-12">Tipo de licencia<br/>
        <small>Los campos indicados con
            <span style="color:red">*</span> son de llenado obligatorio
        </small>
    </p>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[].pathology', ['label' => 'Patologia*',
                'class' => 'form-control form-control-blue m-0 col-12']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].startPathology', ['label' => 'Fecha de inicio*',
                'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0][type]', ['label' => 'Tipo de licencia*',
                'class' => 'form-control form-control-blue m-0 col-12', 'options' => $licenses,
                'empty' => 'Seleccione']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0][extraLicenseInfo]', ['label' => 'Nombre del familiar',
                'class' => 'form-control form-control-blue m-0 col-12']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].askedDays', ['label' => 'Días solicitados*',
                'class' => 'form-control form-control-blue m-0 col-12']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].doctor_id', ['label' => 'Auditor*',
                'class' => 'form-control form-control-blue m-0 col-12', 'options' => $doctors,
                'empty' => 'Seleccione']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-12 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].comments', ['label' => 'Comentarios',
                'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'textarea']); ?>
        </div>
    </div>
</div>
<div class="mx-auto form-group row col-lg-12 col-md-12">
    <div class="pl-0 col-12">
        <button type="submit" id="guardar" class="btn btn-outline-primary col-12" name="guardar">
            <i class="far fa-save"></i> Generar
        </button>
    </div>
</div>
<?= $this->Form->end();?>
<?php
$group = $this->Identity->get('groupIdentity');
$prefix = !empty($group['prefix']) ? $group['prefix'] : 'default';
$redirect = !empty($group) ? $group['redirect'] : ''; ?>

<script>
    $('#guardar').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '<?= $this->Url->build($redirect . 'patients/addWithReport/create', ['fullBase' => true]); ?>',
            dataType: "json",
            data: $(".patientForm form").serialize(),
            success: function (response) {
                let data = response.data;
                console.log(data);
            }
        });
    });

    $("#birthday").on('change', function (e) {
        let birthDay = $(this).val(),
            DOB = new Date(birthDay),
            today = new Date(),
            age = today.getTime() - DOB.getTime();
        age = Math.floor(age / (1000 * 60 * 60 * 24 * 365.25));

        $("#age").val(age);
    });

    $('#reports-0-startpathology').on('change', function() {
       // var $validator = $("#userForm").validate();
        let today =new Date(),
            startPathology =  new Date($('#reports-0-startpathology').val());
        if (startPathology > today){
            errors = { name: "Please enter an ID to check" };
            /* Show errors on the form */
            //$validator.showErrors(errors);
        }
    });
</script>
