<?php
require_once(".config.inc.php");


$job_id=$_REQUEST['job_id'];


$job  = $connection->fetch('SELECT * FROM media_job WHERE job_id = ?',$job_id);

    
if(key_exists("actSubmit",$_REQUEST))
{
	switch ($_REQUEST['actSubmit']) {

		case "refresh_import":
			delete_xlsx($job_id);   
			delete_zip($job_id);
			delete_form_data($job_id);
			add_new_media_drop(__PDF_UPLOAD_DIR__."/".$job['pdf_file'],$job['job_number']);

			break;
	
		case  "create_zip":
			$zip_file = get_zip_filename($job['pdf_file'],$job['job_number']);

			zip_Workbooks($job['xlsx_dir'],$job_id, $zip_file );
			
			$job['zip_file'] = $zip_file;
			echo   JavaRefresh(__URL_PATH__."/index.php");

			break;

		case  "create_xlsx":

				$xlsx_array = build_xlsx_array($_REQUEST['job_id']);
				$keyidx=array_key_first($xlsx_array);

				$job_number = $xlsx_array[$keyidx]["job_number"];
				$pdf_file = $xlsx_array[$keyidx]["pdf_file"];

				write_xlsx_workbook($xlsx_array,$job_number,$pdf_file);
				$job['xlsx_dir'] = get_xlsx_directory($pdf_file,$job_number);

			break;

		case "delete_zip":
			delete_zip($job_id);
			break;

		case  "delete_xlsx":
			delete_xlsx($job_id);   
			delete_zip($job_id);
			break;

		case  "delete_job":
			echo   JavaRefresh(__URL_PATH__."/delete_job.php?job_id=".$job_id);
			break;
			
		case  "actSubmit":
			update_job_number($job_id,$_REQUEST['job_number']);
			delete_xlsx($job_id);
			delete_zip($job_id);
			break;
	}
	
	echo   JavaRefresh(__URL_PATH__."/index.php");
	exit;
}

define('TITLE', "Media Job editor");
include __LAYOUT_HEADER__;
$form_url = __URL_PATH__."/edit.php?job_id=".$job_id;

?>

<main role="main" class="container">
<table>
<tr>
    <td>
  <form action="<?php echo $form_url;?>" method="post">
<?
    
output('Media Drop - <b>'.$job["close"].'</b><br>');
output('New Job Number <input type="text" name="job_number" value="'.$job["job_number"].'"><br>');
output('<input type="hidden" name="job_id" value="'.$job_id.'">');

if($job['zip_file'] == true) {
?>
<input type="submit" name="actSubmit" value="delete_zip">
<?php } else { ?>
<input type="submit" name="actSubmit" value="create_zip">

<?php }

if($job['xlsx_dir'] == true) {
?>
<input type="submit" name="actSubmit" value="delete_xlsx">
<?php } else { ?>
<input type="submit" name="actSubmit" value="create_xlsx">

<?php } ?>


<input type="submit" name="actSubmit" value="refresh_import">
<input type="submit" name="actSubmit" value="delete_job">

<input type="submit" name="actSubmit" value="actSubmit">

</td>
</tr>
<tr>
    <td>
    <?
               
	if(file_exists($job["zip_file"]) == true) {
		output( '  <a href="'.__URL_HOME__.'/download.php?job_id='.$job['job_id'].'"> Zip File </a>');
	}
	 if(is_dir($job["xlsx_dir"] ) == true ) {
		output( '  <a href="'.__URL_HOME__.'/view.php?action=list&job_id='.$job['job_id'].'"> View Files</a>');
	}
    ?>
</td>
</tr>
</table>

</main>

<?php
include __LAYOUT_FOOTER__;
?>