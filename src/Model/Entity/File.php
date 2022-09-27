<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * File Entity
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $report_id
 *
 * @property \App\Model\Entity\Report $report
 */
class File extends Entity
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
        'type' => true,
        'report_id' => true,
        'report' => true,
    ];

    public function getUrl()
    {
        $url = Router::url('/', true);
        $output_dir =  'files' . DS;
        $output_full_path = WWW_ROOT . $output_dir;
        $details = [];
        if (str_contains($this->type, 'image') !== false) {
            $details['path'] = $url . $output_dir . $this->report_id . DS . $this->name;
            $details['absolutePath'] = $output_full_path  . $this->report_id . DS . $this->name;
        } else {
            $details['path'] = $url . $output_dir . pathinfo($this->name, PATHINFO_EXTENSION) . '.jpg';
            $details['absolutePath'] = $output_full_path  .  pathinfo($this->name, PATHINFO_EXTENSION) . '.jpg';
            if (!file_exists($details['absolutePath'])) {
                $details['path'] = $url . $output_dir . 'default.jpg';
                $details['absolutePath'] = $output_full_path  . 'default.jpg';
            }
        }

        return $details['path'];
    }
}
