<?php
require_once(".config.inc.php");

$job_id=$_REQUEST['job_id'];

$job = get_Job($job_id);

if(key_exists("actSubmit",$_REQUEST))
{
	if($_REQUEST['actSubmit'] == "confirm")
	{

		delete_xlsx($job_id);   
		delete_zip($job_id);
		delete_job($job_id);

	}
	echo   JavaRefresh(__URL_PATH__."/home.php");
	exit;
}

define('TITLE', "Media Job editor");
include __LAYOUT_HEADER__;
$form_url = __URL_PATH__."/delete_job.php";



?>

<main role="main" class="container">
<table>
<tr>
    <td>
 <?php
						
		$form = new Formr\Formr();
		$hidden = [	"job_id" => $job_id ];				
		$form->open("",'',$form_url ,'post','',$hidden);
		echo output("Are you sure you want to delete this job <br>");
		$form->input_submit('actSubmit','',"Go Back",'','class="button"');

		$form->input_submit('actSubmit','',"confirm",'','class="button"');

		$form->close();
 
 ?>
</td>
</tr>
</table>

</main>

<?php
include __LAYOUT_FOOTER__;
?>