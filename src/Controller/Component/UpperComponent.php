<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Upper component
 */
class UpperComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];
    public function upper($string){
        $stringArray= explode(" ", $string);
        $numero = count($stringArray);
        if ($numero>1)
        {
            //echo var_dump($stringArray);
            $stringcompleto='';
            $count=1;
            foreach($stringArray as $palabra){
                $stringcompleto.=ucfirst( mb_convert_case($palabra, MB_CASE_LOWER, "UTF-8"));
                $stringcompleto.=" ";
            } 
            return trim($stringcompleto);
        }else{
        return  ucfirst( mb_convert_case($string, MB_CASE_LOWER, "UTF-8"));  
        }
    }
    public function getCuil($string){
         //echo var_dump($string);
        $cuil='';
        $arrayChar=[46,45,32];
        if((isset($string))){
            $string=strval($string);
            $coincidencia=preg_match('/[-.\s]/',$string);
            if ($coincidencia){
                $stringarray=str_split($string);
                foreach($stringarray as $char){
                    //echo $char.'-'.ord($char).'<br/>';
                    if(!(in_array(ord($char),$arrayChar))){
                        $cuil.=$char;
                    }
                } 
                return $cuil; 
            }else{
                return $string;
            }
            
        }
    } 
}
