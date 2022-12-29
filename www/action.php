<?php
require_once(".config.inc.php");

$action_url="/index.php";


if(key_exists("actSubmit",$_REQUEST))
{
	list($action) = explode(" ",$_REQUEST['actSubmit']);
	$job_id=$_REQUEST['job_id'];
	$action = strtolower($action);


	switch ($action) {
		case "download":
			$action_url = '/download.php?job_id='.$job_id;
			break;

		case "zip":
			$action_url = '/zip.php?job_id='.$job_id;
			break;

		case "mail":
			$action_url = '/mail.php?job_id='.$job_id;
			break;

		case "view":
			$action_url = '/view.php?action=list&job_id='.$job_id;
			break;	
		case "create":
			$action_url = '/excel.php?job_id='.$job_id;
			break;
			
		case "process":
			$action_url = '/form.php?job_id='.$job_id;
			break;
			
			
		case "refresh_import":	
		case  "create_xlsx":
		case  "delete_xlsx":
		
		case  "create_zip":		
		case "delete_zip":
		
		case  "delete_job":
			$action_url = '/edit.php?actSubmit='.$action.'&job_id='.$job_id;
			break;

			
			
	}
}


header("Location: ".__URL_HOME__.$action_url);
//echo JavaRefresh(__URL_HOME__.$action_url,1);
?>