<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\HasMany $Reports
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    public const GROUPS = [
        1 => [
            'name' => 'Admin',
            'redirect' => '/admin/',
            'prefix' => 'Admin',
            'login_access' => true,
            'api_access' => true,
            'extraFields' => false,
        ],
        2 => [
            'name' => 'Auditor',
            'redirect' => '/auditor/',
            'prefix' => 'Auditor',
            'login_access' => true,
            'api_access' => false,
            'extraFields' => false,
        ],
        3 => [
            'name' => 'Administration',
            'redirect' => '/administration/',
            'prefix' => 'Administration',
            'login_access' => true,
            'api_access' => false,
            'extraFields' => false,
        ],
        4 => [
            'name' => 'API USER',
            'prefix' => 'api',
            'login_access' => false,
            'api_access' => true,
            'extraFields' => false,
        ],
    ];

    public const GENDERS = [
        1 => 'Hombre',
        2 => 'Mujer',
        3 => 'Otro',
        4 => 'F',
        5 => 'M',
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Trash.Trash');

        $this->hasMany('Reports', [
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('ReportsDoctor', [
            'className' => 'Reports',
            'foreignKey' => 'doctor_id',
        ]);
        $this->hasMany('MedicalCenters',[
            'foreignKey' => 'medicalCenter',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 120)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 120)
            ->requirePresence('lastname', 'create')
            ->notEmptyString('lastname');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 225)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->integer('group_id')
            ->requirePresence('group_id', 'create')
            ->notEmptyString('group_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

    public function setGroupIdentity($userID)
    {
        $user = $this->find()->where(['id' => $userID])->first();
        $user['groupIdentity'] = self::GROUPS[$user->group_id];

        return $user;
    }

    public function getGroupList()
    {
        $groupsToParse = self::GROUPS;
        $groups = [];
        foreach ($groupsToParse as $groupID => $groupToParse) {
            $groups[$groupID] = $groupToParse['name'];
        }

        return $groups;
    }

    public function getGendersList()
    {
        return self::GENDERS;
    }

    public function getNameGroup($name)
    {
        $key = array_search($name, array_column(self::GROUPS, 'name'));

        return $key + 1;
    }

    public function getDoctors()
    {
        return $this->find('all')
            ->where(['group_id' => $this->getNameGroup('Auditor')])
            ->all()
            ->combine('id', function ($entity) {

                return $entity->name . ' ' . $entity->lastname;
            })
            ->toArray();
    }

    public function signatureFromSVGtoPNG($imageToSave, $userID)
    {
        $imagePath = WWW_ROOT . 'signatures' . DS;
        if (!file_exists($imagePath) && !is_dir($imagePath)) {
            mkdir($imagePath);
        }
        $imagePath .= $userID . '_' . time() . '.png';
        $image = new \Imagick();
        $image->setBackgroundColor(new \ImagickPixel('transparent'));
        $image->readImageBlob(file_get_contents('data:' . $imageToSave));
        $image->setImageFormat('png64');
        $image->resizeImage(200, 0, \Imagick::FILTER_LANCZOS, 1);
        $image->writeImage($imagePath);

        return $imagePath;
    }
}
