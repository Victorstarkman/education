<?php

namespace Repository\File;
use Handlers\Pages\Handlers;

class SaveFile
{
    private $time;
    private $pathDefault;
    private Handlers $Handlers;

    public function __construct()
    {
        $this->pathDefault = getenv('PATHFBOOT', '/var/www/filesBot/');
        $this->time = date('H_i_s');
        $this->createDefaultsPath();
        $this->Handlers = new Handlers();
    }

    public function createFilesPages(int $page, string $json)
    {
        echo "createFilesPages \n";
        $this->createPagesPath($page);
        $this->createPageJsonFile($page, $json);
    }

    private function createPageJsonFile(int $page, string $json)
    {
        $date = date('Y-m-d');

        $file = $this->pathDefault . "pages/" . "$page/" . $date . '_' . uniqid(rand(0, 1000)) . ".json";
        echo $file . "\n";
        file_put_contents($file, $json);
    }

    public function createFilesSolicitedJson(int $page, int $id, int $idReg, string $json, string $dataPageFile)
    {
        $this->createUserPath($page, $id, $dataPageFile);
        $this->createJsonFile($page, $id, $idReg, $json, $dataPageFile);
    }

    public function getPathPageDate()
    {
        $paths = scandir($this->pathDefault . 'pages/');
        $paths = array_diff($paths, array('.', '..'));

        if (empty($paths)) {
            return [];
        }

        array_walk($paths, function (&$value, $key) {
            $value = $this->pathDefault . 'pages/' . $value . '/';
        });

        return $paths;
    }

    public function getPathPages($path)
    {
        $paths = scandir($path);
        $paths = array_diff($paths, array('.', '..'));

        if (empty($paths)) {
            return [];
        }

        array_walk($paths, function (&$value, $key) use ($path) {
            $value = $path . '/' . $value . '/';
        });

        return $paths;
    }

    public function getPathFilesPage($path)
    {
        $paths = scandir($path);
        $paths = array_diff($paths, array('.', '..'));

        if (empty($paths)) {
            return [];
        }

        array_walk($paths, function (&$value, $key) use ($path) {
            $value = $path . $value;
        });

        return $paths;
    }

    public function getFile($path)
    {
        return file_get_contents($path);
    }

    private function createJsonFile(int $page, int $id, int $idReg, string $json, string $date)
    {
        $file = $this->pathDefault . "Treatment/" . $date . "/$page/$id/json/consultarDatos/$idReg.json";

        file_put_contents($file, $json);
    }

    public function createImgFile(int $page, string $nameImg, int $idReg, string $img, string $dataPageFile)
    {
        $path = $this->pathDefault . "Treatment/" . $dataPageFile . "/$page/$idReg/img/";

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $path . $nameImg . '.jpg';

        file_put_contents($file, $img);
    }

    private function createUserPath(int $page, int $id, string $date)
    {
        $paths = [
            "Treatment/$date",
            "Treatment/$date/$page",
            "Treatment/$date/$page/$id",
            "Treatment/$date/$page/$id/json/consultarDatos",
            "Treatment/$date/$page/$id/img"
        ];

        foreach ($paths as $path) {
            if (!file_exists($this->pathDefault . $path)) {
                mkdir($this->pathDefault .  $path, 0777, true);
            }
        }
    }

    private function createPagesPath(int $page)
    {

        $date = date('Y-m-d');

        $paths = [
            "pages/",
            "pages/$page",
            "Users/",
            "Users/$date"
        ];

        foreach ($paths as $path) {
            if (!file_exists($this->pathDefault .  $path)) {
                mkdir($this->pathDefault .  $path, 0777, true);
            }
        }
    }

    private function createDefaultsPath()
    {
        $paths = [
            'Treatment',
            'pages'
        ];

        foreach ($paths as $path) {
            if (!file_exists($this->pathDefault .  $path)) {
                mkdir($this->pathDefault .  $path);
            }
        }
    }

    public function deleteAndMovePathAndFilies(int $page,string  $dataPageFile)
    {
        $path = $this->pathDefault . 'pages/' . $page;
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->deleteAndMovePathAndFilies($path . "/" . $file, $dataPageFile);
            } else {
                //move file
                $pathFile = $this->pathDefault . 'Treatment/' . $dataPageFile . '/' . $page . '/' . basename($file);

                rename($file, $pathFile);
            }
        }

        if ($this->checkEmptyPath($path)) {
            echo "deleteAndMovePathAndFilies: " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->deleteAndMovePathAndFilies($path, $dataPageFile);
        }
    }

    public function deletePathAndFilies(int $page, string $dataPageFile)
    {
        $path = $this->pathDefault . 'pages/' . $page;
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->deleteAndMovePathAndFilies($path . "/" . $file, $dataPageFile);
            } else {
                //delete file
                unlink($file);
            }
        }

        if ($this->checkEmptyPath($path)) {
            echo "deletePathAndFilies: " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->deleteAndMovePathAndFilies($path, $dataPageFile);
        }
    }
    public function movePathUsersForSolicited(string $data)
    {
        $path = $this->pathDefault . 'Treatment/' . $data . '/';
        $files = glob($path . '/*'); // get all file names
        //$time = $this->time;
        foreach ($files as $file) { // iterate files
            if (!file_exists($this->pathDefault . 'Users/' . $data . '/')) {
                mkdir($this->pathDefault . 'Users/' . $data . '/', 0777, true);
            }

            rename($file, $this->pathDefault . 'Users/' . $data . '/' . basename($file));
        }

        if ($this->checkEmptyPath($path)) {
            echo "movePathUsersForSolicited : " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->movePathUsersForSolicited($data);
        }
    }

    private function checkEmptyPath($path)
    {
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->checkEmptyPath($path . "/" . $file);
            } else {
                return false;
            }
        }

        return true;
    }

    public function checkPastTreatment(int $id)
    {
        $path = getenv('PATHFBOOT') . "Treatment/";

        $paths = scandir($path);
        $paths = array_diff($paths, array('.', '..'));
        foreach ($paths as $key => $value) {
            $newpath = getenv('PATHFBOOT') . "Treatment/" . $value . "/";
            $newpath = scandir($newpath);
            $newpath = array_diff($newpath, array('.', '..'));
            foreach ($newpath as $k => $v) {
                $vn = getenv('PATHFBOOT') . "Treatment/" . $value . "/" . $v;
                $vn = scandir($vn);
                $vn = array_diff($vn, array('.', '..'));

                if (in_array($id, $vn)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function saveJsonOrigin(int $id,  string $page, array $json, string  $dataPageFile)
    {

        $path = $this->pathDefault . "Treatment/" . $dataPageFile . "/$page/$id/json/jsonOrinResponse.json";
        $json = $this->standardizeData($json);
        file_put_contents($path, json_encode($json));
    }


    private function standardizeData(array $data)
    {
        $newData = $data;
        foreach ($data as $key => $value) {

            if (is_array($value)) {
                $newData[$key] = $this->standardizeData($value);
            } else {
                if ($value) {
                    $isJson = json_encode($value, true);
                    if (is_array($isJson)) {
                        $newData[$key] = $this->standardizeData($isJson);
                    } else {
                        $newData[$key] = $this->Handlers->removeSpace($value);
                        $newData[$key] = $this->Handlers->convetDate($key, $newData[$key]);
                        $newData[$key] = $this->Handlers->convertCodigoRegEstat($key, $newData[$key]);
                    }
                } else {
                    $newData[$key] = $value;
                }
            }
        }

        return $newData;
    }
}
