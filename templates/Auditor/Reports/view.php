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
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= __('Resultado')?></th>
                    <th><?= __('Cantidad de días aconsejados') ?></th>
                    <th><?= __('Desde') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= h($report->getNameStatus()) ?></td>
                    <td><?= h($report->recommendedDays) ?></td>
                    <td><?= h($report->startLicense) ?></td>
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
                        <td><?= h($report->cie10) ?></td>
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
                        <td><?= h($report->observations) ?></td>
                    </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
