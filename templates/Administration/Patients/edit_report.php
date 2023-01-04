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
                    <th><?= __('DNI') ?></th>
                    <th><?= __('CUIL') ?></th>
                    <th><?= __('email oficial') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->patient->name) ?></td>
                    <td><?= h($report->patient->document) ?></td>
                    <td><?= h($report->patient->cuil ) ?></td>
                    <td><?= h($report->patient->email) ?></td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Email') ?></th>
                    <th><?= __('Telefono') ?></th>
                    <th><?= __('Puesto de trabajo') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->patient->email) ?></td>
                    <td><?= h($report->patient->phone) ?></td>
                    <td><?= h($report->patient->job) ?></td>
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
                        <?= $this->Form->control('id', ['label' => 'Diagnóstico*',
                            'class' => 'form-control form-control-blue m-0 col-12', 'required' => true, 'type' => 'hidden']); ?>
                        <?= $this->Form->control('mode_id', ['label' => 'Tipo de Servicio *',
                            'class' => 'form-control form-control-blue m-0 col-12 select2', 'required' => true, 'empty' => 'Seleccione']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('speciality_id', ['label' => 'Especialidad *',
                            'class' => 'form-control form-control-blue m-0 col-12 select2', 'options' => $specialties,
                            'empty' => 'Seleccione', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('type', ['label' => 'Tipo de licencia*',
                            'class' => 'form-control form-control-blue m-0 col-12', 'options' => $licenses,
                            'empty' => 'Seleccione', 'required' => true]); ?>
                    </div>
                </div>
                <div class="familiar row col-12 mx-auto" <?php if ($report['type'] !== 3) {
                    echo 'style="display: none;"';
                                                         } ?>>
                    <div class="pt-0 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('relativeName', ['label' => 'Nombre del familiar *',
                                'class' => 'form-control form-control-blue m-0 col-12', 'required' => $report['type'] !== 3 ? false : true]); ?>
                        </div>
                    </div>
                    <div class="pt-0 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('relativeLastname', ['label' => 'Apellido del familiar*',
                                'class' => 'form-control form-control-blue m-0 col-12', 'required' => $report['type'] !== 3 ? false : true]); ?>
                        </div>
                    </div>
                    <div class="pt-0 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('relativeRelationship', ['label' => 'Relación *',
                                'class' => 'form-control form-control-blue m-0 col-12', 'required' => $report['type'] !== 3 ? false : true]); ?>
                        </div>
                    </div>
                </div>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('startPathology', ['label' => 'Fecha de Creación*',
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
                        <?php
                        if ($report['askedDays'] > 2) {
                            $label = 'Medico Particular <span>*</span>';
                            $requiredDoctor = true;
                        } else {
                            $requiredDoctor = false;
                            $label = 'Medico Particular <span style="display: none;">*</span>';
                        }

                        ?>
                        <?= $this->Form->control('privatedoctor_id', ['label' => $label, 'escape' => false,
                            'class' => 'form-control form-control-blue m-0 col-12 select2', 'options' => $privateDoctors, 'data-create-new' => true, 'data-modal-title' => 'Nuevo medico', 'required' => $requiredDoctor, 'empty' => 'Seleccione', 'value' => $report['privatedoctor_id']]); ?>
                        <div class="text-right editMedico" <?php if ($report['privatedoctor_id'] <= 0) {
                            echo 'style="display: none;"';
                                                           } ?>>
                            <a href="javascript:void(0)" data-id="<?= $report['privatedoctor_id']; ?>">Editar información del Medico</a>
                        </div>
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
                        <?= $this->Form->control('medicalCenter', ['label' => 'Distrito (En el caso que se requiera cambio)',
                             'class' => 'form-control form-control-blue m-0 col-12', 'options' => $getMedicalCenter,
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

    <!-- Modal -->
    <div class="modal fade " id="modal-target-form" tabindex="-1"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Modal title</h1>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div class="toast" style="position: fixed; bottom: 15px; right: 15px;z-index:99999;" data-delay="5500">
        <div class="toast-header">
            <strong class="mr-auto">Notificación</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
        </div>
    </div>


    <?php $this->start('scriptBottom');
echo $this->Html->css('uploadFiles/styleUploadFile', ['block' => 'script']);
echo $this->Html->script('uploadFiles/uploadFile', ['block' => 'script']); ?>
<script>
    selectsToactive =  $('.select2');
    selectsToactive.select2();
    $(selectsToactive).each(function () {
        let createNew = $(this).data('create-new')
        if (typeof createNew !== 'undefined' && createNew !== false) {
            let createNewTextData = $(this).data('create-new-text'),
                createNewText = (typeof createNewTextData !== 'undefined' && createNewTextData !== false) ? createNewTextData : 'Crear nuevo',
                id = $(this).attr('id'),
                button = '<a href="#" style="padding: 6px;height: 20px;display: inline-table; width: 100%"  onclick="createNew(\'' + id +'\');"> <i class="si si-plus"></i>' + createNewText +'</a>';
            $(this)
                .select2()
                .on('select2:open', () => {
                    $(".select2-results:not(:has(a))").prepend(button);
                })
        }
    });

    function createNew(id) {
        let addUrl,
            $modal = $("#modal-target-form"),
            posToSearch = '',
            modalTitle = $('#' + id).data('modal-title');
        switch (id) {
            case 'privatedoctor-id':
                addUrl = '<?php echo $this->Url->build([
				    'controller' => 'Patients',
				    'action' => 'addDoctor']); ?>';
                posToSearch = 'privatedoctor';
                break;
        }

        $.ajax({
            type: "GET",
            url: addUrl,
            contentType: "application/json",
            accepts: "application/json",
            success: function (response) {
                $modal.modal("show");
                $('#' + id).select2("close");
                $(".modal-body", $modal).html(response);
                $(".modal-header .modal-title", $modal).html(modalTitle);
                $('#modal-target-form .modal-body form').on('submit', function (e) {
                    $('button.submit', this).attr('disable', true);
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: addUrl,
                        dataType: "json",
                        data: $(this).serialize(),
                        success: function (response) {
                            console.log('Test', response);
                            $('button.submit', this).attr('disable', false);
                            if (!response.data.error) {
                                var newOption = new Option(response.data[posToSearch].name, response.data[posToSearch].id, true, true);
                                $('#' + id).append(newOption).trigger('change');
                                $(".modal-body", $modal).html('');
                                $modal.modal('hide');
                            }
                            $('.toast .toast-body').html(response.data.message);
                            $('.toast').toast('show');

                        }
                    });
                })
            }
        });
    }

    $("#privatedoctor-id").on('change', function (e) {
        let value = $(this).val();
        if (value) {
            $('.editMedico').show();
            $('.editMedico a').attr('data-id', value);
        } else {
            $('.editMedico').hide();
        }
    });

    $('.editMedico a').on('click', function (){
        let value = $(this).data('id'),
            $privateDoctorInput = $('#privatedoctor-id'),
            $modal = $("#modal-target-form");

        $.ajax({
            type: "GET",
            url: '<?php echo $this->Url->build(['controller' => 'Patients','action' => 'addDoctor']); ?>/' + value,
            contentType: "application/json",
            accepts: "application/json",
            success: function (response) {
                $modal.modal("show");
                $(".modal-body", $modal).html(response);
                $(".modal-header .modal-title", $modal).html('Editar Doctor');
                $('#modal-target-form .modal-body form').on('submit', function (e) {
                    $('button.submit', this).attr('disable', true);
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: '<?php echo $this->Url->build(['controller' => 'Patients','action' => 'addDoctor']); ?>/' + value,
                        dataType: "json",
                        data: $(this).serialize(),
                        success: function (response) {
                            console.log('Test', response.data.privatedoctor.name);
                            $('button.submit', this).attr('disable', false);
                            if (!response.data.error) {
                                $privateDoctorInput.select2('destroy');
                                $privateDoctorInput.find("option:selected").text(response.data.privatedoctor.name);
                                $privateDoctorInput.select2();
                                $(".modal-body", $modal).html('');
                                $modal.modal('hide');
                            }
                            $('.toast .toast-body').html(response.data.message);
                            $('.toast').toast('show');

                        }
                    });
                })
            }
        });
    });

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
            url: '<?php echo $this->Url->build(["controller" => "Files","action" => "addFile", $report->id]); ?>',
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
