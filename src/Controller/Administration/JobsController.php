<?php
declare(strict_types=1);

namespace App\Controller\Administration;

use App\Controller\AppController;
/**
 * Jobs Controller
 *
 * @property \App\Model\Table\JobsTable $Jobs
 * @method \App\Model\Entity\Job[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class JobsController extends AppController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index()
	{
		$getJobs = $this->Jobs->find()->order(['id' => 'desc']);
		$jobs = $this->paginate($getJobs);
		$this->set(compact('jobs'));
	}

	public function showProgress() {
		$getJobs = $this->Jobs->find()->order(['id' => 'desc']);
		$jobs = $this->paginate($getJobs);
		$this->set(compact('jobs'));
	}

	public function run() {
		$data = [
			'running' => false,
			'error' => false,
			'msg' => '',
		];
		$lastJob = $this->Jobs
			->find()
			->where([
				'status IN' => [1, 2],
			])
			->order(['id' => 'desc'])
			->first();

		if ($lastJob) {
			$data['running'] = true;
			$data['msg'] = 'Ya hay un proceso corriendo.';
		} else {
			$connectedUser = $this->Authentication->getIdentity();

			$jobEntity = $this->Jobs->newEmptyEntity();
			$jobEntity = $this->Jobs->patchEntity($jobEntity, [
				'name' => 'manualRequestScrap',
				'status' => 1,
				'user_id' => $connectedUser->id,
			]);

			if ($this->Jobs->save($jobEntity)) {
				$data['running'] = true;
				$data['msg'] = 'El proceso esta a punto de iniciar.';
			} else {
				$data['error'] = true;
				$data['msg'] = 'Hubo un problema al iniciar el proceso de datos.';
			}

		}

		$this->viewBuilder()->setClassName('Json');
		$this->set(compact('data'));
		$this->viewBuilder()->setOption('serialize', ['data']);
	}

	public function checkProcess() {
		$data = [
			'running' => false,
		];
		$lastJob = $this->Jobs
			->find()
			->where([
				'status IN' => [1, 2],
			])
			->order(['id' => 'desc'])
			->first();

		if ($lastJob) {
			$data['running'] = true;
		}

		$this->viewBuilder()->setClassName('Json');
		$this->set(compact('data'));
		$this->viewBuilder()->setOption('serialize', ['data']);
	}
}
