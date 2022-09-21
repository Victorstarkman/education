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
                            <td><?= h($report->comments) ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
                <div class="alert alert-secondary col-lg-12 text-center" role="alert">
                    <div class="message error">Resultado de auditoría</div>
                </div>
                <?= $this->Form->create($report, ['class' => 'col-lg-12 col-md-12 row']) ?>
                    <div class="pt-0 col-lg-12 col-sm-12">
                        <div class="form-group">
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
                    <div class="mx-auto form-group row col-lg-12 col-md-12">
                        <div class="pl-0 col-12">
                            <button type="submit" id="guardar" class="btn btn-outline-primary col-12" name="guardar">
                                <i class="far fa-save"></i> Firmar
                            </button>
                        </div>
                    </div>
                <?= $this->Form->end() ?>
        </div>
    </div>
