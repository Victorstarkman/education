<?php

namespace Service\Treatment;

use Service\LogService;

class TreatmentService
{

    /**
     * @var LogService $LogService
     */
    private $LogService;

    private $dateFieldNames = [
        'fechaEstado',
        'fechaCitacion',
        'fechaInicio',
        'fechaCreacion',
        'fechaFin',
        'fechaAlta'
    ];

    private $codigoRegEstat = [
        'D' => 'Docente',
        'A' => 'Auxiliar',
    ];

    public function __construct()
    {
        $this->LogService = new LogService();
    }

    public function run()
    {
        $log = [
            'date' => date('Y-m-d H:i:s'),
            'message' => 'starting the treatment of the data'
        ];
        $this->LogService->setLog($log, 'Info', 'TreatmentService.php');

        $files = $this->getJsonFilePageNoAprovadas();

        foreach ($files as $file) {
            $json = $this->getJson($file);

            if(empty($json)) {
                continue;
            }

            $newJson = $this->treatmentJson($json, $file);
            $status = $this->saveJson($newJson, $file);
            $this->deleteJson($file, $status);
        }
    }

    private function getJsonFilePageNoAprovadas()
    {
        $path = "File/PageNoAprovadas";
        $files = scandir($path);
        $files = array_diff($files, array('.', '..'));

        return $files;
    }

    private function getJson($file)
    {
        $path = "File/PageNoAprovadas/";
        $content = file_get_contents($path . $file);
        $content = json_decode($content);

        if (empty($content)) {
            $log = [
                'date' => date('Y-m-d H:i:s'),
                'message' => 'el archivo no es un json',
                'file' => $path.$file
            ];

            $this->LogService->setLog($log, 'Failure', 'TreatmentService.php');

            return false;
        }

        return $content;
    }

