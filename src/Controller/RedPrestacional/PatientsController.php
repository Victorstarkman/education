<?php
declare(strict_types=1);

namespace App\Controller\RedPrestacional;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\UnauthorizedException;

/**
 * Patients Controller
 *
 * @property \App\Model\Table\PatientsTable $Patients
 * @method \App\Model\Entity\Patient[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PatientsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [
                'Reports',
                'ReportsWithoutCheck',
            ],
        ];

        $patients = $this->paginate($this->Patients);

        $this->set(compact('patients'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function listWithResults()
    {
        $search = $this->request->getQueryParams();
        $this->paginate = [
            'contain' => [
                'Patients',
            ],
        ];
        $reports = $this->Patients->Reports->find();
        $searchByStatus = false;
        if (!empty($search)) {
            if (!empty($search['document'])) {
                $coincide = preg_match('/@/', $search['document']);
                $patients = $this->Patients->find();
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
        }

        if (!$searchByStatus) {
            $reports->where(['status IN' => $this->Patients->Reports->getStatusesOfDiagnosis()]);
        }

        $settings = [
            'order' => ['created' => 'desc'],
            'limit' => 10,
        ];

        $reports = $this->paginate($reports, $settings);
        $getLicenses = $this->Patients->Reports->getLicenses();
        $getStatuses = $this->Patients->Reports->getStatusForDoctor();
        $this->set(compact('reports', 'getLicenses', 'getStatuses', 'search'));
    }

    /**
     * View method
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $patient = $this->Patients->get($id, [
            'contain' => ['Reports' => ['doctor'], 'Companies', 'Cities' => ['Counties' => 'States']],
        ]);

        $this->set(compact('patient'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $patient = $this->Patients->newEmptyEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $patient = $this->Patients->patchEntity($patient, $this->request->getData());
            if ($this->Patients->save($patient)) {
                $this->Flash->success(__('Se guardo correctamente'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ups, hubo un problema. Intentanuevamente'));
        }

        $companies = $this->Patients->Companies->getCompanies();
        $this->set(compact('patient', 'companies'));
    }

    /**
     * Add method
     *
     * @param  string|null  $action action id.
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */

    public function addWithReport(?string $action = 'list')
    {
        switch ($action) {
            case 'create':
                $data = ['error' => false];
                try {
                    if (!$this->request->is('ajax')) {
                        throw new \Exception('Request is not AJAX.');
                    }
                    if (!$this->request->is(['patch', 'post', 'put'])) {
                        throw new \Exception('Request type is not valid.');
                    }
                    $postData = $this->request->getData();
                    $postData['user_id'] = $this->Authentication->getIdentity()->id;
                    $patientEntity = $this->Patients->find('all')
                        ->where(['OR' => [
                            ['document' => $postData['document']],
                            ['email' => $postData['email']],
                        ]])
                        ->contain(['Reports'])
                        ->first();

                    if (empty($patientEntity)) {
                        $patientEntity = $this->Patients->newEmptyEntity([]);
                    } elseif ($postData['type'] == 'new') {
                        throw new \Exception('Ya existe una persona con ese DNI o Email.');
                    }
                    $postData['reports'][0]['user_id'] = $this->Authentication->getIdentity()->id;

                    $patientEntity = $this->Patients->patchEntity(
                        $patientEntity,
                        $postData,
                        ['associated' => ['Reports']]
                    );

                    if (!$this->Patients->save($patientEntity)) {
                        throw new \Exception('Error al generar el paciente.');
                    }
                    $this->loadComponent('Messenger');
                    $this->Messenger->setToAuditor($patientEntity);
                    $data = [
                        'error' => false,
                        'message' => 'Se genero el paciente exitosamente.',
                    ];
                    $this->Flash->success(__('Se genero el paciente exitosamente'));
                } catch (\Exception $e) {
                    $data = [
                        'error' => true,
                        'message' => $e->getMessage(),
                    ];
                }
                $this->viewBuilder()->setClassName('Json');
                $this->set(compact('data'));
                $this->viewBuilder()->setOption('serialize', ['data']);
                break;
            case 'list':
            default:
                break;
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $patient = $this->Patients->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $patient = $this->Patients->patchEntity($patient, $this->request->getData());
            if ($this->Patients->save($patient)) {
                $this->Flash->success(__('Se guardo correctamente'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ups, hubo un problema. Intentanuevamente'));
        }
        $this->set(compact('patient'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $patient = $this->Patients->get($id);
        if ($this->Patients->delete($patient)) {
            $this->Flash->success(__('The patient has been deleted.'));
        } else {
            $this->Flash->error(__('The patient could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Search method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function search()
    {
        $this->viewBuilder()->setLayout('ajax');
        $document = $this->request->getQuery('dni');
        $type = $this->request->getQuery('type');
        if (empty($document)) {
            $type = 'new';
        }

        if ($type == 'new') {
            $patient = $this->Patients->newEmptyEntity();
        } else {
            $patient = $this->Patients->find('all')->where(['document' => $document])->first();
        }

        $doctors = $this->Patients->Reports->Users->getDoctors();
        $licenses = $this->Patients->Reports->getLicenses();
        $companies = $this->Patients->Companies->getCompanies();
        $this->set(compact('patient', 'doctors', 'licenses', 'type', 'companies'));
    }

    public function result($id)
    {
        $this->loadComponent('Htmltopdf');
        $report = $this->Patients->Reports->get($id, [
            'contain' => ['doctor', 'Patients' => ['Companies', 'Cities']],
        ]);
        if (!in_array($report->status, $this->Patients->Reports->getActiveStatuses())) {
            $this->Htmltopdf->createReport($report);
        } else {
            $this->Flash->error(__('El reporte no esta listo.'));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function viewReport($id)
    {
        try {
            $report = $this->Patients->Reports->get($id, [
                'contain' => ['Patients' => ['Companies', 'Cities' => ['Counties' => 'States']]],
            ]);
            if (empty($report)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if (in_array($report->status, $this->Patients->Reports->getActiveStatuses())) {
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
        $getStatuses = $this->Patients->Reports->getStatusForDoctor();
        $this->set(compact('report', 'getStatuses'));
    }
}
