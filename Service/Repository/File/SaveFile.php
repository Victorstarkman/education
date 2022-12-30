<?php

namespace Repository\File;


class SaveFile
{
    private $time;
    private $pathDefault;

    public function __construct()
    {
        $this->pathDefault = getenv('PATHFBOOT', '/var/www/filesBot/');
        $this->time = date('H_i_s');
        $this->createDefaultsPath();
    }

    public function createFilesPages(int $page, string $json)
    {
        $this->createPagesPath($page);
        $this->createPageJsonFile($page, $json);
    }

    private function createPageJsonFile(int $page, string $json)
    {
        $date = date('Y-m-d');

        $file = $this->pathDefault . "pages/" . "/$page/" . $date . '_' . uniqid(rand(0, 1000)) . ".json";
        echo $file."\n";
        file_put_contents($file, $json);
    }

    public function createFilesSolicitedJson(int $page, int $id, int $idReg, string $json)
    {
        $this->createUserPath($page, $id, $idReg);
        $this->createJsonFile($page, $id, $idReg, $json);
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

    private function createJsonFile(int $page, int $id, int $idReg, string $json)
    {
        $path = $this->pathDefault . "Treatment/" . date('Y-m-d') . "/$page/$id/json/consultarDatos/$idReg.json";
        file_put_contents($path, $json);
    }

    public function createImgFile(int $page, string $nameImg, int $idReg, string $img)
    {
        $path = $this->pathDefault . "Treatment/" . date('Y-m-d') . "/$page/$idReg/img/$nameImg.jpg";

        file_put_contents($path, $img);
    }

    private function createUserPath(int $page, int $idReg)
    {

        $date = date('Y-m-d');

        $paths = [
            "Treatment/$date/$page/$idReg",
            "Treatment/$date/$page/$idReg/json/consultarDatos",
            "Treatment/$date/$page/$idReg/img"
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

    public function deleteAndMovePathAndFilies(int $page, $IDS= [])
    {
        $path = $this->pathDefault . 'pages/' . $page;
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->deleteAndMovePathAndFilies($path . "/" . $file,$IDS);
            } else {
                //move file
                $pathFile = $this->pathDefault . 'Treatment/' . date('Y-m-d') . '/' . $page . '/' . basename($file);
                rename($file, $pathFile);
                $this->setJsonOrigin($pathFile,$page,$IDS);
            }
        }

        if ($this->checkEmptyPath($path)) {
            echo "deleteAndMovePathAndFilies: " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->deleteAndMovePathAndFilies($path,$IDS);
        }
    }

    public function deletePathAndFilies(int $page)
    {
        $path = $this->pathDefault . 'pages/' . $page;
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->deleteAndMovePathAndFilies($path . "/" . $file);
            } else {
                //delete file
                unlink($file);
            }
        }

        if ($this->checkEmptyPath($path)) {
            echo "deletePathAndFilies: " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->deleteAndMovePathAndFilies($path);
        }
    }
    public function movePathUsersForSolicited()
    {
        $path = $this->pathDefault . 'Treatment/' . date('Y-m-d') . '/';
        $files = glob($path . '/*'); // get all file names
        //$time = $this->time;
        foreach ($files as $file) { // iterate files
            if (!file_exists($this->pathDefault . 'Users/' . date('Y-m-d') . '/'  )) {
                mkdir($this->pathDefault . 'Users/' . date('Y-m-d') . '/' , 0777, true);
            }

            rename($file, $this->pathDefault . 'Users/' . date('Y-m-d') . '/' . basename($file));
        }

        if ($this->checkEmptyPath($path)) {
            echo "movePathUsersForSolicited : " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->movePathUsersForSolicited($path);
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

    public function setJsonOrigin(string $path, string $page, array $IDS): void
    {
        $json = json_decode(file_get_contents($path), true);
        foreach ($json as $key => $value) {
            $id = $IDS[$key];
            echo "\ncreating jsonOrigin: $id \n";
            $this->saveJsonOrigin($path,$id,$page, $value);
        }
    }

    private function saveJsonOrigin(string $path,int $id,  string $page, array $json)
    {

        $path = $this->pathDefault . "Treatment/" . date('Y-m-d') . "/$page/$id/json/jsonOrinResponse.json";
        file_put_contents($path, json_encode($json));
    }
}
