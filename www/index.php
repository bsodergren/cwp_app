<?php
require_once ".config.inc.php";
define('TITLE', APP_NAME);
include __LAYOUT_HEADER__;

$table = $explorer->table("media_job");
$results = $table->fetchAssoc('job_id');

$cnt = $table->count('*');
?>

<main role="main" class="mycontainer">
	<table class="blueTable">
		<thead>
			<tr>
				<th>Media</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ($cnt > 0) {

				foreach ($results as $k => $v) {
					$url = __URL_HOME__ . "/form.php?job_id=" . $v['job_id'];
					$text_close = basename($v['pdf_file'], ".pdf");
					$text_job = "Job Number:" . $v['job_number'];

					$form = new Formr\Formr();
					$hidden = ["job_id" => $v['job_id']];
					$form->open("", '', __URL_HOME__ . "/action.php", 'post', '', $hidden);

					$class_create = 'class="btn btn-success"';
					$class_delete = 'class="btn btn-danger"';
					$class_normal = 'class="btn btn-primary"';
					$tb = $explorer->table("media_forms");
					$num_of_forms = $tb->where("job_id", $v['job_id'])->count('*');

			?>
					<tr id="RedHead">
						<td>
							<div class="row mb-1">
								<div class="col text-nowrap "><?php echo $text_job; ?></div>
								<div class="col text-nowrap  text-md"><?php echo $text_close; ?></div>
								<div class="col text-end"> Number of Forms:<?php echo $num_of_forms; ?></div>
							</div>
							<div class="row mb-2">
								<div class="container btn-group btn-group-lg" role="group">
									<?php
									$vdisabled = " disabled";
									$zdisabled = " disabled";

									$zip_file = get_zip_filename($v['pdf_file'], $v['job_number']);
									$xlsx_dir = get_xlsx_directory($v['pdf_file'], $v['job_number']);

									$form->input_submit('actSubmit', '', "Process PDF Form", '', $class_normal);

									if ($v["xlsx_dir"] && is_dir($xlsx_dir) == true) {
										$vdisabled = "";
									}

									//$form->input_submit('actSubmit', '', 'View Forms', '', class_normal.$vdisabled);

									if ($v['xlsx_dir'] == true) {
										$form->input_submit('actSubmit', '', 'delete_xlsx', '', $class_delete);
									} else {
										$form->input_submit('actSubmit', '', 'create_xlsx', '', $class_create);
									}

									if ($v['zip_file'] == true) {
										$form->input_submit('actSubmit', '', 'delete_zip', '', $class_delete);
									} else {
										if ($v['xlsx_dir'] == true) {
											$zdisabled = "";
										}

										$form->input_submit('actSubmit', '', 'create_zip', '', $class_create . $zdisabled);
									}

									$form->input_submit('actSubmit', '', 'refresh_import', '', $class_create);
									$form->input_submit('actSubmit', '', 'delete_job', '', $class_delete);
									$form->close();

									?>
								</div>
							</div>
						</td>

					</tr>
				<?php  } ?>

			<?php

			}

			?>
		</tbody>
	</table>
</main>
<?php include __LAYOUT_FOOTER__;  ?>