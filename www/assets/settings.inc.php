<?php 


$table = $explorer->table("settings");
$results = $table->fetchAssoc('id');

if ($results) {

    foreach ($results as $k => $u) {
        $setting[$u['name']]=  $u['type'].";".$u['value'];
      
        define($u['name'] ,$u['value']);

    }
    define("__SETTINGS__", $setting);
}





logger("DB Settings", __SETTINGS__);
?>