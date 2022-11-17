<?php
declare(strict_types=1);

namespace App\Controller\RedPrestacional;

use App\Controller\AppController;

/**
 * Companies Controller
 *
 * @property \App\Model\Table\CompaniesTable $Companies
 * @method \App\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompaniesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $companies = $this->paginate($this->Companies);

        $this->set(compact('companies'));
    }

    /**
     * View method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('company'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $company = $this->Companies->newEmptyEntity();
        if ($this->request->is('post')) {
            $company = $this->Companies->patchEntity($company, $this->request->getData());
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('La empresa fue creada exitosamente'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ups, hubo un problema al crear la empresa.'));
        }
        $statuses = $this->Companies->getStatuses();
        $cie10Types = $this->Companies->cie10Types();
        $this->set(compact('company', 'statuses', 'cie10Types'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $company = $this->Companies->patchEntity($company, $this->request->getData());
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('La empresa fue actualizada exitosamente'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ups, hubo un problema al actualizar la empresa.'));
        }
        $statuses = $this->Companies->getStatuses();
        $cie10Types = $this->Companies->cie10Types();
        $this->set(compact('company', 'statuses', 'cie10Types'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $company = $this->Companies->get($id, [
            'contain' => ['Patients'],
        ]);
        if (empty($company->patients)) {
            if ($this->Companies->delete($company)) {
                $this->Flash->success(__('Se elimino la empresa.'));
            } else {
                $this->Flash->error(__('La empresa no se pudo eliminar, intente nuevamente.'));
            }
        } else {
            $this->Flash->error(__('La empresa tiene personas asignas, no es posible eliminarla..'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
