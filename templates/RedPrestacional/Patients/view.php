<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Historial del Agente</h4>
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
                    <td><?= h($patient->name) ?></td>
                    <td><?= h($patient->lastname) ?></td>
                    <td><?= h($patient->document) ?></td>
                    <td><?= h($patient->email) ?></td>
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
	                <?php $time = \Cake\I18n\FrozenTime::parse($patient->birthday);  ?>
                    <td><?= $time->i18nFormat('dd/MM/yyyy');?></td>
                    <td><?= h($patient->age) ?></td>
                    <td><?= h($patient->address) ?></td>
                    <td><?= $patient->getLocation() ?></td>

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
                    <td><?= h($patient->phone) ?></td>
                    <td><?= h($patient->job) ?></td>
                    <td><?= h($patient->company->name) ?></td>
                </tr>
                </tbody>
            </table>
            <?php
            foreach ($patient->reports as $report) : ?>
                <div class="col-12">
                    <p class="title-results">Licencia #<?= $report->id; ?></p>
                </div>
                <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                    <div class="message error">Datos de la licencia cargada</div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th><?= __('Tipo de Servicio')?></th>
                        <th><?= __('Especialidad')?></th>
                        <th><?= __('Fecha de inicio') ?></th>
                        <th><?= __('Tipo de licencia') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= h($report->mode->name) ?></td>
                        <td><?= h($report->getSpeciality()) ?></td>
                        <td><?= h($report->startPathology->i18nFormat('dd/MM/yyyy')) ?></td>
                        <td><?= $report->getNameLicense() ?></td>
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
                        <td><?= h($report->askedDays) ?></td>
                        <td><?= $report->privateDoctor() ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php if (!empty($report->comments)) : ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-left"><?= __('Comentario'); ?></th>
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

                <?php if (!$report->isWaitingResults()) : ?>
                    <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                        <div class="message error">Resultado de auditoría</div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th><?= __('Patologia')?></th>
                            <th><?= __('Dictamen')?></th>
                            <th><?= __('Duración') ?></th>
                            <th><?= __('Fecha de Solicitud') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= h($report->getPathology()) ?></td>
                            <td><?= $report->getNameStatus(); ?></td>
                            <td><?= h($report->recommendedDays) ?></td>
                            <td><?= is_null($report->startLicense) ? '-' : $report->startLicense->i18nFormat('dd/MM/yyyy'); ?></td>
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
                        <a href="<?= $this->Url->build($this->Identity->get('groupIdentity')['redirect'] .
                            '/agente/resultado/' . $report->id . '/auditoria-' . strtolower($patient->lastname . '-' . $patient->name), ['fullBase' => true]); ?>" target="_blank" class="btn btn-outline-primary col-12">
                            <i class="mr-2 fa fa-download" aria-hidden="true"></i>Descargar Dictamen</a>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info col-lg-12 text-center" role="alert">
                        <div class="message error"><?= h($report->getNameStatus()) ?></div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
    </div>
