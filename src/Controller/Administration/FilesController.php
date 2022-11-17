<?php
declare(strict_types=1);

namespace App\Controller\Administration;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * Files Controller
 *
 * @property \App\Model\Table\FilesTable $Files
 * @method \App\Model\Entity\File[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilesController extends AppController
{
    public function addFile($reportID = null)
    {
        $this->viewBuilder()->setLayout('ajax');
        $ret['success'] = false;
        $receivedData = $this->request->getData();
        if (isset($receivedData['reportFile']) && !empty($receivedData['reportFile'])) {
            $attachment = $receivedData['reportFile'];

            $data = [
                'report_id' => $reportID,
                'name' => '',
                'type' => $attachment->getClientMediaType(),
            ];

            $path = WWW_ROOT . 'files' . DS;
            if (!file_exists($path) && !is_dir($path)) {
                mkdir($path);
            }

            $path .= $reportID . DS;
            if (!file_exists($path) && !is_dir($path)) {
                mkdir($path);
            }
            if (!$this->Files->checkDocument($attachment->getClientFilename(), $reportID)) {
                try {
                    $this->loadComponent('Uploadfile');
                    $uploadStatus = $this->Uploadfile->upload($attachment, $path);
                } catch (\Exception $e) {
                    $uploadStatus['success'] = false;
                }
                if ($uploadStatus['success']) {
                    $file = $this->Files->newEmptyEntity();
                    $data['name'] = $uploadStatus['filename'];
                    $file = $this->Files->patchEntity($file, $data);

                    if ($this->Files->save($file)) {
                        $ret['name'] =  'ID-' . $file->id . '(' . $uploadStatus['ext'] . ')';
                        $ret['success'] = true;
                    } else {
                        $ret['msg'] = 'Hubo un problema al guardar la imagen';
                    }
                } else {
                    $ret['msg'] = 'La imagen no se pudo subir o ya existe la imagen con el mismo nombre';
                }
            } else {
                $ret['msg'] = 'Ya existe la imagen con el mismo nombre';
            }
        }

        $this->set(compact('ret'));
    }

    public function viewFiles($id = null, $type = 'file')
    {
        $url = Router::url('/', true);
        $this->viewBuilder()->setLayout('ajax');
        $response = [];
        if ($type == 'file') {
            $files = $this->Files->find('all')
                ->where(['report_id' => $id])
                ->toArray();
            $output_dir =  'files' . DS;
            $output_full_path = WWW_ROOT . $output_dir;
            $this->loadComponent('Uploadfile');
            foreach ($files as $file) {
                $details = [];
                $details['name'] =   'ID-' . $file->id . ' (' . pathinfo($file->name, PATHINFO_EXTENSION) . ')<br/>' . $file->name;
                if ($this->Uploadfile->isImage(pathinfo($file->name, PATHINFO_EXTENSION))) {
                    $details['path'] =  $url . $output_dir . $file->report_id . DS . $file->name;
                    $details['absolutePath'] = $output_full_path  . $file->report_id . DS . $file->name;
                } else {
                    $details['path'] = $url . $output_dir . pathinfo($file->name, PATHINFO_EXTENSION) . '.jpg';
                    $details['absolutePath'] = $output_full_path  .  pathinfo($file->name, PATHINFO_EXTENSION) . '.jpg';
                    if (!file_exists($details['absolutePath'])) {
                        $details['path'] = $url . $output_dir . 'default.jpg';
                        $details['absolutePath'] = $output_full_path  . 'default.jpg';
                    }
                }

                if (file_exists($details['absolutePath'])) {
                    $details['size'] = filesize($details['absolutePath']);
                }
                $details['allSize'] = $this->Uploadfile->bringAllSize($details['path'], $details['absolutePath'], $file->name);
                $response[] = $details;
            }
        }

        $this->set('response', $response);
    }

    public function delete($id = null)
    {
        $this->viewBuilder()->setLayout('ajax');
        $this->request->allowMethod(['post', 'delete']);
        $data = $this->request->getData();
        $response = __('Error al eliminar la imagen.');
        if (isset($data['op']) && $data['op'] == 'delete' && isset($data['name'])) {
            $id = str_replace('ID-', '', $data['name']);
            $photoExist = true;
            try {
                $photo = $this->Files->get((int)$id);
            } catch (\Exception $e) {
                $photoExist = false;
                $response = __('La imagen no existe.');
            }

            if ($photoExist) {
                $output_dir = 'files/';
                $pathToProperty = WWW_ROOT . $output_dir  . $photo->report_id . DS . $photo->name;
                if ($this->Files->delete($photo)) {
                    $response = __('La imagen fue eliminada.');
                    unlink($pathToProperty);
                }
            }
        }

        $this->set(compact('response'));
    }
}
