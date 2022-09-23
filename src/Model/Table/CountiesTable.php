<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Counties Model
 *
 * @property \App\Model\Table\StatesTable&\Cake\ORM\Association\BelongsTo $States
 * @property \App\Model\Table\CitiesTable&\Cake\ORM\Association\HasMany $Cities
 *
 * @method \App\Model\Entity\County newEmptyEntity()
 * @method \App\Model\Entity\County newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\County[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\County get($primaryKey, $options = [])
 * @method \App\Model\Entity\County findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\County patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\County[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\County|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\County saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\County[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\County[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\County[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\County[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CountiesTable extends Table
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

        $this->setTable('counties');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('States', [
            'foreignKey' => 'state_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Cities', [
            'foreignKey' => 'county_id',
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
            ->integer('state_id')
            ->requirePresence('state_id', 'create')
            ->notEmptyString('state_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('fullName')
            ->maxLength('fullName', 255)
            ->allowEmptyString('fullName');

        $validator
            ->scalar('oldID')
            ->maxLength('oldID', 255)
            ->requirePresence('oldID', 'create')
            ->notEmptyString('oldID');

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
        $rules->add($rules->existsIn('state_id', 'States'), ['errorField' => 'state_id']);

        return $rules;
    }
}
