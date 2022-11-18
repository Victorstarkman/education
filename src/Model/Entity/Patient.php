<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Patient Entity
 *
 * @property int $id
 * @property string $name
 * @property string $medical_id
 * @property string $address
 * @property string|null $email
 * @property string|null $offitial_email
 * @property string $document
 * @property string $job
 * @property string $phone
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int|null $city_id
 * @property int $seniority
 *
 * @property \App\Model\Entity\Report[] $reports
 * @property \App\Model\Entity\Report[] $reports_without_check
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\City $city
 */
class Patient extends Entity
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
        'name' => true,
        'medical_id' => true,
        'address' => true,
        'email' => true,
        'offitial_email' => true,
        'document' => true,
        'job' => true,
        'phone' => true,
        'created' => true,
        'modified' => true,
        'city_id' => true,
        'seniority' => true,
        'reports' => true,
        'reports_without_check' => true,
        'company' => true,
        'city' => true,
        'cuil' => true,
    ];
    public function getLocation() {
		return $this->city->name . ', ' . $this->city->county->name . ', ' . $this->city->county->state->name ;
	}
}
