<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Table\ReportsTable;
use App\Model\Table\UsersTable;
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
        'relativeName' => true,
        'relativeLastname' => true,
        'relativeRelationship' => true,
        'privatedoctor_id' => true,
        'fraud' => true,
        'mode_id' => true,
        'area' => true,
        'files' => true,
        'modes'.'name'=>true,
    ];

    public function getNameStatus()
    {
        $status = ReportsTable::STATUSES[$this->status]['name'];
        if ($this->status == 3) {
            $status .= '<br/>Posible fraude: ';
            $status .= $this->fraud == 1 ? 'Si' : 'No';
        }

        return $status;
    }

    public function getNameLicense()
    {
        $name =  ReportsTable::LICENSES[$this->type]['name'];
        if (ReportsTable::LICENSES[$this->type]['extra']) {
            $name .=  '<br/>' . $this->relativeName . ' ' . $this->relativeLastname . ' (' . $this->relativeRelationship . ')';
        }

        return $name;
    }

    public function isWaitingResults()
    {
        return $this->status == ReportsTable::ACTIVE;
    }

    public function textForPDF()
    {
        $value = 'X';
        if (ReportsTable::LICENSES[$this->type]['extra']) {
            $value =  $this->relativeName . ' ' . $this->relativeLastname . ' (' . $this->relativeRelationship . ')';
        }

        return $value;
    }

    public function getDoctorName()
    {
        if (isset($this->doctor)) {
            $name = $this->doctor->name . ' ' . $this->doctor->lastname;
        } elseif (!empty($this->doctor_id)) {
            $userTable = new UsersTable();
            $doctor = $userTable->get($this->doctor_id);
            $name = $doctor->name . ' ' . $doctor->lastname;
        } else {
            $name = 'Sin definir';
        }

        return $name;
    }

    public function isOwner($onlineUserID = null)
    {
        return $this->doctor_id == $onlineUserID;
    }

    public function privateDoctor()
    {
        $license = '';
        if (!empty($this->privatedoctor->license)) {
            $license .= '(M.P: ' . $this->privatedoctor->license;
        }

        if (!empty($this->privatedoctor->licenseNational)) {
            if (empty($license)) {
                $license = ' (';
            } else {
                $license .= ' - ';
            }
            $license .= 'M.N: ' . $this->privatedoctor->licenseNational . ')';
        } else {
            $license .= ')';
        }

        return $this->privatedoctor->name . ' ' . $this->privatedoctor->lastname . ' ' . $license;
    }
}
