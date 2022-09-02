<?php
declare(strict_types=1);

namespace App\Controller;

use Authentication\Identity;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Response;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
	/* public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		$this->Authentication->addUnauthenticatedActions(['login']);
	} */

	public function login()
	{
		$this->viewBuilder()->setLayout('login');
		$user = $this->Users->newEmptyEntity();
		/* $this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {
			$userID = $this->Authentication->getIdentity()->id;
			$data = $this->Users->setGroupIdentity($userID);
			if ($data->groupIdentity['login_access']) {
				$identity = new Identity($data);
				$this->Authentication->setIdentity($identity);
				// redirect to /articles after login success
				$redirect = $this->request->getQuery('redirect', [
					'controller' => 'Users',
					'action' => 'redirectTo',
				]);
				return $this->redirect($redirect);
			} else {
				$this->Authentication->logout();
				throw new UnauthorizedException('No tenes permisos suficientes');
			}
		}
		// display error if user submitted and authentication failed
		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error(__('Invalid username or password'));
		} */
		$this->set(compact('user'));
	}

	public function logout()
	{
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {
			$this->Authentication->logout();
			return $this->redirect(['controller' => 'Users', 'action' => 'login']);
		}
	}

	public function redirectTo()
	{
		$redirect = $this->Authentication->getIdentity()->groupIdentity['redirect'];
		return $this->redirect($redirect);
	}
}
