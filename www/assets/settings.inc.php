<?php 


$table = $explorer->table("settings");
$table->order("type ASC");
$results = $table->fetchAssoc('id');

if ($results) {

    foreach ($results as $k => $u) {

        $setting[$u['definedName']] =  [ 
            'type' => $u['type'],
            'value' => $u['value'],
            'name' => $u['name'],
            'description' => $u['description'],
    ];

      
        define($u['definedName'] ,$u['value']);

    }
    define("__SETTINGS__", $setting);
}

unset($setting);



logger("DB Settings", __SETTINGS__);
?>