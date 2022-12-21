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
		$jobs = $this->paginate($this->Jobs);
		$this->set(compact('jobs'));
	}
}
