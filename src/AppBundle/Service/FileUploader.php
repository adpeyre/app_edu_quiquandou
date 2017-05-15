<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /*private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }*/



    public function upload(UploadedFile $file, $params = array())
    {
       

        if(empty($params['filename']))
            $params['filename']=md5(uniqid()).'.'.$file->guessExtension();
        
        if(empty($params['target_dir']))
            return false;


        $status = $file->move($params['target_dir'], $params['filename']);

        return $status;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}