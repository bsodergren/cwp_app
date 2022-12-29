<?php

require_once(".config.inc.php");

if (array_key_exists("submit_edit",$_REQUEST) == TRUE)
{
	myHeader(__URL_HOME__."/edit_form.php?job_id=".$_REQUEST['job_id']."&form_number=".$_REQUEST['form_number']."");  
	exit;
} else {
	
	foreach ($_REQUEST as $key => $value )
	{


		
		if(str_starts_with($key, "former"))
		{
			list($front,$id) = explode("_",$key);
			$count = $explorer->table('form_data')->where('id', $id)->update(["former" => $value]);
		}
		
		if(str_starts_with($key, "facetrim"))
		{
			list($front,$id) = explode("_",$key);
			$count = $explorer->table('form_data')->where('id', $id)->update(["face_trim" => $value]);
		}
		
		if(str_starts_with($key, "nobindery"))
		{
			list($front,$id) = explode("_",$key);
			$count = $explorer->table('form_data')->where('id', $id)->update(["no_bindery" => $value]);
		}
		
		
	}
	if (array_key_exists("view",$_REQUEST) == TRUE)
	{
		if($_REQUEST['view'] == "save" ) {

			myHeader(__URL_HOME__."/index.php");
			exit;
		}
		
	}
	
	$next_form_number=$_REQUEST['form_number'];

	if (array_key_exists("submit_back",$_REQUEST) == TRUE)
	{
		$next_form_number=$next_form_number-2;
	

		$form_data = $explorer->table('form_data');
		$form_data->where('form_number = ?', $next_form_number + 1);
		$form_data->where('job_id = ?', $_REQUEST['job_id']);
		$results = $form_data->fetch();
	
		bdump($results);
		exit;
		if(empty($results))
		{
			$next_form_number=$next_form_number-1;
		} 
		
		if($next_form_number < 0 ){
			$next_form_number=1;
		}
	}
	
myHeader(__URL_HOME__."/form.php?job_id=".$_REQUEST['job_id']."&form_number=".$next_form_number."");  

}


