<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Auditoría</h4>
    </div>
    <div class="results">
        <div class="container mx-auto row">
            <?= $this->Flash->render() ?>
            <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                <div class="message error">Datos del agente</div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Nombre')?></th>
                    <th><?= __('Apellido') ?></th>
                    <th><?= __('DNI') ?></th>
                    <th><?= __('Email') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->patient->name) ?></td>
                    <td><?= h($report->patient->lastname) ?></td>
                    <td><?= h($report->patient->document) ?></td>
                    <td><?= h($report->patient->email) ?></td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Fecha de nacimiento')?></th>
                    <th><?= __('Edad') ?></th>
                    <th><?= __('Domicilio') ?></th>
                    <th><?= __('Localidad') ?></th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->patient->birthday) ?></td>
                    <td><?= h($report->patient->age) ?></td>
                    <td><?= h($report->patient->address) ?></td>
                    <td><?= $report->patient->getLocation() ?></td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Telefono') ?></th>
                    <th><?= __('Puesto de trabajo') ?></th>
                    <th><?= __('Empresa') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->patient->phone) ?></td>
                    <td><?= h($report->patient->job) ?></td>
                    <td><?= h($report->patient->company->name) ?></td>
                </tr>
                </tbody>
            </table>
            <div class="alert alert-danger col-lg-12 errors" role="alert" style="display: none;">
                <div class="message error"></div>
            </div>
            <?= $this->Form->create($report, ['class' => 'col-lg-12 col-md-12 row', 'id' => 'userForm']) ?>
            <div class="row col-12">
                <p class="title-results col-12">Tipo de licencia<br/>
                    <small>Los campos indicados con
                        <span style="color:red">*</span> son de llenado obligatorio
                    </small>
                </p>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('id', ['label' => 'Patologia*',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true, 'type' => 'hidden']); ?>
                        <?= $this->Form->control('mode_id', ['label' => 'Tipo de Servicio *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true, 'empty' => 'Seleccione']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('area', ['label' => 'Especialidad *',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('type', ['label' => 'Tipo de licencia*',
                            'class' => 'form-control form-control-blue m-0 col-12', 'options' => $licenses,
                            'empty' => 'Seleccione', 'required' => true]); ?>
                    </div>
                </div>
                <div class="familiar row col-12 mx-auto" <?php if ($report['type'] !== 3) { echo 'style="display: none;"'; } ?>>
                    <div class="pt-0 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('relativeName', ['label' => 'Nombre del familiar *',
                                'class' => 'form-control form-control-blue m-0 col-12', 'required' => ($report['type'] !== 3) ? false : true]); ?>
                        </div>
                    </div>
                    <div class="pt-0 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('relativeLastname', ['label' => 'Apellido del familiar*',
                                'class' => 'form-control form-control-blue m-0 col-12', 'required' => ($report['type'] !== 3) ? false : true]); ?>
                        </div>
                    </div>
                    <div class="pt-0 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('relativeRelationship', ['label' => 'Relación *',
                                'class' => 'form-control form-control-blue m-0 col-12', 'required' => ($report['type'] !== 3) ? false : true]); ?>
                        </div>
                    </div>
                </div>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('startPathology', ['label' => 'Fecha de Solicitud*',
                            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('askedDays', ['label' => 'Días solicitados*',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-group">
	                    <?= $this->Form->control('privatedoctor_id', ['label' => 'Medico Particular *',
		                    'class' => 'form-control form-control-blue m-0 col-12', 'options' => $privateDoctors, 'required' => true, 'empty' => 'Seleccione', 'value'=> $report['privatedoctor_id']]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('comments', ['label' => 'Observaciones',
                            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'textarea']); ?>
                        <?= $this->Form->control('go_to', ['label' => false,
                            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'hidden' , 'value' => 1]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('doctor_id', ['label' => 'Auditor*',
                            'class' => 'form-control form-control-blue m-0 col-12', 'options' => $doctors,
                            'empty' => 'Seleccione', 'required' => true]); ?>
                    </div>
                </div>
                <div class="col-12">
                    <p class="title-results">Archivos e imagenes (Máx. 10 Archivos hasta 10MB cada uno)</p>
                </div>
                <div id="fileuploader" class="col-12">Cargar</div>
            </div>
            <div class="mx-auto form-group row col-lg-12 col-md-12 mt-3">
                <div class="pl-0 col-12">
                    <button type="submit" id="guardar" class="btn btn-outline-primary col-12" name="guardar">
                        <i class="far fa-save"></i> Finalizar
                    </button>
                </div>
            </div>
            <?= $this->Form->end();?>
        </div>

    </div>

<?php $this->start('scriptBottom');
echo $this->Html->css('uploadFiles/styleUploadFile', ['block' => 'script']);
echo $this->Html->script('uploadFiles/uploadFile', ['block' => 'script']); ?>
<script>
    $('#guardar').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '<?= $this->Url->build($redirectPrefix . 'patients/addWithReport/update', ['fullBase' => true]); ?>',
            dataType: "json",
            data: $("#userForm").serialize(),
            success: function (response) {
                let data = response.data;
                if (data.error) {
                    $('.errors div').html(data.message).css('display', 'block');
                    $('.errors').show();
                    $('.searchAlert').hide();
                } else {
                    window.location.href = data.goTo;
                }
            }
        });
    });

    $("#type").on('change', function (e) {
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

    $(document).ready(function() {



        var $reportID = $("#id").val();
        $("#fileuploader").uploadFile({
            url: '<?php echo $this->Url->build([
                'controller' => 'Files',
                'action' => 'addFile', $report->id]); ?>',
            fileName:"reportFile",
            showCancel: false,
            showAbort: false,
            showFileSize: false,
            showPreview: true,
            previewHeight: "100px",
            headers: { 'X-XSRF-TOKEN' :'<?= $this->request->getAttribute('csrfToken'); ?>'},
            previewWidth: "100px",
            formData: {'report_id': $reportID, "_csrfToken": '<?= $this->request->getAttribute('csrfToken'); ?>'},
            dragDropStr: "<br/><span><b>Arrastra y solta</b></span>",
            uploadStr: 'Subir',
            fileCounterStyle: ') ',
            deleteStr: 'Eliminar',
            showDelete: true,
            returnType: 'json',
            onLoad:function(obj)
            {
                $.ajax({
                    cache: false,
                    url: '<?php echo $this->Url->build(['controller' => 'Files','action' => 'viewFiles', $report->id ]); ?>',
                    dataType: "json",
                    success: function(data)
                    {
                        for(var i=0;i<data.length;i++)
                        {
                            obj.createProgress(
                                data[i]["name"],
                                data[i]["path"],
                                data[i]["size"]);
                        }
                    }
                });
            },
            deleteCallback: function (data, pd) {
                var name= '';
                if (typeof  data === 'string') {
                    data = $.parseJSON(data);
                    if (data.name) {
                        name = data.name;
                    }
                } else if (typeof  data === 'object') {
                    name = data[0];
                } else {
                    alert('No se pudo borrar. Intente nuevamente');
                    return;
                }

                if (name !== "") {
                    $.post('<?php echo $this->Url->build(['controller' => 'Files','action' => 'delete']); ?>', {op: "delete",name: name, "_csrfToken": '<?= $this->request->getAttribute('csrfToken'); ?>'},
                        function (resp, textStatus, jqXHR) {
                            alert(resp);
                        });
                    pd.statusbar.hide(); //You choice.
                }
            },
            onSuccess:function(files,data,xhr,pd) {
                var getNumber = pd.statusbar[0].innerText.split(')')[0];
                if (typeof  data === 'string') {
                    data = $.parseJSON(data);
                    if (data.name) {
                        pd.filename.html(getNumber + ') ' + data.name);
                    }
                }
            },
            onError: function(files,status,errMsg,pd)
            {
                //console.log('a');
                //files: list of files
                //status: error status
                //errMsg: error message
            },
            afterUploadAll:function(obj)
            {
            }
        });

    })
</script>
<?php $this->end(); ?>
