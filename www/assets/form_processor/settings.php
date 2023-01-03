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
                $field = "value";
            }

            if (str_contains($key, "setting_")) {
                $pcs = explode('_', $key);
                $field = $pcs[1];
                $new_settiings[$field] = $value;
                continue;
            }

            if (str_contains($key, "-description")) {
                $pcs = explode('-', $key);
                $key = $pcs[0];
                $field = $pcs[1];
            }

            if (str_contains($key, "-radio")) {
                $pcs = explode('-', $key);
                $key = $pcs[0];
                $field = $pcs[1];
            }



            $count = $explorer->table('settings')->where('definedName', $key)->update([$field => $value]);
            $template->render('process/update_setting',['KEY' => $key, 'VALUE' => $value, 'FIELD' => $field ]);
            
            ob_flush();


        }

        if ($new_settiings['definedName'] != '') {

            if ($new_settiings['value'] == '') {

                $new_settiings['value'] = NULL;
            }

            $explorer->table("settings")->insert($new_settiings);
            echo "Added ".$new_settiings['definedName']." with ".$new_settiings['value']." <br>";
            ob_flush();

        }

    }

 


