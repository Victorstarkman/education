<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reports Model
 *
 * @property \App\Model\Table\PatientsTable&\Cake\ORM\Association\BelongsTo $Patients
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ModesTable&\Cake\ORM\Association\BelongsTo $Modes
 * @property \App\Model\Table\PrivatedoctorsTable&\Cake\ORM\Association\BelongsTo $Privatedoctors
 * @property \App\Model\Table\FilesTable&\Cake\ORM\Association\HasMany $Files
 *
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
 *
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
            'name' => 'PENDIENTE',
        ],
        self::NRLL => [
            'name' => 'NRLL/AUSENTE',
        ],
        self::DENIED => [
            'name' => 'DENEGADA',
        ],
        self::GRANTED => [
            'name' => 'OTORGADA',
        ],
    ];
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
        $this->belongsTo('Modes', [
            'foreignKey' => 'mode_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('MedicalCenters', [
            'foreignKey' => 'id',
            'joinType' => 'INNER',
        ]);
	    $this->belongsTo('Privatedoctors', [
		    'foreignKey' => 'privatedoctor_id',
	    ]);
        $this->hasMany('Files', [
            'foreignKey' => 'report_id',
        ]);
        $this->belongsTo('Cie10', [
            'className' => 'Cie10',
            'foreignKey' => 'cie10_id',
        ]);
        $this->hasMany('Files', [
            'foreignKey' => 'report_id',
            'conditions' => ['Files.reportType' => 1],
        ]);

        $this->hasMany('FilesAuditor', [
            'className' => 'Files',
            'foreignKey' => 'report_id',
            'conditions' => ['FilesAuditor.reportType' => 2],
        ]);

	    $this->belongsTo('Specialties', [
		    'className' => 'Specialties',
		    'foreignKey' => 'speciality_id',
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
            ->date('startPathology')
            ->requirePresence('startPathology', 'create')
            ->notEmptyDate('startPathology');

        $validator
            ->scalar('comments')
            ->allowEmptyString('comments');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('askedDays')
            ->requirePresence('askedDays', 'create')
            ->notEmptyString('askedDays');

        $validator
            ->integer('recommendedDays')
            ->allowEmptyString('recommendedDays');

        $validator
            ->date('startLicense')
            ->allowEmptyDate('startLicense');

        $validator
            ->scalar('relativeName')
            ->maxLength('relativeName', 120)
            ->allowEmptyString('relativeName');

        $validator
            ->scalar('observations')
            ->allowEmptyString('observations');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        $validator
            ->integer('fraud')
            ->notEmptyString('fraud');

        $validator
            ->integer('mode_id')
            ->requirePresence('mode_id', 'create')
            ->notEmptyString('mode_id');

        $validator
            ->integer('speciality_id')
            ->requirePresence('speciality_id', 'create')
            ->notEmptyString('speciality_id');

        $validator
            ->integer('cie10_id')
            ->allowEmptyString('cie10_id');


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
        $rules->add($rules->existsIn('mode_id', 'Modes'), ['errorField' => 'mode_id']);

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
        return $this->getAllStatuses(true);
    }

    public function getAllStatuses($onlyActive = false)
    {
        $statusArray = [];
        foreach (self::STATUSES as $key => $status) {
            if ($onlyActive) {
                if (!in_array($key, $this->getActiveStatuses())) {
                    $statusArray[$key] = $status['name'];
                }
            } else {
                $statusArray[$key] = $status['name'];
            }
        }

        return $statusArray;
    }

    public function getDeniedStatus()
    {
        return [
            self::NRLL,
            self::DENIED,
        ];
    }

    public function getStatusesOfDiagnosis()
    {
        return [
            self::NRLL,
            self::DENIED,
            self::GRANTED,
        ];
    }
}