    private function treatmentJson($jsons, $file)
    {
        $log = [
            'date' => date('Y-m-d H:i:s'),
            'message' => 'iniciar el procesamiento de datos',
            'file' => $file
        ];

        $this->LogService->setLog($log, 'Treatment', 'TreatmentService.php');

        $newJson = json_decode(json_encode($jsons), true);

        //ordenar el campo general
        foreach($newJson as $keyJson => $valueJson) {
            foreach($valueJson as $key => $value) {
                $newJson[$keyJson][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson[$keyJson][$key] = $this->correctingDate($value);
                }
            }
        }

        //arreglando el campo de solicitudLicencia
        foreach($newJson as $keyJson => $valueJson) {
            $solicitudLicencia = $valueJson['solicitudLicencia'] ?? [];

            foreach($solicitudLicencia as $key => $value) {
                $newJson[$keyJson]['solicitudLicencia'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson[$keyJson]['solicitudLicencia'][$key] = $this->correctingDate($value);
                }
            }

            $centroAuditoria = $solicitudLicencia['agente']['centroAuditoria'] ?? [];
            foreach($centroAuditoria as $key => $value) {
                $newJson[$keyJson]['solicitudLicencia']['agente']['centroAuditoria'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson[$keyJson]['solicitudLicencia']['agente']['centroAuditoria'][$key] = $this->correctingDate($value);
                }
            }

            $centroAuditoriaPrestadora = $solicitudLicencia['agente']['centroAuditoria']['prestadora'] ?? [];
            foreach($centroAuditoriaPrestadora as $key => $value) {
                $newJson[$keyJson]['solicitudLicencia']['agente']['centroAuditoria']['prestadora'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson[$keyJson]['solicitudLicencia']['agente']['centroAuditoria']['prestadora'][$key] = $this->correctingDate($value);
                }
            }

            $centroJunta = $solicitudLicencia['agente']['centroJunta'] ?? [];
            foreach($centroJunta as $key => $value) {
                $newJson[$keyJson]['solicitudLicencia']['agente']['centroJunta'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson[$keyJson]['solicitudLicencia']['agente']['centroJunta'][$key] = $this->correctingDate($value);
                }
            }

            $centroJuntaPrestadora = $solicitudLicencia['agente']['centroJunta']['prestadora'] ?? [];
            foreach($centroJuntaPrestadora as $key => $value) {
                $newJson[$keyJson]['solicitudLicencia']['agente']['centroJunta']['prestadora'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson[$keyJson]['solicitudLicencia']['agente']['centroJunta']['prestadora'][$key] = $this->correctingDate($value);
                }
            }

        }

        //arreglando el campo de centroMedico
        foreach($newJson as $keyJson => $valueJson) {
            $centroMedico= $valueJson['centroMedico'] ?? [];

            foreach($centroMedico as $key => $value) {
                $newJson[$keyJson]['centroMedico'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {

                    $newJson[$keyJson]['centroMedico'][$key] = $this->correctingDate($value);
                }
            }

            foreach($centroMedico['prestadora'] as $key => $value) {
                $newJson[$keyJson]['centroMedico']['prestadora'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {

                    $newJson[$keyJson]['centroMedico']['prestadora'][$key] = $this->correctingDate($value);
                }
            }
        }


        return $newJson;
    }

    public function treatmentJsonConsultarDatos($jsons, $file)
    {
        $log = [
            'date' => date('Y-m-d H:i:s'),
            'message' => 'iniciar el procesamiento de datos',
            'file' => $file
        ];

        $this->LogService->setLog($log, 'Treatment', 'TreatmentService.php');

        $newJson = json_decode(json_encode($jsons), true);

        //ordenar el campo general
        foreach($newJson['solicitudLicencia'] as $keyJson => $valueJson) {

            if(in_array($keyJson, $this->dateFieldNames)) {
                $newJson['solicitudLicencia'][$keyJson] = $this->correctingDate($valueJson);
            }

            $solicitudLicenciaCentroAuditoria = $valueJson['centroAuditoria'] ?? [];
            foreach($solicitudLicenciaCentroAuditoria as $key => $value) {
                $newJson['solicitudLicencia'][$keyJson]['centroAuditoria'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson['solicitudLicencia'][$keyJson]['centroAuditoria'][$key] = $this->correctingDate($value);
                }
            }

            $solicitudLicenciaCentroAuditoriaPrestadora = $valueJson['centroAuditoria']['prestadora'] ?? [];
            foreach($solicitudLicenciaCentroAuditoriaPrestadora as $key => $value) {
                $newJson['solicitudLicencia'][$keyJson]['centroAuditoria']['prestadora'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson['solicitudLicencia'][$keyJson]['centroAuditoria']['prestadora'][$key] = $this->correctingDate($value);
                }
            }

            $solicitudLicenciaCentroJunta = $valueJson['centroJunta'] ?? [];
            foreach($solicitudLicenciaCentroJunta as $key => $value) {
                $newJson['solicitudLicencia'][$keyJson]['centroJunta'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson['solicitudLicencia'][$keyJson]['centroJunta'][$key] = $this->correctingDate($value);
                }
            }

            $solicitudLicenciaCentroJuntaPrestadora = $valueJson['centroJunta']['prestadora'] ?? [];
            foreach($solicitudLicenciaCentroJuntaPrestadora as $key => $value) {
                $newJson['solicitudLicencia'][$keyJson]['centroJunta']['prestadora'][$key] = $value;

                if(in_array($key, $this->dateFieldNames)) {
                    $newJson['solicitudLicencia'][$keyJson]['centroJunta']['prestadora'][$key] = $this->correctingDate($value);
                }
            }
        }

        return $newJson;
    }

    private function correctingDate($date)
    {
        if(empty($date)) {
            return null;
        }

        return date('Y-m-d H:i:s', $date/1000);
    }

    private function saveJson($json, $file)
    {
        $pathDatosTratados = "File/DatosTratados/";

        $file = str_replace('.json', '', $file);
        $file = $file . '_treated.json';
        $json = json_encode($json);

        file_put_contents($pathDatosTratados . $file, $json);

        $log = [
            'date' => date('Y-m-d H:i:s'),
            'message' => 'se guardo el archivo',
            'file' => $pathDatosTratados.$file
        ];

        $this->LogService->setLog($log, 'Treatment', 'TreatmentService.php');

        return true;
    }

    private function deleteJson($file, $status)
    {
        $path = "File/PageNoAprovadas/";

        if($status) {
            unlink($path . $file);
        }

        return true;
    }

    public function convertCodigoRegEstat($codigoRegEstat) {
        if(array_key_exists($codigoRegEstat, $this->codigoRegEstat)) {
            $codigoRegEstat = $this->codigoRegEstat[$codigoRegEstat];
        }

        return $codigoRegEstat;
    }

}
