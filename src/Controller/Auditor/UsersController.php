<?php
declare(strict_types=1);

namespace App\Controller\Auditor;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\UnauthorizedException;

class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    public function signature()
    {
        try {
            $connectedUser = $this->Authentication->getIdentity();

            if (!isset($connectedUser->id)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }
            $user = $this->Users->get($connectedUser->id, [
                'contain' => [],
            ]);

            if (empty($user)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if (!is_null($user->signature)) {
                throw new UnauthorizedException('La firma fue guardada.');
            }

            $this->set(compact('user'));
        } catch (\Exception $e) {
	        if (stripos(get_class($e), 'UnauthorizedException')) {
		        $this->Flash->success($e->getMessage(), ['escape' => false]);
		        return $this->redirect('/');
	        }

            $this->Flash->error($e->getMessage(), ['escape' => false]);
            if (stripos(get_class($e), 'RecordNotFoundException')) {
                return $this->redirect('/');
            }
        }
    }

    public function saveSignature()
    {
        $data = ['error' => false];
        try {
            if (!$this->request->is('ajax')) {
                throw new \Exception('Request is not AJAX.');
            }
            if (!$this->request->is(['patch', 'post', 'put'])) {
                throw new \Exception('Request type is not valid.');
            }
            $postData = $this->request->getData();

            if (empty($postData['signature'])) {
                throw new RecordNotFoundException('No se encontro la firma.');
            }

            $connectedUser = $this->Authentication->getIdentity();

            if (!isset($connectedUser->id)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }
            $user = $this->Users->get($connectedUser->id);

            if (empty($user)) {
                throw new RecordNotFoundException('No se encontro el ID.');
            }

            if (!is_null($user->signature)) {
                throw new RecordNotFoundException('Ya tiene firma.');
            }

            $user->signature = $this->Users->signatureFromSVGtoPNG($postData['signature'], $user->id);

            if (!$this->Users->save($user)) {
                throw new \Exception('Error al generar la firma.');
            }

            $data = [
                'error' => false,
                'message' => 'Se genero la firma exitosamente.',
            ];
        } catch (\Exception $e) {
            $data = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
        $this->viewBuilder()->setClassName('Json');
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }
}
