<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Companies Model
 *
 * @method \App\Model\Entity\Company newEmptyEntity()
 * @method \App\Model\Entity\Company newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Company get($primaryKey, $options = [])
 * @method \App\Model\Entity\Company findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Company[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Company|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompaniesTable extends Table
{
    public const ACTIVE = 1;
    public const INACTIVE = 2;

    public const STATUSES = [
        self::ACTIVE => [
            'name' => 'Activo',
        ],
        self::INACTIVE => [
            'name' => 'Inactivo',
        ],
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

        $this->setTable('companies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Patients', [
            'foreignKey' => 'company_id',
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
            ->scalar('razon')
            ->maxLength('razon', 200)
            ->requirePresence('razon', 'create')
            ->notEmptyString('razon');

        $validator
            ->scalar('name')
            ->maxLength('name', 200)
            ->allowEmptyString('name');

        $validator
            ->scalar('cuit')
            ->maxLength('cuit', 255)
            ->requirePresence('cuit', 'create')
            ->notEmptyString('cuit');

        $validator
            ->integer('no_dienst')
            ->requirePresence('no_dienst', 'create')
            ->notEmptyString('no_dienst');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        return $validator;
    }

    public function getActiveStatuses()
    {
        return [
            self::ACTIVE,
        ];
    }

    public function getStatuses()
    {
        $statusArray = [];
        foreach (self::STATUSES as $key => $status) {
            $statusArray[$key] = $status['name'];
        }

        return $statusArray;
    }

    public function cie10Types()
    {
        return [
            1 => 'Default',
            2 => 'Group 1',
        ];
    }

    public function getCompanies()
    {
        return $this->find()->where(['status' => self::ACTIVE])->all()->combine('id', 'name');
    }
}
