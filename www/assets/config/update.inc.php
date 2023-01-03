<?php

use Nette\Utils\FileSystem;


class mediaUpdate
{
    protected $conn;
    public $table_name;

    public function __construct($db_conn)
    {   
        $this->conn = $db_conn;
        
    }

    public function set($table_name){
        $this->table_name = $table_name;
    }

    public function check_tableExists()
    {
        $query = "SELECT name FROM sqlite_master WHERE type='table' AND name='" . $this->table_name . "'";
        $result = $this->conn->fetchField($query);
        return $result;
    }

    public function check_columnExists($column)
    {
        $query = "SELECT 1 FROM pragma_table_info('" . $this->table_name . "') where name='" . $column . "'";
        $result = $this->conn->fetchField($query);
        return $result;
    }

    public function create_table( $table_name)
    {
        $sql_file = FileSystem::normalizePath(__SQLITE_DIR__ . "/default" . '/' . "cwp_table_" . $table_name . ".sql");
        if (file_exists($sql_file)) {
            Nette\Database\Helpers::loadFromFile($this->conn, $sql_file);
        }
    }

    public function create_column( $column, $type)
    {
        $query = "ALTER TABLE " . $this->table_name . " ADD " . $column . " " . $type . ";";
        $result = $this->conn->fetchField($query);
    }

    public function rename_column($old, $new)
    {
        $query = "ALTER TABLE " . $this->table_name . " RENAME COLUMN '" . $old . "'  TO '" . $new . "';";
        $result = $this->conn->fetchField($query);
    }
}


$initial_conn = new Nette\Database\Connection(__DATABASE_DSN__);
$update = new mediaUpdate($initial_conn);
//SQL Changes only!

$new_table = [];
$rename_column = ["settings" => ["setting_name" => "definedName"]];
$new_column = ["settings" => ["setting_name" => "text", "setting_description" => "text"]];

$refresh = false;

if (is_array($new_table)) {
    foreach ($new_table as $table_name) {
        $update->set($table_name);
        if (!$update->check_tableExists()) {
            output("table doesnt exist, adding ".$table_name);
            $update->create_table($table_name);
            $refresh = true;
        }
    }
}

if (is_array($rename_column)) {
    foreach ($rename_column as $table_name => $column) {
        $update->set($table_name);

        foreach ($column as $old => $new) {

            if ($update->check_columnExists($old)) {
                if (!$update->check_columnExists($new)) {
                    output("Updating ".$old." to ".$new ." in ".$table_name);

                    output("Updating columns in ".$table_name);
                    $update->rename_column($old, $new);
                    $refresh = true;
                }
            }
        }
    }
}



if (is_array($new_column)) {
    foreach ($new_column as $table_name => $column) {
        $update->set($table_name);
        foreach ($column as $field => $type) {
            if (!$update->check_columnExists($field)) {
                output("Adding columns in ".$table_name);
                $update->create_column($field, $type);
                $refresh = true;
            }
        }
    }
}



if($refresh == true)
{
    echo JavaRefresh("index.php",0);
    ob_flush();
}


$refresh = false;