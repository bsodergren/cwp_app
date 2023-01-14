<?php

$custom_js = template::echo("form_edit/javascript",['URL_LAYOUT' => __URL_LAYOUT__]);
$onLoad = ' onbeforeunload="refreshAndClose();" onLoad="doLoad(00)" ';
define('NO_NAV',true);


$job_id = $_REQUEST['job_id'];
$form_number = $_REQUEST['form_number'];
$job  = $connection->fetch('SELECT * FROM media_job WHERE job_id = ?', $job_id);

$media = new Media($job);


$first_form = $media->get_first_form();

if ($form_number != $first_form) {

	$form_req = "&form_number=" . $form_number - 1;
}

$form_url = __URL_HOME__ . "/form.php?job_id=" . $job_id . $form_req;


if (key_exists("Return", $_REQUEST)) {
	include __LAYOUT_HEADER__;

	exit;
}

if (key_exists("Reset", $_REQUEST)) 
{
	$media->delete_form($form_number);
	$pdfObj = new PDFImport($media->pdf_fullname, $media->job_id,$form_number);

	$media->add_form_data($form_number, $pdfObj->form[$form_number]);
	include __LAYOUT_HEADER__;

	exit;
}


if (key_exists("submit", $_REQUEST)) {

//	$refresh_url = __URL_HOME__ . "/form_edit.php?job_id=" . $job_id . "&form_number=" . $form_number . "";


	foreach ($_REQUEST as $key => $value) {
		logger("REquest $key", $value, "formEdit.log");

		if ($key == "job_id") {
			continue;
		}
		if ($key == "form_number") {
			continue;
		}
		if ($key == "submit") {
			continue;
		}

		list($id, $action) = explode("_", $key);

		if ($deleted_id == $id) {
			continue;
		}

		unset($data);
		if ($value != '') {
			switch ($action) {
				case "delete":

					$media->deleteFormRow($id);
					include __LAYOUT_HEADER__;
					exit;
					break;

				case "split":
					$form_data =  $media->getFormRow($id);
					$form_data['count'] = ($form_data['count'] / 2);
					$media->updateFormRow($id, $form_data);
					unset($form_data['id']);
					$media->addFormRow($form_data);
					include __LAYOUT_HEADER__;

					exit;
					break;

				case "formletter":
					$data = array('form_letter' => strtoupper($value));
					break;

				case "facetrim":
					$data = array('face_trim' => $value);
					break;

				case "former":
					$data = array('former' => $value);
					break;

				case "pcscount":
					$value = str_replace("*","x",$value);
					if (str_contains($value, "x")) {
						list($x, $n) = explode("x", $value);
						$value = $x * $n;
					}
					if (str_contains($value, "/")) {
						list($x, $n) = explode("/", $value);
						$value = $x / $n;
					}
					$data = array('count' => $value);

					break;
			}

			if (isset($data)) {
				$media->updateFormRow($id, $data);
			}
		}
	}

	//$form_number--;
	//	myHeader(__URL_HOME__."/form.php?job_id=".$job_id."&form_number=".$form_number."");  
	//include __LAYOUT_FOOTER__;
//	HTMLDisplay::javaRefresh($form_url, 0);
}

include __LAYOUT_HEADER__;

exit;
