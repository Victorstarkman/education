<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Exception\BadRequestException;
use Thumber\ThumbCreator;

class UploadfileComponent extends Component
{
    private $pathToTmp = ROOT . '/tmp/thumbs/';
    private $sizes = [];

    function upload($file, $moveTo)
    {
        if (!file_exists($this->pathToTmp) && !is_dir($this->pathToTmp)) {
            mkdir($this->pathToTmp);
        }
        $returnMsg = ['success' => false, 'filename' => ''];

        if ($file->getSize() > 0) {
            if (!file_exists($moveTo) && !is_dir($moveTo)) {
                mkdir($moveTo);
            }
            $path = $file->getClientFilename();
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $fileNameWithOutExtension = rand();
            $fileName = $fileNameWithOutExtension . '.' . $ext;
            $output_dir = $moveTo . $fileNameWithOutExtension;
            $finalOutput = $moveTo . $file->getClientFilename();
            if ($this->isImage($ext)) {
                try {
                    $file->moveTo($this->pathToTmp .  $fileName);
                    $imgWidthHeight = getimagesize($this->pathToTmp .  $fileName);
                    $thumber = new ThumbCreator(ROOT . '/tmp/thumbs/' .  $fileName);
                    $thumber->resize($imgWidthHeight['0'], $imgWidthHeight['1']);
                    $thumber->save(['target' => $finalOutput ]);
                    if (!empty($this->sizes)) {
                        foreach ($this->sizes as $size) {
                            $thumber->resize($size[0], $size[1]);
                            $thumber->save(['target' => WWW_ROOT  . $output_dir . '_' . $size[0] . 'x' . $size[1] . '.' . $ext ]);
                        }
                    }
                    unlink($this->pathToTmp .  $fileName);
                    $returnMsg['filename'] = $file->getClientFilename();
                    $returnMsg['ext'] = $ext;
                    $returnMsg['success'] = true;
                } catch (\Exception $e) {
                    throw new BadRequestException(__('No se pudo crear la imagen.'));
                }
            } else {
                try {
                    $file->moveTo($finalOutput);
                    $returnMsg['filename'] = $file->getClientFilename();
                    $returnMsg['ext'] = $ext;
                    $returnMsg['success'] = true;
                } catch (\Exception $e) {
                    $returnMsg['msg'] = $e;
                    $returnMsg['success'] = false;
                }
            }
        } else {
            $returnMsg['success'] = false;
        }

        return $returnMsg;
    }

    function bringAllSize($path, $absolutePath, $fileName)
    {
        $path = str_replace($fileName, '', $path);
        $absolutePath = str_replace($fileName, '', $absolutePath);
        $namePhoto = explode('.', $fileName);
        $allSizes = [];
        foreach ($this->sizes as $name => $size) {
            $newNamePhoto = $namePhoto[0] . '_' . $size[0] . 'x' . $size[1] . '.' . $namePhoto[1];
            $allSizes[$name] = [
                'path' => $path . $newNamePhoto,
                'absolutePath' => $absolutePath . $newNamePhoto,
                'size' => filesize($absolutePath . $newNamePhoto),
            ];
        }

        return $allSizes;
    }

    function isImage($ext)
    {
        $isImage = false;
        switch ($ext) {
            case 'png':
            case 'jpg':
            case 'jpeg':
            case 'gif':
                $isImage = true;
                break;
        }

        return $isImage;
    }
}
