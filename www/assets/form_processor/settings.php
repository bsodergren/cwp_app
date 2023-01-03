<?php 

define("REFRESH_TIMEOUT", 5);
define("REFRESH_URL", 'index.php');

    $form = new Formr\Formr('bootstrap4');

    if ($form->submitted())
    {
        // get our form values and assign them to a variable
        foreach ($_POST as $key => $value) {
            if ($key == 'submit') {
                continue;
            }

            if (!str_contains($key, "-")) {
                $field = "setting_value";
            }

            if (str_contains($key, "setting_")) {
                $field = $key;
                $new_settiings[$field] = $value;
                continue;
            }

            if (str_contains($key, "-description")) {
                $pcs = explode('-', $key);
                $key = $pcs[0];
                $field = "setting_".$pcs[1];
            }

            if (str_contains($key, "-name")) {
                $pcs = explode('-', $key);
                $key = $pcs[0];
                $field = "setting_".$pcs[1];
            }

            $arr[$key][$field] = $value;
            

            
           $count = $explorer->table('settings')->where('definedName', $key)->update([$field => $value]);
            $template->render('process/update_setting',['KEY' => $key, 'VALUE' => $value, 'FIELD' => $field ]);
            
            ob_flush();


        }
  
        if ($new_settiings['setting_definedName'] != '') {

            if ($new_settiings['setting_value'] == '') {

                $new_settiings['setting_value'] = NULL;
            }

            $explorer->table("settings")->insert($new_settiings);
            echo "Added ".$new_settiings['definedName']." with ".$new_settiings['value']." <br>";
            ob_flush();

        }

    }

 


