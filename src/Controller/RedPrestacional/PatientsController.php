<?php
declare(strict_types=1);

namespace App\Controller\RedPrestacional;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Routing\Router;

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
            $patientsWhere = [];
            $errorPatient = '';
            if (!empty($search['document'])) {
                $coincide = preg_match('/@/', $search['document']);

                if ($coincide > 0) {
                    $errorPatient = 'No se encontro persona con el email: ' . $search['document'];
                    $patientsWhere['email LIKE'] = '%' . $search['document'] . '%';
                } else {
                    $errorPatient = 'No se encontro persona con el documento: ' . $search['document'];
                    $patientsWhere['document'] = $search['document'];
                }
            }
            if (!empty($search['company_id'])) {
                if (empty($errorPatient)) {
                    $errorPatient = 'No se encontraron personas en la empresa indicada.';
                } else {
                    $errorPatient .= ' en la empresa indicada';
                }
                $patientsWhere['company_id'] = $search['company_id'];
            }

            if (!empty($patientsWhere)) {
                $patients = $this->Patients->find()->where($patientsWhere);
                if ($patients->all()->isEmpty()) {
                    $this->Flash->error($errorPatient);
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
        $getAuditors = $this->Patients->Reports->Users->getDoctors();
        $companies = $this->Patients->Companies->find()->all()->combine('id', 'name');
        $this->set(compact('reports', 'getLicenses', 'getStatuses', 'search', 'getAuditors', 'companies'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function listWithoutResults()
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
            $patientsWhere = [];
            $errorPatient = '';
            if (!empty($search['document'])) {
                $coincide = preg_match('/@/', $search['document']);

                if ($coincide > 0) {
                    $errorPatient = 'No se encontro persona con el email: ' . $search['document'];
                    $patientsWhere['email LIKE'] = '%' . $search['document'] . '%';
                } else {
                    $errorPatient = 'No se encontro persona con el documento: ' . $search['document'];
                    $patientsWhere['document'] = $search['document'];
                }
            }
            if (!empty($search['company_id'])) {
                if (empty($errorPatient)) {
                    $errorPatient = 'No se encontraron personas en la empresa indicada.';
                } else {
                    $errorPatient .= ' en la empresa indicada';
                }
                $patientsWhere['company_id'] = $search['company_id'];
            }

            if (!empty($patientsWhere)) {
                $patients = $this->Patients->find()->where($patientsWhere);
                if ($patients->all()->isEmpty()) {
                    $this->Flash->error($errorPatient);
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

            if (!empty($search['doctor_id'])) {
                $reports->where(['Reports.doctor_id' => $search['doctor_id']]);
            }
        }

        $reports->where(['status NOT IN' => $this->Patients->Reports->getStatusesOfDiagnosis()]);

        $settings = [
            'order' => ['created' => 'desc'],
            'limit' => 10,
        ];

        $reports = $this->paginate($reports, $settings);
        $getLicenses = $this->Patients->Reports->getLicenses();
        $getAuditors = $this->Patients->Reports->Users->getDoctors();
        $companies = $this->Patients->Companies->find()->all()->combine('id', 'name');
        $this->set(compact('reports', 'getLicenses', 'search', 'getAuditors', 'companies'));
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
            'contain' => [
                'Reports' => ['doctor', 'Files', 'FilesAuditor', 'Modes', 'Privatedoctors'],
                'Companies',
                'Cities' => ['Counties' => 'States']],
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
            try {
                $postData = $this->request->getData();
                $patientEntity = $this->Patients->find('all')
                    ->where(['OR' => [
                        ['document' => $postData['document']],
                        ['email' => $postData['email']],
                    ]])
                    ->contain(['Reports'])
                    ->first();
                if (!empty($patientEntity)) {
                    throw new \Exception('Ya existe una persona con ese DNI o Email.');
                }
                $patient = $this->Patients->patchEntity($patient, $postData);
                if (!$this->Patients->save($patient)) {
                    throw new \Exception('Error al generar el agente.');
                }

                $this->Flash->success(__('Se genero el agente exitosamente'));

                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                $this->Flash->error($e->getMessage());
            }
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
            case 'update':
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
                    $reportEntity = $this->Patients->Reports->get($postData['id']);

                    if (empty($reportEntity)) {
                        throw new \Exception('No se encontro el registro.');
                    }

                    $reportEntity = $this->Patients->Reports->patchEntity(
                        $reportEntity,
                        $postData
                    );

                    if (!$this->Patients->Reports->save($reportEntity)) {
                        throw new \Exception('Error al generar el agente.');
                    }

                    $user = $this->Authentication->getIdentity();
                    $group = $user->groupIdentity;
                    $redirectPrefix = !empty($group) ? $group['redirect'] : '';
                    $url = Router::url('/', true);
                    $data = [
                        'error' => false,
                        'message' => 'Se genero el agente exitosamente.',
                        'goTo' => $url . $redirectPrefix,
                    ];
                    $this->Flash->success(__('Se actualizo el registro exitosamente'));
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

                    if (
                        empty($postData['personalDoctorName'])
                        || empty($postData['personalDoctorLastname'])
                        || empty($postData['personalDoctorMP'])
                        || empty($postData['personalDoctorMN'])
                    ) {
                        throw new \Exception('Falta informacion del Medico Particular.');
                    }

                    $privateDoctors = $this->Patients->Reports->Privatedoctors->find('all')
                        ->where(['OR' => [
                            ['license' => $postData['personalDoctorMP']],
                            ['licenseNational' => $postData['personalDoctorMN']],
                        ]])
                        ->first();
                    if (!empty($privateDoctors)) {
                        $privateDoctorsEntity = $this->Patients->Reports->Privatedoctors->get($privateDoctors->id);
                    } else {
                        $privateDoctorsEntity = $this->Patients->Reports->Privatedoctors->newEmptyEntity([]);
                    }

                    $doctorData = [
                        'name' => $postData['personalDoctorName'],
                        'lastname' => $postData['personalDoctorLastname'],
                        'license' => $postData['personalDoctorMP'],
                        'licenseNational' => $postData['personalDoctorMN'],
                    ];
                    $privateDoctorsEntity = $this->Patients->Reports->Privatedoctors->patchEntity(
                        $privateDoctorsEntity,
                        $doctorData,
                    );

                    if ($this->Patients->Reports->Privatedoctors->save($privateDoctorsEntity)) {
                        $postData['reports'][0]['privatedoctor_id'] = $privateDoctorsEntity->id;
                    }

                    $postData['reports'][0]['user_id'] = $this->Authentication->getIdentity()->id;

                    $patientEntity = $this->Patients->patchEntity(
                        $patientEntity,
                        $postData,
                        ['associated' => ['Reports']]
                    );
                    if (!$this->Patients->save($patientEntity)) {
                        throw new \Exception('Error al generar el agente.');
                    }
                    $this->loadComponent('Messenger');
                    $this->Messenger->setToAuditor($patientEntity);
                    $user = $this->Authentication->getIdentity();
                    $group = $user->groupIdentity;
                    $redirectPrefix = !empty($group) ? $group['redirect'] : '';
                    $url = Router::url('/', true);
                    $data = [
                        'error' => false,
                        'message' => 'Se genero el agente exitosamente.',
                        'goTo' => $postData['go_to'] == 2
                            ?  $url . $redirectPrefix . 'licencias/editar/' .  $patientEntity->reports[0]->id
                            : $url . $redirectPrefix,
                    ];
                    $this->Flash->success(__('Se genero el agente exitosamente'));
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
        $modes = $this->Patients->Reports->Modes->find()->all()->combine('id', 'name');
        $companies = $this->Patients->Companies->getCompanies();
        $this->set(compact('patient', 'doctors', 'licenses', 'type', 'companies', 'modes'));
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
            $report = $this->Patients->Reports->get($id, [
                'contain' => ['doctor', 'Patients' => ['Companies', 'Cities']],
            ]);
            if (!in_array($report->status, $this->Patients->Reports->getActiveStatuses())) {
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

    public function viewReport($id)
    {
        try {
            $report = $this->Patients->Reports->get($id, [
                'contain' => [
                    'Files',
                    'FilesAuditor',
                    'Patients' => ['Companies', 'Cities' => ['Counties' => 'States']],
                    'Modes',
                    'Privatedoctors',
                ],
            ]);
            if (empty($report)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if (in_array($report->status, $this->Patients->Reports->getActiveStatuses())) {
                throw new UnauthorizedException('El agente no se encuentra diagnosticado');
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

    public function editReport($id)
    {
        try {
            $report = $this->Patients->Reports->get($id, [
                'contain' => [
                    'Privatedoctors',
                    'Patients' => [
                        'Companies',
                        'Cities' => [
                            'Counties' => 'States',
                        ],
                    ]],
            ]);
            if (empty($report)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if (!in_array($report->status, $this->Patients->Reports->getActiveStatuses())) {
                throw new UnauthorizedException('El agente se encuentra diagnosticado');
            }
        } catch (\Exception $e) {
            $this->Flash->error($e->getMessage(), ['escape' => false]);
            if (stripos(get_class($e), 'RecordNotFoundException')) {
                return $this->redirect(['action' => 'index']);
            } elseif (stripos(get_class($e), 'UnauthorizedException')) {
                return $this->redirect(['action' => 'viewReport', $id]);
            }
        }

        $doctors = $this->Patients->Reports->Users->getDoctors();
        $privateDoctors = $this->Patients->Reports->Privatedoctors->find()->all()->combine('id', function ($entity) {

            return $entity->name . ' ' .  $entity->lastname . ' (M.P: ' . $entity->license . ' - M.N:' . $entity->licenseNational . ')';
        });
        $licenses = $this->Patients->Reports->getLicenses();
        $companies = $this->Patients->Companies->getCompanies();
        $modes = $this->Patients->Reports->Modes->find()->all()->combine('id', 'name');

        $this->set(compact('report', 'doctors', 'licenses', 'companies', 'modes', 'privateDoctors'));
    }

    public function deleteReport($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $report = $this->Patients->Reports->get($id);
        if (in_array($report->status, $this->Patients->Reports->getActiveStatuses())) {
            if ($this->Patients->Reports->delete($report)) {
                $this->Flash->success(__('El reporte se elimino.'));
            } else {
                $this->Flash->error(__('El reporte no pudo ser eliminado, intente nuevamente.'));
            }
        } else {
            $this->Flash->error(__('No se puede eliminar ausentes con diagnostico.'));
        }

        return $this->redirect(['action' => 'listWithoutResults']);
    }
}
