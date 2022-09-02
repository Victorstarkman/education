<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class UsersTable extends Table{
    
	const GROUPS = [
		1 => [
			'name' => 'Admin',
			'redirect' => '/admin/',
			'prefix' => 'Admin',
			'login_access' => true,
			'api_access' => true,
		],
		2 => [
			'name' => 'Dienst Medico',
			'redirect' => '/dienst/',
			'prefix' => 'Dienst',
			'login_access' => true,
			'api_access' => false,
		],
		
		3 => [
			'name' => 'Dienst Administración',
			'redirect' => '/dienst/',
			'prefix' => 'Dienst',
			'login_access' => true,
			'api_access' => false,
		],
        //en un futuro los clientes accederan por aca
	];
    public function initialize(array $config): void
    {
        //tbl users
        parent::initialize($config);
        $this->setTable('tbl_users');
        $this->setDisplayField('last_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
	    $this->addBehavior('Muffin/Trash.Trash');
    }
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('first_name')
            ->maxLength('name', 120)
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('lastname', 120)
            ->notEmptyString('last_name');

        $validator
            ->email('email')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 225)
            ->notEmptyString('password');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        $validator
            ->integer('group_id')
            ->notEmptyString('group_id');

        return $validator;
    }
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }
    public function setGroupIdentity($userID) {
	    $user = $this->find()->where(['id' => $userID])->first();
	    $user['groupIdentity'] = self::GROUPS[$user->group_id];
		return $user;
    }
    public function getGroupList() {
		$groupsToParse = self::GROUPS;
		$groups= [];
		foreach ($groupsToParse as $groupID => $groupToParse) {
			$groups[$groupID] = $groupToParse['name'];
		}

		return $groups;
    }
}