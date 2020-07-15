<?php
    class ZipHelper
    {
        /**
        * @param @files: array(array("name" => "nome-do-arquivo.jpg", "url" => "http://www.url.com.br/do/arquivo.jpg"))
        * @param @zipName: string com o nome do arquivo zip (sem a extenso ".zip") que o usuário fará o download
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

        public function exemploUso() {
            $files = array(
                array(
                    "name" => "praia",
                    "url" => "https://viajantecomum.com/wp-content/uploads/2019/04/garca-torta-953x715.jpg"
                ),
                array(
                    "name" => "morangos",
                    "url" => "https://conteudo.imguol.com.br/c/entretenimento/78/2018/02/28/morango-1519823853148_v2_1920x1280.jpg"
                ),
                array(
                    "name" => "caviar",
                    "url" => "https://s2.glbimg.com/MVaVZyFOdnGDBgVzYDkszBAI7yA=/512x320/smart/e.glbimg.com/og/ed/f/original/2019/05/01/gq095-gostocaviar-01.jpg"
                )
            );

            $this->compressZip($files, "arquivo");
        }
    }
