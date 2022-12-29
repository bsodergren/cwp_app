<?php 


$settings = new MetaSettings ();
$val = $settings->orderBy("type")->get();
if ($val) {

    foreach ($val as $u) {
        $setting[$u->name]=  $u->type.";".$u->value;
      
        define($u->name ,$u->value);

    }
    define("__SETTINGS__", $setting);
}

?>