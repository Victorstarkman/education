<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Patient Entity
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $address
 * @property string $birthday
 * @property string|null $email
 * @property int $age
 * @property string $document
 * @property string $job
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Report[] $reports
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
        'lastname' => true,
        'address' => true,
        'birthday' => true,
        'email' => true,
        'age' => true,
        'document' => true,
        'job' => true,
        'created' => true,
        'modified' => true,
        'reports' => true,
        'phone' => true,
        'company_id' => true,
    ];
}
