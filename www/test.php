<?php
require_once(".config.inc.php");
define('TITLE', "Test page");

include __LAYOUT_HEADER__;
function toArray($obj) {
    $vars = get_object_vars ( $obj );
    $array = array ();
    foreach ( $vars as $key => $value ) {
        $array [ltrim ( $key, '_' )] = $value;
    }
    return $array;
}
$var = get_drop_form_data(2, 2,["SORT_FORMER"=>1,"SORT_LETTER"=>1]);


foreach($var as $obj){
    $var_arr[] =  toArray($obj);

   
}
 dump($var_arr);
//}

include __LAYOUT_FOOTER__; ?>