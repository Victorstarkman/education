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
            <div class="patientForm container mx-auto row">
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('patient.name', ['label' => 'Nombre *',
					        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('patient.cuil', ['label' => 'CUIL *',
					        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true ]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('patient.document', ['label' => 'DNI  ',
					        'class' => 'form-control form-control-blue m-0 col-12', 'required' => false]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('patient.email', ['label' => 'Email  ',
					        'class' => 'form-control form-control-blue m-0 col-12', 'required' => false]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('patient.official_email', ['label' => 'Email Oficial ',
					        'class' => 'form-control form-control-blue m-0 col-12', 'required' => false]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('patient.phone', ['label' => 'Telefono',
					        'class' => 'form-control form-control-blue m-0 col-12']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('patient.job', ['label' => 'Puesto de trabajo',
					        'class' => 'form-control form-control-blue m-0 col-12', 'required' => true]); ?>
                    </div>
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
                                                            <td><?= h($clinical->getSpeciality()) ?></td>
                                                            <td><?= h($clinical->startPathology->i18nFormat('dd/MM/yyyy')) ?></td>
                                                            <td><?= $clinical->getNameLicense() ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?= __('Días solicitados') ?></th>
                                                            <th><?= __('Medico particular') ?></th>
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
                                                                <th><?= __('Dictamen')?></th>
                                                                <th><?= __('Duración') ?></th>
                                                                <th><?= __('Fecha de Creacion') ?></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td><?= h($clinical->getPathology()) ?></td>
                                                                <td><?= $clinical->getNameStatus(); ?></td>
                                                                <td><?= h($clinical->recommendedDays) ?></td>
                                                                <td><?= is_null($clinical->startLicense) ? '-' : $clinical->startLicense->i18nFormat('dd/MM/yyyy'); ?></td>
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
                                                                <i class="mr-2 fa fa-download" aria-hidden="true"></i>Descargar Dictamen</a>
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
                        <?= $this->Form->control('speciality_id', ['label' => 'Especialidad *',
                            'class' => 'form-control form-control-blue m-0 col-12 select2', 'options' => $specialties,
                            'empty' => 'Seleccione', 'required' => true]); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-group">
                        <?= $this->Form->control('type', ['label' => 'Tipo de licencia*',
                            'class' => 'form-control form-control-blue m-0 col-12 select2', 'options' => $licenses,
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
                        <?= $this->Form->control('startPathology', ['label' => 'Fecha de creacion*',
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
            <?php if($report->mode_id==4):?>
                <div class="pt-0 col-lg-12 col-sm-12 my-4 border" >
                    <div class="pt-2">Razones</div>
                    <div class="form-check form-switch form-check-inline my-4 reasons">
                        <input class="form-check-input reason" type="radio" value=1 id="licence" name="licence_reason" checked>
                        <label class="form-check-label " for="licence">Licencia</label>
                        <input class="form-check-input reason ml-4" type="radio" value=2 id="change" name="licence_reason">
                        <label class="form-check-label" for="change">Cambio de funciones/Reasignaci&oacute;n de tareas </label>
                        <input class="form-check-input ml-2 reason" type="radio" value=3 id="profilaxis" name="licence_reason">
                        <label class="form-check-label" for="profilaxi">Razones de Profilaxis</label>
                        <input class="form-check-input ml-2 reason" type="radio" value=4 id="health" name="licence_reason">
                        <label class="form-check-label" for="health">Servicios Provisorios por Razones de Enfermedad/Reubicaci&oacute;n laboral</label>
                    </div>
                </div>
            <?php endif;?>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('id', ['label' => 'id',
                        'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'hidden']); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('status', ['label' => 'Dictamen *',
                        'class' => 'form-control form-control-blue m-0 col-12 select2', 'empty' => 'Seleccione',
                        'options' => $getStatuses, 'required' => true]); ?>
                </div>
            </div>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group interdictions d-none">
                    <?= $this->Form->control('interdiction', ['label' => 'El Agente NO debe',
                        'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'textarea','rows'=>'2']); ?>
            </div>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group reinstatement d-none">
                    <?= $this->Form->control('reinstatement', ['label' => 'Reintegro a Tareas Habituales (desde)',
                        'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date']); ?>
                </div>
            </div> 

            <div id="inputs-3" class="inputs-to-show" style="display: none;">
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
                        <?= $this->Form->control('startLicense', ['label' => 'Fecha (desde)',
                            'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'date']); ?>
                    </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12">
                        <div class="form-group">
                            <div class="form-group">
                                <?= $this->Form->control('cie10_id', ['label' => 'Diagnóstico(Codificado CIE 10)',
                                    'class' => 'form-control form-control-blue m-0 col-12 select2', 'empty' => 'Seleccione',
                                    'options' => $cie10]); ?>
                            </div>
                        </div>
                        <div class="form-check form-switch ">
                            <input class="form-check-input" type="checkbox" value="1" id="otherDiag" name="otherDiag">
                            <label class="form-check-label" for="otherDiag">Otro Diagnóstico</label>
                            
                        </div>
                </div>
                <div class="pt-0 col-lg-12 col-sm-12 otroDiagnostico" style="display: none">
                    <div class="form-group">
                        <?=  $this->Form->control('pathology', ['label' => 'Diagnóstico',
                            'class' => 'form-control form-control-blue m-0 col-12']); ?>
                    </div>
                </div>
            </div>
            <div class="pt-0 col-lg-12 col-sm-12">
                <div class="form-group">
                    <?= $this->Form->control('observations', ['label' => 'Observaciones',
                        'class' => 'form-control form-control-blue m-0 col-12', 'type' => 'textarea']); ?>
                </div>
            </div>
            <?php if($report->mode_id==4):?>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-check form-switch ">
                        <input class="form-check-input " type="checkbox" value="1" id="retirement" name="retirement">
                        <label class="form-check-label" for="retirement">Jubilaci&oacute;n por Incapacidad</label>
                    </div>
                </div>
            <?php else:?>
                <div class="pt-0 col-lg-12 col-sm-12">
                    <div class="form-check form-switch ">
                        <input class="form-check-input " type="checkbox" value="1" id="job_registration" name="job_registration">
                        <label class="form-check-label ml-2" for="job_registration">Con Alta Laboral</label>
                        <input class="form-check-input ml-2" type="checkbox" value="1" id="new_exam" name="new_exam">
                        <label class="form-check-label ml-4" for="new_exam">Con Nuevo Ex&aacute;men</label>
                        <input class="form-check-input ml-2" type="checkbox" value="1" id="eval_council" name="eval_council">
                        <label class="form-check-label ml-4" for="eval_council">Se aconseja evaluaci&oacute;n por Junta M&eacute;dica</label>   
                    </div>
                </div>
            <?php endif;?>
            <div class="pt-0 col-lg-12 col-sm-12 mt-3 d-none date_job">
                <div class="pt-0 col-lg-4 col-sm-12">
                    <div class="form-group">
                        <label for="date_job_registration">Fecha de Alta Laboral</label>
                        <input type="date" name="date_job_registration" id="date_job_registration" class='form-control form-control-blue'>
                    </div>
                </div>
            </div>
            <div class="pt-0 col-lg-12 col-sm-12 mt-3 d-none date_new">
                <div class="pt-0 col-lg-4 col-sm-12 " >
                    <div class="form-group">
                        <label for="date_new_exam">Fecha de Nuevo Ex&aacute;men</label>
                        <input type="date" name="date_new_exam" id="date_new_exam" class='form-control form-control-blue'>
                    </div>
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

        function createNew(id) {
            let addUrl,
                $modal = $("#modal-target-form"),
                posToSearch = '',
                modalTitle = $('#' + id).data('modal-title');
            switch (id) {
                case 'privatedoctor-id':
                    addUrl = '<?php echo $this->Url->build([
                        'controller' => 'Reports',
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
        $(document).ready(function() {
            if(!($('.interdictions').hasClass('d-none'))){
            $('.interdictions').addClass('d-none');
            }
            if(!($('.reinstatement').hasClass('d-none'))){
            $('.reinstatement').addClass('d-none');
        }
            var $reportID = $("#id").val();
            $("#fileuploader").uploadFile({
                url: '<?php echo $this->Url->build(['controller' => 'Files','action' => 'addFile', $report->id]); ?>',
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

            $('.editMedico a').on('click', function (){
                let value = $(this).data('id'),
                    $privateDoctorInput = $('#privatedoctor-id'),
                    $modal = $("#modal-target-form");

                $.ajax({
                    type: "GET",
                    url: '<?php echo $this->Url->build(['controller' => 'Reports','action' => 'addDoctor']); ?>/' + value,
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
                                url: '<?php echo $this->Url->build(['controller' => 'Reports','action' => 'addDoctor']); ?>/' + value,
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

            $("#status").on('change', function (){
               let value = $(this).val(),
                   text = $('option:selected', this).text().toLowerCase(),
                   $inputsGranted =$('#inputs-granted'),
                   $observationTextarea = $('#observations');
                   $otherDiagCheckbox = $('#otherDiag');
               $('.inputs-to-show').hide();
               switch (value) {
                   case '4': //GRANTED
                       $inputsGranted.show();
                       $('input, textarea', $inputsGranted).each(function () {
                           $(this).attr('readonly', false);
                           $(this).attr('required', true);
                       });
                       $observationTextarea.attr('required', false);
                       $otherDiagCheckbox.attr('required', false);
                       $('#pathology').attr('required', false);
                   break;
                   case '2': // NRLL
                   case '3': // DENIED
                       $inputsGranted.hide();
                       if (value != 2) {
                           $('#inputs-' + value).show();
                       }
                       $('input, textarea', $inputsGranted).each(function () {
                           $(this).attr('readonly', true);
                           $(this).attr('required', false);
                       });
                       $observationTextarea.attr('required', true);
                   break;
               }
            });

            $("#askeddays").on('change', function (e) {
                if ($(this).val() > 2) {
                    $('#privatedoctor-id').attr('required', true);
                    $('label[for="privatedoctor-id"] span').show();
                } else {
                    $('#privatedoctor-id').attr('required', false);
                    $('label[for="privatedoctor-id"] span').hide();
                }
            });

            $("#otherDiag").on('change', function (e) {
                if($(this).is(':checked')){
                    $('.otroDiagnostico').slideDown();
                    $('#pathology').attr('required', true);
                    $('#cie10-id').prop('disabled', true);
                    $('#cie10-id').val('').change();
                } else {
                    $('.otroDiagnostico').slideUp();
                    $('#pathology').attr('required', false);
                    $('#cie10-id').prop('disabled', false);
                }
            });

            $("#privatedoctor-id").on('change', function (e) {
                let value = $(this).val();
                if (value) {
                    $('.editMedico').show();
                    $('.editMedico a').attr('data-id', value);
                } else {
                    $('.editMedico').hide();
                }
            });
        })
        /* 
        cuando dictamen es otorgado en juntas y estoy en cambio de funciones o en servicios provisorios habiltar interdicciones
        */
       $('#status').change(function(){
        if(!($('.interdictions').hasClass('d-none'))){
            $('.interdictions').addClass('d-none');
        }
        if(!($('.reinstatement').hasClass('d-none'))){
            $('.reinstatement').addClass('d-none');
        }
        if(($('#status').val()==4) && $('#mode-id').val()==4){ 
            if($('#change').prop('checked') || $('#health').prop('checked') ){
                     if($('.interdictions').hasClass('d-none')){
                        $('.interdictions').removeClass('d-none');
                     }
                }
            }
        if((($('#status').val()==3) && $('#mode-id').val()==4)){
            if($('#change').prop('checked')){
                if($('.reinstatement').hasClass('d-none')){
                        $('.reinstatement').removeClass('d-none');
                     }
            }
        }

       })
       $('#job_registration').click(function(){
         $(this).prop('checked')?$('.date_job').removeClass('d-none'):$('.date_job').addClass('d-none');
       })
       $('#new_exam').click(function(){
         $(this).prop('checked')?$('.date_new').removeClass('d-none'):$('.date_new').addClass('d-none');
       })
    </script>
<?php $this->end(); ?>
