<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Auditoría</h4>
    </div>
    <div class="results">
        <div class="container mx-auto row">
            <?= $this->Flash->render() ?>

            <?= $this->Form->create($report, ['class' => 'col-lg-12 col-md-12 row', 'id' => 'userForm']) ?>
            <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                <div class="message error">Datos del Agente</div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.name', ['label' => 'Nombre *',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.lastname', ['label' => 'Apellido *',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.document', ['label' => 'DNI *',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.email', ['label' => 'Email *',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.birthday', ['label' => 'Fecha de nacimiento *',
                        'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date', 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.age', ['label' => 'Edad',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.address', ['label' => 'Domicilio',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.phone', ['label' => 'Telefono',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                </div>
            </div>
            <?= $this->element('partForm/addCity', ['city' => !empty($report->patient) && isset($report->patient->city_id) ? $report->patient->city_id : null]); ?>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.job', ['label' => 'Puesto de trabajo',
                        'class' => 'form-control form-control-blue m-0 col-12']); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-4 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('patient.company_id', ['label' => 'Empresa',
                        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true, 'empty' => 'Seleccione',
                        'options' => $companies]); ?>
                </div>
            </div>
            <div class="row col-12">
                <div id="accordion" class=" alert alert-secondary" style="width: 100%!important;">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <a class="btn btn-link" style="width: 100%!important; cursor: pointer;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Historial del Agente
                            </a>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div id="accordion-2">
                                    <?php foreach ($clinicalHistory as $id => $clinical) : ?>
                                        <div class="card">
                                            <div class="card-header p-0 m-0" id="historial-<?= $id; ?>">
                                                <h5 class="mb-0">
                                                    <a class="btn btn-link w-100 p-3" style="cursor: pointer" data-toggle="collapse" data-target="#collapse-<?= $id; ?>" aria-expanded="true" aria-controls="historial-<?= $id; ?>">
                                                        Licencia #<?= $clinical->id; ?>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapse-<?= $id; ?>" class="collapse" aria-labelledby="historial-<?= $id; ?>" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                                                        <div class="message error">Datos de la licencia cargada</div>
                                                    </div>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?= __('Modalidad')?></th>
                                                            <th><?= __('Area medica')?></th>
                                                            <th><?= __('Fecha de inicio') ?></th>
                                                            <th><?= __('Tipo de licencia') ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?= h($clinical->mode->name) ?></td>
                                                            <td><?= h($clinical->area) ?></td>
                                                            <td><?= h($clinical->startPathology) ?></td>
                                                            <td><?= $clinical->getNameLicense() ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?= __('Días solicitados') ?></th>
                                                            <th><?= __('Medico privado') ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?= h($clinical->askedDays) ?></td>
                                                            <td><?= $clinical->privateDoctor() ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
	                                                <?php if (!empty($clinical->comments)) : ?>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-left"><?= __('Comentario'); ?></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td><?= h($clinical->comments) ?></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
	                                                <?php endif; ?>
	                                                <?php if (!empty($clinical->files)) : ?>
                                                        <div class="col-12 p-0">
                                                            <div class="col-12">
                                                                <p class="title-results">Archivos cargados</p>
                                                            </div>
                                                            <div id="table-files-preoccupational-<?= $clinical->id; ?>" class="col-12 tablaFiles">
                                                                <table class="table table-bordered col-12" >
                                                                    <thead>
                                                                    <tr>
                                                                        <th><?= __('Nombre') ?></th>
                                                                        <th><?= __('Documentos') ?></th>
                                                                        <th><?= __('Acciones') ?></th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
					                                                <?php foreach ($clinical->files as $file) :?>
                                                                        <tr id="file-<?= $file->id; ?>">

                                                                            <td><?= h($file->name) ?></td>
                                                                            <td><img src="<?= $file->getUrl(); ?>" height="100px"/></td>
                                                                            <td>
								                                                <?= $this->Html->link(__('Descargar'), DS .  'files' . DS . $clinical->id . DS . $file->name, ['fullBase' => true, 'class' => 'text-center', 'target' => '_blank']); ?>
                                                                            </td>
                                                                        </tr>
					                                                <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
	                                                <?php endif; ?>

	                                                <?php if (!$clinical->isWaitingResults()) : ?>
                                                        <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                                                            <div class="message error">Resultado de auditoría</div>
                                                        </div>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th><?= __('Patologia')?></th>
                                                                <th><?= __('Resultado')?></th>
                                                                <th><?= __('Duración') ?></th>
                                                                <th><?= __('Desde') ?></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td><?= h($clinical->pathology) ?></td>
                                                                <td><?= $clinical->getNameStatus(); ?></td>
                                                                <td><?= h($clinical->recommendedDays) ?></td>
                                                                <td><?= is_null($clinical->startLicense) ? '-' : $clinical->startLicense; ?></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
		                                                <?php if (!empty($clinical->cie10)) : ?>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th><?= __('Diagnóstico (Codificado CIE 10)'); ?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-left"><?= h($clinical->cie10) ?></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
		                                                <?php endif; ?>
		                                                <?php if (!empty($clinical->observations)) : ?>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th><?= __('Observaciones'); ?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-left"><?= h($clinical->observations) ?></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
		                                                <?php endif; ?>
		                                                <?php if (!empty($clinical->files_auditor)) : ?>
                                                            <div class="col-12 p-0">
                                                                <div class="col-12">
                                                                    <p class="title-results">Archivos cargados en auditoría</p>
                                                                </div>
                                                                <div id="table-files-preoccupational-<?= $clinical->id; ?>" class="col-12 tablaFiles">
                                                                    <table class="table table-bordered col-12" >
                                                                        <thead>
                                                                        <tr>
                                                                            <th><?= __('Nombre') ?></th>
                                                                            <th><?= __('Documentos') ?></th>
                                                                            <th><?= __('Acciones') ?></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
						                                                <?php foreach ($clinical->files_auditor as $file) :?>
                                                                            <tr id="file-<?= $file->id; ?>">

                                                                                <td><?= h($file->name) ?></td>
                                                                                <td><img src="<?= $file->getUrl(); ?>" height="100px"/></td>
                                                                                <td>
									                                                <?= $this->Html->link(__('Descargar'), $file->getLink(), ['fullBase' => true, 'class' => 'text-center', 'target' => '_blank']); ?>
                                                                                </td>
                                                                            </tr>
						                                                <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
		                                                <?php endif; ?>
                                                        <div class="pl-0 col-12">
                                                            <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] .
				                                                '/agente/resultado/' . $clinical->id . '/auditoria-' . strtolower($report->patient->lastname . '-' . $report->patient->name), ['fullBase' => true]); ?>" target="_blank" class="btn btn-outline-primary col-12">
                                                                <i class="mr-2 fa fa-download" aria-hidden="true"></i>Descargar resultado</a>
                                                        </div>
	                                                <?php else : ?>
                                                        <div class="alert alert-info col-lg-12 text-center" role="alert">
                                                            <div class="message error"><?= h($clinical->getNameStatus()) ?></div>
                                                        </div>
	                                                <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col-12">
                <div class="alert alert-secondary col-lg-12 text-center mt-5" role="alert">
                    <div class="message error">Datos de la Licencia</div>
                </div>
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
			            <?= $this->Form->control('startPathology', ['label' => 'Fecha de solicitud*',
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
            </div>
            <?php if (!empty($report->files)) : ?>
                <div class="col-12 p-0">
                    <div class="col-12">
                        <p class="title-results">Archivos cargados</p>
                    </div>
                    <div id="table-files-preoccupational-<?= $report->id; ?>" class="col-12 tablaFiles">
                        <table class="table table-bordered col-12" >
                            <thead>
                            <tr>
                                <th><?= __('Nombre') ?></th>
                                <th><?= __('Documentos') ?></th>
                                <th><?= __('Acciones') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($report->files as $file) :?>
                                <tr id="file-<?= $file->id; ?>">

                                    <td><?= h($file->name) ?></td>
                                    <td><img src="<?= $file->getUrl(); ?>" height="100px"/></td>
                                    <td>
                                        <?= $this->Html->link(__('Descargar'), DS .  'files' . DS . $report->id . DS . $file->name, ['fullBase' => true, 'class' => 'text-center', 'target' => '_blank']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <div class="alert alert-secondary col-lg-12 text-center mt-5" role="alert">
                <div class="message error">Dictamen</div>
            </div>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('id', ['label' => 'Resultado',
                        'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'hidden']); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('status', ['label' => 'Resultado',
                        'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione',
                        'options' => $getStatuses]); ?>
                </div>
            </div>
            <div id="inputs-denegada" class="inputs-to-show" style="display: none;">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" id="fraud" name="fraud">
                    <label class="form-check-label" for="fraud">Posible fraude</label>
                </div>
            </div>
            <div id="inputs-granted" class="inputs-to-show col-lg-12 col-md-12 row">
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('recommendedDays', ['label' => 'Duración',
                            'class' => 'form-control form-control-blue m-0 col-12']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-6 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('startLicense', ['label' => 'Desde (fecha)',
                            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('cie10', ['label' => 'Diagnóstico(Codificado CIE 10)',
                            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'textarea']); ?>
                    </div>
                </div>

            </div>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('observations', ['label' => 'Observaciones',
                        'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'textarea']); ?>
                </div>
            </div>
            <div class="col-12">
                <p class="title-results">Archivos e imagenes (Máx. 10 Archivos hasta 10MB cada uno)</p>
            </div>
            <div id="fileuploader" class="col-12">Cargar</div>
            <div class="mx-auto form-group row col-lg-12 col-md-12 mt-3">
                <div class="pl-0 col-12">
                    <button type="submit" id="guardar" class="btn btn-outline-primary col-12" name="guardar">
                        <i class="far fa-save"></i> Firmar
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

            $("#status").on('change', function (){
               let value = $(this).val(),
                   text = $('option:selected', this).text().toLowerCase(),
                   $inputsGranted =$('#inputs-granted'),
                   $observationTextarea = $('#observations');
               $('.inputs-to-show').hide();
               switch (value) {
                   case '4': //GRANTED
                       $inputsGranted.show();
                       $('input, textarea', $inputsGranted).each(function () {
                           $(this).attr('readonly', false);
                           $(this).attr('required', true);
                       });
                       $observationTextarea.attr('required', false);
                   break;
                   case '3': //NRLL
                   case '2': // DENIED
                       $inputsGranted.hide();
                       $('#inputs-' + text).show();
                       $('input, textarea', $inputsGranted).each(function () {
                           $(this).attr('readonly', true);
                           $(this).attr('required', false);
                       });
                       $observationTextarea.attr('required', true);
                   break;
               }
            });
        })
    </script>
<?php $this->end(); ?>
