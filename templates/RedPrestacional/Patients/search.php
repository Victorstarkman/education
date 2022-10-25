<?php if (empty($patient)) : ?>
    <div class="alert alert-danger col-lg-12 searchAlert" role="alert">
        <div class="message error">No se encontro ninguna persona con el DNI ingresado. Ingrese los datos de la persona a crear.</div>
    </div>
<?php elseif ($type == 'new') : ?>
    <div class="alert alert-info col-lg-12 searchAlert" role="alert">
        <div class="message error">Ingrese los datos de la persona a crear.</div>
    </div>
<?php else : ?>
    <div class="alert alert-success col-lg-12 searchAlert" role="alert">
        <div class="message error">Se encontro la persona <?= $patient->name; ?> <?= $patient->lastname; ?> con el DNI <?= $patient->document; ?></div>
    </div>
<?php endif;?>
<div class="alert alert-danger col-lg-12 errors" role="alert" style="display: none;">
    <div class="message error" id="errors"></div>
</div>
<?= $this->Form->create($patient, ['class' => 'col-lg-12 col-md-12 row', 'id' => 'userForm']) ?>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('type', ['label' => 'type *',
            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'hidden', 'value' => $type]); ?>

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
            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
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
            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('phone', ['label' => 'Telefono',
            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
    </div>
</div>
<?= $this->element('partForm/addCity', ['city' => !empty($patient) && isset($patient->city_id) ? $patient->city_id : null]); ?>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('job', ['label' => 'Puesto de trabajo*',
            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group">
        <?= $this->Form->control('company_id', ['label' => 'Empresa',
            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true, 'empty' => 'Seleccione',
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
            <?= $this->Form->control('reports[0].mode_id', ['label' => 'Tipo de Servicio *',
                'class' => 'form-control form-control-blue m-0 col-12 select2', 'required' => true, 'empty' => 'Seleccione']); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0][speciality_id]', ['label' => 'Especialidad *',
	            'class' => 'form-control form-control-blue m-0 col-12 select2', 'options' => $specialties,
	            'empty' => 'Seleccione', 'required' => true]); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-12 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0][type]', ['label' => 'Tipo de licencia*',
                'class' => 'form-control form-control-blue m-0 col-12 select2', 'options' => $licenses,
                'empty' => 'Seleccione', 'required' => true]); ?>
        </div>
    </div>
    <div class="familiar row col-12 mx-auto" style="display: none;">
        <div class="pt-0 col-lg-4 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('reports[0][relativeName]', ['label' => 'Nombre del familiar *',
                    'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-4 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('reports[0][relativeLastname]', ['label' => 'Apellido del familiar*',
                    'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-4 col-sm-12">
            <div class="form-group">
                <?= $this->Form->control('reports[0][relativeRelationship]', ['label' => 'Relación *',
                    'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
            </div>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].startPathology', ['label' => 'Fecha de Solicitud*',
                'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date', 'required' => true]); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-6 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].askedDays', ['label' => 'Días solicitados*',
                'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
        </div>
    </div>
    <div class="medicoPersonal row col-12 mx-auto">
        <div class="pt-0 col-lg-6 col-sm-12">
            <div class="form-group">
			    <?= $this->Form->control('personalDoctorName', ['label' => 'Nombre Medico Particular <span style="display:none">*</span>','escape' => false,
				    'class' => 'form-control form-control-blue m-0 col-12']); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-6 col-sm-12">
            <div class="form-group">
			    <?= $this->Form->control('personalDoctorLastname', ['label' => 'Apellido Medico Particular <span style="display:none">*</span>','escape' => false,
				    'class' => 'form-control form-control-blue m-0 col-12']); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-6 col-sm-12">
            <div class="form-group">
			    <?= $this->Form->control('personalDoctorMP', ['label' => 'M.P. Medico Particular <span style="display:none">*</span>','escape' => false,
				    'class' => 'form-control form-control-blue m-0 col-12']); ?>
            </div>
        </div>
        <div class="pt-0 col-lg-6 col-sm-12">
            <div class="form-group">
			    <?= $this->Form->control('personalDoctorMN', ['label' => 'M.N. Medico Particular <span style="display:none">*</span>','escape' => false,
				    'class' => 'form-control form-control-blue m-0 col-12']); ?>
            </div>
        </div>
    </div>

    <div class="pt-0 col-lg-12 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].comments', ['label' => 'Comentarios',
                'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'textarea']); ?>
            <?= $this->Form->control('go_to', ['label' => false,
                'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'hidden' , 'value' => 1]); ?>
        </div>
    </div>
    <div class="pt-0 col-lg-12 col-sm-12">
        <div class="form-group">
            <?= $this->Form->control('reports[0].doctor_id', ['label' => 'Auditor*',
                'class' => 'form-control form-control-blue m-0 col-12 select2', 'options' => $doctors,
                'empty' => 'Seleccione', 'required' => true]); ?>
        </div>
    </div>
</div>
<div class="mx-auto form-group row col-lg-6 col-md-12">
    <div class="pl-0 col-12">
        <button type="submit" id="guardar_files" class="btn btn-outline-primary col-12" name="files">
            <i class="fa fa-upload"></i> Subir archivos
        </button>
    </div>
</div>
<div class="mx-auto form-group row col-lg-6 col-md-12">
    <div class="pl-0 col-12">
        <button type="submit" id="guardar" class="btn btn-outline-primary col-12" name="guardar">
            <i class="far fa-save"></i> Finalizar
        </button>
    </div>
</div>
<?= $this->Form->end();?>

<?php
$group = $this->Identity->get('groupIdentity');
$prefix = !empty($group['prefix']) ? $group['prefix'] : 'default';
$redirect = !empty($group) ? $group['redirect'] : ''; ?>

<script>
    $('.select2').select2();
    $('#guardar_files').on('click', function (e) {
        e.preventDefault();
        $('#go-to').val(2);
        $('#guardar').click();
    });

    $('#guardar').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '<?= $this->Url->build($redirect . 'patients/addWithReport/create', ['fullBase' => true]); ?>',
            dataType: "json",
            data: $(".patientForm form").serialize(),
            success: function (response) {
                let data = response.data;
                if (data.error) {
                    $('.errors div').html(data.message).css('display', 'block');
                    $('.errors').show();
                    $('.searchAlert').hide();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    window.location.href = data.goTo;
                }
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

    $("#reports-0-type").on('change', function (e) {
        let $familiar = $('.familiar');
        switch ($(this).val()) {
            case "3":
                $familiar.slideDown();
                $('input, textarea',$familiar).each(function () {
                    $(this).attr('required', true);
                });
                break;
            default:
                $familiar.slideUp();
                $('input, textarea',$familiar).each(function () {
                    $(this).val('');
                    $(this).attr('required', false);
                });

        }
    });

    $("#reports-0-askeddays").on('change', function (e) {
        let $familiar = $('.medicoPersonal');
        if ($(this).val() > 2) {
            $('input, textarea',$familiar).each(function () {
                $(this).attr('required', true);
            });
            $('label span',$familiar).show();
        } else {
            $('input, textarea',$familiar).each(function () {
                $(this).val('');
                $(this).attr('required', false);
            });
            $('label span',$familiar).hide();
        }
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
