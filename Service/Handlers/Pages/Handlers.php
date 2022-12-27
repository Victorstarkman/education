<?php

namespace Handlers\Pages;

class Handlers
{
    public function removeSpace(string $string): string
    {
        return preg_replace('/\s+\s+\s*?/', '', $string);
    }

    public function convetDate(string $key, string $date): string
    {
        $dateFieldNames = [
            'fechaEstado',
            'fechaCitacion',
            'fechaInicio',
            'fechaCreacion',
            'fechaFin',
            'fechaAlta'
        ];

        if (in_array($key, $dateFieldNames)) {
            if (empty($date)) {
                $date = null;
            } else {
                $date = date('Y-m-d H:i:s', $date / 1000);
            }
        }

        return $date;
    }

    public function convertCodigoRegEstat(string $key, string $string): string
    {
        $codigoRegEstat = [
            'D' => 'Docente',
            'A' => 'Auxiliar',
        ];

        $codigoKey = [
            'codigoRegEstat'
        ];

        if (in_array($key, $codigoKey)) {
            if (empty($string)) {
                $string = null;
            } else {
                $string = $codigoRegEstat[$string];
            }
        }

        return $string;
    }
}
