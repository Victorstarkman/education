<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Mailer\Mailer;

class MessengerComponent extends Component
{
    private $transport = 'default';
    private $setFrom = [
        'email' => 'administracion@dienstausentismo.com.ar',
        'name' => 'Sistema Ausentismo',
    ];
    private $testEmail = 'dev.administracion@dienstausentismo.com.ar';

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    private function sendEmail($to, $subject = 'Test', $template = 'default', $values = [])
    {
        $environment = Configure::read('ENVIRONMENT');

        // Si no es prod, envia al test email.
        if ($environment != 'PROD') {
            $to = $this->testEmail;
        }

        $bcc = [];
        if (is_array($to)) {
            $bcc = $to;
            $to = array_shift($bcc);
        }

        $mailer = new Mailer($this->transport);
        $mailer
            ->setFrom([$this->setFrom['email'] => $this->setFrom['name']])
            ->setTo($to);
        if (!empty($bcc)) {
            foreach ($bcc as $email) {
                $mailer->addBcc($email);
            }
        }
        $mailer
            ->setSubject($subject)
            ->setViewVars($values)
            ->viewBuilder()
            ->setTemplate($template);

        // Si es local, no hace el deliver.
        if ($environment != 'LOCAL') {
            $mailer->deliver();
        }
    }

    public function setToAuditor($report)
    {
        if (isset($report->reports[0]->doctor_id)) {
            $users = $this->getController()->getTableLocator()->get('Users');
            $doctor = $users->get($report->reports[0]->doctor_id);
            $to = $doctor['email'];
            $subject = 'Nuevo ausente cargado para revisar.';
            $template = 'newReport';

            $this->sendEmail($to, $subject, $template, []);
        }
    }
}
