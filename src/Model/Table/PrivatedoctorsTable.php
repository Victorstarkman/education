<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Privatedoctors Model
 *
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\HasMany $Reports
 *
 * @method \App\Model\Entity\Privatedoctor newEmptyEntity()
 * @method \App\Model\Entity\Privatedoctor newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Privatedoctor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Privatedoctor get($primaryKey, $options = [])
 * @method \App\Model\Entity\Privatedoctor findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Privatedoctor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Privatedoctor[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Privatedoctor|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Privatedoctor saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Privatedoctor[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Privatedoctor[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Privatedoctor[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Privatedoctor[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PrivatedoctorsTable extends Table
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

        $this->setTable('privatedoctors');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Reports', [
            'foreignKey' => 'privatedoctor_id',
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 255)
            ->requirePresence('lastname', 'create')
            ->notEmptyString('lastname');

        $validator
            ->scalar('license')
            ->maxLength('license', 225)
            ->allowEmptyString('license');

        $validator
            ->scalar('licenseNational')
            ->maxLength('licenseNational', 225)
            ->allowEmptyString('licenseNational');

        return $validator;
    }
}
