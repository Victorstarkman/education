<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Privatedoctor Entity
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string|null $license
 * @property string|null $licenseNational
 *
 * @property \App\Model\Entity\Report[] $reports
 */
class Privatedoctor extends Entity
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
        'license' => true,
        'licenseNational' => true,
        'reports' => true,
    ];
}
