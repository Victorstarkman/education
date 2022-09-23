<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * County Entity
 *
 * @property int $id
 * @property int $state_id
 * @property string $name
 * @property string|null $fullName
 * @property string $oldID
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\City[] $cities
 */
class County extends Entity
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
        'state_id' => true,
        'name' => true,
        'fullName' => true,
        'oldID' => true,
        'created' => true,
        'modified' => true,
        'state' => true,
        'cities' => true,
    ];
}
