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
        $search = $this->request->getQueryParams();
        $this->paginate = [
            'contain' => [
                'Patients',
            ],
        ];
        $reports = $this->Reports->find();
        $searchByStatus = false;
        if (!empty($search)) {
            if (!empty($search['document'])) {
                $coincide = preg_match('/@/', $search['document']);
                $patients = $this->Reports->Patients->find();
                if ($coincide > 0) {
                    $patients->where(['email LIKE' => '%' . $search['document'] . '%']);
                } else {
                    $patients->where(['document' => $search['document']]);
                }

                if ($patients->all()->isEmpty()) {
                    $this->Flash->error(__('No se encontro ninguna persona con cuil o email: ') . $search['document']);
                } else {
                    $reports->where(['patient_id IN' => $patients->all()->extract('id')->toArray()]);
                }
            }

            if (!empty($search['license_type'])) {
                $reports->where(['type' => $search['license_type']]);
            }

            if (!empty($search['status'])) {
                $searchByStatus = true;
                $reports->where(['status' => $search['status']]);
            }

            if (!empty($search['start_date'])) {
                $reports->where(['Reports.created >=' => $search['start_date']]);
            }

            if (!empty($search['end_date'])) {
                $reports->where(['Reports.created <=' => $search['end_date']]);
            }

            if (!empty($search['doctor_id'])) {
                $reports->where(['Reports.doctor_id' => $search['doctor_id']]);
            }
        }

        $settings = [
            'order' => ['created' => 'desc'],
            'limit' => 10,
        ];

        $reports = $this->paginate($reports, $settings);
        $getLicenses = $this->Reports->getLicenses();
        $getStatuses = $this->Reports->getAllStatuses();
        $getAuditors = $this->Reports->Users->getDoctors();
        $this->set(compact('reports', 'getLicenses', 'getStatuses', 'search', 'getAuditors'));
    }

    /**
     * withOutDiagnostic method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function withOutDiagnostic()
    {

        $search = $this->request->getQueryParams();
        $this->paginate = [
            'contain' => [
                'Patients',
                'Users',
            ],
        ];
        $reports = $this->Reports->find();
        $searchByStatus = false;
        if (!empty($search)) {
            if (!empty($search['document'])) {
                $coincide = preg_match('/@/', $search['document']);
                $patients = $this->Reports->Patients->find();
                if ($coincide > 0) {
                    $patients->where(['email LIKE' => '%' . $search['document'] . '%']);
                } else {
                    $patients->where(['document' => $search['document']]);
                }

                if ($patients->all()->isEmpty()) {
                    $this->Flash->error(__('No se encontro ninguna persona con cuil o email: ') . $search['document']);
                } else {
                    $reports->where(['patient_id IN' => $patients->all()->extract('id')->toArray()]);
                }
            }

            if (!empty($search['license_type'])) {
                $reports->where(['type' => $search['license_type']]);
            }

            if (!empty($search['start_date'])) {
                $reports->where(['Reports.created >=' => $search['start_date']]);
            }

            if (!empty($search['end_date'])) {
                $reports->where(['Reports.created <=' => $search['end_date']]);
            }
        }

        $reports
            ->where(['status IN' => $this->Reports::ACTIVE])
            ->where(['doctor_id' => $this->Authentication->getIdentity()->id]);

        $settings = [
            'order' => ['created' => 'desc'],
            'limit' => 10,
        ];

        $reports = $this->paginate($reports, $settings);
        $getLicenses = $this->Reports->getLicenses();
        $getStatuses = $this->Reports->getAllStatuses();
        $getAuditors = $this->Reports->Users->getDoctors();
        $this->set(compact('reports', 'getLicenses', 'getStatuses', 'search', 'getAuditors'));
    }

    public function edit($id)
    {
        try {
            $report = $this->Reports->get($id, [
                'contain' => [
                    'Patients' => 'Companies',
                    'Files',
                ],
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
                'contain' => ['Patients' => 'Companies', 'Files', 'FilesAuditor'],
            ]);
            if (empty($report)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if (
                in_array($report->status, $this->Reports->getActiveStatuses())
                && $report->doctor_id == $this->Authentication->getIdentity()->id
            ) {
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

    public function result($id)
    {
        try {
            $user = $this->Authentication->getIdentity();
            $group = $user->groupIdentity;
            $redirectPrefix = !empty($group) ? $group['prefix'] : '';
            $actualPrefix = $this->request->getParam('prefix');
            if ($redirectPrefix != $actualPrefix) {
                throw new UnauthorizedException('No tienes permisos para ver esto.');
            }

            $this->loadComponent('Htmltopdf');
            $report = $this->Reports->get($id, [
                'contain' => ['doctor', 'Patients' => ['Companies', 'Cities']],
            ]);
            if (!in_array($report->status, $this->Reports->getActiveStatuses())) {
                $this->Htmltopdf->createReport($report);
            } else {
                throw new RecordNotFoundException('El reporte no esta listo');
                $this->Flash->error(__('El reporte no esta listo.'));
            }
        } catch (\Exception $e) {
            $this->Flash->error($e->getMessage(), ['escape' => false]);
            if (stripos(get_class($e), 'RecordNotFoundException')) {
                return $this->redirect(['action' => 'index']);
            } elseif (stripos(get_class($e), 'UnauthorizedException')) {
                return $this->redirect(['action' => 'edit', $id]);
            }
        }
    }
}
