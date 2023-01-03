<?php 


$table = $explorer->table("settings");
$table->order("setting_type ASC");
$results = $table->fetchAssoc('id');

if ($results) {

    foreach ($results as $k => $u) {

        $setting[$u['definedName']] =  [ 
            'type' => $u['setting_type'],
            'value' => $u['setting_value'],
            'name' => $u['setting_name'],
            'description' => $u['setting_description'],
    ];

        define($u['definedName'] ,$u['setting_value']);

    }
    define("__SETTINGS__", $setting);
}

unset($setting);

logger("DB Settings", __SETTINGS__);
?>