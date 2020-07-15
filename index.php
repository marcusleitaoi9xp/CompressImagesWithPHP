<?php
    class ZipHelper extends Helper 
    {
        /**
        * @param @files: array(array("name" => "nome-do-arquivo.jpg", "url" => "http://www.url.com.br/do/arquivo.jpg"))
        * @param @zipName: string com o nome do arquivo zip (sem a extenso ".zip") que o cliente far download
        */
        public function compressZip ($files = array(), $zipName = 'file')
        {
            if(empty($files) === false) {
                $fileName = $zipName.'.zip';
                $path = __DIR__;
                $fullPath = $path . DIRECTORY_SEPARATOR . $fileName;

                foreach ($files as $file) {
                    $content = file_get_contents($file['url'], FILE_BINARY);
                    file_put_contents($path."/".$file['name'], $content, FILE_BINARY);
                }
                    
                $zip = new ZipArchive();

                if($zip->open($fullPath, ZipArchive::CREATE)){
                    foreach ($files as $file) {
                        $zip->addFile($path."/".$file['name'], $file['name']);
                    }
                    $zip->close();
                }

                foreach ($files as $file) {
                    unlink($path."/".$file['name']);
                }

                if(file_exists($fullPath)){
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="'.$fileName.'"');
                    readfile($fullPath);
                    unlink($fullPath);
                }
            }
        }
    }