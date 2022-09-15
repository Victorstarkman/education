<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reports Model
 *
 * @property \App\Model\Table\PatientsTable&\Cake\ORM\Association\BelongsTo $Patients
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FilesTable&\Cake\ORM\Association\HasMany $Files
 * @method \App\Model\Entity\Report newEmptyEntity()
 * @method \App\Model\Entity\Report newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Report[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Report get($primaryKey, $options = [])
 * @method \App\Model\Entity\Report findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Report patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Report[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Report|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Report saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportsTable extends Table
{
    public const LICENSES = [
        1 => [
            'name' => 'Titular',
            'extra' => false,
        ],
        2 => [
            'name' => 'Maternidad',
            'extra' => false,
        ],
        3 => [
            'name' => 'Cuidado de Familiar Enfermo',
            'extra' => true,
        ],
    ];

    public const ACTIVE = 1;
    public const NRLL = 2;
    public const DENIED = 3;
    public const GRANTED = 4;

    public const STATUSES = [
        self::ACTIVE => [
            'name' => 'Esperando resultados',
        ],
        self::NRLL => [
            'name' => 'NRLL',
        ],
        self::DENIED => [
            'name' => 'DENEGADA',
        ],
        self::GRANTED => [
            'name' => 'OTORGADA',
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

        $this->setTable('reports');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Patients', [
            'foreignKey' => 'patient_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('doctor', [
            'className' => 'Users',
            'foreignKey' => 'doctor_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Files', [
            'foreignKey' => 'report_id',
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
            ->integer('doctor_id')
            ->requirePresence('doctor_id', 'create')
            ->notEmptyString('doctor_id');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('pathology')
            ->maxLength('pathology', 255)
            ->requirePresence('pathology', 'create')
            ->notEmptyString('pathology');

        $validator
            ->date('startPathology')
            ->requirePresence('startPathology', 'create')
            ->notEmptyDate('startPathology');

        $validator
            ->scalar('comments')
            ->requirePresence('comments', 'create')
            ->notEmptyString('comments');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('askedDays')
            ->requirePresence('askedDays', 'create')
            ->notEmptyString('askedDays');

        $validator
            ->integer('status')
            ->notEmptyString('status');

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
        $rules->add($rules->existsIn('patient_id', 'Patients'), ['errorField' => 'patient_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    public function getLicenses()
    {
        $licenseArray = [];
        foreach (self::LICENSES as $key => $license) {
            $licenseArray[$key] = $license['name'];
        }

        return $licenseArray;
    }

    public function getActiveStatuses()
    {
        return [
            self::ACTIVE,
        ];
    }

    public function getStatusForDoctor()
    {
        $statusArray = [];
        foreach (self::STATUSES as $key => $status) {
            if (!in_array($key, $this->getActiveStatuses())) {
                $statusArray[$key] = $status['name'];
            }
        }

        return $statusArray;
    }
}
