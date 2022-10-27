<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SearchController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    public function states()
    {
        $statesTable = $this->fetchTable('States');
        //$states = $statesTable->find()->all()->combine('id', 'name')->toArray();
        $states = $statesTable->find()->order(['name'=>'ASC'])->all()->combine('id','name')->toArray();
        $this->set(compact('states'));
    }

    public function counties()
    {
        $state_id = $this->request->getQuery('state_id');
        $countiesTable = $this->fetchTable('Counties');
        $counties = $countiesTable->find()->where(['state_id' => $state_id])->order(['name'=>'ASC'])->all()->combine('id', 'name')->toArray();

        $this->set(compact('counties'));
    }

    public function cities()
    {
        $county_id = $this->request->getQuery('county_id');
        $citiesTable = $this->fetchTable('Cities');
        $cities = $citiesTable->find()->where(['county_id' => $county_id])->order(['name'=>'ASC'])->all()->combine('id', 'name')->toArray();

        $this->set(compact('cities'));
    }

    public function citiesById()
    {
        $city_id = $this->request->getQuery('city_id');
        $citiesTable = $this->fetchTable('Cities');
        $city = $citiesTable->find()->where(['id' => $city_id])->all()->first();
		$county_id = $city->county_id;
	    $cities = $citiesTable->find()->where(['county_id' => $county_id])->all()->combine('id', 'name')->toArray();
	    $countiesTable = $this->fetchTable('Counties');
	    $county = $countiesTable->find()->where(['id' => $county_id])->all()->first();
		$state_id = $county->state_id;
        $this->set(compact('cities', 'county_id', 'state_id', 'city_id'));
    }
}
