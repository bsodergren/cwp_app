<?php

require_once(".config.inc.php");

define('TITLE', "Form Editor");
include __LAYOUT_HEADER__;
?>

<main role="main" class="container">

<?php

    $next_view="job";
    $max_forms =  get_max_drop_forms($_REQUEST['job_id']);
    $first_form=get_first_form($_REQUEST['job_id']);
    logger("max_forms", $max_forms);

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

    bdump($results);
    
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
    output( "<table class=\"blueTable\" border=1>");
    foreach($new_forms as $form_number => $parts)
    {
    

        $next_button="Next Form";
        $form_url=__URL_HOME__."/form_update.php";
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
            $form_url=__URL_HOME__."/form_update.php";
            //$previous_form_html =' ';
            $next_form_number=$current_form_number;
        }
        
        output('<form action="'.$form_url.'" method="post">');

        $edit_button='<input type="submit" name="submit_edit" value="Edit">';
        output(display_table_header("Form Number " . $form_number ." of ". $max_forms .' - '.$config[$form_number]["config"].' - '.$config[$form_number]["bind"],
        $previous_form_html,
        ' <input type="submit" name="submit" value="'.$next_button.'">',$edit_button));
        output("\t<tbody>");
        foreach ($parts as $form_letter => $form_data)
        {
            output( display_table_LetterHeader($form_number,$form_letter,$form_data));
        }
    }
    output("\n\t</tbody>\n</table>\n");
    output('<br>
    <input type="hidden" name="form_number" value="'.$current_form_number.'">
    <input type="hidden" name="job_id" value="'.$_REQUEST['job_id'].'">
    <input type="hidden" name="view" value="'.$next_view.'">
</form>
<br>');

?>
</main>
<?php include __LAYOUT_FOOTER__; ?>