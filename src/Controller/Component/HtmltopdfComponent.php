<?php
declare(strict_types=1);

namespace App\Controller\Component;

require_once 'htmlToPDF/vendor/autoload.php';

use Cake\Controller\Component;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

class HtmltopdfComponent extends Component
{
    public function initialize(array $config): void
    {
        parent::initialize($config); // TODO: Change the autogenerated stub
    }

    public function createReport($dataToShow)
    {

        try {
            ob_start();
            include 'reportTemplates/reportToCompanies.php';
            $content = ob_get_clean();
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [0, 0, 0, 0]);
            $html2pdf->writeHTML($content);
            $html2pdf->output('auditoria-' . strtolower($dataToShow->patient->lastname . '-' . $dataToShow->patient->name) . '.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }


}
