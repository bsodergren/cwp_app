<?php #skip #skip 

use Nette\Utils\FileSystem;

$initial_conn = new Nette\Database\Connection(__DATABASE_DSN__);

$_default_sql_dir = FileSystem::normalizePath(__SQLITE_DIR__ . "/default");

if ($dh = opendir($_default_sql_dir)) {    
    while ($sql_file = readdir($dh)) {
        if (!is_dir($_default_sql_dir . '/' . $sql_file)) {
            if (preg_match('/\.(sql)$/', $sql_file)) {
                
                $table_name = str_replace("cwp_table_","",basename($sql_file,".sql"));
                $initial_conn->query("drop table if exists " . $table_name);
                Nette\Database\Helpers::loadFromFile($initial_conn,  $_default_sql_dir. '/' . $sql_file);

            }
        }
    }
    closedir($dh);
    unset($sql_file);
    unset($_default_sql_dir);
}



