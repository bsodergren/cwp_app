<?php
require_once ".config.inc.php";
define('TITLE', APP_NAME);
include __LAYOUT_HEADER__;

$table = $explorer->table("media_job");
$results = $table->fetchAssoc('job_id');

$cnt = $table->count('*');

if ($cnt > 0) {
	foreach ($results as $k => $v) {
		unset($replacement);
		$url = __URL_HOME__ . "/form.php?job_id=" . $v['job_id'];
		$text_close = basename($v['pdf_file'], ".pdf");
		$text_job = "Job Number: " . $v['job_number'];

		$form = new Formr\Formr('', 'hush');
		$hidden = ["job_id" => $v['job_id']];
		$replacement['FORM_OPEN_HTML'] = $form->open("", '', __URL_HOME__ . "/action.php", 'post', '', $hidden);

		$class_create = 'class="btn btn-success"';
		$class_delete = 'class="btn btn-danger"';
		$class_normal = 'class="btn btn-primary"';
		$tb = $explorer->table("media_forms");
		$num_of_forms = $tb->where("job_id", $v['job_id'])->count('*');
		if ($num_of_forms == 0) {
			$pdisabled = ' disabled';
			$num_of_forms = '<input type="submit" name="actSubmit" value="Run Refresh Import" id="actSubmit" class="btn btn-danger">';
		} else {
			$pdisabled = '';
			$num_of_forms = "Number of Forms: " . $num_of_forms;
		}

		$replacement['TEXT_JOB'] = $text_job;
		$replacement['TEXT_CLOSE'] = $text_close;
		$replacement['NUM_OF_FORMS'] = $num_of_forms;


		$vdisabled = " disabled";
		$zdisabled = " disabled";

		$zip_file = get_zip_filename($v['pdf_file'], $v['job_number']);
		$xlsx_dir = get_xlsx_directory($v['pdf_file'], $v['job_number']);
		if ($v["xlsx_dir"] && is_dir($xlsx_dir) == true) {
			$vdisabled = "";
		}

		$replacement['FORM_BUTTONS_HTML'] =	$form->input_submit('actSubmit', '', "Process PDF Form", '', $class_normal . $pdisabled);
		//$form->input_submit('actSubmit', '', 'View Forms', '', class_normal.$vdisabled);

		if ($v['xlsx_dir'] == true) {
			$replacement['FORM_BUTTONS_HTML'] .= $form->input_submit('actSubmit', '', 'delete_xlsx', '', $class_delete);
		} else {
			$replacement['FORM_BUTTONS_HTML'] .= $form->input_submit('actSubmit', '', 'create_xlsx', '', $class_create . $pdisabled);
		}

		if ($v['zip_file'] == true) {
			$replacement['FORM_BUTTONS_HTML'] .= $form->input_submit('actSubmit', '', 'delete_zip', '', $class_delete);
		} else {
			if ($v['xlsx_dir'] == true) {
				$zdisabled = "";
			}

			$replacement['FORM_BUTTONS_HTML'] .= $form->input_submit('actSubmit', '', 'create_zip', '', $class_create . $zdisabled);
		}

		$replacement['FORM_BUTTONS_HTML'] .= $form->input_submit('actSubmit', '', 'refresh_import', '', $class_create);
		$replacement['FORM_BUTTONS_HTML'] .= $form->input_submit('actSubmit', '', 'delete_job', '', $class_delete);
		$replacement['FORM_CLOSE'] = $form->close();
		$template->template('index/job', $replacement);
	}

	$media_html = $template->return();

	$template->clear();

	$template->template('index/main', ['MEDIA_JOB_ROW' => $media_html]);

	$template->render();
}



include __LAYOUT_FOOTER__;
