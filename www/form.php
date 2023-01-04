<?php
require_once(".config.inc.php");

define('TITLE', "Form Editor");
include __LAYOUT_HEADER__;

$row_html='';
$letter_html='';
    $next_view="job";
    $max_forms =  get_max_drop_forms($_REQUEST['job_id']);
    $first_form=get_first_form($_REQUEST['job_id']);

    if (array_key_exists("form_number",$_REQUEST))
    {
		$prev_form_number = $_REQUEST['form_number']-1;
        $current_form_number = $_REQUEST['form_number']+1;
    } else {
		$prev_form_number = 0;
        $current_form_number = $first_form;
    }
    
    $new_forms = array();
	
	$next_form_number = $current_form_number + 1;    
   
    $form_data = $explorer->table('form_data');
    $form_data->where('form_number = ?', $current_form_number);
    $form_data->where('job_id = ?', $_REQUEST['job_id']);
    $results = $form_data->fetch();
    
    if(empty($results))
    {
        $current_form_number=$current_form_number+1;
    } 

    $sort=array("SORT_FORMER"=>1,"SORT_LETTER"=>1);
	
    logger("current_form_number", $current_form_number);

    $result = get_drop_form_data($_REQUEST['job_id'],$current_form_number,$sort);

   
    foreach ($result as $idx => $form_array)
    {
        $form_number = $form_array['form_number'];
        $job_id = $form_array['job_id'];

        $config = get_drop_details($job_id,$form_number);

        $new_forms[$form_number][$form_array['form_letter']][] = array(
            "id" => $form_array['id'],
            "market" => $form_array['market'],
            "pub" => $form_array['pub'],
            "ship" => $form_array['ship'],
            "count" => $form_array['count'],
            "config" => $config[$form_number]["config"],
            "bind" => $config[$form_number]["bind"],
            "former" => $form_array['former'],
            "facetrim" => $form_array['face_trim'],
			"nobindery" => $form_array['no_bindery'],
            "job_number" =>  $form_array['job_number'] );
    }

    foreach($new_forms as $form_number => $parts)
    {
    

        $next_button="Next Form";
        $form_url=__URL_HOME__."/process.php";
        $previous_form_html ='';
        if($current_form_number != $first_form )
        {
            $previous_form_html ='<input type="submit" name="submit_back" value="previous form">';
        }
        
        logger("next_form_number", $next_form_number);
        logger("max_forms", $max_forms);

        if($next_form_number > $max_forms ) {
            $next_view="save";
            $next_button="Save Form";
            $form_url=__URL_HOME__."/process.php";
            //$previous_form_html =' ';
            $next_form_number=$current_form_number;
        }
        
        $form_html['FORM_URL'] = $form_url;

        //$edit_button = '<input type="submit" name="submit" value="Edit">';

        $form_html["NAME"] = $form_array['job_number'] ." - Form Number " . $form_number ." of ". $max_forms .' - '.$config[$form_number]["config"].' - '.$config[$form_number]["bind"];
        $form_html["EDIT"] = $edit_button;
        $form_html["PREVIOUS"] = $previous_form_html;
        $form_html["NEXT"] =  ' <input type="submit" name="submit" value="'.$next_button.'">';
                
        foreach ($parts as $form_letter => $form_data)
        {
           
            $row_html = display_table_rows($form_data,$form_letter);
            $template->template("form/header", array("NUMBER" => $form_number,"LETTER" => $form_letter,"ROWS" => $row_html));
            $letter_html .= $template->return();
            $template->clear();
            
        }
    }

    $form_html["CURRENT_FORM_NUMBER"] = $current_form_number;
    $form_html["JOB_ID"] = $_REQUEST['job_id'];
    $form_html["NEXT_VIEW"] = $next_view;
    $form_html['FORM_BODY_HTML'] = $letter_html;
    $template->clear();

    $template->template("form/main",$form_html);
    $template->render();



 include __LAYOUT_FOOTER__; ?>