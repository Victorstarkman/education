<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Table\Cie10Table;
use App\Model\Table\PrivatedoctorsTable;
use App\Model\Table\ReportsTable;
use App\Model\Table\SpecialtiesTable;
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
 * @property string|null $comments
 * @property int $type
 * @property int $askedDays
 * @property int|null $recommendedDays
 * @property \Cake\I18n\FrozenDate|null $startLicense
 * @property string|null $relativeName
 * @property string|null $observations
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $fraud
 * @property int $mode_id
 * @property string $relativeLastname
 * @property string $relativeRelationship
 * @property int $privatedoctor_id
 * @property int $speciality_id
 * @property int|null $cie10_id
 * @property string $risk_group
 *
 * @property \App\Model\Entity\Patient $patient
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Mode $mode
 * @property \App\Model\Entity\Privatedoctor $privatedoctor
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
        'type' => true,
        'askedDays' => true,
        'recommendedDays' => true,
        'startLicense' => true,
        'relativeName' => true,
        'observations' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'fraud' => true,
        'mode_id' => true,
        'relativeLastname' => true,
        'relativeRelationship' => true,
        'privatedoctor_id' => true,
        'speciality_id' => true,
        'cie10_id' => true,
        'risk_group' => true,
        'patient' => true,
        'user' => true,
        'mode' => true,
        'privatedoctor' => true,
        'files' => true,
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

    public function getSpeciality()
    {
        if (isset($this->specialties)) {
            $name = $this->specialties->name;
        } elseif (!empty($this->speciality_id)) {
            $specialtiesTable = new SpecialtiesTable();
            $speciality = $specialtiesTable->get($this->speciality_id);
            $name = $speciality->name;
        } else {
            $name = 'Sin definir';
        }

        return $name;
    }

    public function getPathology()
    {
        if (isset($this->cie10)) {
            $name = $this->cie10->name . '(' . $this->cie10->code . ')';
        } elseif (!empty($this->cie10_id)) {
            $cie10Table = new Cie10Table();
            $cie10 = $cie10Table->get($this->cie10_id);
            $name = $cie10->name . '(' . $cie10->code . ')';
        } elseif (!empty($this->pathology)) {
            $name = $this->pathology;
        } else {
            $name = 'Sin definir';
        }

        return $name;
    }

    public function getPathologyCode()
    {
        if (isset($this->cie10)) {
            $name = $this->cie10->code;
        } elseif (!empty($this->cie10_id)) {
            $cie10Table = new Cie10Table();
            $cie10 = $cie10Table->get($this->cie10_id);
            $name = $cie10->code;
        } elseif (!empty($this->pathology)) {
            $name = $this->pathology;
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
        $doctor = false;
        if (isset($this->privatedoctor)) {
            $doctor = $this->privatedoctor;
        } elseif (!empty($this->privatedoctor_id)) {
            $privatedoctorsTable = new PrivatedoctorsTable();
            $doctor = $privatedoctorsTable->get($this->privatedoctor_id);
        }

        if ($doctor !== false) {
            $license = '';
            if (!empty($doctor->license)) {
                $license .= '(M.P: ' . $doctor->license;
            }

            if (!empty($doctor->licenseNational)) {
                if (empty($license)) {
                    $license = ' (';
                } else {
                    $license .= ' - ';
                }
                $license .= 'M.N: ' . $doctor->licenseNational . ')';
            } else {
                $license .= ')';
            }
            $doctorName = $doctor->name . ' ' . $doctor->lastname . ' ' . $license;
        } else {
            $doctorName = 'Sin Definir';
        }

        return $doctorName;
    }
}
