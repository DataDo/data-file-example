<?php

require_once 'vendor/autoload.php';

/**
 * This is the entity we are going to use in this example.
 */
class File
{
    public $id;
    public $filePath;
    public $size;
    public $fileName;
    public $parent;
    public $isFile;
    public $extension;
    public $type;

    public function __construct(SplFileInfo $info = null)
    {
        if ($info !== null) {
            $this->filePath = $info->getRealPath();
            $this->fileName = $info->getBasename();
            $this->size = filesize($this->filePath);
            $this->parent = dirname($this->filePath);
            $this->isFile = $info->isFile();
            $this->extension = $info->getExtension();
            $this->type = $info->getType();
        }
    }

    public static function getExtension(File $file)
    {
        return $file->extension;
    }
}