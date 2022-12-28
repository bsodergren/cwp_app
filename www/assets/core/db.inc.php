<?php

if (!file_exists(__SQLITE_DATABASE__))
{
    require_once __INC_CORE_DIR__ . '/firstrun.inc.php';
    first_run();

}

$database = new Nette\Database\Connection(__DATABASE_DSN__);
