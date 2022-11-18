<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ReportsFixture
 */
class ReportsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'patient_id' => 1,
                'medicalCenter' => 1,
                'doctor_id' => 1,
                'user_id' => 1,
                'pathology' => 'Lorem ipsum dolor sit amet',
                'startPathology' => '2022-11-18',
                'comments' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'type' => 1,
                'askedDays' => 1,
                'recommendedDays' => 1,
                'startLicense' => '2022-11-18',
                'relativeName' => 'Lorem ipsum dolor sit amet',
                'observations' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'status' => 1,
                'created' => '2022-11-18 15:59:51',
                'modified' => '2022-11-18 15:59:51',
                'fraud' => 1,
                'mode_id' => 1,
                'relativeLastname' => 'Lorem ipsum dolor sit amet',
                'relativeRelationship' => 'Lorem ipsum dolor sit amet',
                'privatedoctor_id' => 1,
                'speciality_id' => 1,
                'cie10_id' => 1,
                'risk_group' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
