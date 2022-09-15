<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * MedicalCenters Controller
 *
 * @property \App\Model\Table\MedicalCentersTable $MedicalCenters
 * @method \App\Model\Entity\MedicalCenter[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MedicalCentersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $medicalCenters = $this->paginate($this->MedicalCenters);

        $this->set(compact('medicalCenters'));
    }

    /**
     * View method
     *
     * @param string|null $id Medical Center id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $medicalCenter = $this->MedicalCenters->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('medicalCenter'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $medicalCenter = $this->MedicalCenters->newEmptyEntity();
        if ($this->request->is('post')) {
            $medicalCenter = $this->MedicalCenters->patchEntity($medicalCenter, $this->request->getData());
            if ($this->MedicalCenters->save($medicalCenter)) {
                $this->Flash->success(__('The medical center has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The medical center could not be saved. Please, try again.'));
        }
        $this->set(compact('medicalCenter'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Medical Center id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $medicalCenter = $this->MedicalCenters->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $medicalCenter = $this->MedicalCenters->patchEntity($medicalCenter, $this->request->getData());
            if ($this->MedicalCenters->save($medicalCenter)) {
                $this->Flash->success(__('The medical center has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The medical center could not be saved. Please, try again.'));
        }
        $this->set(compact('medicalCenter'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Medical Center id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $medicalCenter = $this->MedicalCenters->get($id);
        if ($this->MedicalCenters->delete($medicalCenter)) {
            $this->Flash->success(__('The medical center has been deleted.'));
        } else {
            $this->Flash->error(__('The medical center could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
