<?php

use DataDo\Data\Repository;

require_once 'file.php';

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
 * And use this to build a repository
 */
$repository = new Repository(File::class, $pdo, 'id');