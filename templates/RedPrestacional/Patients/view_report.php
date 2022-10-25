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
                    <th><?= __('Tipo de Servicio')?></th>
                    <th><?= __('Especialidad')?></th>
                    <th><?= __('Fecha de solicitud') ?></th>
                    <th><?= __('Tipo de licencia') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->mode->name) ?></td>
                    <td><?= h($report->getSpeciality()) ?></td>
                    <td><?= h($report->startPathology) ?></td>
                    <td><?= $report->getNameLicense() ?></td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Días solicitados') ?></th>
                    <th><?= __('Medico Particular') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->askedDays) ?></td>
                    <td><?= $report->privateDoctor() ?></td>
                </tr>
                </tbody>
            </table>
			<?php if (!empty($report->comments)) : ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-left"><?= __('Observaciones'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= h($report->comments) ?></td>
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
                <div class="message error">Dictamen</div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Patologia')?></th>
                    <th><?= __('Dictamen')?></th>
                    <th><?= __('Duración') ?></th>
                    <th><?= __('Desde') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->getPathology()) ?></td>
                    <td><?= $report->getNameStatus(); ?></td>
                    <td><?= h($report->recommendedDays) ?></td>
                    <td><?= (is_null($report->startLicense)) ? '-' : $report->startLicense; ?></td>
                </tr>
                </tbody>
            </table>
			<?php if (!empty($report->cie10)) : ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th><?= __('Diagnóstico (Codificado CIE 10)'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left"><?= h($report->cie10) ?></td>
                    </tr>
                    </tbody>
                </table>
			<?php endif; ?>
			<?php if (!empty($report->observations)) : ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th><?= __('Observaciones'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left"><?= h($report->observations) ?></td>
                    </tr>
                    </tbody>
                </table>
			<?php endif; ?>
			<?php if (!empty($report->files_auditor)) : ?>
                <div class="col-12 p-0">
                    <div class="col-12">
                        <p class="title-results">Archivos cargados en auditoría</p>
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
							<?php foreach ($report->files_auditor as $file) :?>
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
                <a href="<?= $this->Url->build(  $this->Identity->get('groupIdentity')['redirect'] .
					'/agente/resultado/' . $report->id . '/auditoria-' . strtolower($report->patient->lastname . '-' . $report->patient->name), ['fullBase' => true]); ?>" target="_blank" class="btn btn-outline-primary col-12">
                    <i class="mr-2 fa fa-download" aria-hidden="true"></i>Descargar resultado</a>
            </div>
        </div>
    </div>
