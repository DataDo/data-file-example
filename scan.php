<?php

require_once 'db.php';

use DataDo\Data\Repository;


// Delete all current files
$repository->deleteAll();

$dirScanner = new RecursiveDirectoryIterator(realpath(__DIR__));

// Scan the project folder
foreach ($dirScanner as $child) {
    scan($child, $repository);
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

// Show the repository overview
$repository->checkDatabase();