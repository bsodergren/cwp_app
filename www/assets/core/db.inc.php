<?php

if (!file_exists(__SQLITE_DATABASE__))
{
    require_once __INC_CORE_DIR__ . '/firstrun.inc.php';
    first_run();

}

$storage = new Nette\Caching\Storages\FileStorage(sys_get_temp_dir());
$connection = new Nette\Database\Connection(__DATABASE_DSN__);
$structure = new Nette\Database\Structure($connection, $storage);
$conventions = new Nette\Database\Conventions\DiscoveredConventions($structure);
$explorer = new Nette\Database\Explorer($connection, $structure, $conventions, $storage);

