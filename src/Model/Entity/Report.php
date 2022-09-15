<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Table\ReportsTable;
use Cake\ORM\Entity;

/**
 * Report Entity
 *
 * @property int $id
 * @property int $patient_id
 * @property int $medicalCenter
 * @property int $doctor_id
 * @property int $user_id
 * @property string $pathology
 * @property \Cake\I18n\FrozenDate $startPathology
 * @property string $comments
 * @property int $type
 * @property int $askedDays
 * @property int $recommendedDays
 * @property \Cake\I18n\FrozenDate $startLicense
 * @property string $cie10
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Patient $patient
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\File[] $files
 */
class Report extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'patient_id' => true,
        'medicalCenter' => true,
        'doctor_id' => true,
        'user_id' => true,
        'pathology' => true,
        'startPathology' => true,
        'comments' => true,
        'observations' => true,
        'type' => true,
        'askedDays' => true,
        'recommendedDays' => true,
        'startLicense' => true,
        'cie10' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'patient' => true,
        'user' => true,
        'files' => true,
    ];

    public function getNameStatus()
    {
        return ReportsTable::STATUSES[$this->status]['name'];
    }

	public function getNameLicense()
    {
        return ReportsTable::LICENSES[$this->type]['name'];
    }

	public function isWaitingResults()
    {
        return $this->status == ReportsTable::ACTIVE;
    }
}
