<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cie10 Model
 *
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\HasMany $Reports
 *
 * @method \App\Model\Entity\Cie10 newEmptyEntity()
 * @method \App\Model\Entity\Cie10 newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Cie10[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cie10 get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cie10 findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Cie10 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cie10[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cie10|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cie10 saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cie10[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cie10[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cie10[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cie10[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class Cie10Table extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('cie10');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Reports', [
            'foreignKey' => 'cie10_id',
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
            ->scalar('code')
            ->maxLength('code', 120)
            ->requirePresence('code', 'create')
            ->notEmptyString('code');

        return $validator;
    }
}
