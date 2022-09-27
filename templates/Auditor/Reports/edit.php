<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Diagnosticar a Paciente</h4>
    </div>
    <div class="results">
        <div class="container mx-auto row">
            <?= $this->Flash->render() ?>
            <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                <div class="message error">Datos de paciente</div>
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
                    <th><?= __('Telefono') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->patient->birthday) ?></td>
                    <td><?= h($report->patient->age) ?></td>
                    <td><?= h($report->patient->address) ?></td>
                    <td><?= h($report->patient->phone) ?></td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Puesto de trabajo') ?></th>
                    <th><?= __('Empresa') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->patient->job) ?></td>
                    <td><?= h($report->patient->company->name) ?></td>
                </tr>
                </tbody>
            </table>
                <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                    <div class="message error">Datos de la licencia cargada</div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th><?= __('Patologia')?></th>
                        <th><?= __('Fecha de inicio') ?></th>
                        <th><?= __('Tipo de licencia') ?></th>
                        <th><?= __('Días solicitados') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= h($report->pathology) ?></td>
                        <td><?= h($report->startPathology) ?></td>
                        <td><?= h($report->getNameLicense()) ?></td>
                        <td><?= h($report->askedDays) ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php if (!empty($report->comments)) : ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th><?= __('Comentario'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-left"><?= h($report->comments) ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
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
                <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                    <div class="message error">Resultado de auditoría</div>
                </div>
                <?= $this->Form->create($report, ['class' => 'col-lg-12 col-md-12 row']) ?>
                    <div class="pt-0 col-lg-12 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('id', ['label' => 'Resultado',
                                'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'hidden']); ?>
                            <?= $this->Form->control('status', ['label' => 'Resultado',
                                'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione',
                                'options' => $getStatuses]); ?>
                        </div>
                    </div>
                    <div class="pt-0 col-lg-6 col-sm-12">
                        <div class="form-group">
                            <?= $this->Form->control('recommendedDays', ['label' => 'Cantidad de días aconsejados',
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
                <?= $this->Form->end() ?>
        </div>
    </div>


<?php $this->start('scriptBottom');
echo $this->Html->css('uploadFiles/styleUploadFile', ['block' => 'script']);
echo $this->Html->script('uploadFiles/uploadFile', ['block' => 'script']); ?>
    <script>

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
