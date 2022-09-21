<?php
declare(strict_types=1);

namespace App\Controller\RedPrestacional;

use App\Controller\AppController;

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
     * View method
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $patient = $this->Patients->get($id, [
            'contain' => ['Reports' => ['doctor'], 'Companies'],
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
            'contain' => ['doctor', 'Patients' => ['Companies']],
        ]);
        if (!in_array($report->status, $this->Patients->Reports->getActiveStatuses())) {
            $this->Htmltopdf->createReport($report);
        } else {
            $this->Flash->error(__('El reporte no esta listo.'));

            return $this->redirect(['action' => 'index']);
        }
    }
}
