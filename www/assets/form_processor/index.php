<?php

$job_id = $_REQUEST['job_id'];
$job  = $connection->fetch('SELECT * FROM media_job WHERE job_id = ?', $job_id);

if (key_exists("process", $_REQUEST)) {
    define('REFRESH_URL', '/form.php?job_id=' . $job_id);
}

if (key_exists("create_xlsx", $_REQUEST)) {

    // Need output here
    $xlsx_array = build_xlsx_array($_REQUEST['job_id']);
    $keyidx = array_key_first($xlsx_array);

    $job_number = $xlsx_array[$keyidx]["job_number"];
    $pdf_file = $xlsx_array[$keyidx]["pdf_file"];

    write_xlsx_workbook($xlsx_array, $job_number, $pdf_file);
    $job['xlsx_dir'] = get_xlsx_directory($pdf_file, $job_number);
}

if (key_exists("delete_xlsx", $_REQUEST)) {
    delete_xlsx($job_id);
    delete_zip($job_id);
}
if (key_exists("create_zip", $_REQUEST)) {
    $zip_file = get_zip_filename($job['pdf_file'], $job['job_number'], '', true);

    zip_Workbooks($job['xlsx_dir'], $job_id, $zip_file);

    $job['zip_file'] = $zip_file;
}
if (key_exists("delete_zip", $_REQUEST)) {
    delete_zip($job_id);
}

if (key_exists("delete_job", $_REQUEST)) {
    define('REFRESH_URL', __URL_PATH__ . "/delete_job.php?job_id=" . $job_id);
}

if (key_exists("refresh_import", $_REQUEST)) {

    delete_xlsx($job_id);
    delete_zip($job_id);
    delete_form_data($job_id);
    $pdf_loc = get_pdf_directory($job['pdf_file'], $job['job_number']);
    add_new_media_drop($pdf_loc . "/" . $job['pdf_file'], $job['job_number']);
}

if (!defined("REFRESH_URL")) {
    define('REFRESH_URL', '/index.php');
}
