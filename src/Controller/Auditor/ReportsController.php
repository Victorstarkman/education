<?php
declare(strict_types=1);

namespace App\Controller\Auditor;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\UnauthorizedException;

/**
 * Reports Controller
 *
 * @property \App\Model\Table\ReportsTable $Reports
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Patients', 'Users'],
        ];
        $reports = $this->paginate(
            $this->Reports->find()->where([
                'doctor_id' => $this->Authentication->getIdentity()->id,
            ])
        );

        $this->set(compact('reports'));
    }

    /**
     * withOutDiagnostic method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function withOutDiagnostic()
    {
        $this->paginate = [
            'contain' => ['Patients', 'Users'],
        ];

        $reports = $this->paginate(
            $this->Reports->find()->where([
                'Reports.status' => $this->Reports::ACTIVE,
                'doctor_id' => $this->Authentication->getIdentity()->id,
            ])
        );
        $this->set(compact('reports'));
    }

    public function edit($id)
    {
        try {
            $report = $this->Reports->get($id, [
	            'contain' => ['Patients' => 'Companies'],
            ]);
            if (empty($report)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if ($report->doctor_id != $this->Authentication->getIdentity()->id) {
                throw new RecordNotFoundException('El doctor Auditor no corresponde.');
            }

            if (!in_array($report->status, $this->Reports->getActiveStatuses())) {
                throw new UnauthorizedException('El paciente ya se encuentra diagnosticado');
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $errors = '';
                if (empty($data['status']) || in_array($data['status'], $this->Reports->getActiveStatuses())) {
                    $errors = 'El estado es incorrecto.';
                }

                if (empty($data['recommendedDays']) || $data['recommendedDays'] <= 0) {
                    $errors .= '</br>Los días recomendados deben ser mayor a 0.';
                }

                if (
                    empty($data['startLicense'])
                    || strtotime($data['startLicense']) < strtotime('-1 days')
                ) {
                    $errors .= '</br>La fecha de inicio no puede ser menor a ayer.';
                }

                if (empty($data['cie10'])) {
                    $errors .= '</br>El diagnóstico CIE 10 no puede estar vacio.';
                }

                if (!empty($errors)) {
                    throw new \Exception($errors);
                }

                $dataToSave = [
                    'status' => $data['status'],
                    'recommendedDays' => $data['recommendedDays'],
                    'startLicense' => $data['startLicense'],
                    'cie10' => $data['cie10'],
                    'observations' => $data['observations'],
                ];
                $report = $this->Reports->patchEntity($report, $dataToSave);
                if ($this->Reports->save($report)) {
                    $this->Flash->success(__('El diagnostico fue guardado correctamente.'));

                    return $this->redirect(['action' => 'withOutDiagnostic']);
                } else {
                    throw new \Exception('Ups, hubo un problema. Intentanuevamente');
                }
            }
        } catch (\Exception $e) {
            $this->Flash->error($e->getMessage(), ['escape' => false]);
            if (stripos(get_class($e), 'RecordNotFoundException')) {
                return $this->redirect(['action' => 'withOutDiagnostic']);
            } elseif (stripos(get_class($e), 'UnauthorizedException')) {
                return $this->redirect(['action' => 'view', $id]);
            }
        }
        $getStatuses = $this->Reports->getStatusForDoctor();
        $this->set(compact('report', 'getStatuses'));
    }

    public function view($id)
    {
        try {
            $report = $this->Reports->get($id, [
                'contain' => ['Patients' => 'Companies'],
            ]);
            if (empty($report)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if ($report->doctor_id != $this->Authentication->getIdentity()->id) {
                throw new RecordNotFoundException('El doctor Auditor no corresponde.');
            }

            if (in_array($report->status, $this->Reports->getActiveStatuses())) {
                throw new UnauthorizedException('El paciente no se encuentra diagnosticado');
            }
        } catch (\Exception $e) {
            $this->Flash->error($e->getMessage(), ['escape' => false]);
            if (stripos(get_class($e), 'RecordNotFoundException')) {
                return $this->redirect(['action' => 'index']);
            } elseif (stripos(get_class($e), 'UnauthorizedException')) {
                return $this->redirect(['action' => 'edit', $id]);
            }
        }
        $getStatuses = $this->Reports->getStatusForDoctor();
        $this->set(compact('report', 'getStatuses'));
    }
}
