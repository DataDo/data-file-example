<?php

use DataDo\Data\Repository;

require 'vendor/autoload.php';

/**
 * We start by setting up a database connection
 */
$pdo = new PDO('mysql:host=localhost;dbname=file_demo', 'username', 'password',
    array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    )
);

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
}

/* Then we create the repository we mentioned earlier. To be able to do this we need some information:
 * - The class of the entity we want to store or get from this repository
 * - The PDO connection to the database
 * - The name of the property that identifies this entity. This will be used when updating and inserting objects
 */
$repository = new Repository(File::class, $pdo, 'id');

if (array_key_exists('scan', $_GET)) {
    // Delete all current files
    $repository->deleteAll();

    echo '<pre>' . PHP_EOL;

    $dirScanner = new RecursiveDirectoryIterator(realpath(__DIR__));

    // Scan the project folder
    foreach ($dirScanner as $child) {
        echo 'Scanning: ' . $child->getBaseName() . PHP_EOL;
        scan($child, $repository);
    }

    echo '</pre>';
}

// Scan a folder recursively but skip all folders named . and ..
function scan(SplFileInfo $fileInfo, $repository)
{
    $baseName = $fileInfo->getBasename();
    if ($baseName === '.' || $baseName === '..') {
        return;
    }

    $file = new File($fileInfo);
    $repository->save($file);
    if ($fileInfo->isDir()) {
        foreach (new RecursiveDirectoryIterator($fileInfo) as $child) {
            scan($child, $repository);
        }
    }
}

$repository->checkDatabase();